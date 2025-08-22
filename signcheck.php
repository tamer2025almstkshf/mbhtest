<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'errorscheck.php';
    
    if(isset($_REQUEST['signcheck']) && $_REQUEST['signcheck'] !== ''){
        if($row_permcheck['session_aperm'] == 1){
            $sid = filter_input(INPUT_POST, "sid", FILTER_SANITIZE_NUMBER_INT);
            $zero = '0';
            $empty = '';
            $stmts = $conn->prepare("UPDATE session SET resume_appeal=?, resume_overdue=?, resume_daysno=? WHERE session_id=?");
            $stmts->bind_param("iiii", $zero, $zero, $empty, $sid);
            $stmts->execute();
            $stmts->close();
            
            $fid = filter_input(INPUT_POST, "fid", FILTER_SANITIZE_NUMBER_INT);
            $signcheck = filter_input(INPUT_POST, "signcheck", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $timestamp = date("Y-m-d");
            if($signcheck === 'signed'){
                $degree = filter_input(INPUT_POST, "degree", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $case_num = filter_input(INPUT_POST, "case_num", FILTER_SANITIZE_NUMBER_INT);
                $year = filter_input(INPUT_POST, "year", FILTER_SANITIZE_NUMBER_INT);
                $client_characteristic = filter_input(INPUT_POST, "client_characteristic", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $opponent_characteristic = filter_input(INPUT_POST, "opponent_characteristic", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                
                $stmt = $conn->prepare("INSERT INTO file_degrees (fid, degree, case_num, file_year, client_characteristic, opponent_characteristic, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isiisss", $fid, $degree, $case_num, $year, $client_characteristic, $opponent_characteristic, $timestamp);
                $stmt->execute();
                $stmt->close();
            } else if($signcheck === 'unsigned'){
                $note = filter_input(INPUT_POST, "note", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $doneby = $_SESSION['id'];
                
                $stmt = $conn->prepare("INSERT INTO file_note (file_id, notename, note, doneby, timestamp) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issis", $fid, $note, $note, $doneby, $timestamp);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
    header("Location: index.php");
    exit();
?>