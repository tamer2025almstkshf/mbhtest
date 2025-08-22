<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'secure_filesfunc.php';
    
    if($row_permcheck['emp_perms_add'] == 1){
        if(isset($_REQUEST['owner']) && $_REQUEST['owner'] !== ''){
            $rent_lic = filter_input(INPUT_POST, "rent_lic", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $owner = filter_input(INPUT_POST, "owner", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $place = filter_input(INPUT_POST, "place", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $starting_d = filter_input(INPUT_POST, "starting_d", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $ending_d = filter_input(INPUT_POST, "ending_d", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $branch = filter_input(INPUT_POST, "branch", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $targetDir = "files_images/clients/contracts/$owner";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $cont_lic_pic = '';
            if (isset($_FILES['cont_lic_pic'])) {
                $uploadResult = secure_file_upload($_FILES['cont_lic_pic'], $targetDir);
                
                if ($uploadResult['status']) {
                    echo "Photo has been uploaded: " . htmlspecialchars($uploadResult['path']) . "<br>";
                    $cont_lic_pic = $uploadResult['path'];
                } else {
                    echo "Upload failed: " . htmlspecialchars($uploadResult['error']) . "<br>";
                }
            }
            
            $attachment1 = '';
            if (isset($_FILES['attachment1'])) {
                $uploadResult = secure_file_upload($_FILES['attachment1'], $targetDir);
                
                if ($uploadResult['status']) {
                    echo "Photo has been uploaded: " . htmlspecialchars($uploadResult['path']) . "<br>";
                    $attachment1 = $uploadResult['path'];
                } else {
                    echo "Upload failed: " . htmlspecialchars($uploadResult['error']) . "<br>";
                }
            }
            
            $attachment2 = '';
            if (isset($_FILES['attachment2'])) {
                $uploadResult = secure_file_upload($_FILES['attachment2'], $targetDir);
                
                if ($uploadResult['status']) {
                    echo "Photo has been uploaded: " . htmlspecialchars($uploadResult['path']) . "<br>";
                    $attachment2 = $uploadResult['path'];
                } else {
                    echo "Upload failed: " . htmlspecialchars($uploadResult['error']) . "<br>";
                }
            }
            
            $attachment3 = '';
            if (isset($_FILES['attachment3'])) {
                $uploadResult = secure_file_upload($_FILES['attachment3'], $targetDir);
                
                if ($uploadResult['status']) {
                    echo "Photo has been uploaded: " . htmlspecialchars($uploadResult['path']) . "<br>";
                    $attachment3 = $uploadResult['path'];
                } else {
                    echo "Upload failed: " . htmlspecialchars($uploadResult['error']) . "<br>";
                }
            }
            
            $attachment4 = '';
            if (isset($_FILES['attachment4'])) {
                $uploadResult = secure_file_upload($_FILES['attachment4'], $targetDir);
                
                if ($uploadResult['status']) {
                    echo "Photo has been uploaded: " . htmlspecialchars($uploadResult['path']) . "<br>";
                    $attachment4 = $uploadResult['path'];
                } else {
                    echo "Upload failed: " . htmlspecialchars($uploadResult['error']) . "<br>";
                }
            }
            
            $notes = filter_input(INPUT_POST, "notes", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $timestamp = date('Y/m/d');
            
            $stmt = $conn->prepare("INSERT INTO contracts (rent_lic, owner, place, starting_d, ending_d, branch, cont_lic_pic, attachment1, attachment2, attachment3, 
            attachment4, notes, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssssss", $rent_lic, $owner, $place, $starting_d, $ending_d, $branch, $cont_lic_pic, $attachment1, $attachment2, $attachment3, $attachment4, 
            $notes, $timestamp);
            $stmt->execute();
            $stmt->close();
            
            $submit_back = filter_input(INPUT_POST, "submit_back", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            if($submit_back === 'addmore'){
                header("Location: Contracts.php?contractsaved=1&addmore=1");
            } else{
                header("Location: Contracts.php?contractsaved=1");
            }
            exit();
        } else{
            header("Location: Contracts.php?error=0");
            exit();
        }
    }
    header("Location: Contracts.php?error=0");
    exit();
?>