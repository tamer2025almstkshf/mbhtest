<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $iid = stripslashes($_REQUEST['iid']);
    $iid = mysqli_real_escape_string($conn, $iid);
    
    $id = stripslashes($_REQUEST['user_id']);
    $id = mysqli_real_escape_string($conn, $id);
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['emp_eperm'] === '1'){
        $date = stripslashes($_REQUEST['date']);
        $date = mysqli_real_escape_string($conn, $date);
        
        $dec_amount = stripslashes($_REQUEST['dec_amount']);
        $dec_amount = mysqli_real_escape_string($conn, $dec_amount);
        
        $reason = stripslashes($_REQUEST['reason']);
        $reason = mysqli_real_escape_string($conn, $reason);
        
        $query = "UPDATE incdec SET date='$date', amount='$dec_amount', reason='$reason' WHERE id='$iid'";
        $result = mysqli_query($conn, $query);
    }
    header("Location: incdec.php?id=$id&iid=$iid");
    exit();
?>