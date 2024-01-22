<!DOCTYPE html>
<html>
<head>
    <title>User Page</title>
</head>
<body>
    
    <form action="userUpdate.php" method="post">
    <h1>Update</h1>

        <h>Enter the value you want to change, or leave it blank if you don't want to change it.</h><br><br>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name"><br><br>

        <label for="email">Email Address:</label>
        <input type="text" id="email" name="email"><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>

        <label for="height">Height:</label>
        <input type="number" id="height" name="height"><br><br>

        <label for="weight">Weight:</label>
        <input type="number" id="weight" name="weight"><br><br>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob"><br><br>

        <h>This is the list of Trainers that are available:</h><br>
        <?php
            session_start();
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
            //$db_conn = oci_connect("ora_xuesnow", "a97541940", "dbhost.students.cs.ubc.ca:1522/stu");
            $db_conn = oci_connect("ora_yifeil05", "a88226840", "dbhost.students.cs.ubc.ca:1522/stu");
            $query = 'SELECT * FROM Trainer';
            $stmt = oci_parse($db_conn, $query);
            oci_execute($stmt);
        
            while ($row = oci_fetch_assoc($stmt)) {
                echo "Trainer ID: " . $row["ID"] . " - Name: " . $row["NAME"] . " - Years Of Experience: " . $row["YEARSOFEXPERIENCE"] . " - Specialty: " . $row["SPECIALTY"] . "<br>";
            }
        
            oci_free_statement($stmt);
        ?>
        <br><h>Enter the ID Number of the trainer:</h><br>

        <label for="trainer">Trainer ID:</label>
        <input type="number" id="trainer" name="trainer"><br><br>

        <input type="submit" value="Save">
    </form>

    <?php
    //session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    

    if (isset($_SESSION['UId'])) {
        global $id;
        $id = $_SESSION['UId'];
        echo "UserID: $id</p>";
    }

    function connectToDB() {
        global $db_conn;
        //$db_conn = oci_connect("ora_rkim21", "a47030713", "dbhost.students.cs.ubc.ca:1522/stu");
        $db_conn = oci_connect("ora_yifeil05", "a88226840", "dbhost.students.cs.ubc.ca:1522/stu");
        //$db_conn = oci_connect("ora_xuesnow", "a97541940", "dbhost.students.cs.ubc.ca:1522/stu");

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

    function trainerExist($db_conn,$trainer){
        $query = 'SELECT COUNT(*) FROM Trainer';
        $stmt = oci_parse($db_conn, $query);
        oci_execute($stmt);
        $rowss = oci_fetch_assoc($stmt);
        $TRange = $rowss['COUNT(*)'];

        if ($trainer >= 1 && $trainer <= $TRange) {
            return false;
        } else {
            return true;
        }
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $height = $_POST["height"];
        $weight = $_POST["weight"];
        //$dob = $_POST["dob"];
        $trainer = $_POST["trainer"];
        $dob = date("d-M-y", strtotime($_POST['dob']));

        $conn = connectToDB();
        if ($conn) {
            $existQuery = 'SELECT * FROM "User" WHERE ID = :id';
            $existStid = oci_parse($db_conn, $existQuery);
            oci_bind_by_name($existStid, ':id', $id);
            oci_execute($existStid);

            $row = oci_fetch_assoc($existStid);
            $existingName = $row['NAME'];
            $existingEmail = $row['EMAILADDRESS'];
            $existingPassword = $row['PASSWORD'];
            $existingHeight = $row['HEIGHT'];
            $existingWeight = $row['WEIGHT'];
            $existingdob = $row['DATEOFBIRTH'];
            $existingTrianer = $row['TRAINERID'];

            if (userExists($db_conn,$email)) {
                echo "Email already exists! Update Fail.";
                oci_close($db_conn);
                exit();
            }

            //echo "existingemail: $existingEmail</p>";
            if(empty($name)){
                $name = $existingName;
            }
            if(empty($email)){
                $email = $existingEmail;
            }
            if(empty($password)){
                $password = $existingPassword;
            }
            if(empty($height)){
                $height = $existingHeight;
            }
            if(empty($weight)){
                $weight = $existingWeight;
            }
            if(empty($dob)){
                $dob = $existingdob;
            }
            if(empty($trainer)){
                $trainer = $existingTrianer;
            }

            if (trainerExist($db_conn,$trainer)) {
                echo "This trainer is not exist! Update Fail.";
                oci_close($db_conn);
                exit();
            }

            $sql = 'UPDATE "User" SET Name = :name, EmailAddress = :email, Password = :password, Height = :height, Weight = :weight, DateOfBirth = :dob, TrainerID =  :tid WHERE ID = :id';
            $stid = oci_parse($db_conn, $sql);
            oci_bind_by_name($stid, ':id', $id);
            oci_bind_by_name($stid, ':name', $name);
            oci_bind_by_name($stid, ':email', $email);
            oci_bind_by_name($stid, ':password', $password);
            oci_bind_by_name($stid, ':height', $height);
            oci_bind_by_name($stid, ':weight', $weight);
            oci_bind_by_name($stid, ':dob', $dob);
            oci_bind_by_name($stid, ':tid', $trainer);

            if (oci_execute($stid)) {
                // oci_execute($stid);
                echo "Update successful!</p>";
                oci_close($db_conn);
                exit();
            } else {
                echo "Update failed!";
                oci_close($db_conn);
                exit();
            }

            oci_close($db_conn);
        }
    }
    ?>


</body>
</html>