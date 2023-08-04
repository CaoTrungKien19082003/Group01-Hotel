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
    <title>Admin Profile</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-white">
    <div class="container-fluid bg-dark text-white p-3 d-flex align-items-center justify-content-between">
        <h3 class="mb-0">Admin Panel</h3>
        <a href="logout.php" class="btn h-font-1 text-black custom-bg shadow-none">Log out</a>
    </div>

    <?php
         alert('success', "Welcome back, admin!");
         
    ?>
    <?php require('inc/scripts.php');?>
</body>
</html>