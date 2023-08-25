<?php
    require('../inc/db_config.php');
    require('../inc/essentials.php');
    adminLogin();
    require('../../vendor/autoload.php');
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    function send_warn_mail($email,$name,$type){
        $credentials = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', SENDINBLUE_API);
        $apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(new GuzzleHttp\Client(),$credentials);
        if($type=='warning'){
            $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
                'subject' => 'A warning from Hoteru!',
                'sender' => ['name' => 'Hoteru', 'email' => 'contact@hoteru.com'],
                'to' => [[ 'name' => $name, 'email' => $email]],
                'htmlContent' => '<html><body> 
                You have been reporting by lots of user for wrong behaviors while using our service. So we will temporary suspeneded your account, please reply to this mail and we may consider to release your account.  
                </body></html>',
                //'params' => ['bodyMessage' => 'made just for you!']
            ]);
        }else if($type=='anouce'){
            $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
                'subject' => 'A sad goodbye from Hoteru!',
                'sender' => ['name' => 'Hoteru', 'email' => 'contact@hoteru.com'],
                'to' => [[ 'name' => $name, 'email' => $email]],
                'htmlContent' => '<html><body> 
                You have been reporting many times by lots of user for wrong behaviors while using our service. We have no choice but to delete your account. We sorry to inform you that, but its may your fault<br>
                Thank you for trusting our service.
                </body></html>',
                //'params' => ['bodyMessage' => 'made just for you!']
            ]);
        }
        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            if($result){return 1;}
            else{return 0;}
        } catch (Exception $e) {
            echo $e->getMessage(),PHP_EOL;
            return 0;
        }
    }
    if(isset($_POST['get_hotels'])){
        $frm_data = filteration($_POST);
        $q1 = "SELECT * FROM `admin_cred` WHERE NOT `sr_no`=?";
        $values = [0];
        $res=select($q1,$values,"i");
        $i=1;
        $data = "";
        while($row=mysqli_fetch_assoc($res)){
            $q2 = mysqli_fetch_assoc(select("SELECT * FROM `contact_detail` WHERE `hotel`=?",[$row['hotel']],'s'));
            $q3 = mysqli_fetch_assoc(select("SELECT * FROM `setting` WHERE `hotel`=?",[$row['hotel']],'s'));
            $warn_mail="<button type='button' onclick='warn_user($row[sr_no])' class='btn btn-warning shadow-none btn-sm'>
                    <i class='bi bi-envelope-at-fill'></i>
                </button>";
            $delete_btn ="<button type='button' onclick='remove_user($row[sr_no])' class='btn btn-danger shadow-none btn-sm'>
                            <i class='bi bi-trash'></i>
                        </button>";
            $status= "<span class='badge bg-dark'>active</span>";
            if($q3['shutdown']==1){
                $status= "<span class='badge bg-primary'>freezed</span>";
            }
            $data.="
                <tr class='align-middle'>
                    <td>$i</td>
                    <td>$row[admin_name]</td>
                    <td>$row[admin_pass]</td>
                    <td>$q2[pn1]</td>
                    <td>$q2[pn2]</td>
                    <td>$q2[address]</td>
                    <td>$status</td>
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
        $q="UPDATE `setting` SET `shutdown` = ? WHERE `sr_no` = ?";
        $v = [$frm_data['value'],$frm_data['toggle_status']];
        if(update($q,$v,'ii')){
            echo 1;
        }
        else{
            echo 0;
        }
    }
    if(isset($_POST['warn_user'])){
        $frm_data = filteration($_POST);
        $check_q = select("SELECT * FROM `admin_cred` WHERE `sr_no`=?",[$frm_data['hotel']],'i');
        $q2 = mysqli_fetch_assoc(select("SELECT * FROM `contact_detail` WHERE `sr_no`=?",[$frm_data['hotel']],'i'));
        $row = mysqli_fetch_assoc($check_q);
        if(!send_warn_mail($q2['email'],$row['admin_name'],'warning')){
            echo 0;
        }
        else{
            echo 1;
        } 
    }
    if(isset($_POST['remove_user'])){
        $frm_data = filteration($_POST);
        $check_q = select("SELECT * FROM `admin_cred` WHERE `sr_no`=?",[$frm_data['hotel']],'i');
        $q2 = mysqli_fetch_assoc(select("SELECT * FROM `contact_detail` WHERE `sr_no`=?",[$frm_data['hotel']],'i'));
        $row = mysqli_fetch_assoc($check_q);
        $pre_q1 = delete("DELETE FROM `admin_cred` WHERE `sr_no`=?",[$frm_data['hotel']],'i');
        if(!send_warn_mail($q2['email'],$row['admin_name'],'anouce')||!$pre_q1){
            echo 0;
        }
        else{
            echo 1;
        } 
    }
    if(isset($_POST['search_hotel'])){
        $frm_data = filteration($_POST);
        $q = "SELECT * FROM `admin_cred` WHERE `admin_name` LIKE ? AND NOT`sr_no`=?";
        $values = ["%$frm_data[name]%",0];
        $res=select($q,$values,"si");
        $i=1;
        $data = "";
        while($row=mysqli_fetch_assoc($res)){
            $q2 = mysqli_fetch_assoc(select("SELECT * FROM `contact_detail` WHERE `hotel`=?",[$row['hotel']],'s'));
            $q3 = mysqli_fetch_assoc(select("SELECT * FROM `setting` WHERE `hotel`=?",[$row['hotel']],'s'));
            $warn_mail="<button type='button' onclick='warn_user($row[hotel])' class='btn btn-warning shadow-none btn-sm'>
                    <i class='bi bi-envelope-at-fill'></i>
                </button>";
            $delete_btn ="<button type='button' onclick='remove_user($row[hotel])' class='btn btn-danger shadow-none btn-sm'>
                            <i class='bi bi-trash'></i>
                        </button>";
            $status= "<button onclick='toggle_status($row[hotel],0)' class='btn btn-dark btn-sm shadow-none'>active</button>";
            if($q3['shutdown']==1){
                $status= "<button onclick='toggle_status($row[hotel],1)' class='btn btn-ìno btn-sm shadow-none'>freeze</button>";
            }
            $data.="
                <tr class='align-middle'>
                    <td>$i</td>
                    <td>$row[admin_name]</td>
                    <td>$row[admin_pass]</td>
                    <td>$q2[pn1]</td>
                    <td>$q2[pn2]</td>
                    <td>$q2[address]</td>
                    <td>$status</td>
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