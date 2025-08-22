<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'secure_filesfunc.php';
    
    if($row_permcheck['emp_perms_add'] == 1){
        if(isset($_REQUEST['type']) && $_REQUEST['type'] !== '' && isset($_REQUEST['emp_id']) && $_REQUEST['emp_id'] !== ''){
            $model = filter_input(INPUT_POST, "model", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $num = filter_input(INPUT_POST, 'num', FILTER_SANITIZE_NUMBER_INT);
            $emp_id = filter_input(INPUT_POST, 'emp_id', FILTER_SANITIZE_NUMBER_INT);
            $lic_expir = filter_input(INPUT_POST, "lic_expir", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $insur_expir = filter_input(INPUT_POST, "insur_expir", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $branch = filter_input(INPUT_POST, "branch", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $notes = filter_input(INPUT_POST, "notes", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $targetDir = "files_images/employees/$emp_id/vehicles";
            $photo = '';
            $upload = 0;
            
            if (isset($_FILES['photo'])) {
                $uploadResult = secure_file_upload($_FILES['photo'], $targetDir);
                
                if ($uploadResult['status']) {
                    echo "Photo has been uploaded: " . htmlspecialchars($uploadResult['path']) . "<br>";
                    $photo = $uploadResult['path'];
                    $upload = 1;
                } else {
                    echo "Upload failed: " . htmlspecialchars($uploadResult['error']) . "<br>";
                }
            }
            
            if ($upload === 0) {
                $photo = '';
            }
            
            $timestamp = date('Y/m/d');
            
            $stmt = $conn->prepare("INSERT INTO vehicles (model, type, num, emp_id, lic_expir, insur_expir, branch, photo, notes, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiissssss", $model, $type, $num, $emp_id, $lic_expir, $insur_expir, $branch, $photo, $notes, $timestamp);
            $stmt->execute();
            $stmt->close();
            
            $submit_back = filter_input(INPUT_POST, "submit_back", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($submit_back === 'addmore'){
                header("Location: Office_Vehicles.php?savedvehicle=1&addmore=1");
            } else{
                header("Location: Office_Vehicles.php?savedvehicle=1");
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