<?php 
    require('../admin/inc/db_config.php');
    require('../admin/inc/essentials.php');
    require('../vendor/autoload.php');
    date_default_timezone_set("Asia/Ho_Chi_Minh");

    if(isset($_POST['check_avalability'])){
        $frm_data = filteration($_POST);
        $status = "";
        $result = "";
        $today_date = new DateTime(date("Y-m-d"));
        $checkin_date= new DateTime($frm_data['check_in']);
        $checkout_date= new DateTime($frm_data['check_out']);
        if($checkin_date == $checkout_date){
            $status = 'check_in_out_equal';
            $result = json_encode(["status"=>$status]);
        }else if($checkout_date < $checkin_date){
            $status = 'check_out_earlier';
            $result = json_encode(["status"=>$status]);
        }else if($checkin_date <= $today_date){
            $status = 'check_in_earlier';
            $result = json_encode(["status"=>$status]);
        }
        if($status!=''){
            echo $result;
        }else{
            session_start();
            $_SESSION['room'];
            $tb_query = "SELECT COUNT(*) AS `total_b` from `booking_order` WHERE `booking_status`=? AND `room_sr_no`=? AND `check_out`>? AND `check_in`<?";
            $values = ['booked',$_SESSION['room']['sr_no'],$frm_data['check_in'],$frm_data['check_out']];
            $tb_fetch = mysqli_fetch_assoc(select($tb_query,$values,'siss'));
            $rq_res = select("SELECT `quantity` FROM `room` WHERE `sr_no`=?",[$_SESSION['room']['sr_no']],'i');
            $rq_fetch = mysqli_fetch_assoc($rq_res);
            if(($rq_fetch['quantity']-$tb_fetch['total_b'])<=0){
                $status = 'unavalable';
                $result = json_encode(['status'=>$status]);
                echo $result;
                exit;
            }



            $checkout_date->modify('+1 day');
            $interval = $checkout_date->diff($checkin_date);
            // total days
            $d1 = $days = $interval->days;
            // create an iterateable period of date (P1D equates to 1 day)
            $period = new DatePeriod($checkin_date, new DateInterval('P1D'), $checkout_date);
            foreach($period as $dt) {
                $curr = $dt->format('D');
                // substract if Saturday or Sunday
                if ($curr == 'Sat' || $curr == 'Sun') {
                    $days--;
                }
            }
            $wk=$d1-$days;
            $payment = ($wk)*$_SESSION['room']['wkprice']+$days*$_SESSION['room']['price'];
            $_SESSION['room']['payment'] = $payment;
            $_SESSION['room']['available'] = true;
            $_SESSION['room']['in'] = $checkin_date->format('Y-m-d');
            $_SESSION['room']['out'] = $checkout_date->format('Y-m-d');
            $_SESSION['room']['day'] = $d1;
            $_SESSION['room']['wk'] = $wk;
            $result  = json_encode(["status"=>'available',"days"=>$d1,"weekends"=>$wk,"payment"=>$payment]);
            echo $result;
        }
    }
?>