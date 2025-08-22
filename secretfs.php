<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['secretf_aperm'] == 1){
        $fid = filter_input(INPUT_POST, 'fid', FILTER_SANITIZE_NUMBER_INT);
        
        $stmtid = $conn->prepare("SELECT * FROM file WHERE file_id=?");
        $stmtid->bind_param("i", $fid);
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
        $secret_folder = filter_input(INPUT_POST, "secret_folder", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(isset($secret_folder) && $secret_folder !== ''){
            $secret_folder = '1';
            $sec_stat = 'ملف سري';
        } else{
            $secret_folder = '0';
            $sec_stat = 'ملف عام (غير سري)';
        }
        
        $stmtr = $conn->prepare("SELECT * FROM file WHERE file_id=?");
        $stmtr->bind_param("i", $fid);
        $stmtr->execute();
        $resultr = $stmtr->get_result();
        $rowr = $resultr->fetch_assoc();
        $stmtr->close();
        
        $oldsf = $rowr['secret_folder'];
        if(isset($secret_folder) && $secret_folder !== $oldsf){
            $flag = '1';
            
            if($secret_folder == 1){
                $action = "تم تغيير حالة الملف رقم $fid الى ملف سري<br>";
            } else{
                $action = "تم تغيير حالة الملف رقم $fid من ملف سري الى عام<br>";
            }
        }
        
        if ($secret_folder == 1) {
            $selectedemps = array_filter($_POST['employee_id'], function($id) {
                return !empty($id);
            });
            
            if (!empty($selectedemps)) {
                $ids = implode(',', array_map('intval', $selectedemps));
                $idsc = explode(",", $ids);
                
                $count = 0;
                $emp_names = '';
                
                foreach ($idsc as $id) {
                    $count++;
                    $stmtemps = $conn->prepare("SELECT * FROM user WHERE id=?");
                    $stmtemps->bind_param("i", $id);
                    $stmtemps->execute();
                    $resultemps = $stmtemps->get_result();
                    if ($rowemps = $resultemps->fetch_assoc()) {
                        $emp_names .= '<br>' . $count . ' - ' . safe_output($rowemps['name']) . '<br>';
                    }
                }
                
                if($rowr['secret_emps'] !== ''){
                    $ids .= ',' . $rowr['secret_emps'];
                }
                
                $action .= $emp_names;
                
                $stmt = $conn->prepare("UPDATE file SET secret_folder=?, secret_emps=? WHERE file_id=?");
                $stmt->bind_param("isi", $secret_folder, $ids, $fid);
                $stmt->execute();
                $stmt->close();
            }
        }
        
        header("Location: SecretFolder.php?id=$fid");
        exit();
    }
    header("Location: SecretFolder.php?id=$fid&error=1");
    exit();
?>