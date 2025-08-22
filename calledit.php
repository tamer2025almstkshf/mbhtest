<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
    
    if($row_permcheck['call_eperm'] == 1){
        if(isset($_REQUEST['cname']) && $_REQUEST['cname'] !== ''){
            $action = "تم تعديل بيانات مكالمة :<br>";
            $flag = '0';
            
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
            
            $stmtr = $conn->prepare("SELECT * FROM clientsCalls WHERE id=?");
            $stmtr->bind_param("i", $id);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmtr->close();
            
            $oldcaller_name = $rowr['caller_name'];
            $oldcaller_no = $rowr['caller_no'];
            $olddetails = $rowr['details'];
            $oldaction = $rowr['action'];
            $oldmoved_to = $rowr['moved_to'];
            
            $caller_name = filter_input(INPUT_POST, "cname", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($caller_name) && $caller_name !== $oldcaller_name){
                $flag = '1';
                
                $action = $action."<br>تم تغيير اسم المتصل : من $oldcaller_name الى $caller_name";
            }
            
            $caller_no = filter_input(INPUT_POST, "cno", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($caller_no) && $caller_no !== $oldcaller_no){
                $flag = '1';
                
                $action = $action."<br>تم تغيير رقم المتصل : من $oldcaller_no الى $caller_no";
            }
            
            $details = filter_input(INPUT_POST, "details", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($details) && $details !== $olddetails){
                $flag = '1';
                
                $action = $action."<br>تم تغيير تفاصيل المكالمة : من $olddetails الى $details";
            }
            
            $action1 = filter_input(INPUT_POST, "action", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($action1) && $action1 !== $oldaction){
                $flag = '1';
                
                $action = $action."<br>تم تغيير الاجراء : من $oldaction الى $action1";
            }
            
            $moved_to = filter_input(INPUT_POST, "moved_to", FILTER_SANITIZE_NUMBER_INT);
            if(isset($moved_to) && $moved_to !== $oldmoved_to){
                $flag = '1';
                
                $stmtu1 = $conn->prepare("SELECT * FROM user WHERE id=?");
                $stmtu1->bind_param("i", $oldmoved_to);
                $stmtu1->execute();
                $resultu1 = $stmtu1->get_result();
                $rowu1 = $resultu1->fetch_assoc();
                $stmtu1->close();
                $uname1 = $rowu1['name'];
                
                $stmtu1 = $conn->prepare("SELECT * FROM user WHERE id=?");
                $stmtu1->bind_param("i", $moved_to);
                $stmtu1->execute();
                $resultu1 = $stmtu1->get_result();
                $rowu1 = $resultu1->fetch_assoc();
                $stmtu1->close();
                $uname = $rowu1['name'];
                
                $action = $action."<br>تم تغيير مستلم المكالمة : من $uname1 الى $uname";
            }
            
            $stmt = $conn->prepare("UPDATE clientsCalls SET caller_name = ?, caller_no = ?, details = ?, action = ?, moved_to = ? WHERE id=?");
            $stmt->bind_param("ssssii", $caller_name, $caller_no, $details, $action1, $moved_to, $id);
            $stmt->execute();
            $stmt->close();
            
            if(isset($flag) && $flag === '1'){
                include_once 'addlog.php';
            }
            
            $submit_back = filter_input(INPUT_POST, "submit_back", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($submit_back) && $submit_back === 'addmore'){
                header("Location: clientsCalls.php?calledited=1&addmore=1&$queryString");
                exit();
            } else{
                header("Location: clientsCalls.php?calledited=1&$queryString");
                exit();
            }
        } else{
            header("Location: clientsCalls.php?id=$id&cname=0");
            exit();
        }
    }
    header("Location: clientsCalls.php?id=$id&error=0");
    exit();
?>