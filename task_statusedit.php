<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    if(isset($_REQUEST['endt'])){
        $idddd = filter_input(INPUT_POST, "idddd", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $stmtr = $conn->prepare("SELECT * FROM tasks WHERE id = ?"); 
        $stmtr->bind_param("i", $idddd); 
        $stmtr->execute(); 
        $resultr = $stmtr->get_result(); 
        $row = $resultr->fetch_assoc();
        
        $fid = $row['file_no'];
        $task_status = $row['task_status'];
        
        $flag = '0';
        
        if($task_status !== '2'){
            $flag = '1';
            
            if($task_status === '0'){
                $ts = 'لم يتخذ به اجراء';
            } else if($task_status === '1'){
                $ts = 'جاري العمل عليه';
            } else if($task_status === '2'){
                $ts = 'منتهي';
            }
            
            $action = "تم تغيير حالة العمل الاداري : من $ts الى منتهي<br>رقم الملف : $fid";
        }
        
        $t_note = filter_input(INPUT_POST, "t_note", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $timestamp = date("Y-m-d");
        
        if(isset($t_note) && $t_note !== ''){
            $stmt1 = $conn->prepare("INSERT INTO task_notes (taskid, note, timestamp) VALUES (?, ?, ?)"); 
            $stmt1->bind_param("iss", $idddd, $t_note, $timestamp); 
            $stmt1->execute(); 
            
            $flag = '1';
            
            $action = $action."<br>تم اضافة ملاحظة على المهمة : $t_note";
        }
        
        if($flag === '1'){
            include_once 'addlog.php';
        }
        
        $stmt1 = $conn->prepare("UPDATE tasks SET task_status='2' WHERE id=?"); 
        $stmt1->bind_param("i", $idddd); 
        $stmt1->execute();
        
        $page = filter_input(INPUT_POST, "page", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(isset($page) && $page !== ''){
            header("Location: $page");
        } else{
            header("Location: Tasks.php");
        }
        exit();
    } else if(isset($_REQUEST['inpt'])){
        $idddd = filter_input(INPUT_POST, "idddd", FILTER_VALIDATE_INT);
        
        $stmtr = $conn->prepare("SELECT * FROM tasks WHERE id = ?"); 
        $stmtr->bind_param("i", $idddd); 
        $stmtr->execute(); 
        $resultr = $stmtr->get_result(); 
        $row = $resultr->fetch_assoc();
        
        $fid = $row['file_no'];
        $task_status = $row['task_status'];
        
        $flag = '0';
        if($task_status != 1){
            if($task_status == 0){
                $ts = 'لم يتخذ به اجراء';
            } else if($task_status == 1){
                $ts = 'جاري العمل عليه';
            } else if($task_status == 2){
                $ts = 'منتهي';
            }
            $action = "تم تغيير حالة العمل الاداري : من $ts الى جاري العمل عليه<br>رقم الملف : $fid";
        }
        
        $t_note = filter_input(INPUT_POST, "t_note", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $timestamp = date("Y-m-d");
        
        if(isset($t_note) && $t_note !== ''){
            $stmt1 = $conn->prepare("INSERT INTO task_notes (taskid, note, timestamp) VALUES (?, ?, ?)"); 
            $stmt1->bind_param("iss", $idddd, $t_note, $timestamp); 
            $stmt1->execute(); 
            
            $flag = '1';
            
            $action = $action."<br>تم اضافة ملاحظة على المهمة : $t_note";
        }
        
        if($flag === '1'){
            include_once 'addlog.php';
        }
        
        $stmt1 = $conn->prepare("UPDATE tasks SET task_status='1' WHERE id=?"); 
        $stmt1->bind_param("i", $idddd); 
        $stmt1->execute(); 
        
        $page = filter_input(INPUT_POST, "page", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(isset($page) && $page !== ''){
            header("Location: $page");
        } else{
            header("Location: Tasks.php");
        }
        exit();
    } else if(isset($_REQUEST['submit_re_name'])){
        $re_name = filter_input(INPUT_POST, "re_name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $idddd = filter_input(INPUT_POST, "idddd", FILTER_VALIDATE_INT);
        
        $stmtr = $conn->prepare("SELECT * FROM tasks WHERE id = ?"); 
        $stmtr->bind_param("i", $idddd); 
        $stmtr->execute(); 
        $resultr = $stmtr->get_result(); 
        $row = $resultr->fetch_assoc();
        
        $t_note = filter_input(INPUT_POST, "t_note", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $timestamp = date("Y-m-d");
        
        $stmt1 = $conn->prepare("INSERT INTO task_notes (taskid, note, timestamp) VALUES (?, ?, ?)"); 
        $stmt1->bind_param("iss", $idddd, $t_note, $timestamp); 
        $stmt1->execute(); 
        
        $stmt = $conn->prepare("UPDATE tasks SET employee_id=? WHERE id=?"); 
        $stmt->bind_param("si", $re_name, $idddd); 
        $stmt->execute(); 
        
        $action = '';
        $flag = '0';
        
        $empid = $row['employee_id'];
        $stmtu = $conn->prepare("SELECT * FROM user WHERE id=?");
        $stmtu->bind_param("i", $empid);
        $stmtu->execute();
        $resultu = $stmtu->get_result();
        $rowu = $resultu->fetch_assoc();
        $emp = $rowu['name'];
        if(isset($emp) && $emp !== ''){
            $flag = '1';
            
            $action = "تم تحويل عمل اداري : من $emp الى $re_name<br>";
        }
        
        $fid = $row['file_no'];
        if(isset($fid) && $fid !== ''){
            $action = $action."<br>رقم الملف : $fid";
        }
        
        $jid = $row['task_type'];
        $stmtj = $conn->prepare("SELECT * FROM job_name WHERE id=?");
        $stmtj->bind_param("i", $jid);
        $stmtj->execute();
        $resultj = $stmtj->get_result();
        $rowj = $resultj->fetch_assoc();
        $job = $rowj['job_name'];
        
        $priority = $row['priority'];
        if($priority === '0'){
            $pr = 'عادي';
        } else if($priority === '1'){
            $pr = 'عاجل';
        }
        if(isset($pr) && $pr !== ''){
            $action = $action."<br>اهمية العمل : $pr";
        }
        
        $degid = $row['degree'];
        $stmtd = $conn->prepare("SELECT * FROM file_degrees WHERE id=?");
        $stmtd->bind_param("i", $degid);
        $stmtd->execute();
        $resultd = $stmtd->get_result();
        $rowd = $resultd->fetch_assoc();
        $deg = $rowd['case_num'].$rowd['year'].$rowd['degree'];
        if(isset($deg) && $deg !== ''){
            $action = $action."<br>درجة التقاضي : $deg";
        }
        
        $date = $row['duedate'];
        if(isset($date) && $date !== ''){
            $action = $action."<br>تاريخ التنفيذ : $date";
        }
        
        $note = $row['details'];
        if(isset($note) && $note !== ''){
            $action = $action."<br>التفاصيل : $date";
        }
        
        if($flag === '1'){
            include_once 'addlog.php';
        }
        
        $page = filter_input(INPUT_POST, "page", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(isset($page) && $page !== ''){
            header("Location: $page");
        } else{
            header("Location: Tasks.php");
        }
        exit();
    } else if(isset($_GET['tsknoteid'])){
        $tsknoteid = $_GET['tsknoteid'];
        $fidnotetsk = $_GET['fidnotetsk'];
        
        $fidd = $fidnotetsk;
        
        $stmtr = $conn->prepare("SELECT * FROM task_notes WHERE id=?");
        $stmtr->bind_param("i", $tsknoteid);
        $stmtr->execute();
        $resultr = $stmtr->get_result();
        $rowr = $resultr->fetch_assoc();
        
        $action = "تم حذف الملاحظة من احد مهام الملف رقم : $fidnotetsk";
        
        include_once 'addlog.php';
        
        $stmt = $conn->prepare("DELETE FROM task_notes WHERE id=?");
        $stmt->bind_param("i", $tsknoteid);
        $stmt->execute();
        
        $page = filter_input(INPUT_GET, "page", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(isset($page) && $page !== ''){
            header("Location: $page");
        } else{
            header("Location: Tasks.php");
        }
        exit();
    }
?>