<?php 
    require('../inc/db.php');
    require('../inc/essentials.php');
    Login();

    if(isset($_POST['get_general'])){
        $q="SELECT * FROM `settings` WHERE `number`=?";
        $values=[1];
        $res=select($q, $values, "i");
        $data=mysqli_fetch_assoc($res);
        $json_data=json_encode($data);
        echo $json_data;
    }

    if(isset($_POST['update_shutdown'])){
        $frm_data=($_POST['update_shutdown']==0) ? 1:0;
        $q="UPDATE `settings` SET `status`=? WHERE `number`=?";
        $values=[$frm_data, 1];
        $res=update($q, $values, 'ii');

        echo $res;
    }

    if(isset($_POST['update_general'])){
        $frm_data=filter($_POST);
        $q="UPDATE `settings` SET `site_title`=?, `site_about`=? WHERE `number`=?";
        $values=[$frm_data['site_title'], $frm_data['site_about'], 1];
        $res=update($q, $values, 'ssi');

        echo $res;
    }
?>