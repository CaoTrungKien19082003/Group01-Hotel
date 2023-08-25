<?php
    require('inc/essentials.php');
    require('inc/db_config.php');
    adminLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Bookings</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-white">    
    <?php require('inc/hoteru_header.php'); ?>
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflown-hidden">
                <h3 class="mb-4 h-font-1">
                    New Bookings
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

    <div class="modal fade" id="room_assignment" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="assign_room_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Room</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Room Number</label>
                            <input type="text" name="room_no" id="room_no" class="form-control shadow-none" required>
                        </div>
                        <span class="badge rounded-pill bg-light text-dark mb-3 text-wrap lh-base h-font-1">
                            Note: Only assign Room Number when user has arrived!
                        </span>
                        <input type="hidden" name="booking_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary custom-bg text-white shadow-none" data-bs-dismiss="modal">Assign</button>
                    </div>
                </div>
            </form>
        
        </div>
    </div>
    <?php require('inc/scripts.php');?>
    
    <script src="script/new_bookings.js"></script>
</body>
</html>