<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['cfiles_eperm'] == 1){
        if(isset($_REQUEST['fid']) && (!isset($_REQUEST['type_id']) || $_REQUEST['type_id'] === '')){
            $fid = filter_input(INPUT_POST, 'fid', FILTER_SANITIZE_NUMBER_INT);
            
            $stmtid = $conn->prepare("SELECT * FROM file WHERE file_id=?");
            $stmtid->bind_param("i", $fid);
            $stmtid->execute();
            $resultid = $stmtid->get_result();
            $row_details = $resultid->fetch_assoc();
            $stmtid->close();
            if($admin != 1){
                if($row_details['secret_folder'] == 1){
                    $empids = $row_details['secret_emps'];
                    $empids = array_filter(array_map('trim', explode(',', $empids)));
                    if (!in_array($_SESSION['id'], $empids)) {
                        exit();
                    }
                }
            }
        } else if(isset($_REQUEST['fid']) && isset($_REQUEST['type_id']) && $_REQUEST['type_id'] !== ''){
            $fid = filter_input(INPUT_POST, 'fid', FILTER_SANITIZE_NUMBER_INT);
            $type_id = filter_input(INPUT_POST, "type_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $flag = '0'; 
            $action = "تم التعديل على ملف :<br>رقم الملف : $fid<br>";
            
            $stmtr = $conn->prepare("SELECT * FROM file WHERE file_id=?");
            $stmtr->bind_param("i", $fid);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            
            $oldtype = $rowr['file_type'];
            if(isset($type_id) && $type_id !== $oldtype){
                $flag = '1';
                
                $action = $action."<br>تم التعديل على نوع الملف : من $oldtype الى $type_id";
            }
            
            $stmt = $conn->prepare("UPDATE file SET file_type=? WHERE file_id=?");
            $stmt->bind_param("ii", $type_id, $fid);
            $stmt->execute();
            
            if(isset($flag) && $flag === '1'){
                include_once 'addlog.php';
            }
        }
        header("Location: fileconverter.php?fid=$fid");
        exit();
    }
    header("Location: fileconverter.php?fid=$fid");
    exit();
?>