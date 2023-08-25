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
    <?php
        if(!isset($_GET['id'])){
            redirect('hoteru_rooms.php');
        }
        $data = filteration($_GET);
        $hotel_res = select("SELECT * FROM `setting` WHERE `hotel`=?",[$data['id']],'s');
        if(mysqli_num_rows($hotel_res)==0){
            redirect('hoteru_rooms.php');
        }
        $hotel_data=mysqli_fetch_assoc($hotel_res);
        if($hotel_data['shutdown']==1){
            echo<<<alertbar
                <div class='bg-danger text-center p-2 fw-bold'>
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Bookings to this hotel are temporarily closed!
                </div>
            alertbar;
        }
    ?>
    <h1 class="mt-5 pt-4 mb-4 text-center fw-bold h-font"><?php echo $hotel_data['site_title']?></h1>
    <?php
            $rating_q = "SELECT AVG(rating) AS `avg_rating` FROM `rating_review` WHERE `hotel`= '$hotel_data[hotel]' ORDER BY `sr_no` DESC LIMIT 50";
            $rating_r = mysqli_query($con,$rating_q);
            $rating_fetch = mysqli_fetch_assoc($rating_r);
            $rating_d = "";
            if($rating_fetch['avg_rating']!=NULL){
                $rating_d = "<div class='rating mb-4'>
                    <span class='badge rounded-pill bg-light'>";
                for($i=0;$i<$rating_fetch['avg_rating'];$i++){
                    $rating_d.="<i class='bi bi-star-fill text-warning me-2'></i>";
                }
                $rating_d .="</span></div>";
            }else{
                $rating_d ="No reviews yet!";
            }
    ?>
    <h1 class='text-center'><?php echo $rating_d; ?></h1>
    <p class="text-center mt-3">
        <?php echo $hotel_data['site_about'];?>
    </p>
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR ROOMS</h2>
    <div class="container">
        <div class="row">
        <?php
                $room_res = select("SELECT * FROM `room` WHERE `status`=? AND `removed`=? AND `hotel`=? ORDER BY RAND ( )" ,[1,0,$data['id']],'iis');
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
    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">OUR FACILITIES</h2>
        <h4></h4>
        <div class="h-line bg-dark"></div>
    </div>
    <div class="container">
        <<?php
            $res = select("SELECT * FROM `facility` WHERE `hotel`=?",[$data['id']],'s');
            $path=FACILITY_IMG_PATH;
            $i = 0;
            while($row=mysqli_fetch_assoc($res)){
                if($i%2==0){
                    echo <<<data
                        <div class="row align-items-center bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                            <div class="col-md-12 col-lg-6 ml-auto order-lg-2 mb-5">
                                <img src="$path$row[picture]" alt="$row[name] image" class="img-fluid rounded">
                            </div>
                            <div class="col-md-12 col-lg-6 order-lg-1">
                                <img src="$path$row[icon]" alt="$row[name] svg" class="rounded" width="40px">
                                <h2>$row[name]</h2>
                                <p class="mb-4">$row[description]</p>
                            </div>
                        </div>
                    data;
                }
                else{
                    echo <<<data
                    <div class="row align-items-center bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                        <div class="col-md-12 col-lg-6 order-lg-2">
                            <img src="$path$row[icon]" alt="$row[name] svg" class="rounded" width="40px">
                            <h2>$row[name]</h2>
                            <p class="mb-4">$row[description]</p>
                        </div>
                        <div class="col-md-12 col-lg-6 ml-auto order-lg-1 mb-5">
                            <img src="$path$row[picture]" alt="$row[name] image" class="img-fluid rounded">
                        </div>
                    </div>
                data;
                }
                $i++;
            }
        ?>
    </div>
    <?php
        $cont_q = "SELECT * FROM `contact_detail` WHERE `hotel`=?";
        $values =[$data['id']];
        $cont_r=mysqli_fetch_assoc(select($cont_q,$values,'s'));
    ?>
    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">CONTACT US</h2>
        <div class="h-line bg-dark"></div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4">
                    <iframe class="w-100 rounded mb-4" height="320px" src="<?php echo $cont_r['iframe']?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <h5>Address</h5>
                    <a href="<?php echo $cont_r['gmap']?>" target="_blank" class="d-inline-block text-decoraton-none text-dark mb-2">
                        <i class="bi bi-geo-alt-fill"></i><?php echo $cont_r['address']?>
                    </a>
                    <h5 class="mt-4">Call us</h5>
                    <a href="<?php echo $cont_r['pn1']?>" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i>+<?php echo $cont_r['pn1']?>
                    </a>
                    <br>
                    <?php
                        if($contact_r['pn2']!=''){
                            echo <<<data
                                <a href="tel: +$cont_r[pn2]>" class="d-inline-block mb-2 text-decoration-none text-dark">
                                    <i class="bi bi-telephone-fill"></i>+$cont_r[pn2]
                                </a>
                            data;
                        }
                    ?>
                    <h5 class="mt-4">Email</h5>
                    <a href="mailto: <?php echo $cont_r['email']?>" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-envelope-at-fill"></i><?php echo $cont_r['email']?>
                    </a>
                    <h5 class="mt-4">Follow us</h5>
                    <?php
                        if($cont_r['tw']!=''){
                            echo <<<data
                                <a href="$cont_r[tw]" class="d-inline-block mb-1">
                                    <span class="badge bg-light text-dark fs-6 p-2">
                                        <i class="bi bi-twitter me-1"></i>Twitter
                                    </span>
                                </a>
                            data;
                        }
                    ?>
                    <br>
                    <?php
                        if($cont_r['ins']!=''){
                            echo <<<data
                                <a href="$cont_r[ins]" class="d-inline-block mb-1">
                                    <span class="badge bg-light text-dark fs-6 p-2">
                                        <i class="bi bi-instagram me-1"></i>Instagram
                                    </span>
                                </a>
                            data;
                        }
                    ?>
                    <br>
                    <?php
                        if($cont_r['fb']!=''){
                            echo <<<data
                                <a href="$cont_r[fb]" class="d-inline-block mb-1">
                                    <span class="badge bg-light text-dark fs-6 p-2">
                                        <i class="bi bi-facebook me-1"></i>Facebook
                                    </span>
                                </a>
                            data;
                        }
                    ?>
                    <br>
                    <?php
                        if($cont_r['tt']!=''){
                            echo <<<data
                                <a href="$cont_r[tt]" class="d-inline-block mb-1">
                                    <span class="badge bg-light text-dark fs-6 p-2">
                                        <i class="bi bi-tiktok me-1"></i>Tiktok
                                    </span>
                                </a>
                            data;
                        }
                    ?>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4">
                    <form method="POST">
                        <h5>Send us a message</h5>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Name</label>
                            <input name="name" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Email</label>
                            <input name="email" required type="email" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Subject</label>
                            <input name="subject" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Message</label>
                            <textarea name="message" required class="form-control shadow-none" rows="5" style="resize: none;"></textarea>
                        </div>
                        <button type="submit" name="send" class="btn text-white custom-bg mt-3">SEND</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    </script>
    <?php
        if(isset($_POST['send'])){
            $frm_data = filteration($_POST);
            $q = "INSERT INTO `user_note`(`name`, `email`, `subject`, `message`, `send_to`) VALUES (?,?,?,?,?)";
            $values = [$frm_data['name'],$frm_data['email'],$frm_data['subject'],$frm_data['message'],$data['id']];
            $res = insert($q,$values,'sssss');
            if($res==1){
                alert('success','Mail sent!');
            }
            else{
                alert('error','Server Down! Try again later.');
            }
        }
    ?>
    <?php 
        require('inc1/footer.php');
    ?>
</body>
</html>
