<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['acccasecost_aperm'] === '1'){
        $fid = stripslashes($_REQUEST['fid']);
        $fid = mysqli_real_escape_string($conn, $fid);
        
        $querycheck = "SELECT * FROM file WHERE file_id='$fid'";
        $resultcheck = mysqli_query($conn, $querycheck);
        
        if($resultcheck->num_rows > 0){
            $flag = '0';
            $action = "تم تعديل اتعاب القضايا للملف رقم : $fid :<br>";
            
            $queryr = "SELECT * FROM cases_fees WHERE fid='$fid'";
            $resultr = mysqli_query($conn, $queryr);
            
            if($resultr->num_rows > 0){
                $rowr = mysqli_fetch_array($resultr);
                
                $oldfees = $rowr['fees'];
                $oldbm_fees = $rowr['bm_fees'];
                $oldbm_alert = $rowr['bm_alert'];
            } else{
                $oldfees = '0';
                $oldbm_fees = '0';
                $oldbm_alert = '0';
            }
            
            $fees = stripslashes($_REQUEST['fees']);
            $fees = mysqli_real_escape_string($conn, $fees);
            if(isset($fees) && $fees !== $oldfees){
                $flag = '1';
                
                $action = $action."<br>تم تعديل قيمة الاتعاب : من $oldfees الى $fees";
            }
            
            $bm_fees = stripslashes($_REQUEST['bm_fees']);
            $bm_fees = mysqli_real_escape_string($conn, $bm_fees);
            if(isset($bm_fees) && $bm_fees !== $oldbm_fees){
                $flag = '1';
                
                $action = $action."<br>تم تعديل قيمة الاعمال الادارية : من $oldbm_fees الى $bm_fees";
            }
            
            $bm_alert = stripslashes($_REQUEST['bm_alert']);
            $bm_alert = mysqli_real_escape_string($conn, $bm_alert);
            if(isset($bm_alert) && $bm_alert !== $oldbm_alert){
                $flag = '1';
                
                $action = $action."<br>تم تعديل قيمة نسبة التنبيه لقيمة الاعمال الادارية للمحاسب : من $oldbm_alert الى $bm_alert";
            }
            
            if($resultr->num_rows > 0){
                $id = $rowr['id'];
                $query = "UPDATE cases_fees SET fid='$fid', fees='$fees', bm_fees='$bm_fees', bm_alert='$bm_alert' WHERE id='$id'";
            } else{
                $query = "INSERT INTO cases_fees (fid, fees, bm_fees, bm_alert) VALUES ('$fid', '$fees', '$bm_fees', '$bm_alert')";
            }
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
            
            $submit_back = stripslashes($_REQUEST['submit_back']);
            $submit_back = mysqli_real_escape_string($conn, $submit_back);
            
            if($submit_back === 'addmore'){
                header("Location: CasesFees.php?savedfees=1&addmore=1");
                exit();
            } else{
                header("Location: CasesFees.php?savedfees=1");
                exit();
            }
        } else{
            header("Location: CasesFees.php?error=0");
            exit();
        }
    }
    header("Location: CasesFees.php");
    exit();
?>