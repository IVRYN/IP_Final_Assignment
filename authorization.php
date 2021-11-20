<?php
if ($_SESSION['authorization'] == 'admin')
    header('Location: dashboard.php');
?>
