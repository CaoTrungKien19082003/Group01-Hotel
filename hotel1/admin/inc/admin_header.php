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
                        <a class="nav-link text-white" href="admin_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="users.php">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="hotels.php">Hotels</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="admin_user_note.php">User Notes</a>
                    </li>          
                    <li class="nav-item">
                        <a class="nav-link text-white" href="setting.php">Setting</a>
                    </li>
                </ul>
            </div>   
        </div>
    </nav>
</div>