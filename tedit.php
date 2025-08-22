<?php
    include_once 'login_check.php';
    include_once 'connection.php';
    include_once 'permissions_check.php';

    if(isset($_REQUEST['re_name']) && $_REQUEST['re_name'] !== '' && isset($_REQUEST['type_name']) && $_REQUEST['type_name'] !== ''){
        if($row_permcheck['admjobs_eperm'] == 1){
            $tid = filter_input(INPUT_POST, 'tid', FILTER_SANITIZE_NUMBER_INT);
            
            $stmtt = $conn->prepare("SELECT * FROM tasks WHERE id=?");
            $stmtt->bind_param("i", $tid);
            $stmtt->execute();
            $resultt = $stmtt->get_result();
            $rowt = $resultt->fetch_assoc();
            
            $oldfid = $rowt['file_no'];
            $oldttypeid = $rowt['task_type'];
            $olddegree_id = $rowt['degree'];
            $oldpriority = $rowt['priority'];
            $olddate = $rowt['duedate'];
            $oldnotes = $rowt['details'];
            $oldemp = $rowt['employee_id'];
            
            $action = "تم التعديل على احد الاعمال الادارية :<br>رقم الملف : $oldfid<br>";
            $flag = '0';
            
            $re_name = filter_input(INPUT_POST, 're_name', FILTER_SANITIZE_NUMBER_INT);
            if(isset($re_name) && $re_name != $oldemp){
                $flag = '1';
                
                $stmtjn = $conn->prepare("SELECT * FROM user WHERE id=?");
                $stmtjn->bind_param("i", $re_name);
                $stmtjn->execute();
                $resultjn = $stmtjn->get_result();
                $rowjn = $resultjn->fetch_assoc();
                $empname = $rowjn['name'];
                
                $stmtjn = $conn->prepare("SELECT * FROM user WHERE id=?");
                $stmtjn->bind_param("i", $oldemp);
                $stmtjn->execute();
                $resultjn = $stmtjn->get_result();
                $rowjn = $resultjn->fetch_assoc();
                $oldname = $rowjn['name'];
                
                $action = $action."<br>تم تغيير الموظف المكلف : من $oldname الى $empname";
            }
            
            $type_name = filter_input(INPUT_POST, 'type_name', FILTER_SANITIZE_NUMBER_INT);
            if(isset($type_name) && $type_name != $oldttypeid){
                $flag = '1';
                
                $stmtjn = $conn->prepare("SELECT * FROM job_name WHERE id=?");
                $stmtjn->bind_param("i", $type_name);
                $stmtjn->execute();
                $resultjn = $stmtjn->get_result();
                $rowjn = $resultjn->fetch_assoc();
                $jobname = $rowjn['job_name'];
                
                $stmtjn = $conn->prepare("SELECT * FROM job_name WHERE id=?");
                $stmtjn->bind_param("i", $oldttypeid);
                $stmtjn->execute();
                $resultjn = $stmtjn->get_result();
                $rowjn = $resultjn->fetch_assoc();
                $oldjobname = $rowjn['job_name'];
                
                $action = $action."<br>تم تغيير نوع العمل : من $oldjobname الى $jobname";
            }
            
            $degree_id = filter_input(INPUT_POST, 'degree_id', FILTER_SANITIZE_NUMBER_INT);
            if(isset($degree_id) && $degree_id != $olddegree_id){
                $flag = '1';
                
                $stmtjn = $conn->prepare("SELECT * FROM file_degrees WHERE id=?");
                $stmtjn->bind_param("i", $degree_id);
                $stmtjn->execute();
                $resultjn = $stmtjn->get_result();
                $rowjn = $resultjn->fetch_assoc();
                $cno = $rowjn['case_num'];
                $year = $rowjn['file_year'];
                $deg = $rowjn['degree'];
                
                $stmtjn = $conn->prepare("SELECT * FROM file_degrees WHERE id=?");
                $stmtjn->bind_param("i", $olddegree_id);
                $stmtjn->execute();
                $resultjn = $stmtjn->get_result();
                $rowjn = $resultjn->fetch_assoc();
                $oldcno = $rowjn['case_num'];
                $oldyear = $rowjn['file_year'];
                $olddeg = $rowjn['degree'];
                
                $action = $action."<br>تم تغيير درجة التقاضي : من $oldcno/$oldyear-$olddeg الى $cno/$year-$deg";
            }
            
            $busi_priority = filter_input(INPUT_POST, 'busi_priority', FILTER_SANITIZE_NUMBER_INT);
            if(isset($busi_priority) && $busi_priority != $oldpriority){
                $flag = '1';
                
                if($busi_priority == 0){
                    $oldpr = 'عاجل';
                    $pr = 'عادي';
                } else if($busi_priority == 1){
                    $oldpr = 'عادي';
                    $pr = 'عاجل';
                }
                
                $action = $action."<br>تم تغيير اهمية العمل : من $oldpr الى $pr";
            }
            
            $busi_date = filter_input(INPUT_POST, 'busi_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($busi_date) && $busi_date !== $olddate){
                $flag = '1';
                
                $action = $action."<br>تم تغيير تاريخ التنفيذ : من $olddate الى $busi_date";
            }
            
            $busi_notes = filter_input(INPUT_POST, 'busi_notes', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($busi_notes) && $busi_notes !== $oldnotes){
                $flag = '1';
                
                $action = $action."<br>تم تغيير الملاحظات : من $oldnotes الى $busi_notes";
            }
            
            $stmt = $conn->prepare("UPDATE tasks SET employee_id=?, task_type=?, degree=?, priority=?, duedate=?, details=? WHERE id=?");
            $stmt->bind_param("iiiissi", $re_name, $type_name, $degree_id, $busi_priority, $busi_date, $busi_notes, $tid);
            $stmt->execute();
            
            if($flag === '1'){
                include_once 'addlog.php';
            }
            
            $submit_back = filter_input(INPUT_POST, 'submit_back', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            if(isset($submit_back) && $submit_back === 'addmore'){
                header("Location: Tasks.php?taskedited=1&addmore=1");
                exit();
            } else{
                header("Location: Tasks.php?taskedited=1");
                exit();
            }
        }
    }
    header("Location: Tasks.php");
    exit();
?>