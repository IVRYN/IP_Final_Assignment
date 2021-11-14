<?php
session_start();

include ('authenticate.php');
include ('common.php');

if ($_SESSION['authorization'] == "user")
{
    $user_id    =   $_SESSION['user_id'];

    $user_delete_booking_query  =   sprintf();
}
else
{
    if (isset($_REQUEST['delete_booking']))
    {
        $user_id     =   $_REQUEST['delete_booking'];

        array_push($success, "Booking has been deleted");
        header('Location: dashboard.php');
    }
}
?>
