<?php

session_start();

require_once '../classes/Database.php';

$message = "";

if(isset($_POST['login'])){

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $db = new Database();

    $conn = $db->connect();

    $sql = "SELECT * FROM users WHERE username = ?";

    $stmt = $conn->prepare($sql);

    $stmt->execute([$username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user){

        if(password_verify(
            $password,
            $user['password_hash']
        )){

            $_SESSION['user_id'] = $user['id'];

            $_SESSION['username'] = $user['username'];

            header("Location: ../dashboard/home.php");

            exit();

        } else {

            $message = "Invalid Password";

        }

    } else {

        $message = "User Not Found";

    }

}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Login</title>

</head>
<body>

    <h2>User Login</h2>

    <form method="POST">

        <input 
            type="text"
            name="username"
            placeholder="Enter Username"
        >

        <br><br>

        <input 
            type="password"
            name="password"
            placeholder="Enter Password"
        >

        <br><br>

        <button type="submit" name="login">
            Login
        </button>

    </form>

    <br>

    <?php echo $message; ?>

</body>
</html>