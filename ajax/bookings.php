<?php
require('../admin/inc/db.php');
require('../admin/inc/essentials.php');

date_default_timezone_set('Asia/Ho_Chi_Minh');

if (isset($_POST['cancel_booking'])){
        $frm_data=filter($_POST);

        $query="UPDATE `booking_order`
        SET `booking_status`=?, `refund`=?
        WHERE `booking_id`=?";

        $values=['cancelled', 0, $frm_data['id']];
        $res=update($query, $values, 'sii');

        echo $res;
    }
?>