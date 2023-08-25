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
    <title>Hoteru - Features & Facilities</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">
    <?php require('inc/hoteru_header.php');?>
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3>Features & Facilities</h3>
                <!--feature section-->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">Features</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#feature-s">
                                <i class="bi bi-pencil-square"></i>Edit
                            </button>
                        </div>
                        <div class="table-responsive-md" style="height: 350px;overflow-y: scroll;">
                        <table class="table table-hover border">
                            <thead>
                                <tr class="bg-dark text-light">
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="feature-data">
                                
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--facility section-->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">Facility</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#facility-s">
                                <i class="bi bi-pencil-square"></i>Add
                            </button>
                        </div>
                        <div class="table-responsive-md" style="height: 350px;overflow-y: scroll;">
                        <table class="table table-hover border">
                            <thead class="sticky-top">
                                <tr class="bg-dark text-light">
                                <th scope="col">#</th>
                                <th scope="col">Icon</th>
                                <th scope="col">Picture</th>
                                <th scope="col">Name</th>
                                <th scope="col">Thumbnail</th>
                                <th scope="col">Description</th>
                                <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="facilily-data">
                                
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Feature modal-->
    <div class="modal fade" id="feature-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="feature_s_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Feature</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="feature_name" id="feature_name_inp" class="form-control shadow-none" required>
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
    <!--facility add modal-->
    <div class="modal fade" id="facility-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="facility_s_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Team Facility</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="facility_name" id="facility_name_inp" class="form-control shadow-none" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Icon (SVG File)</label>
                            <input type="file" name="facility_icon" id="facility_icon_inp" accept=".svg" class="form-control shadow-none" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Picture</label>
                            <input type="file" name="facility_picture" id="facility_picture_inp" accept=".jpg, .png, .webp, .jpeg" class="form-control shadow-none" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Thumbnail</label>
                            <input type="text" name="facility_thumb" id="facility_thumb_inp" class="form-control shadow-none" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" width = "40%">Description</label>
                            <textarea name="facility_desc" class="form-control shadow-none" rows="3"></textarea>
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
    <?php require('inc/scripts.php');?>
    <script src="script/hoteru_feature_facility.js"></script>
</body>
</html>