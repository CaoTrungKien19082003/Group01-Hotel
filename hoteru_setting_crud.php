<?php
    require('../inc/db_config.php');
    require('../inc/essentials.php');
    adminLogin();
    if(isset($_POST['get_general'])){
        $q = "SELECT * FROM `setting` WHERE `sr_no`=?";
        $values = [$_SESSION['adminId']];
        $res=select($q,$values,"i");
        $data=mysqli_fetch_assoc($res);
        $json_data=json_encode($data);
        echo $json_data;

    }
    if(isset($_POST['upd_general'])){
        $frm_data = filteration($_POST);
        $q = "UPDATE `setting` SET `site_title`=?,`site_about`=?  WHERE `sr_no`=?";
        $values = [$frm_data['site_title'],$frm_data['site_about'],$_SESSION['adminId']];
        $res = update($q,$values,'ssi');
        echo $res;
    }
    if(isset($_POST['upd_shutdown'])){
        $frm_data = ($_POST['upd_shutdown']==0) ? 1 : 0;
        $q = "UPDATE `setting` SET `shutdown`=? WHERE `sr_no`=?";
        $values = [$frm_data,$_SESSION['adminId']];
        $res = update($q,$values,'ii');
        echo $res;
    }
    if(isset($_POST['get_contact'])){
        $q = "SELECT * FROM `contact_detail` WHERE `sr_no`=?";
        $values = [$_SESSION['adminId']];
        $res=select($q,$values,"i");
        $data=mysqli_fetch_assoc($res);
        $json_data=json_encode($data);
        echo $json_data;
    }
    if(isset($_POST['upd_contact'])){
        $frm_data = filteration($_POST);
        $q = "UPDATE `contact_detail` SET `address`=?,`gmap`=?,`pn1`=?,`pn2`=?,`email`=?,`fb`=?,`ins`=?,`tw`=?,`tt`=?,`iframe`=? WHERE `hotel`=?";
        $values = [$frm_data['address'],$frm_data['gmap'],$frm_data['pn1'],$frm_data['pn2'],$frm_data['email'],$frm_data['fb'],$frm_data['ins'],$frm_data['tw'],$frm_data['tt'],$frm_data['iframe'],$_SESSION['adminCode']];
        $res = update($q,$values,'sssssssssss');
        echo $res;
    }
    if(isset($_POST['add_member'])){
        $frm_data = filteration($_POST);
        $img_r = uploadImage($_FILES['picture'],ABOUT_FOLDER);
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
            $q = "INSERT INTO `team_detail`(`name`, `picture`, `hotel`) VALUES (?,?,?)";
            $values = [$frm_data['name'],$img_r,$_SESSION['adminCode']];
            $res = insert($q,$values,'sss');
            echo $res;
        }
    }
    if(isset($_POST['get_member'])){
        $q = "SELECT * FROM `team_detail` WHERE `hotel`=?";
        $values = [$_SESSION['adminCode']];
        $res=select($q,$values,"s");
        while($row=mysqli_fetch_assoc($res)){
            $path = ABOUT_IMG_PATH;
            echo <<<data
                <div class="col-md-2 mb-3">
                    <div class="card bg-dark text-white">
                        <img src="$path$row[picture]" class="card-img">
                        <div class="card-img-overlay text-end">
                            <button type="button" onclick="rem_member($row[sr_no])" class="btn btn-danger btn-sm shadow-none">
                                <i class="bi bi-person-fill-dash"></i>Delete
                            </button>
                        </div>
                        <p class="card-text text-center px-3 py-2"><small>$row[name]</small></p>
                    </div>
                </div>
            data;
        }
        
    }
    if(isset($_POST['rem_member'])){
        $frm_data = filteration($_POST);
        $values = [$frm_data['rem_member'],$_SESSION['adminCode']];
        $pre_q = "SELECT * FROM `team_detail` WHERE `sr_no`=? AND `hotel`=?";
        $res = select($pre_q,$values,'is');
        $img = mysqli_fetch_assoc($res);
        if(deleteImage($img['picture'],ABOUT_FOLDER)){
            $q = "DELETE FROM `team_detail` WHERE `sr_no`=? AND `hotel`=?";
            $res = delete($q,$values,'is');
            echo $res;
        }
        else{
            echo 0;
        }
    }
?>