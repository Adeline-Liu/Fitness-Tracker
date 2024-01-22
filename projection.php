<!DOCTYPE html>
<html>
<head>
    <title>Database Projection</title>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        
        <label for="attributes">List of Tables:</label><br>
        <?php
        $conn = oci_connect("ora_yifeil05", "a88226840", "dbhost.students.cs.ubc.ca:1522/stu");
        //$conn = oci_connect("ora_xuesnow", "a97541940", "dbhost.students.cs.ubc.ca:1522/stu");
                $tableQuery = oci_parse($conn, "SELECT table_name FROM user_tables");
                oci_execute($tableQuery);
                $tables = array();
                
                //table names
                while ($row = oci_fetch_assoc($tableQuery)) {
                    $tables[] = $row['TABLE_NAME'];
                }
                
                //create drop-down menus
                foreach ($tables as $tableName) {
                    echo "<label for='$tableName'>$tableName:</label>";
                    echo "<select id='$tableName'>";
                
                    //get column names for the current table
                    $columnQuery = oci_parse($conn, "SELECT column_name FROM user_tab_columns WHERE table_name = '$tableName'");
                    oci_execute($columnQuery);
                
                    //column names and add to the drop-down menu
                    while ($column = oci_fetch_assoc($columnQuery)) {
                        if($tableName == "TRAINER" && ($column['COLUMN_NAME'] == "PASSWORD" || $column['COLUMN_NAME'] == "EMAILADDRESS")){
                            
                        }else {
                            echo "<option value='{$column['COLUMN_NAME']}'>{$column['COLUMN_NAME']}</option>";
                        }
                    }
                    echo "</select><br><br>";
                }
                oci_close($conn);
        ?>
        <br><br>
        <label for="tableName">Enter Table Name:</label>
        <input type="text" id="tableName" name="tableName"><br><br>
        <label for="attributes">Enter Attribute(s) (comma-separated, no space):</label>
        <input type="text" id="attributes" name="attributes"><br><br>
        <input type="submit" value="Show Data Table"><br><br>
    </form>

    <?php
session_start();
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
if (isset($_SESSION['UId'])) {
    global $id;
    $id = $_SESSION['UId'];
    echo "UserID: $id</p>";
}

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $tableName = $_POST['tableName'];
        $attributes = $_POST['attributes'];

        $conn = oci_connect("ora_yifeil05", "a88226840", "dbhost.students.cs.ubc.ca:1522/stu");
        //$conn = oci_connect("ora_xuesnow", "a97541940", "dbhost.students.cs.ubc.ca:1522/stu");

        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        // // get table names
        // $query = "SELECT table_name FROM user_tables";
        // $stmt = oci_parse($conn, $query);
        // oci_execute($stmt);

        // // display table names
        // while ($row = oci_fetch_assoc($stmt)) {
        //     echo $row['TABLE_NAME'] . "<br>";
        // }
        if($tableName != "\"User\""){
            $sql = "SELECT $attributes FROM $tableName";
            $stmt = oci_parse($conn, $sql);
            oci_execute($stmt);

            // Display data in table 
            echo "<br><br><table border='1'><tr>";
            $attrsArray = explode(",", $attributes);

            // Display attribute names
            foreach ($attrsArray as $attr) {
                echo "<th>$attr</th>";
            }
            echo "</tr>";

            // Output data
            while ($row = oci_fetch_assoc($stmt)) {
                echo "<tr>";
                foreach ($attrsArray as $attr) {
                    if($attr != "PASSWORD" && $attr != "EMAILADDRESS"){
                        echo "<td>" . $row[$attr] . "</td>";
                    }
                }
                echo "</tr>";
            }
            echo "</table>";
        }
         else {
            $sql = "SELECT $attributes FROM $tableName WHERE id = $id";
            $stmt = oci_parse($conn, $sql);
            oci_execute($stmt);

            // Display data in table 
            echo "<br><br><table border='1'><tr>";
            $attrsArray = explode(",", $attributes);

            // Display attribute names
            foreach ($attrsArray as $attr) {
                echo "<th>$attr</th>";
            }
            echo "</tr>";

            // Output data
            while ($row = oci_fetch_assoc($stmt)) {
                echo "<tr>";
                foreach ($attrsArray as $attr) {
                    echo "<td>" . $row[$attr] . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }
        oci_free_statement($stmt);
        oci_close($conn);
    }
    ?>
</body>
</html>