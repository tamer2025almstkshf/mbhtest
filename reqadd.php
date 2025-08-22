<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $userid = $_SESSION['id'];
    $queryr = "SELECT * FROM vacations WHERE emp_id='$userid'";
    $resultr = mysqli_query($conn, $queryr);
    $rowr = mysqli_fetch_array($resultr);
    
    if(isset($_REQUEST['vtype']) && $_REQUEST['vtype'] !== ''){
        if($rowr['ask'] === '1'){
            header("Location: vacationReq.php?req=1");
            exit();
        }
        
        $emp_id = $_SESSION['id'];
        
        $queryu = "SELECT * FROM user WHERE id='$emp_id'";
        $resultu = mysqli_query($conn, $queryu);
        $rowu = mysqli_fetch_array($resultu);
        $emp_name = $rowu['name'];
        
        $flag = '0';
        $action = "تم تقديم طلب اجازة : <br> اسم الموظف : $emp_name<br>";
        
        $type = stripslashes($_REQUEST['vtype']);
        $type = mysqli_real_escape_string($conn, $type);
        if(isset($type) && $type !== ''){
            $flag = '1';
            
            $action = $action."<br>نوع الطلب : $type";
        }
        
        $starting_date = stripslashes($_REQUEST['starting_date']);
        $starting_date = mysqli_real_escape_string($conn, $starting_date);
        if(isset($starting_date) && $starting_date !== ''){
            $flag = '1';
            
            $action = $action."<br>من تاريخ : $starting_date";
        }
        
        $ending_date = stripslashes($_REQUEST['ending_date']);
        $ending_date = mysqli_real_escape_string($conn, $ending_date);
        if(isset($ending_date) && $ending_date !== ''){
            $flag = '1';
            
            $action = $action."<br>الى تاريخ : $ending_date";
        }
        
        $notes = stripslashes($_REQUEST['notes']);
        $notes = mysqli_real_escape_string($conn, $notes);
        if(isset($notes) && $notes !== ''){
            $flag = '1';
            
            $action = $action."<br>ملاحظات : $notes";
        }
        
        $ask = '1';
        if(isset($ask) && $ask === '1'){
            $flag = '1';
            
            $action = $action."<br>موافقة الموارد البشرية : في الانتظار";
        }
        
        $ask2 = '0';
        if(isset($ask2) && $ask2 === '1'){
            $flag = '1';
            
            $action = $action."<br>موافقة المدير : في الانتظار";
        }
        
        $timestamp = date('Y-m-d');
        
        $query = "INSERT INTO vacations (emp_id, type, ask_date, starting_date, ending_date, notes, ask, ask2, timestamp) VALUES ('$emp_id', '$type', '$timestamp', '$starting_date', '$ending_date', '$notes', '$ask', '$ask2', '$timestamp')";
        $result = mysqli_query($conn, $query);
        
        if($flag === '1'){
            $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
            $resultlog = mysqli_query($conn, $querylog);
        }
        
        header("Location: vacationReq.php");
        exit();
    } else{
        header("Location: vacationReq.php?vtype=0");
        exit();
    }
?>