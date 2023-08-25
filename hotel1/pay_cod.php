<?php 
    require('admin/inc/db_config.php');
    require('admin/inc/essentials.php');
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    session_start();
    if(!(isset($_SESSION['login'])&& $_SESSION['login']==true)){
        redirect('index.php');
    }
        $checkSum = "";
        $ORDER_ID = 'ORD_'.$_SESSION['uId'].random_int(11111,999999);
        $CUST_ID = $_SESSION['uId'];
        $TOTAL = $_SESSION['room']['payment'];
        $paramList = array();
        $query1 = "INSERT INTO `booking_order`(`id`, `room_sr_no`, `booking_status`, `check_in`, `check_out`, `order_id`, `hotel`) VALUES (?,?,?,?,?,?,?)";
        $res1=insert($query1,[$_SESSION['uId'],$_SESSION['room']['sr_no'],'booked',$_SESSION['room']['in'],$_SESSION['room']['out'],$ORDER_ID,$_SESSION['room']['hotel']],'issssss');
        $booking_id = mysqli_insert_id($con);
        $query2 = "INSERT INTO `booking_details`(`booking_id`, `room_name`, `price`, `wkprice`, `day`, `wk`, `total_pay`, `user_name`, `phone`, `address`) 
        VALUES (?,?,?,?,?,?,?,?,?,?)";
        $res2=insert($query2,[$booking_id,$_SESSION['room']['name'],$_SESSION['room']['price'],$_SESSION['room']['wkprice'],$_SESSION['room']['day'],$_SESSION['room']['wk'],$TOTAL,$_SESSION['uName'],$_SESSION['uPhone'],$_SESSION['uAdd']],'isssssssss');
        redirect('index.php?b_success');
?>


