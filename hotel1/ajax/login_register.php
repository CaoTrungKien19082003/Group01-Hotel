<?php 
    require('../admin/inc/db_config.php');
    require('../admin/inc/essentials.php');
    require('../vendor/autoload.php');
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    function send_mail($email,$name,$token,$type){
        $credentials = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', SENDINBLUE_API);
        $apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(new GuzzleHttp\Client(),$credentials);
        if($type=='verified'){
            $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
                'subject' => 'A welcome from Hoteru!',
                'sender' => ['name' => 'Hoteru', 'email' => 'contact@hoteru.com'],
                'to' => [[ 'name' => $name, 'email' => $email]],
                'htmlContent' => '<html><body> 
                One more step to create new account. Click the link to complete the process: <br>
                <a class="btn btn-sm text-white custom-bg shaadow-none" href="'.SITE_URL.'email_confirm.php?'.$type.'&email='.$email.'&token='.$token.'">
                Click here
                </a>
                </body></html>',
                //'params' => ['bodyMessage' => 'made just for you!']
            ]);
        }else if($type=='passf'){
            $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
                'subject' => 'Password reset confirmation!',
                'sender' => ['name' => 'Hoteru', 'email' => 'contact@hoteru.com'],
                'to' => [[ 'name' => $name, 'email' => $email]],
                'htmlContent' => '<html><body> 
                Click the link to complete the process: <br>
                <a class="btn btn-sm text-white custom-bg shaadow-none" href="'.SITE_URL.'index.php?'.$type.'&email='.$email.'&token='.$token.'">
                Click here
                </a>
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
    if(isset($_POST['register'])){
        $data = filteration($_POST);
        if($data['pass']!=$data['cpass']){
            echo 'pass_mismatch';
            exit;
        }
        $u_exist = select("SELECT * FROM `user_cred` WHERE `email`=? AND `phone`=? LIMIT 1",[$data['email'],$data['phone']],"ss");
        if(mysqli_num_rows($u_exist)!=0){
            $u_exist_fetch = mysqli_fetch_assoc($u_exist);
            echo ($u_exist_fetch['email'] == $data['email']) ? 'email_already' : 'phone_already';
            exit;
        }
        $img = uploadUserImage($_FILES['profile']);
        if($img == 'inv_img'){
            echo 'inv_img';
            exit;
        }
        else if($img =='upd_failed'){
            echo 'upd_failed';
            exit;
        }
        
        $token = bin2hex(random_bytes(16));
        
        if(!send_mail($data['email'],$data['name'],$token,'verified')){
            echo 'mail_failed';
            exit;
        };
        
        $enc_pass = password_hash($data['pass'],PASSWORD_BCRYPT);
        $query = "INSERT INTO `user_cred`(`name`, `email`, `phone`, `profile`, 
        `user_id`, `user_address`, `pcode`, `dob`, `pass`, `token`) 
        VALUES (?,?,?,?,?,?,?,?,?,?)";
        $values = [$data['name'],$data['email'],$data['phone'],$img,$data['user_id'],$data['add'],$data['pcode'],$data['dob'],$enc_pass,$token];
        if(insert($query,$values,'ssssssssss')){
            echo 1;
        }else{echo 'ins_failed';}
    }
    if(isset($_POST['login'])){
        $data = filteration($_POST);
        $u_exist = select("SELECT * FROM `user_cred` WHERE `email`=? LIMIT 1",[$data['email']],"s");
        if(mysqli_num_rows($u_exist)==0){
            echo 'inv_email';
        }else{
            $u_fetch = mysqli_fetch_assoc($u_exist);
            if($u_fetch['is_verified']==0){
                echo 'not_verified';
            }else if($u_fetch['status']==0){
                echo 'inactive';
            }else{
                if(!password_verify($data['password'],$u_fetch['pass'])){
                    echo 'invalid_pass';
                }else{
                    session_start();
                    $_SESSION['login'] = true;
                    $_SESSION['uId'] = $u_fetch['id'];
                    $_SESSION['uName'] = $u_fetch['name'];
                    $_SESSION['uPic'] = $u_fetch['profile'];
                    $_SESSION['uPhone'] = $u_fetch['phone'];
                    $_SESSION['uAdd'] = $u_fetch['user_address'];
                    echo 1;
                }
            }
        }
    }
    if(isset($_POST['passf'])){
        $data = filteration($_POST);
        $u_exist = select("SELECT * FROM `user_cred` WHERE `email`=? LIMIT 1",[$data['email']],"s");
        if(mysqli_num_rows($u_exist)==0){
            echo 'inv_email';
        }else{
            $u_fetch = mysqli_fetch_assoc($u_exist);
            if($u_fetch['is_verified']==0){
                echo 'not_verified';
            }else if($u_fetch['status']==0){
                echo 'inactive';
            }else{
                $token=bin2hex(random_bytes(16));
                if(!send_mail($data['email'],$u_fetch['name'],$token,'passf')){
                    echo 'mail_failed';
                }else{
                    $date = date("Y-m-d");
                    $query= mysqli_query($con,"UPDATE `user_cred` SET `token`='$token', `t_expire`='$date' WHERE `id`='$u_fetch[id]'");
                    if($query){echo 1;}else{echo 'upd_failed';}
                }
            }
        }
    }
    if(isset($_POST['passr'])){
        $data = filteration($_POST);
        if($data['npass']!=$data['cnpass']){
            echo 'pass_mismatch';
            exit;
        }
        $u_exist = select("SELECT * FROM `user_cred` WHERE `email`=? LIMIT 1",[$data['email']],"s");
        $u_fetch = mysqli_fetch_assoc($u_exist);
        if(password_verify($data['npass'],$u_fetch['pass'])){
            echo 'old_pass';
        }else{
            $enc_pass = password_hash($data['npass'],PASSWORD_BCRYPT);
            $query= mysqli_query($con,"UPDATE `user_cred` SET `pass`='$enc_pass', `token`= NULL, `t_expire`= NULL WHERE `email`='$u_fetch[email]'");
            if($query){echo 1;}else{echo 'upd_failed';}
        }
            
        
    }
?>