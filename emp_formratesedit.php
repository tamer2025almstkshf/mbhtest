<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'secure_filesfunc.php';
    
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    
    if($user_id !== $myid && $row_permcheck['rate_eperm'] == 1){
        if(isset($_REQUEST['rating_date']) && $_REQUEST['rating_date'] !== ''){
            $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $flag = '0';
            $action = "تم تعديل احد تقييمات الموظف رقم : $user_id<br>";
            
            $stmtr = $conn->prepare("SELECT * FROM ratings WHERE id=?");
            $stmtr->bind_param("i", $id);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmtr->close();
            
            $oldrating_date = $rowr['rating_date'];
            $oldrating_type = $rowr['rating_type'];
            $oldattachment = $rowr['attachment'];
            
            $rating_date = filter_input(INPUT_POST, "rating_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($rating_date) && $rating_date !== $oldrating_date){
                $flag = '1';
                
                $action = $action."<br>تم تغيير تاريخ التقييم : من $oldrating_date الى $rating_date";
            }
            
            $rating_type = filter_input(INPUT_POST, "rating_type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($rating_type) && $rating_type !== $oldrating_type){
                $flag = '1';
                
                $action = $action."<br>تم تغيير نوع التقييم : من $oldrating_type الى $rating_type";
            }
            
            $targetDir = "files_images/employees/$user_id/ratings";
            
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $attachment = $oldattachment;
            
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
                $upload = secure_file_upload($_FILES['attachment'], $targetDir);
            
                if ($upload['status']) {
                    $attachment = $upload['path'];
                    $flag = '1';
                    $action .= "<br>تم تغيير مرفق التقييم";
                } else {
                    echo "فشل في رفع المرفق: " . htmlspecialchars($upload['error']) . "<br>";
                }
            }
            
            $stmt = $conn->prepare("UPDATE ratings SET rating_date=?, rating_type=?, attachment=? WHERE id=?");
            $stmt->bind_param("sssi", $rating_date, $rating_type, $attachment, $id);
            $stmt->execute();
            $stmt->close();
            
            if($flag === '1'){
                include_once 'addlog.php';
                
                $respid = $_SESSION['id'];
                $empid = $user_id;
                
                $stmtn = $conn->prepare("SELECT * FROM ratings WHERE id=?");
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
                $target = "ratings /-/ $target_id";
                $notification = "تم تعديل احد تقييماتك من قبل $respname";
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
    }
    header("Location: mbhEmps.php?empid=$user_id&empsection=rating-management&rating-section=ratings");
    exit();
?>