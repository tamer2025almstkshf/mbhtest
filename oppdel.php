<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['session_dperm'] == 1){
        if(isset($_GET['id']) && $_GET['id'] !== ''){
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            
            $stmtid = $conn->prepare("SELECT * FROM file WHERE file_id=?");
            $stmtid->bind_param("i", $id);
            $stmtid->execute();
            $resultid = $stmtid->get_result();
            $row_details = $resultid->fetch_assoc();
            $stmtid->close();
            if($admin != 1){
                if($row_details['secret_folder'] == 1){
                    $empids = $row_details['secret_emps'];
                    $empids = array_filter(array_map('trim', explode(',', $empids)));
                    if (!in_array($_SESSION['id'], $empids)) {
                        exit();
                    }
                }
            }
            
            $stmtr = $conn-prepare("SELECT * FROM oppcase WHERE id=?");
            $stmtr->bind_param("i", $id);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $result->fetch_assoc();
            $stmtr->close();
            
            $fid = $rowr['fid'];
            
            $action = "تم حذف قضية متقابلة من الملف رقم : $fid<br>";
            $flag = '0';
            
            $fdi = $rowr['file_degree_id'];
            if(isset($fdi) && $fdi != 0){
                $flag = '1';
                
                $action = $action."<br>الدرجة : $fdi";
            }
            
            $date = $rowr['opp_date'];
            if(isset($date) && $date !== ''){
                $flag = '1';
                
                $action = $action."<br>التاريخ : $date";
            }
            
            $caseno = $rowr['case_no'];
            $year = $rowr['year'];
            if((isset($caseno) && $caseno != 0) || (isset($year) && $year != 0)){
                $flag = '1';
                
                $action = $action."<br>رقم القضية : $caseno / $year";
            }
            
            $cchar = $rowr['client_characteristic'];
            if(isset($cchar) && $cchar !== ''){
                $flag = '1';
                
                $action = $action."<br>صفة الموكل : $cchar";
            }
            
            $ochar = $rowr['opponent_characteristic'];
            if(isset($ochar) && $ochar !== ''){
                $flag = '1';
                
                $action = $action."<br>صفة الخصم : $ochar";
            }
            
            if($flag === '1'){
                include_once 'addlog.php';
            }
            
            $stmt = $conn->prepare("DELETE FROM oppcase WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }
    
    header("Location: oppCase.php?fid=$fid");
    exit();
?>