<?php
    require('../inc/db_config.php');
    require('../inc/essentials.php');
    adminLogin();
    if(isset($_POST['add_room'])){
        $feature = filteration(json_decode($_POST['feature']));
        $facility = filteration(json_decode($_POST['facility']));
        $frm_data = filteration($_POST);
        $img_r = uploadImage($_FILES['image'],ROOM_FOLDER);
        $flag=0;
        if($img_r == 'inv_img'){
            echo $img_r;
        }
        else if($img_r == 'inv_size'){
            echo $img_r;
        }
        else if($img_r == 'upd_failed'){
            echo $img_r;
        }
        else{
            $q1 = "INSERT INTO `room`(`name`, `area`, `price`, `wk_price`, `quantity`, `adult`, `children`, `description`, `image`, `star`, `hotel`,`removed`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
            $values = [$frm_data['name'],$frm_data['area'],$frm_data['price'],$frm_data['wk_price'],$frm_data['quantity'],$frm_data['adult'],$frm_data['children'],$frm_data['desc'],$img_r,0,$_SESSION['adminCode'],0];
            if(insert($q1,$values,'siiiiiissisi')){
                $flag=1;
            }
            $room_id = mysqli_insert_id($con);
            $q2 = "INSERT INTO `room_facilities`(`room_sr_no`, `facility_sr_no`) VALUES (?,?)";
            if($stmt = mysqli_prepare($con,$q2)){
                foreach($facility as $f){
                    mysqli_stmt_bind_param($stmt,'ii',$room_id,$f);
                    mysqli_stmt_execute($stmt);
                }
                mysqli_stmt_close($stmt);
            }   
            else{
                $flag = 0;
                die('query cannot be prepared! - Insert');
            }
            $q3 = "INSERT INTO `room_features`(`room_sr_no`, `feature_sr_no`) VALUES (?,?)";
            if($stmt = mysqli_prepare($con,$q3)){
                foreach($feature as $fe){
                    mysqli_stmt_bind_param($stmt,'ii',$room_id,$fe);
                    mysqli_stmt_execute($stmt);
                }
                mysqli_stmt_close($stmt);
            }   
            else{
                $flag = 0;
                die('query cannot be prepared! - Insert');
            }
            $q4 = "INSERT INTO `room_images`(`room_sr_no`, `image`) VALUES (?,?)";
            if($stmt = mysqli_prepare($con,$q4)){
                mysqli_stmt_bind_param($stmt,'is',$room_id,$img_r);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
            else{
                $flag = 0;
                die('query cannot be prepared! - Insert');
            }
            if($flag==0){
                echo 1;
            }
            else{
                echo 0;
            }
        }
    }
    if(isset($_POST['get_room'])){
        $q = "SELECT * FROM `room` WHERE `hotel`=? AND `removed`=?";
        $values = [$_SESSION['adminCode'],0];
        $res=select($q,$values,"si");
        $i=1;
        $data = "";
        $path=ROOM_IMG_PATH;
        while($row=mysqli_fetch_assoc($res)){
            if($row['status']==1){
                $status= "<button onclick='toggle_status($row[sr_no],0)' class='btn btn-dark btn-sm shadow-none'>active</button>";
            }
            else{
                $status= "<button onclick='toggle_status($row[sr_no],1)' class='btn btn-warning btn-sm shadow-none'>inactive</button>";
            }
            $data.="
                <tr class='align-middle'>
                    <td>$i</td>
                    <td>$row[name]</td>
                    <td><img src= '$path$row[image]' width ='100px'></td>
                    <td>$row[area] sq.m. </td>
                    <td>
                        <span class='badge rounded-pill bg-light text-dark'>
                            Adult: $row[adult]
                        </span><br>
                        <span class='badge rounded-pill bg-light text-dark'>
                            Children: $row[children]
                        </span><br>
                    </td>
                    <td>$row[price] dollars</td>
                    <td>$row[wk_price] dollars</td>
                    <td>$row[star]</td>
                    <td>$row[quantity]</td>
                    <td>$status</td>
                    <td>
                        <button type='button' onclick='edit_detail($row[sr_no])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-room-s'>
                            <i class='bi bi-pencil-square'></i>
                        </button>
                        <button type='button' onclick=\"room_image($row[sr_no],'$row[name]')\" class='btn btn-info shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#room-image'>
                            <i class='bi bi-images'></i>
                        </button>
                        <button type='button' onclick='remove_room($row[sr_no])' class='btn btn-danger shadow-none btn-sm'>
                            <i class='bi bi-trash'></i>
                        </button>
                    </td>
                </tr>
            ";
            $i++;
        }
        echo $data;
    }
    if(isset($_POST['get_one_room'])){
        $frm_data=filteration($_POST);
        $res1 = select("SELECT * FROM `room` WHERE `sr_no` = ? AND `hotel` = ?",[$frm_data['get_one_room'],$_SESSION['adminCode']],'is');
        $res2 = select("SELECT * FROM `room_features` WHERE `room_sr_no` = ?",[$frm_data['get_one_room']],'i');
        $res3 = select("SELECT * FROM `room_facilities` WHERE `room_sr_no` = ?",[$frm_data['get_one_room']],'i');
        $roomdata=mysqli_fetch_assoc($res1);
        $features=[];
        $facilities=[];
        if(mysqli_num_rows($res2)>0){
            while($row=mysqli_fetch_assoc($res2)){
                array_push($features,$row['feature_sr_no']);
            }
        }
        if(mysqli_num_rows($res3)>0){
            while($row=mysqli_fetch_assoc($res3)){
                array_push($facilities,$row['facility_sr_no']);
            }
        }
        $data = ["roomdata"=> $roomdata,"features"=>$features,"facilities"=>$facilities];
        $data = json_encode($data);
        echo $data;
    }
    if(isset($_POST['edit_room'])){
        $feature = filteration(json_decode($_POST['feature']));
        $facility = filteration(json_decode($_POST['facility']));
        $frm_data = filteration($_POST);
        $flag=0;
        if(empty($_FILES['image'])){
            $q11 = "UPDATE `room` SET `name`=?,`area`=?,`price`=?,`wk_price`=?,`quantity`=?,`adult`=?,`children`=?,`description`=? WHERE `sr_no`=? AND `hotel`=?";
            $values = [$frm_data['name'],$frm_data['area'],$frm_data['price'],$frm_data['wk_price'],$frm_data['quantity'],$frm_data['adult'],$frm_data['children'],$frm_data['desc'],$frm_data['room_id'],$_SESSION['adminCode']];
            if(update($q11,$values,'siiiiiisis')){
                $flag=1;
            }
        }
        else{
            $img_r = uploadImage($_FILES['image'],ROOM_FOLDER);
            
            if($img_r == 'inv_img'){
                echo $img_r;
            }
            else if($img_r == 'inv_size'){
                echo $img_r;
            }
            else if($img_r == 'upd_failed'){
                echo $img_r;
            }
            else{
                $q12 = "UPDATE `room` SET `name`=?,`area`=?,`price`=?,`wk_price`=?,`quantity`=?,`adult`=?,`children`=?,`description`=?,`image`=? WHERE `sr_no`=? AND `hotel`=?";
                $values = [$frm_data['name'],$frm_data['area'],$frm_data['price'],$frm_data['wk_price'],$frm_data['quantity'],$frm_data['adult'],$frm_data['children'],$frm_data['desc'],$img_r,$frm_data['room_id'],$_SESSION['adminCode']];
                if(update($q12,$values,'siiiiiissis')){
                    $flag=1;
                }
                insert("INSERT INTO `room_images`(`room_sr_no`, `image`) VALUES (?,?)",[$frm_data['room_id'],$img_r],'is');
            }
        }
        $del_feature= delete("DELETE FROM `room_features` WHERE `room_sr_no`=?",[$frm_data['room_id']],'i');
        $del_facility= delete("DELETE FROM `room_facilities` WHERE `room_sr_no`=?",[$frm_data['room_id']],'i');
        if(!($del_facility&&$del_feature)){
            $flag = 0;
        }
        $q2 = "INSERT INTO `room_facilities`(`room_sr_no`, `facility_sr_no`) VALUES (?,?)";

        if($stmt = mysqli_prepare($con,$q2)){
            foreach($facility as $f){
                mysqli_stmt_bind_param($stmt,'ii',$frm_data['room_id'],$f);
                mysqli_stmt_execute($stmt);
            }
            $flag=1;
            mysqli_stmt_close($stmt);
        }   
        else{
            $flag = 0;
            die('query cannot be prepared! - Insert');
        }
        $q3 = "INSERT INTO `room_features`(`room_sr_no`, `feature_sr_no`) VALUES (?,?)";
        if($stmt = mysqli_prepare($con,$q3)){
            foreach($feature as $fe){
                mysqli_stmt_bind_param($stmt,'ii',$frm_data['room_id'],$fe);
                mysqli_stmt_execute($stmt);
            }
            $flag=1;
            mysqli_stmt_close($stmt);
        }   
        else{
            $flag = 0;
            die('query cannot be prepared! - Insert');
        }
        if($flag==0){
            echo 1;
        }
        else{
            echo 0;
        }
    }
    if(isset($_POST['toggle_status'])){
        $frm_data=filteration($_POST);
        $q="UPDATE `room` SET `status` = ? WHERE `sr_no` = ? AND `hotel` = ?";
        $v = [$frm_data['value'],$frm_data['toggle_status'],$_SESSION['adminCode']];
        if(update($q,$v,'iis')){
            echo 1;
        }
        else{
            echo 0;
        }
    }
    if(isset($_POST['add_image'])){
        $frm_data = filteration($_POST);
        $img_r = uploadImage($_FILES['image'],ROOM_FOLDER);
        if($img_r == 'inv_img'){
            echo $img_r;
        }
        else if($img_r == 'inv_size'){
            echo $img_r;
        }
        else if($img_r == 'upd_failed'){
            echo $img_r;
        }
        else{
            $q = "INSERT INTO `room_images`(`room_sr_no`, `image`) VALUES (?,?)";
            $values = [$frm_data['room_id'],$img_r];
            $res = insert($q,$values,'is');
            echo $res;
        }
    }
    if(isset($_POST['get_room_image'])){
        $frm_data= filteration($_POST);
        $q = "SELECT * FROM `room_images` WHERE `room_sr_no`=?";
        $values = [$frm_data['get_room_image']];
        $res=select($q,$values,"i");
        $path=ROOM_IMG_PATH;
        while($row=mysqli_fetch_assoc($res)){
            if($row['thumb']==1){
                $thumb_btn = "<button onclick='thumb_image($row[sr_no],$row[room_sr_no],0)' class='btn btn-success shadow-none'>
                                    <i class='bi bi-check-lg'></i>
                                </button>";
            }
            else{
                $thumb_btn = "<button onclick='thumb_image($row[sr_no],$row[room_sr_no],1)' class='btn btn-secondary shadow-none'>
                                    <i class='bi bi-check-lg'></i>
                                </button>";
            }
            echo <<<data
                <tr class='align-middle'>
                    <td><img src="$path$row[image]"class="img-fluid"></td>
                    <td>$thumb_btn</td>
                    <td>
                        <button onclick='rem_image($row[sr_no],$row[room_sr_no])' class='btn btn-danger shadow-none'>
                            <i class='bi bi-trash'></i>
                        </button>
                    </td>
                </tr>
            data;
        }
    }
    if(isset($_POST['rem_image'])){
        $frm_data = filteration($_POST);
        $pre_q = select("SELECT * FROM `room_images` WHERE `sr_no`=? AND `room_sr_no`=?",[$frm_data['image_id'],$frm_data['room_id']],'ii');
        $img=mysqli_fetch_assoc($pre_q);
        $check_q = select("SELECT * FROM `room` WHERE `image`=?",[$img['image']],'s');
        if(mysqli_num_rows($check_q)==0 && deleteImage($img['image'],ROOM_FOLDER)){
            $res = delete("DELETE FROM `room_images` WHERE `room_sr_no`=? AND `sr_no`=?",[$frm_data['room_id'],$frm_data['image_id']],'is');
            echo $res;
        }
        else{
            echo 'room_added';
        }
    }
    if(isset($_POST['thumb_image'])){
        $frm_data=filteration($_POST);
        $q="UPDATE `room_images` SET `thumb` = ? WHERE `room_sr_no` = ? AND `sr_no`=?";
        $v = [$frm_data['val'],$frm_data['room_id'],$frm_data['image_id']];
        if(update($q,$v,'iii')){
            echo 1;
        }
        else{
            echo 0;
        }
    }
    if(isset($_POST['remove_room'])){
        $frm_data = filteration($_POST);
        $check_q = select("SELECT * FROM `room_images` WHERE `room_sr_no`=?",[$frm_data['room_id']],'i');
        //$pre_q = select("SELECT * FROM `facility` WHERE `sr_no`=? AND `hotel`=?",$values,'is');
        while($row = mysqli_fetch_assoc($check_q)){
            deleteImage($row['image'],ROOM_FOLDER);
        }
        $pre_q1 = delete("DELETE FROM `room_images` WHERE `room_sr_no`=?",[$frm_data['room_id']],'i');
        $pre_q2 = delete("DELETE FROM `room_features` WHERE `room_sr_no`=?",[$frm_data['room_id']],'i');
        $pre_q3 = delete("DELETE FROM `room_facilities` WHERE `room_sr_no`=?",[$frm_data['room_id']],'i');
        $pre_q4 = update("UPDATE `room` SET `removed`=? WHERE `sr_no`=? AND `hotel`=?",[1,$frm_data['room_id'],$_SESSION['adminCode']],'iis');

        if($pre_q1||$pre_q2||$pre_q3||$pre_q4){
            echo 1;
        }
        else{
            echo 0;
        } 
    }
?>