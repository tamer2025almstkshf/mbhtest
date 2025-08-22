<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['accfinance_eperm'] === '1'){
            
            $changed = stripslashes($_REQUEST['changed']);
            $changed = mysqli_real_escape_string($conn, $changed);
            echo $changed.' / '.$_REQUEST['newValue'].'/'.$_REQUEST['cid'];
            exit;
        if(isset($_REQUEST['cid']) && $_REQUEST['cid'] !== '' && isset($_REQUEST['changed']) && $_REQUEST['changed'] !== '' && isset($_REQUEST['newValue'])){
            $cid = stripslashes($_REQUEST['cid']);
            $cid = mysqli_real_escape_string($conn, $cid);
            
            
            $flag = '0';
            $action = "تم تعديل البيانات المالية للموكل :<br>رقم الموكل : $cid";
            
            $changed = stripslashes($_REQUEST['changed']);
            $changed = mysqli_real_escape_string($conn, $changed);
            
            $queryc = "SELECT * FROM finance WHERE cid='$cid'";
            $resultc = mysqli_query($conn, $queryc);
            $insupd = '0';
            $oldchanged = '';
            if($resultc->num_rows > 0){
                $rowc = mysqli_fetch_array($resultc);
                $oldchanged = $rowc["$changed"];
                $insupd = '1';
            }
            
            $newValue = stripslashes($_REQUEST['newValue']);
            $newValue = mysqli_real_escape_string($conn, $newValue);
            if(isset($newValue) && $newValue !== $oldchanged){
                
                $transchanged = '';
                if(isset($changed) && $changed !== ''){
                    $flag = '1';
                    
                    if($changed === 'terms'){
                        $transchanged = 'االشروط';
                    }
                    
                    if($changed === 'agreement_date'){
                        $transchanged = 'تاريخ الاتفاق';
                    }
                    
                    if($changed === 'professional_fees'){
                        $transchanged = 'الرسوم المهنية';
                    }
                    
                    if($changed === 'signing_agreement'){
                        $transchanged = 'خانة (عند التوقيع على الاتفاقية)';
                    }
                    
                    if($changed === 'judge_after'){
                        $transchanged = 'خانة (بعد الحكم)';
                    }
                    
                    if($changed === 'dates_JAN' || $changed === 'dates_FEB' || $changed === 'dates_MAR' || $changed === 'dates_APR' || $changed === 'dates_MAY' || $changed === 'dates_JUN' || $changed === 'dates_JUL' || $changed === 'dates_AUG' || $changed === 'dates_SEP' || $changed === 'dates_OCT' || $changed === 'dates_NOV' || $changed === 'dates_DEC'){
                        $transchanged = 'التواريخ';
                    }
                }
                $action = $action."<br>تم تعديل $transchanged من $oldchanged الى $newValue";
            }
            
            $empid = $_SESSION['id'];
            
            if($insupd === '0'){
                $query = "INSERT INTO finance (cid, $changed, done_by) VALUES ('$cid', '$newValue', '$empid')";
            } else if($insupd === '1'){
                $query = "UPDATE finance SET $changed='$newValue' WHERE cid='$cid'";
            }
            $result = mysqli_query($conn, $query);
            
            if(isset($flag) && $flag === '1'){
                $queryu = "SELECT * FROM user WHERE id='$empid'";
                $resultu = mysqli_query($conn, $queryu);
                $rowu = mysqli_fetch_array($resultu);
                $emp_name = $rowu['name'];
                
                $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
                $resultlog = mysqli_query($conn, $querylog);
            }
        }
    }
    
    if(isset($_REQUEST['scrollPosition']) && $_REQUEST['scrollPosition'] !== ''){
        $scrollPosition = stripslashes($_REQUEST['scrollPosition']);
        $scrollPosition = mysqli_real_escape_string($conn, $scrollPosition);
        
        header("Location: Finance.php?scroll=$scrollPosition");
    } else{
        header("Location: Finance.php");
    }
    exit();
?>