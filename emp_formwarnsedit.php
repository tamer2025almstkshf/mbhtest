<?php 
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'secure_filesfunc.php';
    
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    
    if(isset($_REQUEST['warning_date']) && $_REQUEST['warning_date'] !== ''){
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        
        $flag = '0';
        $action = "تم تعديل احد انذارات الموظف رقم : $userid<br>";
        
        $stmtr = $conn->prepare("SELECT * FROM warnings WHERE id=?");
        $stmtr->bind_param("i", $id);
        $stmtr->execute();
        $resultr = $stmtr->get_result();
        $rowr = $resultr->fetch_assoc();
        $stmtr->close();
        
        $olddate = $rowr['warning_date'];
        $oldtype = $rowr['warning_type'];
        $oldreason = $rowr['warning_reason'];
        $oldattachment = $rowr['attachments'];
        
        $warning_date = filter_input(INPUT_POST, "warning_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(isset($warning_date) && $warning_date !== $olddate){
            $flag = '1';
            
            $action = $action."<br>تم تغيير تاريخ الانذار : من $olddate الى $warning_date";
        }
        
        $warning_type = filter_input(INPUT_POST, "warning_type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(isset($warning_type) && $warning_type !== $oldtype){
            $flag = '1';
            
            $action = $action."<br>تم تغيير نوع الانذار : من $oldtype الى $warning_type";
        }
        
        $warning_reason = filter_input(INPUT_POST, "warning_reason", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(isset($warning_reason) && $warning_reason !== $oldreason){
            $flag = '1';
            
            $action = $action."<br>تم تغيير سبب الانذار : من $oldreason الى $warning_reason";
        }
        
        $destination = $oldattachment;
        if (!empty($_FILES['warning_attachments']['name'])) {
            $file = [
                'name'     => $_FILES['warning_attachments']['name'],
                'type'     => $_FILES['warning_attachments']['type'],
                'tmp_name' => $_FILES['warning_attachments']['tmp_name'],
                'error'    => $_FILES['warning_attachments']['error'],
                'size'     => $_FILES['warning_attachments']['size']
            ];
            
            $targetDir = "files_images/employees/$user_id/warnings";
            
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $upload = secure_file_upload($file, $targetDir);
            
            if ($upload['status']) {
                $destination = $upload['path'];
                $fileExtension = strtolower(pathinfo($destination, PATHINFO_EXTENSION));
                $fileSizeReadable = $upload['size'];
            }
        }
        
        $stmt = $conn->prepare("UPDATE warnings SET warning_type=?, warning_reason=?, warning_date=?, attachments=? WHERE id=?");
        $stmt->bind_param("ssssi", $warning_type, $warning_reason, $warning_date, $destination, $id);
        $stmt->execute();
        $stmt->close();
        
        if($flag === '1'){
            include_once 'addlog.php';
            
            $respid = $_SESSION['id'];
            $empid = $user_id;
            
            $stmtnr = $conn->prepare("SELECT * FROM user WHERE id=?");
            $stmtnr->bind_param("i", $respid);
            $stmtnr->execute();
            $resultnr = $stmtnr->get_result();
            $rownr = $resultnr->fetch_assoc();
            $stmtnr->close();
            $respname = $rownr['name'];
            
            $target_id = $id;
            $target = "warnings /-/ $target_id";
            $notification = "تم تعديل بيانات الإنذار من قبل $respname";
            $notification_date = date("Y-m-d");
            $status = 0;
            $timestamp = date("Y-m-d H:i:s");
            
            if($empid != 0 && $empid !== ''){
                $stmt = $conn->prepare("INSERT INTO notifications (resp_id, empid, target, target_id, notification, notification_date, status, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iisissis", $respid, $empid, $target, $target_id, $notification, $notification_date, $status, $timestamp);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
    header("Location: mbhEmps.php?empid=$user_id&empsection=rating-management&rating-section=warnings");
    exit();
?>