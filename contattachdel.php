<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    $page = filter_input(INPUT_GET, "page", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if($row_permcheck['emp_perms_edit'] == 1){
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        if(isset($_GET['id']) && $_GET['id'] !== '' && isset($_GET['del']) && $_GET['del'] !== ''){
            $del = filter_input(INPUT_GET, "del", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            if($del === 'cont_lic_pic'){
                $delar = "مرفق العقد / الرخصة";
            } else if($del === 'attachment1'){
                $delar = "المرفق 1";
            } else if($del === 'attachment2'){
                $delar = "المرفق 2";
            } else if($del === 'attachment3'){
                $delar = "المرفق 3";
            } else if($del === 'attachment4'){
                $delar = "المرفق 4";
            }
            
            $action = "تم حذف $delar من العقود و الرخص";
            
            $stmt = $conn->prepare("UPDATE contracts SET $del=? WHERE id=?");
            $empty = '';
            $stmt->bind_param("si", $empty, $id);
            $stmt->execute();
            $stmt->close();
            
            include_once 'addlog.php';
        }
        header("Location: $page?attachments=1&id=$id");
        exit();
    } else {
        header("Location: $page");
    }
?>