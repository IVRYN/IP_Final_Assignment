<?php
session_start();
include ('common.php');

$page_title     =   "Edit Booking | Murni Bus Ticketing";
include ('includes/header.php');
?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-sm-5">
            <div class="container py-5">
                <form class="row g-3 mb-3" action="add_booking.php" method="post">
                    <div class="col-sm-6">
                        <label for="depart_date" class="form-label">Date of Departure</label>
                        <input id="depart_date" min="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d", strtotime('+1 year')); ?>" type="date" name="depart_date" class="form-control"/>
                    </div>
                    <div class="col-sm-6">
                        <label for="depart_time" class="form-label">Time of Departure</label>
                        <input id="depart_time" type="time" name="depart_time" class="form-control"/>
                    </div>
                    <div class="col-sm-12">
                        <label for="depart_station" class="form-label">Station of Departure</label>
                        <select id="depart_station" class="form-select" name="depart_station">
                            <option value="">Select the departing station</option>
                            <option value="kl_sentral" <?php sticky_select("depart_station", "kl_sentral"); ?>>KL Sentral Bus Station</option>
                            <option value="bt_pahat" <?php sticky_select("depart_station", "bt_pahat"); ?>>Batu Pahat Bus Terminal</option>
                            <option value="kuantan_sentral" <?php sticky_select("depart_station", "kuantan_sentral"); ?>>Kuantan Sentral Bus Station</option>
                            <option value="kt_terminal" <?php sticky_select("depart_station", "kt_terminal"); ?>>Kuala Terengganu Bus Terminal</option>
                            <option value="jengka_sentral" <?php sticky_select("depart_station", "jengka_sentral"); ?>>Jengka Sentral Bus Terminal</option>
                        </select>
                    </div>
                    <div class="col-sm-12">
                        <label for="dest_station" class="form-label">Destined Station</label>
                        <select id="dest_station" class="form-select" name="dest_station">
                            <option value="">Select the departing station</option>
                            <option value="kl_sentral" <?php sticky_select("dest_station", "kl_sentral"); ?>>KL Sentral Bus Station</option>
                            <option value="bt_pahat" <?php sticky_select("dest_station", "bt_pahat"); ?>>Batu Pahat Bus Terminal</option>
                            <option value="kuantan_sentral" <?php sticky_select("dest_station", "kuantan_sentral"); ?>>Kuantan Sentral Bus Station</option>
                            <option value="kt_terminal" <?php sticky_select("dest_station", "kt_terminal"); ?>>Kuala Terengganu Bus Terminal</option>
                            <option value="jengka_sentral" <?php sticky_select("dest_station", "jengka_sentral"); ?>>Jengka Sentral Bus Terminal</option>
                        </select>
                    </div>
                    <div class="col-sm-12">
                        <input type="submit" class="btn btn-primary" name="add_booking" value="Add Booking" />
                    </div>
                </form>
                <div class="row">
                    <div class="col-sm-12">
                        <?php display_errors(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
