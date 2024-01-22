<!DOCTYPE html>
<html>
<head>
    <title>Choose Function Page</title>
</head>

<body>
    <form method="post" action="">
        <button type="submit" name="button1">Update User information</button><br><br>
        <button type="submit" name="button2">Delete Sleep Tracking Date</button><br><br>
        <button type="submit" name="button3">Meal Search</button><br><br>
        <button type="submit" name="button4">Calories of all the plans containing a specific exercise</button><br><br>
        <button type="submit" name="button5">Health Analytics</button><br><br>
        <button type="submit" name="button6">Information View</button><br><br>
    </form>
    <?php
    session_start();
    if (isset($_SESSION['UId'])) {
        global $id;
        $id = $_SESSION['UId'];
        echo "UserID: $id</p>";
    }
    if (isset($_POST['button1'])) {
    // Redirect to the first website
        header("Location: https://www.students.cs.ubc.ca/~xuesnow/project_k3m8h_r7d9d_r8o7r/userUpdate.php");
        exit();
    } elseif (isset($_POST['button2'])) {
        header("Location: https://www.students.cs.ubc.ca/~xuesnow/project_k3m8h_r7d9d_r8o7r/delete.php");
        exit();
    } elseif (isset($_POST['button3'])) {
        header("Location: https://www.students.cs.ubc.ca/~xuesnow/project_k3m8h_r7d9d_r8o7r/select.php");
        exit();
    } elseif (isset($_POST['button4'])) {
        header("Location: https://www.students.cs.ubc.ca/~xuesnow/project_k3m8h_r7d9d_r8o7r/join.php");
        exit();
    }
    elseif (isset($_POST['button5'])) {
        header("Location: https://www.students.cs.ubc.ca/~xuesnow/project_k3m8h_r7d9d_r8o7r/aggregation.php");
        exit();
    }
    elseif (isset($_POST['button6'])) {
        header("Location: https://www.students.cs.ubc.ca/~xuesnow/project_k3m8h_r7d9d_r8o7r/projection.php");
        exit();
    }
    ?>


</body>

</html>