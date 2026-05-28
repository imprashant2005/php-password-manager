<?php

require_once '../classes/Database.php';
require_once '../classes/Encryption.php';

$message = "";

if(isset($_POST['register'])){

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(!empty($username) && !empty($password)){

        // Hash login password
        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        // Generate unique AES key
        $userKey = openssl_random_pseudo_bytes(32);

        // Encrypt the AES key using user password
        $encryptedKey = Encryption::encrypt(
            base64_encode($userKey),
            $password
        );

        // Database connection
        $db = new Database();
        $conn = $db->connect();

        // Insert user
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

    } else {

        $message = "All fields are required";

    }

}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Register</title>

</head>
<body>

    <h2>User Registration</h2>

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

        <button type="submit" name="register">
            Register
        </button>

    </form>

    <br>

    <?php echo $message; ?>

</body>
</html>