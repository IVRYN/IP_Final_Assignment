<?php
session_start();
include ('authenticate.php');
include ('common.php');

$page_title     =   "Edit Booking | Murni Bus Ticketing";

$user_id        =   $_SESSION['user_id'];

if (isset($_GET['edit_booking']))
    $booking_id     =   $_GET['edit_booking'];
else if (isset($_POST['edit_booking']))
    $booking_id     =   $_POST['edit_booking'];
else
    array_push($errors, "An errors has occured while requesting for the booking id");

$depart_date;
$depart_time;
$depart_station;
$dest_station;

// Get the data from database
$user_edit_query    =   sprintf("SELECT b.booking_id,
                                        depart_date,
                                        depart_time,
                                        depart_station,
                                        dest_station
                                 FROM customer_busbooking AS cb, busbooking AS b
                                 WHERE cb.customer_id = '%d'
                                 AND cb.booking_id = b.booking_id
                                 AND cb.booking_id = '%d'
                                 LIMIT 1",
                                 $user_id,
                                 $booking_id
                                );

$user_edit_result   =   mysqli_query($dbconnect, $user_edit_query);

if (mysqli_num_rows($user_edit_result) == 1)
{
    $booking_info   =   mysqli_fetch_assoc($user_edit_result);

    $depart_date    =   $booking_info['depart_date'];
    $depart_time    =   $booking_info['depart_time'];
    $depart_station =   $booking_info['depart_station'];
    $dest_station   =   $booking_info['dest_station'];
}

if (isset($_POST['confirm_edit']))
{
    user_edit_confirm($booking_id, $_POST['depart_date'], $_POST['depart_time'], $_POST['depart_station'], $_POST['dest_station']);
}

include ('includes/header.php');

?>

<div class="container">
    <div class="row justify-content-center mt-5 g-3">
        <div class="col-sm-12"><?php display_errors(); ?></div>
        <div class="col-sm-5">
            <div class="container py-5">
                <form class="row g-3 mb-3" action="edit_booking.php" method="post">
                    <div class="col-sm-6">
                        <label for="depart_date" class="form-label">Date of Departure</label>
                        <input id="depart_date" min="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d", strtotime('+1 year')); ?>" value="<?php echo $depart_date; ?>" type="date" name="depart_date" class="form-control"/>
                    </div>
                    <div class="col-sm-6">
                        <label for="depart_time" class="form-label">Time of Departure</label>
                        <input id="depart_time" type="time" name="depart_time" value="<?php echo $depart_time; ?>" class="form-control"/>
                    </div>
                    <div class="col-sm-12">
                        <label for="depart_station" class="form-label">Station of Departure</label>
                        <select id="depart_station" class="form-select" name="depart_station">
                            <option value="">Select the departing station</option>
                            <option value="kl_sentral" <?php sticky_select($depart_station, "kl_sentral"); ?>>KL Sentral Bus Station</option>
                            <option value="bt_pahat" <?php sticky_select($depart_station, "bt_pahat"); ?>>Batu Pahat Bus Terminal</option>
                            <option value="kuantan_sentral" <?php sticky_select($depart_station, "kuantan_sentral"); ?>>Kuantan Sentral Bus Station</option>
                            <option value="kt_terminal" <?php sticky_select($depart_station, "kt_terminal"); ?>>Kuala Terengganu Bus Terminal</option>
                            <option value="jengka_sentral" <?php sticky_select($depart_station, "jengka_sentral"); ?>>Jengka Sentral Bus Terminal</option>
                        </select>
                    </div>
                    <div class="col-sm-12">
                        <label for="dest_station" class="form-label">Destined Station</label>
                        <select id="dest_station" class="form-select" name="dest_station">
                            <option value="">Select the departing station</option>
                            <option value="kl_sentral" <?php sticky_select($dest_station, "kl_sentral"); ?>>KL Sentral Bus Station</option>
                            <option value="bt_pahat" <?php sticky_select($dest_station, "bt_pahat"); ?>>Batu Pahat Bus Terminal</option>
                            <option value="kuantan_sentral" <?php sticky_select($dest_station, "kuantan_sentral"); ?>>Kuantan Sentral Bus Station</option>
                            <option value="kt_terminal" <?php sticky_select($dest_station, "kt_terminal"); ?>>Kuala Terengganu Bus Terminal</option>
                            <option value="jengka_sentral" <?php sticky_select($dest_station, "jengka_sentral"); ?>>Jengka Sentral Bus Terminal</option>
                        </select>
                    </div>
                    <div class="col-sm-12">
                        <input type="hidden" name="edit_booking" value="<?php echo $booking_id; ?>" />
                        <input type="submit" class="btn btn-primary" name="confirm_edit" value="Edit Booking" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
