<?php
    if (!isset($action)) {
        $action = '';
    }
    $empid = $_SESSION['id'];
    
    $stmtemp = $conn->prepare("SELECT * FROM user WHERE id=?");
    $stmtemp->bind_param("i", $empid);
    $stmtemp->execute();
    $resultemp = $stmtemp->get_result();
    if($resultemp->num_rows > 0){
        $rowemp = $resultemp->fetch_assoc();
        $empname = isset($rowemp['name']) ? $rowemp['name'] : 'Unknown';
    }
    
    $timestamp = date("Y-m-d H:i:s");
    
    $stmtlog = $conn->prepare("INSERT INTO logs (empid, emp_name, action, timestamp) VALUES (?, ?, ?, ?)");
    $stmtlog->bind_param("isss", $empid, $empname, $action, $timestamp);
    $stmtlog->execute();
?>