<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'secure_filesfunc.php';
    include_once 'errorscheck.php';
    
    if(isset($_REQUEST['name']) && $_REQUEST['name'] !== '' && isset($_REQUEST['username']) && $_REQUEST['username'] !== ''
    && isset($_REQUEST['password']) && $_REQUEST['password'] !== '' && isset($_REQUEST['tel1']) && 
    $_REQUEST['tel1'] !== '' && isset($_REQUEST['email']) && $_REQUEST['email'] !== ''&& isset($_REQUEST['passport_no'])
    && $_REQUEST['passport_no'] !== '' && isset($_REQUEST['job_title']) && $_REQUEST['job_title'] !== ''){
        if($row_permcheck['emp_perms_add'] == 1){
            
            $empty = '';
            $stmtcheck = $conn->prepare("SELECT * FROM user WHERE name=? AND username=? AND password=? AND email=? AND tel1=? AND work_place=?");
            $stmtcheck->bind_param("ssssss", $empty, $empty, $empty, $empty, $empty, $empty);
            $stmtcheck->execute();
            $resultcheck = $stmtcheck->get_result();
            $stmtcheck->close();
            
            if($resultcheck->num_rows === 0){
                $stmt_C = $conn->prepare("INSERT INTO user (name, username, password, tel1, email, work_place) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt_C->bind_param("ssssss", $empty, $empty, $empty, $empty, $empty, $empty);
                $stmt_C->execute();
                $result_C = $stmt_C->get_result();
                $stmt_C->close();
            }
            
            $user_n = filter_input(INPUT_POST, "username", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $stmt_dup = $conn->prepare("SELECT * FROM user WHERE username = ?");
            $stmt_dup->bind_param("s", $user_n);
            $stmt_dup->execute();
            $result_dup = $stmt_dup->get_result();
            if($result_dup->num_rows > 0){
                header("Location: mbhEmps.php?empadd=1&namerror=1");
                exit();
            }
            
            $stmtid = $conn->prepare("SELECT * FROM user WHERE name=? AND username=? AND email=? AND tel1=? AND work_place=?");
            $stmtid->bind_param("sssss", $empty, $empty, $empty, $empty, $empty);
            $stmtid->execute();
            $resultid = $stmtid->get_result();
            $rowid = $resultid->fetch_assoc();
            $stmtid->close();
            
            $id = $rowid['id'];
            
            $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $flag = '0';
            $action = "تم انشاء حساب موظف جديد باسم : $name<br>";
            
            $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($username) && $username !== ''){
                $flag = '1';
                
                $action = $action."<br>اسم المستخدم : $username";
            }
            
            $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($password) && $password !== ''){
                $flag = '1';
                
                $action = $action."<br>كلمة المرور : $password";
            }
            
            $nationality = filter_input(INPUT_POST, "nationality", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $tel1 = filter_input(INPUT_POST, "tel1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $tel2 = filter_input(INPUT_POST, "tel2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $address = filter_input(INPUT_POST, "address", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $social = filter_input(INPUT_POST, "social", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $sex = filter_input(INPUT_POST, "sex", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $passport_no = filter_input(INPUT_POST, "passport_no", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
            include_once 'AES256.php';
            $encrypted_password = openssl_encrypt($password, $cipher, $key, $options, $iv);
            $encrypted_tel1 = openssl_encrypt($tel1, $cipher, $key, $options, $iv);
            $encrypted_tel2 = openssl_encrypt($tel2, $cipher, $key, $options, $iv);
            $encrypted_email = openssl_encrypt($email, $cipher, $key, $options, $iv);
            $encrypted_address = openssl_encrypt($address, $cipher, $key, $options, $iv);
            $encrypted_passport_no = openssl_encrypt($passport_no, $cipher, $key, $options, $iv);
            
            $residence_no = filter_input(INPUT_POST, "residence_no", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $card_no = filter_input(INPUT_POST, "card_no", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $passport_exp = filter_input(INPUT_POST, "passport_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $residence_date = filter_input(INPUT_POST, "residence_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $app_date = filter_input(INPUT_POST, "app_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $residence_exp = filter_input(INPUT_POST, "residence_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $contract_exp = filter_input(INPUT_POST, "contract_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $idno = filter_input(INPUT_POST, "idno", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $cardno_exp = filter_input(INPUT_POST, "cardno_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $id_exp = filter_input(INPUT_POST, "id_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $sigorta_exp = filter_input(INPUT_POST, "sigorta_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $dxblaw_exp = filter_input(INPUT_POST, "dxblaw_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $shjlaw_exp = filter_input(INPUT_POST, "shjlaw_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $ajmlaw_exp = filter_input(INPUT_POST, "ajmlaw_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $abdlaw_exp = filter_input(INPUT_POST, "abdlaw_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $dob = filter_input(INPUT_POST, "dob", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $app_type = filter_input(INPUT_POST, "app_type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $responsible = filter_input(INPUT_POST, "responsible", FILTER_SANITIZE_NUMBER_INT);
            $job_title = filter_input(INPUT_POST, "job_title", FILTER_SANITIZE_NUMBER_INT);
            $section = filter_input(INPUT_POST, "section", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $work_place = filter_input(INPUT_POST, "work_place", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $signin_perm = 1;
            
            $emergency_name1 = filter_input(INPUT_POST, "emergency_name1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $emergency_relate1 = filter_input(INPUT_POST, "emergency_relate1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $emergency_tel1 = filter_input(INPUT_POST, "emergency_tel1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $emergency_name2 = filter_input(INPUT_POST, "emergency_name2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $emergency_relate2 = filter_input(INPUT_POST, "emergency_relate2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $emergency_tel2 = filter_input(INPUT_POST, "emergency_tel2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $bank_name = filter_input(INPUT_POST, "bank_name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $iban = filter_input(INPUT_POST, "iban", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $acc_no = filter_input(INPUT_POST, "acc_no", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pay_way = filter_input(INPUT_POST, "pay_way", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $basic_salary = filter_input(INPUT_POST, "basic_salary", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $travel_tickets = filter_input(INPUT_POST, "travel_tickets", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $oil_cost = filter_input(INPUT_POST, "oil_cost", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $housing_cost = filter_input(INPUT_POST, "housing_cost", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $living_cost = filter_input(INPUT_POST, "living_cost", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
            if(isset($_REQUEST['cfiles_rperm'])){
                $cfiles_rperm = filter_input(INPUT_POST, "cfiles_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $cfiles_rperm = 0;
            }
    
            if(isset($_REQUEST['cfiles_aperm'])){
                $cfiles_aperm = filter_input(INPUT_POST, "cfiles_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $cfiles_aperm = 0;
            }
    
            if(isset($_REQUEST['cfiles_eperm'])){
                $cfiles_eperm = filter_input(INPUT_POST, "cfiles_eperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $cfiles_eperm = 0;
            }
    
            if(isset($_REQUEST['cfiles_dperm'])){
                $cfiles_dperm = filter_input(INPUT_POST, "cfiles_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $cfiles_dperm = 0;
            }
    
            if(isset($_REQUEST['cfiles_archive_perm'])){
                $cfiles_archive_perm = filter_input(INPUT_POST, "cfiles_archive_perm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $cfiles_archive_perm = 0;
            }
    
            if(isset($_REQUEST['secretf_aperm'])){
                $secretf_aperm = filter_input(INPUT_POST, "secretf_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $secretf_aperm = 0;
            }
    
            if(isset($_REQUEST['session_rperm'])){
                $session_rperm = filter_input(INPUT_POST, "session_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $session_rperm = 0;
            }
    
            if(isset($_REQUEST['session_aperm'])){
                $session_aperm = filter_input(INPUT_POST, "session_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $session_aperm = 0;
            }
    
            if(isset($_REQUEST['session_eperm'])){
                $session_eperm = filter_input(INPUT_POST, "session_eperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $session_eperm = 0;
            }
    
            if(isset($_REQUEST['session_dperm'])){
                $session_dperm = filter_input(INPUT_POST, "session_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $session_dperm = 0;
            }
    
            if(isset($_REQUEST['sessionrole_rperm'])){
                $sessionrole_rperm = filter_input(INPUT_POST, "sessionrole_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $sessionrole_rperm = 0;
            }
    
            if(isset($_REQUEST['levels_eperm'])){
                $levels_eperm = filter_input(INPUT_POST, "levels_eperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $levels_eperm = 0;
            }
    
            if(isset($_REQUEST['degree_rperm'])){
                $degree_rperm = filter_input(INPUT_POST, "degree_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $degree_rperm = 0;
            }
    
            if(isset($_REQUEST['degree_aperm'])){
                $degree_aperm = filter_input(INPUT_POST, "degree_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $degree_aperm = 0;
            }
    
            if(isset($_REQUEST['degree_eperm'])){
                $degree_eperm = filter_input(INPUT_POST, "degree_eperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $degree_eperm = 0;
            }
    
            if(isset($_REQUEST['degree_dperm'])){
                $degree_dperm = filter_input(INPUT_POST, "degree_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $degree_dperm = 0;
            }
    
            if(isset($_REQUEST['admjobs_rperm'])){
                $admjobs_rperm = filter_input(INPUT_POST, "admjobs_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $admjobs_rperm = 0;
            }
    
            if(isset($_REQUEST['admjobs_aperm'])){
                $admjobs_aperm = filter_input(INPUT_POST, "admjobs_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $admjobs_aperm = 0;
            }
    
            if(isset($_REQUEST['admjobs_eperm'])){
                $admjobs_eperm = filter_input(INPUT_POST, "admjobs_eperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $admjobs_eperm = 0;
            }
    
            if(isset($_REQUEST['admjobs_dperm'])){
                $admjobs_dperm = filter_input(INPUT_POST, "admjobs_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $admjobs_dperm = 0;
            }
    
            if(isset($_REQUEST['admjobs_pperm'])){
                $admjobs_pperm = filter_input(INPUT_POST, "admjobs_pperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $admjobs_pperm = 0;
            }
    
            if(isset($_REQUEST['admprivjobs_rperm'])){
                $admprivjobs_rperm = filter_input(INPUT_POST, "admprivjobs_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $admprivjobs_rperm = 0;
            }
    
            if(isset($_REQUEST['attachments_dperm'])){
                $attachments_dperm = filter_input(INPUT_POST, "attachments_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $attachments_dperm = 0;
            }
    
            if(isset($_REQUEST['judicialwarn_rperm'])){
                $judicialwarn_rperm = filter_input(INPUT_POST, "judicialwarn_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $judicialwarn_rperm = 0;
            }
    
            if(isset($_REQUEST['judicialwarn_aperm'])){
                $judicialwarn_aperm = filter_input(INPUT_POST, "judicialwarn_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $judicialwarn_aperm = 0;
            }
    
            if(isset($_REQUEST['judicialwarn_eperm'])){
                $judicialwarn_eperm = filter_input(INPUT_POST, "judicialwarn_eperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $judicialwarn_eperm = 0;
            }
    
            if(isset($_REQUEST['judicialwarn_dperm'])){
                $judicialwarn_dperm = filter_input(INPUT_POST, "judicialwarn_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $judicialwarn_dperm = 0;
            }
    
            if(isset($_REQUEST['petition_rperm'])){
                $petition_rperm = filter_input(INPUT_POST, "petition_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $petition_rperm = 0;
            }
    
            if(isset($_REQUEST['petition_aperm'])){
                $petition_aperm = filter_input(INPUT_POST, "petition_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $petition_aperm = 0;
            }
    
            if(isset($_REQUEST['petition_eperm'])){
                $petition_eperm = filter_input(INPUT_POST, "petition_eperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $petition_eperm = 0;
            }
    
            if(isset($_REQUEST['petition_dperm'])){
                $petition_dperm = filter_input(INPUT_POST, "petition_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $petition_dperm = 0;
            }
    
            if(isset($_REQUEST['note_rperm'])){
                $note_rperm = filter_input(INPUT_POST, "note_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $note_rperm = 0;
            }
            
            if(isset($_REQUEST['note_aperm'])){
                $note_aperm = filter_input(INPUT_POST, "note_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $note_aperm = 0;
            }
    
            if(isset($_REQUEST['note_eperm'])){
                $note_eperm = filter_input(INPUT_POST, "note_eperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $note_eperm = 0;
            }
    
            if(isset($_REQUEST['note_dperm'])){
                $note_dperm = filter_input(INPUT_POST, "note_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $note_dperm = 0;
            }
    
            if(isset($_REQUEST['doc_faperm'])){
                $doc_faperm = filter_input(INPUT_POST, "doc_faperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $doc_faperm = 0;
            }
    
            if(isset($_REQUEST['doc_laperm'])){
                $doc_laperm = filter_input(INPUT_POST, "doc_laperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $doc_laperm = 0;
            }
    
            if(isset($_REQUEST['clients_rperm'])){
                $clients_rperm = filter_input(INPUT_POST, "clients_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $clients_rperm = 0;
            }
    
            if(isset($_REQUEST['clients_aperm'])){
                $clients_aperm = filter_input(INPUT_POST, "clients_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $clients_aperm = 0;
            }
    
            if(isset($_REQUEST['clients_eperm'])){
                $clients_eperm = filter_input(INPUT_POST, "clients_eperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $clients_eperm = 0;
            }
    
            if(isset($_REQUEST['clients_dperm'])){
                $clients_dperm = filter_input(INPUT_POST, "clients_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $clients_dperm = 0;
            }
    
            if(isset($_REQUEST['csched_rperm'])){
                $csched_rperm = filter_input(INPUT_POST, "csched_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $csched_rperm = 0;
            }
    
            if(isset($_REQUEST['csched_aperm'])){
                $csched_aperm = filter_input(INPUT_POST, "csched_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $csched_aperm = 0;
            }
            
            if(isset($_REQUEST['csched_eperm'])){
                $csched_eperm = filter_input(INPUT_POST, "csched_eperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $csched_eperm = 0;
            }
            
            if(isset($_REQUEST['csched_dperm'])){
                $csched_dperm = filter_input(INPUT_POST, "csched_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $csched_dperm = 0;
            }
            
            if(isset($_REQUEST['cons_rperm'])){
                $cons_rperm = filter_input(INPUT_POST, "cons_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $cons_rperm = 0;
            }
            
            if(isset($_REQUEST['cons_aperm'])){
                $cons_aperm = filter_input(INPUT_POST, "cons_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $cons_aperm = 0;
            }
            
            if(isset($_REQUEST['cons_eperm'])){
                $cons_eperm = filter_input(INPUT_POST, "cons_eperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $cons_eperm = 0;
            }
            
            if(isset($_REQUEST['cons_dperm'])){
                $cons_dperm = filter_input(INPUT_POST, "cons_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $cons_dperm = 0;
            }
    
            if(isset($_REQUEST['call_rperm'])){
                $call_rperm = filter_input(INPUT_POST, "call_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $call_rperm = 0;
            }
    
            if(isset($_REQUEST['call_aperm'])){
                $call_aperm = filter_input(INPUT_POST, "call_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $call_aperm = 0;
            }
    
            if(isset($_REQUEST['call_eperm'])){
                $call_eperm = filter_input(INPUT_POST, "call_eperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $call_eperm = 0;
            }
    
            if(isset($_REQUEST['call_dperm'])){
                $call_dperm = filter_input(INPUT_POST, "call_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $call_dperm = 0;
            }
            
            if(isset($_REQUEST['goaml_rperm'])){
                $goaml_rperm = filter_input(INPUT_POST, "goaml_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $goaml_rperm = 0;
            }
            
            if(isset($_REQUEST['goaml_aperm'])){
                $goaml_aperm = filter_input(INPUT_POST, "goaml_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $goaml_aperm = 0;
            }
            
            if(isset($_REQUEST['goaml_dperm'])){
                $goaml_dperm = filter_input(INPUT_POST, "goaml_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $goaml_dperm = 0;
            }
            
            if(isset($_REQUEST['terror_rperm'])){
                $terror_rperm = filter_input(INPUT_POST, "terror_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $terror_rperm = 0;
            }
            
            if(isset($_REQUEST['terror_eperm'])){
                $terror_eperm = filter_input(INPUT_POST, "terror_eperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $terror_eperm = 0;
            }
            
            if(isset($_REQUEST['terror_dperm'])){
                $terror_dperm = filter_input(INPUT_POST, "terror_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $terror_dperm = 0;
            }
            
            if(isset($_REQUEST['agr_rperm'])){
                $agr_rperm = filter_input(INPUT_POST, "agr_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $agr_rperm = 0;
            }
            
            if(isset($_REQUEST['agr_aperm'])){
                $agr_aperm = filter_input(INPUT_POST, "agr_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $agr_aperm = 0;
            }
            
            if(isset($_REQUEST['agr_eperm'])){
                $agr_eperm = filter_input(INPUT_POST, "agr_eperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $agr_eperm = 0;
            }
            
            if(isset($_REQUEST['agr_dperm'])){
                $agr_dperm = filter_input(INPUT_POST, "agr_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $agr_dperm = 0;
            }
    
            if(isset($_REQUEST['logs_rperm'])){
                $logs_rperm = filter_input(INPUT_POST, "logs_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $logs_rperm = 0;
            }
    
            if(isset($_REQUEST['logs_dperm'])){
                $logs_dperm = filter_input(INPUT_POST, "logs_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $logs_dperm = 0;
            }
            
            if(isset($_REQUEST['vacf_aperm'])){
                $vacf_aperm = filter_input(INPUT_POST, "vacf_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $vacf_aperm = 0;
            }
            
            if(isset($_REQUEST['vacl_aperm'])){
                $vacl_aperm = filter_input(INPUT_POST, "vacl_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $vacl_aperm = 0;
            }
            
            if(isset($_REQUEST['emp_perms_read'])){
                $emp_perms_read = filter_input(INPUT_POST, "emp_perms_read", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $emp_perms_read = 0;
            }
            
            if(isset($_REQUEST['emp_perms_add'])){
                $emp_perms_add = filter_input(INPUT_POST, "emp_perms_add", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $emp_perms_add = 0;
            }
            
            if(isset($_REQUEST['emp_perms_edit'])){
                $emp_perms_edit = filter_input(INPUT_POST, "emp_perms_edit", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $emp_perms_edit = 0;
            }
    
            if(isset($_REQUEST['emp_perms_delete'])){
                $emp_perms_delete = filter_input(INPUT_POST, "emp_perms_delete", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $emp_perms_delete = 0;
            }
    
            if(isset($_REQUEST['workingtime_rperm'])){
                $workingtime_rperm = filter_input(INPUT_POST, "workingtime_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $workingtime_rperm = 0;
            }
    
            if(isset($_REQUEST['workingtime_aperm'])){
                $workingtime_aperm = filter_input(INPUT_POST, "workingtime_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $workingtime_aperm = 0;
            }
    
            if(isset($_REQUEST['workingtime_eperm'])){
                $workingtime_eperm = filter_input(INPUT_POST, "workingtime_eperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $workingtime_eperm = 0;
            }
    
            if(isset($_REQUEST['workingtime_dperm'])){
                $workingtime_dperm = filter_input(INPUT_POST, "workingtime_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $workingtime_dperm = 0;
            }
    
            if(isset($_REQUEST['selectors_rperm'])){
                $selectors_rperm = filter_input(INPUT_POST, "selectors_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $selectors_rperm = 0;
            }
    
            if(isset($_REQUEST['selectors_aperm'])){
                $selectors_aperm = filter_input(INPUT_POST, "selectors_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $selectors_aperm = 0;
            }
    
            if(isset($_REQUEST['selectors_eperm'])){
                $selectors_eperm = filter_input(INPUT_POST, "selectors_eperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $selectors_eperm = 0;
            }
    
            if(isset($_REQUEST['selectors_dperm'])){
                $selectors_dperm = filter_input(INPUT_POST, "selectors_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $selectors_dperm = 0;
            }
            
            if(isset($_REQUEST['rate_rperm'])){
                $rate_rperm = filter_input(INPUT_POST, "rate_rperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $rate_rperm = 0;
            }
            
            if(isset($_REQUEST['rate_aperm'])){
                $rate_aperm = filter_input(INPUT_POST, "rate_aperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $rate_aperm = 0;
            }
            
            if(isset($_REQUEST['rate_eperm'])){
                $rate_eperm = filter_input(INPUT_POST, "rate_eperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $rate_eperm = 0;
            }
    
            if(isset($_REQUEST['rate_dperm'])){
                $rate_dperm = filter_input(INPUT_POST, "rate_dperm", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $rate_dperm = 0;
            }
    
            if(isset($_REQUEST['representative_exp'])){
                $representative_exp = filter_input(INPUT_POST, "representative_exp", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $representative_exp = 0;
            }
            /*
            
            if(isset($_REQUEST['accfinance_rperm'])){
                $accfinance_rperm = stripslashes($_REQUEST['accfinance_rperm']);
                $accfinance_rperm = mysqli_real_escape_string($conn, $accfinance_rperm);
            } else{
                $accfinance_rperm = 0;
            }
            
            if(isset($_REQUEST['accfinance_eperm'])){
                $accfinance_eperm = stripslashes($_REQUEST['accfinance_eperm']);
                $accfinance_eperm = mysqli_real_escape_string($conn, $accfinance_eperm);
            } else{
                $accfinance_eperm = 0;
            }
            
            if(isset($_REQUEST['accmainterms_rperm'])){
                $accmainterms_rperm = stripslashes($_REQUEST['accmainterms_rperm']);
                $accmainterms_rperm = mysqli_real_escape_string($conn, $accmainterms_rperm);
            } else{
                $accmainterms_rperm = 0;
            }
            
            if(isset($_REQUEST['accmainterms_aperm'])){
                $accmainterms_aperm = stripslashes($_REQUEST['accmainterms_aperm']);
                $accmainterms_aperm = mysqli_real_escape_string($conn, $accmainterms_aperm);
            } else{
                $accmainterms_aperm = 0;
            }
            
            if(isset($_REQUEST['accmainterms_eperm'])){
                $accmainterms_eperm = stripslashes($_REQUEST['accmainterms_eperm']);
                $accmainterms_eperm = mysqli_real_escape_string($conn, $accmainterms_eperm);
            } else{
                $accmainterms_eperm = 0;
            }
            
            if(isset($_REQUEST['accmainterms_dperm'])){
                $accmainterms_dperm = stripslashes($_REQUEST['accmainterms_dperm']);
                $accmainterms_dperm = mysqli_real_escape_string($conn, $accmainterms_dperm);
            } else{
                $accmainterms_dperm = 0;
            }
            
            if(isset($_REQUEST['accsecterms_rperm'])){
                $accsecterms_rperm = stripslashes($_REQUEST['accsecterms_rperm']);
                $accsecterms_rperm = mysqli_real_escape_string($conn, $accsecterms_rperm);
            } else{
                $accsecterms_rperm = 0;
            }
            
            if(isset($_REQUEST['accsecterms_aperm'])){
                $accsecterms_aperm = stripslashes($_REQUEST['accsecterms_aperm']);
                $accsecterms_aperm = mysqli_real_escape_string($conn, $accsecterms_aperm);
            } else{
                $accsecterms_aperm = 0;
            }
            
            if(isset($_REQUEST['accsecterms_eperm'])){
                $accsecterms_eperm = stripslashes($_REQUEST['accsecterms_eperm']);
                $accsecterms_eperm = mysqli_real_escape_string($conn, $accsecterms_eperm);
            } else{
                $accsecterms_eperm = 0;
            }
            
            if(isset($_REQUEST['accsecterms_dperm'])){
                $accsecterms_dperm = stripslashes($_REQUEST['accsecterms_dperm']);
                $accsecterms_dperm = mysqli_real_escape_string($conn, $accsecterms_dperm);
            } else{
                $accsecterms_dperm = 0;
            }
            
            if(isset($_REQUEST['accbankaccs_rperm'])){
                $accbankaccs_rperm = stripslashes($_REQUEST['accbankaccs_rperm']);
                $accbankaccs_rperm = mysqli_real_escape_string($conn, $accbankaccs_rperm);
            } else{
                $accbankaccs_rperm = 0;
            }
            
            if(isset($_REQUEST['accbankaccs_aperm'])){
                $accbankaccs_aperm = stripslashes($_REQUEST['accbankaccs_aperm']);
                $accbankaccs_aperm = mysqli_real_escape_string($conn, $accbankaccs_aperm);
            } else{
                $accbankaccs_aperm = 0;
            }
            
            if(isset($_REQUEST['accbankaccs_eperm'])){
                $accbankaccs_eperm = stripslashes($_REQUEST['accbankaccs_eperm']);
                $accbankaccs_eperm = mysqli_real_escape_string($conn, $accbankaccs_eperm);
            } else{
                $accbankaccs_eperm = 0;
            }
            
            if(isset($_REQUEST['accbankaccs_dperm'])){
                $accbankaccs_dperm = stripslashes($_REQUEST['accbankaccs_dperm']);
                $accbankaccs_dperm = mysqli_real_escape_string($conn, $accbankaccs_dperm);
            } else{
                $accbankaccs_dperm = 0;
            }
            
            if(isset($_REQUEST['acccasecost_rperm'])){
                $acccasecost_rperm = stripslashes($_REQUEST['acccasecost_rperm']);
                $acccasecost_rperm = mysqli_real_escape_string($conn, $acccasecost_rperm);
            } else{
                $acccasecost_rperm = 0;
            }
            
            if(isset($_REQUEST['acccasecost_aperm'])){
                $acccasecost_aperm = stripslashes($_REQUEST['acccasecost_aperm']);
                $acccasecost_aperm = mysqli_real_escape_string($conn, $acccasecost_aperm);
            } else{
                $acccasecost_aperm = 0;
            }
            
            if(isset($_REQUEST['accrevenues_rperm'])){
                $accrevenues_rperm = stripslashes($_REQUEST['accrevenues_rperm']);
                $accrevenues_rperm = mysqli_real_escape_string($conn, $accrevenues_rperm);
            } else{
                $accrevenues_rperm = 0;
            }
            
            if(isset($_REQUEST['accrevenues_aperm'])){
                $accrevenues_aperm = stripslashes($_REQUEST['accrevenues_aperm']);
                $accrevenues_aperm = mysqli_real_escape_string($conn, $accrevenues_aperm);
            } else{
                $accrevenues_aperm = 0;
            }
            
            if(isset($_REQUEST['accrevenues_dperm'])){
                $accrevenues_dperm = stripslashes($_REQUEST['accrevenues_dperm']);
                $accrevenues_dperm = mysqli_real_escape_string($conn, $accrevenues_dperm);
            } else{
                $accrevenues_dperm = 0;
            }
            
            if(isset($_REQUEST['accexpenses_rperm'])){
                $accexpenses_rperm = stripslashes($_REQUEST['accexpenses_rperm']);
                $accexpenses_rperm = mysqli_real_escape_string($conn, $accexpenses_rperm);
            } else{
                $accexpenses_rperm = 0;
            }
            
            if(isset($_REQUEST['accexpenses_aperm'])){
                $accexpenses_aperm = stripslashes($_REQUEST['accexpenses_aperm']);
                $accexpenses_aperm = mysqli_real_escape_string($conn, $accexpenses_aperm);
            } else{
                $accexpenses_aperm = 0;
            }
            
            if(isset($_REQUEST['accexpenses_dperm'])){
                $accexpenses_dperm = stripslashes($_REQUEST['accexpenses_dperm']);
                $accexpenses_dperm = mysqli_real_escape_string($conn, $accexpenses_dperm);
            } else{
                $accexpenses_dperm = 0;
            }
            
            if(isset($_REQUEST['emp_rperm'])){
                $emp_rperm = stripslashes($_REQUEST['emp_rperm']);
                $emp_rperm = mysqli_real_escape_string($conn, $emp_rperm);
            } else{
                $emp_rperm = 0;
            }
            
            if(isset($_REQUEST['emp_aperm'])){
                $emp_aperm = stripslashes($_REQUEST['emp_aperm']);
                $emp_aperm = mysqli_real_escape_string($conn, $emp_aperm);
            } else{
                $emp_aperm = 0;
            }
            
            if(isset($_REQUEST['emp_eperm'])){
                $emp_eperm = stripslashes($_REQUEST['emp_eperm']);
                $emp_eperm = mysqli_real_escape_string($conn, $emp_eperm);
            } else{
                $emp_eperm = 0;
            }
            
            if(isset($_REQUEST['emp_dperm'])){
                $emp_dperm = stripslashes($_REQUEST['emp_dperm']);
                $emp_dperm = mysqli_real_escape_string($conn, $emp_dperm);
            } else{
                $emp_dperm = 0;
            }
            */
            
            $activation_date = date("Y-m-d");
            $opened_by = $_SESSION['id'];
            $logins_num = 0;
    
            $targetDir = "files_images/employees/$id";
            $upload = $upload2 = $upload3 = $upload4 = 0;
            
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            if (isset($_FILES['personal_image'])) {
                $result = secure_file_upload($_FILES['personal_image'], $targetDir);
                if ($result['status']) {
                    $personal_image = $result['path'];
                    echo "personal_image has been uploaded. File size: {$result['size']}<br>";
                    $upload = 1;
                } else {
                    $personal_image = '';
                    echo "Upload error (personal_image): {$result['error']}<br>";
                }
            } else {
                $personal_image = '';
            }
            
            // 2. Passport Residence
            if (isset($_FILES['passport_residence'])) {
                $result = secure_file_upload($_FILES['passport_residence'], $targetDir);
                if ($result['status']) {
                    $passport_residence = $result['path'];
                    echo "passport_residence has been uploaded. File size: {$result['size']}<br>";
                    $upload2 = 1;
                } else {
                    $passport_residence = '';
                    echo "Upload error (passport_residence): {$result['error']}<br>";
                }
            } else {
                $passport_residence = '';
            }
            
            // 3. Practical Qualification
            if (isset($_FILES['practical_qualification'])) {
                $result = secure_file_upload($_FILES['practical_qualification'], $targetDir);
                if ($result['status']) {
                    $practical_qualification = $result['path'];
                    echo "practical_qualification has been uploaded. File size: {$result['size']}<br>";
                    $upload3 = 1;
                } else {
                    $practical_qualification = '';
                    echo "Upload error (practical_qualification): {$result['error']}<br>";
                }
            } else {
                $practical_qualification = '';
            }
            
            if($upload = 0){
                $personal_image = '';
            }
            
            if($upload2 = 0){
                $passport_residence = '';
            }
            
            if($upload3 = 0){
                $practical_qualification = '';
            }
            
            $yearly_vacbalance = 30;
            $sick_vacbalance = 90;
            $mother_vacbalance = 30;
            $father_vacbalance = 5;
            $study_vacbalance = 10;
            
            $stmt = $conn->prepare("UPDATE user SET name=?, username=?, nationality=?, password=?, tel1=?, tel2=?, email=?, address=?, social=?, sex=?, passport_no=?, 
            residence_no=?, card_no=?, passport_exp=?, residence_date=?, app_date=?, residence_exp=?, contract_exp=?, idno=?, cardno_exp=?, id_exp=?, sigorta_exp=?, dxblaw_exp=?, 
            shjlaw_exp=?, ajmlaw_exp=?, abdlaw_exp=?, dob=?, app_type=?, responsible=?, job_title=?, section=?, work_place=?, signin_perm=?, emergency_name1=?, emergency_relate1=?, 
            emergency_tel1=?, emergency_name2=?, emergency_relate2=?, emergency_tel2=?, bank_name=?, iban=?, acc_no=?, pay_way=?, basic_salary=?, travel_tickets=?, oil_cost=?, 
            housing_cost=?, living_cost=?, cfiles_rperm=?, cfiles_aperm=?, 
            cfiles_eperm=?, cfiles_dperm=?, cfiles_archive_perm=?, secretf_aperm=?, session_rperm=?, session_aperm=?, session_eperm=?, session_dperm=?, sessionrole_rperm=?, 
            levels_eperm=?, degree_rperm=?, degree_aperm=?, degree_eperm=?, degree_dperm=?, admjobs_rperm=?, admjobs_aperm=?, admjobs_eperm=?, admjobs_dperm=?, admjobs_pperm=?, 
            admprivjobs_rperm=?, workingtime_rperm=?, workingtime_aperm=?, workingtime_eperm=?, workingtime_dperm=?, selectors_rperm=?, selectors_aperm=?, selectors_eperm=?, selectors_dperm=?, 
            attachments_dperm=?, judicialwarn_rperm=?, judicialwarn_aperm=?, judicialwarn_eperm=?, judicialwarn_dperm=?, petition_rperm=?, petition_aperm=?, petition_eperm=?, 
            petition_dperm=?, note_rperm=?, note_aperm=?, note_eperm=?, note_dperm=?, doc_faperm=?, doc_laperm=?, clients_rperm=?, clients_aperm=?, clients_eperm=?, clients_dperm=?, 
            csched_rperm=?, csched_aperm=?, csched_eperm=?, csched_dperm=?, cons_rperm=?, cons_aperm=?, cons_eperm=?, cons_dperm=?, call_rperm=?, call_aperm=?, call_eperm=?, 
            call_dperm=?, goaml_rperm=?, goaml_aperm=?, goaml_dperm=?, terror_rperm=?, terror_eperm=?, terror_dperm=?, agr_rperm=?, agr_aperm=?, agr_eperm=?, agr_dperm=?, 
            logs_rperm=?, logs_dperm=?, vacf_aperm=?, vacl_aperm=?, emp_perms_read=?, emp_perms_add=?, emp_perms_edit=?, emp_perms_delete=?, rate_rperm=?, rate_aperm=?, rate_eperm=?, 
            rate_dperm=?, userattendance_rperm=?, userattendance_aperm=?, userattendance_eperm=?, userattendance_dperm=?, useratts_eperm=?, useratts_dperm=?, loggingtimes_eperm=?, 
            discounts_rperm=?, discounts_aperm=?, discounts_dperm=?, warnings_rperm=?, warnings_aperm=?, warnings_eperm=?, warnings_dperm=?, trainings_rperm=?, trainings_aperm=?, 
            trainings_eperm=?, trainings_dperm=?, typecolor_eperm=?, yearly_vacbalance=?, sick_vacbalance=?, mother_vacbalance=?, father_vacbalance=?, study_vacbalance=?, representative_exp=?, 
            logins_num=?, activation_date=?, opened_by=?, personal_image=?, passport_residence=?, practical_qualification=? WHERE id=?");
            $stmt->bind_param("ssssssssssssssssssssssssssssiississsssssssssssssiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiisisisssi",
            $name, $username, $nationality, $encrypted_password, $encrypted_tel1, $encrypted_tel2, $encrypted_email, $encrypted_address, $social, $sex, $encrypted_passport_no,
            $residence_no, $card_no, $passport_exp, $residence_date, $app_date, $residence_exp, $contract_exp, $idno, $cardno_exp, $id_exp, $sigorta_exp, $dxblaw_exp, $shjlaw_exp, 
            $ajmlaw_exp, $abdlaw_exp, $dob, $app_type, $responsible, $job_title, $section, $work_place, $signin_perm, $emergency_name1, $emergency_relate1, $emergency_tel1, 
            $emergency_name2, $emergency_relate2, $emergency_tel2, $bank_name, $iban, $acc_no, $pay_way,  $basic_salary, $travel_tickets, $oil_cost, $housing_cost, $living_cost, 
            $cfiles_rperm, $cfiles_aperm, $cfiles_eperm, $cfiles_dperm, $cfiles_archive_perm, $secretf_aperm, $session_rperm, $session_aperm, $session_eperm, $session_dperm, 
            $sessionrole_rperm, $levels_eperm, $degree_rperm, $degree_aperm, $degree_eperm, $degree_dperm, $admjobs_rperm, $admjobs_aperm, $admjobs_eperm, $admjobs_dperm, 
            $admjobs_pperm, $admprivjobs_rperm, $workingtime_rperm, $workingtime_aperm, $workingtime_eperm, $workingtime_dperm, $selectors_rperm, $selectors_aperm, $selectors_eperm, 
            $selectors_dperm, $attachments_dperm, $judicialwarn_rperm, $judicialwarn_aperm, $judicialwarn_eperm, $judicialwarn_dperm, $petition_rperm, $petition_aperm, $petition_eperm, 
            $petition_dperm, $note_rperm, $note_aperm, $note_eperm, $note_dperm, $doc_faperm, $doc_laperm, $clients_rperm, $clients_aperm, $clients_eperm, $clients_dperm, $csched_rperm, 
            $csched_aperm, $csched_eperm, $csched_dperm, $cons_rperm, $cons_aperm, $cons_eperm, $cons_dperm, $call_rperm, $call_aperm, $call_eperm, $call_dperm, $goaml_rperm, 
            $goaml_aperm, $goaml_dperm, $terror_rperm, $terror_eperm, $terror_dperm, $agr_rperm, $agr_aperm, $agr_eperm, $agr_dperm, $logs_rperm, $logs_dperm, $vacf_aperm, $vacl_aperm, 
            $emp_perms_read, $emp_perms_add, $emp_perms_edit, $emp_perms_delete, $rate_rperm, $rate_aperm, $rate_eperm, $rate_dperm, $userattendance_rperm, $userattendance_aperm, 
            $userattendance_eperm, $userattendance_dperm, $useratts_eperm, $useratts_dperm, $loggingtimes_eperm, $discounts_rperm, $discounts_aperm, $discounts_dperm, $warnings_rperm, 
            $warnings_aperm, $warnings_eperm, $warnings_dperm, $trainings_rperm, $trainings_aperm, $trainings_eperm, $trainings_dperm, $typecolor_eperm, $yearly_vacbalance, 
            $sick_vacbalance, $mother_vacbalance, $father_vacbalance, $study_vacbalance, $representative_exp, $logins_num, $activation_date, $opened_by, $personal_image, $passport_residence, 
            $practical_qualification, $id);
            $stmt->execute();
            $stmt->close();
            
            if(isset($flag) && $flag === '1'){
                include_once 'addlog.php';
            }
            
            header("Location: mbhEmps.php?section=users&savedemp=1");
            exit();
        }
    }
    header("Location: mbhEmps.php?section=users&error=0");
    exit();
?>