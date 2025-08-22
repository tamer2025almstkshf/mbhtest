<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $user_id = stripslashes($_REQUEST['user_id']);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    
    $lid = stripslashes($_REQUEST['lid']);
    $lid = mysqli_real_escape_string($conn, $lid);
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['emp_eperm'] === '1'){
        if(isset($_REQUEST['login_day']) && $_REQUEST['login_day'] !== '' && isset($_REQUEST['login_date']) && $_REQUEST['login_date'] !== '' && isset($_REQUEST['login_time'])
        && $_REQUEST['login_time'] !== '' && isset($_REQUEST['logout_day']) && $_REQUEST['logout_day'] !== '' && isset($_REQUEST['logout_date']) && $_REQUEST['logout_date'] !== ''
        && isset($_REQUEST['logout_time']) && $_REQUEST['logout_time'] !== ''){
            $login_day = stripslashes($_REQUEST['login_day']);
            $login_day = mysqli_real_escape_string($conn, $login_day);
            
            $login_date = stripslashes($_REQUEST['login_date']);
            $login_date = mysqli_real_escape_string($conn, $login_date);
            
            $login_time = stripslashes($_REQUEST['login_time']);
            $login_time = mysqli_real_escape_string($conn, $login_time);
            
            $logout_day = stripslashes($_REQUEST['logout_day']);
            $logout_day = mysqli_real_escape_string($conn, $logout_day);
            
            $logout_date = stripslashes($_REQUEST['logout_date']);
            $logout_date = mysqli_real_escape_string($conn, $logout_date);
            
            $logout_time = stripslashes($_REQUEST['logout_time']);
            $logout_time = mysqli_real_escape_string($conn, $logout_time);
            
            $late_time = "08:00";
            list($login_timeHH, $login_timeMM) = explode(":", $login_time);
            list($late_timeHH, $late_timeMM) = explode(":", $late_time);
            
            if($login_timeHH > $late_timeHH){
                $late_loginHH = $login_timeHH - $late_timeHH;
                $late_loginMM = $login_timeMM - $late_timeMM;
                $late_login = "$late_loginHH ساعات و $late_loginMM دقائق";
            } 
            
            else if($login_timeHH === $late_timeHH){
                $late_loginMM = $login_timeMM - $late_timeMM;
                $late_login = "$late_loginMM دقائق";
            } 
            
            else{
                $late_login = '0';
            }
            
            $early_time = "18:00";
            list($logout_timeHH, $logout_timeMM) = explode(":", $logout_time);
            list($early_timeHH, $early_timeMM) = explode(":", $early_time);
            
            if($early_timeHH > $logout_timeHH){
                $early_logoutHH = $early_timeHH - $logout_timeHH - 1;
                $early_logoutMM = 60 - $logout_timeMM;
                if($early_logoutMM === '60'){
                    $early_logoutHH = $early_logoutHH + 1;
                }
                $early_logout = "$early_logoutHH ساعات و $early_logoutMM دقائق";
            } 
            
            else if($logout_timeHH === $early_timeHH){
                $early_logoutMM = $logout_timeMM - $early_timeMM;
                $early_logout = "$early_logoutMM دقائق";
            } 
            
            else{
                $early_logout = '0';
            }
            
            $query = "UPDATE user_logs SET login_day='$login_day', login_date='$login_date', login_time='$login_time', late_login='$late_login', 
            logout_day='$logout_day', logout_date='$logout_date', logout_time='$logout_time', early_logout='$early_logout' WHERE id='$lid'";
            $result = mysqli_query($conn, $query);
        }
    }
    header("Location: user_Logs.php?id=$user_id&lid=$lid");
    exit();
?>