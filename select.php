<!DOCTYPE html>
<html>
<head>
    <title>Meal Search</title>
</head>
<body>
    <h2>Search for Meals</h2>
    <form method="post" action="select.php"> 
        <!-- The form submits to the same page -->
        <label for="mealType">Meal Type:</label>
        <input type="text" id="mealType" name="mealType">
        <br><br>
        <label for="logic1">Select Logic (Meal Type & Calories):</label>
        <select id="logic1" name="logic1">
            <option value="AND">AND</option>
            <option value="OR">OR</option>
        </select>
        <br><br>
        <label for="calories">Maximum Calories:</label>
        <input type="number" id="calories" name="calories">
        <br><br>
        <label for="logic2">Select Logic (Calories & Description):</label>
        <select id="logic2" name="logic2">
            <option value="AND">AND</option>
            <option value="OR">OR</option>
        </select>
        <br><br>
        <label for="descriptionWord">Description Contains:</label>
        <input type="text" id="descriptionWord" name="descriptionWord">
        <br><br>
        <input type="submit" value="Search">
    </form>

    <?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

    session_start();

    $loggedInUserID = $_SESSION['UId'];

    // Establish Oracle database connection
    $db_conn = oci_connect("ora_yifeil05", "a88226840", "dbhost.students.cs.ubc.ca:1522/stu");

    if ($db_conn) {
        echo "Database is connected";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $mealType = isset($_POST['mealType']) ? $_POST['mealType'] : null;
            $calories = isset($_POST['calories']) ? $_POST['calories'] : null;
            $descriptionWord = isset($_POST['descriptionWord']) ? $_POST['descriptionWord'] : null;
            $logicOperator2 = $_POST['logic2'];


            $sql = "SELECT Meal.* FROM Meal
                INNER JOIN DietPlanInFitnessPlan ON Meal.DietID = DietPlanInFitnessPlan.DietID
                INNER JOIN FitnessPlan ON DietPlanInFitnessPlan.PlanID = FitnessPlan.PlanID
                WHERE FitnessPlan.UserID = $loggedInUserID"; 

            $sqlConditions = [];

            if ($mealType !== '' && $calories !== '') {
                $logicOperator1 = $_POST['logic1'];
                $sqlConditions[] = "(Meal.MealType = '$mealType' $logicOperator1 Meal.Calories <= $calories)";
            } elseif ($mealType !== '') {
                $sqlConditions[] = "Meal.MealType = '$mealType'";
            } elseif ($calories !== '') {
                $sqlConditions[] = "Meal.Calories <= $calories";
            }
            
            if ($descriptionWord !== '') {
                $logicOperator2 = $_POST['logic2'];
                $sqlConditions[] = "(Meal.MealDescription LIKE '%$descriptionWord%')";
            }
            
            if (!empty($sqlConditions)) {
                $sql .= ' AND (' . implode(" $logicOperator2 ", $sqlConditions) . ')';
            }

            $result = oci_parse($db_conn, $sql);
            oci_execute($result);

            // Flag to track if any meals are found
            $mealsFound = false;

            // Display the results
            while ($row = oci_fetch_assoc($result)) {
                echo "<p>Meal ID: " . $row["MEALID"] . "</p>";
                echo "<p>Meal Type: " . $row["MEALTYPE"] . "</p>";
                echo "<p>Calories: " . $row["CALORIES"] . "</p>";
                echo "<p>Meal Description: " . $row["MEALDESCRIPTION"] . "</p>";
            //  echo "<p>" . $sql; // debugging
                echo "<hr>"; // Separate each meal with a horizontal line

                $mealsFound = true; // Set flag to true as meals are found
            }

            // Display message if no meals are found based on search criteria
            if (!$mealsFound) {
                echo "<p>No meals found based on your search criteria.</p>";
            }
        }

        oci_close($db_conn); 
        
    } else {
        echo "Cannot connect to Database";
        $e = OCI_Error(); 
        echo htmlentities($e['message']);
    }
    ?>
</body>
</html>
