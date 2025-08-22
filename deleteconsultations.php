<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['csched_dperm'] == 1){
        if(isset($_GET['id']) && $_GET['id'] !== ''){
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            
            $action = "تم حذف استشارة :<br>";
            $stmtr = $conn->prepare("SELECT * FROM consultations WHERE id=?");
            $stmtr->bind_param("i", $id);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $row = $resultr->fetch_assoc();
            
            $branch = $row['branch'];
            if(isset($branch) && $branch !== ''){
                $flag = '1';
                
                $action = $action."<br>الفرع : $branch";
            }
            
            $client_name = $row['client_name'];
            if(isset($client_name) && $client_name !== ''){
                $flag = '1';
                
                $action = $action."<br>اسم الموكل : $client_name";
            }
            
            $telno = $row['telno'];
            if(isset($telno) && $telno !== ''){
                $flag = '1';
                
                $action = $action."<br>الهاتف : $telno";
            }
            
            $email = $row['email'];
            if(isset($email) && $email !== ''){
                $flag = '1';
                
                $action = $action."<br>الايميل : $email";
            }
            
            $client_type = $row['client_type'];
            if(isset($client_type) && $client_type !== ''){
                $flag = '1';
                
                $action = $action."<br>فئة العميل : $client_type";
            }
            
            $passport = $row['passport'];
            if(isset($passport) && $passport !== ''){
                $flag = '1';
                
                $action = $action."<br>تم حذف مرفق جواز السفر";
            }
            
            $attachments = $row['attachment'];
            if(isset($attachments) && $attachments !== ''){
                $flag = '1';
                
                $action = $action."<br>تم حذف المرفقات";
            }
            
            if($flag === '1'){
                include_once 'addlog.php';
            }
            
            $stmt = $conn->prepare("DELETE FROM consultations WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }
    header("Location: consultations.php?branch=$branch");
    exit();
?>