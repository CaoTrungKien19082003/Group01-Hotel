<?php
    require('../inc/essentials.php');
    require('../inc/db_config.php');
    adminLogin();  
    if (isset($_POST['get_bookings'])){
        $frm_data=filteration($_POST);
        $query="SELECT bo.*, bd.* FROM `booking_order` bo
        INNER JOIN `booking_details` bd ON bo.booking_id=bd.booking_id
        WHERE (bo.order_id LIKE ? OR bd.phone LIKE ? OR bd.user_name LIKE ?) AND
        (bo.booking_status=? AND bo.refund=?) AND bo.hotel=?";

        $values=["$frm_data[search]%", "$frm_data[search]%", "$frm_data[search]%", "cancelled", 0, $_SESSION['adminCode']];

        $res=select($query, $values, 'ssssis');
        $i=1;
        $table_data="";

        if (mysqli_num_rows($res)==0){
            $table_data .="<b class='h-font-1'>No Data Found!</b>";
        }

        while($data=mysqli_fetch_assoc($res)){
            $table_data .="
                <tr>
                    <td class='h-font-1'>$i</td>
                    <td class='h-font-1 bg-light'>
                        <span class='badge bg-primary h-font-1'>
                            Order ID: $data[order_id]
                        </span>
                        <br>
                        <b class='h-font-1'>Name: </b>$data[user_name]
                        <br>
                        <b class='h-font-1'>Phone Number: </b>$data[phone]
                    </td>
                    <td class='h-font-1'>
                        <b class='h-font-1'>Room: </b>$data[room_name]
                        <br>
                        <b class='h-font-1'>Price: </b>$$data[price]
                        <br>
                        <b class='h-font-1'>Weekend Price: </b>$$data[wkprice]
                    </td>
                    <td>
                        <button type='button' onclick='refund_bookings($data[booking_id])' class='btn btn-success btn-sm fw-bold text-white h-font-1 shadow-none' data-bs-toggle='modal' data-bs-target='#room_assignment'>
                            <i class='bi bi-cash-stack'></i> Refund
                        </button>
                    </td>
                </tr>
            ";

            $i++;
        }

        echo $table_data;
    }

    if (isset($_POST['refund_bookings'])){
        $frm_data=filteration($_POST);

        $query="UPDATE `booking_order`
        SET `refund`=?
        WHERE `booking_id`=? AND `hotel`=?";

        $values=[1, $frm_data['booking_id'],$_SESSION['adminCode']];
        $res=update($query, $values, 'iis');

        echo $res;
    }
?>