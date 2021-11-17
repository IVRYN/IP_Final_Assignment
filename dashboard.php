<?php
session_start();
include ('authenticate.php');
include ("common.php");

if (isset($_REQUEST['cancel_booking']))
{
    if ($_SESSION['authorization'] == "user")
        user_delete_booking($_SESSION['user_id'], $_REQUEST['cancel_booking']);
    else
        admin_delete_booking($_REQUEST['cancel_booking']);
}

$page_title     =   "Dashboard | Murni Bus Ticketing";
include ("includes/header.php");
?>

<div class="container">
    <div class="row g-3">
        <div class="col-sm-12">
            <h2>Booked tickets</h2>
            <?php
                display_errors();
                display_success();
            ?>
        </div>
        <?php
            if ($_SESSION['authorization'] == "user")
                user_booked_ticket_view($_SESSION['user_id']);
            else
                admin_user_booking_view();
        ?>
    </div>
</div>
