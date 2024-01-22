<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 800px;
            margin: auto;
            padding-top: 20px;
        }

        .results-table {
            margin-top: 20px;
        }

        .form-group {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group button {
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Health Analytics</h2>

        <div class="form-group">
            <form action="aggregation.php" method="post">
                <button type="submit" class="btn btn-primary" name="action" value="group_by">Meals per Category</button>
                <button type="submit" class="btn btn-secondary" name="action" value="having">Exercises with Average Calorie Burn Over 200</button>
                <button type="submit" class="btn btn-success" name="action" value="nested">Total Calories per Diet Plan</button>
                <button type="submit" class="btn btn-info" name="action" value="division">Users that achieved all Goals</button>
            </form>
        </div>


        <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();

    if (isset($_SESSION['UId'])) {
        global $id;
        $id = $_SESSION['UId'];
        echo "UserID: $id</p>";
    }

    function connectToDB() {
        global $db_conn;
        //$db_conn = oci_connect("ora_rkim21", "a47030713", "dbhost.students.cs.ubc.ca:1522/stu");
        //$db_conn = oci_connect("ora_xuesnow", "a97541940", "dbhost.students.cs.ubc.ca:1522/stu");
        $db_conn = oci_connect("ora_yifeil05", "a88226840", "dbhost.students.cs.ubc.ca:1522/stu");

        if ($db_conn) {
            //echo "Database is connected</p>";
            return true;
        } else {
            echo "Cannot connect to Database</p>";
            $e = OCI_Error(); 
            echo htmlentities($e['message']);
            return false;
        }
    }

    function userExists($db_conn, $email) {
        $sql = 'SELECT * FROM "User" WHERE EmailAddress = :email';
        $stid = oci_parse($db_conn, $sql);
        oci_bind_by_name($stid, ':email', $email);
        oci_execute($stid);
        $row = oci_fetch_array($stid, OCI_ASSOC);
        return is_array($row) && count($row) > 0;
    }

    // global $result;
    // $result = '';
    
        // var_dump($_POST);
        $conn = connectToDB();
        if ($conn) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
            switch ($_POST['action']) {
                case 'group_by':
                    
                    $query = 'SELECT MealType, Count(*) as MEALCOUNT From Meal GROUP BY MealType';
                    $stid = oci_parse($db_conn, $query);
                    oci_execute($stid);

                    $result = "<h3>Meals per Category:</h3>";
                    $result .= "<table border='1'><tr><th>Meal Type</th><th>Count</th></tr>";

                    while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
                        
                        $result .= "<tr><td>" . htmlspecialchars($row['MEALTYPE']) . "</td>";
                        $result .= "<td>" . htmlspecialchars($row['MEALCOUNT']) . "</td></tr>";
                    }

                    $result .= "</table>";
                    // var_dump($result); debugg when front end is not working
                    break;
                case 'having':
                    $query = 'SELECT ExerciseName, AVG(CALORIESBURNED) as AVG_CALORIES From EXERCISE GROUP BY DURATION, ExerciseName HAVING AVG(CALORIESBURNED) > (200)';
                    $stid = oci_parse($db_conn, $query);
                    oci_execute($stid);

                    $result = "<h3>Exercises with Average Calorie Burn Over 200:</h3>";
                    $result .= "<table border='1'><tr><th>Exercise Name</th><th>Average Calories Burned</th></tr>";

                    while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
                        $result .= "<tr><td>" . htmlspecialchars($row['EXERCISENAME']) . "</td>";
                        $result .= "<td>" . htmlspecialchars($row['AVG_CALORIES']) . "</td></tr>";
                    }

                    $result .= "</table>";
                    // var_dump($result); debugg when front end is not working
                    break;
                case 'nested':
                    // total amount of calories per diet plan
                    $query = 'SELECT DietPlan.DietName, SUM(Meal.Calories) AS TotalCalories FROM DietPlan JOIN Meal ON DietPlan.DietID = Meal.DietID GROUP BY DietPlan.DietName';
                    $stid = oci_parse($db_conn, $query);
                    oci_execute($stid);

                    $result = "<h3>Total Calories per Diet Plan:</h3>";
                    $result .= "<table border='1'><tr><th>Diet Name</th><th>Total Calories</th></tr>";
                
                    while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
                        $result .= "<tr><td>" . htmlspecialchars($row['DIETNAME']) . "</td>";
                        $result .= "<td>" . htmlspecialchars($row['TOTALCALORIES']) . "</td></tr>";
                    }
                
                    $result .= "</table>";
                    // var_dump($result); debugg when front end is not working
                    break;
                case 'division':
                    // Code for Division
                    // Returns Users that have achieved all their goals
                    $query = "SELECT U.Name
                    FROM \"User\" U
                    WHERE EXISTS (
                        SELECT 1
                        FROM Goal G
                        WHERE G.UserID = U.ID
                    )
                    AND NOT EXISTS (
                        SELECT G.GoalID
                        FROM Goal G
                        WHERE G.Status != 'Achieved'
                        AND G.UserID = U.ID
                    )";

                    $stid = oci_parse($db_conn, $query);
                    oci_execute($stid);

                    $result = "<h3>Users Who Have Achieved All Their Goals:</h3>";
                    $result .= "<table border='1'><tr><th>User Name</th></tr>";

                    while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
                    $result .= "<tr><td>" . htmlspecialchars($row['NAME']) . "</td></tr>";
                    }

                    $result .= "</table>";
                    // debug when front end is not working
                    // var_dump($result); 
                    break;    
            }
        }
        
    }
?>





        <div id="results" class="results-table">
            <?php
                if (isset($result)) {
                    echo $result;
                }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>