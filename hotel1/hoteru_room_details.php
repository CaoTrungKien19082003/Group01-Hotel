<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoteru - Rooms</title>
    <?php require('inc1/links.php');?>
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
        $room_res = select("SELECT * FROM `room` WHERE `sr_no`=? AND `status`=? AND `removed`=? AND `hotel`=?",[$data['id'],1,0,'HTR'],'iiis');
        if(mysqli_num_rows($room_res)==0){
            redirect('hoteru_rooms.php');
        }
        $room_data=mysqli_fetch_assoc($room_res);
        $hotel_data = mysqli_fetch_assoc(select("SELECT * FROM `setting` WHERE `hotel`=?",[$room_data['hotel']],'s'));
        $book_btn="";
        if($hotel_data['shutdown']==0){
            $login=0;
            if(isset($_SESSION['login'])&& $_SESSION['login']==true){
                $login=1;
            }
            $book_btn="<button onclick='checkLoginToBook($login,$room_data[sr_no])' class='btn w-100 text-white custom-bg shaadow-none'>Book Now</button>";
        }
        $rating_q = "SELECT AVG(rating) AS `avg_rating` FROM `rating_review` WHERE `room_sr_no`= '$room_data[sr_no]' ORDER BY `sr_no` DESC LIMIT 50";
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

    <div class="container">
        <div class="row">
            <div class="col-12 my-5 px-4">
                <h2 class="fw-bold"><?php echo $room_data['name']?></h2>
                <div style="font-size: 14px">
                <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                <span class="text-secondary"> > </span>
                <a href="hoteru_rooms.php" class="text-secondary text-decoration-none">ROOMS</a>
                </div>
            </div>
            <div class="col-md-12 col-lg-7 px-4">
                <div id="roomCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php 
                            $image_q = select("SELECT * FROM `room_images` WHERE `room_sr_no`=?",[$room_data['sr_no']],'i');
                            if(mysqli_num_rows($image_q)>0){
                                $active_class = 'active';
                                while($image_res = mysqli_fetch_assoc($image_q)){
                                    echo"
                                        <div class='carousel-item $active_class'>
                                            <img src='".ROOM_IMG_PATH.$image_res['image']."' class='d-block w-100 rounded'>
                                        </div>
                                    ";
                                    $active_class='';
                                }
                                
                            }
                        ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <div class="col-md-12 col-lg-5 px-4">
                <div class="card mb-4 border-3 shadow-sm rounded-3">
                    <div class="card-body">
                        <?php
                            $u_exist = select("SELECT * FROM `admin_cred` WHERE `hotel`=? LIMIT 1",[$room_data['hotel']],"s");
                            $u_fetch = mysqli_fetch_assoc($u_exist);
                            echo<<<price
                                <h4>$room_data[price]$ per night</h4>
                                <h4>$room_data[wk_price]$ per weekend</h4>
                            price; 
                            echo<<<hotel
                                <h6>Hotel:
                                    <a href="hotel.php?id=$room_data[hotel]" class="btn btn-sm btn-outline-dark shadow-none">$u_fetch[admin_name]</a>
                                </h6>
                            hotel;
                                echo<<<rating
                                    <h6> Rating: $rating_d </h6>
                                rating;
                            $fea_q = mysqli_query($con,"SELECT f.feature_name 
                                                FROM `feature` f INNER JOIN `room_features` rfea 
                                                ON f.sr_no = rfea.feature_sr_no 
                                                WHERE rfea.room_sr_no = '$room_data[sr_no]'");
                            $feature_data = "";
                            while($fea_row = mysqli_fetch_assoc($fea_q)){
                                $feature_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                                $fea_row[feature_name]
                                                </span>";
                            }
                            $fac_q = mysqli_query($con,"SELECT f.name 
                                                        FROM `facility` f INNER JOIN `room_facilities` rfac 
                                                        ON f.sr_no = rfac.facility_sr_no 
                                                        WHERE rfac.room_sr_no = '$room_data[sr_no]'");
                            $facility_data = "";
                            while($fac_row = mysqli_fetch_assoc($fac_q)){
                                $facility_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                                $fac_row[name]
                                                </span>";
                            }
                            echo<<<feature
                                <div class="mb-3">
                                    <h6 class="mb-1">Features</h6>
                                    $feature_data
                                </div>
                            feature;
                            echo<<<facility
                                <div class="mb-3">
                                    <h6 class="mb-1">Facilities</h6>
                                    $facility_data
                                </div>
                            facility;
                            echo<<<guest
                                <h6 class="mb-1">Guests</h6>
                                <span class="badge rounded-pill bg-light text-dark text-wrap me-1 mb-1">
                                    $room_data[adult] Adults
                                </span>
                                <span class="badge rounded-pill bg-light text-dark text-wrap me-1 mb-1">
                                    $room_data[children] Children
                                </span>
                            guest;

                            echo<<<area
                                <h6 class="mb-1">Area
                                <span class="badge rounded-pill bg-light text-dark text-wrapme-1 mb-1">
                                    $room_data[area] sq.m
                                </span>
                                </h6>
                            area;
                            echo<<<book
                                $book_btn
                            book;
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-4 px-4">
                <div class="mb-5">
                    <h5>Description</h5>
                    <p>
                        <?php 
                            echo $room_data['description'];
                        ?>
                    </p>
                </div>
                <div>
                    <h5 class="mb-3">Review & Rating</h5>
                        <div>
                        <?php 
                            $review_q="SELECT rr.rating as rating,rr.review as review, rr.datentime as datentime, uc.name as uname, uc.profile as prof, r.name as rname, rr.seen as seen,rr.sr_no as sr_no
                            FROM `rating_review` rr
                            INNER JOIN `user_cred` uc on  rr.id = uc.id
                            INNER JOIN `room` r on  rr.room_sr_no = r.sr_no
                            WHERE rr.room_sr_no ='$data[id]'LIMIT 6";
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
                                    <div>
                                        <div class="d-flex align-items-center mb-2">
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
                </div>
            </div>
        </div>
    </div>
    <?php 
        require('inc1/footer.php');
    ?>

</body>

</html>