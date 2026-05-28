<?php

require_once 'classes/Database.php';

$db = new Database();

$conn = $db->connect();

if($conn){

    echo "Database Connected Successfully";

}

?>