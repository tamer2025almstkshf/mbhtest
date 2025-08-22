<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'AES256.php';
    
    $id = $_SESSION['id'];
    $querymain = "SELECT * FROM user WHERE id='$id'";
    $resultmain = mysqli_query($conn, $querymain);
    $rowmain = mysqli_fetch_array($resultmain);
    
    if(isset($_REQUEST['name']) && $_REQUEST['name'] !== '' && isset($_REQUEST['username']) && $_REQUEST['username'] !== ''
        && isset($_REQUEST['password']) && $_REQUEST['password'] !== '' && isset($_REQUEST['tel1']) && 
        $_REQUEST['tel1'] !== '' && isset($_REQUEST['email']) && $_REQUEST['email'] !== ''&& isset($_REQUEST['passport_no'])
        && $_REQUEST['passport_no'] !== '' && isset($_REQUEST['job_title']) && $_REQUEST['job_title'] !== ''){
        
        $name = stripslashes($_REQUEST['name']);
        $name = mysqli_real_escape_string($conn, $name);
        
        $username = stripslashes($_REQUEST['username']);
        $username = mysqli_real_escape_string($conn, $username);
        
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($conn, $password);
        $password = openssl_encrypt($password, $cipher, $key, $options, $iv);
        
        $email = stripslashes($_REQUEST['email']);
        $email = mysqli_real_escape_string($conn, $email);
        $email = openssl_encrypt($email, $cipher, $key, $options, $iv);
        
        $address = stripslashes($_REQUEST['address']);
        $address = mysqli_real_escape_string($conn, $address);
        $address = openssl_encrypt($address, $cipher, $key, $options, $iv);
        
        $tel1 = stripslashes($_REQUEST['tel1']);
        $tel1 = mysqli_real_escape_string($conn, $tel1);
        $tel1 = openssl_encrypt($tel1, $cipher, $key, $options, $iv);
        
        $tel2 = stripslashes($_REQUEST['tel2']);
        $tel2 = mysqli_real_escape_string($conn, $tel2);
        $tel2 = openssl_encrypt($tel2, $cipher, $key, $options, $iv);
        
        $sex = stripslashes($_REQUEST['sex']);
        $sex = mysqli_real_escape_string($conn, $sex);
        
        $social = stripslashes($_REQUEST['social']);
        $social = mysqli_real_escape_string($conn, $social);
        
        $nationality = stripslashes($_REQUEST['nationality']);
        $nationality = mysqli_real_escape_string($conn, $nationality);
        
        $dob = stripslashes($_REQUEST['dob']);
        $dob = mysqli_real_escape_string($conn, $dob);
        
        $passport_no = stripslashes($_REQUEST['passport_no']);
        $passport_no = mysqli_real_escape_string($conn, $passport_no);
        $passport_no = openssl_encrypt($passport_no, $cipher, $key, $options, $iv);
        
        $passport_exp = stripslashes($_REQUEST['passport_exp']);
        $passport_exp = mysqli_real_escape_string($conn, $passport_exp);
        
        $residence_no = stripslashes($_REQUEST['residence_no']);
        $residence_no = mysqli_real_escape_string($conn, $residence_no);
        
        $residence_date = stripslashes($_REQUEST['residence_date']);
        $residence_date = mysqli_real_escape_string($conn, $residence_date);
        
        $job_title = stripslashes($_REQUEST['job_title']);
        $job_title = mysqli_real_escape_string($conn, $job_title);
        
        $residence_exp = stripslashes($_REQUEST['residence_exp']);
        $residence_exp = mysqli_real_escape_string($conn, $residence_exp);
        
        $targetDir = "files_images/employees/$id";
        
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        if(isset($rowmain['passport_residence'])){
            $passport_residence = $rowmain['passport_residence'];
        } else{
            $passport_residence = '';
        }
        if (isset($_FILES['passport_residence']) && $_FILES['passport_residence']['error'] == 0) {
            $passport_residence = $targetDir . "/" . basename($_FILES['passport_residence']['name']);
            if (move_uploaded_file($_FILES['passport_residence']['tmp_name'], $passport_residence)) {
                $upload = 1;
            } else {
                $upload = 0;
            }
        }
        
        if(isset($rowmain['practical_qualification'])){
            $practical_qualification = $rowmain['practical_qualification'];
        } else{
            $practical_qualification = '';
        }
        if (isset($_FILES['practical_qualification']) && $_FILES['practical_qualification']['error'] == 0) {
            $practical_qualification = $targetDir . "/" . basename($_FILES['practical_qualification']['name']);
            if (move_uploaded_file($_FILES['practical_qualification']['tmp_name'], $practical_qualification)) {
                $upload = 1;
            } else {
                $upload = 0;
            }
        }
        
        $query_att = "SELECT * FROM user_attachments WHERE user_id='$id'";;
        $result_att = mysqli_query($conn, $query_att);
        $row_att = mysqli_fetch_array($result_att);
        
        if (isset($_FILES['biography']) && $_FILES['biography']['error'] == 0) {
            $biography = $targetDir . "/" . basename($_FILES['biography']['name']);
            $biography_by = $_SESSION['id'];
            if (move_uploaded_file($_FILES['biography']['tmp_name'], $biography)) {
                $queryr = "SELECT * FROM user_attachments WHERE user_id='$id' AND biography = ''";
                $resultr = mysqli_query($conn, $queryr);
                if($resultr->num_rows > 0){
                    $rowr = mysqli_fetch_array($resultr);
                    $atts_id = $rowr['id'];
                    
                    $queryat = "UPDATE user_attachments SET biography='$biography', biography_by='$biography_by' WHERE id='$atts_id'";
                    $resultat = mysqli_query($conn, $queryat);
                } else{
                    $query = "INSERT INTO user_attachments (user_id, biography, biography_by, uaeresidence, uaeresidence_by, behaviour, behaviour_by, university, university_by, contract, contract_by, card, card_by, sigorta, sigorta_by, other, other_by) 
                    VALUES ('$id', '$biography', '$biography_by', '', '', '', '', '', '', '', '', '', '', '', '', '', '')";
                    $result = mysqli_query($conn, $query);
                }
            } else {
                $upload = 0;
            }
        }
        
        if (isset($_FILES['uaeresidence']) && $_FILES['uaeresidence']['error'] == 0) {
            $uaeresidence = $targetDir . "/" . basename($_FILES['uaeresidence']['name']);
            $uaeresidence_by = $_SESSION['id'];
            if (move_uploaded_file($_FILES['uaeresidence']['tmp_name'], $uaeresidence)) {
                $queryr = "SELECT * FROM user_attachments WHERE user_id='$id' AND uaeresidence = ''";
                $resultr = mysqli_query($conn, $queryr);
                if($resultr->num_rows > 0){
                    $rowr = mysqli_fetch_array($resultr);
                    $atts_id = $rowr['id'];
                    
                    $queryat = "UPDATE user_attachments SET uaeresidence='$uaeresidence', uaeresidence_by='$uaeresidence_by' WHERE id='$atts_id'";
                    $resultat = mysqli_query($conn, $queryat);
                } else{
                    $query = "INSERT INTO user_attachments (user_id, biography, biography_by, uaeresidence, uaeresidence_by, behaviour, behaviour_by, university, university_by, contract, contract_by, card, card_by, sigorta, sigorta_by, other, other_by) 
                    VALUES ('$id', '', '', '$uaeresidence', '$uaeresidence_by', '', '', '', '', '', '', '', '', '', '', '', '')";
                    $result = mysqli_query($conn, $query);
                }
            } else {
                $upload = 0;
            }
        }
        
        if (isset($_FILES['behaviour']) && $_FILES['behaviour']['error'] == 0) {
            $behaviour = $targetDir . "/" . basename($_FILES['behaviour']['name']);
            $behaviour_by = $_SESSION['id'];
            if (move_uploaded_file($_FILES['behaviour']['tmp_name'], $behaviour)) {
                $queryr = "SELECT * FROM user_attachments WHERE user_id='$id' AND behaviour = ''";
                $resultr = mysqli_query($conn, $queryr);
                if($resultr->num_rows > 0){
                    $rowr = mysqli_fetch_array($resultr);
                    $atts_id = $rowr['id'];
                    
                    $queryat = "UPDATE user_attachments SET behaviour='$behaviour', behaviour_by='$behaviour_by' WHERE id='$atts_id'";
                    $resultat = mysqli_query($conn, $queryat);
                } else{
                    $query = "INSERT INTO user_attachments (user_id, biography, biography_by, uaeresidence, uaeresidence_by, behaviour, behaviour_by, university, university_by, contract, contract_by, card, card_by, sigorta, sigorta_by, other, other_by) 
                    VALUES ('$id', '', '', '', '', '$behaviour', '$behaviour_by', '', '', '', '', '', '', '', '', '', '')";
                    $result = mysqli_query($conn, $query);
                }
            } else {
                $upload = 0;
            }
        }
        
        if (isset($_FILES['university']) && $_FILES['university']['error'] == 0) {
            $university = $targetDir . "/" . basename($_FILES['university']['name']);
            $university_by = $_SESSION['id'];
            if (move_uploaded_file($_FILES['university']['tmp_name'], $university)) {
                $queryr = "SELECT * FROM user_attachments WHERE user_id='$id' AND university = ''";
                $resultr = mysqli_query($conn, $queryr);
                if($resultr->num_rows > 0){
                    $rowr = mysqli_fetch_array($resultr);
                    $atts_id = $rowr['id'];
                    
                    $queryat = "UPDATE user_attachments SET university='$university', university_by='$university_by' WHERE id='$atts_id'";
                    $resultat = mysqli_query($conn, $queryat);
                } else{
                    $query = "INSERT INTO user_attachments (user_id, biography, biography_by, uaeresidence, uaeresidence_by, behaviour, behaviour_by, university, university_by, contract, contract_by, card, card_by, sigorta, sigorta_by, other, other_by) 
                    VALUES ('$id', '', '', '', '', '', '', '$university', '$university_by', '', '', '', '', '', '', '', '')";
                    $result = mysqli_query($conn, $query);
                }
            } else {
                $upload = 0;
            }
        }
        
        if (isset($_FILES['contract']) && $_FILES['contract']['error'] == 0) {
            $contract = $targetDir . "/" . basename($_FILES['contract']['name']);
            $contract_by = $_SESSION['id'];
            if (move_uploaded_file($_FILES['contract']['tmp_name'], $contract)) {
                $queryr = "SELECT * FROM user_attachments WHERE user_id='$id' AND contract = ''";
                $resultr = mysqli_query($conn, $queryr);
                if($resultr->num_rows > 0){
                    $rowr = mysqli_fetch_array($resultr);
                    $atts_id = $rowr['id'];
                    
                    $queryat = "UPDATE user_attachments SET contract='$contract', contract_by='$contract_by' WHERE id='$atts_id'";
                    $resultat = mysqli_query($conn, $queryat);
                } else{
                    $query = "INSERT INTO user_attachments (user_id, biography, biography_by, uaeresidence, uaeresidence_by, behaviour, behaviour_by, university, university_by, contract, contract_by, card, card_by, sigorta, sigorta_by, other, other_by) 
                    VALUES ('$id', '', '', '', '', '', '', '', '', '$contract', '$contract_by', '', '', '', '', '', '')";
                    $result = mysqli_query($conn, $query);
                }
            } else {
                $upload = 0;
            }
        }
        
        if (isset($_FILES['card']) && $_FILES['card']['error'] == 0) {
            $card = $targetDir . "/" . basename($_FILES['card']['name']);
            $card_by = $_SESSION['id'];
            if (move_uploaded_file($_FILES['card']['tmp_name'], $card)) {
                $queryr = "SELECT * FROM user_attachments WHERE user_id='$id' AND card = ''";
                $resultr = mysqli_query($conn, $queryr);
                if($resultr->num_rows > 0){
                    $rowr = mysqli_fetch_array($resultr);
                    $atts_id = $rowr['id'];
                    
                    $queryat = "UPDATE user_attachments SET card='$card', card_by='$card_by' WHERE id='$atts_id'";
                    $resultat = mysqli_query($conn, $queryat);
                } else{
                    $query = "INSERT INTO user_attachments (user_id, biography, biography_by, uaeresidence, uaeresidence_by, behaviour, behaviour_by, university, university_by, contract, contract_by, card, card_by, sigorta, sigorta_by, other, other_by) 
                    VALUES ('$id', '', '', '', '', '', '', '', '', '', '', '$card', '$card_by', '', '', '', '')";
                    $result = mysqli_query($conn, $query);
                }
            } else {
                $upload = 0;
            }
        }
        
        if (isset($_FILES['sigorta']) && $_FILES['sigorta']['error'] == 0) {
            $sigorta = $targetDir . "/" . basename($_FILES['sigorta']['name']);
            $sigorta_by = $_SESSION['id'];
            if (move_uploaded_file($_FILES['sigorta']['tmp_name'], $sigorta)) {
                $queryr = "SELECT * FROM user_attachments WHERE user_id='$id' AND sigorta = ''";
                $resultr = mysqli_query($conn, $queryr);
                if($resultr->num_rows > 0){
                    $rowr = mysqli_fetch_array($resultr);
                    $atts_id = $rowr['id'];
                    
                    $queryat = "UPDATE user_attachments SET sigorta='$sigorta', sigorta_by='$sigorta_by' WHERE id='$atts_id'";
                    $resultat = mysqli_query($conn, $queryat);
                } else{
                    $query = "INSERT INTO user_attachments (user_id, biography, biography_by, uaeresidence, uaeresidence_by, behaviour, behaviour_by, university, university_by, contract, contract_by, card, card_by, sigorta, sigorta_by, other, other_by) 
                    VALUES ('$id', '', '', '', '', '', '', '', '', '', '', '', '', '$sigorta', '$sigorta_by', '', '')";
                    $result = mysqli_query($conn, $query);
                }
            } else {
                $upload = 0;
            }
        }
        
        if (isset($_FILES['other']) && $_FILES['other']['error'] == 0) {
            $other = $targetDir . "/" . basename($_FILES['other']['name']);
            $other_by = $_SESSION['id'];
            if (move_uploaded_file($_FILES['other']['tmp_name'], $other)) {
                $queryr = "SELECT * FROM user_attachments WHERE user_id='$id' AND other = ''";
                $resultr = mysqli_query($conn, $queryr);
                if($resultr->num_rows > 0){
                    $rowr = mysqli_fetch_array($resultr);
                    $atts_id = $rowr['id'];
                    
                    $queryat = "UPDATE user_attachments SET other='$other', other_by='$other_by' WHERE id='$atts_id'";
                    $resultat = mysqli_query($conn, $queryat);
                } else{
                    $query = "INSERT INTO user_attachments (user_id, biography, biography_by, uaeresidence, uaeresidence_by, behaviour, behaviour_by, university, university_by, contract, contract_by, card, card_by, sigorta, sigorta_by, other, other_by) 
                    VALUES ('$id', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '$other', '$other_by')";
                    $result = mysqli_query($conn, $query);
                }
            } else {
                $upload = 0;
            }
        }
        
        if(isset($rowmain['personal_image'])){
            $personal_image = $rowmain['personal_image'];
        } else{
            $personal_image = '';
        }
        if (isset($_FILES['personal_image']) && $_FILES['personal_image']['error'] == 0) {
            $personal_image = $targetDir . "/" . basename($_FILES['personal_image']['name']);
            if (move_uploaded_file($_FILES['personal_image']['tmp_name'], $personal_image)) {
                $upload = 1;
            } else {
                $upload = 0;
            }
        }
        
        $bank_name = stripslashes($_REQUEST['bank_name']);
        $bank_name = mysqli_real_escape_string($conn, $bank_name);
        
        $acc_no = stripslashes($_REQUEST['acc_no']);
        $acc_no = mysqli_real_escape_string($conn, $acc_no);
        
        $iban = stripslashes($_REQUEST['iban']);
        $iban = mysqli_real_escape_string($conn, $iban);
        
        $pay_way = stripslashes($_REQUEST['pay_way']);
        $pay_way = mysqli_real_escape_string($conn, $pay_way);
        
        $emergency_name1 = stripslashes($_REQUEST['emergency_name1']);
        $emergency_name1 = mysqli_real_escape_string($conn, $emergency_name1);
        
        $emergency_relate1 = stripslashes($_REQUEST['emergency_relate1']);
        $emergency_relate1 = mysqli_real_escape_string($conn, $emergency_relate1);
        
        $emergency_tel1 = stripslashes($_REQUEST['emergency_tel1']);
        $emergency_tel1 = mysqli_real_escape_string($conn, $emergency_tel1);
        
        $emergency_name2 = stripslashes($_REQUEST['emergency_name2']);
        $emergency_name2 = mysqli_real_escape_string($conn, $emergency_name2);
        
        $emergency_relate2 = stripslashes($_REQUEST['emergency_relate2']);
        $emergency_relate2 = mysqli_real_escape_string($conn, $emergency_relate2);
        
        $emergency_tel2 = stripslashes($_REQUEST['emergency_tel2']);
        $emergency_tel2 = mysqli_real_escape_string($conn, $emergency_tel2);
        
        $query = "UPDATE user SET name='$name', username='$username', password='$password', email='$email', address='$address', tel1='$tel1', tel2='$tel2', sex='$sex', social='$social', nationality='$nationality', dob='$dob', 
        passport_no='$passport_no', passport_exp='$passport_exp', residence_no='$residence_no', residence_date='$residence_date', job_title='$job_title', residence_exp='$residence_exp', passport_residence='$passport_residence', 
        practical_qualification='$practical_qualification', personal_image='$personal_image', bank_name='$bank_name', acc_no='$acc_no', iban='$iban', pay_way='$pay_way', emergency_name1='$emergency_name1', 
        emergency_relate1='$emergency_relate1', emergency_tel1='$emergency_tel1', emergency_name2='$emergency_name2', emergency_relate2='$emergency_relate2', emergency_tel2='$emergency_tel2' WHERE id='$id'";
        $result = mysqli_query($conn, $query);
        
        header("Location: UserProfile.php?editedprofile=1");
        exit();
    }
    header("Location: UserProfile.php");
    exit();
?>