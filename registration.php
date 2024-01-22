
<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
</head>
<body>
    
    <form action="registration.php" method="post">
    <h1>Registration</h1>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email Address:</label>
        <input type="text" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="height">Height:</label>
        <input type="number" id="height" name="height" required><br><br>

        <label for="weight">Weight:</label>
        <input type="number" id="weight" name="weight" required><br><br>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required><br><br>

        <h>This is the list of Trainers that are available:</h><br>
        <?php
            session_start();
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
            //$db_conn = oci_connect("ora_xuesnow", "a97541940", "dbhost.students.cs.ubc.ca:1522/stu");
            $db_conn = oci_connect("ora_yifeil05", "a88226840", "dbhost.students.cs.ubc.ca:1522/stu");
            $query = "SELECT * FROM Trainer";
            $stmt = oci_parse($db_conn, $query);
            oci_execute($stmt);
        
            while ($row = oci_fetch_assoc($stmt)) {
                echo "Trainer ID: " . $row["ID"] . " - Name: " . $row["NAME"] . " - Years Of Experience: " . $row["YEARSOFEXPERIENCE"] . " - Specialty: " . $row["SPECIALTY"] . "<br>";
            }
        
            oci_free_statement($stmt);
        ?>
        <br><h>Enter the ID Number of the trainer:</h><br>

        <label for="trainer">Trainer ID:</label>
        <input type="number" id="trainer" name="trainer" required><br><br>

        <input type="submit" value="Register">

        
    </form>
    <form action="registration.php" method="post">
        <button type="submit" name="button1">Login</button>
    </form>

    

    <?php
    if (isset($_POST['button1'])) {
        // Redirect to the first website
        header("Location: https://www.students.cs.ubc.ca/~xuesnow/project_k3m8h_r7d9d_r8o7r/login.php");
        exit();
    }
    function connectToDB() {
        global $db_conn;
        //$db_conn = oci_connect("ora_rkim21", "a47030713", "dbhost.students.cs.ubc.ca:1522/stu");
        //$db_conn = oci_connect("ora_xuesnow", "a97541940", "dbhost.students.cs.ubc.ca:1522/stu");
        $db_conn = oci_connect("ora_yifeil05", "a88226840", "dbhost.students.cs.ubc.ca:1522/stu");

        if ($db_conn) {
            //echo "Database is connected";
            return true;
        } else {
            echo "Cannot connect to Database";
            $e = OCI_Error(); 
            echo htmlentities($e['message']);
            return false;
        }
    }

    function goodEmail($email) {
        return !empty($email);
    }

    function userExists($db_conn, $email) {
        $sql = 'SELECT * FROM "User" WHERE EmailAddress = :email';
        $stid = oci_parse($db_conn, $sql);
        oci_bind_by_name($stid, ':email', $email);
        oci_execute($stid);
        $row = oci_fetch_array($stid, OCI_ASSOC);
        return is_array($row) && count($row) > 0;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // $id = $_POST["id"];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $height = $_POST["height"];
        $weight = $_POST["weight"];
        $dob = $_POST["dob"];
        $trainer = $_POST["trainer"];

        $conn = connectToDB();
        if ($conn) {
            if (userExists($db_conn, $email)) {
                echo "Email already exists!</p>";
                echo "Registration failed!";
                oci_close($db_conn);
                exit();
            }

            $query = 'SELECT COUNT(*) FROM "User"';
    
            // Execute the query
            $stmt = oci_parse($db_conn, $query);
            oci_execute($stmt);
        
            // Fetch the result
            $rowss = oci_fetch_assoc($stmt);
            $newID = $rowss['COUNT(*)']+1;

            if(goodEmail($email)){
                $sql = 'INSERT INTO "User" (ID, Name, EmailAddress, Password, Height, Weight, DateOfBirth, TrainerID) VALUES (:id, :name, :email, :password, :height, :weight, TO_DATE(:dob, \'YYYY-MM-DD\'), :tid)';
                $stid = oci_parse($db_conn, $sql);

                oci_bind_by_name($stid, ':id', $newID);
                oci_bind_by_name($stid, ':name', $name);
                oci_bind_by_name($stid, ':email', $email);
                oci_bind_by_name($stid, ':password', $password);
                oci_bind_by_name($stid, ':height', $height);
                oci_bind_by_name($stid, ':weight', $weight);
                oci_bind_by_name($stid, ':dob', $dob);
                oci_bind_by_name($stid, ':tid', $trainer);

                if (oci_execute($stid)) {
                    // oci_execute($stid);
                    echo "Registration successful!";
                    // debug("Database is Connected");
                } else {
                    echo "Registration failed!";
                }
            }

            oci_close($db_conn);
        }
    }
    ?>


</body>
</html>