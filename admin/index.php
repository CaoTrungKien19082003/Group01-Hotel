<?php
    require('inc/essentials.php');
    require('inc/db.php');
    session_start();
    if((isset($_SESSION['Login']) && $_SESSION['Login']==true)){
        redirecting('dashboard.php');
    }
?>

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
    <div class="login-form text-center rounded bg-black shadow overflow-none">
        <form method="POST">
            <h4 class="bg-dark text-white py-4">Admin Login</h4>
            <div class="p-4">
                <div class="mb-3">
                    <input name="username" required type="text" class="form-control shadow-none text-center h-font-1" placeholder="Enter Admin's Username"> 
                </div>

                <div class="mb-4">              
                    <input name="password" required type="password" class="form-control shadow-none text-center h-font-1" placeholder="Enter Admin's Password">   
                </div>
                <button name="login-button" type="submit" class="btn h-font-2 text-black custom-bg shadow-none">Login</button>
            </div>
        </form>
    </div>

    <?php require('inc/scripts.php'); ?>

    <?php
        if(isset($_POST['login-button'])){
           
            $frm_data=filter($_POST);
            
            $query="SELECT * FROM `admin_list` WHERE`username`=? AND `password`=?";
            $values=[$frm_data['username'], $frm_data['password']];
            
            $res=select($query, $values, "ss");
            if($res->num_rows==1){
                $row=mysqli_fetch_assoc($res);
                $_SESSION['Login']=true;
                $_SESSION['ID']=$row['number'];
                redirecting('dashboard.php');
               
            }
            else{
                alert('error', 'Invalid username or password.');
            }
        }
    ?>

    
    
</body>
</html>
