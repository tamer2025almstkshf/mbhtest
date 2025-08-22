<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['clients_eperm'] == 1){
        if(isset($_GET['cid']) && $_GET['cid'] !== ''){
            $cid = $_GET['cid'];
            
            $stmtr = $conn->prepare("SELECT * FROM client WHERE id=?");
            $stmtr->bind_param("i", $cid);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            
            $perm = $rowr['perm'];
            if($perm == '1'){
                $update = '0';
                
                $oldperm = 'فعال';
                $newperm = 'غير فعال';
            } else{
                $update = '1';
                
                $oldperm = 'غير فعال';
                $newperm = 'فعال';
            }
            
            $stmt = $conn->prepare("UPDATE client SET perm=? WHERE id=?");
            $stmt->bind_param("ii", $update, $cid);
            $stmt->execute();
            
            $action = "تم تعديل صلاحيات الدخول من $oldperm الى $newperm للموكل رقم : $cid";
            include_once 'addlog.php';
        }
        header("Location: clients.php?permschanged=1");
        exit();
    } else{
        header("Location: clients.php");
    }
?>