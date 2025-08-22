<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if(isset($_REQUEST['dfile_no']) && $_REQUEST['dfile_no'] !== ''){
        $dfile_no = filter_input(INPUT_POST, 'dfile_no', FILTER_SANITIZE_NUMBER_INT);
        
        $stmtid = $conn->prepare("SELECT * FROM file WHERE file_id=?");
        $stmtid->bind_param("i", $dfile_no);
        $stmtid->execute();
        $resultid = $stmtid->get_result();
        $row_details = $resultid->fetch_assoc();
        $stmtid->close();
        if($admin != 1){
            if($row_details['secret_folder'] == 1){
                $empids = $row_details['secret_emps'];
                $empids = array_filter(array_map('trim', explode(',', $empids)));
                if (!in_array($_SESSION['id'], $empids)) {
                    exit();
                }
            }
        }
        
        if($row_permcheck['note_aperm'] == 1){
            $stmtcdidcheck = $conn->prepare("SELECT * FROM case_document WHERE dfile_no='' AND dcase_no='' AND document_date='' AND document_subject='' AND document_details=''");
            $stmtcdidcheck->execute();
            $resultcdidcheck = $stmtcdidcheck->get_result();
            if($resultcdidcheck->num_rows > 0){
                $rowcdidcheck = $resultcdidcheck->fetch_assoc();
            } else{
                $empty = '';
                $stmt1 = $conn->prepare("INSERT INTO case_document (dfile_no, dcase_no, document_date, document_subject, document_details) VALUES (?, ?, ?, ?, ?)");
                $stmt1->bind_param("sssss", $empty, $empty, $empty, $empty, $empty);
                $stmt1->execute();
                $stmt1->close();
                
                $stmtcdidcheck = $conn->prepare("SELECT * FROM case_document WHERE dfile_no='' AND dcase_no='' AND document_date='' AND document_subject='' AND document_details=''");
                $stmtcdidcheck->execute();
                $resultcdidcheck = $stmtcdidcheck->get_result();
                $rowcdidcheck = $resultcdidcheck->fetch_assoc();
            }
            $stmtcdidcheck->close();
            
            $did = $rowcdidcheck['did'];
            
            $action = "تمت اضافة مستندات دعوى جديدة : <br> رقم الملف : $dfile_no";
            $flag = '0';
            
            $dcase_no = filter_input(INPUT_POST, 'dcase_no', FILTER_SANITIZE_NUMBER_INT);
            if(isset($dcase_no) && $dcase_no != 0){
                $flag = '1';
                
                $stmtcn = $conn->prepare("SELECT * FROM file_degrees WHERE id=?");
                $stmtcn->bind_param("i", $dcase_no);
                $stmtcn->execute();
                $resultcn = $stmtcn->get_result();
                $rowcn = $resultcn->fetch_assoc();
                $stmtcn->close();
                $year = $rowcn['file_year'];
                $cn = $rowcn['case_num'];
                $caseno = "$cn/$year";
                
                $action = $action."<br>رقم القضية : $caseno";
            }
            
            $document_date = filter_input(INPUT_POST, "note_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($document_date) && $document_date !== ''){
                $flag = '1';
                
                $action = $action."<br>التاريخ : $document_date";
            }
            
            $document_subject = filter_input(INPUT_POST, "document_subject", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($document_subject) && $document_subject !== ''){
                $flag = '1';
                
                $action = $action."<br>عنوان المستند : $document_subject";
            }
            
            $document_details = filter_input(INPUT_POST, "document_details", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($document_details) && $document_details !== ''){
                $flag = '1';
                
                $action = $action."<br>تفاصيل المستند : $document_details";
            }
            
            $stmt = $conn->prepare("UPDATE case_document SET dfile_no=?, dcase_no=?, document_date=?, document_subject=?, document_details=? WHERE did=?");
            $stmt->bind_param("iisssi", $dfile_no, $dcase_no, $document_date, $document_subject, $document_details, $did);
            $stmt->execute();
            $stmt->close();
            
            if(isset($flag) && $flag === '1'){
                include_once 'addlog.php';
                include_once 'timerfunc.php';
            }
            
            header("Location: AddNotes.php?fno=$dfile_no&edit=1&id=$did");
            exit();
        }
        header("Location: AddNotes.php?fno=$dfile_no");
        exit();
    } else{
        header("Location: AddNotes.php?error=0&fno=$dfile_no");
        exit();
    }
?>