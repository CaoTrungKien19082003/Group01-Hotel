<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Booking</title>
    <?php require('inc1/links.php');?>
</head>

<body>
    <?php 
        require('inc1/header.php');
        
    ?>
    <?php
        if(!isset($_GET['id'])){
            redirect('hoteru_rooms.php');
        }else if(!(isset($_SESSION['login'])&&$_SESSION['login']==true)){
            redirect('hoteru_rooms.php');
        }
        $data = filteration($_GET);
        $room_res = select("SELECT * FROM `room` WHERE `sr_no`=? AND `status`=? AND `removed`=? AND `hotel`=?",[$data['id'],1,0,'HTR'],'iiis');
        if(mysqli_num_rows($room_res)==0){
            redirect('hoteru_rooms.php');
        }
        $room_data=mysqli_fetch_assoc($room_res);
        $hotel_data = mysqli_fetch_assoc(select("SELECT * FROM `setting` WHERE `hotel`=?",[$room_data['hotel']],'s'));
        if($hotel_data['shutdown']==1){
            redirect('hoteru_rooms.php');
        }else{
            $_SESSION['room'] = [
                "sr_no" => $room_data['sr_no'],
                "name" => $room_data['name'],
                "price" => $room_data['price'],
                "wkprice" => $room_data['wk_price'],
                "hotel" => $room_data['hotel'],
                "in" =>  null,
                "out" => null,
                "day" => null,
                "wk" => null,
                "payment" => null,
                "available" => false,
            ];
            $u_res = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1",[$_SESSION['uId']],"i");
            $u_data = mysqli_fetch_assoc($u_res);
        }
    ?>

    <div class="container">
        <div class="row">
            <div class="col-12 my-5 px-4">
                <h2 class="fw-bold">CONFIRM BOOKING</h2>
                <div style="font-size: 14px">
                <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                <span class="text-secondary"> > </span>
                <a href="hoteru_rooms.php" class="text-secondary text-decoration-none">ROOMS</a>
                <span class="text-secondary"> > </span>
                <a href="#" class="text-secondary text-decoration-none">CONFIRM</a>
                </div>
            </div>
            <div class="col-md-12 col-lg-7 px-4">
                <?php
                $price=0;
                $thumb_image_q = select("SELECT * FROM `room` WHERE `status`=? AND `removed`=? AND `hotel`=? AND `sr_no`=?",[1,0,$room_data['hotel'],$room_data['sr_no']],'iiss');
                $thumb_res = mysqli_fetch_assoc($thumb_image_q);
                $room_thumb = ROOM_IMG_PATH.$thumb_res['image'];
                echo<<<data
                    <div class="card p-3 shadow-sm rounded">
                        <img src="$room_thumb" class="img-fluid rounded mb-3">
                        <h5>$room_data[name]</h5>
                        <h6>$$room_data[price] per night</h6>
                        <h6>$$room_data[wk_price] per weekend and holiday</h6>
                    </div>
                data;
                ?>
            </div>
            <div class="col-md-12 col-lg-5 px-4">
                <div class="card mb-4 border-3 shadow-sm rounded-3">
                    <div class="card-body">
                        <form action="pay_cod.php" id="booking-form">
                            <h6 class="mb-3">BOOKING DETAILS</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name</label>
                                    <input name="name" type="text" value="<?php echo $u_data['name']?>" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone number</label>
                                    <input name="phone" type="number" value="<?php echo $u_data['phone']?>" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="add" class="form-control shadow-none" rows="1" required><?php echo $u_data['user_address']?></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Check-in</label>
                                    <input name="checkin" onchange="check_avalability()" type="date" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Check-out</label>
                                    <input name="checkout" onchange="check_avalability()" type="date" class="form-control shadow-none" required>
                                </div>
                                <div class="col-12">
                                    <div class="spinner-border text-dark mb-3 d-none" id="info-loader" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <h6 class="mb-3 text-danger" id="pay-info">Please choose the check-in date and check-out date</h6>
                                    <button name="cod-pay" class="btn w-100 text-white custom-bg shadow-none mb-1" disabled>Pay at hotel</button>
                                    <button name="momo-pay" class="btn w-100 text-white custom-bg shadow-none mb-1" disabled>Pay with Momo</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php 
        require('inc1/footer.php');
    ?>
    <script>
        let booking_form = document.getElementById('booking-form');
        let info_loader = document.getElementById('info-loader');
        let pay_info = document.getElementById('pay-info');
        function check_avalability(){
            let checkin_val = booking_form.elements['checkin'].value;
            let checkout_val = booking_form.elements['checkout'].value;
            booking_form.elements['cod-pay'].setAttribute('disabled',true);
            booking_form.elements['momo-pay'].setAttribute('disabled',true);
            if(checkin_val!='' && checkout_val!=''){
                pay_info.classList.add('d-none');
                pay_info.classList.replace('text-dark','text-danger');
                info_loader.classList.remove('d-none');
                let data = new FormData();
                data.append('check_avalability','');
                data.append('check_in',checkin_val);
                data.append('check_out',checkout_val);
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/confirm_booking.php", true);
                xhr.onload = function(){
                    let data = JSON.parse(this.responseText);
                    if(data.status == 'check_in_out_equal'){
                        pay_info.innerText = "You cannot check-out on the same day!";
                    }else if(data.status == 'check_out_earlier'){
                        pay_info.innerText = "Check-out date is earlier than check-in date!";
                    }else if(data.status == 'check_in_earlier'){
                        pay_info.innerText = "You cannot check-in on that day!";
                    }else if(data.status == 'unavailable'){
                        pay_info.innerText = "Room not available for this check-in date!";
                    }else{
                        pay_info.innerHTML = "No. of Days: "+data.days+"<br>No. of Weekend days: "+data.weekends+"<br>Total Amount to pay: $"+data.payment;
                        pay_info.classList.replace('text-danger','text-dark');
                        booking_form.elements['cod-pay'].removeAttribute('disabled');
                        //booking_form.elements['momo-pay'].removeAttribute('disabled');
                    }
                    pay_info.classList.remove('d-none');
                    info_loader.classList.add('d-none');
                }
                xhr.send(data);
            }
        }    
    </script>
</body>

</html>