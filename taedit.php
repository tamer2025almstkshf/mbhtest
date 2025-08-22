<?php
    include_once 'connection.php';
    include_once 'login_check.php';
        
    $file_no = stripslashes($_REQUEST['job_fid']);
    $file_no = mysqli_real_escape_string($conn, $file_no);
    
    if(isset($_REQUEST['flegal_researcher']) && $_REQUEST['flegal_researcher'] !== '' && isset($_REQUEST['type_name']) && $_REQUEST['type_name'] !== ''){
        
        $flag = '0';
        $action = "تم التعديل على احد الاعمال الادارية : <br> رقم الملف : $file_no";
        
        $id = stripslashes($_REQUEST['tid']);
        $id = mysqli_real_escape_string($conn, $id);
        
        $queryold = "SELECT * FROM tasks WHERE id='$id'";
        $resultold = mysqli_query($conn, $queryold);
        $rowold = mysqli_fetch_array($resultold);
        
        $oldempid = $rowold['employee_id'];
        $oldtype = $rowold['task_type'];
        $oldpriority = $rowold['priority'];
        $olddeg = $rowold['degree'];
        $olddate = $rowold['duedate'];
        $olddets = $rowold['details'];
        
        $responsible = $_SESSION['id'];
        
        $employee_id = stripslashes($_REQUEST['flegal_researcher']);
        $employee_id = mysqli_real_escape_string($conn, $employee_id);
        if(isset($employee_id) && $employee_id !== $oldempid){
            $flag = '1';
            
            $queryu2 = "SELECT * FROM user WHERE id='$employee_id'";
            $resultu2 = mysqli_query($conn, $queryu2);
            $rowu2 = mysqli_fetch_array($resultu2);
            $employee_name = $rowu2['name'];
            
            $queryuold = "SELECT * FROM user WHERE id='$oldempid'";
            $resultuold = mysqli_query($conn, $queryuold);
            $rowuold = mysqli_fetch_array($resultuold);
            $oldemp = $rowuold['name'];
            
            $action = $action."<br>تم تغيير الموظف المكلف : من $oldemp الى $employee_name";
        }
        
        $task_type = stripslashes($_REQUEST['type_name']);
        $task_type = mysqli_real_escape_string($conn, $task_type);
        if(isset($task_type) && $task_type !== $oldtype){
            $flag = '1';
            
            $queryt = "SELECT * FROM job_name WHERE id='$task_type'";
            $resultt = mysqli_query($conn, $queryt);
            $rowt = mysqli_fetch_array($resultt);
            $task_type2 = $rowt['job_name'];
            
            $queryt = "SELECT * FROM job_name WHERE id='$oldtype'";
            $resultt = mysqli_query($conn, $queryt);
            $rowt = mysqli_fetch_array($resultt);
            $task_type3 = $rowt['job_name'];
            
            $action = $action."<br>تم تغيير نوع العمل : من $task_type2 الى $task_type3";
        }
        
        $priority = stripslashes($_REQUEST['priority']);
        $priority = mysqli_real_escape_string($conn, $priority);
        if(isset($priority) && $priority !== $oldpriority){
            $flag = '1';
            if($priority === '1'){
                $priority1 = 'عادي';
                $priority2 = "عاجل";
            } else{
                $priority1 = 'عاجل';
                $priority2 = "عادي";
            }
            $action = $action."<br>تم تغيير اهمية العمل : من $priority1 الى $priority2";
        }
        
        $degree = stripslashes($_REQUEST['degree_id']);
        $degree = mysqli_real_escape_string($conn, $degree);
        if(isset($degree) && $degree !== $olddeg){
            $flag = '1';
            
            $queryu2 = "SELECT * FROM file_degrees WHERE id='$degree'";
            $resultu2 = mysqli_query($conn, $queryu2);
            $rowu2 = mysqli_fetch_array($resultu2);
            $caseno = $rowu2['case_num'];
            $year = $rowu2['file_year'];
            $degree2 = $rowu2['degree'];
            
            $queryu2 = "SELECT * FROM file_degrees WHERE id='$olddeg'";
            $resultu2 = mysqli_query($conn, $queryu2);
            $rowu2 = mysqli_fetch_array($resultu2);
            $oldcaseno = $rowu2['case_num'];
            $oldyear = $rowu2['file_year'];
            $olddegree2 = $rowu2['degree'];
            
            $action = $action."<br>تم تغيير درجة التقاضي : من $oldcaseno/$oldyear-$olddegree2 الى $caseno/$year-$degree2";
        }
        
        $duedate = stripslashes($_REQUEST['date']);
        $duedate = mysqli_real_escape_string($conn, $duedate);
        if(isset($duedate) && $duedate !== ''){
            list($d, $m, $y) = explode("/", $duedate);
            $duedate = "$y-$m-$d";
        
            if($duedate !== $olddate){
                $flag = '1';
                $action = $action."<br>تم تغيير التاريخ : من $olddate الى $duedate";
            }
        }
        
        $details = stripslashes($_REQUEST['details']);
        $details = mysqli_real_escape_string($conn, $details);
        if(isset($details) && $details !== $olddets){
            $flag = '1';
            $action = $action."<br>تم تغيير التفاصيل : من $olddets الى $details";
        }
        
        $query = "UPDATE tasks SET responsible='$responsible', employee_id='$employee_id', file_no='$file_no', task_type='$task_type', priority='$priority', degree='$degree', duedate='$duedate', details='$details' WHERE id='$id'";
        $result = mysqli_query($conn, $query);
        
        $queryu = "SELECT * FROM user WHERE id='$responsible'";
        $resultu = mysqli_query($conn, $queryu);
        $rowu = mysqli_fetch_array($resultu);
        $name = $rowu['name'];
        
        $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$responsible', '$name', '$action')";
        $resultlog = mysqli_query($conn, $querylog);
        
        header("Location: Business_Management.php?fid=$file_no&tid=$id");
        exit();
    } else{
        if(!isset($_REQUEST['flegal_researcher']) || $_REQUEST['flegal_researcher'] === ''){
        header("Location: Business_Management.php?fid=$file_no&tid=$id&error=lr");
        exit();
        } else if(!isset($_REQUEST['type_name']) || $_REQUEST['type_name'] === ''){
        header("Location: Business_Management.php?fid=$file_no&tid=$id&error=tn");
        exit();
        } else{
        header("Location: Business_Management.php?fid=$file_no&tid=$id&error=unk");
        exit();
        }
    }
?>