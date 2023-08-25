<?php 
    require('../admin/inc/db_config.php');
    require('../admin/inc/essentials.php');
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    session_start();
    if(isset($_GET['fetch_room'])){
        $chk_avail = json_decode($_GET['chk_avail'],true);

        if($chk_avail['checkin']!=''&&$chk_avail['checkout']!=''){
            $today_date = new DateTime(date("Y-m-d"));
            $checkin_date= new DateTime($chk_avail['checkin']);
            $checkout_date= new DateTime($chk_avail['checkout']);
            if($checkin_date == $checkout_date){
                echo "<h3 class='text-center text-danger'>You cannot check-out on the same day!</h3>";
                exit;
            }else if($checkout_date < $checkin_date){
                echo "<h3 class='text-center text-danger'>Check-out date is earlier than check-in date!</h3>";
                exit;
            }else if($checkin_date <= $today_date){
                echo "<h3 class='text-center text-danger'>You cannot check-in on that day!</h3>";
                exit;
            }
        }
        $max_p = json_decode($_GET['max_p'],true);
        $max = ($max_p['max_p']!='') ? $max_p['max_p']: 1000000000;
        $locat = json_decode($_GET['locat'],true);
        $add = ($locat['locat']!='') ? $locat['locat']: '';
        $guest = json_decode($_GET['guest'],true);
        $adult = ($guest['adult']!='') ? $guest['adult']: 0;
        $children = ($guest['children']!='') ? $guest['children']: 0;
        $count_rooms = 0;
        $output = "";
        $room_res = select("SELECT r.sr_no as sr_no, r.hotel as hotel, r.name as rname, r.adult as adult, r.children as children, r.price as price,r.wk_price as wkprice,r.quantity as quantity
                            FROM `room` r JOIN `contact_detail` cd ON r.hotel = cd.hotel
                            WHERE r.adult>=? AND r.children>=? AND r.status=? AND r.removed=?
                            AND r.wk_price<=? AND cd.address LIKE ?" ,
                            [$guest['adult'],$guest['children'],1,0,$max,"%$add%"],'iiiiis');
        while($room_data=mysqli_fetch_assoc($room_res)){
            if($chk_avail['checkin']!=''&&$chk_avail['checkout']!=''){
                $tb_query = "SELECT COUNT(*) AS `total_b` from `booking_order` WHERE `booking_status`=? AND `room_sr_no`=? AND `check_out`>? AND `check_in`<?";
                $values = ['booked',$room_data['sr_no'],$chk_avail['checkin'],$chk_avail['checkout']];
                $tb_fetch = mysqli_fetch_assoc(select($tb_query,$values,'siss'));
                if(($room_data['quantity']-$tb_fetch['total_b'])<=0){
                    continue;
                }
            }
            $fea_q = mysqli_query($con,"SELECT f.feature_name 
                                        FROM `feature` f INNER JOIN `room_features` rfea 
                                        ON f.sr_no = rfea.feature_sr_no 
                                        WHERE rfea.room_sr_no = '$room_data[sr_no]'");
            $feature_data = "";
            while($fea_row = mysqli_fetch_assoc($fea_q)){
                $feature_data .="<span class='badge rounded-pill bg-light text-dark text-wrap'>
                                $fea_row[feature_name]
                                </span>";
            }
            $fac_q = mysqli_query($con,"SELECT f.name 
                                        FROM `facility` f INNER JOIN `room_facilities` rfac 
                                        ON f.sr_no = rfac.facility_sr_no 
                                        WHERE rfac.room_sr_no = '$room_data[sr_no]'");
            $facility_data = "";
            while($fac_row = mysqli_fetch_assoc($fac_q)){
                $facility_data .="<span class='badge rounded-pill bg-light text-dark text-wrap'>
                                $fac_row[name]
                                </span>";
            }
            $thumb_image_q = select("SELECT * FROM `room` WHERE `status`=? AND `removed`=? AND `hotel`=? AND `sr_no`=?",[1,0,$room_data['hotel'],$room_data['sr_no']],'iiss');
            $thumb_res = mysqli_fetch_assoc($thumb_image_q);
            $room_thumb = ROOM_IMG_PATH.$thumb_res['image']; 
            $hotel_data = mysqli_fetch_assoc(select("SELECT * FROM `setting` WHERE `hotel`=?",[$room_data['hotel']],'s'));
            $book_btn="";
            if($hotel_data['shutdown']==0){
                $login=0;
                if(isset($_SESSION['login'])&& $_SESSION['login']==true){
                    $login=1;
                }
                $book_btn="<button onclick='checkLoginToBook($login,$room_data[sr_no])' class='btn btn-sm w-100 text-white custom-bg shaadow-none'>Book Now</button>";
            }
            $rating_q = "SELECT AVG(rating) AS `avg_rating` FROM `rating_review` WHERE `room_sr_no`= '$room_data[sr_no]' ORDER BY `sr_no` DESC LIMIT 50";
            $rating_r = mysqli_query($con,$rating_q);
            $rating_fetch = mysqli_fetch_assoc($rating_r);
            $rating_d = "";
            if($rating_fetch['avg_rating']!=NULL){
                $rating_d = "<div class='rating mb-4'>
                    <h6 class='mb-1'>Rating</h6>
                    <span class='badge rounded-pill bg-light'>";
                
                for($i=0;$i<$rating_fetch['avg_rating'];$i++){
                    $rating_d.="<i class='bi bi-star-fill text-warning me-2'></i>";
                }
                $rating_d .="</span></div>";
            }else{
                $rating_d ="No reviews yet!";
            }
            $output.="
                <div class='card mb-4 border-0 shadow'>
                    <div class='row g-0 p-3 align-items-center'>
                        <div class='col-md-5 mb-lg-0 mb-lg-0 mb-3'>
                            <img src='$room_thumb' class='img-fluid rounded'>
                        </div>
                        <div class='col-md-5 px-lg-3 px-md-3 px-0'>
                            <h5 class='mb-3'>$room_data[rname]</h5>
                            <h6 class='mb-3'>Hotel: $room_data[hotel]</h6>
                            <div class='features mb-3'>
                                <h6 class='mb-1'>Features</h6>
                                $feature_data
                            </div>
                            <div class='facilities mb-3'>
                                <h6 class='mb-1'>Facilities</h6>
                                $facility_data
                            </div>
                            <div class='guests mb-3'>
                                <h6 class='mb-1'>Guests</h6>
                                <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                    $room_data[adult] Adults
                                </span>
                                <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                    $room_data[children] Children
                                </span>
                            </div>
                            $rating_d
                        </div>
                        <div class='col-md-2  mt-lg-0 mt-md-0 mt-4 text-center'>
                            <h6 class='mb-4'>$room_data[price]$ per night</h6>
                            <h6 class='mb-4'>$room_data[wkprice]$ per weekend</h6>
                            $book_btn
                            <a href='hoteru_room_details.php?id=$room_data[sr_no]' class='btn btn-sm w-100 btn-outline-dark shaadow-none'>More details</a>
                        </div>
                    </div>
                </div>
            ";
            $count_rooms++;
        }
        if($count_rooms>0){
            echo $output;
        }else{
            echo "<h3 class='text-center text-danger'>No rooms to show! Sorry ...</h3>";
        }
    }
?>