<?php 
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'secure_filesfunc.php';
    
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    
    if($_GET['id'] !== $myid && $row_permcheck['warnings_aperm'] == 1){
        if(isset($_REQUEST['warning_date']) && $_REQUEST['warning_date'] !== ''){
            
            $flag = '0';
            $action = "تمت اضافة انذار جديد للموظف رقم : $user_id<br>";
            
            $warning_date = filter_input(INPUT_POST, "warning_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($warning_date) && $warning_date !== ''){
                $flag = '1';
                
                $action = $action."<br>تاريخ الانذار : $warning_date";
            }
            
            $warning_type = filter_input(INPUT_POST, "warning_type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($warning_type) && $warning_type !== ''){
                $flag = '1';
                
                $action = $action."<br>نوع الانذار : $warning_type";
            }
            
            $warning_reason = filter_input(INPUT_POST, "warning_reason", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($warning_reason) && $warning_reason !== ''){
                $flag = '1';
                
                $action = $action."<br>سبب الانذار : $warning_reason";
            }
            
            $targetDir = "files_images/employees/$user_id/warnings";
            
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $destination = '';
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
            
            $message = "*`مكتب محمد بني هاشم للمحاماة و الاستشارات القانونية`*\n\n*تم تحذير الموظف $emp_n بانذار $warning_type*\nتاريخ الانذار : $warning_date\nسبب الانذار : $warning_reason";
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
            
            $stmt = $conn->prepare("INSERT INTO warnings (emp_id, warning_type, warning_reason, warning_date, attachments) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $user_id, $warning_type, $warning_reason, $warning_date, $destination);
            $stmt->execute();
            $stmt->close();
            
            if($flag === '1'){
                include_once 'addlog.php';
                
                $respid = $_SESSION['id'];
                $empid = $user_id;
                
                $stmtn = $conn->prepare("SELECT * FROM warnings WHERE emp_id=? ORDER BY id DESC");
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
                $target = "warnings /-/ $target_id";
                $notification = "تم تنبيهك بإنذار $warning_type من قبل $respname";
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
    header("Location: mbhEmps.php?empid=$user_id&empsection=rating-management&rating-section=trainings");
    exit();
?>