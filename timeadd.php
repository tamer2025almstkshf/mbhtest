<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $page = stripslashes($_REQUEST['page']);
    $page = mysqli_real_escape_string($conn, $page);
    
    if(isset($_REQUEST['emp_name']) && $_REQUEST['emp_name'] !== '' && isset($_REQUEST['fid']) && $_REQUEST['fid'] !== ''
    && isset($_REQUEST['report_action']) && $_REQUEST['report_action'] !== '' && isset($_REQUEST['date']) && $_REQUEST['date'] !== ''
    && isset($_REQUEST['countw']) && $_REQUEST['countw'] !== ''){
        $fid = stripslashes($_REQUEST['fid']);
        $fid = mysqli_real_escape_string($conn, $fid);
        
        $flag = '0';
        $action = "تم ادخال تقرير ساعات عمل للملف رقم : $fid";
        
        $emp_id = $_SESSION['id'];
        $queryemp = "SELECT * FROM user WHERE id='$emp_id'";
        $resultemp = mysqli_query($conn, $queryemp);
        $rowemp = mysqli_fetch_array($resultemp);
        $emp_namecheck = $rowemp['name'];
        
        $emp_name = stripslashes($_REQUEST['emp_name']);
        $emp_name = mysqli_real_escape_string($conn, $emp_name);
        if($emp_name !== $emp_namecheck){
            $emp_name = $emp_namecheck;
        }
        
        if(isset($emp_name) && $emp_name !== ''){
            $flag = '1';
            
            $action = $action."<br>اسم الموظف : $emp_name";
        }
        
        $report_action = stripslashes($_REQUEST['report_action']);
        $report_action = mysqli_real_escape_string($conn, $report_action);
        if(isset($report_action) && $report_action !== ''){
            $flag = '1';
            
            $action = $action."<br>العمل : $report_action";
        }
        
        $date = stripslashes($_REQUEST['date']);
        $date = mysqli_real_escape_string($conn, $date);
        if(isset($date) && $date !== ''){
            $flag = '1';
            
            $action = $action."<br>التاريخ : $date";
        }
        
        $duration = 0;
        $countw = stripslashes($_REQUEST['countw']);
        $countw = mysqli_real_escape_string($conn, $countw);
        if(isset($countw) && $countw !== ''){
            if($countw === '1'){
                $flag = '1';
                $action = $action."<br>كيفية حساب الوقت : تلقائي (عن طريق البرنامج)";
                
                $starting_time = date("Y-m-d H:i:s");
            } else if($countw === '0'){
                $flag = '0';
                $action = $action."<br>كيفية حساب الوقت : ادخال الوقت بشكل يدوي";
                
                $timeHH = stripslashes($_REQUEST['timeHH']);
                $timeHH = mysqli_real_escape_string($conn, $timeHH);
                if($timeHH === ''){
                    $timeHH = '0';
                }
                if($timeHH < 10){
                    $timeHH = '0'.$timeHH;
                }
                
                $timeMM = stripslashes($_REQUEST['timeMM']);
                $timeMM = mysqli_real_escape_string($conn, $timeMM);
                if($timeMM === ''){
                    $timeMM = '0';
                }
                if($timeMM < 10){
                    $timeMM = '0'.$timeMM;
                }
                
                $duration = "$timeHH:$timeMM";
            }
        }
        
        $notes = stripslashes($_REQUEST['notes']);
        $notes = mysqli_real_escape_string($conn, $notes);
        if(isset($notes) && $notes !== ''){
            $flag = '1';
            
            $action = $action."<br>الملاحظات : $notes";
        }
        
        $query = "INSERT INTO working_time (file_id, empid, employee, report, action_date, starting_time, duration, notes) VALUES ('$fid', '$emp_id', '$emp_name', '$report_action', '$date', '$starting_time', '$duration', '$notes')";
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
        
        if($countw === '1'){
            header("Location: $page");
            exit();
        } else if($countw === '0'){
            header("Location: $page");
            exit();
        }
    }
    header("Location: $page&error=0");
    exit();
?>