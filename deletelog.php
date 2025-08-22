<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['logs_dperm'] == 1){
        if(isset($_GET['id']) && $_GET['id'] !== ''){
            $logid = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            $stmt = $conn->prepare("DELETE FROM logs WHERE id=?");
            $stmt->bind_param("i", $logid);
            $stmt->execute();
            $stmt->close();
            
            header("Location: Logs.php");
            exit();
        } else{
            header("Location: Logs.php?error=0");
            exit();
        }
    }
    header("Location: Logs.php?error=0");
    exit();
?>