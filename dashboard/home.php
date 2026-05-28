<?php

session_start();

if(!isset($_SESSION['user_id'])){

    header("Location: ../auth/login.php");

    exit();

}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Dashboard</title>

</head>
<body>

    <h1>Welcome</h1>

    <h2>
        <?php echo $_SESSION['username']; ?>
    </h2>

    <br><br>

    <a href="add_password.php">
        Generate Password
    </a>

    <br><br>

    <a href="view_passwords.php">
        View Saved Passwords
    </a>

    <br><br>

    <a href="../auth/logout.php">
        Logout
    </a>

</body>
</html>