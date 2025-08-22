<?php
    include_once 'connection.php';
    include_once 'login_check.php';

    $session_id = stripslashes($_REQUEST['session_id']);
    $session_id = mysqli_real_escape_string($conn, $session_id);
    
    $queryc = "SELECT * FROM session WHERE session_id='$session_id'";
    $resultc = mysqli_query($conn, $queryc);
    $rowc = mysqli_fetch_array($resultc);
    
    $fid = $rowc['session_fid'];
    $sdate = $rowc['session_date'];
    $session_degree = $rowc['session_degree'];
    $case_num = $rowc['case_num'];
    $year = $rowc['year'];
    $sdegree = "$year/$case_num-$session_degree";

    if(isset($_REQUEST['session_notes']) && $_REQUEST['session_notes'] !== ''){

        $session_notes = stripslashes($_REQUEST['session_notes']);
        $session_notes = mysqli_real_escape_string($conn, $session_notes);
        
        $query = "UPDATE session SET session_note='$session_notes' WHERE session_id='$session_id'";
        $result = mysqli_query($conn, $query);
        
        $action = "تم اضافة ملاحظة على الجلسة :<br>رقم الملف : $fid<br><br>تاريخ الجلسة : $sdate<br>درجة التقاضي : $sdegree<br>الملاحظة : $session_notes";
        
        $empid = $_SESSION['id'];
        
        $queryu = "SELECT * FROM user WHERE id='$empid'";
        $resultu = mysqli_query($conn, $queryu);
        $rowu = mysqli_fetch_array($resultu);
        $emp_name = $rowu['name'];
        
        $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
        $resultlog = mysqli_query($conn, $querylog);
    } 
    
    if(isset($_REQUEST['session_decission']) && $_REQUEST['session_decission'] !== ''){

        $session_decission = stripslashes($_REQUEST['session_decission']);
        $session_decission = mysqli_real_escape_string($conn, $session_decission);

        $query = "UPDATE session SET session_trial='$session_decission' WHERE session_id='$session_id'";
        $result = mysqli_query($conn, $query);
        
        $action = "تم اضافة قرار على الجلسة :<br>رقم الملف : $fid<br><br>تاريخ الجلسة : $sdate<br>درجة التقاضي : $sdegree<br>القرار : $session_decission";
        
        $empid = $_SESSION['id'];
        
        $queryu = "SELECT * FROM user WHERE id='$empid'";
        $resultu = mysqli_query($conn, $queryu);
        $rowu = mysqli_fetch_array($resultu);
        $emp_name = $rowu['name'];
        
        $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
        $resultlog = mysqli_query($conn, $querylog);
    }

    header("Location: session_nd.php?id=$session_id");
    exit();
?>