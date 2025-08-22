<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'secure_filesfunc.php';
    include_once 'errorscheck.php';
    
    if($row_permcheck['goaml_aperm'] == 1){
        $empty = '';
        
        $stmtr = $conn->prepare("SELECT * FROM goAML WHERE goAML_attachment=? AND empid=? AND timestamp=?");
        $stmtr->bind_param("sss", $empty, $empty, $empty);
        $stmtr->execute();
        $resultr = $stmtr->get_result();
        
        if($resultr->num_rows === 0){
            $stmtc = $conn->prepare("INSERT INTO goAML (goAML_attachment, empid, timestamp) VALUES (?, ?, ?)");
            $stmtc->bind_param("sss", $empty, $empty, $empty);
            $stmtc->execute();
            $stmtc->close();
            
            $stmtr = $conn->prepare("SELECT * FROM goAML WHERE goAML_attachment=? AND empid=? AND timestamp=?");
            $stmtr->bind_param("sss", $empty, $empty, $empty);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
        }
        
        $rowr = $resultr->fetch_assoc();
        $stmtr->close();
        $id = $rowr['id'];
        
        $targetDir = "files_images/goAML/$id";
        
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $AML_attachment_result = is_array($_FILES['AML_attachment'] ?? null) ? secure_file_upload($_FILES['AML_attachment'], $targetDir) : ['status'=>false,'path'=>'','size'=>'','error'=>'AML file not provided'];
        $AML_attachment = $AML_attachment_result['path'];
        
        $empid = $_SESSION['id'];
        $timestamp = date("Y-m-d H:i:s");
        
        $stmt = $conn->prepare("UPDATE goAML SET goAML_attachment=?, empid=?, timestamp=? WHERE id=?");
        $stmt->bind_param("sisi", $AML_attachment, $empid, $timestamp, $id);
        $stmt->execute();
        $stmt->close();
        
        if(isset($AML_attachment) && $AML_attachment !== ''){
            $action = "تم اضافة مستند جديد في goAML";
            
            include_once 'addlog.php';
        }
        
        $submit_back = filter_input(INPUT_POST, "submit_back", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(isset($submit_back) && $submit_back === 'addmore'){
            header("Location: goAMList.php?amlsaved=1&addmore=1");
            exit();
        } else{
            header("Location: goAMList.php?amlsaved=1");
            exit();
        }
    }
    header("Location: goAMList.php?error=0");
    exit();
?>