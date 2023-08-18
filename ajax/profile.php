<?php
require('../admin/inc/db.php');
require('../admin/inc/essentials.php');

date_default_timezone_set('Asia/Ho_Chi_Minh');

if (isset($_POST['info_form'])){
    $frm_data=filter($_POST);

    $query_1="SELECT * FROM `user_cred` WHERE `phone`=? AND `user_id`!=1 LIMIT 1";
    $values_1=[$frm_data['phone']];

    $res=select($query_1, $values_1, 's');
    if (mysqli_num_rows($res)!=0){
        echo 'phone_already';
        exit;
    }

    $q="UPDATE `user_cred` SET `name`=?,`phone`=?,`dob`=?,`pincode`=?,`address`=? WHERE `user_id`=1";
    $values=[$frm_data['name'], $frm_data['phone'], $frm_data['dob'], $frm_data['pincode'], $frm_data['address']];


    $res=update($q, $values, 'sssss');
    echo $res;
}

if (isset($_POST['profile'])){
    $img=uploadImage($_FILES['pfp'], USER);

    if ($img=='inv_file'){
        echo $img;
    }
    else if ($img_r=='inv_size'){
        echo $img;
    }
    if ($img=='upload_failed'){
        echo $img;
    }

    $q=mysqli_query($con ,"SELECT `pfp` FROM `user_cred` WHERE `user_id`=1");
    $fetch=mysqli_fetch_assoc($q);

    deleteImage($fetch['pfp'], USER);

    $query="UPDATE `user_cred` SET `pfp`=? WHERE `user_id`=1";
    $values=[$img];

    $res=update($query, $values, 's');
    echo $res;
}

if (isset($_POST['password'])){
    $frm_data=filter($_POST);

    $enc_pass=password_hash($frm_data['new_pass'], PASSWORD_BCRYPT);
    
    $q="UPDATE `user_cred` SET `password`=? WHERE `user_id`=1";
    $values=[$enc_pass];

    $res=update($q, $values, 's');
    echo $res;
}

