<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'safe_output.php';
    include_once 'AES256.php';
    
    if($row_permcheck['cfiles_eperm'] == 1){
        if(isset($_REQUEST['mfid']) && $_REQUEST['mfid'] !== '' && $_REQUEST['mfid'] !== '0'){
            
            $flag = '0';
            $action = "تم ربط ملفات :<br>";
            
            if(isset($_REQUEST['mfid']) && $_REQUEST['mfid'] !== ''){
                $mfid = filter_input(INPUT_POST, 'mfid', FILTER_SANITIZE_NUMBER_INT);
                
                $stmtmfid = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                $stmtmfid->bind_param("i", $mfid);
                $stmtmfid->execute();
                $resultmfid = $stmtmfid->get_result();
                if($resultmfid->num_rows === 0){
                    $mfid = '0';
                }
                $stmtmfid->close();
                
                if(isset($mfid) && $mfid != 0){
                    $flag = '1';
                    
                    $action = $action."<br>رقم الملف الرئيسي : $mfid";
                }
            } else{
                $mfid = '0';
            }
            
            if(isset($_REQUEST['fid1']) && $_REQUEST['fid1'] !== ''){
                $fid1 = filter_input(INPUT_POST, 'fid1', FILTER_SANITIZE_NUMBER_INT);
                
                $stmtfid1 = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                $stmtfid1->bind_param("i", $fid1);
                $stmtfid1->execute();
                $resultfid1 = $stmtfid1->get_result();
                if($resultfid1->num_rows === 0){
                    $fid1 = '0';
                }
                $stmtfid1->close();
                
                if($fid1 == $mfid){
                    $fid1 = '0';
                }
                
                if(isset($fid1) && $fid1 != 0){
                    $flag = '1';
                    
                    $action = $action."<br>رقم الملف المرتبط 1 : $fid1";
                }
            } else{
                $fid1 = '0';
            }
            
            if(isset($_REQUEST['fid2']) && $_REQUEST['fid2'] !== ''){
                $fid2 = filter_input(INPUT_POST, 'fid2', FILTER_SANITIZE_NUMBER_INT);
                
                $stmtfid2 = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                $stmtfid2->bind_param("i", $fid2);
                $stmtfid2->execute();
                $resultfid2 = $stmtfid2->get_result();
                if($resultfid2->num_rows === 0){
                    $fid2 = '0';
                }
                $stmtfid2->close();
                
                if($fid2 == $mfid){
                    $fid2 = '0';
                }
                
                if(isset($fid2) && $fid2 != 0){
                    $flag = '1';
                    
                    $action = $action."<br>رقم الملف المرتبط 2 : $fid2";
                }
            } else{
                $fid2 = '0';
            }
            
            if(isset($_REQUEST['fid3']) && $_REQUEST['fid3'] !== ''){
                $fid3 = filter_input(INPUT_POST, 'fid3', FILTER_SANITIZE_NUMBER_INT);
                
                $stmtfid3 = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                $stmtfid3->bind_param("i", $fid3);
                $stmtfid3->execute();
                $resultfid3 = $stmtfid3->get_result();
                if($resultfid3->num_rows === 0){
                    $fid3 = '0';
                }
                $stmtfid3->close();
                
                if($fid3 == $mfid){
                    $fid3 = '0';
                }
                
                if(isset($fid3) && $fid3 != 0){
                    $flag = '1';
                    
                    $action = $action."<br>رقم الملف المرتبط 3 : $fid3";
                }
            } else{
                $fid3 = '0';
            }
            
            $stmtupd1 = $conn->prepare("UPDATE file SET related_file1=?, related_file2=?, related_file3=? WHERE file_id=?");
            $stmtupd1->bind_param("iiii", $fid1, $fid2, $fid3, $mfid);
            $stmtupd1->execute();
            $stmtupd1->close();
            
            if(isset($flag) && $flag === '1'){
                include_once 'addlog.php';
            }
            
            header("Location: relatedfiles.php?mfid=$mfid");
            exit();
        } else{
            header("Location: relatedfiles.php?error=1");
            exit();
        }
    } else{
        header("Location: relatedfiles.php?error=0");
        exit();
    }
?>