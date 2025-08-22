<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['call_aperm'] == 1){
        if(isset($_REQUEST['cname']) && $_REQUEST['cname'] !== ''){
            
            $flag = '0';
            $action = "تمت اضافة مكالمة جديدة :<br>";
            
            $queryString = filter_input(INPUT_POST, "queryString", FILTER_UNSAFE_RAW);
            $queryString = html_entity_decode($queryString);
            if (strpos($queryString, 'callsaved') !== false) {
                parse_str($queryString, $queryParams);
                unset($queryParams['callsaved']);
                $queryString = http_build_query($queryParams);
            }
            if (strpos($queryString, 'calledited') !== false) {
                parse_str($queryString, $queryParams);
                unset($queryParams['calledited']);
                $queryString = http_build_query($queryParams);
            }
            
            $branch = filter_input(INPUT_POST, "branch", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($branch) && $branch !== ''){
                $flag = '1';
                
                $action = $action."<br>الفرع : $branch";
            }
            
            $caller_name = filter_input(INPUT_POST, "cname", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($caller_name) && $caller_name !== ''){
                $flag = '1';
                
                $action = $action."<br>اسم المتصل : $caller_name";
            }
            
            $caller_no = filter_input(INPUT_POST, "cno", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($caller_no) && $caller_no !== ''){
                $flag = '1';
                
                $action = $action."<br>رقم المتصل : $caller_no";
            }
            
            $details = filter_input(INPUT_POST, "details", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($details) && $details !== ''){
                $flag = '1';
                
                $action = $action."<br>تفاصيل المكالمة : $details";
            }
            
            $action1 = filter_input(INPUT_POST, "action", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($action1) && $action1 !== ''){
                $flag = '1';
                
                $action = $action."<br>الاجراء : $action1";
            }
            
            $moved_to = filter_input(INPUT_POST, "moved_to", FILTER_SANITIZE_NUMBER_INT);
            if(isset($moved_to) && $moved_to !== ''){
                $flag = '1';
                
                $stmtm = $conn->prepare("SELECT * FROM user WHERE id=?");
                $stmtm->bind_param("i", $moved_to);
                $stmtm->execute();
                $resultm = $stmtm->get_result();
                $rowm = $resultm->fetch_assoc();
                $stmtm->close();
                $moved_toname = $rowm['name'];
                
                $action = $action."<br>تم تحويل المكالمة الى : $moved_toname";
            }
            
            $myid = $_SESSION['id'];
            $timestamp = date("Y-m-d");
            $tmid = "$myid <br> $timestamp";
            
            $stmt = $conn->prepare("INSERT INTO clientsCalls (caller_name, caller_no, details, action, moved_to, branch, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssiss", $caller_name, $caller_no, $details, $action1, $moved_to, $branch, $tmid);
            $stmt->execute();
            $stmt->close();
            
            if(isset($flag) && $flag === '1'){
                include_once 'addlog.php';
            }
            
            $submit_back = filter_input(INPUT_POST, "submit_back", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($submit_back) && $submit_back === 'addmore'){
                header("Location: clientsCalls.php?callsaved=1&addmore=1&$queryString");
                exit();
            } else{
                header("Location: clientsCalls.php?callsaved=1&$queryString");
                exit();
            }
        } else{
            header("Location: clientsCalls.php?$queryString&cname=0");
            exit();
        }
    }
    header("Location: clientsCalls.php?$queryString&error=0");
    exit();
?>