<div class="container-fluid bg-dark text-white p-3 d-flex align-items-center justify-content-between sticky-top">
    <h3 class="mb-0 h-font-1">Hoteru's Admin Page</h3>
    <a href="logout.php" class="btn h-font-1 text-black custom-bg shadow-none">Log out</a>
</div>

<div class="col-lg-2 bg-dark border-top border-3 border-secondary" id="admin-menu">
    <nav class="navbar flex-lg-column navbar-dark">
        <div class="container-fluid flex-lg-column align-items-stretch">
            <h4 class="mt-2 text-center h-font-1 text-white alighn-items-center">Admin</h4>
            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminDropdown" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" id="collapse-button">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="adminDropdown">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white" style="font-size: 25px;" href="dashboard.php">Dashboard</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white" style="font-size: 25px;" href="#">Rooms</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white" style="font-size: 25px;" href="#">Users</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white" style="font-size: 25px;" href="settings.php">Settings</a>
                </li>

            </ul>
        </div>
    </nav>
</div>