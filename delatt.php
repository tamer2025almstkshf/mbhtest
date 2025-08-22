<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    if(isset($_GET['file']) && $_GET['file'] !== ''){
        $fid = $_GET['fid'];
        $file = $_GET['file'];
        
        $myid = $_SESSION['id'];
        $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
        $result_permcheck = mysqli_query($conn, $query_permcheck);
        $row_permcheck = mysqli_fetch_array($result_permcheck);
        
        if($row_permcheck['attachments_dperm'] === '1'){
            $query = "SELECT * FROM file WHERE file_id='$fid'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result);
            
            if($row['file_upload1'] === $file){
                $selected = 'file_upload1';
            }
            if($row['file_upload2'] === $file){
                $selected = 'file_upload2';
            }
            if($row['file_upload3'] === $file){
                $selected = 'file_upload3';
            }
            if($row['file_upload4'] === $file){
                $selected = 'file_upload4';
            }
            if($row['file_upload5'] === $file){
                $selected = 'file_upload5';
            }
            if($row['file_upload6'] === $file){
                $selected = 'file_upload6';
            }
            
            $action = "تم حذف مرفق : <br>رقم الملف : $fid";
            
            $empid = $_SESSION['id'];
            
            $queryu = "SELECT * FROM user WHERE id='$empid'";
            $resultu = mysqli_query($conn, $queryu);
            $rowu = mysqli_fetch_array($resultu);
            $emp_name = $rowu['name'];
            
            $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
            $resultlog = mysqli_query($conn, $querylog);
            
            unlink($file);
            
            $querydel = "UPDATE file SET $selected='' WHERE file_id='$fid'";
            $resultdel = mysqli_query($conn, $querydel);
        }
        header("Location: fileidAttachment.php?folder=$file&fid=$fid");
        exit();
    }
    header("Location: fileidAttachment.php?folder=$file&fid=$fid");
    exit();
?>