<?php
    include_once 'connection.php';
    include_once 'login_check.php';

    if(isset($_REQUEST['emp_id']) && $_REQUEST['emp_id'] !== ''&& isset($_REQUEST['warning_date']) && 
    $_REQUEST['warning_date'] !== ''){
        
        $emp_id = stripslashes($_REQUEST['emp_id']);
        $emp_id = mysqli_real_escape_string($conn, $emp_id);
        
        $warning_reason = stripslashes($_REQUEST['warning_reason']);
        $warning_reason = mysqli_real_escape_string($conn, $warning_reason);
        
        $warning_date = stripslashes($_REQUEST['warning_date']);
        $warning_date = mysqli_real_escape_string($conn, $warning_date);

        $timestamp = date('Y/m/d');

        $query = "INSERT INTO warnings (emp_id, warning_reason, warning_date, timestamp) VALUES ('$emp_id', 
        '$warning_reason', '$warning_date', '$timestamp')";

        $result = mysqli_query($conn, $query);

        header("Location: employeeEdit.php?id=$emp_id");
        exit();
    } else{
        
        $emp_id = stripslashes($_REQUEST['emp_id']);
        $emp_id = mysqli_real_escape_string($conn, $emp_id);

        if(!isset($_REQUEST['warning_date']) || $_REQUEST['warning_date'] === ''){
            header("Location: employeeEdit.php?id=$emp_id&wd=0");
            exit();
        } else{
            header("Location: employeeEdit.php?id=$emp_id&error=unknown");
            exit();
        }
    }
?>