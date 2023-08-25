<?php
    require('../inc/essentials.php');
    require('../inc/db_config.php');
    adminLogin();
    if (isset($_POST['booking_analytics'])){
        $frm_data=filteration($_POST);

        $condition="";
        if ($frm_data['period']==1){
            $condition="WHERE `datentime` BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";
        }

        else if ($frm_data['period']==2){
            $condition="WHERE `datentime` BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
        }

        else if ($frm_data['period']==3){
            $condition="WHERE `datentime` BETWEEN NOW() - INTERVAL 1 YEAR AND NOW()";
        }


        $result=mysqli_fetch_assoc(select("SELECT 
        COUNT(CASE WHEN booking_status!='pending' AND booking_status!='payment failed' THEN 1 END) AS `total`,

        COUNT(CASE WHEN booking_status='booked' AND arrival=1 THEN 1 END) AS `active`,

        COUNT(CASE WHEN booking_status='cancelled' AND refund=1 THEN 1 END) AS `cancelled`

        FROM `booking_order` $condition AND `hotel`=?",[$_SESSION['adminCode']],'s'));

        $output=json_encode($result);

        echo $output;
    }

    if (isset($_POST['uqr_analytics'])){
        $frm_data=filteration($_POST);

        $condition="";
        if ($frm_data['period']==1){
            $condition="WHERE datentime BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";
        }

        else if ($frm_data['period']==2){
            $condition="WHERE datentime BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
        }

        else if ($frm_data['period']==3){
            $condition="WHERE datentime BETWEEN NOW() - INTERVAL 1 YEAR AND NOW()";
        }

        $total_queries=mysqli_fetch_assoc(select("SELECT COUNT(number) AS `count` FROM `user_note` $condition AND `hotel`=?",[$_SESSION['adminCode']],'s'));
        $total_reviews=mysqli_fetch_assoc(select("SELECT COUNT(number) AS `count` FROM `ratings_review` AND `hotel`=?",[$_SESSION['adminCode']],'s'));

        $output=['total_queries'=>$total_queries['count'],
            'total_reviews'=>$total_reviews['count'],
        ];

        $output=json_encode($output);
        
        echo $output;
    }
?>