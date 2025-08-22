<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    $idddd = stripslashes($_REQUEST['idddd']);
    $idddd = mysqli_real_escape_string($conn, $idddd);
    
    $queryr = "SELECT * FROM tasks WHERE id='$idddd'";
    $resultr = mysqli_query($conn, $queryr);
    $row = mysqli_fetch_array($resultr);

    if(isset($_REQUEST['delete'])){
        if($row_permcheck['admjobs_dperm'] === '1'){
            $idddd = stripslashes($_REQUEST['idddd']);
            $idddd = mysqli_real_escape_string($conn, $idddd);
            
            $flag = '0';
            $action = 'تم حذف عمل اداري :<br>';
            
            $empid = $row['employee_id'];
            $queryu = "SELECT * FROM user WHERE id='$empid'";
            $resultu = mysqli_query($conn, $queryu);
            $rowu = mysqli_fetch_array($resultu);
            $emp = $rowu['name'];
            if(isset($emp) && $emp !== ''){
                $flag = '1';
                
                $action = $action."<br>الموظف المكلف : $emp";
            }
            
            $fid = $row['file_no'];
            if(isset($fid) && $fid !== ''){
                $flag = '1';
                
                $action = $action."<br>رقم الملف : $fid";
            }
            
            $jid = $row['task_type'];
            $queryj = "SELECT * FROM job_name WHERE id='$jid'";
            $resultj = mysqli_query($conn, $queryj);
            $rowj = mysqli_fetch_array($resultj);
            $job = $rowj['job_name'];
            
            $priority = $row['priority'];
            if($priority === '0'){
                $pr = 'عادي';
            } else if($priority === '1'){
                $pr = 'عاجل';
            }
            if(isset($pr) && $pr !== ''){
                $flag = '1';
                
                $action = $action."<br>اهمية العمل : $pr";
            }
            
            $degid = $row['degree'];
            $queryd = "SELECT * FROM file_degrees WHERE id='$degid'";
            $resultd = mysqli_query($conn, $queryd);
            $rowd = mysqli_fetch_array($resultd);
            $deg = $rowd['case_num'].$rowd['year'].$rowd['degree'];
            if(isset($deg) && $deg !== ''){
                $flag = '1';
                
                $action = $action."<br>درجة التقاضي : $deg";
            }
            
            $date = $row['duedate'];
            if(isset($date) && $date !== ''){
                $flag = '1';
                
                $action = $action."<br>تاريخ التنفيذ : $date";
            }
            
            $note = $row['details'];
            if(isset($note) && $note !== ''){
                $flag = '1';
                
                $action = $action."<br>التفاصيل : $date";
            }
            
            $query = "DELETE FROM tasks WHERE id='$idddd'";
            $result = mysqli_query($conn, $query);
            
            if($flag === '1'){
                $empid = $_SESSION['id'];
                
                $queryu = "SELECT * FROM user WHERE id='$empid'";
                $resultu = mysqli_query($conn, $queryu);
                $rowu = mysqli_fetch_array($resultu);
                $emp_name = $rowu['name'];
                
                $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
                $resultlog = mysqli_query($conn, $querylog);
            }
        }
        header("Location: mytasks.php");
        exit();
    }

    if(isset($_REQUEST['endt'])){
        $idddd = stripslashes($_REQUEST['idddd']);
        $idddd = mysqli_real_escape_string($conn, $idddd);
        
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
        
        if($flag === '1'){
            $empid = $_SESSION['id'];
            
            $queryu = "SELECT * FROM user WHERE id='$empid'";
            $resultu = mysqli_query($conn, $queryu);
            $rowu = mysqli_fetch_array($resultu);
            $emp_name = $rowu['name'];
            
            $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
            $resultlog = mysqli_query($conn, $querylog);
        }
        
        $query = "UPDATE tasks SET task_status='2' WHERE id='$idddd'";
        $result = mysqli_query($conn, $query);
        
        header("Location: mytasks.php");
        exit();
    }

    if(isset($_REQUEST['inpt'])){
        $idddd = stripslashes($_REQUEST['idddd']);
        $idddd = mysqli_real_escape_string($conn, $idddd);
        
        $fid = $row['file_no'];
        $task_status = $row['task_status'];
        
        $flag = '0';
        
        if($task_status !== '1'){
            $flag = '1';
            
            if($task_status === '0'){
                $ts = 'لم يتخذ به اجراء';
            } else if($task_status === '1'){
                $ts = 'جاري العمل عليه';
            } else if($task_status === '2'){
                $ts = 'منتهي';
            }
            
            $action = "تم تغيير حالة العمل الاداري : من $ts الى جاري العمل عليه<br>رقم الملف : $fid";
        }
        
        if($flag === '1'){
            $empid = $_SESSION['id'];
            
            $queryu = "SELECT * FROM user WHERE id='$empid'";
            $resultu = mysqli_query($conn, $queryu);
            $rowu = mysqli_fetch_array($resultu);
            $emp_name = $rowu['name'];
            
            $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
            $resultlog = mysqli_query($conn, $querylog);
        }

        $query = "UPDATE tasks SET task_status='1' WHERE id='$idddd'";
        $result = mysqli_query($conn, $query);

        header("Location: mytasks.php");
        exit();
    } 
    
    if(isset($_REQUEST['re_name'])){
        $re_name = stripslashes($_REQUEST['re_name']);
        $re_name = mysqli_real_escape_string($conn, $re_name);
        
        $idddd = stripslashes($_REQUEST['idddd']);
        $idddd = mysqli_real_escape_string($conn, $idddd);
        
        $query = "UPDATE tasks SET employee_id='$re_name' WHERE id='$idddd'";
        $result = mysqli_query($conn, $query);
        
        $action = '';
        $flag = '0';
        
        $empid = $row['employee_id'];
        $queryu = "SELECT * FROM user WHERE id='$empid'";
        $resultu = mysqli_query($conn, $queryu);
        $rowu = mysqli_fetch_array($resultu);
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
        $queryj = "SELECT * FROM job_name WHERE id='$jid'";
        $resultj = mysqli_query($conn, $queryj);
        $rowj = mysqli_fetch_array($resultj);
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
        $queryd = "SELECT * FROM file_degrees WHERE id='$degid'";
        $resultd = mysqli_query($conn, $queryd);
        $rowd = mysqli_fetch_array($resultd);
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
            $empid = $_SESSION['id'];
            
            $queryu = "SELECT * FROM user WHERE id='$empid'";
            $resultu = mysqli_query($conn, $queryu);
            $rowu = mysqli_fetch_array($resultu);
            $emp_name = $rowu['name'];
            
            $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
            $resultlog = mysqli_query($conn, $querylog);
        }

        header("Location: mytasks.php");
        exit();
    }

    header("Location: mytasks.php?error=unk");
    exit();
?>