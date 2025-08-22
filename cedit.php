<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'secure_filesfunc.php';
    
    // Get form data using filter_input
    $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if($row_permcheck['clients_eperm'] == 1){
            $cid = filter_input(INPUT_POST, 'cid', FILTER_SANITIZE_NUMBER_INT);
            // Get file removal flags
            $remove_passport = filter_input(INPUT_POST, 'remove_passport', FILTER_SANITIZE_NUMBER_INT);
            $remove_fileid = filter_input(INPUT_POST, 'remove_fileid', FILTER_SANITIZE_NUMBER_INT);
            $remove_auth = filter_input(INPUT_POST, 'remove_auth', FILTER_SANITIZE_NUMBER_INT);
            $remove_attach1 = filter_input(INPUT_POST, 'remove_attach1', FILTER_SANITIZE_NUMBER_INT);
            $remove_attach2 = filter_input(INPUT_POST, 'remove_attach2', FILTER_SANITIZE_NUMBER_INT);
            $remove_attach3 = filter_input(INPUT_POST, 'remove_attach3', FILTER_SANITIZE_NUMBER_INT);
            $remove_attach4 = filter_input(INPUT_POST, 'remove_attach4', FILTER_SANITIZE_NUMBER_INT);
            $remove_attach5 = filter_input(INPUT_POST, 'remove_attach5', FILTER_SANITIZE_NUMBER_INT);
            $remove_attach6 = filter_input(INPUT_POST, 'remove_attach6', FILTER_SANITIZE_NUMBER_INT);

            // Get form fields
            $arname = filter_input(INPUT_POST, 'arname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $engname = filter_input(INPUT_POST, 'engname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $client_kind = filter_input(INPUT_POST, 'client_kind', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $client_type = filter_input(INPUT_POST, 'client_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $tel1 = filter_input(INPUT_POST, 'tel1', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $fax = filter_input(INPUT_POST, 'fax', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $tel2 = filter_input(INPUT_POST, 'tel2', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $terror = filter_input(INPUT_POST, 'terror', FILTER_SANITIZE_NUMBER_INT);
            $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $idno = filter_input(INPUT_POST, 'idno', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $perm = filter_input(INPUT_POST, 'perm', FILTER_SANITIZE_NUMBER_INT);
            
            // Get submit_back value
            $submit_back = filter_input(INPUT_POST, 'submit_back', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $flag = '0';
            $action = "تم التعديل على بيانات احد الموكلين :<br><br>رقم الموكل : $cid";
            
            $stmtr = $conn->prepare("SELECT * FROM client WHERE id=?");
            $stmtr->bind_param("i", $cid);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            
            if(isset($client_kind) && $client_kind !== $rowr['client_kind']){
                $flag = '1';
                $oldkind = $rowr['client_kind'];
                
                $action = $action."<br>تم تغيير تصنيف الموكل : من $oldkind الى $client_kind";
            }
            
            if(isset($arname) && $arname !== $rowr['arname']){
                $flag = '1';
                $oldarname = $rowr['arname'];
                
                $action = $action."<br>تم تغيير الاسم الموكل : من $oldarname الى $arname";
            }
            
            if(isset($engname) && $engname !== $rowr['engname']){
                $flag = '1';
                $oldengname = $rowr['engname'];
                
                $action = $action."<br>تم تغيير الاسم الموكل : من $oldengname الى $engname";
            }
            
            $stmt_count = $conn->prepare("SELECT COUNT(*) as c2count FROM client WHERE (arname = ? OR arname = ? OR engname = ? OR engname = ?) AND id != ?");
            $stmt_count->bind_param("ssssi", $arname, $engname, $engname, $arname, $cid);
            $stmt_count->execute();
            $result_count = $stmt_count->get_result();
            $row_count = $result_count->fetch_assoc();
            $check_count = $row_count['c2count'];
        
            if($check_count > 0){
                header("Location: $page?edit=1&id=$cid&namerror=1");
                exit();
            }
            
            if(isset($terror) && $terror != $rowr['terror']){
                $flag = '1';
                $oldterror = $rowr['terror'];
                
                if($oldterror == 1){
                    $oldt = '<br>تمت ازالة الموكل من قائمة الارهاب';
                } else{
                    $oldt = '<br>تمت اضافة الموكل الى قائمة الارهاب';
                }
                $action = $action.$oldt;
            }
            
            if(isset($email) && $email !== $rowr['email']){
                $flag = '1';
                $oldemail = $rowr['email'];
                
                $action = $action."<br>تم تغيير البريد الالكتروني : من $oldemail الى $email";
            }
            
            if(isset($tel1) && $tel1 !== $rowr['tel1']){
                $flag = '1';
                $oldtel1 = $rowr['tel1'];
                
                $action = $action."<br>تم تغيير رقم الهاتف المتحرك : من $oldtel1 الى $tel1";
            }
            
            if(isset($fax) && $fax !== $rowr['fax']){
                $flag = '1';
                $oldfax = $rowr['fax'];
                
                $action = $action."<br>تم تغيير رقم الهاتف : من $oldfax الى $fax";
            }
            
            if(isset($tel2) && $tel2 !== $rowr['tel2']){
                $flag = '1';
                $oldtel2 = $rowr['tel2'];
                
                $action = $action."<br>تم تغيير رقم الهاتف : من $oldtel2 الى $tel2";
            }
            
            if(isset($notes) && $notes !== $rowr['notes']){
                $flag = '1';
                $oldnotes = $rowr['notes'];
                
                $action = $action."<br>تم تغيير الملاحظات : من $oldnotes الى $notes";
            }
            
            if(isset($address) && $address !== $rowr['address']){
                $flag = '1';
                $oldaddress = $rowr['address'];
                
                $action = $action."<br>تم تغيير العنوان : من $oldaddress الى $address";
            }
            
            if(isset($country) && $country !== $rowr['country']){
                $flag = '1';
                $oldcountry = $rowr['country'];
                
                $action = $action."<br>تم تغيير الجنسية : من $oldcountry الى $country";
            }
            
            if(isset($idno) && $idno !== $rowr['idno']){
                $flag = '1';
                $oldidno = $rowr['idno'];
                
                $action = $action."<br>تم تغيير رقم الهوية : من $oldidno الى $idno";
            }
            
            if(isset($perm) && $perm != $rowr['perm']){
                $flag = '1';
                $oldperm = $rowr['perm'];
                if($oldperm == 1){
                    $oldp = "<br>تم تغيير صلاحيات الدخول من : فعال الى غير فعال";
                } else{
                    $oldp = "<br>تم تغيير صلاحيات الدخول : من غير فعال الى فعال";
                }
                
                $action = $action.$oldp;
            }
        
            $targetDir = "files_images/clients/$cid";
        
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $passport = $rowr['passport'];
            $passport_size = $rowr['passport_size'];
            
            if($remove_passport == 1){
                unlink($passport);
                
                $passport = '';
                $passport_size = '';
            }
            
            // Handle passport file
            if (isset($_FILES['passport_file']) && $_FILES['passport_file']['error'] == 0) {
                $passport_result = secure_file_upload($_FILES['passport_file'], $targetDir, 'passport');
                $passport = $passport_result['path'];
                $passport_size = $passport_result['size'];
            } else if ($remove_passport == 1) {
                $passport = '';
                $passport_size = '';
            } else {
                $passport = $rowr['passport'];
                $passport_size = $rowr['passport_size'];
            }
            if(isset($passport) && $passport !== $rowr['passport']){
                $flag = '1';
                if($passport === ''){
                    $oldpa = "<br>تم حذف مرفق جواز السفر";
                } else{
                    $oldpa = "<br>تم تغيير مرفق جواز السفر";
                }
                
                $action = $action.$oldpa;
            }
            
            $id_file = $rowr['id_file'];
            $id_file_size = $rowr['id_file_size'];
            
            if($remove_fileid == 1){
                unlink($id_file);
                
                $id_file = '';
                $id_file_size = '';
            }
            
            // Handle ID file
            if ((isset($_FILES['id_file']) && $_FILES['id_file']['error'] == 0)) {
                $id_result = secure_file_upload($_FILES['id_file'], $targetDir, 'id');
                $id_file = $id_result['path'];
                $id_file_size = $id_result['size'];
            } else if ($remove_fileid == 1) {
                $id_file = '';
                $id_file_size = '';
            } else {
                $id_file = $rowr['id_file'];
                $id_file_size = $rowr['id_file_size'];
            }
            if(isset($id_file) && $id_file !== $rowr['id_file']){
                $flag = '1';
                if($id_file === ''){
                    $oldidfile = "<br>تم حذف مرفق صورة الهوية";
                } else{
                    $oldidfile = "<br>تم تغيير مرفق صورة الهوية";
                }
                
                $action = $action.$oldidfile;
            }
            
            $auth_file = $rowr['auth'];
            $auth_size = $rowr['auth_size'];
                
            if($remove_auth == 1){
                unlink($auth_file);
                
                $auth_file = '';
                $auth_size = '';
            }
            
            // Handle auth file
            if ((isset($_FILES['auth_file']) && $_FILES['auth_file']['error'] == 0)) {
                $auth_result = secure_file_upload($_FILES['auth_file'], $targetDir, 'auth');
                $auth_file = $auth_result['path'];
                $auth_size = $auth_result['size'];
            } else if ($remove_auth == 1) {
                $auth_file = '';
                $auth_size = '';
            } else {
                $auth_file = $rowr['auth'];
                $auth_size = $rowr['auth_size'];
            }
            if(isset($auth_file) && $auth_file !== $rowr['auth']){
                $flag = '1';
                if($auth_file === ''){
                    $oldau = "<br>تم حذف مرفق الوكالة";
                } else{
                    $oldau = "<br>تم تغيير مرفق الوكالة";
                }
                
                $action = $action.$oldau;
            }
            
            $attach_file = $rowr['attach1'];
            $attach_size = $rowr['attach1_size'];
            
            if($remove_attach1 == 1){
                unlink($attach_file);
                
                $attach_file = '';
                $attach_size = '';
            }
                
            // Handle attachment 1
            if ((isset($_FILES['attach_file']) && $_FILES['attach_file']['error'] == 0)) {
                $attach1_result = secure_file_upload($_FILES['attach_file'], $targetDir, 'attach1');
                $attach_file = $attach1_result['path'];
                $attach_size = $attach1_result['size'];
            } else if ($remove_attach1 == 1) {
                $attach_file = '';
                $attach_size = '';
            } else {
                $attach_file = $rowr['attach1'];
                $attach_size = $rowr['attach1_size'];
            }
            
            if(isset($attach_file) && $attach_file !== $rowr['attach1']){
                $flag = '1';
                if($attach_file === ''){
                    $oldat1 = "<br>تم حذف مرفق (1)";
                } else{
                    $oldat1 = "<br>تم تغيير مرفق (1)";
                }
                
                $action = $action.$oldat1;
            }
            
            $attach_file2 = $rowr['attach2'];
            $attach2_size = $rowr['attach2_size'];
            
            if($remove_attach2 == 1){
                unlink($attach_file2);
                
                $attach_file2 = '';
                $attach2_size = '';
            }
                
            // Handle attachment 2
            if ((isset($_FILES['attach_file2']) && $_FILES['attach_file2']['error'] == 0)) {
                $attach2_result = secure_file_upload($_FILES['attach_file2'], $targetDir, 'attach2');
                $attach_file2 = $attach2_result['path'];
                $attach2_size = $attach2_result['size'];
            } else if ($remove_attach2 == 1) {
                $attach_file2 = '';
                $attach2_size = '';
            } else {
                $attach_file2 = $rowr['attach2'];
                $attach2_size = $rowr['attach2_size'];
            }
            if(isset($attach_file2) && $attach_file2 !== $rowr['attach2']){
                $flag = '1';
                if($attach_file2 === ''){
                    $oldat2 = "<br>تم حذف مرفق (2)";
                } else{
                    $oldat2 = "<br>تم تغيير مرفق (2)";
                }
                
                $action = $action.$oldat2;
            }
            
            $attach_file3 = $rowr['attach3'];
            $attach3_size = $rowr['attach3_size'];
            
            if($remove_attach3 == 1){
                unlink($attach_file3);
                
                $attach_file3 = '';
                $attach3_size = '';
            }
            
            if (isset($_FILES['attach_file3']) && $_FILES['attach_file3']['error'] == 0) {
                $attach3_result = secure_file_upload($_FILES['attach_file3'], $targetDir, 'attach3');
                $attach_file3 = $attach3_result['path'];
                $attach3_size = $attach3_result['size'];
            } else if ($remove_attach3 == 1) {
                $attach_file3 = '';
                $attach3_size = '';
            } else {
                $attach_file3 = $rowr['attach3'];
                $attach3_size = $rowr['attach3_size'];
            }
            if(isset($attach_file3) && $attach_file3 !== $rowr['attach3']){
                $flag = '1';
                if($attach_file3 === ''){
                    $oldat3 = "<br>تم حذف مرفق (3)";
                } else{
                    $oldat3 = "<br>تم تغيير مرفق (3)";
                }
                
                $action = $action.$oldat3;
            }
            
            $attach_file4 = $rowr['attach4'];
            $attach4_size = $rowr['attach4_size'];
            
            if($remove_attach4 == 1){
                unlink($attach_file4);
                
                $attach_file4 = '';
                $attach4_size = '';
            }
                
            if (isset($_FILES['attach_file4']) && $_FILES['attach_file4']['error'] == 0) {
                $attach4_result = secure_file_upload($_FILES['attach_file4'], $targetDir, 'attach4');
                $attach_file4 = $attach4_result['path'];
                $attach4_size = $attach4_result['size'];
            } else if ($remove_attach4 == 1) {
                $attach_file4 = '';
                $attach4_size = '';
            } else {
                $attach_file4 = $rowr['attach4'];
                $attach4_size = $rowr['attach4_size'];
            }
            if(isset($attach_file4) && $attach_file4 !== $rowr['attach4']){
                $flag = '1';
                if($attach_file4 === ''){
                    $oldat4 = "<br>تم حذف مرفق (4)";
                } else{
                    $oldat4 = "<br>تم تغيير مرفق (4)";
                }
                
                $action = $action.$oldat4;
            }
            
            $attach_file5 = $rowr['attach5'];
            $attach5_size = $rowr['attach5_size'];
            
            if($remove_attach5 == 1){
                unlink($attach_file5);
                
                $attach_file5 = '';
                $attach5_size = '';
            }
                
            if (isset($_FILES['attach_file5']) && $_FILES['attach_file5']['error'] == 0) {
                $attach5_result = secure_file_upload($_FILES['attach_file5'], $targetDir, 'attach5');
                $attach_file5 = $attach5_result['path'];
                $attach5_size = $attach5_result['size'];
            } else if ($remove_attach5 == 1) {
                $attach_file5 = '';
                $attach5_size = '';
            } else {
                $attach_file5 = $rowr['attach5'];
                $attach5_size = $rowr['attach5_size'];
            }
            if(isset($attach_file5) && $attach_file5 !== $rowr['attach5']){
                $flag = '1';
                if($attach_file5 === ''){
                    $oldat5 = "<br>تم حذف مرفق (5)";
                } else{
                    $oldat5 = "<br>تم تغيير مرفق (5)";
                }
                
                $action = $action.$oldat5;
            }
            
            $attach_file6 = $rowr['attach6'];
            $attach6_size = $rowr['attach6_size'];
                
            if($remove_attach6 == 1){
                unlink($attach_file6);
                
                $attach_file6 = '';
                $attach6_size = '';
            }
                
            if (isset($_FILES['attach_file6']) && $_FILES['attach_file6']['error'] == 0) {
                $attach6_result = secure_file_upload($_FILES['attach_file6'], $targetDir, 'attach6');
                $attach_file6 = $attach6_result['path'];
                $attach6_size = $attach6_result['size'];
            } else if ($remove_attach6 == 1) {
                $attach_file6 = '';
                $attach6_size = '';
            } else {
                $attach_file6 = $rowr['attach6'];
                $attach6_size = $rowr['attach6_size'];
            }
            if(isset($attach_file6) && $attach_file6 !== $rowr['attach6']){
                $flag = '1';
                if($attach_file6 === ''){
                    $oldat6 = "<br>تم حذف مرفق (6)";
                } else{
                    $oldat6 = "<br>تم تغيير مرفق (6)";
                }
                
                $action = $action.$oldat6;
            }
            $upload_errors = array_filter([
                isset($_FILES['passport_file']) ? ($passport_result['error'] ?? '') : '',
                isset($_FILES['id_file']) ? ($id_result['error'] ?? '') : '',
                isset($_FILES['auth_file']) ? ($auth_result['error'] ?? '') : '',
                isset($_FILES['attach_file']) ? ($attach1_result['error'] ?? '') : '',
                isset($_FILES['attach_file2']) ? ($attach2_result['error'] ?? '') : '',
                isset($_FILES['attach_file3']) ? ($attach3_result['error'] ?? '') : '',
                isset($_FILES['attach_file4']) ? ($attach4_result['error'] ?? '') : '',
                isset($_FILES['attach_file5']) ? ($attach5_result['error'] ?? '') : '',
                isset($_FILES['attach_file6']) ? ($attach6_result['error'] ?? '') : ''
            ]);

            if (!empty($upload_errors)) {
                error_log("File upload errors for client $cid: " . implode(", ", $upload_errors));
            }
            
            $stmt = $conn->prepare("UPDATE client SET arname=?, engname=?, terror=?, client_kind=?, 
            email=?, tel1=?, fax=?, tel2=?, notes=?, address=?, country=?, idno=?, perm=?, 
            passport=?, passport_size=?, id_file=?, id_file_size=?, auth=?, auth_size=?, attach1=?, 
            attach1_size=?, attach2=?, attach2_size=?, attach3=?, attach3_size=?, attach4=?, 
            attach4_size=?, attach5=?, attach5_size=?, attach6=?, attach6_size=? WHERE id=?");
            $stmt->bind_param("ssisssssssssissssssssssssssssssi", $arname, $engname, $terror, 
            $client_kind, $email, $tel1, $fax, $tel2, $notes, $address, $country, $idno, $perm, 
            $passport, $passport_size, $id_file, $id_file_size, $auth_file, $auth_size, $attach_file, 
            $attach_size, $attach_file2, $attach2_size, $attach_file3, $attach3_size, $attach_file4, 
            $attach4_size, $attach_file5, $attach5_size, $attach_file6, $attach6_size, $cid);
            $stmt->execute();
            
            if($flag === '1'){
                include_once 'addlog.php';
            }
        
            if ($stmt->execute()) {
                if ($flag === '1') {
                    include_once 'addlog.php';
                    include_once 'timerfunc.php';
                }
                
                if($submit_back === 'addmore'){
                    header("Location: $page?done=1&addmore=1");
                } else{
                    header("Location: $page?done=1");
                }
                exit();
            }
        } else{
            header("Location: $page?edit=1&id=$cid&error=0");
            exit();
        }
    } else{
        
        $cid = filter_input(INPUT_POST, 'cid', FILTER_SANITIZE_NUMBER_INT);
    
        $arname=1;
        $engname=1;
        $tel=1;
        if(!isset($_REQUEST['arname']) || $_REQUEST['arname'] === ''){
            $arname = 0;
        }
        if(!isset($_REQUEST['tel1']) || $_REQUEST['tel1'] === ''){
            $tel = 0;
        }
        if(!isset($_REQUEST['engname']) || $_REQUEST['engname'] === ''){
            $engname = 0;
        }
        header("Location: $page?id=$cid&a=$arname&t=$tel&e=$engname");
        exit();
    }
    header("Location: $page");
    exit();