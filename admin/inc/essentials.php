<?php
   
    define('TEAM', 'team/');
    function Login(){
        session_start();
        if(!(isset($_SESSION['Login']) && $_SESSION['Login']==true)){
            echo"
                <script>
                    window.location.href='index.php';
                </script>
            ";
            exit;
        }
    }
    function redirecting($url){
        echo"
            <script>
                window.location.href='$url';
            </script>
        ";
        exit;
    }
    
    function alert($type, $msg){
        $bs_class=($type=="success") ? "alert-success":"alert-danger";
        echo<<<alert
        <div class="alert $bs_class alert-dismissible fade show" role="alert">
            <div class="me-3">$msg</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        alert;
    }

    function uploadImage($image, $folder){
        $valid_file=['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        $img_type=$image['type'];

        if (!in_array($img_type, $valid_file)){
            return 'inv_file';
        }

        else if (($image['size']/(1024*1024))>2){
            return 'inv_size';
        }

        else{
            $ext=pathinfo($image['name'], PATHINFO_EXTENSION);
            $rname='IMG_'.random_int(11111, 99999).".$ext";

            $img_path=$_SERVER['DOCUMENT_ROOT'].'/Group01-Hotel/images/'.$folder.$rname;
            echo $img_path;
            if(move_uploaded_file($image['tmp_name'], $img_path)){
                return $rname;
            }

            else{
                return 'upload_failed';
            }
        }
    }

    function deleteImage($image, $folder){
        if(unlink($_SERVER['DOCUMENT_ROOT'].'/Group01-Hotel/images/'.$folder.$image)){
            return true;
        }
        else{
            return false;
        }
    }
    
?>