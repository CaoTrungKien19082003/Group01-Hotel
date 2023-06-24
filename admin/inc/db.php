<?php
    $hname='localhost';
    $uname='root';
    $pass='';
    $db='hotel_admins';

    $con=mysqli_connect($hname, $uname, $pass, $db);
    
    if(!$con){
        die("Cannot connect to Database. Please try again.".mysqli_connect_error());
    }
?>