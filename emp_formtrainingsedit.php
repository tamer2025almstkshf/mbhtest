<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'secure_filesfunc.php';
    
    if(isset($_REQUEST['training_type']) && $_REQUEST['training_type'] !== ''){
        if($row_permcheck['trainings_eperm'] == 1){
            $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $training_date = filter_input(INPUT_POST, "training_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $training_type = filter_input(INPUT_POST, "training_type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $stmtr = $conn->prepare("SELECT * FROM trainings WHERE id=?");
            $stmtr->bind_param("i", $id);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmtr->close();
            
            $attachment = $rowr['attachment'];
            if (isset($_FILES['training_attachment']) && $_FILES['training_attachment']['error'] == 0) {
                $file = [
                    'name'     => $_FILES['training_attachment']['name'],
                    'type'     => $_FILES['training_attachment']['type'],
                    'tmp_name' => $_FILES['training_attachment']['tmp_name'],
                    'error'    => $_FILES['training_attachment']['error'],
                    'size'     => $_FILES['training_attachment']['size']
                ];
                
                $targetDir = "files_images/emp_attachments/$user_id/trainings";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $upload = secure_file_upload($file, $targetDir);
                
                if ($upload['status']) {
                    $training_attachment = $upload['path'];
                }
            }
            
            $stmt = $conn->prepare("UPDATE trainings SET training_date=?, training_type=?, attachment=? WHERE id=?");
            $stmt->bind_param("sssi", $training_date, $training_type, $training_attachment, $id);
            $stmt->execute();
            $stmt->close();
            
            $respid = $_SESSION['id'];
            $empid = $user_id;
            
            $stmtn = $conn->prepare("SELECT * FROM trainings WHERE id=?");
            $stmtn->bind_param("i", $id);
            $stmtn->execute();
            $resultn = $stmtn->get_result();
            $rown = $resultn->fetch_assoc();
            $stmtn->close();
            
            $stmtnr = $conn->prepare("SELECT * FROM user WHERE id=?");
            $stmtnr->bind_param("i", $respid);
            $stmtnr->execute();
            $resultnr = $stmtnr->get_result();
            $rownr = $resultnr->fetch_assoc();
            $stmtnr->close();
            $respname = $rownr['name'];
            
            $target_id = $rown['id'];
            $target = "trainings /-/ $target_id";
            $notification = "تم تعديل بيانات احد دوراتك التدريبية من قبل $respname";
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
    header("Location: mbhEmps.php?empid=$user_id&empsection=rating-management&rating-section=trainings");
    exit();
?>