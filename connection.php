<?php
    date_default_timezone_set('Asia/Dubai');
    $servername = 'localhost';
    $username = 'mbhDev';
    $password = '#yuCyTJ!FI=K';
    $dbname = 'mbhdbase';

    $conn = new mysqli($servername, $username, $password, $dbname);
    if($conn->connect_error){
        die('error connecting to the database'.$conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    
    header("X-XSS-Protection: 1; mode=block");
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: SAMEORIGIN");
?>