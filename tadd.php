<?php
    include_once 'connection.php';
    include_once 'login_check.php';
        
    $file_no = $_POST['job_fid'];

    if(isset($_POST['flegal_researcher']) && $_POST['flegal_researcher'] !== '' && isset($_POST['type_name']) && $_POST['type_name'] !== ''){
        
        $flag = '0';
        $action = "تم اضافة عمل اداري جديد : <br> رقم الملف : $file_no";
        
        $responsible = $_SESSION['id'];
        
        $employee_id = $_POST['flegal_researcher'];
        if(isset($employee_id) && $employee_id !== '0'){
            $flag = '1';
            $stmt = $conn->prepare("SELECT * FROM user WHERE id=?");
            $stmt->bind_param("i", $employee_id);
            $stmt->execute();
            $resultu2 = $stmt->get_result();
            $rowu2 = mysqli_fetch_array($resultu2);
            $employee_name = $rowu2['name'];
            
            $action = $action."<br>الموظف المكلف : $employee_name";
        }
        
        $task_type = $_POST['type_name'];
        if(isset($task_type) && $task_type !== ''){
            $flag = '1';
            $stmt = $conn->prepare("SELECT * FROM job_name WHERE id=?");
            $stmt->bind_param("i", $task_type);
            $stmt->execute();
            $resultt = $stmt->get_result();
            $rowt = mysqli_fetch_array($resultt);
            $task_type2 = $rowt['job_name'];
            
            $action = $action."<br>نوع العمل : $task_type2";
        }
        
        $priority = $_POST['priority'];
        if(isset($priority) && $priority !== ''){
            $flag = '1';
            if($priority === '1'){
                $priority2 = "عاجل";
            } else{
                $priority2 = "عادي";
            }
            $action = $action."<br>اهمية العمل : $priority2";
        }
        
        $degree = $_POST['degree_id'];
        if(isset($degree) && $degree !== ''){
            $flag = '1';
            $stmt = $conn->prepare("SELECT * FROM file_degrees WHERE id=?");
            $stmt->bind_param("i", $degree);
            $stmt->execute();
            $resultu2 = $stmt->get_result();
            $rowu2 = mysqli_fetch_array($resultu2);
            $caseno = $rowu2['case_num'];
            $year = $rowu2['file_year'];
            $degree2 = $rowu2['degree'];
            
            $action = $action."<br>درجة التقاضي : $caseno/$year-$degree2";
        }
        
        $duedate = $_POST['date'];
        if(isset($duedate) && $duedate !== ''){
            list($d, $m, $y) = explode("/", $duedate);
            $duedate = "$y-$m-$d";
        
            $flag = '1';
            $action = $action."<br>التاريخ : $duedate";
        }
        
        $details = $_POST['details'];
        if(isset($details) && $details !== ''){
            $flag = '1';
            $action = $action."<br>التفاصيل : $details";
        }

        $timestamp = date("Y-m-d");
        $task_status = '';
        $stmt = $conn->prepare("INSERT INTO tasks (responsible, employee_id, file_no, task_type, priority, degree, duedate, details, timestamp, task_status) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("iisiiissss", $responsible, $employee_id, $file_no, $task_type, $priority, $degree, $duedate, $details, $timestamp, $task_status);
        $stmt->execute();

        $stmt = $conn->prepare("SELECT * FROM user WHERE id=?");
        $stmt->bind_param("i", $responsible);
        $stmt->execute();
        $resultu = $stmt->get_result();
        $rowu = mysqli_fetch_array($resultu);
        $name = $rowu['name'];

        $stmt = $conn->prepare("INSERT INTO logs (empid, emp_name, action) VALUES (?,?,?)");
        $stmt->bind_param("iss", $responsible, $name, $action);
        $stmt->execute();
        
        header("Location: Business_Management.php?fid=$file_no");
        exit();
    } else{
        if(!isset($_POST['flegal_researcher']) || $_POST['flegal_researcher'] === ''){
        header("Location: Business_Management.php?fid=$file_no&error=lr");
        exit();
        } else if(!isset($_POST['type_name']) || $_POST['type_name'] === ''){
        header("Location: Business_Management.php?fid=$file_no&error=tn");
        exit();
        } else{
        header("Location: Business_Management.php?fid=$file_no&error=unk");
        exit();
        }
    }
?>