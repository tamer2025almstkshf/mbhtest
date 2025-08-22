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
    && $_REQUEST['passport_no'] !== ''){
        if($row_permcheck['emp_perms_edit'] == 1){
            
            $user_n = filter_input(INPUT_POST, "username", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $stmt_dup = $conn->prepare("SELECT * FROM user WHERE username = ? AND id!=?");
            $stmt_dup->bind_param("si", $user_n, $id);
            $stmt_dup->execute();
            $result_dup = $stmt_dup->get_result();
            if($result_dup->num_rows > 0){
                header("Location: mbhEmps.php?edit=1&id=$id&namerror=1");
                exit();
            }
            
            $stmtr = $conn->prepare("SELECT * FROM user WHERE id=?");
            $stmtr->bind_param("i", $id);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmtr->close();
            
            $name = $rowr['name'];
            if(isset($_REQUEST['name'])){
                $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $username = $rowr['username'];
            if(isset($_REQUEST['username'])){
                $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $password = $rowr['password'];
            if(isset($_REQUEST['password'])){
                $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $nationality = $rowr['nationality'];
            if(isset($_REQUEST['nationality'])){
                $nationality = filter_input(INPUT_POST, "nationality", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $email = $rowr['email'];
            if(isset($_REQUEST['email'])){
                $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $tel1 = $rowr['tel1'];
            if(isset($_REQUEST['tel1'])){
                $tel1 = filter_input(INPUT_POST, "tel1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $tel2 = $rowr['tel2'];
            if(isset($_REQUEST['tel2'])){
                $tel2 = filter_input(INPUT_POST, "tel2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $address = $rowr['address'];
            if(isset($_REQUEST['address'])){
                $address = filter_input(INPUT_POST, "address", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $social = $rowr['social'];
            if(isset($_REQUEST['social'])){
                $social = filter_input(INPUT_POST, "social", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $sex = $rowr['sex'];
            if(isset($_REQUEST['sex'])){
                $sex = filter_input(INPUT_POST, "sex", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $passport_no = $rowr['passport_no'];
            if(isset($_REQUEST['passport_no'])){
                $passport_no = filter_input(INPUT_POST, "passport_no", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
    
            include_once 'AES256.php';
            $encrypted_password = openssl_encrypt($password, $cipher, $key, $options, $iv);
            $encrypted_tel1 = openssl_encrypt($tel1, $cipher, $key, $options, $iv);
            $encrypted_tel2 = openssl_encrypt($tel2, $cipher, $key, $options, $iv);
            $encrypted_email = openssl_encrypt($email, $cipher, $key, $options, $iv);
            $encrypted_address = openssl_encrypt($address, $cipher, $key, $options, $iv);
            $encrypted_passport_no = openssl_encrypt($passport_no, $cipher, $key, $options, $iv);
            
            $residence_no = $rowr['residence_no'];
            if(isset($_REQUEST['residence_no'])){
                $residence_no = filter_input(INPUT_POST, "residence_no", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $card_no = $rowr['card_no'];
            if(isset($_REQUEST['card_no'])){
                $card_no = filter_input(INPUT_POST, "card_no", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $passport_exp = $rowr['passport_exp'];
            if(isset($_REQUEST['passport_exp'])){
                $passport_exp = filter_input(INPUT_POST, "passport_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $residence_date = $rowr['residence_date'];
            if(isset($_REQUEST['residence_date'])){
                $residence_date = filter_input(INPUT_POST, "residence_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $app_date = $rowr['app_date'];
            if(isset($_REQUEST['app_date'])){
                $app_date = filter_input(INPUT_POST, "app_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $residence_exp = $rowr['residence_exp'];
            if(isset($_REQUEST['residence_exp'])){
                $residence_exp = filter_input(INPUT_POST, "residence_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $contract_exp = $rowr['contract_exp'];
            if(isset($_REQUEST['contract_exp'])){
                $contract_exp = filter_input(INPUT_POST, "contract_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $idno = $rowr['idno'];
            if(isset($_REQUEST['idno'])){
                $idno = filter_input(INPUT_POST, "idno", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $cardno_exp = $rowr['cardno_exp'];
            if(isset($_REQUEST['cardno_exp'])){
                $cardno_exp = filter_input(INPUT_POST, "cardno_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $id_exp = $rowr['id_exp'];
            if(isset($_REQUEST['id_exp'])){
                $id_exp = filter_input(INPUT_POST, "id_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $sigorta_exp = $rowr['sigorta_exp'];
            if(isset($_REQUEST['sigorta_exp'])){
                $sigorta_exp = filter_input(INPUT_POST, "sigorta_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $dxblaw_exp = $rowr['dxblaw_exp'];
            if(isset($_REQUEST['dxblaw_exp'])){
                $dxblaw_exp = filter_input(INPUT_POST, "dxblaw_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $shjlaw_exp = $rowr['shjlaw_exp'];
            if(isset($_REQUEST['shjlaw_exp'])){
                $shjlaw_exp = filter_input(INPUT_POST, "shjlaw_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $ajmlaw_exp = $rowr['ajmlaw_exp'];
            if(isset($_REQUEST['ajmlaw_exp'])){
                $ajmlaw_exp = filter_input(INPUT_POST, "ajmlaw_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $abdlaw_exp = $rowr['abdlaw_exp'];
            if(isset($_REQUEST['abdlaw_exp'])){
                $abdlaw_exp = filter_input(INPUT_POST, "abdlaw_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $dob = $rowr['dob'];
            if(isset($_REQUEST['dob'])){
                $dob = filter_input(INPUT_POST, "dob", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $app_type = $rowr['app_type'];
            if(isset($_REQUEST['app_type'])){
                $app_type = filter_input(INPUT_POST, "app_type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $responsible = $rowr['responsible'];
            if(isset($_REQUEST['responsible'])){
                $responsible = filter_input(INPUT_POST, "responsible", FILTER_SANITIZE_NUMBER_INT);
            }
            $job_title = $rowr['job_title'];
            if(isset($_REQUEST['job_title'])){
                $job_title = filter_input(INPUT_POST, "job_title", FILTER_SANITIZE_NUMBER_INT);
            }
            $section = $rowr['section'];
            if(isset($_REQUEST['section'])){
                $section = filter_input(INPUT_POST, "section", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $work_place = $rowr['work_place'];
            if(isset($_REQUEST['work_place'])){
                $work_place = filter_input(INPUT_POST, "work_place", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $signin_perm = $rowr['signin_perm'];
            if(isset($_REQUEST['signin_perm'])){
                $signin_perm = filter_input(INPUT_POST, "signin_perm", FILTER_SANITIZE_NUMBER_INT);
            }
            $representative_exp = $rowr['representative_exp'];
            if(isset($_REQUEST['representative_exp'])){
                $representative_exp = filter_input(INPUT_POST, "representative_exp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            
            $emergency_name1 = $rowr['emergency_name1'];
            if(isset($_REQUEST['emergency_name1'])){
                $emergency_name1 = filter_input(INPUT_POST, "emergency_name1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $emergency_relate1 = $rowr['emergency_relate1'];
            if(isset($_REQUEST['emergency_relate1'])){
                $emergency_relate1 = filter_input(INPUT_POST, "emergency_relate1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $emergency_tel1 = $rowr['emergency_tel1'];
            if(isset($_REQUEST['emergency_tel1'])){
                $emergency_tel1 = filter_input(INPUT_POST, "emergency_tel1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $emergency_name2 = $rowr['emergency_name2'];
            if(isset($_REQUEST['emergency_name2'])){
                $emergency_name2 = filter_input(INPUT_POST, "emergency_name2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $emergency_relate2 = $rowr['emergency_relate2'];
            if(isset($_REQUEST['emergency_relate2'])){
                $emergency_relate2 = filter_input(INPUT_POST, "emergency_relate2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $emergency_tel2 = $rowr['emergency_tel2'];
            if(isset($_REQUEST['emergency_tel2'])){
                $emergency_tel2 = filter_input(INPUT_POST, "emergency_tel2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            
            $bank_name = $rowr['bank_name'];
            if(isset($_REQUEST['bank_name'])){
                $bank_name = filter_input(INPUT_POST, "bank_name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $iban = $rowr['iban'];
            if(isset($_REQUEST['iban'])){
                $iban = filter_input(INPUT_POST, "iban", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $acc_no = $rowr['acc_no'];
            if(isset($_REQUEST['acc_no'])){
                $acc_no = filter_input(INPUT_POST, "acc_no", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $pay_way = $rowr['pay_way'];
            if(isset($_REQUEST['pay_way'])){
                $pay_way = filter_input(INPUT_POST, "pay_way", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            
            $basic_salary = $rowr['basic_salary'];
            if(isset($_REQUEST['basic_salary'])){
                $basic_salary = filter_input(INPUT_POST, "basic_salary", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $travel_tickets = $rowr['travel_tickets'];
            if(isset($_REQUEST['travel_tickets'])){
                $travel_tickets = filter_input(INPUT_POST, "travel_tickets", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $oil_cost = $rowr['oil_cost'];
            if(isset($_REQUEST['oil_cost'])){
                $oil_cost = filter_input(INPUT_POST, "oil_cost", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $housing_cost = $rowr['housing_cost'];
            if(isset($_REQUEST['housing_cost'])){
                $housing_cost = filter_input(INPUT_POST, "housing_cost", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $living_cost = $rowr['living_cost'];
            if(isset($_REQUEST['living_cost'])){
                $living_cost = filter_input(INPUT_POST, "living_cost", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
    
            $cfiles_rperm = $rowr['cfiles_rperm'];
            if(isset($_REQUEST['cfiles_rperm'])){
                $cfiles_rperm = filter_input(INPUT_POST, "cfiles_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $cfiles_aperm = $rowr['cfiles_aperm'];
            if(isset($_REQUEST['cfiles_aperm'])){
                $cfiles_aperm = filter_input(INPUT_POST, "cfiles_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $cfiles_eperm = $rowr['cfiles_eperm'];
            if(isset($_REQUEST['cfiles_eperm'])){
                $cfiles_eperm = filter_input(INPUT_POST, "cfiles_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $cfiles_dperm = $rowr['cfiles_dperm'];
            if(isset($_REQUEST['cfiles_dperm'])){
                $cfiles_dperm = filter_input(INPUT_POST, "cfiles_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $cfiles_archive_perm = $rowr['cfiles_archive_perm'];
            if(isset($_REQUEST['cfiles_archive_perm'])){
                $cfiles_archive_perm = filter_input(INPUT_POST, "cfiles_archive_perm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $secretf_aperm = $rowr['secretf_aperm'];
            if(isset($_REQUEST['secretf_aperm'])){
                $secretf_aperm = filter_input(INPUT_POST, "secretf_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $session_rperm = $rowr['session_rperm'];
            if(isset($_REQUEST['session_rperm'])){
                $session_rperm = filter_input(INPUT_POST, "session_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $session_aperm = $rowr['session_aperm'];
            if(isset($_REQUEST['session_aperm'])){
                $session_aperm = filter_input(INPUT_POST, "session_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $session_eperm = $rowr['session_eperm'];
            if(isset($_REQUEST['session_eperm'])){
                $session_eperm = filter_input(INPUT_POST, "session_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $session_dperm = $rowr['session_dperm'];
            if(isset($_REQUEST['session_dperm'])){
                $session_dperm = filter_input(INPUT_POST, "session_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $sessionrole_rperm = $rowr['sessionrole_rperm'];
            if(isset($_REQUEST['sessionrole_rperm'])){
                $sessionrole_rperm = filter_input(INPUT_POST, "sessionrole_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $levels_eperm = $rowr['levels_eperm'];
            if(isset($_REQUEST['levels_eperm'])){
                $levels_eperm = filter_input(INPUT_POST, "levels_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $degree_rperm = $rowr['degree_rperm'];
            if(isset($_REQUEST['degree_rperm'])){
                $degree_rperm = filter_input(INPUT_POST, "degree_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $degree_aperm = $rowr['degree_aperm'];
            if(isset($_REQUEST['degree_aperm'])){
                $degree_aperm = filter_input(INPUT_POST, "degree_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $degree_eperm = $rowr['degree_eperm'];
            if(isset($_REQUEST['degree_eperm'])){
                $degree_eperm = filter_input(INPUT_POST, "degree_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $degree_dperm = $rowr['degree_dperm'];
            if(isset($_REQUEST['degree_dperm'])){
                $degree_dperm = filter_input(INPUT_POST, "degree_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $admjobs_rperm = $rowr['admjobs_rperm'];
            if(isset($_REQUEST['admjobs_rperm'])){
                $admjobs_rperm = filter_input(INPUT_POST, "admjobs_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $admjobs_aperm = $rowr['admjobs_aperm'];
            if(isset($_REQUEST['admjobs_aperm'])){
                $admjobs_aperm = filter_input(INPUT_POST, "admjobs_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $admjobs_eperm = $rowr['admjobs_eperm'];
            if(isset($_REQUEST['admjobs_eperm'])){
                $admjobs_eperm = filter_input(INPUT_POST, "admjobs_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $admjobs_dperm = $rowr['admjobs_dperm'];
            if(isset($_REQUEST['admjobs_dperm'])){
                $admjobs_dperm = filter_input(INPUT_POST, "admjobs_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $admjobs_pperm = $rowr['admjobs_pperm'];
            if(isset($_REQUEST['admjobs_pperm'])){
                $admjobs_pperm = filter_input(INPUT_POST, "admjobs_pperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $admprivjobs_rperm = $rowr['admprivjobs_rperm'];
            if(isset($_REQUEST['admprivjobs_rperm'])){
                $admprivjobs_rperm = filter_input(INPUT_POST, "admprivjobs_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $attachments_dperm = $rowr['attachments_dperm'];
            if(isset($_REQUEST['attachments_dperm'])){
                $attachments_dperm = filter_input(INPUT_POST, "attachments_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $judicialwarn_rperm = $rowr['judicialwarn_rperm'];
            if(isset($_REQUEST['judicialwarn_rperm'])){
                $judicialwarn_rperm = filter_input(INPUT_POST, "judicialwarn_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $judicialwarn_aperm = $rowr['judicialwarn_aperm'];
            if(isset($_REQUEST['judicialwarn_aperm'])){
                $judicialwarn_aperm = filter_input(INPUT_POST, "judicialwarn_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $judicialwarn_eperm = $rowr['judicialwarn_eperm'];
            if(isset($_REQUEST['judicialwarn_eperm'])){
                $judicialwarn_eperm = filter_input(INPUT_POST, "judicialwarn_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $judicialwarn_dperm = $rowr['judicialwarn_dperm'];
            if(isset($_REQUEST['judicialwarn_dperm'])){
                $judicialwarn_dperm = filter_input(INPUT_POST, "judicialwarn_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $petition_rperm = $rowr['petition_rperm'];
            if(isset($_REQUEST['petition_rperm'])){
                $petition_rperm = filter_input(INPUT_POST, "petition_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $petition_aperm = $rowr['petition_aperm'];
            if(isset($_REQUEST['petition_aperm'])){
                $petition_aperm = filter_input(INPUT_POST, "petition_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $petition_eperm = $rowr['petition_eperm'];
            if(isset($_REQUEST['petition_eperm'])){
                $petition_eperm = filter_input(INPUT_POST, "petition_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $petition_dperm = $rowr['petition_dperm'];
            if(isset($_REQUEST['petition_dperm'])){
                $petition_dperm = filter_input(INPUT_POST, "petition_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $note_rperm = $rowr['note_rperm'];
            if(isset($_REQUEST['note_rperm'])){
                $note_rperm = filter_input(INPUT_POST, "note_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $note_aperm = $rowr['note_aperm'];
            if(isset($_REQUEST['note_aperm'])){
                $note_aperm = filter_input(INPUT_POST, "note_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $note_eperm = $rowr['note_eperm'];
            if(isset($_REQUEST['note_eperm'])){
                $note_eperm = filter_input(INPUT_POST, "note_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $note_dperm = $rowr['note_dperm'];
            if(isset($_REQUEST['note_dperm'])){
                $note_dperm = filter_input(INPUT_POST, "note_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $doc_faperm = $rowr['doc_faperm'];
            if(isset($_REQUEST['doc_faperm'])){
                $doc_faperm = filter_input(INPUT_POST, "doc_faperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $doc_laperm = $rowr['doc_laperm'];
            if(isset($_REQUEST['doc_laperm'])){
                $doc_laperm = filter_input(INPUT_POST, "doc_laperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $clients_rperm = $rowr['clients_rperm'];
            if(isset($_REQUEST['clients_rperm'])){
                $clients_rperm = filter_input(INPUT_POST, "clients_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $clients_aperm = $rowr['clients_aperm'];
            if(isset($_REQUEST['clients_aperm'])){
                $clients_aperm = filter_input(INPUT_POST, "clients_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $clients_eperm = $rowr['clients_eperm'];
            if(isset($_REQUEST['clients_eperm'])){
                $clients_eperm = filter_input(INPUT_POST, "clients_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $clients_dperm = $rowr['clients_dperm'];
            if(isset($_REQUEST['clients_dperm'])){
                $clients_dperm = filter_input(INPUT_POST, "clients_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $csched_rperm = $rowr['csched_rperm'];
            if(isset($_REQUEST['csched_rperm'])){
                $csched_rperm = filter_input(INPUT_POST, "csched_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $csched_aperm = $rowr['csched_aperm'];
            if(isset($_REQUEST['csched_aperm'])){
                $csched_aperm = filter_input(INPUT_POST, "csched_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $csched_eperm = $rowr['csched_eperm'];
            if(isset($_REQUEST['csched_eperm'])){
                $csched_eperm = filter_input(INPUT_POST, "csched_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $csched_dperm = $rowr['csched_dperm'];
            if(isset($_REQUEST['csched_dperm'])){
                $csched_dperm = filter_input(INPUT_POST, "csched_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $cons_rperm = $rowr['cons_rperm'];
            if(isset($_REQUEST['cons_rperm'])){
                $cons_rperm = filter_input(INPUT_POST, "cons_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $cons_aperm = $rowr['cons_aperm'];
            if(isset($_REQUEST['cons_aperm'])){
                $cons_aperm = filter_input(INPUT_POST, "cons_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $cons_eperm = $rowr['cons_eperm'];
            if(isset($_REQUEST['cons_eperm'])){
                $cons_eperm = filter_input(INPUT_POST, "cons_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $cons_dperm = $rowr['cons_dperm'];
            if(isset($_REQUEST['cons_dperm'])){
                $cons_dperm = filter_input(INPUT_POST, "cons_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $call_rperm = $rowr['call_rperm'];
            if(isset($_REQUEST['call_rperm'])){
                $call_rperm = filter_input(INPUT_POST, "call_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $call_aperm = $rowr['call_aperm'];
            if(isset($_REQUEST['call_aperm'])){
                $call_aperm = filter_input(INPUT_POST, "call_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $call_eperm = $rowr['call_eperm'];
            if(isset($_REQUEST['call_eperm'])){
                $call_eperm = filter_input(INPUT_POST, "call_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $call_dperm = $rowr['call_dperm'];
            if(isset($_REQUEST['call_dperm'])){
                $call_dperm = filter_input(INPUT_POST, "call_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $goaml_rperm = $rowr['goaml_rperm'];
            if(isset($_REQUEST['goaml_rperm'])){
                $goaml_rperm = filter_input(INPUT_POST, "goaml_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $goaml_aperm = $rowr['goaml_aperm'];
            if(isset($_REQUEST['goaml_aperm'])){
                $goaml_aperm = filter_input(INPUT_POST, "goaml_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $goaml_dperm = $rowr['goaml_dperm'];
            if(isset($_REQUEST['goaml_dperm'])){
                $goaml_dperm = filter_input(INPUT_POST, "goaml_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $terror_rperm = $rowr['terror_rperm'];
            if(isset($_REQUEST['terror_rperm'])){
                $terror_rperm = filter_input(INPUT_POST, "terror_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $terror_eperm = $rowr['terror_eperm'];
            if(isset($_REQUEST['terror_eperm'])){
                $terror_eperm = filter_input(INPUT_POST, "terror_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $terror_dperm = $rowr['terror_dperm'];
            if(isset($_REQUEST['terror_dperm'])){
                $terror_dperm = filter_input(INPUT_POST, "terror_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $agr_rperm = $rowr['agr_rperm'];
            if(isset($_REQUEST['agr_rperm'])){
                $agr_rperm = filter_input(INPUT_POST, "agr_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $agr_aperm = $rowr['agr_aperm'];
            if(isset($_REQUEST['agr_aperm'])){
                $agr_aperm = filter_input(INPUT_POST, "agr_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $agr_eperm = $rowr['agr_eperm'];
            if(isset($_REQUEST['agr_eperm'])){
                $agr_eperm = filter_input(INPUT_POST, "agr_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $agr_dperm = $rowr['agr_dperm'];
            if(isset($_REQUEST['agr_dperm'])){
                $agr_dperm = filter_input(INPUT_POST, "agr_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $logs_rperm = $rowr['logs_rperm'];
            if(isset($_REQUEST['logs_rperm'])){
                $logs_rperm = filter_input(INPUT_POST, "logs_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $logs_dperm = $rowr['logs_dperm'];
            if(isset($_REQUEST['logs_dperm'])){
                $logs_dperm = filter_input(INPUT_POST, "logs_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $vacf_aperm = $rowr['vacf_aperm'];
            if(isset($_REQUEST['vacf_aperm'])){
                $vacf_aperm = filter_input(INPUT_POST, "vacf_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $vacl_aperm = $rowr['vacl_aperm'];
            if(isset($_REQUEST['vacl_aperm'])){
                $vacl_aperm = filter_input(INPUT_POST, "vacl_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $emp_perms_read = $rowr['emp_perms_read'];
            if(isset($_REQUEST['emp_perms_read'])){
                $emp_perms_read = filter_input(INPUT_POST, "emp_perms_read", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $emp_perms_add = $rowr['emp_perms_add'];
            if(isset($_REQUEST['emp_perms_add'])){
                $emp_perms_add = filter_input(INPUT_POST, "emp_perms_add", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $emp_perms_edit = $rowr['emp_perms_edit'];
            if(isset($_REQUEST['emp_perms_edit'])){
                $emp_perms_edit = filter_input(INPUT_POST, "emp_perms_edit", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $emp_perms_delete = $rowr['emp_perms_delete'];
            if(isset($_REQUEST['emp_perms_delete'])){
                $emp_perms_delete = filter_input(INPUT_POST, "emp_perms_delete", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $workingtime_rperm = $rowr['workingtime_rperm'];
            if(isset($_REQUEST['workingtime_rperm'])){
                $workingtime_rperm = filter_input(INPUT_POST, "workingtime_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $workingtime_aperm = $rowr['workingtime_aperm'];
            if(isset($_REQUEST['workingtime_aperm'])){
                $workingtime_aperm = filter_input(INPUT_POST, "workingtime_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $workingtime_eperm = $rowr['workingtime_eperm'];
            if(isset($_REQUEST['workingtime_eperm'])){
                $workingtime_eperm = filter_input(INPUT_POST, "workingtime_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $workingtime_dperm = $rowr['workingtime_dperm'];
            if(isset($_REQUEST['workingtime_dperm'])){
                $workingtime_dperm = filter_input(INPUT_POST, "workingtime_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
    
            $selectors_rperm = $rowr['selectors_rperm'];
            if(isset($_REQUEST['selectors_rperm'])){
                $selectors_rperm = filter_input(INPUT_POST, "selectors_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $selectors_aperm = $rowr['selectors_aperm'];
            if(isset($_REQUEST['selectors_aperm'])){
                $selectors_aperm = filter_input(INPUT_POST, "selectors_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $selectors_eperm = $rowr['selectors_eperm'];
            if(isset($_REQUEST['selectors_eperm'])){
                $selectors_eperm = filter_input(INPUT_POST, "selectors_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $selectors_dperm = $rowr['selectors_dperm'];
            if(isset($_REQUEST['selectors_dperm'])){
                $selectors_dperm = filter_input(INPUT_POST, "selectors_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $rate_rperm = $rowr['rate_rperm'];
            if(isset($_REQUEST['rate_rperm'])){
                $rate_rperm = filter_input(INPUT_POST, "rate_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $rate_aperm = $rowr['rate_aperm'];
            if(isset($_REQUEST['rate_aperm'])){
                $rate_aperm = filter_input(INPUT_POST, "rate_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $rate_eperm = $rowr['rate_eperm'];
            if(isset($_REQUEST['rate_eperm'])){
                $rate_eperm = filter_input(INPUT_POST, "rate_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $rate_dperm = $rowr['rate_dperm'];
            if(isset($_REQUEST['rate_dperm'])){
                $rate_dperm = filter_input(INPUT_POST, "rate_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $userattendance_rperm = $rowr['userattendance_rperm'];
            if(isset($_REQUEST['userattendance_rperm'])){
                $userattendance_rperm = filter_input(INPUT_POST, "userattendance_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $userattendance_aperm = $rowr['userattendance_aperm'];
            if(isset($_REQUEST['userattendance_aperm'])){
                $userattendance_aperm = filter_input(INPUT_POST, "userattendance_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $userattendance_eperm = $rowr['userattendance_eperm'];
            if(isset($_REQUEST['userattendance_eperm'])){
                $userattendance_eperm = filter_input(INPUT_POST, "userattendance_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $userattendance_dperm = $rowr['userattendance_dperm'];
            if(isset($_REQUEST['userattendance_dperm'])){
                $userattendance_dperm = filter_input(INPUT_POST, "userattendance_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $useratts_eperm = $rowr['useratts_eperm'];
            if(isset($_REQUEST['useratts_eperm'])){
                $useratts_eperm = filter_input(INPUT_POST, "useratts_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $useratts_dperm = $rowr['useratts_dperm'];
            if(isset($_REQUEST['useratts_dperm'])){
                $useratts_dperm = filter_input(INPUT_POST, "useratts_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $loggingtimes_eperm = $rowr['loggingtimes_eperm'];
            if(isset($_REQUEST['loggingtimes_eperm'])){
                $loggingtimes_eperm = filter_input(INPUT_POST, "loggingtimes_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $discounts_rperm = $rowr['discounts_rperm'];
            if(isset($_REQUEST['discounts_rperm'])){
                $discounts_rperm = filter_input(INPUT_POST, "discounts_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $discounts_aperm = $rowr['discounts_aperm'];
            if(isset($_REQUEST['discounts_aperm'])){
                $discounts_aperm = filter_input(INPUT_POST, "discounts_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $discounts_dperm = $rowr['discounts_dperm'];
            if(isset($_REQUEST['discounts_dperm'])){
                $discounts_dperm = filter_input(INPUT_POST, "discounts_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $warnings_rperm = $rowr['warnings_rperm'];
            if(isset($_REQUEST['warnings_rperm'])){
                $warnings_rperm = filter_input(INPUT_POST, "warnings_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $warnings_aperm = $rowr['warnings_aperm'];
            if(isset($_REQUEST['warnings_aperm'])){
                $warnings_aperm = filter_input(INPUT_POST, "warnings_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $warnings_eperm = $rowr['warnings_eperm'];
            if(isset($_REQUEST['warnings_eperm'])){
                $warnings_eperm = filter_input(INPUT_POST, "warnings_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $warnings_dperm = $rowr['warnings_dperm'];
            if(isset($_REQUEST['warnings_dperm'])){
                $warnings_dperm = filter_input(INPUT_POST, "warnings_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $trainings_rperm = $rowr['trainings_rperm'];
            if(isset($_REQUEST['trainings_rperm'])){
                $trainings_rperm = filter_input(INPUT_POST, "trainings_rperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $trainings_aperm = $rowr['trainings_aperm'];
            if(isset($_REQUEST['trainings_aperm'])){
                $trainings_aperm = filter_input(INPUT_POST, "trainings_aperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $trainings_eperm = $rowr['trainings_eperm'];
            if(isset($_REQUEST['trainings_eperm'])){
                $trainings_eperm = filter_input(INPUT_POST, "trainings_eperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $trainings_dperm = $rowr['trainings_dperm'];
            if(isset($_REQUEST['trainings_dperm'])){
                $trainings_dperm = filter_input(INPUT_POST, "trainings_dperm", FILTER_SANITIZE_NUMBER_INT);
            }
            
            $typecolor_eperm = $rowr['typecolor_eperm'];
            if(isset($_REQUEST['typecolor_eperm'])){
                $typecolor_eperm = filter_input(INPUT_POST, "typecolor_eperm", FILTER_SANITIZE_NUMBER_INT);
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
            
            $stmt_att = $conn->prepare("SELECT * FROM user WHERE id=?");
            $stmt_att->bind_param("i", $id);
            $stmt_att->execute();
            $result_att = $stmt_att->get_result();
            $row_att = $result_att->fetch_assoc();
            $stmt_att->close();
            
            $targetDir = "files_images/employees/$id";
            $upload = $upload2 = $upload3 = $upload4 = 0;
            
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            if(isset($row_att['personal_image'])){
                $personal_image = $row_att['personal_image'];
            } else{
                $personal_image = '';
            }
            if (isset($_FILES['personal_image']) && $_FILES['personal_image']['error'] == 0) {
                $result = secure_file_upload($_FILES['personal_image'], $targetDir);
                if ($result['status']) {
                    $personal_image = $result['path'];
                    echo "personal_image has been uploaded. File size: {$result['size']}<br>";
                    $upload = 1;
                } else {
                    $personal_image = '';
                    echo "Upload error (personal_image): {$result['error']}<br>";
                }
            }
            
            if(isset($row_att['passport_residence'])){
                $passport_residence = $row_att['passport_residence'];
            } else{
                $passport_residence = '';
            }
            if (isset($_FILES['passport_residence']) && $_FILES['passport_residence']['error'] == 0) {
                $result = secure_file_upload($_FILES['passport_residence'], $targetDir);
                if ($result['status']) {
                    $passport_residence = $result['path'];
                    echo "passport_residence has been uploaded. File size: {$result['size']}<br>";
                    $upload2 = 1;
                } else {
                    $passport_residence = '';
                    echo "Upload error (passport_residence): {$result['error']}<br>";
                }
            }
            
            if(isset($row_att['practical_qualification'])){
                $practical_qualification = $row_att['practical_qualification'];
            } else{
                $practical_qualification = '';
            }
            if (isset($_FILES['practical_qualification']) && $_FILES['practical_qualification']['error'] == 0) {
                $result = secure_file_upload($_FILES['practical_qualification'], $targetDir);
                if ($result['status']) {
                    $practical_qualification = $result['path'];
                    echo "practical_qualification has been uploaded. File size: {$result['size']}<br>";
                    $upload3 = 1;
                } else {
                    $practical_qualification = '';
                    echo "Upload error (practical_qualification): {$result['error']}<br>";
                }
            }
            
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
            trainings_eperm=?, trainings_dperm=?, typecolor_eperm=?, representative_exp=?, activation_date=?, opened_by=?, personal_image=?, passport_residence=?, practical_qualification=? WHERE id=?");
            $stmt->bind_param("ssssssssssssssssssssssssssssiississsssssssssssssiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiississsi",
            $name, $username, $nationality, $encrypted_password, $encrypted_tel1, $encrypted_tel2, $encrypted_email, $encrypted_address, $social, $sex, $encrypted_passport_no,
            $residence_no, $card_no, $passport_exp, $residence_date, $app_date, $residence_exp, $contract_exp, $idno, $cardno_exp, $id_exp, $sigorta_exp, $dxblaw_exp,
            $shjlaw_exp, $ajmlaw_exp, $abdlaw_exp, $dob, $app_type, $responsible, $job_title, $section, $work_place, $signin_perm, $emergency_name1, $emergency_relate1, 
            $emergency_tel1, $emergency_name2, $emergency_relate2, $emergency_tel2, $bank_name, $iban, $acc_no, $pay_way, $basic_salary, $travel_tickets, $oil_cost, $housing_cost, 
            $living_cost, $cfiles_rperm, $cfiles_aperm, $cfiles_eperm,
            $cfiles_dperm, $cfiles_archive_perm, $secretf_aperm, $session_rperm, $session_aperm, $session_eperm, $session_dperm, $sessionrole_rperm, $levels_eperm, $degree_rperm, 
            $degree_aperm, $degree_eperm, $degree_dperm, $admjobs_rperm, $admjobs_aperm, $admjobs_eperm, $admjobs_dperm, $admjobs_pperm, $admprivjobs_rperm, $workingtime_rperm, 
            $workingtime_aperm, $workingtime_eperm, $workingtime_dperm, $selectors_rperm, $selectors_aperm, $selectors_eperm, $selectors_dperm, $attachments_dperm, 
            $judicialwarn_rperm, $judicialwarn_aperm, $judicialwarn_eperm, $judicialwarn_dperm, $petition_rperm, $petition_aperm, $petition_eperm, $petition_dperm, $note_rperm, 
            $note_aperm, $note_eperm, $note_dperm, $doc_faperm, $doc_laperm, $clients_rperm, $clients_aperm, $clients_eperm, $clients_dperm, $csched_rperm, $csched_aperm, $csched_eperm,
            $csched_dperm, $cons_rperm, $cons_aperm, $cons_eperm, $cons_dperm, $call_rperm, $call_aperm, $call_eperm, $call_dperm, $goaml_rperm, $goaml_aperm, $goaml_dperm,
            $terror_rperm, $terror_eperm, $terror_dperm, $agr_rperm, $agr_aperm, $agr_eperm, $agr_dperm, $logs_rperm, $logs_dperm, $vacf_aperm, $vacl_aperm, $emp_perms_read,
            $emp_perms_add, $emp_perms_edit, $emp_perms_delete, $rate_rperm, $rate_aperm, $rate_eperm, $rate_dperm, $userattendance_rperm, $userattendance_aperm, $userattendance_eperm,
            $userattendance_dperm, $useratts_eperm, $useratts_dperm, $loggingtimes_eperm, $discounts_rperm, $discounts_aperm, $discounts_dperm, $warnings_rperm, $warnings_aperm, 
            $warnings_eperm, $warnings_dperm, $trainings_rperm, $trainings_aperm, $trainings_eperm, $trainings_dperm, $typecolor_eperm, $representative_exp, $activation_date, $opened_by, 
            $personal_image, $passport_residence, $practical_qualification, $id);
            $stmt->execute();
            $stmt->close();
            
            $respid = $_SESSION['id'];
            $empid = $id;
            
            $stmtnr = $conn->prepare("SELECT * FROM user WHERE id=?");
            $stmtnr->bind_param("i", $respid);
            $stmtnr->execute();
            $resultnr = $stmtnr->get_result();
            $rownr = $resultnr->fetch_assoc();
            $stmtnr->close();
            $respname = $rownr['name'];
            
            $target = "user /-/ $id";
            $target_id = $id;
            $notification = "تم تعديل بيانات حساب المستخدم الخاص بك من قبل $respname";
            $notification_date = date("Y-m-d");
            $status = 0;
            $timestamp = date("Y-m-d H:i:s");
            
            if($empid != 0 && $empid !== ''){
                $stmt = $conn->prepare("INSERT INTO notifications (resp_id, empid, target, target_id, notification, notification_date, status, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iisissis", $respid, $empid, $target, $target_id, $notification, $notification_date, $status, $timestamp);
                $stmt->execute();
                $stmt->close();
            }
            
            header("Location: mbhEmps.php?edit=1&id=$id&editedemp=1");
            exit();
        }
    }
    header("Location: mbhEmps.php");
    exit();
?>