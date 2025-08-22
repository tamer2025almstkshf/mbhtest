<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'secure_filesfunc.php';
    
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    
    if($row_permcheck['useratts_eperm'] == 1){
        $targetDir = "files_images/user_attachments/$user_id";
        
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $flag = '0';
        $action = "تم التغيير على المرفقات التابعة للموظف رقم $user_id";
        
        $query = '';
        $dtypes = '';
        $variables = '';
        
        $biography = '';
        $biography_by = $_SESSION['id'];
        
        if (isset($_FILES['biography'])) {
            $result = secure_file_upload($_FILES['biography'], $targetDir);
            if ($result['status']) {
                $empty = '';
                $stmtcheck = $conn->prepare("SELECT * FROM user_attachments WHERE user_id=? AND biography=?");
                $stmtcheck->bind_param("is", $user_id, $empty);
                $stmtcheck->execute();
                $resultcheck = $stmtcheck->get_result();
                
                $biography = $result['path'];
                $flag = '1';
                $action = $action."<br>تمت اضافة مرفق السيرة الذاتية";
                
                if($resultcheck->num_rows > 0){
                    $rowcheck = $resultcheck->fetch_assoc();
                    $id = $rowcheck['id'];
                    $stmt = $conn->prepare("UPDATE user_attachments SET biography=?, biography_by=? WHERE id=?");
                    $stmt->bind_param("sii", $biography, $biography_by, $id);
                    $stmt->execute();
                    $stmt->close();
                } else{
                    $stmt = $conn->prepare("INSERT INTO user_attachments (user_id, biography, biography_by) VALUES (?, ?, ?)");
                    $stmt->bind_param("isi", $user_id, $biography, $biography_by);
                    $stmt->execute();
                    $stmt->close();
                }
                $stmtcheck->close();
            }
        }
        
        if (isset($_FILES['passport_residence']) && $_FILES['passport_residence']['error'] == 0) {
            $result = secure_file_upload($_FILES['passport_residence'], $targetDir);
            if ($result['status']) {
                $passport_residence = $result['path'];
                $flag = '1';
                $action = $action."<br>تم تغيير مرفق جواز السفر";
                
                $stmtpass = $conn->prepare("UPDATE user SET passport_residence=? WHERE id=?");
                $stmtpass->bind_param("si", $passport_residence, $user_id);
                $stmtpass->execute();
                $stmtpass->close();
            }
        }
        
        $uaeresidence = '';
        $uaeresidence_by = $_SESSION['id'];
        
        if (isset($_FILES['uaeresidence'])) {
            $result = secure_file_upload($_FILES['uaeresidence'], $targetDir);
            if ($result['status']) {
                $empty = '';
                $stmtcheck = $conn->prepare("SELECT * FROM user_attachments WHERE user_id=? AND uaeresidence=?");
                $stmtcheck->bind_param("is", $user_id, $empty);
                $stmtcheck->execute();
                $resultcheck = $stmtcheck->get_result();
                
                $uaeresidence = $result['path'];
                $flag = '1';
                $action = $action."<br>تمت اضافة مرفق الهوية";
                
                if($resultcheck->num_rows > 0){
                    $rowcheck = $resultcheck->fetch_assoc();
                    $id = $rowcheck['id'];
                    
                    $stmt = $conn->prepare("UPDATE user_attachments SET uaeresidence=?, uaeresidence_by=? WHERE id=?");
                    $stmt->bind_param("sii", $uaeresidence, $uaeresidence_by, $id);
                    $stmt->execute();
                    $stmt->close();
                } else{
                    $stmt = $conn->prepare("INSERT INTO user_attachments (user_id, uaeresidence, uaeresidence_by) VALUES (?, ?, ?)");
                    $stmt->bind_param("isi", $user_id, $uaeresidence, $uaeresidence_by);
                    $stmt->execute();
                    $stmt->close();
                }
                $stmtcheck->close();
            }
        }
        
        $behaviour = '';
        $behaviour_by = $_SESSION['id'];
        
        if (isset($_FILES['behaviour'])) {
            $result = secure_file_upload($_FILES['behaviour'], $targetDir);
            if ($result['status']) {
                $empty = '';
                $stmtcheck = $conn->prepare("SELECT * FROM user_attachments WHERE user_id=? AND behaviour=?");
                $stmtcheck->bind_param("is", $user_id, $empty);
                $stmtcheck->execute();
                $resultcheck = $stmtcheck->get_result();
                
                $behaviour = $result['path'];
                $flag = '1';
                $action = $action."<br>تمت اضافة مرفق حسن السيرة و السلوك";
                
                if($resultcheck->num_rows > 0){
                    $rowcheck = $resultcheck->fetch_assoc();
                    $id = $rowcheck['id'];
                    
                    $stmt = $conn->prepare("UPDATE user_attachments SET behaviour=?, behaviour_by=? WHERE id=?");
                    $stmt->bind_param("sii", $behaviour, $behaviour_by, $id);
                    $stmt->execute();
                    $stmt->close();
                } else{
                    $stmt = $conn->prepare("INSERT INTO user_attachments (user_id, behaviour, behaviour_by) VALUES (?, ?, ?)");
                    $stmt->bind_param("isi", $user_id, $behaviour, $behaviour_by);
                    $stmt->execute();
                    $stmt->close();
                }
                $stmtcheck->close();
            }
        }
        
        $university = '';
        $university_by = $_SESSION['id'];
        
        if (isset($_FILES['university'])) {
            $result = secure_file_upload($_FILES['university'], $targetDir);
            if ($result['status']) {
                $empty = '';
                $stmtcheck = $conn->prepare("SELECT * FROM user_attachments WHERE user_id=? AND university=?");
                $stmtcheck->bind_param("is", $user_id, $empty);
                $stmtcheck->execute();
                $resultcheck = $stmtcheck->get_result();
                
                $university = $result['path'];
                $flag = '1';
                $action = $action."<br>تمت اضافة مرفق الشهادة الجامعية";
                
                if($resultcheck->num_rows > 0){
                    $rowcheck = $resultcheck->fetch_assoc();
                    $id = $rowcheck['id'];
                    
                    $stmt = $conn->prepare("UPDATE user_attachments SET university=?, university_by=? WHERE id=?");
                    $stmt->bind_param("sii", $university, $university_by, $id);
                    $stmt->execute();
                    $stmt->close();
                } else{
                    $stmt = $conn->prepare("INSERT INTO user_attachments (user_id, university, university_by) VALUES (?, ?, ?)");
                    $stmt->bind_param("isi", $user_id, $university, $university_by);
                    $stmt->execute();
                    $stmt->close();
                }
                $stmtcheck->close();
            }
        }
        
        $contract = '';
        $contract_by = $_SESSION['id'];
        
        if (isset($_FILES['contract'])) {
            $result = secure_file_upload($_FILES['contract'], $targetDir);
            if ($result['status']) {
                $empty = '';
                $stmtcheck = $conn->prepare("SELECT * FROM user_attachments WHERE user_id=? AND contract=?");
                $stmtcheck->bind_param("is", $user_id, $empty);
                $stmtcheck->execute();
                $resultcheck = $stmtcheck->get_result();
                
                $contract = $result['path'];
                $flag = '1';
                $action = $action."<br>تمت اضافة مرفق عقد العمل";
                
                if($resultcheck->num_rows > 0){
                    $rowcheck = $resultcheck->fetch_assoc();
                    $id = $rowcheck['id'];
                    
                    $stmt = $conn->prepare("UPDATE user_attachments SET contract=?, contract_by=? WHERE id=?");
                    $stmt->bind_param("sii", $contract, $contract_by, $id);
                    $stmt->execute();
                    $stmt->close();
                } else{
                    $stmt = $conn->prepare("INSERT INTO user_attachments (user_id, contract, contract_by) VALUES (?, ?, ?)");
                    $stmt->bind_param("isi", $user_id, $contract, $contract_by);
                    $stmt->execute();
                    $stmt->close();
                }
                $stmtcheck->close();
            }
        }
        
        $card = '';
        $card_by = $_SESSION['id'];
        
        if (isset($_FILES['card'])) {
            $result = secure_file_upload($_FILES['card'], $targetDir);
            if ($result['status']) {
                $empty = '';
                $stmtcheck = $conn->prepare("SELECT * FROM user_attachments WHERE user_id=? AND contract=?");
                $stmtcheck->bind_param("is", $user_id, $empty);
                $stmtcheck->execute();
                $resultcheck = $stmtcheck->get_result();
                
                $card = $result['path'];
                $flag = '1';
                $action = $action."<br>تمت اضافة مرفق عقد العمل";
                
                if($resultcheck->num_rows > 0){
                    $rowcheck = $resultcheck->fetch_assoc();
                    $id = $rowcheck['id'];
                    
                    $stmt = $conn->prepare("UPDATE user_attachments SET card=?, card_by=? WHERE id=?");
                    $stmt->bind_param("sii", $card, $card_by, $id);
                    $stmt->execute();
                    $stmt->close();
                } else{
                    $stmt = $conn->prepare("INSERT INTO user_attachments (user_id, card, card_by) VALUES (?, ?, ?)");
                    $stmt->bind_param("isi", $user_id, $card, $card_by);
                    $stmt->execute();
                    $stmt->close();
                }
                $stmtcheck->close();
            }
        }
        
        $sigorta = '';
        $sigorta_by = $_SESSION['id'];
        
        if (isset($_FILES['sigorta'])) {
            $result = secure_file_upload($_FILES['sigorta'], $targetDir);
            if ($result['status']) {
                $empty = '';
                $stmtcheck = $conn->prepare("SELECT * FROM user_attachments WHERE user_id=? AND sigorta=?");
                $stmtcheck->bind_param("is", $user_id, $empty);
                $stmtcheck->execute();
                $resultcheck = $stmtcheck->get_result();
                
                $sigorta = $result['path'];
                $flag = '1';
                $action = $action."<br>تمت اضافة مرفق عقد العمل";
                
                if($resultcheck->num_rows > 0){
                    $rowcheck = $resultcheck->fetch_assoc();
                    $id = $rowcheck['id'];
                    
                    $stmt = $conn->prepare("UPDATE user_attachments SET sigorta=?, sigorta_by=? WHERE id=?");
                    $stmt->bind_param("sii", $sigorta, $sigorta_by, $id);
                    $stmt->execute();
                    $stmt->close();
                } else{
                    $stmt = $conn->prepare("INSERT INTO user_attachments (user_id, sigorta, sigorta_by) VALUES (?, ?, ?)");
                    $stmt->bind_param("isi", $user_id, $sigorta, $sigorta_by);
                    $stmt->execute();
                    $stmt->close();
                }
                $stmtcheck->close();
            }
        }
        
        $other = '';
        $other_by = $_SESSION['id'];
        
        if (isset($_FILES['other'])) {
            $result = secure_file_upload($_FILES['other'], $targetDir);
            if ($result['status']) {
                $empty = '';
                $stmtcheck = $conn->prepare("SELECT * FROM user_attachments WHERE user_id=? AND other=?");
                $stmtcheck->bind_param("is", $user_id, $empty);
                $stmtcheck->execute();
                $resultcheck = $stmtcheck->get_result();
                
                $other = $result['path'];
                $flag = '1';
                $action = $action."<br>تمت اضافة مرفق عقد العمل";
                
                if($resultcheck->num_rows > 0){
                    $rowcheck = $resultcheck->fetch_assoc();
                    $id = $rowcheck['id'];
                    
                    $stmt = $conn->prepare("UPDATE user_attachments SET other=?, other_by=? WHERE id=?");
                    $stmt->bind_param("sii", $other, $other_by, $id);
                    $stmt->execute();
                    $stmt->close();
                } else{
                    $stmt = $conn->prepare("INSERT INTO user_attachments (user_id, other, other_by) VALUES (?, ?, ?)");
                    $stmt->bind_param("isi", $user_id, $other, $other_by);
                    $stmt->execute();
                    $stmt->close();
                }
                $stmtcheck->close();
            }
        }
        
        if(isset($flag) && $flag === '1'){
            include_once 'addlog.php';
        }
    }
    header("Location: mbhEmps.php?empid=$user_id&empsection=attachments&action=editattachments");
    exit();
?>