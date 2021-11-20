<?php
//  Authentication Guard
if (!isset($_SESSION['login']))
    header('Location: login.php');

?>
