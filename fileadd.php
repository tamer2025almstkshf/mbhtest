<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'secure_filesfunc.php';
    
    $page = filter_input(INPUT_POST, "page", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $queryString = filter_input(INPUT_POST, "queryString", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (strpos($queryString, 'error') !== false) {
        parse_str($queryString, $queryParams);
        unset($queryParams['error']);
        $queryString = http_build_query($queryParams);
    }
    if (strpos($queryString, 'savedfile') !== false) {
        parse_str($queryString, $queryParams);
        unset($queryParams['savedfile']);
        $queryString = http_build_query($queryParams);
    }
    if (strpos($queryString, 'editedfile') !== false) {
        parse_str($queryString, $queryParams);
        unset($queryParams['editedfile']);
        $queryString = http_build_query($queryParams);
    }
    
    if(isset($_REQUEST['type']) && isset($_REQUEST['saveinfo'])){
        if($row_permcheck['cfiles_aperm'] == 1){
            $notempids = [];
            $type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($type === ''){
                header('Location: addcase.php?error=0');
                exit();
            }
            
            $place = filter_input(INPUT_POST, "place", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($place === ''){
                header('Location: addcase.php?error=0');
                exit();
            }
            
            $class = filter_input(INPUT_POST, "class", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($class === ''){
                header('Location: addcase.php?error=0');
                exit();
            }
            
            $status = filter_input(INPUT_POST, "file_stat", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $file_lawyer = filter_input(INPUT_POST, 'file_lawyer', FILTER_SANITIZE_NUMBER_INT);
            $notempids[] = $file_lawyer;
            $file_lawyer2 = filter_input(INPUT_POST, 'file_lawyer2', FILTER_SANITIZE_NUMBER_INT);
            $notempids[] = $file_lawyer2;
            $file_secritary = filter_input(INPUT_POST, 'file_secritary', FILTER_SANITIZE_NUMBER_INT);
            $notempids[] = $file_secritary;
            $file_secritary2 = filter_input(INPUT_POST, 'file_secritary2', FILTER_SANITIZE_NUMBER_INT);
            $notempids[] = $file_secritary2;
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_NUMBER_INT);
            $note = filter_input(INPUT_POST, "note", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $client = filter_input(INPUT_POST, 'client', FILTER_SANITIZE_NUMBER_INT);
            if($client === ''){
                header('Location: addcase.php?error=3');
                exit();
            }
            
            $client2 = filter_input(INPUT_POST, 'client2', FILTER_SANITIZE_NUMBER_INT);
            $client3 = filter_input(INPUT_POST, 'client3', FILTER_SANITIZE_NUMBER_INT);
            $client4 = filter_input(INPUT_POST, 'client4', FILTER_SANITIZE_NUMBER_INT);
            $client5 = filter_input(INPUT_POST, 'client5', FILTER_SANITIZE_NUMBER_INT);
            
            $cchar = filter_input(INPUT_POST, "cchar", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $ccharedit = filter_input(INPUT_POST, "ccharedit", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $cchar2 = filter_input(INPUT_POST, "cchar2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $cchar3 = filter_input(INPUT_POST, "cchar3", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $cchar4 = filter_input(INPUT_POST, "cchar4", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $cchar5 = filter_input(INPUT_POST, "cchar5", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
            $opponent = filter_input(INPUT_POST, 'opponent', FILTER_SANITIZE_NUMBER_INT);
            $opponent2 = filter_input(INPUT_POST, 'opponent2', FILTER_SANITIZE_NUMBER_INT);
            $opponent3 = filter_input(INPUT_POST, 'opponent3', FILTER_SANITIZE_NUMBER_INT);
            $opponent4 = filter_input(INPUT_POST, 'opponent4', FILTER_SANITIZE_NUMBER_INT);
            $opponent5 = filter_input(INPUT_POST, 'opponent5', FILTER_SANITIZE_NUMBER_INT);
            
            $ochar = filter_input(INPUT_POST, "ochar", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $ocharedit = filter_input(INPUT_POST, "ocharedit", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $ochar2 = filter_input(INPUT_POST, "ochar2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $ochar3 = filter_input(INPUT_POST, "ochar3", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $ochar4 = filter_input(INPUT_POST, "ochar4", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $ochar5 = filter_input(INPUT_POST, "ochar5", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $pstation = filter_input(INPUT_POST, "pstation", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $ctype = filter_input(INPUT_POST, "ctype", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $important = filter_input(INPUT_POST, 'important', FILTER_SANITIZE_NUMBER_INT);
            $court = filter_input(INPUT_POST, "court", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $prosecution = filter_input(INPUT_POST, "prosecution", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $flegal_researcher = filter_input(INPUT_POST, 'flegal_researcher', FILTER_SANITIZE_NUMBER_INT);
            $notempids[] = $flegal_researcher;
            $flegal_researcher2 = filter_input(INPUT_POST, 'flegal_researcher2', FILTER_SANITIZE_NUMBER_INT);
            $notempids[] = $flegal_researcher2;
            $flegal_advisor = filter_input(INPUT_POST, 'flegal_advisor', FILTER_SANITIZE_NUMBER_INT);
            $notempids[] = $flegal_advisor;
            $flegal_advisor2 = filter_input(INPUT_POST, 'flegal_advisor2', FILTER_SANITIZE_NUMBER_INT);
            $notempids[] = $flegal_advisor2;
            $degree = filter_input(INPUT_POST, "degree", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $cnum = filter_input(INPUT_POST, "cnum", FILTER_SANITIZE_NUMBER_INT);
            $year = filter_input(INPUT_POST, "year", FILTER_SANITIZE_NUMBER_INT);
            $fulldate = filter_input(INPUT_POST, "fulldate", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $resp_name = filter_input(INPUT_POST, "resp_name", FILTER_SANITIZE_NUMBER_INT);
            
            function formatSize($bytes) {
                if ($bytes < 1024) {
                    return $bytes . ' B';
                } elseif ($bytes < 1048576) {
                    return round($bytes / 1024, 2) . ' KB';
                } elseif ($bytes < 1073741824) {
                    return round($bytes / (1024 * 1024), 2) . ' MB';
                } else {
                    return round($bytes / (1024 * 1024 * 1024), 2) . ' GB';
                }
            }
            
            $empid = $_SESSION['id'];
            $timestamp = date("Y-m-d H:i:s");
            
            if (!empty($_FILES['attach_files_multi']['name'][0])) {
                $multiCount = count($_FILES['attach_files_multi']['name']);
                
                for ($j = 0; $j < $multiCount; $j++) {
                    // Build single-file array for this index
                    $file = [
                        'name'     => $_FILES['attach_files_multi']['name'][$j],
                        'type'     => $_FILES['attach_files_multi']['type'][$j],
                        'tmp_name' => $_FILES['attach_files_multi']['tmp_name'][$j],
                        'error'    => $_FILES['attach_files_multi']['error'][$j],
                        'size'     => $_FILES['attach_files_multi']['size'][$j],
                    ];
                    
                    // Set up target directory
                    $targetDir = "files_images/file_upload/$fid";
                    
                    // Use the secure upload function
                    $upload = secure_file_upload($file, $targetDir);
                    
                    if ($upload['status']) {
                        $destination = $upload['path'];
                        $fileExtension = strtolower(pathinfo($destination, PATHINFO_EXTENSION));
                        $fileSizeReadable = $upload['size'];
                        
                        $stmt = $conn->prepare("INSERT INTO files_attachments (fid, attachment, attachment_type, attachment_size, done_by, timestamp) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("isssis", $id, $destination, $fileExtension, $fileSizeReadable, $empid, $timestamp);
                        $stmt->execute();
                        $stmt->close();
                    } else {
                        // Log upload errors
                        error_log("Upload failed for file {$file['name']}: " . $upload['error']);
                    }
                }
            }
            
            $important = filter_input(INPUT_POST, 'important', FILTER_SANITIZE_NUMBER_INT);
            $oldimp = isset($rowr['important']) ? safe_output($rowr['important']) : 0;
            if($important != $oldimp){
                $flag2 = '1';
                if($important == 1){
                    $impname = "دعوى مهمة";
                } else{
                    $impname = "عادي";
                }
                
                $action2 = $action2."<br>تم تسجيل الملف كدعوى مهمة : $impname";
            }
            
            $timestamp = date('Y-m-d H:i:s');
            $timestampsession = date("Y-m-d");
            
            $stmt = $conn->prepare("UPDATE file SET file_type=?, frelated_place=?, file_class=?, file_status=?, file_subject=?, file_notes=?, file_client=?, file_client2=?,
            file_client3=?, file_client4=?, file_client5=?, fclient_characteristic=?, fclient_characteristic2=?, fclient_characteristic3=?, fclient_characteristic4=?, 
            fclient_characteristic5=?, file_opponent=?, file_opponent2=?, file_opponent3=?, file_opponent4=?, file_opponent5=?, fopponent_characteristic=?, 
            fopponent_characteristic2=?, fopponent_characteristic3=?, fopponent_characteristic4=?, fopponent_characteristic5=?, fpolice_station=?, fcase_type=?, file_court=?, 
            file_prosecution=?, flegal_researcher=?, flegal_researcher2=?, flegal_advisor=?, flegal_advisor2=?, file_lawyer=?, file_lawyer2=?, file_secritary=?,
            file_secritary2=?, important=?, done_by=?, file_timestamp=? WHERE file_id=?");
            $stmt->bind_param("ssssssiiiiisssssiiiiisssssssssiiiiiiiiiisi", $type, $place, $class, $status, $subject, $note, $client, $client2, $client3, $client4,
            $client5, $cchar, $cchar2, $cchar3, $cchar4, $cchar5, $opponent, $opponent2, $opponent3, $opponent4, $opponent5, $ochar, $ochar2, $ochar3, $ochar4, $ochar5,
            $pstation, $ctype, $court, $prosecution, $flegal_researcher, $flegal_researcher2, $flegal_advisor, $flegal_advisor2, $file_lawyer, $file_lawyer2, $file_secritary, 
            $file_secritary2, $importnat, $resp_name, $timestamp, $id);
            $stmt->execute();
            $stmt->close();
            
            if(isset($degree) && $degree !== ''){
                $flag = '0';
                $action = "تم اضافة قضية جديدة :<br>رقم الملف : $id<br>";
                
                if(isset($degree) && $degree !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>$degree : درجة التقاضي";
                }
                
                if(isset($cnum) && $cnum !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>$cnum : رقم القضية";
                }
                
                if(isset($year) && $year !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>$year : السنة";
                }
                
                if(isset($ccharedit) && $ccharedit !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>$ccharedit : صفة الموكل";
                }
                
                if(isset($ocharedit) && $ocharedit !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>$ocharedit : صفة الخصم";
                }
                
                $stmtdeg = $conn->prepare("INSERT INTO file_degrees (fid, degree, case_num, file_year, client_characteristic, opponent_characteristic, timestamp)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmtdeg->bind_param("isiisss", $id, $degree, $cnum, $year, $ccharedit, $ocharedit, $timestampsession);
                $stmtdeg->execute();
                $stmtdeg->close();
                if(isset($flag) && $flag === '1'){
                    include_once 'addlog.php';
                }
            }
            
            $action = "تمت اضافة ملف جديد برقم $id";
            include_once 'addlog.php';
            include_once 'timerfunc.php';
            
            $respid = $_SESSION['id'];
            $target = "file /-/ $id";
            $target_id = $id;
            $notification = "تم تعيينك كأحد المسؤولين عن الملف الجديد برقم $id";
            $notification_date = date("Y-m-d");
            $status = 0;
            $timestamp = date("Y-m-d H:i:s");
            if (!empty($notempids) && is_array($notempids)) {
                foreach($notempids as $empid){
                    if($empid !== '' && $empid != 0){
                        $stmtnot = $conn->prepare("INSERT INTO notifications (resp_id, empid, target, target_id, notification, notification_date, status, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmtnot->bind_param("iisissis", $respid, $empid, $target, $target_id, $notification, $notification_date, $status, $timestamp);
                        $stmtnot->execute();
                        $stmtnot->close();
                    }
                }
            }
            
            header("Location: addcase.php?savedfile=1");
            exit();
        }
        header("Location: addcase.php");
        exit();
    } else{
        if($queryString !== ''){
            header("Location: $page?$queryString&error=20");
        } else{
            header("Location: $page?error=20");
        }
        exit();
    }
?>