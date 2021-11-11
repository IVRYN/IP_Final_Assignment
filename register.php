<?php
$page_title =   "Register | Murni Bas Ticketing";
include ("common.php");

if (isset($_POST['register']))
{
    register_user($_POST['f_name'], $_POST['l_name'], $_POST['mobilehp'], $_POST['username'], $_POST['password'], $_POST['confirm_password']);
}

include ("includes/header.php");
?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-sm-5">
            <div class="container py-5">
                <form class="row g-3" action="register.php" method="post">
                    <div class="col-sm-6">
                        <label for="first_name" class="form-label">First Name:</label>
                        <input id="first_name" type="text" name="f_name" value="<?php echo sticky_form('f_name'); ?>" class="form-control" required/>
                    </div>
                    <div class="col-sm-6">
                        <label for="last_name" class="form-label">Last Name:</label>
                        <input id="last_name" type="text" name="l_name" value="<?php echo sticky_form('l_name'); ?>" class="form-control" required/>
                    </div>
                    <div class="col-sm-12">
                        <label for="mobilehp" class="form-label">Phone No.:</label>
                        <input id="mobilehp" type="text" name="mobilehp" value="<?php echo sticky_form('mobilehp'); ?>" class="form-control" required/>
                    </div>
                    <div class="col-sm-12">
                        <label for="username" class="form-label">Username:</label>
                        <input id="username" type="text" name="username" value="<?php echo sticky_form('username'); ?>" class="form-control" required/>
                    </div>
                    <div class="col-sm-12">
                        <label for="password" class="form-label">Password:</label>
                        <input id="password" type="password" name="password" class="form-control" required/>
                    </div>
                    <div class="col-sm-12">
                        <label for="confirm_password" class="form-label">Confirm Password:</label>
                        <input id="confirm_password" type="password" name="confirm_password" class="form-control" required/>
                    </div>
                    <div class="col-sm-12">
                        <input type="submit" class="btn btn-primary" name="register" value="Register"/>
                    </div>
                </form>
            </div>
            <div class="col-sm-7">
                <?php display_errors(); ?>
            </div>
        </div>
    </div>
</div>

<?php
include ("includes/footer.php");
?>
