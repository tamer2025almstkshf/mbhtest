<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $id = $_SESSION['id'];
    $stmt = $conn->prepare("SELECT * FROM user WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultmain = $stmt->get_result();
    $rowmain = mysqli_fetch_array($resultmain);

    $myid = $_SESSION['id'];
    $stmt = $conn->prepare("SELECT * FROM user WHERE id=?");
    $stmt->bind_param("i", $myid);
    $stmt->execute();
    $result_permcheck = $stmt->get_result();
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['csched_eperm'] === '1'){
        $id = (int)$_GET['id'];
        if(isset($_GET['id']) && $_GET['id'] !== '' && isset($_GET['del']) && $_GET['del'] !== ''){
            $del = $_GET['del'];
            $allowedCols = ['meeting'];
            if(!in_array($del, $allowedCols)){
                header("Location: clients_schedule.php");
                exit();
            }
            
            if($del === 'meeting'){
                $delar = "مرفق محضر الاجتماع";
            }
            
            $action = "تم حذف $delar من الموكل رقم : $id";
            
            $stmt = $conn->prepare("UPDATE clients_schedule SET $del='' WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            $empid = $_SESSION['id'];
            
            $stmt = $conn->prepare("SELECT * FROM user WHERE id=?");
            $stmt->bind_param("i", $empid);
            $stmt->execute();
            $resultu = $stmt->get_result();
            $rowu = mysqli_fetch_array($resultu);
            $emp_name = $rowu['name'];
            $stmt = $conn->prepare("INSERT INTO logs (empid, emp_name, action) VALUES (?,?,?)");
            $stmt->bind_param("iss", $empid, $emp_name, $action);
            $stmt->execute();
        }
        header("Location: clients_schedule.php?attachments=1&id=$id");
        exit();
    } else {
        header("Location: clients_schedule.php");
    }
?>