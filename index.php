<?php
session_start();
$page_title =   "Home | Murni Bas Ticketing";
include ("includes/header.php");
?>

<div class="main-container container my-4 py-1">
    <div class="row g-3">
        <div id="header" class="col-sm-12">
        </div>
        <div class="col-sm-12 py-4 bg-darkblue">
            <h1 class="display-3 text-light">
                <b>
                    Murni Bus ticketing
                </b>
                <br />
                <b>
                    <small class="text-muted">A better solution to online ticketing</small>
                </b>
            </h1>
            <p class="text-light lead px-3">
                Murni bus ticketing is a web-based ticketing system which allows our customers to make ticket booking quick and easy.
                While, having their booking information accessible and ready - This ticketing systems also allows for users to
                Change their ticket plans to suit their time and needs.
            </p>
        </div>
        <div class="col-sm-12">
            <h2 class="display-6">
                Using Murni Bus Ticketing System is as easy as;
            </h2>
            <div class="row g-3 py-3 text-center">
                <div class="col-lg-4">
                    <a href="register.php" class="card-link">
                    <div class="card-float card py-3">
                        <span class="material-icons text-darkblue" style="font-size: 10rem;">account_circle</span>
                        <div class="card-body">
                            <h5 class="card-title text-center">Register an account</h5>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-lg-4">
                    <a href="login.php" class="card-link">
                    <div class="card-float card py-3">
                        <span class="material-icons text-darkblue" style="font-size: 10rem;">lock</span>
                        <div class="card-body">
                            <h5 class="card-title text-center">Login into account</h5>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-lg-4">
                    <a href="add_booking.php" class="card-link">
                    <div class="card-float card py-3">
                        <span class="material-icons text-darkblue" style="font-size: 10rem;">confirmation_number</span>
                        <div class="card-body">
                            <h5 class="card-title text-center">Book a ticket</h5>
                        </div>
                    </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="container-fluid bg-darkblue">
        <footer class="footer mt-auto py-3 text-light">
            <p>Copyright 2021 Murni Bus Ticketing</p>
        </footer>
    </div>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>
