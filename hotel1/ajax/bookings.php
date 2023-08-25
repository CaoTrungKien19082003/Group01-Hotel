<?php 
    require('../admin/inc/db_config.php');
    require('../admin/inc/essentials.php');
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    session_start();
    if(!(isset($_SESSION['login'])&& $_SESSION['login']==true)){
        redirect('index.php');
    }
    if (isset($_POST['cancel_booking'])){
        $frm_data=filteration($_POST);

        $query="UPDATE `booking_order`
        SET `booking_status`=?, `refund`=?
        WHERE `booking_id`=?";

        $values=['cancelled', 0, $frm_data['id']];
        $res=update($query, $values, 'sii');

        echo $res;
    }
?>