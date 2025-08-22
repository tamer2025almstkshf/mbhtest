<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $id = $_SESSION['id'];
    $querymain = "SELECT * FROM user WHERE id='$id'";
    $resultmain = mysqli_query($conn, $querymain);
    $rowmain = mysqli_fetch_array($resultmain);
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['csched_eperm'] === '1'){
        $id = $_GET['id'];
        if(isset($_GET['id']) && $_GET['id'] !== '' && isset($_GET['del']) && $_GET['del'] !== ''){
            $del = $_GET['del'];
            
            if($del === 'meeting'){
                $delar = "مرفق محضر الاجتماع";
            }
            
            $action = "تم حذف $delar من الموكل رقم : $id";
            
            $query = "UPDATE clients_schedule SET $del='' WHERE id='$id'";
            $result = mysqli_query($conn, $query);
            
            $empid = $_SESSION['id'];
            
            $queryu = "SELECT * FROM user WHERE id='$empid'";
            $resultu = mysqli_query($conn, $queryu);
            $rowu = mysqli_fetch_array($resultu);
            $emp_name = $rowu['name'];
            
            $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
            $resultlog = mysqli_query($conn, $querylog);
        }
        header("Location: clients_schedule.php?attachments=1&id=$id");
        exit();
    } else {
        header("Location: clients_schedule.php");
    }
?>