<?php 
    require('../inc/db.php');
    require('../inc/essentials.php');
    Login();

    if (isset($_POST['get_bookings'])){
        $frm_data=filter($_POST);
        $query="SELECT bo.*, bd.* FROM `booking_order` bo
        INNER JOIN `booking_details` bd ON bo.booking_id=bd.booking_id
        WHERE (bo.order_id LIKE ? OR bd.phone_num LIKE ? OR bd.user_name LIKE ?) AND
        (bo.booking_status=? AND bo.refund=?)";

        $values=["$frm_data[search]%", "$frm_data[search]%", "$frm_data[search]%", "cancelled", 0];

        $res=select($query, $values, 'ssssi');
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
                        <b class='h-font-1'>Phone Number: </b>$data[phone_num]
                    </td>
                    <td class='h-font-1'>
                        <b class='h-font-1'>Room: </b>$data[room_name]
                        <br>
                        <b class='h-font-1'>Price: </b>$$data[price]
                    </td>
                    <td class='h-font-1 bg-light'>
                        <b class='h-font-1'>Refund: </b>$$data[trans_amt]
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
        $frm_data=filter($_POST);

        $query="UPDATE `booking_order`
        SET `refund`=?
        WHERE `booking_id`=?";

        $values=[1, $frm_data['booking_id']];
        $res=update($query, $values, 'ii');

        echo $res;
    }
?>