<?php
session_start();
$page_title =   "Login | Murni Bas Ticketing";
include ('common.php');

if (isset($_POST['login']))
{
    login_user($_POST['username'], $_POST['password']);
}

include ("includes/header.php");
?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-10 col-sm-10 col-md-10 col-lg-6 mt-5">
            <div class="container py-5">
            <form class="row g-3" action="login.php" method="post">
                <div class="row mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <div class="input-group">
                        <div class="input-group-text">
                            <span class="material-icons">person</span>
                        </div>
                        <input id="username" class="form-control" type="text" name="username" placeholder="Username" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <div class="input-group">
                        <div class="input-group-text">
                            <span class="material-icons">lock</span>
                        </div>
                        <input id="password" class="form-control" type="password" name="password" placeholder="Password" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <input class="btn btn-primary" type="submit" name="login" value="Login"/>
                    </div>
                </div>
            </form>
            </div>
            <div class="col-sm-7">
                <?php display_errors(); ?>
            </div>
        </div>
    </div>
</div>
