<?php
    require('inc/essentials.php');
    require('inc/db_config.php');
    adminLogin();
    if(isset($_GET['seen'])){
        $frm_data=filteration($_GET);
        if($frm_data['seen']=='all'){
            $q = "UPDATE `rating_review` SET `seen`=? WHERE `hotel`=?";
            $values = [1,$_SESSION['adminCode']];
            if(update($q,$values,'is')){
                alert('success','Marked all as read!');
                sleep(2);
                redirect('hoteru_rate_review.php');
            }
            else{
                alert('error','Operation failed!');
            }
        }
        else{
            $q = "UPDATE `rating_review` SET `seen`=? WHERE `sr_no`=? AND `hotel`=?";
            $values = [1,$frm_data['seen'],$_SESSION['adminCode']];
            if(update($q,$values,'iis')){
                alert('success','Marked as read!');
                sleep(2);
                redirect('hoteru_rate_review.php');
            }
            else{
                alert('error','Operation failed!');
            }
        }
    }
    if(isset($_GET['del'])){
        $frm_data=filteration($_GET);
        if($frm_data['del']=='all'){
            $q = "DELETE FROM `rating_review` WHERE `hotel`=?";
            $values = [$_SESSION['adminCode']];
            if(update($q,$values,'s')){
                alert('success','All note deleted!');
                sleep(2);
                redirect('hoteru_rate_review.php');
            }
            else{
                alert('error','Operation failed!');
            }
        }
        else{
            $q = "DELETE FROM `rating_review` WHERE `sr_no`=? AND `hotel`=?";
            $values = [$frm_data['del'],$_SESSION['adminCode']];
            if(update($q,$values,'is')){
                alert('success','Note deleted!');
                sleep(2);
                redirect('hoteru_rate_review.php');
            }
            else{
                alert('error','Operation failed!');
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-eqiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoteru - Rate & Review</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">
    <?php require('inc/hoteru_header.php');?>
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3>USER NOTES</h3>
                <!--Carousel section-->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="text-end mb-4">
                            <a href="?seen=all" class="btn btn-dark rounded-pill shadow-none bt-sm">
                                <i class="bi bi-check-all"></i>Mark all read
                            </a>
                            <a href="?del=all" class="btn btn-danger rounded-pill shadow-none bt-sm">
                                <i class="bi bi-trash2-fill"></i>Delete all
                            </a>
                        </div>
                        <div class="table-responsive-md" style="height: 450px;overflow-y: scroll;">
                        <table class="table table-hover border">
                            <thead class="sticky-top">
                                <tr class="bg-dark text-light">
                                <th scope="col">#</th>
                                <th scope="col">Room Name</th>
                                <th scope="col">User Name</th>
                                <th scope="col">Rating</th>
                                <th scope="col" width="30%">Review</th>
                                <th scope="col">Date</th>
                                <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $q="SELECT rr.rating as rating,rr.review as review, rr.datentime as datentime, uc.name as uname, r.name as rname, rr.seen as seen,rr.sr_no as sr_no
                                    FROM `rating_review` rr
                                    INNER JOIN `user_cred` uc on  rr.id = uc.id
                                    INNER JOIN `room` r on  rr.room_sr_no = r.sr_no
                                     WHERE rr.hotel=? ORDER BY rr.sr_no DESC";
                                    $values=[$_SESSION['adminCode']];
                                    $res=select($q,$values,'s');
                                    $i=1;
                                    while($row=mysqli_fetch_assoc($res)){
                                        $seen='';
                                        if($row['seen']!=1){
                                            $seen = "<a href='?seen=$row[sr_no]' class='btn btn-sm rounded-pill btn-primary'>Mark as read</a>";
                                        }
                                        $seen.="<a href='?del=$row[sr_no]' class='btn btn-sm rounded-pill btn-danger mt-2'>Delete</a>";
                                        echo<<<note
                                            <tr>
                                                <td>$i</td>
                                                <td>$row[rname]</td>
                                                <td>$row[uname]</td>
                                                <td>$row[rating]</td>
                                                <td>$row[review]</td>
                                                <td>$row[datentime]</td>
                                                <td>$seen</td>
                                            </tr>
                                        note;
                                        $i++;
                                    }
                                ?>
                                
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <?php require('inc/scripts.php');?>
    <script src="script/hoteru_user_note.js"></script>
</body>
</html>