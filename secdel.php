<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['secretf_aperm'] == 1){
        if(isset($_GET['fid']) && $_GET['fid'] !== '' && isset($_GET['empid']) && $_GET['empid'] !== ''){
            $fid = intval($_GET['fid']);
            $empid = intval($_GET['empid']);
            
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
            
            $stmt = $conn->prepare("SELECT secret_emps FROM file WHERE file_id = ?");
            $stmt->bind_param("i", $fid);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            
            $current_emps = explode(',', $row['secret_emps']);
            $updated_emps = array_filter($current_emps, function($id) use ($empid) {
                return intval($id) !== $empid;
            });
            
            $new_emps = implode(',', $updated_emps);
            
            $stmt = $conn->prepare("UPDATE file SET secret_emps = ? WHERE file_id = ?");
            $stmt->bind_param("si", $new_emps, $fid);
            $stmt->execute();
            $stmt->close();
            
            header("Location: SecretFolder.php?id=$fid");
            exit();
        }
    }
?>