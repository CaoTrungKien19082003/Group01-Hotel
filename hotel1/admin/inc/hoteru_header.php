<div class="container-fluid bg-dark text-light p-3 d-flex align-items-center justify-content-between sticky-top">
    <h3 class="mb-0 h-font">Hoteru</h3>
    <?php 
        if(isset($_SESSION['adminLogin'])&& $_SESSION['adminLogin']==true){
            echo<<<data
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-outline-dark shadow-none dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                        $_SESSION[adminName]
                    </button>
                    <ul class="dropdown-menu dropdown-menu-lg-end">
                    <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
                    </ul>
                </div>
            data;
        }else{
            echo<<<data
                <a href="logout.php"class="btn btn-light btn-sm">LOG OUT</a>
            data;
        }
    ?>
    
</div>
<div class="col-lg-2 bg-dark border-top border-3 border-secondary" id="dashboard-menu">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid flex-lg-column align-items-stretch">
            <h4 class="mt-2 text-light">ADMIN PANEL</h4>
            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="adminDropdown">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="hoteru_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <button class="btn text-white px-3 w-100 shadow-none text-start d-flex align-items-center justify-content-between"  type="button" data-bs-toggle="collapse" data-bs-target="#BookingLinks">
                            <span> Booking </span>
                            <span><i class="bi bi-caret-down-fill"></i></span>
                        </button>                
                    </li>

                    <div class="collapse hidden px-3 small mb-1" id="BookingLinks">
                        <ul class="nav nav-pills flex-column rounded border border-secondary">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="new_bookings.php">New Bookings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="refund_bookings.php">Refund Bookings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="booking_records.php">Booking Records</a>
                            </li>
                        </ul>
                    </div>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="hoteru_rate_review.php">Rate & Review</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="hoteru_room.php">Rooms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="hoteru_feature_facility.php">Features & Facilities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="hoteru_user_note.php">User Notes</a>
                    </li>          
                    <li class="nav-item">
                        <a class="nav-link text-white" href="hoteru_carousel.php">Carousel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="hoteru_setting.php">Setting</a>
                    </li>
                </ul>
            </div>   
        </div>
    </nav>
</div>