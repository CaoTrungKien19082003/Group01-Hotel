<?php
    require('../inc/essentials.php');
    require('../inc/db_config.php');
    adminLogin();    
    if (isset($_POST['get_bookings'])){
        $frm_data=filteration($_POST);
        $query="SELECT bo.*, bd.* FROM `booking_order` bo
        INNER JOIN `booking_details` bd ON bo.booking_id=bd.booking_id
        WHERE (bo.order_id LIKE ? OR bd.phone LIKE ? OR bd.user_name LIKE ?) AND
        (bo.booking_status=? AND bo.arrival=?) AND bo.hotel=?";

        $values=["$frm_data[search]%", "$frm_data[search]%", "$frm_data[search]%", "booked", 0, $_SESSION['adminCode']];

        $res=select($query, $values, 'ssssis');
        $i=1;
        $table_data="";

        if (mysqli_num_rows($res)==0){
            $table_data .="<b class='h-font-1'>No Data Found!</b>";
        }

        while($data=mysqli_fetch_assoc($res)){
            $date=date("d-m-Y", strtotime($data['datentime']));
            $checkin=date("d-m-Y", strtotime($data['check_in']));
            $checkout=date("d-m-Y", strtotime($data['check_out']));

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
                    <td class='h-font-1 bg-light'>
                        <b class='h-font-1'>Checkin: </b>$checkin
                        <br>
                        <b class='h-font-1'>Checkout: </b>$checkout
                        <br>
                        <b class='h-font-1'>Date: </b>$date
                    </td>
                    <td>
                        <button type='button' onclick='assign_room($data[booking_id])' class='btn btn-sm fw-bold custom-bg shadow-none' data-bs-toggle='modal' data-bs-target='#room_assignment'>
                            <i class='bi bi-check-square'></i> Assign Room
                        </button>
                        <br>
                        <button type='button' onclick='cancel_booking($data[booking_id])' class='mt-2 btn btn-danger btn-sm fw-bold shadow-none'>
                            <i class='bi bi-trash'></i> Cancel Booking
                        </button>
                    </td>
                </tr>
            ";

            $i++;
        }

        echo $table_data;
    }

    
?>