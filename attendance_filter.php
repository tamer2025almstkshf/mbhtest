<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['userattendance_rperm'] == 1){
        $user_id = filter_input(INPUT_POST, "user_id", FILTER_SANITIZE_NUMBER_INT);
        $month = filter_input(INPUT_POST, 'month', FILTER_SANITIZE_NUMBER_INT);
        $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);
        
        header("Location: attendance.php?id=$user_id&month=$month&year=$year");
        exit();
    }
?>