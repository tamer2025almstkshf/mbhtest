<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    //judicial_warnings notifications
    $judnot_today = date("Y-m-d");
    $stmt_judnot = $conn->prepare("SELECT * FROM judicial_warnings WHERE duedate<=?");
    $stmt_judnot->bind_param("s", $judnot_today);
    $stmt_judnot->execute();
    $result_judnot = $stmt_judnot->get_result();
    if($result_judnot->num_rows > 0){
        while($row_judnot = $result_judnot->fetch_assoc()){
            $judnot_fid = $row_judnot['fid'];
            $judnot_target = "judicial_warnings /-/ $judnot_fid";
            $judnot_targetid = $row_judnot['id'];
            $judnot_notification = "اعداد لائحة الدعوى على الانذار العدلي في الملف رقم $judnot_fid";
            $judnot_date = date("Y-m-d");
            $judnot_status = 0;
            $judnot_timestamp = date("Y-m-d H:i:s");
            
            $stmt_fjudnot = $conn->prepare("SELECT * FROM file WHERE file_id=?");
            $stmt_fjudnot->bind_param("i", $judnot_fid);
            $stmt_fjudnot->execute();
            $result_fjudnot = $stmt_fjudnot->get_result();
            if($result_fjudnot->num_rows > 0){
                $judnot_empids = [];
                $row_fjudnot = $result_fjudnot->fetch_assoc();
                $judnot_empids[] = $row_fjudnot['file_secritary'];
                $judnot_empids[] = $row_fjudnot['file_secritary2'];
                $judnot_empids[] = $row_fjudnot['flegal_researcher'];
                $judnot_empids[] = $row_fjudnot['flegal_researcher2'];
                $judnot_empids[] = $row_fjudnot['flegal_advisor'];
                $judnot_empids[] = $row_fjudnot['flegal_advisor2'];
                $judnot_empids[] = $row_fjudnot['file_lawyer'];
                $judnot_empids[] = $row_fjudnot['file_lawyer2'];
                
                foreach($judnot_empids as $empid){
                    $judnot_respid = $empid;
                    
                    $stmt_checknot = $conn->prepare("SELECT * FROM notifications WHERE empid=? AND target=? AND target_id=?");
                    $stmt_checknot->bind_param("isi", $empid, $judnot_target, $judnot_targetid);
                    $stmt_checknot->execute();
                    $result_checknot = $stmt_checknot->get_result();
                    if($result_checknot->num_rows == 0 && $empid != 0){
                        $stmtinsnot = $conn->prepare("INSERT INTO notifications (resp_id, empid, target, target_id, notification, notification_date, status, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmtinsnot->bind_param("iisissis", $judnot_respid, $empid, $judnot_target, $judnot_targetid, $judnot_notification, $judnot_date, $judnot_status, $judnot_timestamp);
                        $stmtinsnot->execute();
                        $stmtinsnot->close();
                    }
                    $stmt_checknot->close();
                }
            }
            $stmt_fjudnot->close();
        }
    }
    $stmt_judnot->close();
    
    //petitions notifications
    $petnot_today = date("Y-m-d");
    $stmt_petnot = $conn->prepare("SELECT * FROM petition WHERE (hearing_lastdate != '' AND hearing_lastdate<=?) OR (appeal_lastdate != '' AND appeal_lastdate<=?)");
    $stmt_petnot->bind_param("ss", $petnot_today, $petnot_today);
    $stmt_petnot->execute();
    $result_petnot = $stmt_petnot->get_result();
    if($result_petnot->num_rows > 0){
        while($row_petnot = $result_petnot->fetch_assoc()){
            $petnot_fid = $row_petnot['fid'];
            $petnot_target = "petition /-/ $petnot_fid";
            $petnot_targetid = $row_petnot['id'];
            if($row_petnot['hearing_lastdate'] !== '' && $row_petnot['appeal_lastdate'] === ''){
                $petnot_notification = "اعداد لائحة الدعوى على الامر على عريضة في الملف رقم $petnot_fid";
            } else if($row_petnot['hearing_lastdate'] === '' && $row_petnot['appeal_lastdate'] !== ''){
                $petnot_notification = "اعداد لائحة التظلم على الامر على عريضة في الملف رقم $petnot_fid";
            }
            $petnot_date = date("Y-m-d");
            $petnot_status = 0;
            $petnot_timestamp = date("Y-m-d H:i:s");
            
            $stmt_fpetnot = $conn->prepare("SELECT * FROM file WHERE file_id=?");
            $stmt_fpetnot->bind_param("i", $petnot_fid);
            $stmt_fpetnot->execute();
            $result_fpetnot = $stmt_fpetnot->get_result();
            if($result_fpetnot->num_rows > 0){
                $petnot_empids = [];
                $row_fpetnot = $result_fpetnot->fetch_assoc();
                $petnot_empids[] = $row_fpetnot['file_secritary'];
                $petnot_empids[] = $row_fpetnot['file_secritary2'];
                $petnot_empids[] = $row_fpetnot['flegal_researcher'];
                $petnot_empids[] = $row_fpetnot['flegal_researcher2'];
                $petnot_empids[] = $row_fpetnot['flegal_advisor'];
                $petnot_empids[] = $row_fpetnot['flegal_advisor2'];
                $petnot_empids[] = $row_fpetnot['file_lawyer'];
                $petnot_empids[] = $row_fpetnot['file_lawyer2'];
                
                foreach($petnot_empids as $empid){
                    $petnot_respid = $empid;
                    
                    $stmt_checknot2 = $conn->prepare("SELECT * FROM notifications WHERE empid=? AND target=? AND target_id=?");
                    $stmt_checknot2->bind_param("isi", $empid, $petnot_target, $petnot_targetid);
                    $stmt_checknot2->execute();
                    $result_checknot2 = $stmt_checknot2->get_result();
                    if($result_checknot2->num_rows == 0 && $empid != 0){
                        $stmtinsnot2 = $conn->prepare("INSERT INTO notifications (resp_id, empid, target, target_id, notification, notification_date, status, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmtinsnot2->bind_param("iisissis", $petnot_respid, $empid, $petnot_target, $petnot_targetid, $petnot_notification, $petnot_date, $petnot_status, $petnot_timestamp);
                        $stmtinsnot2->execute();
                        $stmtinsnot2->close();
                    }
                    $stmt_checknot2->close();
                }
            }
            $stmt_fpetnot->close();
        }
    }
    $stmt_petnot->close();
?>