<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <?php require('inc/links.php'); ?>
</head>
<body class='bg-light'>
    <div>
        <form class="form">
            <h4>Admin Login</h4>
            <div>
                <div class="username">
                    <input type="username" class="form-control shadow_none text-center" placeholder="Enter Admin's Username">
                </div>
                <div class="password">
                    <input type="password" class="form-control shadow_none text-center" placeholder="Enter Admin's Password">
                </div>
            </div>
        </form>
    </div>
    
    <?php require('inc/scripts.php'); ?>
</body>
</html>