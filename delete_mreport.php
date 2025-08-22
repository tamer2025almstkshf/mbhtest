<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['csched_eperm'] == 1){
        if(isset($_GET['id']) && $_GET['id'] !== ''){
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            
            $stmtr = $conn->prepare("SELECT * FROM meetings_reports WHERE id=?");
            $stmtr->bind_param("i", $id);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmtr->close();
            
            $name = $rowr['name'];
            $details = $rowr['details'];
            
            $action = 'تم حذف محضر الاجتماع';
            $flag = '0';
            
            if(isset($name) && $name !== ''){
                $action .= "<br>اسم المحضر : $name";
                
                $flag = '1';
            }
            
            if(isset($details) && $details !== ''){
                $action .= "<br>تفاصيل المحضر : $details";
                
                $flag = '1';
            }
            
            $mid = $rowr['meeting_id'];
            
            $stmtm = $conn->prepare("SELECT * FROM events WHERE id=?");
            $stmtm->bind_param("i", $mid);
            $stmtm->execute();
            $resultm = $stmtm->get_result();
            $rowm = $resultm->fetch_assoc();
            $stmtm->close();
            
            $mtitle = $rowm['title'];
            if(isset($mtitle) && $mtitle !== ''){
                $action .= "<br>موضوع الاجتماع : $mtitle";
                
                $flag = '1';
            }
            
            if($flag === '1'){
                include_once 'addlog.php';
            }
            
            $stmt = $conn->prepare("DELETE FROM meetings_reports WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            
            $month = filter_input(INPUT_GET, 'month', FILTER_SANITIZE_NUMBER_INT);
            $year = filter_input(INPUT_GET, 'year', FILTER_SANITIZE_NUMBER_INT);
            
            if(isset($month) && $month !== '' && isset($year) && $year !== ''){
                header("Location: meetings.php?month=$month&year=$year&edit=1");
                exit();
            } else{
                $month = date('m');
                $year = date('Y');
                header("Location: meetings.php?month=$month&year=$year&edit=1");
                exit();
            }
        }
    }
?>