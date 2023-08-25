<?php
    require('inc/essentials.php');
    require('inc/db_config.php');
    adminLogin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-eqiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoteru - Rooms</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">
    <?php require('inc/hoteru_header.php'); ?>
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3>ROOMS</h3>
                <!--room section-->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="text-end mb-4">
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-room-s">
                                <i class="bi bi-plus-square"></i>Add
                            </button>
                        </div>
                        <div class="table-responsive-lg" style="height: 450px;overflow-y: scroll;">
                            <table class="table table-hover border">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Area</th>
                                        <th scope="col">Guests</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">WK Price</th>
                                        <th scope="col">Star</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="room-data">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Add room modal-->
    <div class="modal fade" id="add-room-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="add_room_s_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add room</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <input type="text" name="room_name" id="room_name_inp" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Area</label>
                                <input type="number" min="1" name="room_area" id="room_area_inp" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Price</label>
                                <input type="number" min="1" name="room_price" id="room_price_inp" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">WK Price</label>
                                <input type="number" min="1" name="room_wk_price" id="room_wk_price_inp" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Quantity</label>
                                <input type="number" min="1" name="room_quantity" id="room_quantity_inp" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Adult (Max.)</label>
                                <input type="number" min="1" name="room_adult" id="room_adult_inp" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Children (Max.)</label>
                                <input type="number" min="1" name="room_children" id="room_children_inp" class="form-control shadow-none" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Feature</label>
                                <div class="row">
                                    <?php
                                    $q = "SELECT * FROM  `feature` WHERE `hotel`=?";
                                    $values = [$_SESSION['adminCode']];
                                    $res = select($q, $values, 's');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo "
                                            <div class='col-md-3 mb-1'>
                                                <label>
                                                    <input type='checkbox' name='feature' value='$opt[sr_no]' class='form-check-input shadow-none'>
                                                    $opt[feature_name]
                                                </label>
                                            </div>
                                            ";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Facilities</label>
                                <div class="row">
                                    <?php
                                    $q = "SELECT * FROM  `facility` WHERE `hotel`=?";
                                    $values = [$_SESSION['adminCode']];
                                    $res = select($q, $values, 's');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo "
                                            <div class='col-md-3 mb-1'>
                                                <label>
                                                    <input type='checkbox' name='facility' value='$opt[sr_no]' class='form-check-input shadow-none'>
                                                    $opt[name]
                                                </label>
                                            </div>
                                        ";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Picture</label>
                                <input type="file" name="room_image" id="room_image_inp" accept=".jpg, .png, .webp, .jpeg" class="form-control shadow-none" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="desc" rows="10" class="form-control shadow-none" required></textarea>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--edit room modal-->
    <div class="modal fade" id="edit-room-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="edit_room_s_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit room</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <input type="text" name="room_name" id="room_name_inp" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Area</label>
                                <input type="number" min="1" name="room_area" id="room_area_inp" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Price</label>
                                <input type="number" min="1" name="room_price" id="room_price_inp" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">WK Price</label>
                                <input type="number" min="1" name="room_wk_price" id="room_wk_price_inp" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Quantity</label>
                                <input type="number" min="1" name="room_quantity" id="room_quantity_inp" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Adult (Max.)</label>
                                <input type="number" min="1" name="room_adult" id="room_adult_inp" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Children (Max.)</label>
                                <input type="number" min="1" name="room_children" id="room_children_inp" class="form-control shadow-none" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Feature</label>
                                <div class="row">
                                    <?php
                                    $q = "SELECT * FROM  `feature` WHERE `hotel`=?";
                                    $values = [$_SESSION['adminCode']];
                                    $res = select($q, $values, 's');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo "
                                            <div class='col-md-3 mb-1'>
                                                <label>
                                                    <input type='checkbox' name='feature' value='$opt[sr_no]' class='form-check-input shadow-none'>
                                                    $opt[feature_name]
                                                </label>
                                            </div>
                                            ";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Facilities</label>
                                <div class="row">
                                    <?php
                                    $q = "SELECT * FROM  `facility` WHERE `hotel`=?";
                                    $values = [$_SESSION['adminCode']];
                                    $res = select($q, $values, 's');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo "
                                            <div class='col-md-3 mb-1'>
                                                <label>
                                                    <input type='checkbox' name='facility' value='$opt[sr_no]' class='form-check-input shadow-none'>
                                                    $opt[name]
                                                </label>
                                            </div>
                                        ";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Picture</label>
                                <input type="file" name="room_image" id="room_image_inp" accept=".jpg, .png, .webp, .jpeg" class="form-control shadow-none">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="desc" rows="10" class="form-control shadow-none" required></textarea>
                            </div>
                            <input type="hidden" name="room_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--Photo album-->
    <div class="modal fade" id="room-image" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Room Name</h1>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="image-alert"></div>
                    <div class="border-bottom border-3 pb-3 mb-3">
                        <form id="add_image_form">
                            <label class="form-label fw-bold">Add Image</label>
                            <input type="file" name="image" accept=".jpg, .png, .webp, .jpeg" class="form-control shadow-none mb-3" required>
                            <button class="btn custom-bg text-white shadow-none">ADD</button>
                            <input type="hidden" name="room_id">
                            
                        </form>
                    </div>
                    <div class="table-responsive-lg" style="height: 450px;overflow-y: scroll;">
                        <table class="table table-hover border">
                            <thead>
                                <tr class="bg-dark text-light">
                                    <th scope="col" width = "60%">Image</th>
                                    <th scope="col">Thumb</th>
                                    <th scope="col">Delete</th>
                                </tr>
                            </thead>
                            <tbody id="room-image-data">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php require('inc/scripts.php'); ?>
    <script src="script/hoteru_room.js"></script>
</body>

</html>