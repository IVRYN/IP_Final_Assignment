<?php
session_start();
include ("config/config.php");

$page_title =   "Home | Murni Bas Ticketing";
include ("includes/header.php");
?>

<div class="main-container container mt-4 py-1">
    <div class="row g-3">
        <div id="header" class="col-sm-12">
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
            <div class="row py-3">
                <div class="card mx-auto" style="width: 25rem;">
                    <span class="material-icons">account_circle</span>
                    <div class="card-body">
                        <h5 class="card-title text-center">Register an account</h5>
                    </div>
                </div>
                <div class="card mx-auto" style="width: 25rem;">
                    <div class="card-body">
                        <h5 class="card-title text-center">Login into account</h5>
                    </div>
                </div>
                <div class="card mx-auto" style="width: 25rem;">
                    <div class="card-body">
                        <h5 class="card-title text-center">Book a ticket</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include ("includes/footer.php");
?>
