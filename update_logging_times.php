<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'errorscheck.php';
    
    if((isset($_REQUEST['sunday_loginHH']) && $_REQUEST['sunday_loginHH'] !== '') || (isset($_REQUEST['sunday_loginMM']) && $_REQUEST['sunday_loginMM'] !== '') || 
    (isset($_REQUEST['monday_loginHH']) && $_REQUEST['monday_loginHH'] !== '') || (isset($_REQUEST['monday_loginMM']) && $_REQUEST['monday_loginMM'] !== '') || 
    (isset($_REQUEST['tuesday_loginHH']) && $_REQUEST['tuesday_loginHH'] !== '') || (isset($_REQUEST['tuesday_loginMM']) && $_REQUEST['tuesday_loginMM'] !== '') || 
    (isset($_REQUEST['wednesday_loginHH']) && $_REQUEST['wednesday_loginHH'] !== '') || (isset($_REQUEST['wednesday_loginMM']) && $_REQUEST['wednesday_loginMM'] !== '') ||
    (isset($_REQUEST['thursday_loginHH']) && $_REQUEST['thursday_loginHH'] !== '') || (isset($_REQUEST['thursday_loginMM']) && $_REQUEST['thursday_loginMM'] !== '') ||
    (isset($_REQUEST['friday_loginHH']) && $_REQUEST['friday_loginHH'] !== '') || (isset($_REQUEST['friday_loginMM']) && $_REQUEST['friday_loginMM'] !== '') ||
    (isset($_REQUEST['saturday_loginHH']) && $_REQUEST['saturday_loginHH'] !== '') || (isset($_REQUEST['saturday_loginMM']) && $_REQUEST['saturday_loginMM'] !== '') ||
    (isset($_REQUEST['sunday_logoutHH']) && $_REQUEST['sunday_logoutHH'] !== '') || (isset($_REQUEST['sunday_logoutMM']) && $_REQUEST['sunday_logoutMM'] !== '') || 
    (isset($_REQUEST['monday_logoutHH']) && $_REQUEST['monday_logoutHH'] !== '') || (isset($_REQUEST['monday_logoutMM']) && $_REQUEST['monday_logoutMM'] !== '') || 
    (isset($_REQUEST['tuesday_logoutHH']) && $_REQUEST['tuesday_logoutHH'] !== '') || (isset($_REQUEST['tuesday_logoutMM']) && $_REQUEST['tuesday_logoutMM'] !== '') || 
    (isset($_REQUEST['wednesday_logoutHH']) && $_REQUEST['wednesday_logoutHH'] !== '') || (isset($_REQUEST['wednesday_logoutMM']) && $_REQUEST['wednesday_logoutMM'] !== '') ||
    (isset($_REQUEST['thursday_logoutHH']) && $_REQUEST['thursday_logoutHH'] !== '') || (isset($_REQUEST['thursday_logoutMM']) && $_REQUEST['thursday_logoutMM'] !== '') ||
    (isset($_REQUEST['friday_logoutHH']) && $_REQUEST['friday_logoutHH'] !== '') || (isset($_REQUEST['friday_logoutMM']) && $_REQUEST['friday_logoutMM'] !== '') ||
    (isset($_REQUEST['saturday_logoutHH']) && $_REQUEST['saturday_logoutHH'] !== '') || (isset($_REQUEST['saturday_logoutMM']) && $_REQUEST['saturday_logoutMM'] !== '')){
        $userid = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_NUMBER_INT);
        
        $loginHH = filter_input(INPUT_POST, 'sunday_loginHH', FILTER_SANITIZE_NUMBER_INT);
        $loginMM = filter_input(INPUT_POST, 'sunday_loginMM', FILTER_SANITIZE_NUMBER_INT);
        $loginHH = intval($loginHH);
        $loginMM = intval($loginMM);
        if($loginHH < 10){
            $loginHH = "0$loginHH";
        }
        if($loginMM < 10){
            $loginMM = "0$loginMM";
        }
        $logintime = "$loginHH:$loginMM";
        if(isset($logintime) && $logintime !== ''){
            $day = 'sunday';
            $stmtr = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
            $stmtr->bind_param("s", $day);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            if($resultr->num_rows > 0){
                $stmt = $conn->prepare("UPDATE user_logging SET login_time=? WHERE day=?");
                $stmt->bind_param("ss", $logintime, $day);
                $stmt->execute();
                $stmt->close();
            } else{
                $stmt = $conn->prepare("INSERT INTO user_logging (login_time, day) VALUES (?, ?)");
                $stmt->bind_param("ss", $logintime, $day);
                $stmt->execute();
                $stmt->close();
            }
            $stmtr->close();
            $loginHH = '';
            $loginMM = '';
            $logintime = '';
        }
        
        $logoutHH = filter_input(INPUT_POST, 'sunday_logoutHH', FILTER_SANITIZE_NUMBER_INT);
        $logoutMM = filter_input(INPUT_POST, 'sunday_logoutMM', FILTER_SANITIZE_NUMBER_INT);
        $logoutHH = intval($logoutHH);
        $logoutMM = intval($logoutMM);
        if($logoutHH < 10){
            $logoutHH = "0$logoutHH";
        }
        if($logoutMM < 10){
            $logoutMM = "0$logoutMM";
        }
        $logouttime = "$logoutHH:$logoutMM";
        if(isset($logouttime) && $logouttime !== ''){
            $day = 'sunday';
            $stmtr = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
            $stmtr->bind_param("s", $day);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            if($resultr->num_rows > 0){
                $stmt = $conn->prepare("UPDATE user_logging SET logout_time=? WHERE day=?");
                $stmt->bind_param("ss", $logouttime, $day);
                $stmt->execute();
                $stmt->close();
            } else{
                $stmt = $conn->prepare("INSERT INTO user_logging (logout_time, day) VALUES (?, ?)");
                $stmt->bind_param("ss", $logouttime, $day);
                $stmt->execute();
                $stmt->close();
            }
            $stmtr->close();
            $logoutHH = '';
            $logoutMM = '';
            $logouttime = '';
        }
        
        $loginHH = filter_input(INPUT_POST, 'monday_loginHH', FILTER_SANITIZE_NUMBER_INT);
        $loginMM = filter_input(INPUT_POST, 'monday_loginMM', FILTER_SANITIZE_NUMBER_INT);
        $loginHH = intval($loginHH);
        $loginMM = intval($loginMM);
        if($loginHH < 10){
            $loginHH = "0$loginHH";
        }
        if($loginMM < 10){
            $loginMM = "0$loginMM";
        }
        $logintime = "$loginHH:$loginMM";
        if(isset($logintime) && $logintime !== ''){
            $day = 'monday';
            $stmtr = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
            $stmtr->bind_param("s", $day);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            if($resultr->num_rows > 0){
                $stmt = $conn->prepare("UPDATE user_logging SET login_time=? WHERE day=?");
                $stmt->bind_param("ss", $logintime, $day);
                $stmt->execute();
                $stmt->close();
            } else{
                $stmt = $conn->prepare("INSERT INTO user_logging (login_time, day) VALUES (?, ?)");
                $stmt->bind_param("ss", $logintime, $day);
                $stmt->execute();
                $stmt->close();
            }
            $stmtr->close();
            $loginHH = '';
            $loginMM = '';
            $logintime = '';
        }
        
        $logoutHH = filter_input(INPUT_POST, 'monday_logoutHH', FILTER_SANITIZE_NUMBER_INT);
        $logoutMM = filter_input(INPUT_POST, 'monday_logoutMM', FILTER_SANITIZE_NUMBER_INT);
        $logoutHH = intval($logoutHH);
        $logoutMM = intval($logoutMM);
        if($logoutHH < 10){
            $logoutHH = "0$logoutHH";
        }
        if($logoutMM < 10){
            $logoutMM = "0$logoutMM";
        }
        $logouttime = "$logoutHH:$logoutMM";
        if(isset($logouttime) && $logouttime !== ''){
            $day = 'monday';
            $stmtr = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
            $stmtr->bind_param("s", $day);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            if($resultr->num_rows > 0){
                $stmt = $conn->prepare("UPDATE user_logging SET logout_time=? WHERE day=?");
                $stmt->bind_param("ss", $logouttime, $day);
                $stmt->execute();
                $stmt->close();
            } else{
                $stmt = $conn->prepare("INSERT INTO user_logging (logout_time, day) VALUES (?, ?)");
                $stmt->bind_param("ss", $logouttime, $day);
                $stmt->execute();
                $stmt->close();
            }
            $stmtr->close();
            $logoutHH = '';
            $logoutMM = '';
            $logouttime = '';
        }
        
        $loginHH = filter_input(INPUT_POST, 'tuesday_loginHH', FILTER_SANITIZE_NUMBER_INT);
        $loginMM = filter_input(INPUT_POST, 'tuesday_loginMM', FILTER_SANITIZE_NUMBER_INT);
        $loginHH = intval($loginHH);
        $loginMM = intval($loginMM);
        if($loginHH < 10){
            $loginHH = "0$loginHH";
        }
        if($loginMM < 10){
            $loginMM = "0$loginMM";
        }
        $logintime = "$loginHH:$loginMM";
        if(isset($logintime) && $logintime !== ''){
            $day = 'tuesday';
            $stmtr = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
            $stmtr->bind_param("s", $day);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            if($resultr->num_rows > 0){
                $stmt = $conn->prepare("UPDATE user_logging SET login_time=? WHERE day=?");
                $stmt->bind_param("ss", $logintime, $day);
                $stmt->execute();
                $stmt->close();
            } else{
                $stmt = $conn->prepare("INSERT INTO user_logging (login_time, day) VALUES (?, ?)");
                $stmt->bind_param("ss", $logintime, $day);
                $stmt->execute();
                $stmt->close();
            }
            $stmtr->close();
            $loginHH = '';
            $loginMM = '';
            $logintime = '';
        }
        
        $logoutHH = filter_input(INPUT_POST, 'tuesday_logoutHH', FILTER_SANITIZE_NUMBER_INT);
        $logoutMM = filter_input(INPUT_POST, 'tuesday_logoutMM', FILTER_SANITIZE_NUMBER_INT);
        $logoutHH = intval($logoutHH);
        $logoutMM = intval($logoutMM);
        if($logoutHH < 10){
            $logoutHH = "0$logoutHH";
        }
        if($logoutMM < 10){
            $logoutMM = "0$logoutMM";
        }
        $logouttime = "$logoutHH:$logoutMM";
        if(isset($logouttime) && $logouttime !== ''){
            $day = 'tuesday';
            $stmtr = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
            $stmtr->bind_param("s", $day);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            if($resultr->num_rows > 0){
                $stmt = $conn->prepare("UPDATE user_logging SET logout_time=? WHERE day=?");
                $stmt->bind_param("ss", $logouttime, $day);
                $stmt->execute();
                $stmt->close();
            } else{
                $stmt = $conn->prepare("INSERT INTO user_logging (logout_time, day) VALUES (?, ?)");
                $stmt->bind_param("ss", $logouttime, $day);
                $stmt->execute();
                $stmt->close();
            }
            $stmtr->close();
            $logoutHH = '';
            $logoutMM = '';
            $logouttime = '';
        }
        
        $loginHH = filter_input(INPUT_POST, 'wednesday_loginHH', FILTER_SANITIZE_NUMBER_INT);
        $loginMM = filter_input(INPUT_POST, 'wednesday_loginMM', FILTER_SANITIZE_NUMBER_INT);
        $loginHH = intval($loginHH);
        $loginMM = intval($loginMM);
        if($loginHH < 10){
            $loginHH = "0$loginHH";
        }
        if($loginMM < 10){
            $loginMM = "0$loginMM";
        }
        $logintime = "$loginHH:$loginMM";
        if(isset($logintime) && $logintime !== ''){
            $day = 'wednesday';
            $stmtr = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
            $stmtr->bind_param("s", $day);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            if($resultr->num_rows > 0){
                $stmt = $conn->prepare("UPDATE user_logging SET login_time=? WHERE day=?");
                $stmt->bind_param("ss", $logintime, $day);
                $stmt->execute();
                $stmt->close();
            } else{
                $stmt = $conn->prepare("INSERT INTO user_logging (login_time, day) VALUES (?, ?)");
                $stmt->bind_param("ss", $logintime, $day);
                $stmt->execute();
                $stmt->close();
            }
            $stmtr->close();
            $loginHH = '';
            $loginMM = '';
            $logintime = '';
        }
        
        $logoutHH = filter_input(INPUT_POST, 'wednesday_logoutHH', FILTER_SANITIZE_NUMBER_INT);
        $logoutMM = filter_input(INPUT_POST, 'wednesday_logoutMM', FILTER_SANITIZE_NUMBER_INT);
        $logoutHH = intval($logoutHH);
        $logoutMM = intval($logoutMM);
        if($logoutHH < 10){
            $logoutHH = "0$logoutHH";
        }
        if($logoutMM < 10){
            $logoutMM = "0$logoutMM";
        }
        $logouttime = "$logoutHH:$logoutMM";
        if(isset($logouttime) && $logouttime !== ''){
            $day = 'wednesday';
            $stmtr = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
            $stmtr->bind_param("s", $day);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            if($resultr->num_rows > 0){
                $stmt = $conn->prepare("UPDATE user_logging SET logout_time=? WHERE day=?");
                $stmt->bind_param("ss", $logouttime, $day);
                $stmt->execute();
                $stmt->close();
            } else{
                $stmt = $conn->prepare("INSERT INTO user_logging (logout_time, day) VALUES (?, ?)");
                $stmt->bind_param("ss", $logouttime, $day);
                $stmt->execute();
                $stmt->close();
            }
            $stmtr->close();
            $logoutHH = '';
            $logoutMM = '';
            $logouttime = '';
        }
        
        $loginHH = filter_input(INPUT_POST, 'thursday_loginHH', FILTER_SANITIZE_NUMBER_INT);
        $loginMM = filter_input(INPUT_POST, 'thursday_loginMM', FILTER_SANITIZE_NUMBER_INT);
        $loginHH = intval($loginHH);
        $loginMM = intval($loginMM);
        if($loginHH < 10){
            $loginHH = "0$loginHH";
        }
        if($loginMM < 10){
            $loginMM = "0$loginMM";
        }
        $logintime = "$loginHH:$loginMM";
        if(isset($logintime) && $logintime !== ''){
            $day = 'thursday';
            $stmtr = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
            $stmtr->bind_param("s", $day);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            if($resultr->num_rows > 0){
                $stmt = $conn->prepare("UPDATE user_logging SET login_time=? WHERE day=?");
                $stmt->bind_param("ss", $logintime, $day);
                $stmt->execute();
                $stmt->close();
            } else{
                $stmt = $conn->prepare("INSERT INTO user_logging (login_time, day) VALUES (?, ?)");
                $stmt->bind_param("ss", $logintime, $day);
                $stmt->execute();
                $stmt->close();
            }
            $stmtr->close();
            $loginHH = '';
            $loginMM = '';
            $logintime = '';
        }
        
        $logoutHH = filter_input(INPUT_POST, 'thursday_logoutHH', FILTER_SANITIZE_NUMBER_INT);
        $logoutMM = filter_input(INPUT_POST, 'thursday_logoutMM', FILTER_SANITIZE_NUMBER_INT);
        $logoutHH = intval($logoutHH);
        $logoutMM = intval($logoutMM);
        if($logoutHH < 10){
            $logoutHH = "0$logoutHH";
        }
        if($logoutMM < 10){
            $logoutMM = "0$logoutMM";
        }
        $logouttime = "$logoutHH:$logoutMM";
        if(isset($logouttime) && $logouttime !== ''){
            $day = 'thursday';
            $stmtr = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
            $stmtr->bind_param("s", $day);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            if($resultr->num_rows > 0){
                $stmt = $conn->prepare("UPDATE user_logging SET logout_time=? WHERE day=?");
                $stmt->bind_param("ss", $logouttime, $day);
                $stmt->execute();
                $stmt->close();
            } else{
                $stmt = $conn->prepare("INSERT INTO user_logging (logout_time, day) VALUES (?, ?)");
                $stmt->bind_param("ss", $logouttime, $day);
                $stmt->execute();
                $stmt->close();
            }
            $stmtr->close();
            $logoutHH = '';
            $logoutMM = '';
            $logouttime = '';
        }
        
        $loginHH = filter_input(INPUT_POST, 'friday_loginHH', FILTER_SANITIZE_NUMBER_INT);
        $loginMM = filter_input(INPUT_POST, 'friday_loginMM', FILTER_SANITIZE_NUMBER_INT);
        $loginHH = intval($loginHH);
        $loginMM = intval($loginMM);
        if($loginHH < 10){
            $loginHH = "0$loginHH";
        }
        if($loginMM < 10){
            $loginMM = "0$loginMM";
        }
        $logintime = "$loginHH:$loginMM";
        if(isset($logintime) && $logintime !== ''){
            $day = 'friday';
            $stmtr = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
            $stmtr->bind_param("s", $day);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            if($resultr->num_rows > 0){
                $stmt = $conn->prepare("UPDATE user_logging SET login_time=? WHERE day=?");
                $stmt->bind_param("ss", $logintime, $day);
                $stmt->execute();
                $stmt->close();
            } else{
                $stmt = $conn->prepare("INSERT INTO user_logging (login_time, day) VALUES (?, ?)");
                $stmt->bind_param("ss", $logintime, $day);
                $stmt->execute();
                $stmt->close();
            }
            $stmtr->close();
            $loginHH = '';
            $loginMM = '';
            $logintime = '';
        }
        
        $logoutHH = filter_input(INPUT_POST, 'friday_logoutHH', FILTER_SANITIZE_NUMBER_INT);
        $logoutMM = filter_input(INPUT_POST, 'friday_logoutMM', FILTER_SANITIZE_NUMBER_INT);
        $logoutHH = intval($logoutHH);
        $logoutMM = intval($logoutMM);
        if($logoutHH < 10){
            $logoutHH = "0$logoutHH";
        }
        if($logoutMM < 10){
            $logoutMM = "0$logoutMM";
        }
        $logouttime = "$logoutHH:$logoutMM";
        if(isset($logouttime) && $logouttime !== ''){
            $day = 'friday';
            $stmtr = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
            $stmtr->bind_param("s", $day);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            if($resultr->num_rows > 0){
                $stmt = $conn->prepare("UPDATE user_logging SET logout_time=? WHERE day=?");
                $stmt->bind_param("ss", $logouttime, $day);
                $stmt->execute();
                $stmt->close();
            } else{
                $stmt = $conn->prepare("INSERT INTO user_logging (logout_time, day) VALUES (?, ?)");
                $stmt->bind_param("ss", $logouttime, $day);
                $stmt->execute();
                $stmt->close();
            }
            $stmtr->close();
            $logoutHH = '';
            $logoutMM = '';
            $logouttime = '';
        }
        
        $loginHH = filter_input(INPUT_POST, 'saturday_loginHH', FILTER_SANITIZE_NUMBER_INT);
        $loginMM = filter_input(INPUT_POST, 'saturday_loginMM', FILTER_SANITIZE_NUMBER_INT);
        $loginHH = intval($loginHH);
        $loginMM = intval($loginMM);
        if($loginHH < 10){
            $loginHH = "0$loginHH";
        }
        if($loginMM < 10){
            $loginMM = "0$loginMM";
        }
        $logintime = "$loginHH:$loginMM";
        if(isset($logintime) && $logintime !== ''){
            $day = 'saturday';
            $stmtr = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
            $stmtr->bind_param("s", $day);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            if($resultr->num_rows > 0){
                $stmt = $conn->prepare("UPDATE user_logging SET login_time=? WHERE day=?");
                $stmt->bind_param("ss", $logintime, $day);
                $stmt->execute();
                $stmt->close();
            } else{
                $stmt = $conn->prepare("INSERT INTO user_logging (login_time, day) VALUES (?, ?)");
                $stmt->bind_param("ss", $logintime, $day);
                $stmt->execute();
                $stmt->close();
            }
            $stmtr->close();
            $loginHH = '';
            $loginMM = '';
            $logintime = '';
        }
        
        $logoutHH = filter_input(INPUT_POST, 'saturday_logoutHH', FILTER_SANITIZE_NUMBER_INT);
        $logoutMM = filter_input(INPUT_POST, 'saturday_logoutMM', FILTER_SANITIZE_NUMBER_INT);
        $logoutHH = intval($logoutHH);
        $logoutMM = intval($logoutMM);
        if($logoutHH < 10){
            $logoutHH = "0$logoutHH";
        }
        if($logoutMM < 10){
            $logoutMM = "0$logoutMM";
        }
        $logouttime = "$logoutHH:$logoutMM";
        if(isset($logouttime) && $logouttime !== ''){
            $day = 'saturday';
            $stmtr = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
            $stmtr->bind_param("s", $day);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            if($resultr->num_rows > 0){
                $stmt = $conn->prepare("UPDATE user_logging SET logout_time=? WHERE day=?");
                $stmt->bind_param("ss", $logouttime, $day);
                $stmt->execute();
                $stmt->close();
            } else{
                $stmt = $conn->prepare("INSERT INTO user_logging (logout_time, day) VALUES (?, ?)");
                $stmt->bind_param("ss", $logouttime, $day);
                $stmt->execute();
                $stmt->close();
            }
            $stmtr->close();
            $logoutHH = '';
            $logoutMM = '';
            $logouttime = '';
        }
        
        header("Location: mbhEmps.php?empid=$userid&empsection=time-management&time-section=attendance");
        exit();
    }
?>