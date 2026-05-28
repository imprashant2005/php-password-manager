<?php

session_start();

if(!isset($_SESSION['user_id'])){

    header("Location: ../auth/login.php");

    exit();

}

require_once '../classes/Database.php';
require_once '../classes/Encryption.php';

$passwords = [];

$message = "";

if(isset($_POST['view'])){

    $loginPassword = trim($_POST['login_password']);

    $db = new Database();

    $conn = $db->connect();

    // Get encrypted user key
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

        // Get saved passwords
        $passwordSql = "SELECT * FROM passwords 
        WHERE user_id = ?";

        $passwordStmt = $conn->prepare($passwordSql);

        $passwordStmt->execute([
            $_SESSION['user_id']
        ]);

        $passwords = $passwordStmt->fetchAll(PDO::FETCH_ASSOC);

    } else {

        $message = "Wrong Login Password";

    }

}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Saved Passwords</title>

</head>
<body>

    <h2>View Saved Passwords</h2>

    <form method="POST">

        <label>
            Enter Your Login Password:
        </label>

        <input 
            type="password"
            name="login_password"
            required
        >

        <br><br>

        <button type="submit" name="view">
            View Passwords
        </button>

    </form>

    <br>

    <?php echo $message; ?>

    <br><br>

    <table border="1" cellpadding="10">

        <tr>

            <th>Website</th>

            <th>Password</th>

            <th>Created At</th>

        </tr>

        <?php

        if(!empty($passwords)){

            foreach($passwords as $row){

                $decryptedPassword = Encryption::decrypt(
                    $row['encrypted_password'],
                    $userKey
                );

                echo "<tr>";

                echo "<td>" . 
                    htmlspecialchars(
                        $row['website_name']
                    ) . 
                "</td>";

                echo "<td>" . 
                    htmlspecialchars(
                        $decryptedPassword
                    ) . 
                "</td>";

                echo "<td>" . 
                    $row['created_at'] . 
                "</td>";

                echo "</tr>";

            }

        }

        ?>

    </table>

    <br><br>

    <a href="home.php">
        Back to Dashboard
    </a>

</body>
</html>