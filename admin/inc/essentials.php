<?php
    function alert($type, $msg){
        echo<<<alert
        <div class="alert alert-warning alert-dismissible fade show custom-alert" role="alert">
            <div class="me-3">$msg</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        alert;
    }
?>