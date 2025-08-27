<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';

    /** @var mysqli $conn */
    /** @var array $row_permcheck */
    /** @var int $admin */
    
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
    if($row_permcheck['levels_eperm'] == 1){
        if (isset($_POST['levels'])){
            $action2 = "تم تغيير مراحل الاتفاقية للملف رقم : $fid<br>";
            
            $levels = $_POST['levels'];
            
            $ids = implode(',', array_map('intval', $levels));
            $idscheck = $ids;
            $idsc = explode(",", $idscheck);
            
            $file_levels = '';
            foreach($idsc as $id){
                $stmtf = $conn->prepare("SELECT * FROM levels WHERE id=?");
                $stmtf->bind_param("i", $id);
                $stmtf->execute();
                $resultf = $stmtf->get_result();
                $rowf = $resultf->fetch_assoc();
                $level_name = $rowf['level_name'];
                
                if($file_levels !== ''){
                    $file_levels = $file_levels.', '.$level_name;
                } else{
                    $file_levels = $level_name;
                }
            }
            $action2 = $action2."<br>مراحل الاتفاقية : $file_levels";
            
            $stmt = $conn->prepare("UPDATE file SET file_levels=? WHERE file_id=?");
            $stmt->bind_param("si", $file_levels, $fid);
            $stmt->execute();
            
            $empid = $_SESSION['id'];
            
            include_once 'addlog.php';
        }
    }
    
    header("Location: Fees.php?id=$fid");
    exit();
?>