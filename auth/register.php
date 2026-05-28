<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../classes/Database.php';
require_once '../classes/Encryption.php';

$message = "";

if(isset($_POST['register'])){

    $username = trim($_POST['username']);

    $password = trim($_POST['password']);

    if(!empty($username) && !empty($password)){

        // Hash user login password
        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        // Generate unique AES key
        $userKey = openssl_random_pseudo_bytes(32);

        // Encrypt AES key using login password
        $encryptedKey = Encryption::encrypt(
            base64_encode($userKey),
            $password
        );

        // Database connection
        $db = new Database();

        $conn = $db->connect();

        // Check if username already exists
        $checkSql = "SELECT id FROM users WHERE username = ?";

        $checkStmt = $conn->prepare($checkSql);

        $checkStmt->execute([$username]);

        if($checkStmt->rowCount() > 0){

            $message = "Username already exists";

        } else {

            // Insert new user
            $sql = "INSERT INTO users
            (username, password_hash, encrypted_key)
            VALUES (?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $result = $stmt->execute([
                $username,
                $hashedPassword,
                $encryptedKey
            ]);

            if($result){

                $message = "Registration Successful";

            } else {

                $message = "Registration Failed";

            }

        }

    } else {

        $message = "All fields are required";

    }

}

?>

<!DOCTYPE html>
<html>
<head>

    <title>User Registration</title>

    <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-5">

                <div class="card shadow p-4">

                    <h2 class="text-center text-primary">
                        User Registration
                    </h2>

                    <hr>

                    <form method="POST">

                        <label>Username</label>

                        <input 
                        type="text"
                        name="username"
                        class="form-control"
                        placeholder="Enter Username"
                        required>

                        <br>

                        <label>Password</label>

                        <input 
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Enter Password"
                        required>

                        <br>

                        <button 
                        type="submit"
                        name="register"
                        class="btn btn-success w-100">

                            Register

                        </button>

                    </form>

                    <br>

                    <p class="text-center text-danger">
                        <?php echo $message; ?>
                    </p>

                    <div class="text-center">

                        <a href="login.php">
                            Already have an account? Login
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>
</html>