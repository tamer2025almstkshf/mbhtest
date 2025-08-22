<?php
    include_once 'connection.php';
    include_once 'login_check.php';

    if(isset($_REQUEST['emp_id']) && $_REQUEST['emp_id'] !== ''){
        $emp_id = stripslashes($_REQUEST['emp_id']);
        $emp_id = mysqli_real_escape_string($conn, $emp_id);

        $type = stripslashes($_REQUEST['type']);
        $type = mysqli_real_escape_string($conn, $type);
        
        $starting_date = stripslashes($_REQUEST['starting_date']);
        $starting_date = mysqli_real_escape_string($conn, $starting_date);
        
        $ending_date = stripslashes($_REQUEST['ending_date']);
        $ending_date = mysqli_real_escape_string($conn, $ending_date);

        $timestamp = date('Y/m/d');

        $query = "INSERT INTO vacations (emp_id, type, ask_date, starting_date, ending_date, ask, ask2, timestamp) VALUES ('$emp_id', 
        '$type', '$timestamp', '$starting_date', '$ending_date', '3', '3', '$timestamp')";
        $result = mysqli_query($conn, $query);

        header("Location: employeeEdit.php?id=$emp_id");
        exit();
    } else{
        $emp_id = stripslashes($_REQUEST['emp_id']);
        $emp_id = mysqli_real_escape_string($conn, $emp_id);

        if(!isset($_REQUEST['emp_id']) || $_REQUEST['emp_id'] === ''){
            header("Location: employeeEdit.php?id=$emp_id&e=0");
            exit();
        } else{
            header("Location: employeeEdit.php?id=$emp_id&error=unknown");
            exit();
        }
    }
?>