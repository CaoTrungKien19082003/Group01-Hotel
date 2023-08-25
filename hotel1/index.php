<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoteru - Home page</title>
    <?php require('inc1/links.php');?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <style>
        .availability-form {
            margin-top: -50px;
            z-index: 2;
            position: relative;
        }
        @media screen and (max-wid: 575px) {
            .availability-form {
                margin-top: 25px;
                padding: 0 35px;
            }
        }
    </style>
</head>

<body>
    <?php 
        require('inc1/header.php');
    ?>
    <!-- Xoay -->
    <div class="container-fluid">
        <div class="swiper swiper-container">
            <div class="swiper-wrapper">
                <?php
                   $q = selectAll("`carousel`");
                   while($row=mysqli_fetch_assoc($q)){
                       $path = CAROUSEL_IMG_PATH;
                       echo <<<data
                           <div class="swiper-slide">
                                <img src="$path$row[image]" class="w-100 d-block" />
                            </div>
                       data;
                   } 
                ?>
            </div>
        </div>
    </div>
    <!-- Dò ngày còn phòng -->
    <div class="container availability-form">
        <div class="row">
            <div class="col-lg-12 bg-white shadow p-4 rounded">
                <h5 class="mb-4">Check Booking Avalability</h5>
                <form action="hoteru_rooms.php">
                    <div class="row align-itens-end">
                        <div class="col-lg-2 mb-3">
                            <label class="form-label" style="font-weight:500;">Check-in</label>
                            <input type="date" class="form-control shadow-none" name="checkin" required>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label class="form-label" style="font-weight:500;">Check-out</label>
                            <input type="date" class="form-control shadow-none" name="checkout" required>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label class="form-label" style="font-weight:500;">Adult</label>
                            <select class="form-select shadow-none">
                                <?php 
                                    $guest_q = mysqli_query($con,"SELECT MAX(adult) AS `max_adult`,MAX(children) AS `max_children` FROM `room` WHERE `status`='1' AND `removed`='0'");
                                    $guest_res = mysqli_fetch_assoc($guest_q);
                                    for($i=1;$i<=$guest_res['max_adult'];$i++){
                                        echo"<option value='$i'>$i</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label class="form-label" style="font-weight:500;">Children</label>
                            <select class="form-select shadow-none">
                                <?php 
                                    for($i=0;$i<=$guest_res['max_children'];$i++){
                                        echo"<option value='$i'>$i</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label class="form-label" style="font-weight:500;">City or Province</label>
                            <textarea name="add" class="form-control shadow-none" rows="1"></textarea>
                        </div>
                        <input type="hidden" name="chk_availability">
                        <div class="col-lg-1 mb-lg-3 mt-2">
                            <button type="submit" class="btn text-white shadow-none custom-bg">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Phòng -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">RECOMMENTDED ROOMS</h2>
    <div class="container">
        <div class="row">
        <?php
                $room_res = select("SELECT * FROM `room` WHERE `status`=? AND `removed`=? ORDER BY RAND ( ) LIMIT 3" ,[1,0],'ii');
                while($room_data=mysqli_fetch_assoc($room_res)){
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
                    $thumb_image_q = select("SELECT * FROM `room` WHERE `status`=? AND `removed`=? AND `sr_no`=?",[1,0,$room_data['sr_no']],'iis');
                    $thumb_res = mysqli_fetch_assoc($thumb_image_q);
                    $room_thumb = ROOM_IMG_PATH.$thumb_res['image']; 
                    $hotel_data = mysqli_fetch_assoc(select("SELECT * FROM `setting` WHERE `hotel`=?",[$room_data['hotel']],'s'));
                    $book_btn="";
                    if($hotel_data['shutdown']==0){
                        $login=0;
                        if(isset($_SESSION['login'])&& $_SESSION['login']==true){
                            $login=1;
                        }
                        $book_btn="<button onclick='checkLoginToBook($login,$room_data[sr_no])' class='btn btn-sm text-white custom-bg shaadow-none'>Book Now</button>";
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
                            $rating_d.="<i class='bi bi-star-fill text-warning'></i>";
                        }
                        $rating_d .="</span></div>";
                    }else{
                        $rating_d ="No reviews yet!";
                    }
                    echo <<<data
                        <div class="col-lg-4 col-md-6 my-3">
                            <div class="card border-0 shadow" style="max-width: 350px; margin: auto">
                                <img src="$room_thumb" class="card-img-top">
                                <div class="card-body">
                                    <h5>$room_data[name]</h5>
                                    <h6 class="mb-4">from $room_data[price]$ per night</h6>
                                    <div class="features mb-4">
                                        <h6 class="mb-1">Features</h6>
                                        $feature_data
                                    </div>
                                    <div class="facilities mb-4">
                                        <h6 class="mb-1">Facilities</h6>
                                        $facility_data
                                    </div>
                                    <div class="guests mb-4">
                                        <h6 class="mb-1">Guests</h6>
                                        <span class="badge rounded-pill bg-light text-dark text-wrap">
                                            $room_data[adult] Adults
                                        </span>
                                        <span class="badge rounded-pill bg-light text-dark text-wrap">
                                            $room_data[children] Children
                                        </span>
                                    </div>
                                    <div class="rating mb-4">
                                        $rating_d
                                    </div>
                                    <div class="d-flex justify-content-evenly mb-2">
                                        $book_btn
                                        <a href="hoteru_room_details.php?id=$room_data[sr_no]" class="btn btn-sm btn-outline-dark shadow-none">More details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    data;
                }
            ?>
            <div class="col-lg-12 text-center mt-5">
                <a href="hoteru_rooms.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Rooms >>></a>
            </div>
        </div>
    </div>
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR FACILITIES</h2>
    <div class="container">
        <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
            <?php
                $res = select("SELECT * FROM `facility` WHERE `hotel`=? ORDER BY `sr_no` DESC LIMIT 5",['HTR'],'s');
                $path=FACILITY_IMG_PATH;
                $i = 0;
                while($row=mysqli_fetch_assoc($res)){
                    echo <<< data
                        <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                            <img src="$path$row[icon]" width="50px">
                            <h5 class="mt-3">$row[name]</h5>
                        </div>
                    data;
                }
            ?>
            <div class="col-lg-12 text-center mt-5">
                <a href="hoteru_facilities.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Facility information >>></a>
            </div>
        </div>
    </div>
    <!--chứng thực-->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">TESTIMONIALS</h2>
    
    <div class="container mt-5">
        <div class="swiper swiper-testimonials">
            <div class="swiper-wrapper mb-5">
                <?php 
                    $review_q="SELECT rr.rating as rating,rr.review as review, rr.datentime as datentime, uc.name as uname, uc.profile as prof, r.name as rname, rr.seen as seen,rr.sr_no as sr_no
                    FROM `rating_review` rr
                    INNER JOIN `user_cred` uc on  rr.id = uc.id
                    INNER JOIN `room` r on  rr.room_sr_no = r.sr_no
                    ORDER BY rr.sr_no DESC LIMIT 6";
                    $review_r=mysqli_query($con,$review_q);
                    $img_path = USER_IMG_PATH;
                    if(mysqli_num_rows($review_r)==0){
                        echo 'No reviews yet!';
                    }else{
                        while($row = mysqli_fetch_assoc($review_r)){
                            $stars = "<i class='bi bi-star-fill text-warning'></i>";
                            for($i=1;$i<$row['rating'];$i++){
                                $stars.= "<i class='bi bi-star-fill text-warning'></i>";
                            }
                            echo<<<slide
                            <div class="swiper-slide bg-white p-4">
                                <div class="profile d-flex align-items-center mb-3">
                                    <img src='$img_path$row[prof]' width="30px">
                                    <h6 class="m-0 ms-2">$row[uname]</h6>
                                </div>
                                <p>
                                    $row[review]
                                </p>
                                <div class="rating">
                                    $stars
                                </div>
                            </div>
                            slide;
                        }
                    }
                ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <div class="col-lg-12 text-center mt-5">
                <a href="hoteru_about.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Know us more >>></a>
            </div>
    </div>
    <!-- Liên hệ -->
    <h2 class="mt-5 pt4 mb-4 text-center fw-bold h-font">REACH US</h2>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
                <iframe class="w-100 rounded" height="320px" src="<?php echo $contact_r['iframe']?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="bg-white p-4 rounded mb-4">
                    <h5>Call us</h5>
                    <a href="tel: +<?php echo $contact_r['pn1']?>" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i>+<?php echo $contact_r['pn1']?>
                    </a>
                    <br>
                    <?php
                        if($contact_r['pn2']!=''){
                            echo <<<data
                                <a href="tel: +$contact_r[pn2]>" class="d-inline-block mb-2 text-decoration-none text-dark">
                                    <i class="bi bi-telephone-fill"></i>+$contact_r[pn2]
                                </a>
                            data;
                        }
                    ?>
                </div>
                <div class="bg-white p-4 rounded mb-4">
                    <h5>Follow us</h5>
                    <?php
                        if($contact_r['tw']!=''){
                            echo <<<data
                                <a href="$contact_r[tw]" class="d-inline-block mb-1">
                                    <span class="badge bg-light text-dark fs-6 p-2">
                                        <i class="bi bi-twitter me-1"></i>Twitter
                                    </span>
                                </a>
                            data;
                        }
                    ?>
                    <br>
                    <?php
                        if($contact_r['ins']!=''){
                            echo <<<data
                                <a href="$contact_r[ins]" class="d-inline-block mb-1">
                                    <span class="badge bg-light text-dark fs-6 p-2">
                                        <i class="bi bi-instagram me-1"></i>Instagram
                                    </span>
                                </a>
                            data;
                        }
                    ?>
                    <br>
                    <?php
                        if($contact_r['fb']!=''){
                            echo <<<data
                                <a href="$contact_r[fb]" class="d-inline-block mb-1">
                                    <span class="badge bg-light text-dark fs-6 p-2">
                                        <i class="bi bi-facebook me-1"></i>Facebook
                                    </span>
                                </a>
                            data;
                        }
                    ?>
                    <br>
                    <?php
                        if($contact_r['tt']!=''){
                            echo <<<data
                                <a href="$contact_r[tt]" class="d-inline-block mb-1">
                                    <span class="badge bg-light text-dark fs-6 p-2">
                                        <i class="bi bi-tiktok me-1"></i>Tiktok
                                    </span>
                                </a>
                            data;
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="passrModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="passr-form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-item-center"><i class="bi bi-shield-lock-fill fs-3 me-2"></i>
                            Set up new password
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label">New Password</label>
                            <input name="npass" type="password" class="form-control shadow-none" required>
                            <input type="hidden" name="email">
                            <input type="hidden" name="token">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Confirm New Password</label>
                            <input name="cnpass" type="password" class="form-control shadow-none" required>
                        </div>
                        <div class="mb-2 text-end">
                            <button type="button" class="btn shadow-none p-0 me-2" data-bs-dismiss="modal">
                                CANCEL
                            </button>
                            <button type="submit" class="btn btn-dark shadow-none">SUBMIT</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php 
        require('inc1/footer.php');
    ?>
    <?php 
        if(isset($_GET['passf'])){
            $data = filteration($_GET);
            $t_date = date("Y-m-d");
            $query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire`=? LIMIT 1",[$data['email'],$data['token'],$t_date],'sss');
            if(mysqli_num_rows($query)==1){
                echo<<<showModal
                    <script>
                        var myModal = document.getElementById('passrModal');
                        myModal.querySelector("input[name='email']").value = '$data[email]';
                        myModal.querySelector("input[name='token']").value = '$data[token]'; 
                        var modal = bootstrap.Modal.getOrCreateInstance(myModal);
                        modal.show();
                    </script>
                showModal;
            }else{
                alert("error","Invalid or Expired link!");
            }
        }
        if(isset($_GET['b_success'])){
            alert("success","Booking success, the receptionist got your request");
            sleep(5);
            redirect('index.php');
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".swiper-container", {
            spaceBetween: 30,
            effect: "fade",
            loop: true,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            }
        });
        var swiper = new Swiper(".swiper-testimonials", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            slidesPerView: "3",
            loop: false,
            coverflowEffect: {
              rotate: 50,
              stretch: 0,
              depth: 100,
              modifier: 1,
              slideShadows: false,
            },
            pagination: {
              el: ".swiper-pagination",
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
          });
        //recover pass
        let passr_form = document.getElementById('passr-form');
        passr_form.addEventListener('submit',(e)=>{
            e.preventDefault();
            let data= new FormData();
            data.append('npass',passr_form.elements['npass'].value);
            data.append('cnpass',passr_form.elements['cnpass'].value);
            data.append('email',passr_form.elements['email'].value);
            data.append('token',passr_form.elements['token'].value);
            data.append('passr','');
            //alert('error',data.get('password'));
            var myModal = document.getElementById('passrModal');
            var modal = bootstrap.Modal.getInstance(myModal);
            
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/login_register.php", true);
            xhr.onload = function(){
                if(this.responseText == 'pass_mismatch'){
                    alert('error',"Password Mismatch!");
                }else if(this.responseText == 'old_pass'){
                    alert('error',"You entered the old password (congratulation?)!");
                }else if(this.responseText == 'upd_failed'){
                    alert('error',"Password update failed, Server down!");
                }else{
                    modal.hide();
                    alert('success',"Password updated successfully!");
                    passr_form.reset();
                }
            }
            xhr.send(data);
        });
    </script>
</body>

</html>