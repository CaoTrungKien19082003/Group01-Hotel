<?php
    require('../inc/db.php');
    require('../inc/essentials.php');
    Login();

    if (isset($_POST['booking_analytics'])){
        $frm_data=filter($_POST);

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


        $result=mysqli_fetch_assoc(mysqli_query($con, "SELECT 
        COUNT(CASE WHEN booking_status!='pending' AND booking_status!='payment failed' THEN 1 END) AS `total`,
        SUM(CASE WHEN booking_status!='pending' AND booking_status!='payment failed' THEN `trans_amt` END) AS `total_amt`,

        COUNT(CASE WHEN booking_status='booked' AND arrival=1 THEN 1 END) AS `active`,
        SUM(CASE WHEN booking_status='booked' AND arrival=1 THEN `trans_amt` END) AS `active_amt`,

        COUNT(CASE WHEN booking_status='cancelled' AND refund=1 THEN 1 END) AS `cancelled`,
        SUM(CASE WHEN booking_status='cancelled' AND refund=1 THEN `trans_amt` END) AS `cancelled_amt`
        FROM `booking_order` $condition"));

        $output=json_encode($result);

        echo $output;
    }

    if (isset($_POST['uqr_analytics'])){
        $frm_data=filter($_POST);

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

        $total_queries=mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(number) AS `count` FROM `user_queries` $condition"));
        $total_reviews=mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(number) AS `count` FROM `ratings_reviews` $condition"));
        $new_reg=mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(user_id) AS `count` FROM `user_cred` $condition"));

        $output=['total_queries'=>$total_queries['count'],
            'total_reviews'=>$total_reviews['count'],
            'new_reg'=>$new_reg['count']
        ];

        $output=json_encode($output);
        
        echo $output;
    }
?>