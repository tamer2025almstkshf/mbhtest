<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $id = stripslashes($_REQUEST['id']);
    $id = mysqli_real_escape_string($conn, $id);
    
    $emergency_name1 = stripslashes($_REQUEST['emergency_name1']);
    $emergency_name1 = mysqli_real_escape_string($conn, $emergency_name1);
    
    $emergency_relate1 = stripslashes($_REQUEST['emergency_relate1']);
    $emergency_relate1 = mysqli_real_escape_string($conn, $emergency_relate1);
    
    $emergency_tel1 = stripslashes($_REQUEST['emergency_tel1']);
    $emergency_tel1 = mysqli_real_escape_string($conn, $emergency_tel1);
    
    $emergency_name2 = stripslashes($_REQUEST['emergency_name2']);
    $emergency_name2 = mysqli_real_escape_string($conn, $emergency_name2);
    
    $emergency_relate2 = stripslashes($_REQUEST['emergency_relate2']);
    $emergency_relate2 = mysqli_real_escape_string($conn, $emergency_relate2);
    
    $emergency_tel2 = stripslashes($_REQUEST['emergency_tel2']);
    $emergency_tel2 = mysqli_real_escape_string($conn, $emergency_tel2);
    
    $query = "UPDATE user SET emergency_name1 = '$emergency_name1', emergency_relate1 = '$emergency_relate1', emergency_tel1 = '$emergency_tel1', 
    emergency_name2 = '$emergency_name2', emergency_relate2 = '$emergency_relate2', emergency_tel2 = '$emergency_tel2' WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    
    header("Location: emp_data.php?id=$id&emerg=success");
    exit();
?>