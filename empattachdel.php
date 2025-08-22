<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['useratts_dperm'] == 1){
        if(isset($_GET['empid'])){
            $user_id = $_GET['empid'];
            $id = $_GET['id'];
        }
        if(isset($_GET['del']) && $_GET['del'] !== ''){
            $del = $_GET['del'];
            
            if($del === 'passport_residence'){
                $delar = "جواز السفر";
            } else if($del === 'practical_qualification'){
                $delar = "المؤهل العملي";
            } else if($del === 'biography'){
                $delar = "السيرة الذاتية";
            } else if($del === 'uaeresidence'){
                $delar = "الهوية الاماراتية";
            } else if($del === 'behaviour'){
                $delar = "شهادة حسن السيرة و السلوك";
            } else if($del === 'university'){
                $delar = "الشهادة الجامعية";
            } else if($del === 'contract'){
                $delar = "عقد العمل";
            } else if($del === 'card'){
                $delar = "بطاقة العمل";
            } else if($del === 'sigorta'){
                $delar = "التأمين الصحي";
            } else if($del === 'other'){
                $delar = "(الأُخرى)";
            }
            
            $action = "تم حذف مرفق $delar من الموظف رقم : $choosenempid";
            
            if($del === 'passport_residence'){
                $empty = '';
                $stmt = $conn->prepare("UPDATE user SET $del=? WHERE id=?");
                $stmt->bind_param("si", $empty, $user_id);
                $stmt->execute();
                $stmt->close();
            } else{
                $empty = '';
                $stmt = $conn->prepare("UPDATE user_attachments SET $del=? WHERE id=?");
                $stmt->bind_param("si", $empty, $id);
                $stmt->execute();
                $stmt->close();
            }
            
            include_once 'addlog.php';
        }
        $empid = $_GET['empid'];
        header("Location: mbhEmps.php?empid=$empid&empsection=attachments");
        exit();
    } else {
        header("Location: mbhEmps.php?empid=$empid&empsection=attachments&error=0");
        exit();
    }
?>