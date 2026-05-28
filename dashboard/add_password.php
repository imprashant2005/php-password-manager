<?php

session_start();

if(!isset($_SESSION['user_id'])){

    header("Location: ../auth/login.php");

    exit();

}

require_once '../classes/Database.php';
require_once '../classes/PasswordGenerator.php';
require_once '../classes/Encryption.php';

$generatedPassword = "";
$message = "";

if(isset($_POST['generate'])){

    $uppercase = $_POST['uppercase'];
    $lowercase = $_POST['lowercase'];
    $numbers = $_POST['numbers'];
    $special = $_POST['special'];

    $generator = new PasswordGenerator();

    $generatedPassword = $generator->generatePassword(
        $uppercase,
        $lowercase,
        $numbers,
        $special
    );

}

if(isset($_POST['save'])){

    $website = trim($_POST['website']);

    $password = trim($_POST['password']);

    $loginPassword = trim($_POST['login_password']);

    $db = new Database();

    $conn = $db->connect();

    // Get user encrypted key
    $sql = "SELECT encrypted_key FROM users WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->execute([$_SESSION['user_id']]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Decrypt user AES key
    $userKey = Encryption::decrypt(
        $user['encrypted_key'],
        $loginPassword
    );

    if($userKey){

        // Encrypt password before saving
        $encryptedPassword = Encryption::encrypt(
            $password,
            $userKey
        );

        // Save into database
        $insert = "INSERT INTO passwords
        (user_id, website_name, encrypted_password)
        VALUES (?, ?, ?)";

        $insertStmt = $conn->prepare($insert);

        $result = $insertStmt->execute([
            $_SESSION['user_id'],
            $website,
            $encryptedPassword
        ]);

        if($result){

            $message = "Password Saved Successfully";

        } else {

            $message = "Failed To Save Password";

        }

    } else {

        $message = "Wrong Login Password";

    }

}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Password Manager</title>

</head>
<body>

    <h2>Password Generator</h2>

    <form method="POST">

        <label>Uppercase Letters:</label>

        <input type="number" name="uppercase" required>

        <br><br>

        <label>Lowercase Letters:</label>

        <input type="number" name="lowercase" required>

        <br><br>

        <label>Numbers:</label>

        <input type="number" name="numbers" required>

        <br><br>

        <label>Special Characters:</label>

        <input type="number" name="special" required>

        <br><br>

        <button type="submit" name="generate">
            Generate Password
        </button>

    </form>

    <br><br>

    <h3>Generated Password</h3>

    <form method="POST">

        <label>Website Name:</label>

        <input 
            type="text"
            name="website"
            required
        >

        <br><br>

        <label>Password:</label>

        <input 
            type="text"
            name="password"
            value="<?php echo $generatedPassword; ?>"
            required
        >

        <br><br>

        <label>Your Login Password:</label>

        <input 
            type="password"
            name="login_password"
            required
        >

        <br><br>

        <button type="submit" name="save">
            Save Password
        </button>

    </form>

    <br>

    <?php echo $message; ?>

    <br><br>

    <a href="home.php">
        Back to Dashboard
    </a>

</body>
</html>