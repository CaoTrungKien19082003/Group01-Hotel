<?php
    require('inc/essentials.php');
    session_start();
    session_destroy();
    redirecting('index.php');
?>