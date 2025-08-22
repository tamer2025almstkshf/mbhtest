<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'errorscheck.php';
    
    if($row_permcheck['clients_aperm']){
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $branch = filter_input(INPUT_GET, 'branch', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $one = 1;
        
        $stmt = $conn->prepare("UPDATE consultations SET status=? WHERE id=?");
        $stmt->bind_param("ii", $one, $id);
        $stmt->execute();
        $stmt->close();
        
        $stmtr = $conn->prepare("SELECT * FROM consultations WHERE id=?");
        $stmtr->bind_param("i", $id);
        $stmtr->execute();
        $resultr = $stmtr->get_result();
        $rowr = $resultr->fetch_assoc();
        $stmtr->close();
        
        $arname = $rowr['client_name'];
        $terror = 0;
        $client_kind = 'موكل';
        $client_type = $rowr['client_type'];
        $email = $rowr['email'];
        $tel1 = $rowr['telno'];
        $password = 'randompass';
        $perm = 0;
        $passport = $rowr['passport'];
        $attachments_raw = $rowr['attachment'];
        $attachments_array = explode(' #,.,# ', $attachments_raw);
        
        $attach = [];
        for ($i = 0; $i < 6; $i++) {
            $attach[$i + 1] = isset($attachments_array[$i]) ? trim($attachments_array[$i]) : '';
        }
        
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
        $normalized_arname = normalize_name($arname);
        $stmtcheck = $conn->prepare("SELECT * FROM client WHERE arname = ?");
        $stmtcheck->bind_param("s", $normalized_arname);
        $stmtcheck->execute();
        $resultcheck = $stmtcheck->get_result();
        if($resultcheck->num_rows > 0){
            header("Location: consultations.php?branch=$branch&namerror=1");
            exit();
        } else{
            $empty = '';
            $stmt2 = $conn->prepare("INSERT INTO client (arname, engname, terror, client_kind, client_type, email, tel1, fax, tel2, notes, address, country, idno, password, perm, passport, attach1, attach2, attach3, attach4, attach5, attach6, empid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("ssisssssssssssisssssssi", $arname, $empty, $terror, $client_kind, $client_type, $email, $tel1, $empty, $empty, $empty, $empty, $empty, $empty, $password, $perm, $passport, $attach[1], $attach[2], $attach[3], $attach[4], $attach[5], $attach[6], $_SESSION['id']);
            $stmt2->execute();
            $stmt2->close();
            
            $stmt2 = $conn->prepare("SELECT id FROM client WHERE arname=? AND engname=? AND terror=? AND client_kind=? AND client_type=? AND email=? AND tel1=? AND fax=? AND tel2=? AND notes=? AND address=? AND country=? AND idno=? AND password=? AND perm=? AND passport=? AND attach1=? AND attach2=? AND attach3=? AND attach4=? AND attach5=? AND attach6=? AND empid=?");
            $stmt2->bind_param("ssisssssssssssisssssssi", $arname, $empty, $terror, $client_kind, $client_type, $email, $tel1, $empty, $empty, $empty, $empty, $empty, $empty, $password, $perm, $passport, $attach[1], $attach[2], $attach[3], $attach[4], $attach[5], $attach[6], $_SESSION['id']);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $row2 = $result2->fetch_assoc();
            $clientid = $row2['id'];
            
            $respid = $_SESSION['id'];
            $empid = $_SESSION['id'];
            $target = "consultations /-/ $id";
            $target_id = $clientid;
            $notification = "يرجى اكمال بيانات الموكل رقم $clientid";
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
        
        header("Location: consultations.php?branch=$branch");
        exit();
    }
    header("Location: consultations.php?branch=$branch&error=0");
    exit();
?>