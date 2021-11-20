<?php
session_start();
include ("config/config.php");

$page_title =   "Home | Murni Bas Ticketing";
include ("includes/header.php");
?>

<div class="container mt-4">
    <div class="row g-3">
        <div id="header" class="col-sm-12">
            <img class="img-fluid" src="images/header.jpg"/>
        </div>
        <div class="col-sm-12">
            <h1 class="display-3">
                Murni Bus ticketing
                <br />
                <small class="text-muted">A better solution to online ticketing</small>
            </h1>
        </div>
        <div class="col-sm-12">
            <p class="lead">
                Murni bus ticketing is a web-based ticketing system which allows our customers to make ticket booking quick and easy.
                While, having their booking information accessible and ready - This ticketing systems also allows for users to
                Change their ticket plans to suit their time and needs.
            </p>
        </div>
        <div class="col-sm-12">
            <h2 class="display-6">
                How to use the Murni ticketing system
            </h2>
            <p>
                <ol>
                    <li>Register an account</li>
                    <li>Login into the account</li>
                    <li>Book a ticket</li>
                </ol>
            </p>
        </div>
    </div>
</div>

<?php
include ("includes/footer.php");
?>
