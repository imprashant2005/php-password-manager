<?php

session_start();

if(!isset($_SESSION['user_id'])){

    header("Location: ../auth/login.php");

    exit();

}

require_once '../classes/PasswordGenerator.php';

$generatedPassword = "";

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

?>

<!DOCTYPE html>
<html>
<head>

    <title>Password Generator</title>

</head>
<body>

    <h2>Password Generator</h2>

    <form method="POST">

        <label>Uppercase Letters:</label>

        <input 
            type="number"
            name="uppercase"
            required
        >

        <br><br>

        <label>Lowercase Letters:</label>

        <input 
            type="number"
            name="lowercase"
            required
        >

        <br><br>

        <label>Numbers:</label>

        <input 
            type="number"
            name="numbers"
            required
        >

        <br><br>

        <label>Special Characters:</label>

        <input 
            type="number"
            name="special"
            required
        >

        <br><br>

        <button type="submit" name="generate">
            Generate Password
        </button>

    </form>

    <br>

    <h3>Generated Password:</h3>

    <input 
        type="text"
        value="<?php echo $generatedPassword; ?>"
        readonly
    >

    <br><br>

    <a href="home.php">
        Back to Dashboard
    </a>

</body>
</html>