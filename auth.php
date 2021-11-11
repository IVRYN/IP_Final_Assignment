<?php
//  Authentication Guard
if (!isset($_SESSION['login']))
    header('Location: login.php');

//  Authorization Guard
//  Check if going into admin directory, must have admin session
?>
