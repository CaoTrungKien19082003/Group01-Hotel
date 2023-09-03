<?php
    require('../inc/db_config.php');
    require('../inc/essentials.php');
    adminLogin();
    require('../../vendor/autoload.php');
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    
    if(isset($_POST['get_users'])){
        $res=selectAll("`user_cred`");
        $i=1;
        $data = "";
        $path=USER_IMG_PATH;
        while($row=mysqli_fetch_assoc($res)){
            $warn_mail="<button type='button' onclick='warn_user($row[id])' class='btn btn-warning shadow-none btn-sm'>
                    <i class='bi bi-envelope-at-fill'></i>
                </button>";
            $delete_btn ="<button type='button' onclick='remove_user($row[id])' class='btn btn-danger shadow-none btn-sm'>
                            <i class='bi bi-trash'></i>
                        </button>";
            $verified = "<span class='badge bg-danger'><i class='bi bi-x-lg'></i></span>";
            if($row['is_verified']){
                $verified = "<span class='badge bg-success'><i class='bi bi-check-lg'></i></span>";
            }
            $status= "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>active</button>";
            if($row['status']==0){
                $status= "<button onclick='toggle_status($row[id],1)' class='btn btn-warning btn-sm shadow-none'>inactive</button>";
            }
            $date=date("d-m-Y",strtotime($row['datentime']));
            $data.="
                <tr class='align-middle'>
                    <td>$i</td>
                    <td>
                        <img src= '$path$row[profile]' width ='55px'>
                        <br>
                        $row[name]
                    </td>
                    <td>$row[email]</td>
                    <td>$row[phone]</td>
                    <td>$row[user_address]</td>
                    <td>$row[dob]</td>
                    <td>$verified</td>
                    <td>$status</td>
                    <td>$date</td>
                    <td>
                        $warn_mail
                        $delete_btn
                    </td>
                </tr>
            ";
            $i++;
        }
        echo $data;
    }
    if(isset($_POST['toggle_status'])){
        $frm_data=filteration($_POST);
        $q="UPDATE `user_cred` SET `status` = ? WHERE `id` = ?";
        $v = [$frm_data['value'],$frm_data['toggle_status']];
        if(update($q,$v,'ii')){
            echo 1;
        }
        else{
            echo 0;
        }
    }
    
    if(isset($_POST['search_user'])){
        $frm_data = filteration($_POST);
        $q = "SELECT * FROM `user_cred` WHERE `name` LIKE ?";
        $values = ["%$frm_data[name]%"];
        $res=select($q,$values,"s");
        $i=1;
        $data = "";
        $path=USER_IMG_PATH;
        while($row=mysqli_fetch_assoc($res)){
            $warn_mail="<button type='button' onclick='warn_user($row[id])' class='btn btn-warning shadow-none btn-sm'>
                    <i class='bi bi-envelope-at-fill'></i>
                </button>";
            $delete_btn ="<button type='button' onclick='remove_user($row[id])' class='btn btn-danger shadow-none btn-sm'>
                            <i class='bi bi-trash'></i>
                        </button>";
            $verified = "<span class='badge bg-danger'><i class='bi bi-x-lg'></i></span>";
            if($row['is_verified']){
                $verified = "<span class='badge bg-success'><i class='bi bi-check-lg'></i></span>";
            }
            $status= "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>active</button>";
            if($row['status']==0){
                $status= "<button onclick='toggle_status($row[id],1)' class='btn btn-warning btn-sm shadow-none'>inactive</button>";
            }
            $date=date("d-m-Y",strtotime($row['datentime']));
            $data.="
                <tr class='align-middle'>
                    <td>$i</td>
                    <td>
                        <img src= '$path$row[profile]' width ='55px'>
                        <br>
                        $row[name]
                    </td>
                    <td>$row[email]</td>
                    <td>$row[phone]</td>
                    <td>$row[user_address]</td>
                    <td>$row[dob]</td>
                    <td>$verified</td>
                    <td>$status</td>
                    <td>$date</td>
                    <td>
                        $warn_mail
                        $delete_btn
                    </td>
                </tr>
            ";
            $i++;
        }
        echo $data;
    }
?>