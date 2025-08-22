<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['csched_dperm'] == 1){
        if(isset($_GET['id']) && $_GET['id'] !== ''){
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            $stmtr = $conn->prepare("SELECT * FROM events WHERE id=?");
            $stmtr->bind_param("i", $id);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmtr->close();
            
            $date = $rowr['event_date'];
            
            $stmt = $conn->prepare("DELETE FROM events WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        } else{
            $date = date("Y-m-d");
        }
    }
    
    $month = date('m', strtotime($date));
    $year = date('Y', strtotime($date));
    header("Location: meetings.php?month=$month&year=$year&edit=1");
    exit;
?>