<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['call_dperm'] == 1){
        if(isset($_GET['id']) && $_GET['id'] !== ''){
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
            $flag = '0';
            $action = "تم حذف مكالمة :<br>";
            
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
            
            if(isset($oldcaller_name) && $oldcaller_name !== ''){
                $flag = '1';
                
                $action = $action."<br>اسم المتصل : $oldcaller_name";
            }
            
            if(isset($oldcaller_no) && $oldcaller_no !== ''){
                $flag = '1';
                
                $action = $action."<br>رقم المتصل : $oldcaller_no";
            }
            
            if(isset($olddetails) && $olddetails !== ''){
                $flag = '1';
                
                $action = $action."<br>تفاصيل المكالمة : $olddetails";
            }
            
            if(isset($oldaction) && $oldaction !== ''){
                $flag = '1';
                
                $action = $action."<br>الاجراء : $oldaction";
            }
            
            if(isset($oldmoved_to) && $oldmoved_to !== ''){
                $flag = '1';
                
                $stmtu1 = $conn->prepare("SELECT * FROM user WHERE id=?");
                $stmtu1->bind_param("i", $oldmoved_to);
                $stmtu1->execute();
                $resultu1 = $stmtu1->get_result();
                $rowu1 = $resultu1->fetch_assoc();
                $stmtu1->close();
                $cname = $rowu1['name'];
                
                $action = $action."<br>تم تحويل المكالمة الى : $cname";
            }
            
            if($flag === '1'){
                include_once 'addlog.php';
            }
            
            $stmt = $conn->prepare("DELETE FROM clientsCalls WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }
    $branch = filter_input(INPUT_GET, "branch", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    header("Location: clientsCalls.php?branch=$branch");
    exit();
?>