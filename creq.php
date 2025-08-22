<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    if(isset($_REQUEST['client_id']) && $_REQUEST['client_id'] !=='' && !isset($_REQUEST['submit_btn']) && !isset($_REQUEST['submit_back'])){
        $client_id = filter_input(INPUT_POST, 'client_id', FILTER_SANITIZE_NUMBER_INT);
        
        $queryString = "client_id=" . $client_id . "&addmore=1";
        
        if(isset($_REQUEST['subject']) && $_REQUEST['subject'] !== ''){
            $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $queryString = $queryString . "&subject=" . $subject;
        }
        
        if(isset($_REQUEST['details']) && $_REQUEST['details'] !== ''){
            $details = filter_input(INPUT_POST, 'details', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $queryString = $queryString . "&details=" . $details;
        }
        
        header("Location: clientsrequests.php?$queryString");
        exit();
    } else if(isset($_REQUEST['client_id']) && $_REQUEST['client_id'] !== '' && isset($_REQUEST['details']) && $_REQUEST['details'] !== '' && 
    (isset($_REQUEST['submit_btn']) || isset($_REQUEST['submit_back']))){
        $client_id = filter_input(INPUT_POST, 'client_id', FILTER_SANITIZE_NUMBER_INT);
        
        $flag = '0';
        $action = "تم تعديل طلب الموكل : <br> رقم الموكل : $client_id<br>";
        
        $file_id = filter_input(INPUT_POST, 'file_id', FILTER_SANITIZE_NUMBER_INT);
        
        if(isset($file_id) && $file_id !== ''){
            $stmtcheck = $conn->prepare("SELECT * FROM file WHERE file_id=?");
            $stmtcheck->bind_param("i", $file_id);
            $stmtcheck->execute();
            $resultcheck = $stmtcheck->get_result();
            if($resultcheck->num_rows == 0){
                header("Location: clientsrequests.php?error=fid");
                exit();
            }
            if(isset($file_id) && $file_id !== ''){
                $flag = '1';
                
                $action = $action."<br>رقم الملف : $file_id";
            }
        }
        
        $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(isset($subject) && $subject !== ''){
            $flag = '1';
            
            $action = $action."<br>عنوان الطلب : $subject";
        }
        
        $details = filter_input(INPUT_POST, 'details', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(isset($details) && $details !== ''){
            $flag = '1';
            
            $action = $action."<br>التفاصيل : $details";
        }
        
        $date = date('Y-m-d');
        if(isset($date) && $date !== ''){
            $flag = '1';
            
            $action = $action."<br>تاريخ الطلب : $date";
        }

        $timestamp = date('Y/m/d');
        $empty = '';
        
        $stmtcheck = $conn->prepare("INSERT INTO clients_requests (client_id, file_id, subject, details, date, reply, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmtcheck->bind_param("iisssss", $client_id, $file_id, $subject, $details, $date, $empty, $timestamp);
        $stmtcheck->execute();
        
        if($flag === '1'){
            include_once 'addlog.php';
        }
        
        $submit_back = filter_input(INPUT_POST, 'submit_back', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if(isset($submit_back) && $submit_back === 'addmore'){
            header("Location: clientsrequests.php?requestsaved=1&cid=$client_id&addmore=1");
            exit();
        } else{
            header("Location: clientsrequests.php?requestsaved=1&cid=$client_id");
            exit();
        }
    } else{
        header("Location: clientsrequests.php?error=0");
        exit();
    }
?>