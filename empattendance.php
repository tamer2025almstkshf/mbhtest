<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    foreach ($_POST['login_time'] as $date => $login) {
        $logout = $_POST['logout_time'][$date] ?? '';
        if(strpos($login, ':') !== false) {
            list($inHH, $inMM) = explode(":", $login);
            $inHH = intval($inHH);
            if(!isset($inHH) || $inHH > 23 || $inHH < 0){
                $inHH = 0;
            }
            if(intval($inHH) < 10){
                $inHH = "0$inHH";
            }
            
            $inMM = intval($inMM);
            if(!isset($inMM) || $inMM > 59 || $inMM < 0){
                $inMM = 0;
            }
            if(intval($inMM) < 10){
                $inMM = "0$inMM";
            }
            
            $login_time = "$inHH:$inMM";
        } else {
            $login_time = "";
        }
        
        if(strpos($logout, ':') !== false) {
            list($outHH, $outMM) = explode(":", $logout);
            $outHH = intval($outHH);
            if(!isset($outHH) || $outHH > 23 || $outHH < 0){
                $outHH = 0;
            }
            if(intval($outHH) < 10){
                $outHH = "0$outHH";
            }
            
            $outMM = intval($outMM);
            if(!isset($outMM) || $outMM > 59 || $outMM < 0){
                $outMM = 0;
            }
            if(intval($outMM) < 10){
                $outMM = "0$outMM";
            }
            
            $logout_time = "$outHH:$outMM";
        } else {
            $logout_time = "";
        }
        
        $day = date("l", strtotime($date));
        $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
        
        $stmtlogin = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
        $stmtlogin->bind_param("s", $day);
        $stmtlogin->execute();
        $resultlogin = $stmtlogin->get_result();
        $rowlogin = $resultlogin->fetch_assoc();
        $stmtlogin->close();
        
        $expected_login = $rowlogin['login_time'];
        $expected_logout = $rowlogin['logout_time'];
        if($login_time !== ''){
            $actual_login = new DateTime($login_time);
            $expected_login_dt = new DateTime($expected_login);
        } if($logout_time !== ''){
            $actual_logout = new DateTime($logout_time);
            $expected_logout_dt = new DateTime($expected_logout);
        }
        
        $late_login = '00:00';
        $early_logout = '00:00';
        if ($login_time !== '' && $actual_login > $expected_login_dt) {
            $diff_login = $expected_login_dt->diff($actual_login);
            $late_login = str_pad($diff_login->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($diff_login->i, 2, '0', STR_PAD_LEFT);
        }
        
        if ($logout_time !== '' && $actual_logout < $expected_logout_dt) {
            $diff_logout = $actual_logout->diff($expected_logout_dt);
            $early_logout = str_pad($diff_logout->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($diff_logout->i, 2, '0', STR_PAD_LEFT);
        }
        
        if((isset($login_time) && $login_time !== '' && $login_time !== '00:00') || (isset($logout_time) && $logout_time !== '' && $logout_time !== '00:00')){
            $stmtr = $conn->prepare("SELECT * FROM user_logs WHERE user_id=? AND login_day=? AND login_date=? AND logout_day=? AND logout_date=?");
            $stmtr->bind_param("issss", $user_id, $day, $date, $day, $date);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            if($resultr->num_rows > 0){
                $stmt = $conn->prepare("UPDATE user_logs SET login_time=?, late_login=?, logout_time=?, early_logout=? WHERE user_id=? AND login_day=? AND login_date=? AND logout_day=? AND logout_date=?");
                $stmt->bind_param("ssssissss", $login_time, $late_login, $logout_time, $early_logout, $user_id, $day, $date, $day, $date);
                $stmt->execute();
                $stmt->close();
            } else{
                $stmt = $conn->prepare("INSERT INTO user_logs (user_id, login_day, login_date, login_time, late_login, logout_day, logout_date, logout_time, early_logout) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issssssss", $user_id, $day, $date, $login_time, $late_login, $day, $date, $logout_time, $early_logout);
                $stmt->execute();
                $stmt->close();
            }
            $stmtr->close();
        }
    }
    $queryString = filter_input(INPUT_POST, "queryString", FILTER_SANITIZE_URL);
    header("Location: attendance.php?$queryString");
    exit();
?>