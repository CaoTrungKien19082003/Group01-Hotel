

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <?php require('inc/links.php'); ?>
    <style>
        .login-form{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
        }
    </style>
</head>
<body class='bg-light'>
    <div class="login-form text-center rounded bg-white shadow overflow-none">
        <form class="form">
            <h4 class="bg-dark text-white py-4">Admin Login</h4>
            <div class="p-4">
                <div class="mb-3">
                    <input name="username" type="text" class="form-control shadow-none text-center h-font-1" placeholder="Enter Admin's Username"> 
                </div>

                <div class="mb-4">              
                    <input name="password" type="text" class="form-control shadow-none text-center h-font-1" placeholder="Enter Admin's Password">   
                </div>

                <div>
                    <button name="login-button" class="btn h-font-2 text-black custom-bg shadow-none">Login</button>
                </div>
            </div>
        </form>
    </div>
    
    <?php require('inc/scripts.php'); ?>
</body>
</html>
