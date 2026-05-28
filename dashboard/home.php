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

    <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="card shadow p-4">

            <h1 class="text-center text-primary">
                Password Manager Dashboard
            </h1>

            <hr>

            <h3>
                Welcome,
                <?php echo $_SESSION['username']; ?>
            </h3>

            <div class="mt-4">

                <a 
                href="add_password.php"
                class="btn btn-success me-2">

                    Generate & Save Password

                </a>

                <a 
                href="view_passwords.php"
                class="btn btn-info me-2">

                    View Saved Passwords

                </a>

                <a 
                href="../auth/logout.php"
                class="btn btn-danger">

                    Logout

                </a>

            </div>

        </div>

    </div>

</body>
</html>