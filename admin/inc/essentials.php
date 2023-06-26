<?php
    function Login(){
        session_start();
        if(!(isset($_SESSION['Login']) && $_SESSION['Login']==true)){
            echo"
                <script>
                    window.location.href='index.php';
                </script>
            ";
        }
        session_regenerate_id(true);
    }
    function redirecting($url){
        echo"
            <script>
                window.location.href='$url';
            </script>
        ";
    }
    function alert($type, $msg){
        $bs_class=($type=="success") ? "alert-success":"alert-danger";
        echo<<<alert
        <div class="alert $bs_class alert-dismissible fade show custom-alert" role="alert">
            <div class="me-3">$msg</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        alert;
    }
?>