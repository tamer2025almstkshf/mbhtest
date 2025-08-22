<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if(isset($_REQUEST['did']) && $_REQUEST['did'] !== ''){
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
        $did = filter_input(INPUT_POST, 'did', FILTER_SANITIZE_NUMBER_INT);
        
        if($row_permcheck['note_eperm'] == 1){
            $stmtold = $conn->prepare("SELECT * FROM case_document WHERE did=?");
            $stmtold->bind_param("i", $did);
            $stmtold->execute();
            $resultold = $stmtold->get_result();
            $row = $resultold->fetch_assoc();
            $stmtold->close();
            
            $olddate = $row['document_date'];
            $oldsub = $row['document_subject'];
            $olddets = $row['document_details'];
            $oldnotes = $row['document_notes'];
            $not_fid = $row['dfile_no'];
            $not_targetid = $row['id'];
            
            $action = "تم تعديل احد مستندات الدعوى : <br> رقم الملف : $dfile_no";
            $flag = '0';
            
            $dcase_no = filter_input(INPUT_POST, 'dcase_no', FILTER_SANITIZE_NUMBER_INT);
            if(isset($dcase_no) && $dcase_no != 0){
                $stmtcn = $conn->prepare("SELECT * FROM file_degrees WHERE id=?");
                $stmtcn->bind_param("i", $dcase_no);
                $stmtcn->execute();
                $resultcn = $stmtcn->get_result();
                $rowcn = $resultcn->fetch_assoc();
                $stmtcn->close();
                
                $year = $rowcn['file_year'];
                $cn = $rowcn['case_num'];
                $caseno = "$cn / $year";
                
                $action = $action."<br>رقم القضية : $caseno";
            }
            
            $document_subject = filter_input(INPUT_POST, "document_subject", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($document_subject) && $document_subject !== $oldsub){
                $flag = '1';
                
                $action = $action."<br>تم تغيير عنوان المستندات : من $oldsub الى $document_subject";
            }
            
            $document_date = filter_input(INPUT_POST, "note_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($document_date) && $document_date !== $olddate){
                $flag = '1';
                
                $action = $action."<br>تم تغيير التاريخ : من $olddate الى $document_date";
            }
            
            $document_details = filter_input(INPUT_POST, "document_details", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($document_details) && $document_details !== $olddets){
                $flag = '1';
                
                $action = $action."<br>تم تعديل تفاصيل المستندات";
            }
            
            $targetDir = "files_images/case_documents/$dfile_no";
            $upload = 0;
            $upload2 = 0;
            
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $stmt = $conn->prepare("UPDATE case_document SET dfile_no=?, dcase_no=?, document_date=?, document_subject=?, document_details=? WHERE did=?");
            $stmt->bind_param("iisssi", $dfile_no, $dcase_no, $document_date, $document_subject, $document_details, $did);
            $stmt->execute();
            $stmt->close();
            
            if(isset($flag) && $flag === '1'){
                include_once 'addlog.php';
                include_once 'timerfunc.php';
                if($oldnotes !== ''){
                    $not_target = "case_document /-/ $not_fid";
                    $not_notification = "تم تعديل المذكرة في الملف رقم $not_fid";
                    $not_date = date("Y-m-d");
                    $not_status = 0;
                    $not_timestamp = date("Y-m-d H:i:s");
                    echo $not_targetid;exit;
                    
                    $stmt_fnot = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                    $stmt_fnot->bind_param("i", $not_fid);
                    $stmt_fnot->execute();
                    $result_fnot = $stmt_fnot->get_result();
                    if($result_fnot->num_rows > 0){
                        $not_empids = [];
                        $row_fnot = $result_fnot->fetch_assoc();
                        $not_empids[] = $row_fnot['file_secritary'];
                        $not_empids[] = $row_fnot['file_secritary2'];
                        $not_empids[] = $row_fnot['flegal_researcher'];
                        $not_empids[] = $row_fnot['flegal_researcher2'];
                        $not_empids[] = $row_fnot['flegal_advisor'];
                        $not_empids[] = $row_fnot['flegal_advisor2'];
                        $not_empids[] = $row_fnot['file_lawyer'];
                        $not_empids[] = $row_fnot['file_lawyer2'];
                        
                        foreach($not_empids as $empid){
                            $not_respid = $_SESSION['id'];
                            
                            $stmt_checknot2 = $conn->prepare("SELECT * FROM notifications WHERE empid=? AND target=? AND target_id=?");
                            $stmt_checknot2->bind_param("isi", $empid, $not_target, $not_targetid);
                            $stmt_checknot2->execute();
                            $result_checknot2 = $stmt_checknot2->get_result();
                            if($result_checknot2->num_rows == 0 && $empid != 0){
                                $stmtinsnot2 = $conn->prepare("INSERT INTO notifications (resp_id, empid, target, target_id, notification, notification_date, status, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                                $stmtinsnot2->bind_param("iisissis", $not_respid, $empid, $not_target, $not_targetid, $not_notification, $not_date, $not_status, $not_timestamp);
                                $stmtinsnot2->execute();
                                $stmtinsnot2->close();
                            }
                            $stmt_checknot2->close();
                        }
                    }
                    $stmt_fnot->close();
                }
            }
        }
        header("Location: AddNotes.php?fno=$dfile_no&edit=1&id=$did");
        exit();
    }
    header("Location: AddNotes.php?fno=$dfile_no&edit=1&id=$did");
    exit();
?>