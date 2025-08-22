<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $userid = stripslashes($_REQUEST['userid']);
    $userid = mysqli_real_escape_string($conn, $userid);

    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['emp_dperm'] === '1'){
        if (isset($_POST['CheckedD'])) {
            $CheckedD = $_POST['CheckedD'];
            
            $ids = implode(',', array_map('intval', $CheckedD));
            $idscheck = $ids;
            $idsc = explode(",", $idscheck);
            
            foreach($idsc as $id){
                $flag = '0';
                $action = "تم حذف انذار من ملف الموظف رقم : $userid<br>";
                
                $queryr = "SELECT * FROM warnings WHERE id='$id'";
                $resultr = mysqli_query($conn, $queryr);
                $rowr = mysqli_fetch_array($resultr);
                
                $olddate = $rowr['warning_date'];
                $oldtype = $rowr['warning_type'];
                $oldreason = $rowr['warning_reason'];
                $oldattachment = $rowr['attachments'];
                
                if(isset($olddate) && $olddate !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>تاريخ الانذار : $olddate";
                }
                
                if(isset($oldreason) && $oldreason !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>سبب الانذار : $oldreason";
                }
                
                if(isset($oldtype) && $oldtype !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>نوع الانذار : $oldtype";
                }
                
                if(isset($oldattachment) && $oldattachment !== ''){
                    $flag = '1';
                    unlink($oldattachment);
                    
                    $action = $action."<br>تم حذف المرفق";
                }
                
                $empid = $_SESSION['id'];
                
                $queryu = "SELECT * FROM user WHERE id='$empid'";
                $resultu = mysqli_query($conn, $queryu);
                $rowu = mysqli_fetch_array($resultu);
                $emp_name = $rowu['name'];
                
                if($flag === '1'){
                    $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
                    $resultlog = mysqli_query($conn, $querylog);
                }
            }
            
            $query_del = "DELETE FROM warnings WHERE id IN ($ids)";
        
            if (mysqli_query($conn, $query_del)) {
                header("Location: emp_warns.php?id=$userid");
                exit();
            } else {
                header("Location: emp_warns.php?id=$userid");
                exit();
            }
        } else{
            header("Location: emp_warns.php?id=$userid");
            exit();
        }
    }
    header("Location: clientsCalls.php");
    exit();
?>