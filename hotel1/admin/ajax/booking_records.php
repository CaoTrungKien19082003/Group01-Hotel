<?php
    require('../inc/essentials.php');
    require('../inc/db_config.php');
    adminLogin();
    if (isset($_POST['get_bookings'])){
        $frm_data=filteration($_POST);
        $limit=10;

        $page=$frm_data['page'];
        $start=($page-1)*$limit;

        $query="SELECT bo.*, bd.* FROM `booking_order` bo
        INNER JOIN `booking_details` bd ON bo.booking_id=bd.booking_id
        WHERE (bo.order_id LIKE ? OR bd.phone LIKE ? OR bd.user_name LIKE ?)
        AND((bo.booking_status='cancelled' AND bo.refund=1) OR (bo.booking_status='booked' AND bo.arrival=1) OR bo.booking_status='payment failed') AND bo.hotel=?";

        $values=["$frm_data[search]%", "$frm_data[search]%", "$frm_data[search]%",$_SESSION['adminCode']];

        $res=select($query, $values, 'ssss');

        $limit_query=$query ." LIMIT $start, $limit";
        $limit_res=select($limit_query, $values, 'ssss');

        $i=1;
        $table_data="";

        if (mysqli_num_rows($res)==0){
            $out=json_encode(["booking_data"=>"<b class='h-font-1'>No Data Found!</b>"]);
            echo $out;
            exit;
        }



        while($data=mysqli_fetch_assoc($limit_res)){
            $date=date("d-m-Y", strtotime($data['datentime']));

            if ($data['booking_status']=='booked'){
                $status_bg='bg-success';
            }

            else if ($data['booking_status']=='cancelled'){
                $status_bg='bg-danger';
            }

            else if ($data['booking_status']=='payment failed'){
                $status_bg='bg-warning';
            }

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
                        <b class='h-font-1'>Date: </b>$date
                    </td>
                    <td>
                        <span class='badge $status_bg h-font-1'>$data[booking_status]</span>
                    </td>
                    <td class='justify-content-center'>
                        <button type='button' onclick='download($data[booking_id])' class='mt-2 btn btn-success btn-sm fw-bold h-font-1 shadow-none'>
                            <i class='bi bi-filetype-pdf'></i> Downloads
                        </button>
                    </td>
                </tr>
            ";

            $i++;
        }

        $pagination="";

        if (mysqli_num_rows($res)>$limit){
            $total_pages=ceil(mysqli_num_rows($res)/$limit);
            $disabled=($page==1) ? "disabled":"";
            $prev=$page-1;
            $pagination .="<li class='page-item $disabled'>
                                <button onclick='change_page($prev)' class='page-link shadow-none'>Prev</button>
                            </li>";
            $next=$page+1;
            $disabled1=($page==$total_pages) ? "disabled":"";
            $pagination .="<li class='page-item $disabled1'>
                                <button onclick='change_page($next)' class='page-link shadow-none'>Next</button>
                            </li>";
        }

        $output=json_encode(["booking_data"=>$table_data, "pagination"=>$pagination]);

        echo $output;
    }
?>