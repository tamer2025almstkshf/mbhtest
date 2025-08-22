<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if(isset($_GET['did'])){
        $did = filter_input(INPUT_GET, 'did', FILTER_SANITIZE_NUMBER_INT);
        $fid = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        
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
        
        if($row_permcheck['note_dperm'] == 1){
            $flag = '0';
            $action = "تم حذف احد مستندات الدعوى : <br> رقم الملف : $fid";
            
            $stmtcheck = $conn->prepare("SELECT * FROM case_document WHERE did=?");
            $stmtcheck->bind_param("i", $did);
            $stmtcheck->execute();
            $resultcheck = $stmtcheck->get_result();
            $row = $resultcheck->fetch_assoc();
            $stmtcheck->close();
            
            $oldfno = $row['dfile_no'];
            $oldcno = $row['dcase_no'];
            $olddate = $row['document_date'];
            $oldsub = $row['document_subject'];
            $olddet = $row['document_details'];
            $oldat1 = $row['document_attachment'];
            $oldat2 = $row['document_attachment2'];
            
            if(isset($oldcno) && $oldcno != 0){
                $flag = '1';
                
                $stmtd = $conn->prepare("SELECT * FROM file_degrees WHERE id=?");
                $stmtd->bind_param("i", $oldcno);
                $stmtd->execute();
                $resultd = $stmtd->get_result();
                $rowd = $resultd->fetch_assoc();
                $stmtd->close();
                
                $year = $rowd['file_year'];
                $cn = $rowd['case_num'];
                $deg = $rowd['degree'];
                $degree = "$cn/$year-$deg";
                
                $action = $action."<br>رقم القضية : $degree";
            }
            
            if(isset($oldsub) && $oldsub !== ''){
                $flag = '1';
                
                $action = $action."<br>عنوان المستندات : $oldsub";
            }
            
            if(isset($olddet) && $olddet !== ''){
                $flag = '1';
                
                $action = $action."<br>تفاصيل مستندات الدعوى : $olddet";
            }
            
            if(isset($olddate) && $olddate !== ''){
                $flag = '1';
                
                $action = $action."<br>التاريخ : $olddate";
            }
            
            if(isset($oldat1) && $oldat1 !== ''){
                $flag = '1';
                
                $action = $action."<br>المرفق (1) : $oldat1";
            }
            
            if(isset($oldat2) && $oldat2 !== ''){
                $flag = '1';
                
                $action = $action."<br>المرفق (2) : $oldat2";
            }
            
            if (!empty($oldat1) && file_exists($oldat1)) {
                unlink($oldat1);
            }
            
            if (!empty($oldat2) && file_exists($oldat2)) {
                unlink($oldat2);
            }
            
            $stmt = $conn->prepare("DELETE FROM case_document WHERE did=?");
            $stmt->bind_param("i", $did);
            $stmt->execute();
            $stmt->close();
            
            if($flag === '1'){
                include_once 'addlog.php';
            }
        }
        header("Location: FileEdit.php?id=$fid");
        exit();
    }
    
    if(isset($_GET['page']) && $_GET['page'] === 'ef'){
        $fid = $_GET['id'];
        header("Location: FileEdit.php?fid=$fid");
        exit();
    } else{
        header("Location: AddNotes.php?id=$fid&error=1");
        exit();
    }
?>