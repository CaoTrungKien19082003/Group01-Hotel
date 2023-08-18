<?php 
    require('../inc/db.php');
    require('../inc/essentials.php');
    Login();

    if(isset($_POST['get_general'])){
        $q= "SELECT * FROM `settings` WHERE `number`=?";
        $values=[1];
        $res=select($q, $values, "i");
        $data=mysqli_fetch_assoc($res);
        $json_data=json_encode($data);

        echo $json_data;
    }

    if(isset($_POST['update_shutdown'])){
        $frm_data=($_POST['update_shutdown']==0) ? 1:0;
        $q="UPDATE `settings` SET `shutdown`=? WHERE `number`=?";
        $values=[$frm_data, 1];
        $res=update($q, $values, 'ii');

        echo $res;
    }

    if(isset($_POST['update_general'])){
        $frm_data=filter($_POST);
        $q="UPDATE `settings` SET `site_title`=?, `site_about`=? WHERE `number`=?";
        $values=[$frm_data['site_title'], $frm_data['site_about'], 1];
        $res=update($q, $values, 'ssi');

        echo $res;
    }

    if(isset($_POST['get_contacts'])){
        $q="SELECT * FROM `contact_info` WHERE `number`=?";
        $values=[1];
        $res=select($q, $values, "i");
        $data=mysqli_fetch_assoc($res);
        $json_data=json_encode($data);
        echo $json_data;
    }

    if(isset($_POST['update_contacts'])){
        $frm_data=filter($_POST);
        $q="UPDATE `contact_info` SET `address`=?,`google_map`=?,`phone_01`=?,`phone_02`=?,`email`=?,`fb`=?,`tw`=?,`insta`=?,`iframe`=? WHERE `number`=?";
        $values=[$frm_data['address'], $frm_data['gmap'], $frm_data['phone_01'], $frm_data['phone_02'], $frm_data['email'], $frm_data['fb'], $frm_data['tw'], $frm_data['insta'], $frm_data['iframe'],1];
        $res=update($q, $values, 'sssssssssi');

        echo $res;
    }

    if (isset($_POST['add_member'])){
        $frm_data=filter($_POST);

        $img_r=uploadImage($_FILES['picture'], TEAM);
        if ($img_r=='inv_file'){
            echo $img_r;
        }
        else if ($img_r=='inv_size'){
            echo $img_r;
        }
        if ($img_r=='upload_failed'){
            echo $img_r;
        }
        else{
            $q="INSERT INTO `management_team`(`name`, `picture`) VALUES (?, ?)";
            $values=[$frm_data['name'], $img_r];
            $res=insert($q, $values, 'ss');
            echo $res;
        }
    }

    if(isset($_POST['get_members'])){
        $res=selectAll('management_team');
        while($row=mysqli_fetch_assoc($res)){
            echo <<< data
                <div class="col-md-2 mb-3">
                    <div class="card-deck d-flex">
                        <div class="card text-center" style="width: 18rem;">
                            <img src="http://127.0.0.1/Group01-Hotel/images/team/$row[picture]">
                            <div class="card-img-overlay text-end">
                                <button class="btn btn-danger btn-sm shadow-none h-font-1" onclick="remove_member($row[number])">
                                    <i class="bi bi-trash"></i>Delete
                                </button>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center h-font-1 text-info">$row[name]</h5>
                            </div>
                        </div>
                        
                    </div>
                </div>
            data;
        }
    }

    if(isset($_POST['remove_member'])){
        $frm_data=filter($_POST);
        $values=[$frm_data];

        $pre_q="SELECT `picture` FROM `management_team` WHERE `number`=?";
        $res=select($pre_q, $values, 'i');
        $img=mysqli_fetch_assoc($res);
        $img=$img['picture'];

        if (deleteImage($img, TEAM)){
            $q="DELETE FROM `management_team` WHERE `number`=?";
            $res=remove($q, $values, 'i');
            echo $res;
        }

        else{
            echo 0;
        }
    }
?>