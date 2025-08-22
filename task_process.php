<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['admjobs_aperm'] == 1){
        $file_id = filter_input(INPUT_POST, 'file_id', FILTER_SANITIZE_NUMBER_INT);
        $type_name = filter_input(INPUT_POST, 'type_name', FILTER_SANITIZE_NUMBER_INT);
        $position_id = filter_input(INPUT_POST, 'position_id', FILTER_SANITIZE_NUMBER_INT);
        $degree_id = filter_input(INPUT_POST, 'degree_id', FILTER_SANITIZE_NUMBER_INT);
        if(!isset($degree_id) || $degree_id === ''){
            $degree_id = 0;
        }
        $busi_priority = filter_input(INPUT_POST, 'busi_priority', FILTER_SANITIZE_NUMBER_INT);
        $busi_date = filter_input(INPUT_POST, "busi_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $busi_notes = filter_input(INPUT_POST, "busi_notes", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $re_name = filter_input(INPUT_POST, 're_name', FILTER_SANITIZE_NUMBER_INT);
        
        if(isset($_REQUEST['save_task_fid']) || isset($_REQUEST['submit_back'])){
            if(isset($_REQUEST['re_name']) && $_REQUEST['re_name'] !== '' && isset($_REQUEST['type_name']) && $_REQUEST['type_name'] !== ''){
                $stmtr = $conn->prepare("SELECT * FROM user WHERE id=?");
                $stmtr->bind_param("i", $re_name);
                $stmtr->execute();
                $resultr = $stmtr->get_result();
                $rowr = $resultr->fetch_assoc();
                $re1_name = $rowr['name'];
                
                $flag = '0';
                $action = "تم تكليف موظف بعمل اداري : <br><br> اسم الموظف المكلف : $re1_name";
                
                if(isset($file_id) && $file_id != 0){
                    $flag = '1';
                    
                    $action = $action."<br>رقم الملف : $file_id";
                }
                
                if(isset($type_name) && $type_name !== ''){
                    $flag = '1';
                    
                    $stmttn = $conn->prepare("SELECT * FROM job_name WHERE id=?");
                    $stmttn->bind_param("i", $type_name);
                    $stmttn->execute();
                    $resulttn = $stmttn->get_result();
                    $rowtn = $resulttn->fetch_assoc();
                    $tn = $rowtn['job_name'];
                    
                    $action = $action."<br>نوع العمل : $tn";
                }
                
                if(isset($degree_id) && $degree_id != 0){
                    $flag = '1';
                    
                    $stmtd = $conn->prepare("SELECT * FROM file_degrees WHERE id=?");
                    $stmtd->bind_param("i", $degree_id);
                    $stmtd->execute();
                    $resultd = $stmtd->get_result();
                    $rowd = $resultd->fetch_assoc();
                    $degreename = $rowd['case_num'].'/'.$rowd['file_year'].'-'.$rowd['degree'];
                    
                    $action = $action."<br>درجة التقاضي : $degreename";
                }
                
                if(isset($busi_priority) && $busi_priority !== ''){
                    $flag = '1';
                    
                    if($busi_priority == 1){
                        $busi1_priority = 'عاجل';
                    } else{
                        $busi1_priority = 'عادي';
                    }
                    
                    $action = $action."<br>اهمية العمل : $busi1_priority";
                }
                
                if(isset($busi_date) && $busi_date !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>تاريخ تنفيذ العمل : $busi_date";
                }
                
                if(isset($busi_notes) && $busi_notes !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>ملاحظات : $busi_notes";
                }
                
                $timestamp = date('Y/m/d H:i:s');
                
                if(isset($flag) && $flag === '1'){
                    include_once 'addlog.php';
                }
                
                $empid = $_SESSION['id'];
                $zero = 0;
                $stmt = $conn->prepare("INSERT INTO tasks (responsible, employee_id, file_no, task_type, priority, duedate, details, task_status, degree, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iiiiissiis", $empid, $re_name, $file_id, $type_name, $busi_priority, $busi_date, $busi_notes, $zero, $degree_id, $timestamp);
                
                if($stmt->execute()){
                    $notifications_empid = $re_name;
                    $notifications_notification = "تم تكليفك بمهمة جديدة";
                }
                
                $submit_back = stripslashes($_REQUEST['submit_back']);
                $submit_back = mysqli_real_escape_string($conn, $submit_back);
                
                if(isset($submit_back) && $submit_back === 'addmore'){
                    header("Location: Tasks.php?tasksaved=1&addmore=1");
                    exit();
                } else{
                    header("Location: Tasks.php?tasksaved=1");
                    exit();
                }
            } else{
                if(!isset($_REQUEST['re_name']) || $_REQUEST['re_name'] === ''){
                    header("Location: Tasks.php?addmore=1&section=4&fno=0&agree=1&pi=$position_id&rn=$re_name&tn=$type_name&bp=$busi_priority&bd=$busi_date&bn=$busi_notes&error=1");
                    exit();
                } else if(!isset($_REQUEST['type_name']) || $_REQUEST['type_name'] === ''){
                    header("Location: Tasks.php?addmore=1&section=4&fno=0&agree=1&pi=$position_id&rn=$re_name&tn=$type_name&bp=$busi_priority&bd=$busi_date&bn=$busi_notes&error=2");
                    exit();
                }
            }
        } else{
            $queryString = '';
            $SearchType = filter_input(INPUT_POST, 'SearchType', FILTER_SANITIZE_NUMBER_INT);
            $queryString = $queryString."addmore=1&section=$SearchType";
            
            if($SearchType == 1){
                if(isset($file_id) && $file_id != 0){
                    $queryString = $queryString."&fno=$file_id";
                }
            } else if($SearchType == 2){
                $Ckind = filter_input(INPUT_POST, 'Ckind', FILTER_SANITIZE_NUMBER_INT);
                if(isset($Ckind) && ($Ckind == 1 || $Ckind == 2)){
                    $queryString = $queryString."&ck=$Ckind";
                }
                $SearchByClient = filter_input(INPUT_POST, 'SearchByClient', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($SearchByClient) && $SearchByClient !== ''){
                    $queryString = $queryString."&cn=$SearchByClient";
                }
            } else if($SearchType == 3){
                $case_no = filter_input(INPUT_POST, 'case_no', FILTER_SANITIZE_NUMBER_INT);
                if(isset($case_no) && $case_no !== '' && $case_no != 0){
                    $queryString = $queryString."&cno=$case_no";
                }
                $case_no_year = filter_input(INPUT_POST, 'case_no_year', FILTER_SANITIZE_NUMBER_INT);
                if(isset($case_no_year) && $case_no_year !== '' && $case_no != 0){
                    $queryString = $queryString."&cy=$case_no_year";
                }
            } else if($SearchType == 4){
                $queryString = $queryString."&agree=1";
            } else{
                header("Location: Tasks.php?addmore=1&error=0");
                exit();
            }
            
            $agree = filter_input(INPUT_POST, 'agree', FILTER_SANITIZE_NUMBER_INT);
            if(isset($agree) && $agree == 1){
                $queryString = $queryString."&agree=$agree";
            }
            
            $position_id = filter_input(INPUT_POST, 'position_id', FILTER_SANITIZE_NUMBER_INT);
            if(isset($position_id) && $position_id !== '' && $position_id != 0){
                $queryString = $queryString."&pi=$position_id";
            }
            
            header("Location: Tasks.php?$queryString");
            exit();
        }
    } else{
        header("Location: Tasks.php?error=0");
        exit();
    }
?>