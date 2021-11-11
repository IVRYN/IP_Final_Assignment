<?php
// Authorization guard
if ($_SESSION['authorization'] == 'user')
    header('Location: ../dashboard.php');

?>
