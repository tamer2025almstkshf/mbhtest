<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['emp_perms_delete'] === '1'){
        if (isset($_POST['CheckedD'])) {
            $CheckedD = $_POST['CheckedD'];
            
            $ids = implode(',', array_map('intval', $CheckedD));
            $query_del = "DELETE FROM contracts WHERE id IN ($ids)";
            
            if (mysqli_query($conn, $query_del)) {
                header("Location: Contracts.php");
                exit();
            } else {
                header("Location: Contracts.php?error=0");
                exit();
            }
        } else{
            header("Location: Contracts.php?error=17");
            exit();
        }
    }
    header("Location: Contracts.php");
    exit();
?>