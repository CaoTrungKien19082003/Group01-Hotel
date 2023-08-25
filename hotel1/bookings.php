<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc1/links.php'); ?>
    <title>Hoteru - Confirm Booking</title>
</head>
<body class="bg-light">
    <?php require('inc1/header.php');?>

    <div class="container">
        <div class="row">
            <div class="col-12 my-5 px-4">
                <h2 class="fw-bold">BOOKING</h2>
                <div style="font-size: 14px">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">BOOKINGS</a>
                </div>
            </div>
            <?php

                $query="SELECT bo.*, bd.* FROM `booking_order` bo
                INNER JOIN `booking_details` bd ON bo.booking_id=bd.booking_id
                WHERE (bo.booking_status='cancelled' OR bo.booking_status='booked' OR bo.booking_status='payment failed')
                AND bo.id=?";
                $values = [$_SESSION['uId']];
                $res=select($query,$values,"s");

                while($data=mysqli_fetch_assoc($res)){
                    $date=date("d-m-Y", strtotime($data['datentime']));
                    $checkin=date("d-m-Y", strtotime($data['check_in']));
                    $checkout=date("d-m-Y", strtotime($data['check_out']));

                    $status_bg="";
                    $btn="";

                    if ($data['booking_status']=='booked'){
                        $status_bg='bg-success';
                        if ($data['arrival']==1){
                            $btn="<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>
                            ";
                            if($data['rate_review']==0){
                                $btn.="<button type='button' onclick='review_booking($data[booking_id],$data[room_sr_no])' data-bs-toggle='modal' data-bs-target='#reviewModal' class='btn btn-dark btn-sm shadow-none ms-2'>
                                <i class='bi bi-star-fill'></i> Rate & Review
                            </button>";
                            }
                        }

                        else{
                            $btn="
                            <button type='button' onclick='cancel_booking($data[booking_id])' class='btn btn-danger btn-sm shadow-none'>
                                <i class='bi bi-x-square'></i> Cancel
                            </button>
                            ";
                        }
                    }
        
                    else if ($data['booking_status']=='cancelled'){
                        $status_bg='bg-danger';

                        if ($data['refund']==0){
                            $btn="<span class='badge bg-primary'>Refund in progress!</span>";
                        }

                        else{
                            $btn="<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>";

                        }
                    }
        
                    else if ($data['booking_status']=='payment failed'){
                        $status_bg='bg-warning';
                        $btn="<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>
                        ";
                    }

                    echo<<<bookings
                        <div class='col-md-4 px-4 mb-4'>
                            <div class='bg-white p-3 rounded shadow-sm'>
                                <h5 class='fw-bold'>$data[room_name]</h5>
                                <p>$$data[price] per night</p>
                                <p>$$data[wkprice] per weekend</p>
                                <p>
                                    <b>Check in: </b>$checkin <br>
                                    <b>Check out: </b>$checkout
                                </p>

                                <p>
                                    <b>Total: </b>$$data[total_pay] <br>
                                    <b>Order ID: </b>$data[order_id] <br>
                                    <b>Date: </b>$date
                                </p>
                                <p>
                                    <span class='badge $status_bg'>$data[booking_status]</span>
                                </p>
                                $btn
                            </div>
                        </div>
                    bookings;
                }
            ?>
        </div>
    </div>
    <div class="modal fade" id="reviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="review-form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-item-center">
                            <i class="bi bi-chat-heart-fill fs-3 me-2"></i>Rate and Review</h5>
                        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <select class="form-select shadow-none" name="rating">
                                <option value="5">Excellent</option>
                                <option value="4">Good</option>
                                <option value="3">Moderate</option>
                                <option value="2">Pretty bad</option>
                                <option value="1">Bad</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Review</label>
                            <textarea name="review" rows="3" class="form-control shadow-none" required></textarea>
                        </div>
                        <input type="hidden" name="booking_id">
                        <input type="hidden" name="room_sr_no">
                        <div class="text-end">
                            <button type="submit" class="btn custom-bg text-white bt-sm shadow-none">SUBMIT</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php 
        if(isset($_GET['cancel_status'])){
            alert('success', 'Cancellation succeeded!');
        }else if(isset($_GET['review_status'])){
            alert('success', 'Thank you for rating and review!');
        }

    ?>

    <?php require('inc1/footer.php');?>

    <script>
    function cancel_booking(id){
        if (confirm("Are you sure you want to cancel this booking?")){
            let xhr=new XMLHttpRequest();
            xhr.open("POST", "ajax/bookings.php", true);

            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload=function(){
                console.log(this.responseText);
                if (this.responseText==1){                
                    window.location.href="bookings.php?cancel_status=true"
                }    
                else{
                    alert('error', 'Cancellation failed!');
                }
            } 

            xhr.send('cancel_booking&id='+id);
        }
    }
    let review_form = document.getElementById('review-form');
    function review_booking(bid,rsn){
        review_form.elements['booking_id'].value=bid;
        review_form.elements['room_sr_no'].value=rsn;
    }
    review_form.addEventListener('submit',function(e){
        e.preventDefault();
        let data= new FormData();
        data.append('review_form','');
        data.append('rating',review_form.elements['rating'].value);
        data.append('review',review_form.elements['review'].value);
        data.append('booking_id',review_form.elements['booking_id'].value);
        data.append('room_sr_no',review_form.elements['room_sr_no'].value);
        var myModal = document.getElementById('reviewModal');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/review_booking.php", true);
        xhr.onload = function(){
            if(this.responseText == 1){
                window.location.href = 'bookings.php?review_status=true';
            }else{
                var myModal = document.getElementById('reviewModal');
                var modal = bootstrap.Modal.getInstance(myModal);
                modal.hide();
                alert('error',"Rating & Review failed!");
            }
        }
        xhr.send(data);
    });
        
    </script>
</body>
</html>