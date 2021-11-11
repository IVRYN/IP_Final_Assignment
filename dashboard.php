<?php
session_start();
include ('authenticate.php');
include ("common.php");

$page_title     =   "Dashboard | Murni Bus Ticketing";
include ("includes/header.php");
?>

<div class="container">
    <div class="row g-3">
        <div class="col-sm-12">
            <h2>Booked tickets</h2>
        </div>
        <div class="col-sm-12">
            <?php user_booked_ticket($_SESSION['user_id']); ?>
        </div>
    </div>
</div>
