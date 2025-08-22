<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $id = stripslashes($_REQUEST['id']);
    $id = mysqli_real_escape_string($conn, $id);
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['emp_dperm'] === '1'){
        if (isset($_POST['CheckedD'])) {
            $CheckedD = $_POST['CheckedD'];
        
            $ids = implode(',', array_map('intval', $CheckedD));
            $query_del = "DELETE FROM incdec WHERE id IN ($ids)";
            $result_del = mysqli_query($conn, $query_del);
        }
    }
    header("Location: incdec.php?id=$id");
    exit();
?>