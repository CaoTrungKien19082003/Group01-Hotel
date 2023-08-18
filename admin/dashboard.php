<?php
    require('inc/db.php')  ;
    require('inc/essentials.php');
    Login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-white">
    
    <?php 
        require('inc/header.php');

        $is_shutdown=mysqli_fetch_assoc(mysqli_query($con, "SELECT `shutdown` from `settings`"));
    
        $current_booking=mysqli_fetch_assoc(mysqli_query($con, "SELECT 
        COUNT(CASE WHEN booking_status='booked' AND arrival=0 THEN 1 END) AS `new_bookings`, 
        COUNT(CASE WHEN booking_status='cancelled' AND refund=0 THEN 1 END) AS `refund_bookings` FROM `booking_order`"));

        $unread_queries=mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(number) AS `unread` FROM `user_queries` WHERE `seen`=0"));
        $unread_reviews=mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(number) AS `unread` FROM `ratings_reviews` WHERE `seen`=0"));

        $current_user=mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(user_id) AS `total`, 
        COUNT(CASE WHEN `status`=1 THEN 1 END) AS `active`,
        COUNT(CASE WHEN `status`=0 THEN 1 END) AS `inactive`,
        COUNT(CASE WHEN `is_verified`=0 THEN 1 END) AS `unverified`
        FROM `user_cred`"));
    ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden" id="content">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3 class="h-font-1">Dashboard</h3>
                    <?php
                    if ($is_shutdown['shutdown']){
                        echo <<< data
                            <h6 class="badge bg-danger py-2 px-3 rounded h-font-1">
                                Shutdown Mode is on!
                            </h6>
                        data;
                    }
                    
                    ?>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3 mb-4">
                        <a href="new_bookings.php" class="text-decoration-none">
                            <div class="card text-center p-3 text-success">
                                <h6 class="h-font-1">
                                    New Bookings
                                </h6>
                                <h1 class="h-font-1 mt-2 mb-0">
                                    <?php
                                        echo $current_booking['new_bookings'];
                                    ?>
                                </h1>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <a href="refund_bookings.php" class="text-decoration-none">
                            <div class="card text-center p-3 text-warning">
                                <h6 class="h-font-1">
                                    Refund Bookings
                                </h6>
                                <h1 class="h-font-1 mt-2 mb-0">
                                    <?php
                                        echo $current_booking['refund_bookings'];
                                    ?>
                                </h1>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-3 mb-4">
                        <a href="user_queries.php" class="text-decoration-none">
                            <div class="card text-center p-3 text-info">
                                <h6 class="h-font-1">
                                    User Queries
                                </h6>
                                <h1 class="h-font-1 mt-2 mb-0">
                                    <?php
                                        echo $unread_queries['unread'];
                                    ?>
                                </h1>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-3 mb-4">
                        <a href="rating_and_reviews.php" class="text-decoration-none">
                            <div class="card text-center p-3 text-primary">
                                <h6 class="h-font-1">
                                    Ratings & Reviews
                                </h6>
                                <h1 class="h-font-1 mt-2 mb-0">
                                    <?php
                                        echo $unread_reviews['unread'];
                                    ?>
                                </h1>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3 class="h-font-1">Booking Analytics</h3>
                    <select class="form-select shadow-none bg-light w-auto" onchange="booking_analytics(this.value)">
                        <option value="1">Past 30 days</option>
                        <option value="2">Past 90 days</option>
                        <option value="3">Past 1 year</option>
                        <option value="4">All time</option>
                    </select>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center p-3 text-primary">
                            <h6 class="h-font-1">Total Bookings</h6>
                            <h1 class="h-font-1 mt-2 mb-0" id="total"></h1>
                            <h4 class="h-font-1 mt-2 mb-0" id="total_amt"></h4>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-center p-3 text-success">
                            <h6 class="h-font-1">Active Bookings</h6>
                            <h1 class="h-font-1 mt-2 mb-0" id="active"></h1>
                            <h4 class="h-font-1 mt-2 mb-0" id="active_amt"></h4>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-center p-3 text-danger">
                            <h6 class="h-font-1">Cancelled Bookings</h6>
                            <h1 class="h-font-1 mt-2 mb-0" id="cancelled"></h1>
                            <h4 class="h-font-1 mt-2 mb-0" id="cancelled_amt"></h4>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3 class="h-font-1">User, Queries, Reviews Analytics</h3>
                    <select class="form-select shadow-none bg-light w-auto">
                        <option value="1">Past 30 days</option>
                        <option value="2">Past 90 days</option>
                        <option value="3">Past 1 year</option>
                        <option value="4">All time</option>
                    </select>
                </div>
                <div class="row mb-4">
                    <div class="col-md-3 mb-4">                    
                        <div class="card text-center p-3 text-success">
                            <h6 class="h-font-1">New Registration</h6>
                            <h1 class="h-font-1 mt-2 mb-0" id="new_reg"></h1>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-center p-3 text-primary">
                            <h6 class="h-font-1">Queries</h6>
                            <h1 class="h-font-1 mt-2 mb-0" id="user_queries"></h1>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-center p-3 text-info">
                            <h6 class="h-font-1">Reviews</h6>
                            <h1 class="h-font-1 mt-2 mb-0" id="user_reviews"></h1>
                        </div>
                    </div>
                </div>
                <h3 class="h-font-1">Users</h3>
                <div class="row mb-4">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center p-3 text-primary">
                            <h6 class="h-font-1">All Users</h6>
                            <h1 class="h-font-1 mt-2 mb-0">
                                <?php
                                    echo $current_user['total'];
                                ?>
                            </h1>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-center p-3 text-success">
                            <h6 class="h-font-1">Active Users</h6>
                            <h1 class="h-font-1 mt-2 mb-0">
                                <?php
                                    echo $current_user['active'];
                                ?>
                            </h1>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-center p-3 text-warning">
                            <h6 class="h-font-1">Inactive Users</h6>
                            <h1 class="h-font-1 mt-2 mb-0">
                                <?php
                                    echo $current_user['inactive'];
                                ?>  
                            </h1>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-center p-3 text-danger">
                            <h6 class="h-font-1">Unverified Users</h6>
                            <h1 class="h-font-1 mt-2 mb-0">
                                <?php
                                    echo $current_user['unverified'];
                                ?>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/scripts.php');?>
    <script src="scripts/dashboard.js"></script>
</body>
</html>