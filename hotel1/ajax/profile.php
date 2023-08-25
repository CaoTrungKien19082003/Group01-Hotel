<?php
    require('../admin/inc/db_config.php');
    require('../admin/inc/essentials.php');

date_default_timezone_set('Asia/Ho_Chi_Minh');

if (isset($_POST['info_form'])){
    $frm_data=filteration($_POST);
    session_start();
    $u_exist = select("SELECT * FROM `user_cred` WHERE `email`=? AND `id`!=? LIMIT 1",
    [$frm_data['email'],$_SESSION['uId']],"ss");
    if(mysqli_num_rows($u_exist)!=0){
        echo 'email_already';
        exit;
    }
    $query = "UPDATE `user_cred` SET `name`=?, `user_address`=?, `email`=?, `pcode`=?, `dob`=? WHERE `id`=?";
    $values = [$frm_data['name'],$frm_data['address'],$frm_data['email'],$frm_data['pincode'],$frm_data['dob'],$_SESSION['uId']];
    if(update($query,$values,'ssssss')){
        $_SESSION['uName'] = $frm_data['name'];
        $_SESSION['uAdd'] = $frm_data['address'];
        echo 1;
    }else{echo 0;}
}

if (isset($_POST['profile'])){
    session_start();
    $img=uploadUserImage($_FILES['pfp']);

    if ($img=='inv_file'){
        echo $img;
    }else if ($img_r=='inv_size'){
        echo $img;
    }else if ($img=='upload_failed'){
        echo $img;
    }

    $q="SELECT `profile` FROM `user_cred` WHERE `id`=? LIMIT 1";
    $v=[$_SESSION['uId']];
    $fetch=mysqli_fetch_assoc(select($q,$v,'s'));

    deleteImage($fetch['profile'], USER_FOLDER);

    $query="UPDATE `user_cred` SET `profile`=? WHERE `id`=?";
    $values=[$img,$_SESSION['uId']];
    if(update($query, $values, 'ss')){
        $_SESSION['uPic'] = $img;
        echo 1;
    }else{echo 0;}
}

if (isset($_POST['password'])){
    session_start(); 
    $frm_data=filteration($_POST);

    $enc_pass=password_hash($frm_data['new_pass'], PASSWORD_BCRYPT);
    
    $q="UPDATE `user_cred` SET `pass`=? WHERE `id`=?";
    $values=[$enc_pass,$_SESSION['uId']];

    $res=update($q, $values, 'ss');
    if($res){echo 1;}else{echo 0;}
}