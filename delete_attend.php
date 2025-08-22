<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['userattendance_dperm']){
        $attendid = filter_input(INPUT_GET, 'attendid', FILTER_SANITIZE_NUMBER_INT);
        $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);
        
        $stmt = $conn->prepare("DELETE FROM user_logs WHERE id=?");
        $stmt->bind_param("i", $attendid);
        $stmt->execute();
        $stmt->close();
        
        if(isset($_GET['page']) && $_GET['page'] !== ''){
            $page = filter_input(INPUT_GET, "page", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            header("Location: $page");
        } else{
            header("Location: mbhEmps.php?empid=$user_id&empsection=time-management&time-section=attendance");
        }
        exit();
    }
?>