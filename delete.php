<!DOCTYPE html>
<html>
<head>
    <title>Delete Sleep Data</title>
</head>
<body>
    <h2>Delete Sleep Data</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
        <label for="date">Select Date:</label>
        <input type="date" id="date" name="date" required><br><br>
        <input type="submit" name="delete_sleep_data" value="Delete Sleep Data">
    </form>

    <?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $loggedInUserID = $_SESSION['UId'];

    $conn = oci_connect("ora_yifeil05", "a88226840", "dbhost.students.cs.ubc.ca:1522/stu");

    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    $userID = $loggedInUserID; // or replace this with the desired user ID
    $dateToDelete = date("d-M-y", strtotime($_POST['date']));

    echo "<p>" . $dateToDelete . "</p>";
   // echo $userID;

    $checkExistingData = "SELECT COUNT(*) AS data_count FROM SleepTracking WHERE UserID = :userID AND \"Date\" = :dateToDelete";
    
    $stmtCheck = oci_parse($conn, $checkExistingData);
    
    oci_bind_by_name($stmtCheck, ":userID", $userID);
    oci_bind_by_name($stmtCheck, ":dateToDelete", $dateToDelete);
    
    oci_execute($stmtCheck);
    $data = oci_fetch_assoc($stmtCheck);
    $dataCount = $data['DATA_COUNT'];

   // echo "Data Count: " . $dataCount; // Output the count to check if data exists for the provided date and user ID

    if ($dataCount > 0) {
        $deleteSleepData = "DELETE FROM SleepTracking WHERE UserID = :userID AND \"Date\" = :dateToDelete";
        $stmtDelete = oci_parse($conn, $deleteSleepData);
        
        oci_bind_by_name($stmtDelete, ":userID", $userID);
        oci_bind_by_name($stmtDelete, ":dateToDelete", $dateToDelete);

        $execute = oci_execute($stmtDelete);
        
        if ($execute) {
            echo "Sleep data for User ID: $userID on $dateToDelete has been deleted successfully.";
        } else {
            $e = oci_error($stmtDelete);
            echo "Error deleting sleep data: " . htmlentities($e['message'], ENT_QUOTES);
        }
    } else {
        echo "There's no sleeping tracking data for your input date.";
    }

    oci_free_statement($stmtCheck);
  //  oci_free_statement($stmtDelete);
    oci_close($conn);
}
?>

</body>
</html>