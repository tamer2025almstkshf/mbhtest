<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    
    if(isset($_GET['tid']) && $_GET['tid'] !== ''){
        $id = $_GET['tid'];
        $file_no = $_GET['fid'];
        
        if($row_permcheck['admjobs_dperm'] == 1){
            $flag = '0';
            $action = "تم حذف احد الاعمال الادارية : <br> رقم الملف : $file_no";
            
            $stmtread = $conn->prepare("SELECT * FROM tasks WHERE id=?");
            $stmtread->bind_param("i", $id);
            $stmtread->execute();
            $resultread = $stmtread->get_result();
            $row = $resultread->fetch_assoc();
            
            $oldempid = $row['employee_id'];
            $oldtype = $row['task_type'];
            $oldpriority = $row['priority'];
            $olddeg = $row['degree'];
            $olddate = $row['duedate'];
            $olddets = $row['details'];
            
            if(isset($oldempid) && $oldempid != 0){
                $flag = '1';
                
                $stmtuold = $conn->prepare("SELECT * FROM user WHERE id=?");
                $stmtuold->bind_param("i", $id);
                $stmtuold->execute();
                $resultuold = $stmtuold->get_result();
                $rowuold = $resultuold->fetch_assoc();
                
                $oldemp = $rowuold['name'];
                
                $action = $action."<br>الموظف المكلف : $oldemp";
            }
            
            if(isset($oldtype) && $oldtype !== '0'){
                $flag = '1';
                
                $stmtt = $conn->prepare("SELECT * FROM job_name WHERE id=?");
                $stmtt->bind_param("i", $oldtype);
                $stmtt->execute();
                $resultt = $stmtt->get_result();
                $rowt = $resultt->fetch_assoc();
                $task_type3 = $rowt['job_name'];
                
                $action = $action."<br>العمل الاداري : $task_type3";
            }
            
            if(isset($oldpriority) && $oldpriority !== '0'){
                $flag = '1';
                
                if($oldpriority === '1'){
                    $oldpriority = 'عاجل';
                } else{
                    $oldpriority = 'عادي';
                }
                
                $action = $action."<br>اهمية العمل : $oldpriority";
            }
            
            if(isset($olddeg) && $olddeg !== '0'){
                $flag = '1';
                
                $stmtu2 = $conn->prepare("SELECT * FROM file_degrees WHERE id=?");
                $stmtu2->bind_param("i", $olddeg);
                $stmtu2->execute();
                $resultu2 = $stmtu2->get_result();
                $rowu2 = $resultu2->fetch_assoc();
                
                $oldcaseno = $rowu2['case_num'];
                $oldyear = $rowu2['file_year'];
                $olddegree2 = $rowu2['degree'];
                
                $action = $action."<br>درجة التقاضي : $oldcaseno/$oldyear-$olddegree2";
            }
            
            if(isset($olddate) && $olddate !== ''){
                $flag = '1';
                
                $action = $action."<br>تاريخ تنفيذ العمل : $olddate";
            }
            
            if(isset($olddets) && $olddets !== ''){
                $flag = '1';
                
                $action = $action."<br>التفاصيل : $olddets";
            }
            
            $stmt = $conn->prepare("DELETE FROM tasks WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            if($flag === '1'){
                include_once 'addlog.php';
            }
        }
        header("Location: Tasks.php");
        exit();
    }    
    header("Location: Tasks.php");
    exit();
?>