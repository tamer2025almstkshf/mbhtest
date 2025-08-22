<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    
    $page = $_GET['page'];
    
    if($row_permcheck['clients_eperm'] == 1){
        $id = $_GET['id'];
        if(isset($_GET['id']) && $_GET['id'] !== '' && isset($_GET['del']) && $_GET['del'] !== ''){
            $del = $_GET['del'];
            
            if($del === 'passport'){
                $delar = "مرفق جواز السفر";
            } else if($del === 'id_file'){
                $delar = "مرفق الهوية";
            } else if($del === 'auth'){
                $delar = "مرفق الوكالة";
            } else if($del === 'attach1'){
                $delar = "المرفق 1";
            } else if($del === 'attach2'){
                $delar = "المرفق 2";
            } else if($del === 'attach3'){
                $delar = "المرفق 3";
            } else if($del === 'attach4'){
                $delar = "المرفق 4";
            } else if($del === 'attach5'){
                $delar = "المرفق 5";
            } else if($del === 'attach6'){
                $delar = "المرفق 6";
            }
            
            $action = "تم حذف $delar من الموكل رقم : $id";
            
            $stmt = $conn->prepare("UPDATE client SET $del='' WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            include_once 'logadd.php';
        }
        header("Location: $page?attachments=1&id=$id");
        exit();
    } else {
        header("Location: $page");
    }
?>