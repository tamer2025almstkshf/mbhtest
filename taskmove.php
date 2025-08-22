<?php
    include_once 'login_check.php';
    include_once 'connection.php';
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if(isset($_REQUEST['delete'])){
        if($row_permcheck['admjobs_dperm'] === '1'){
            $id = stripslashes($_REQUEST['idddd']);
            $id = mysqli_real_escape_string($conn, $id);
            
            $flag = '0';
            
            $queryr1 = "SELECT * FROM tasks WHERE id='$id'";
            $resultr1 = mysqli_query($conn, $queryr1);
            $rowr1 = mysqli_fetch_array($resultr1);
            $re_name1 = $rowr1['employee_id'];
            
            $queryr2 = "SELECT * FROM user WHERE id='$re_name1'";
            $resultr2 = mysqli_query($conn, $queryr2);
            $rowr2 = mysqli_fetch_array($resultr2);
            $old = $rowr2['name'];
            
            $action = "تم حذف عمل اداري : <br><br> الموظف المكلف : $old";
            
            $fid = $rowr1['file_no'];
            if(isset($fid) && $fid !== '0'){
                $flag = '1';
                
                $action = $action."<br>رقم الملف : $fid";
            }
            
            $queryfid = "SELECT * FROM file WHERE file_id='$fid'";
            $resultfid = mysqli_query($conn, $queryfid);
            $rowfid = mysqli_fetch_array($resultfid);
            
            $degree = $rowr1['degree'];
            if(isset($degree) && $degree !== '0'){
                $flag = '1';
                
                $querydeg = "SELECT * FROM file_degrees WHERE id='$degree'";
                $resultdeg = mysqli_query($conn, $querydeg);
                $rowdeg = mysqli_fetch_array($resultdeg);
                $deg = $rowdeg['case_num'].'/'.$rowdeg['file_year'].'-'.$rowdeg['degree'];
                
                $action = $action."<br>درجة التقاضي : $deg";
            }
            
            $casetype = $rowfid['fcase_type'];
            if(isset($casetype) && $casetype !== ''){
                $flag = '1';
                
                $action = $action."<br>نوع القضية : $casetype";
            }
            
            $jtype = $rowr1['jtype'];
            if(isset($jtype) && $jtype !== '0'){
                $flag = '1';
                
                $queryjt = "SELECT * FROM job_name WHERE id='$jtype'";
                $resultjt = mysqli_query($conn, $queryjt);
                $rowjt = mysqli_fetch_array($resultjt);
                $job_name = $rowjt['job_name'];
                
                $action = $action."<br>نوع العمل : $job_name";
            }
            
            $priority = $rowr1['priority'];
            if(isset($priority) && $priority !== ''){
                $flag = '1';
                
                if($priority === '0'){
                    $pt = 'عادي';
                } else{
                    $pt = 'عاجل';
                }
                
                $action = $action."<br>اهمية العمل : $pt";
            }
            
            $duedate = $rowr1['duedate'];
            if(isset($duedate) && $duedate !== ''){
                $flag = '1';
                
                $action = $action."<br>تاريخ تنفيذ العمل : $duedate";
            }
            
            $details = $rowr1['details'];
            if(isset($details) && $details !== ''){
                $flag = '1';
                
                $action = $action."<br>تفاصيل العمل : $details";
            }
            
            $status = $rowr1['task_status'];
            if(isset($status) && $status !== ''){
                $flag = '1';
                
                if($status === '0'){
                    $st = 'لم يتخذ به اجراء';
                } else if($status === '1'){
                    $st = 'جاري العمل عليه';
                } else{
                    $st = 'منتهي';
                }
                
                $action = $action."<br>حالة العمل قبل حذفه : $st";
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
    
            $query = "DELETE FROM tasks WHERE id='$id'";
            $result = mysqli_query($conn, $query);
        }
        header("Location: searchtask.php");
        exit();
    }

    if(isset($_REQUEST['re_name'])){
        $re_name = stripslashes($_REQUEST['re_name']);
        $re_name = mysqli_real_escape_string($conn, $re_name);
        
        $idddd = stripslashes($_REQUEST['idddd']);
        $idddd = mysqli_real_escape_string($conn, $idddd);
        
        $flag = '0';
        
        $queryr = "SELECT * FROM user WHERE id='$re_name'";
        $resultr = mysqli_query($conn, $queryr);
        $rowr = mysqli_fetch_array($resultr);
        $new = $rowr['name'];
        
        $queryr1 = "SELECT * FROM tasks WHERE id='$idddd'";
        $resultr1 = mysqli_query($conn, $queryr1);
        $rowr1 = mysqli_fetch_array($resultr1);
        $re_name1 = $rowr1['employee_id'];
        
        $queryr2 = "SELECT * FROM user WHERE id='$re_name1'";
        $resultr2 = mysqli_query($conn, $queryr2);
        $rowr2 = mysqli_fetch_array($resultr2);
        $old = $rowr2['name'];
        
        $action = "تم تحويل عمل اداري من $old الى $new - تفاصيل العمل :<br>";
        
        $fid = $rowr1['file_no'];
        if(isset($fid) && $fid !== '0'){
            $flag = '1';
            
            $action = $action."<br>رقم الملف : $fid";
        }
        
        $queryfid = "SELECT * FROM file WHERE file_id='$fid'";
        $resultfid = mysqli_query($conn, $queryfid);
        $rowfid = mysqli_fetch_array($resultfid);
        
        $degree = $rowr1['degree'];
        if(isset($degree) && $degree !== '0'){
            $flag = '1';
            
            $querydeg = "SELECT * FROM file_degrees WHERE id='$degree'";
            $resultdeg = mysqli_query($conn, $querydeg);
            $rowdeg = mysqli_fetch_array($resultdeg);
            $deg = $rowdeg['case_num'].'/'.$rowdeg['file_year'].'-'.$rowdeg['degree'];
            
            $action = $action."<br>درجة التقاضي : $deg";
        }
        
        $casetype = $rowfid['fcase_type'];
        if(isset($casetype) && $casetype !== ''){
            $flag = '1';
            
            $action = $action."<br>نوع القضية : $casetype";
        }
        
        $jtype = $rowr1['jtype'];
        if(isset($jtype) && $jtype !== '0'){
            $flag = '1';
            
            $queryjt = "SELECT * FROM job_name WHERE id='$jtype'";
            $resultjt = mysqli_query($conn, $queryjt);
            $rowjt = mysqli_fetch_array($resultjt);
            $job_name = $rowjt['job_name'];
            
            $action = $action."<br>نوع العمل : $job_name";
        }
        
        $priority = $rowr1['priority'];
        if(isset($priority) && $priority !== ''){
            $flag = '1';
            
            if($priority === '0'){
                $pt = 'عادي';
            } else{
                $pt = 'عاجل';
            }
            
            $action = $action."<br>اهمية العمل : $pt";
        }
        
        $duedate = $rowr1['duedate'];
        if(isset($duedate) && $duedate !== ''){
            $flag = '1';
            
            $action = $action."<br>تاريخ تنفيذ العمل : $duedate";
        }
        
        $details = $rowr1['details'];
        if(isset($details) && $details !== ''){
            $flag = '1';
            
            $action = $action."<br>تفاصيل العمل : $details";
        }
        
        $status = $rowr1['task_status'];
        if(isset($status) && $status !== ''){
            $flag = '1';
            
            if($status === '0'){
                $st = 'لم يتخذ به اجراء';
            } else if($status === '1'){
                $st = 'جاري العمل عليه';
            } else{
                $st = 'منتهي';
            }
            
            $action = $action."<br>حالة العمل قبل تحويله : $st";
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
        
        $queryupd = "UPDATE tasks SET employee_id='$re_name' WHERE id='$idddd'";
        $resultupd = mysqli_query($conn, $queryupd);
        
        header("Location: Tasks.php?taskmoved=1");
        exit();
    }

    header("Location: Tasks.php");
    exit();
?>