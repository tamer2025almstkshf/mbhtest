<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $user_id = stripslashes($_REQUEST['user_id']);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['emp_dperm'] === '1'){
        if (isset($_POST['CheckedD'])) {
            $CheckedD = $_POST['CheckedD'];
        
            $ids = implode(',', array_map('intval', $CheckedD));
            $query_del = "DELETE FROM user_logs WHERE id IN ($ids)";
        
            if (mysqli_query($conn, $query_del)) {
                header("Location: user_Logs.php?id=$user_id");
                exit();
            } else {
                header("Location: user_Logs.php?id=$user_id");
                exit();
            }
        } else{
            header("Location: user_Logs.php?id=$user_id");
            exit();
        }
    }
    header("Location: Office_Vehicles.php");
    exit();
?>