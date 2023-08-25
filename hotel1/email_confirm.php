<?php
    require('admin/inc/db_config.php');
    require('admin/inc/essentials.php');
    if(isset($_GET['verified'])){
        $data = filteration($_GET);
        $query = select("SELECT * FROM `user_cred` WHERE `email`=? And `token`=? LIMIT 1",[$data['email'],$data['token']],'ss');
        if(mysqli_num_rows($query)==1){
            $fetch = mysqli_fetch_assoc($query);
            if($fetch['is_verified']==1){
                echo"<script>alert('Email already verified!')</script>";
                redirect('index.php');
            }else{
                $update = update("UPDATE `user_cred` SET `is_verified`=? WHERE `id`=?",[1,$fetch['id']],'ii');
                if($update){
                    echo"<script>alert('Email verified successfully!')</script>";
                }else{
                    echo"<script>alert('Email verified failed!, Server down!')</script>";
                }
                redirect('index.php');
            }
        }else{
            echo"<script>alert('Invalid Links!')</script>";
        }

    }
?>