<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'secure_filesfunc.php';
    
    if($row_permcheck['emp_perms_edit'] == 1){
        if(isset($_REQUEST['type']) && $_REQUEST['type'] !== '' && isset($_REQUEST['emp_id']) && $_REQUEST['emp_id'] !== ''){
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $model = filter_input(INPUT_POST, "model", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $num = filter_input(INPUT_POST, 'num', FILTER_SANITIZE_NUMBER_INT);
            $emp_id = filter_input(INPUT_POST, 'emp_id', FILTER_SANITIZE_NUMBER_INT);
            $lic_expir = filter_input(INPUT_POST, "lic_expir", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $insur_expir = filter_input(INPUT_POST, "insur_expir", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $branch = filter_input(INPUT_POST, "branch", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $notes = filter_input(INPUT_POST, "notes", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            if(isset($_REQUEST['remove_photo']) && $_REQUEST['remove_photo'] == 1){
                $empty = '';
                $stmt_delphoto = $conn->prepare("UPDATE vehicles SET photo=? WHERE id=?");
                $stmt_delphoto->bind_param("si", $empty, $id);
                $stmt_delphoto->execute();
                $stmt_delphoto->close();
            }
            
            $stmtr = $conn->prepare("SELECT * FROM vehicles WHERE id=?");
            $stmtr->bind_param("i", $id);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmtr->close();
            
            $photo = $rowr['photo'];
            $targetDir = "files_images/employees/$emp_id/vehicles";
            
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            if (isset($_FILES['photo'])) {
                $uploadResult = secure_file_upload($_FILES['photo'], $targetDir);
                
                if ($uploadResult['status']) {
                    echo "Photo has been uploaded: " . htmlspecialchars($uploadResult['path']) . "<br>";
                    $photo = $uploadResult['path'];
                } else {
                    echo "Upload failed: " . htmlspecialchars($uploadResult['error']) . "<br>";
                }
            }
            
            $stmt = $conn->prepare("UPDATE vehicles SET model=?, type=?, num=?, emp_id=?, lic_expir=?, insur_expir=?, branch=?, notes=?, photo=? WHERE id=?");
            $stmt->bind_param("ssiisssssi", $model, $type, $num, $emp_id, $lic_expir, $insur_expir, $branch, $notes, $photo, $id);
            $stmt->execute();
            $stmt->close();
            
            $submit_back = filter_input(INPUT_POST, "submit_back", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($submit_back === 'addmore'){
                header("Location: Office_Vehicles.php?editedvehicle=1&addmore=1");
            } else{
                header("Location: Office_Vehicles.php?editedvehicle=1");
            }
            exit();
        } else{
            header("Location: Office_Vehicles.php?error=0");
            exit();
        }
    }
    header("Location: Office_Vehicles.php");
    exit();
?>