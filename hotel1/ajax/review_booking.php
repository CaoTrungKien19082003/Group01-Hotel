<?php 
    require('../admin/inc/db_config.php');
    require('../admin/inc/essentials.php');
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    session_start();
    if(!(isset($_SESSION['login'])&& $_SESSION['login']==true)){
        redirect('index.php');
    }
    if (isset($_POST['review_form'])){
        $frm_data=filteration($_POST);

        $query="UPDATE `booking_order` SET `rate_review`=? WHERE `booking_id`=? AND `id`=?";

        $values=[1,$frm_data['booking_id'],$_SESSION['uId']];
        $res=update($query, $values, 'iii');
        $q = mysqli_fetch_assoc(select("SELECT * FROM `room` WHERE `sr_no`=?",[$frm_data['room_sr_no']],'i'));
        $ins_q = "INSERT INTO `rating_review`(`booking_id`, `room_sr_no`, `id`, `rating`, `review`, `hotel`) VALUES (?,?,?,?,?,?)";
        $val=[$frm_data['booking_id'],$frm_data['room_sr_no'],$_SESSION['uId'],$frm_data['rating'],$frm_data['review'],$q['hotel']];
        $ins_r = insert($ins_q,$val,'iiiiss');

        echo $ins_r;
    }
?>