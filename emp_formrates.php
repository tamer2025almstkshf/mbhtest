<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'secure_filesfunc.php';
    
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    
    if($user_id !== $myid && $row_permcheck['rate_aperm'] == 1){
        if(isset($_REQUEST['rating_date']) && $_REQUEST['rating_date'] !== ''){
            $flag = '0';
            $action = "تمت اضافة تقييم جديد للموظف رقم : $user_id<br>";
            
            $rating_date = filter_input(INPUT_POST, "rating_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($rating_date) && $rating_date !== ''){
                $flag = '1';
                
                $action = $action."<br>تاريخ التقييم : $rating_date";
            }
            
            $rating_type = filter_input(INPUT_POST, "rating_type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($rating_type) && $rating_type !== ''){
                $flag = '1';
                
                $action = $action."<br>نوع التقييم : $rating_type";
            }
            
            $targetDir = "files_images/employees/$user_id/ratings";
            
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $attachment = '';
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
                $upload = secure_file_upload($_FILES['attachment'], $targetDir);
                
                if ($upload['status']) {
                    $attachment = $upload['path'];
                    $flag = '1';
                    $action .= "<br>تم اضافة مرفق للتقييم";
                } else {
                    echo "فشل في رفع المرفق: " . htmlspecialchars($upload['error']) . "<br>";
                }
            }
            
            $instanceID = "instance109692";
            $apiToken = "gc26sv6jdy1yeib9";
            
            $stmtus = $conn->prepare("SELECT * FROM user WHERE id=?");
            $stmtus->bind_param("i", $user_id);
            $stmtus->execute();
            $resultus = $stmtus->get_result();
            $rowus = $resultus->fetch_assoc();
            $stmtus->close();
            $emp_n = $rowus['name'];
            
            include_once 'AES256.php';
            $encrypted_telno = $rowus['tel1'];
            $decrypted_telno = openssl_decrypt($encrypted_telno, $cipher, $key, $options, $iv);
            $recipient = $decrypted_telno;
            
            $message = "*`مكتب محمد بني هاشم للمحاماة و الاستشارات القانونية`*\n\n*تمت اضافة تقييم جديد للموظف : $emp_n*\nتاريخ التقييم : $rating_date\nنوع التقييم : $rating_type";
            $url = "https://api.ultramsg.com/$instanceID/messages/chat";
            
            $data = [
                'token' => $apiToken,
                'to' => $recipient,
                'body' => $message,
            ];
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $apiToken",
            ]);
            $response = curl_exec($ch);
            
            if ($response === false) {
                die('Error: ' . curl_error($ch));
            }
            curl_close($ch);
            
            $stmt = $conn->prepare("INSERT INTO ratings (emp_id, rating_date, rating_type, attachment) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $user_id, $rating_date, $rating_type, $attachment);
            $stmt->execute();
            $stmt->close();
            
            if($flag === '1'){
                include_once 'addlog.php';
                
                $respid = $_SESSION['id'];
                $empid = $user_id;
                
                $stmtn = $conn->prepare("SELECT * FROM ratings WHERE emp_id=? ORDER BY id DESC");
                $stmtn->bind_param("i", $user_id);
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
                $notification = "تم تقييمك بتقييم جديد من قبل $respname";
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