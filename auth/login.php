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

    <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-4">

                <div class="card shadow p-4">

                    <h2 class="text-center text-primary">
                        User Login
                    </h2>

                    <hr>

                    <form method="POST">

                        <input 
                        type="text"
                        name="username"
                        class="form-control"
                        placeholder="Enter Username"
                        required>

                        <br>

                        <input 
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Enter Password"
                        required>

                        <br>

                        <button 
                        type="submit"
                        name="login"
                        class="btn btn-primary w-100">

                            Login

                        </button>

                    </form>

                    <br>

                    <p class="text-danger text-center">
                        <?php echo $message; ?>
                    </p>

                    <a href="register.php">
                        Create New Account
                    </a>

                </div>

            </div>

        </div>

    </div>

</body>
</html>