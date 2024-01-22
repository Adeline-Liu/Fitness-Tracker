<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <form action="login.php" method="post">
        <label for="email">Email Address:</label>
        <input type="text" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="submit">
    </form>

    <?php
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    $id = -1;
    

    function connectToDB() {
        // $username = 'ora_rkim21';
        // $password = 'a47030713';
        // $connection_string = 'dbhost.students.cs.ubc.ca:1522/stu'; // Example: 'localhost/orcl'
        //$conn = oci_connect("ora_xuesnow", "a97541940", "dbhost.students.cs.ubc.ca:1522/stu");
        $conn = oci_connect("ora_yifeil05", "a88226840", "dbhost.students.cs.ubc.ca:1522/stu");

        //$conn = oci_connect($username, $password, $connection_string);
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            return false;
        }
        return $conn;
    }

    function verifyLogin($conn, $email, $password) {
        global $id;
        $sql = 'SELECT * FROM "User" WHERE EmailAddress = :email AND Password = :password';

        $stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ':email', $email);
        oci_bind_by_name($stid, ':password', $password); 

        oci_execute($stid);
        $row = oci_fetch_array($stid, OCI_ASSOC);

        if (is_array($row) && count($row) > 0) {
            // Set the global variable $id to the user ID
            $id = $row['ID'];
            return true;
        } else {
            return false;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $password = $_POST["password"]; // Consider hashing the password

        $conn = connectToDB();
        if ($conn) {
            $isValid = verifyLogin($conn, $email, $password);
            oci_close($conn);

            if ($isValid) {
                // Redirect to dashboard
                //header("Location: https://www.students.cs.ubc.ca/~xuesnow/userUpdate.php?processed_data=" . urlencode($id));
                $_SESSION['UId'] = $id;
                header("Location: https://www.students.cs.ubc.ca/~xuesnow/project_k3m8h_r7d9d_r8o7r/button.php");
                //header("Location: https://www.students.cs.ubc.ca/~xuesnow/delete.php");
                exit();     
            } else {
                echo "Invalid email or password!";
                exit();
            }
            
            oci_close($conn);
        }
    }
    ?>
</body>
</html>
