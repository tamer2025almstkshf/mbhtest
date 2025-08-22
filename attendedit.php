<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['userattendance_eperm']){
        if(isset($_REQUEST['login_date']) && $_REQUEST['login_date'] !== '' && (isset($_REQUEST['ltHH']) && $_REQUEST['ltHH'] !== '' || isset($_REQUEST['ltMM']) && $_REQUEST['ltMM'] !== '') && 
        (isset($_REQUEST['louttHH']) && $_REQUEST['louttHH'] !== '' || isset($_REQUEST['louttMM']) && $_REQUEST['louttMM'] !== '')){
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
            $ltHH = filter_input(INPUT_POST, "ltHH", FILTER_SANITIZE_NUMBER_INT);
            $ltHH = intval($ltHH);
            if($ltHH < 10){
                $ltHH = "0$ltHH";
            }
            $ltMM = filter_input(INPUT_POST, "ltMM", FILTER_SANITIZE_NUMBER_INT);
            $ltMM = intval($ltMM);
            if($ltMM < 10){
                $ltMM = "0$ltMM";
            }
            $louttHH = filter_input(INPUT_POST, "louttHH", FILTER_SANITIZE_NUMBER_INT);
            $louttHH = intval($louttHH);
            if($louttHH < 10){
                $louttHH = "0$louttHH";
            }
            $louttMM = filter_input(INPUT_POST, "louttMM", FILTER_SANITIZE_NUMBER_INT);
            $louttMM = intval($louttMM);
            if($louttMM < 10){
                $louttMM = "0$louttMM";
            }
            
            $login_date = filter_input(INPUT_POST, "login_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $login_day = date('l', strtotime($login_date));
            $login_time = "$ltHH:$ltMM";
            $logout_date = filter_input(INPUT_POST, "login_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $logout_day = date('l', strtotime($logout_date));
            $logout_time = "$louttHH:$louttMM";
    
            $stmtlogin = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
            $stmtlogin->bind_param("s", $login_day);
            $stmtlogin->execute();
            $resultlogin = $stmtlogin->get_result();
            $rowlogin = $resultlogin->fetch_assoc();
            $stmtlogin->close();
            
            $stmtlogout = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
            $stmtlogout->bind_param("s", $logout_day);
            $stmtlogout->execute();
            $resultlogout = $stmtlogout->get_result();
            $rowlogout = $resultlogout->fetch_assoc();
            $stmtlogout->close();
            
            $expected_login = $rowlogin['login_time'];
            $expected_logout = $rowlogout['logout_time'];
            $actual_login = new DateTime($login_time);
            $expected_login_dt = new DateTime($expected_login);
            $actual_logout = new DateTime($logout_time);
            $expected_logout_dt = new DateTime($expected_logout);
            
            $late_login = '00:00';
            $early_logout = '00:00';
            if ($actual_login > $expected_login_dt) {
                $diff_login = $expected_login_dt->diff($actual_login);
                $late_login = str_pad($diff_login->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($diff_login->i, 2, '0', STR_PAD_LEFT);
            }
            
            if ($actual_logout < $expected_logout_dt) {
                $diff_logout = $actual_logout->diff($expected_logout_dt);
                $early_logout = str_pad($diff_logout->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($diff_logout->i, 2, '0', STR_PAD_LEFT);
            }
            
            $stmt = $conn->prepare("UPDATE user_logs SET user_id=?, login_day=?, login_date=?, login_time=?, late_login=?, logout_day=?, logout_date=?, logout_time=?, early_logout=? WHERE id=?");
            $stmt->bind_param("issssssssi", $user_id, $login_day, $login_date, $login_time, $late_login, $logout_day, $logout_date, $logout_time, $early_logout, $id);
            $stmt->execute();
            $stmt->close();
            
            if(isset($_REQUEST['page']) && $_REQUEST['page'] !== ''){
                $page = filter_input(INPUT_POST, "page", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                header("Location: $page");
            } else{
                header("Location: mbhEmps.php?empid=$user_id&empsection=time-management&time-section=attendance");
            }
            exit();
        }
    }
    header("Location: attendance.php");
    exit();
?>