<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $fid = $_GET['fid'];
    if(isset($_GET['id']) && $_GET['id'] !== ''){
        $id = $_GET['id'];
        
        $queryr = "SELECT * FROM tasks WHERE id='$id'";
        $resultr = mysqli_query($conn, $queryr);
        $rowr = mysqli_fetch_array($resultr);
        
        $currentstatus = $rowr['task_status'];
        if($currentstatus === '0'){
            $oldstatus = 'لم يتخذ به اجراء';
            $newstatus = 'جاري العمل عليه';
            $status = '1';
        } else if($currentstatus === '1'){
            $oldstatus = 'جاري العمل عليه';
            $newstatus = 'تم الانتهاء';
            $status = '2';
        } else if($currentstatus === '2'){
            $oldstatus = 'تم الانتهاء';
            $newstatus = 'لم يتخذ به اجراء';
            $status = '0';
        } 
        
        $action = "تم تعديل حالة العمل الاداري :<br>رقم الملف : $fid<br><br>تم تغيير حالة العمل من $oldstatus الى $newstatus";
        $query = "UPDATE tasks SET task_status='$status' WHERE id='$id'";
        $result = mysqli_query($conn, $query);
        
        $empid = $_SESSION['id'];
        
        $queryu = "SELECT * FROM user WHERE id='$empid'";
        $resultu = mysqli_query($conn, $queryu);
        $rowu = mysqli_fetch_array($resultu);
        $emp_name = $rowu['name'];
        
        $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
        $resultlog = mysqli_query($conn, $querylog);
    }
    header("Location: FileEdit.php?fid=$fid");
    exit();
?>