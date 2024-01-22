<!DOCTYPE html>
<html>
<head>
    <title>Join</title>
</head>
<body>
<h2>Looking for the calories of all the plans containing a specific exercise?</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="exercise_name">Enter Exercise Name:</label>
    <input type="text" id="exercise_name" name="exercise_name" required>
    <button type="submit">Search</button>
</form>

<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $userInput = $_POST['exercise_name'];

    $userID = $_SESSION['UId'];
 
    $conn = oci_connect("ora_yifeil05", "a88226840", "dbhost.students.cs.ubc.ca:1522/stu");

    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    // SQL query to join Exercise and FitnessPlan tables based on user input exercise and UserID
    $query = "SELECT FitnessPlan.PlanID, FitnessPlan.ActualCalories
              FROM FitnessPlan
              JOIN PlanContainsExercise ON FitnessPlan.PlanID = PlanContainsExercise.PlanID
              JOIN Exercise ON PlanContainsExercise.ExerciseName = Exercise.ExerciseName
              WHERE Exercise.ExerciseName = :exercise_name
              AND FitnessPlan.UserID = :user_id";

     
    // echo $query;

    $statement = oci_parse($conn, $query);

    oci_bind_by_name($statement, ':exercise_name', $userInput);
    oci_bind_by_name($statement, ':user_id', $userID);

    oci_execute($statement);

    // Fetch the results
    $resultsFound = false;

    while ($row = oci_fetch_assoc($statement)) {
        $resultsFound = true;
        echo "<table><tr><th>PlanID</th><th>Actual Calories</th></tr>";
        echo "<tr><td>" . $row['PLANID'] . "</td><td>" . $row['ACTUALCALORIES'] . "</td></tr>";
    }

    echo "</table>";

    if ($resultsFound) {
        echo "<p>Here's the result.</p>";
    } else {
        echo "<p>No result found.</p>";
    }

    oci_free_statement($statement);
    oci_close($conn);
}
?>

</body>
</html>

