<?php
    require('../inc/db_config.php');
    require('../inc/essentials.php');
    adminLogin();
    if(isset($_POST['add_feature'])){
        $frm_data = filteration($_POST);
        $q = "INSERT INTO `feature`(`feature_name`,`hotel`) VALUES (?,?)";
        $values = [$frm_data['name'],$_SESSION['adminCode']];
        $res = insert($q,$values,'ss');
        echo $res;
    }
    if(isset($_POST['get_feature'])){
        $q = "SELECT * FROM `feature` WHERE `hotel`=?";
        $values = [$_SESSION['adminCode']];
        $res=select($q,$values,"s");
        $i=1;
        while($row=mysqli_fetch_assoc($res)){
            echo <<<data
                <tr>
                    <td>$i</td>
                    <td>$row[feature_name]</td>
                    <td>
                    <button type="button" onclick="rem_feature($row[sr_no])" class="btn btn-danger btn-sm shadow-none">
                        <i class="bi bi-trash"></i>Delete
                    </button>
                    </td>
                </tr>
            data;
            $i++;
        }
    }
    if(isset($_POST['rem_feature'])){
        $frm_data = filteration($_POST);
        $values = [$frm_data['rem_feature'],$_SESSION['adminCode']];
        $check_q = select("SELECT * FROM `room_features` WHERE `feature_sr_no`=?",[$frm_data['rem_feature']],'i');
        if(mysqli_num_rows($check_q)==0){
            $q = "DELETE FROM `feature` WHERE `sr_no`=? AND `hotel`=?";
            $res = delete($q,$values,'is');
            echo $res;
        }
        else{
            echo 'room_added';
        }
    }
    if(isset($_POST['add_facility'])){
        $frm_data = filteration($_POST);
        $img_ri = uploadSVGImage($_FILES['icon'],FACILITY_FOLDER);
        $img_rp = uploadImage($_FILES['picture'],FACILITY_FOLDER);
        if($img_ri == 'inv_img'){
            echo $img_ri;
        }
        else if($img_rp == 'inv_img'){
            echo $img_rp;
        }
        else if($img_ri == 'inv_size'){
            echo $img_ri;
        }
        else if($img_rp == 'inv_size'){
            echo $img_rp;
        }
        else if($img_ri == 'upd_failed'){
            echo $img_ri;
        }
        else if($img_rp == 'upd_failed'){
            echo $img_rp;
        }
        else{
            $q = "INSERT INTO `facility`(`name`, `icon`, `picture`,`thumb` `description`, `hotel`) VALUES (?,?,?,?,?,?)";
            $values = [$frm_data['name'],$img_ri,$img_rp,$frm_data['thumb'],$frm_data['desc'],$_SESSION['adminCode']];
            $res = insert($q,$values,'ssssss');
            echo $res;
        }
    }
    if(isset($_POST['get_facility'])){
        $q = "SELECT * FROM `facility` WHERE `hotel`=?";
        $values = [$_SESSION['adminCode']];
        $res=select($q,$values,"s");
        $i=1;
        $path=FACILITY_IMG_PATH;
        while($row=mysqli_fetch_assoc($res)){
            echo <<<data
                <tr>
                    <td>$i</td>
                    <td><img src="$path$row[icon]" width="30px"></td>
                    <td><img src="$path$row[picture]" width ="100px"></td>
                    <td>$row[name]</td>
                    <td>$row[thumb]</td>
                    <td>$row[description]</td>
                    <td>
                    <button type="button" onclick="rem_facility($row[sr_no])" class="btn btn-danger btn-sm shadow-none">
                        <i class="bi bi-trash"></i>Delete
                    </button>
                    </td>
                </tr>
            data;
            $i++;
        }
    }
    if(isset($_POST['rem_facility'])){
        $frm_data = filteration($_POST);
        $values = [$frm_data['rem_facility'],$_SESSION['adminCode']];
        $check_q = select("SELECT * FROM `room_facilities` WHERE `facility_sr_no`=?",[$frm_data['rem_facility']],'i');
        $pre_q = select("SELECT * FROM `facility` WHERE `sr_no`=? AND `hotel`=?",$values,'is');
        $img = mysqli_fetch_assoc($pre_q);
        if(mysqli_num_rows($check_q)==0 && deleteImage($img['icon'],FACILITY_FOLDER) && deleteImage($img['picture'],FACILITY_FOLDER)){
            $q = "DELETE FROM `facility` WHERE `sr_no`=? AND `hotel`=?";
            $res = delete($q,$values,'is');
            echo $res;
        }
        else{
            echo 'room_added';
        } 
    }
?>