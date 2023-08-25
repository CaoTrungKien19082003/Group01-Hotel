<?php
    require('inc/essentials.php');
    require('inc/db_config.php');
    adminLogin();
    if(isset($_GET['seen'])){
        $frm_data=filteration($_GET);
        if($frm_data['seen']=='all'){
            $q = "UPDATE `user_note` SET `seen`=? WHERE `send_to`=?";
            $values = [1,'HTR'];
            if(update($q,$values,'is')){
                alert('success','Marked all as read!');
                redirect('hoteru_user_note.php');
            }
            else{
                alert('error','Operation failed!');
            }
        }
        else{
            $q = "UPDATE `user_note` SET `seen`=? WHERE `sr_no`=? AND `send_to`=?";
            $values = [1,$frm_data['seen'],'HTR'];
            if(update($q,$values,'iis')){
                alert('success','Marked as read!');
                redirect('hoteru_user_note.php');
            }
            else{
                alert('error','Operation failed!');
            }
        }
    }
    if(isset($_GET['del'])){
        $frm_data=filteration($_GET);
        if($frm_data['del']=='all'){
            $q = "DELETE FROM `user_note` WHERE `send_to`=?";
            $values = ['HTR'];
            if(update($q,$values,'s')){
                alert('success','All note deleted!');
                redirect('hoteru_user_note.php');
            }
            else{
                alert('error','Operation failed!');
            }
        }
        else{
            $q = "DELETE FROM `user_note` WHERE `sr_no`=? AND `send_to`=?";
            $values = [$frm_data['del'],'HTR'];
            if(update($q,$values,'is')){
                alert('success','Note deleted!');
                redirect('hoteru_user_note.php');
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
    <title>Hoteru - User Notes</title>
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
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col" width="20%">Subject</th>
                                <th scope="col" width="30%">Message</th>
                                <th scope="col">Date</th>
                                <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $q="SELECT * FROM `user_note` WHERE `send_to`=? ORDER BY `sr_no` DESC";
                                    $values=['HTR'];
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
                                                <td>$row[name]</td>
                                                <td>$row[email]</td>
                                                <td>$row[subject]</td>
                                                <td>$row[message]</td>
                                                <td>$row[date]</td>
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