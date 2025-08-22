<?php
    include_once 'connection.php';
    include_once 'login_check.php';
        
    $file_no = stripslashes($_REQUEST['job_fid']);
    $file_no = mysqli_real_escape_string($conn, $file_no);
    
    if(isset($_REQUEST['flegal_researcher']) && $_REQUEST['flegal_researcher'] !== '' && isset($_REQUEST['type_name']) && $_REQUEST['type_name'] !== ''){
        
        $flag = '0';
        $action = "تم اضافة عمل اداري جديد : <br> رقم الملف : $file_no";
        
        $responsible = $_SESSION['id'];
        
        $employee_id = stripslashes($_REQUEST['flegal_researcher']);
        $employee_id = mysqli_real_escape_string($conn, $employee_id);
        if(isset($employee_id) && $employee_id !== '0'){
            $flag = '1';
            
            $queryu2 = "SELECT * FROM user WHERE id='$employee_id'";
            $resultu2 = mysqli_query($conn, $queryu2);
            $rowu2 = mysqli_fetch_array($resultu2);
            $employee_name = $rowu2['name'];
            
            $action = $action."<br>الموظف المكلف : $employee_name";
        }
        
        $task_type = stripslashes($_REQUEST['type_name']);
        $task_type = mysqli_real_escape_string($conn, $task_type);
        if(isset($task_type) && $task_type !== ''){
            $flag = '1';
            
            $queryt = "SELECT * FROM job_name WHERE id='$task_type'";
            $resultt = mysqli_query($conn, $queryt);
            $rowt = mysqli_fetch_array($resultt);
            $task_type2 = $rowt['job_name'];
            
            $action = $action."<br>نوع العمل : $task_type2";
        }
        
        $priority = stripslashes($_REQUEST['priority']);
        $priority = mysqli_real_escape_string($conn, $priority);
        if(isset($priority) && $priority !== ''){
            $flag = '1';
            if($priority === '1'){
                $priority2 = "عاجل";
            } else{
                $priority2 = "عادي";
            }
            $action = $action."<br>اهمية العمل : $priority2";
        }
        
        $degree = stripslashes($_REQUEST['degree_id']);
        $degree = mysqli_real_escape_string($conn, $degree);
        if(isset($degree) && $degree !== ''){
            $flag = '1';
            
            $queryu2 = "SELECT * FROM file_degrees WHERE id='$degree'";
            $resultu2 = mysqli_query($conn, $queryu2);
            $rowu2 = mysqli_fetch_array($resultu2);
            $caseno = $rowu2['case_num'];
            $year = $rowu2['file_year'];
            $degree2 = $rowu2['degree'];
            
            $action = $action."<br>درجة التقاضي : $caseno/$year-$degree2";
        }
        
        $duedate = stripslashes($_REQUEST['date']);
        $duedate = mysqli_real_escape_string($conn, $duedate);
        if(isset($duedate) && $duedate !== ''){
            list($d, $m, $y) = explode("/", $duedate);
            $duedate = "$y-$m-$d";
        
            $flag = '1';
            $action = $action."<br>التاريخ : $duedate";
        }
        
        $details = stripslashes($_REQUEST['details']);
        $details = mysqli_real_escape_string($conn, $details);
        if(isset($details) && $details !== ''){
            $flag = '1';
            $action = $action."<br>التفاصيل : $details";
        }
        
        $timestamp = date("Y-m-d");
        
        $query = "INSERT INTO tasks (responsible, employee_id, file_no, task_type, priority, degree, duedate, details, timestamp, task_status) VALUES ('$responsible', '$employee_id', '$file_no', '$task_type', '$priority', '$degree', '$duedate', '$details', '$timestamp', '')";
        $result = mysqli_query($conn, $query);
        
        $queryu = "SELECT * FROM user WHERE id='$responsible'";
        $resultu = mysqli_query($conn, $queryu);
        $rowu = mysqli_fetch_array($resultu);
        $name = $rowu['name'];
        
        $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$responsible', '$name', '$action')";
        $resultlog = mysqli_query($conn, $querylog);
        
        header("Location: Business_Management.php?fid=$file_no");
        exit();
    } else{
        if(!isset($_REQUEST['flegal_researcher']) || $_REQUEST['flegal_researcher'] === ''){
        header("Location: Business_Management.php?fid=$file_no&error=lr");
        exit();
        } else if(!isset($_REQUEST['type_name']) || $_REQUEST['type_name'] === ''){
        header("Location: Business_Management.php?fid=$file_no&error=tn");
        exit();
        } else{
        header("Location: Business_Management.php?fid=$file_no&error=unk");
        exit();
        }
    }
?>