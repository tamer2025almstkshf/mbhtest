<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['cfiles_eperm'] == 1){
        if (isset($_GET['id']) && $_GET['id'] !== '') {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            
            $stmtr = $conn->prepare("SELECT * FROM file_note WHERE id=?");
            $stmtr->bind_param("i", $id);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmtr->close();
            
            $oldn = $rowr['notename'];
            $oldnote = $rowr['note'];
            $fid = $rowr['file_id'];
            
            $flag = '0';
            $action = "تم حذف ملاحظة من الملف رقم : $fid<br>";
            
            if(isset($oldn) && $oldn !== ''){
                $flag = '1';
                
                $action = $action."<br>الملاحظة : $oldnote";
            }
            
            if(isset($flag) && $flag === '1'){
                include_once 'addlog.php';
            }
            
            $stmt = $conn->prepare("DELETE FROM file_note WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            
            header("Location: FileEdit.php?id=$fid&checknotes=1");
            exit();
        } else{
            header ("Location: FileEdit.php?id=$fid&error=0&checknotes=1");
            exit();
        }
    } else{
        header("Location: FileEdit.php?id=$fid&error=0");
        exit();
    }

?>
