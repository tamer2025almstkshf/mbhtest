<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['emp_perms_delete'] === '1'){
        if(isset($_GET['id']) && $_GET['id'] !== ''){
            $id = $_GET['id'];
            
            $query = "DELETE FROM user WHERE id='$id'";
            $result = mysqli_query($conn, $query);
        }
    }
    header("Location: mbhEmps.php");
    exit();
?>