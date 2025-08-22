<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'secure_filesfunc.php';
    function normalize_name($name) {
        $name = str_replace(' ', '', $name);
        $name = preg_replace('/[\x{064B}-\x{0652}]/u', '', $name);
        $name = str_replace(
            ['أ', 'إ', 'ى', 'آ', 'ا', 'َ', 'ً', 'ُ', 'ٌ', 'ِ', 'ٍ', 'ْ'],
            ['ا', 'ا', 'ي', 'ا', 'ا', '', '', '', '', '', '', ''],
            $name
        );
        return $name;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if($row_permcheck['clients_aperm'] == 1){
            $cid = filter_input(INPUT_POST, 'cid', FILTER_SANITIZE_NUMBER_INT);
            $action = "تم اضافة موكل جديد برقم : $cid";
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $arname = filter_input(INPUT_POST, 'arname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $engname = filter_input(INPUT_POST, 'engname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $normalized_arname = normalize_name($arname);
            $normalized_engname = normalize_name($engname);
            $stmtchk = $conn->prepare("SELECT * FROM client");
            $stmtchk->execute();
            $resultchk = $stmtchk->get_result();
            while($rowchk = $resultchk->fetch_assoc()){
                $db_arname = $rowchk['arname'];
                $db_engname = $rowchk['engname'];

                $normalizedDB_arname = normalize_name($db_arname);
                $normalizedDB_engname = normalize_name($db_engname);

                if($normalizedDB_arname === $normalized_arname || $normalizedDB_engname === $normalized_engname){
                    echo $normalizedDB_arname . ' == ' . $normalized_arname . ' || ' . $normalizedDB_engname . ' == ' . $normalized_engname;exit;
                    header("Location: $page?namerror=1");
                    exit();
                }
            }
            $stmtchk->close();
            
            $client_kind = filter_input(INPUT_POST, 'client_kind', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $client_type = filter_input(INPUT_POST, 'client_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $tel1 = filter_input(INPUT_POST, 'tel1', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $fax = filter_input(INPUT_POST, 'fax', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $tel2 = filter_input(INPUT_POST, 'tel2', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $terror = filter_input(INPUT_POST, 'terror', FILTER_SANITIZE_NUMBER_INT);
            $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $idno = filter_input(INPUT_POST, 'idno', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $perm = filter_input(INPUT_POST, 'perm', FILTER_SANITIZE_NUMBER_INT);
            
            $submit_back = filter_input(INPUT_POST, 'submit_back', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
            $targetDir = "files_images/clients/$cid";
    
            $passport_result = secure_file_upload($_FILES['passport_file'] ?? null, $targetDir, 'passport');
            $passport = $passport_result['path'];
            $passport_size = $passport_result['size'];
            
            $id_result = secure_file_upload($_FILES['id_file'] ?? null, $targetDir, 'id');
            $id_file = $id_result['path'];
            $id_file_size = $id_result['size'];
            
            $auth_result = secure_file_upload($_FILES['auth_file'] ?? null, $targetDir, 'auth');
            $auth_file = $auth_result['path'];
            $auth_size = $auth_result['size'];
            
            $attach1_result = secure_file_upload($_FILES['attach_file'] ?? null, $targetDir, 'attach1');
            $attach_file = $attach1_result['path'];
            $attach_size = $attach1_result['size'];
            
            $attach2_result = secure_file_upload($_FILES['attach_file2'] ?? null, $targetDir, 'attach2');
            $attach_file2 = $attach2_result['path'];
            $attach2_size = $attach2_result['size'];
            
            $attach3_result = secure_file_upload($_FILES['attach_file3'] ?? null, $targetDir, 'attach3');
            $attach_file3 = $attach3_result['path'];
            $attach3_size = $attach3_result['size'];
            
            $attach4_result = secure_file_upload($_FILES['attach_file4'] ?? null, $targetDir, 'attach4');
            $attach_file4 = $attach4_result['path'];
            $attach4_size = $attach4_result['size'];
            
            $attach5_result = secure_file_upload($_FILES['attach_file5'] ?? null, $targetDir, 'attach5');
            $attach_file5 = $attach5_result['path'];
            $attach5_size = $attach5_result['size'];
            
            $attach6_result = secure_file_upload($_FILES['attach_file6'] ?? null, $targetDir, 'attach6');
            $attach_file6 = $attach6_result['path'];
            $attach6_size = $attach6_result['size'];

            $upload_errors = array_filter([
                $passport_result['error'],
                $id_result['error'],
                $auth_result['error'],
                $attach1_result['error'],
                $attach2_result['error'],
                $attach3_result['error'],
                $attach4_result['error'],
                $attach5_result['error'],
                $attach6_result['error']
            ]);
            
            if (!empty($upload_errors)) {
                error_log("File upload errors for client $cid: " . implode(", ", $upload_errors));
            }

            $empid = $_SESSION['id'];
            $timestamp = date("Y-m-d H:i:s");
            $stmt = $conn->prepare("UPDATE client SET arname=?, engname=?, terror=?, client_kind=?, client_type=?, email=?, tel1=?, fax=?, tel2=?, notes=?, address=?, 
            country=?, idno=?, password=?, perm=?, passport=?, passport_size=?, id_file=?, id_file_size=?, auth=?, auth_size=?, attach1=?, attach1_size=?, attach2=?, 
            attach2_size=?, attach3=?, attach3_size=?, attach4=?, attach4_size=?, attach5=?, attach5_size=?, attach6=?, attach6_size=?, empid=?, timestamp=? WHERE id=?");
            $stmt->bind_param("ssisssssssssssissssssssssssssssssssi", $arname, $engname, $terror, $client_kind, $client_type, $email, $tel1, $fax, $tel2, $notes, 
            $address, $country, $idno, $password, $perm, $passport, $passport_size, $id_file, $id_file_size, $auth_file, $auth_size, $attach_file, $attach_size, 
            $attach_file2, $attach2_size, $attach_file3, $attach3_size, $attach_file4, $attach4_size, $attach_file5, $attach5_size, $attach_file6, $attach6_size, 
            $empid, $timestamp, $cid);
            $stmt->execute();
            $stmt->close();
            
            include_once 'addlog.php';
            include_once 'timerfunc.php';
            
            $submit_back = filter_input(INPUT_POST, 'submit_back', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            if(isset($submit_back) && $submit_back === 'addmore'){
                header("Location: $page?saved=1&addmore=1");
                exit();
            } else{
                header("Location: $page?saved=1");
                exit();
            }
        }
    }
    header("Location: $page?error=0");
    exit();
?>