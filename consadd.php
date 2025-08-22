<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'secure_filesfunc.php';
    include_once 'errorscheck.php';
    
    if($row_permcheck['csched_aperm'] == 1){
        if(isset($_REQUEST['client_name']) && $_REQUEST['client_name'] !== ''){
            
            $flag = '0';
            $action = 'تم اضافة استشارة قانونية جديدة :<br>';
            
            $branch = filter_input(INPUT_POST, "branch", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($branch) && $branch !== ''){
                $flag = '1';
                
                $action = $action."<br>الفرع : $branch";
            }
            
            $client_name = filter_input(INPUT_POST, "client_name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($client_name) && $client_name !== ''){
                $flag = '1';
                
                $action = $action."<br>اسم الموكل : $client_name";
            }
            
            $telno = filter_input(INPUT_POST, "telno", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($telno) && $telno !== ''){
                $flag = '1';
                
                $action = $action."<br>الهاتف : $telno";
            }
            
            $client_type = filter_input(INPUT_POST, "client_type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($client_type) && $client_type !== ''){
                $flag = '1';
                
                $action = $action."<br>الفئة : $client_type";
            }
            
            $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($email) && $email !== ''){
                $flag = '1';
                
                $action = $action."<br>الايميل : $email";
            }
            
            $reference = filter_input(INPUT_POST, "reference", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($reference) && $reference !== ''){
                $flag = '1';
                
                $action = $action."<br>المرجع : $reference";
            }
            
            $details = filter_input(INPUT_POST, "details", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($details) && $details !== ''){
                $flag = '1';
                
                $action = $action."<br>تفاصيل الاجتماع : $details";
            }
            
            $result = filter_input(INPUT_POST, "result", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($result) && $result !== ''){
                $flag = '1';
                
                $action = $action."<br>نتيجة الاجتماع : $result";
            }
            
            $followup1 = filter_input(INPUT_POST, "followup1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($followup1) && $followup1 !== ''){
                $flag = '1';
                
                $action = $action."<br>المتابعة - 1 : $followup1";
            }
            
            $followup2 = filter_input(INPUT_POST, "followup2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($followup2) && $followup2 !== ''){
                $flag = '1';
                
                $action = $action."<br>المتابعة - 2 : $followup2";
            }
            
            $followup3 = filter_input(INPUT_POST, "followup3", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($followup3) && $followup3 !== ''){
                $flag = '1';
                
                $action = $action."<br>المتابعة - 3 : $followup3";
            }
            
            $targetDir = "files_images/consultations";
            
            $passport_result = is_array($_FILES['passport'] ?? null) ? secure_file_upload($_FILES['passport'], $targetDir) : ['status'=>false,'path'=>'','size'=>'','error'=>'Passport file not provided'];
            $passport = $passport_result['path'];
            
            $attachments = '';
            if (!empty($_FILES['attach_files_multi']['name'][0])) {
                $multiCount = count($_FILES['attach_files_multi']['name']);
                
                for ($j = 0; $j < $multiCount; $j++) {
                    $file = [
                        'name'     => $_FILES['attach_files_multi']['name'][$j],
                        'type'     => $_FILES['attach_files_multi']['type'][$j],
                        'tmp_name' => $_FILES['attach_files_multi']['tmp_name'][$j],
                        'error'    => $_FILES['attach_files_multi']['error'][$j],
                        'size'     => $_FILES['attach_files_multi']['size'][$j],
                    ];
                    
                    $targetDir = "files_images/consultations";
                    
                    $upload = secure_file_upload($file, $targetDir);
                    
                    if ($upload['status']) {
                        $destination = $upload['path'];
                        if(($j + 1) === $multiCount){
                            $attachments = $attachments.$destination;
                        } else{
                            $attachments = $attachments.$destination.' #,.,# ';
                        }
                    } else {
                        error_log("Upload failed for file {$file['name']}: " . $upload['error']);
                    }
                }
            }
            
            if($flag === '1'){
                include_once 'addlog.php';
            }
            
            $empid = $_SESSION['id'];
            $timestamp = date("Y-m-d");
            
            $stmt = $conn->prepare("INSERT INTO consultations (branch, client_name, telno, client_type, email, reference, details, result, followup1, followup2, followup3, passport, attachment, empid, timestamp) VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssssssis", $branch, $client_name, $telno, $client_type, $email, $reference, $details, $result, $followup1, $followup2, $followup3, $passport, $attachments, $empid, $timestamp);
            $stmt->execute();
            $stmt->close();
            
            $submit_back = filter_input(INPUT_POST, "submit_back", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($submit_back) && $submit_back === 'addmore'){
                header("Location: consultations.php?conssaved=1&addmore=1&branch=$branch");
                exit();
            } else{
                header("Location: consultations.php?conssaved=1&branch=$branch");
                exit();
            }
        } else{
            header("Location: consultations.php?cli_name=0&branch=$branch");
            exit();
        }
    }
    header("Location: consultations.php?error=0&branch=$branch");
    exit();
?>