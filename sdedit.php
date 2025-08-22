<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    if(isset($_REQUEST['Hearing_dt']) && $_REQUEST['Hearing_dt'] !== ''){
        $session_date = stripslashes($_REQUEST['Hearing_dt']);
        $session_date = mysqli_real_escape_string($conn, $session_date);
        
        list($d, $m, $y) = explode("/", $session_date);
        $session_date = "$y-$m-$d";
        
        $session_id = stripslashes($_REQUEST['session_id']);
        $session_id = mysqli_real_escape_string($conn, $session_id);
        
        $queryr = "SELECT * FROM session WHERE session_id='$session_id'";
        $resultr = mysqli_query($conn, $queryr);
        $rowr = mysqli_fetch_array($resultr);
        $olddate = $rowr['session_date'];
        
        $fid = $rowr['session_fid'];
        $sdegree = $rowd['year'].'/'.$rowd['case_num'].'-'.$rowd['session_degree'];
        
        $flag = '0';
        
        if(isset($session_date) && $session_date !== $olddate){
            $flag = '1';
            
            $action = "تم التعديل على تاريخ الجلسة : من $olddate الى $session_date :<br>رقم الملف : $fid<br>درجة التقاضي : $sdegree";
        }
        
        if(isset($flag) && $flag === '1'){
            $empid = $_SESSION['id'];
            
            $queryu = "SELECT * FROM user WHERE id='$empid'";
            $resultu = mysqli_query($conn, $queryu);
            $rowu = mysqli_fetch_array($resultu);
            $emp_name = $rowu['name'];
            
            $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
            $resultlog = mysqli_query($conn, $querylog);
        }
        
        $created_at = date("Y/m/d H:i:s");
        
        $query = "UPDATE session SET session_date='$session_date', created_at='$created_at' WHERE session_id='$session_id'";
        $result = mysqli_query($conn, $query);
        
        header("Location: session_date.php?id=$session_id");
        exit();
    } else{
        $session_id = stripslashes($_REQUEST['session_id']);
        $session_id = mysqli_real_escape_string($conn, $session_id);
        
        header("Location: session_date.php?id=$session_id&error=hdt");
        exit();
    }
?>