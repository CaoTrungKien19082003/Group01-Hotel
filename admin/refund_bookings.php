<?php
    require('inc/essentials.php');
    Login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Bookings</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-white">    
    <?php require('inc/header.php'); ?>
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflown-hidden">
                <h3 class="mb-4 h-font-1">
                    Refund Bookings
                </h3>
                <div class="card border-0 shadow-none mb-4">
                    <div class="card-body">
                        <div class="text-end mb-4">
                            <input type="text" oninput="get_bookings(this.value)" class="form-control shadow-none w-25 ms-auto" placeholder="Type to search...">
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover border">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col" class="h-font-1">#</th>
                                        <th scope="col" class="h-font-1">User Info</th>
                                        <th scope="col" class="h-font-1">Room Info</th>
                                        <th scope="col" class="h-font-1">Booking Info</th>
                                        <th scope="col" class="h-font-1">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="booking_data">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require('inc/scripts.php');?>

    <script src="scripts/refund_bookings.js"></script>
</body>
</html>