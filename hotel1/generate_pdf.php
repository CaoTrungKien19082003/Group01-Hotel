<?php
    require('admin/inc/essentials.php');
    require('admin/inc/db_config.php');
    require('admin/inc/mpdf/vendor/autoload.php');

    if (isset($_GET['gen_pdf']) && isset($_GET['id'])){
        $frm_data=filteration($_GET);

        $query="SELECT bo.*, bd.* FROM `booking_order` bo
        INNER JOIN `booking_details` bd ON bo.booking_id=bd.booking_id
        WHERE((bo.booking_status='cancelled' AND bo.refund=1) OR (bo.booking_status='booked' AND bo.arrival=1) OR bo.booking_status='payment failed')
        AND bo.booking_id=?";

        $values=[$frm_data['id']];

        $res=select($query, $values, 'i');

        $total_rows=mysqli_num_rows($res);

        $table_data="";

        if ($total_rows==0){
            header('location: bookings.php');
            exit;
        }

        $data=mysqli_fetch_assoc($res);

        $date=date("h:iA | d-m-Y", strtotime($data['datentime']));
        $checkin=date("d-m-Y", strtotime($data['check_in']));
        $checkout=date("d-m-Y", strtotime($data['check_out']));

        $table_data .="
        <h2 style=''>BOOKING RECEIPT</h2>
        <h5 style=''>Please show the pdf file or physical copy of it to receptionist to get room</h5>
        <table class='table table-dark table-striped' border='1'>
            <tr>
                <td>Order ID: $data[order_id]</td>
                <td>Booking Date: $date</td>
            </tr>
            <tr>
                <td colspan='2'>Status: $data[booking_status]</td>
            </tr>
            <tr>
                <td>Name: $data[user_name]</td>
                <td>Total: $$data[total_pay]</td>
            </tr>
            <tr>
                <td>Phone Number: $data[phone]</td>
                <td>Address: $data[address]</td>
            </tr>
            <tr>
                <td>Price: $$data[price] per night</td>
                <td>Weekend price: $$data[wkprice] per weekend</td>
            </tr>
            <tr>
                <td>Check-in: $checkin</td>
                <td>Check-out: $checkout</td>
            </tr>
            
        ";

        if ($data['booking_status']=='cancelled'){
            $refund=($data['refund']==1)? "Refunded": "Not yet refunded";

            $table_data .="<tr>
                <td>Amount Paid: $data[total_pay]</td>
                <td>Refund: $refund</td>
            </tr>
            ";
        }
        else{
            $table_data .="<tr>
                <td>Room Number: $data[room_no]</td>
                <td>Room: $data[room_name]</td>
            </tr>
            ";
        }

        $table_data .="</table>";

        $mpdf=new \Mpdf\Mpdf();

        $mpdf->WriteHTML($table_data);

        $mpdf->Output($data['order_id'].'.pdf', 'D');
    }

    else{
        header('location: bookings.php');
    }
?>