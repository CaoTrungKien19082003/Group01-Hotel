<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Confirm Booking</title>
</head>
<body class="bg-light">
    <?php require('inc/header.php');?>

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
                AND bo.user_id=6";

                $res=mysqli_query($con, $query);

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
                            <button type='button' class='btn btn-dark btn-sm shadow-none'>
                                <i class='bi bi-star-fill'></i> Rate & Review
                            </button>
                            ";
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
                                <p>
                                    <b>Check in: </b>$checkin <br>
                                    <b>Check out: </b>$checkout
                                </p>

                                <p>
                                    <b>Amount: </b>$$data[price] <br>
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

    <?php 
        if(isset($_GET['cancel_status'])){
            alert('success', 'Cancellation succeeded!');
        }

    ?>

    <?php require('inc/footer.php');?>

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
    </script>
</body>