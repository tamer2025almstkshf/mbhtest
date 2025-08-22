<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $id = $_GET['id'];
    $del = $_GET['del'];
    $user_id = $_GET['user_id'];
    $by = $del.'_by';
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['emp_dperm'] === '1'){
        if($del === 'passport_residence'){
            $user_id = $id;
            $queryr = "SELECT * FROM user WHERE id='$id'";
            $query = "UPDATE user SET $del='' WHERE id='$id'";
        } else{
            $queryr = "SELECT * FROM user_attachments WHERE id='$id'";
            $query = "UPDATE user_attachments SET $del='' WHERE id='$id'";
            
            $queryr1 = "SELECT * FROM user_attachments WHERE user_id='$user_id'";
            $resultr1 = mysqli_query($conn, $queryr1);
            while($rowr1 = mysqli_fetch_array($resultr1)){
                $uaid = $rowr1['id'];
                $queryby = "UPDATE user_attachments SET $by='0' WHERE id='$uaid'";
                $resultby = mysqli_query($conn, $queryby);
            }
        }
        
        $resultr = mysqli_query($conn, $queryr);
        $rowr = mysqli_fetch_array($resultr);
        unlink($rowr["$del"]);
        
        $empid = $_SESSION['id'];
        
        $queryu = "SELECT * FROM user WHERE id='$empid'";
        $resultu = mysqli_query($conn, $queryu);
        $rowu = mysqli_fetch_array($resultu);
        $emp_name = $rowu['name'];
        
        if($del === 'biography'){
            $aratt = "السيرة الذاتية";
        } else if($del === 'passport_residence'){
            $aratt = "جواز السفر";
        } else if($del === 'uaeresidence'){
            $aratt = "الهوية الاماراتية";
        } else if($del === 'behaviour'){
            $aratt = "شهادة حسن السيرة و السلوك";
        } else if($del === 'university'){
            $aratt = "الشهادة الجامعية";
        } else if($del === 'contract'){
            $aratt = "عقد العمل";
        } else if($del === 'card'){
            $aratt = "بطاقة العمل";
        } else if($del === 'sigorta'){
            $aratt = "التأمين الصحي";
        } else if($del === 'other'){
            $aratt = "أخرى";
        }
        
        $action = "تم حذف مرفق $aratt من ملف الموظف رقم $user_id";
        
        $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
        $resultlog = mysqli_query($conn, $querylog);
        
        $result = mysqli_query($conn, $query);
    }
    header("Location: emp_atts.php?id=$user_id");
    exit();
?>