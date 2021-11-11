<?php
session_start();

// include ("auth.php");
include ("common.php");

if (isset($_POST['add_booking']))
{
    user_add_booking($_SESSION['user_id'], $_POST['depart_date'], $_POST['depart_time'], $_POST['depart_station'], $_POST['dest_station']);
}

$page_title     =   "Add Booking | Murni Bus Ticketing";
include ("includes/header.php");
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
                            <option value="kuala_lumpur" <?php sticky_select("depart_station", "kuala_lumpur"); ?>>Kuala Lumpur</option>
                            <option value="johor_bahru" <?php sticky_select("depart_station", "johor_bahru"); ?>>Johor Bahru</option>
                            <option value="kuantan" <?php sticky_select("depart_station", "kuantan"); ?>>Kuantan</option>
                            <option value="kuala_terengganu" <?php sticky_select("depart_station", "kuala_terengganu"); ?>>Kuala Terengganu</option>
                            <option value="arau" <?php sticky_select("depart_station", "arau"); ?>>Arau</option>
                        </select>
                    </div>
                    <div class="col-sm-12">
                        <label for="dest_station" class="form-label">Destined Station</label>
                        <select id="dest_station" class="form-select" name="dest_station">
                            <option value="">Select the departing station</option>
                            <option value="kuala_lumpur" <?php sticky_select("dest_station", "kuala_lumpur"); ?>>Kuala Lumpur</option>
                            <option value="johor_bahru" <?php sticky_select("dest_station", "johor_bahru"); ?>>Johor Bahru</option>
                            <option value="kuantan" <?php sticky_select("dest_station", "kuantan"); ?>>Kuantan</option>
                            <option value="kuala_terengganu" <?php sticky_select("dest_station", "kuala_terengganu"); ?>>Kuala Terengganu</option>
                            <option value="arau" <?php sticky_select("dest_station", "arau"); ?>>Arau</option>
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
