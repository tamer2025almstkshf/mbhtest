<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'secure_filesfunc.php';
    
    $fidd = $_REQUEST['fid'];
    if (isset($_REQUEST['save_file'])) {
        $fid = (int)filter_input(INPUT_POST, 'fidget', FILTER_SANITIZE_NUMBER_INT);
        
        if(isset($_REQUEST['fclass_edit']) && $_REQUEST['fclass_edit'] !== ''){
            if($row_permcheck['cfiles_eperm'] == 1){
                $flag2 = '0';
                $action2 = "تم التعديل على الملف :<br>رقم الملف : $fid<br>";
                
                $stmtr = $conn->prepare("SELECT * FROM file WHERE file_id = ?"); 
                $stmtr->bind_param("i", $fid); 
                $stmtr->execute(); 
                $resultr = $stmtr->get_result(); 
                $rowr = $resultr->fetch_assoc();
                $stmtr->close();
                
                $type_edit = filter_input(INPUT_POST, "type_edit", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($rowr['file_type'])){
                    $oldtype = isset($rowr['file_type']) ? safe_output($rowr['file_type']) : '';
                }
                if(isset($type_edit) && $type_edit !== $oldtype){
                    $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير نوع الملف : من $oldtype الى $type_edit";
                }
                
                if($type_edit === ''){
                    $_SESSION['form_data'] = $_POST;
                    header('Location: addcase.php?error=0');
                    exit();
                }
                
                $status_edit = filter_input(INPUT_POST, "fstatus_edit", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldstatus = isset($rowr['file_status']) ? safe_output($rowr['file_status']) : '';
                if(isset($status_edit) && $status_edit !== $oldstatus){
                    $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير حالة الملف : من $oldstatus الى $status_edit";
                }
                
                $important = (int)filter_input(INPUT_POST, 'important', FILTER_SANITIZE_NUMBER_INT);
                $oldimp = isset($rowr['important']) ? safe_output($rowr['important']) : 0;
                if($important != $oldimp){
                    $flag2 = '1';
                    if($important == 1){
                        $impname = "دعوى مهمة";
                    } else{
                        $impname = "عادي";
                    }
                    
                    $action2 = $action2."<br>تم تغيير اولوية الملف الى : $impname";
                }
                
                $fclass_edit = filter_input(INPUT_POST, "fclass_edit", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldclass = isset($rowr['file_class']) ? safe_output($rowr['file_class']) : '';
                if(isset($fclass_edit) && $fclass_edit !== $oldclass){
                    $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير تصنيف الملف : من $oldclass الى $fclass_edit";
                }
        
                if($fclass_edit === ''){
                    $_SESSION['form_data'] = $_POST;
                    header('Location: addcase.php?error=0');
                    exit();
                }
                
                $place_edit = filter_input(INPUT_POST, "place_edit", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldplace = isset($rowr['frelated_place']) ? safe_output($rowr['frelated_place']) : '';
                if(isset($place_edit) && $place_edit !== $oldplace){
                    $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير الفرع المختص : من $oldplace الى $place_edit";
                }
        
                if($place_edit === ''){
                    $_SESSION['form_data'] = $_POST;
                    header('Location: addcase.php?error=0');
                    exit();
                }
                
                $fnotes_edit = filter_input(INPUT_POST, "fnotes_edit", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldnotes = isset($rowr['file_notes']) ? safe_output($rowr['file_notes']) : '';
                if(isset($fnotes_edit) && $fnotes_edit !== $oldnotes){
                    $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير الملاحظات : من $oldnotes الى $fnotes_edit";
                }
        
                $fsubject_edit = filter_input(INPUT_POST, "fsubject_edit", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldsubject = isset($rowr['file_subject']) ? safe_output($rowr['file_subject']) : '';
                if($fsubject_edit !== $oldsubject){
                    $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير الموضوع : من $oldsubject الى $fsubject_edit";
                }
                
                $fcourt_edit = filter_input(INPUT_POST, "fcourt_edit", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldcourt = isset($rowr['file_court']) ? safe_output($rowr['file_court']) : '';
                if(isset($fcourt_edit) && $fcourt_edit !== $oldcourt){
                    $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير المحكمة : من $oldcourt الى $fcourt_edit";
                }
                
                $fclient_edit = (int)filter_input(INPUT_POST, 'fclient_edit', FILTER_SANITIZE_NUMBER_INT);
                $oldc = isset($rowr['file_client']) ? safe_output($rowr['file_client']) : 0;
                if(isset($fclient_edit) && $fclient_edit != $oldc){
                    $flag2 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $oldc);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $oldcn = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $fclient_edit);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $cn = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $action2 = $action2."<br>تم تغيير الموكل : من $oldcn الى $cn";
                }
                
                $fchar_edit = filter_input(INPUT_POST, "fchar_edit", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldcc = $rowr['fclient_characteristic'];
                if(isset($fchar_edit) && $fchar_edit !== $oldcc){
                    $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير صفة الموكل : من $oldcc الى $fchar_edit";
                }
        
                if($fclient_edit === '' || $fclient_edit == 0){
                    $_SESSION['form_data'] = $_POST;
                    header('Location: addcase.php?error=0');
                    exit();
                }
                
                $fclient_edit2 = (int)filter_input(INPUT_POST, 'fclient_edit2', FILTER_SANITIZE_NUMBER_INT);
                $oldc = isset($rowr['file_client2']) ? safe_output($rowr['file_client2']) : 0;
                if(isset($fclient_edit2) && $fclient_edit2 != $oldc){
                    $flag2 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $oldc);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $oldcn = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $fclient_edit2);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $cn = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $action2 = $action2."<br>تم تغيير الموكل 2 : من $oldcn الى $cn";
                }
                
                $fchar_edit2 = filter_input(INPUT_POST, "fchar_edit2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldcc = isset($rowr['fclient_characteristic2']) ? safe_output($rowr['fclient_characteristic2']) : '';
                if(isset($fchar_edit2) && $fchar_edit2 !== $oldcc){
                    $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير صفة الموكل 2 : من $oldcc الى $fchar_edit2";
                }
        
                $fclient_edit3 = (int)filter_input(INPUT_POST, 'fclient_edit3', FILTER_SANITIZE_NUMBER_INT);
                $oldc = isset($rowr['file_client3']) ? safe_output($rowr['file_client3']) : 0;
                if(isset($fclient_edit3) && $fclient_edit3 != $oldc){
                    $flag2 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $oldc);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $oldcn = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $fclient_edit3);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $cn = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $action2 = $action2."<br>تم تغيير الموكل 3 : من $oldcn الى $cn";
                }
                
                $fchar_edit3 = filter_input(INPUT_POST, "fchar_edit3", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldcc = isset($rowr['fclient_characteristic3']) ? safe_output($rowr['fclient_characteristic3']) : '';
                if(isset($fchar_edit3) && $fchar_edit3 !== $oldcc){
                     $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير صفة الموكل 3 : من $oldcc الى $fchar_edit3";
                }
        
                $fclient_edit4 = (int)filter_input(INPUT_POST, 'fclient_edit4', FILTER_SANITIZE_NUMBER_INT);
                $oldc = isset($rowr['file_client4']) ? safe_output($rowr['file_client4']) : 0;
                if(isset($fclient_edit4) && $fclient_edit4 != $oldc){
                     $flag2 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $oldc);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $oldcn = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $fclient_edit4);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $cn = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $action2 = $action2."<br>تم تغيير الموكل 4 : من $oldcn الى $cn";
                }
                
                $fchar_edit4 = filter_input(INPUT_POST, "fchar_edit4", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldcc = isset($rowr['fclient_characteristic4']) ? safe_output($rowr['fclient_characteristic4']) : '';
                if(isset($fchar_edit4) && $fchar_edit4 !== $oldcc){
                     $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير صفة الموكل 4 : من $oldcc الى $fchar_edit4";
                }
        
                $fclient_edit5 = (int)filter_input(INPUT_POST, 'fclient_edit5', FILTER_SANITIZE_NUMBER_INT);
                $oldc = isset($rowr['file_client5']) ? safe_output($rowr['file_client5']) : 0;
                if(isset($fclient_edit5) && $fclient_edit5 != $oldc){
                     $flag2 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $oldc);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $oldcn = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $fclient_edit5);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $cn = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $action2 = $action2."<br>تم تغيير الموكل 5 : من $oldcn الى $cn";
                }
                
                $fchar_edit5 = filter_input(INPUT_POST, "fchar_edit5", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldcc = isset($rowr['fclient_characteristic5']) ? safe_output($rowr['fclient_characteristic5']) : '';
                if(isset($fchar_edit5) && $fchar_edit5 !== $oldcc){
                     $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير صفة الموكل 5 : من $oldcc الى $fchar_edit5";
                }
                
                $fopponent_edit = (int)filter_input(INPUT_POST, 'fopponent_edit', FILTER_SANITIZE_NUMBER_INT);
                $oldo = isset($rowr['file_opponent']) ? safe_output($rowr['file_opponent']) : 0;
                if(isset($fopponent_edit) && $fopponent_edit != $oldo){
                     $flag2 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $oldo);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $oldon = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $fopponent_edit);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $on = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $action2 = $action2."<br>تم تغيير الخصم : من $oldon الى $on";
                }
                
                $fopponent_charedit = filter_input(INPUT_POST, "fopponent_charedit", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldoc = isset($rowr['fopponent_characteristic']) ? safe_output($rowr['fopponent_characteristic']) : '';
                if(isset($fopponent_charedit) && $fopponent_charedit !== $oldoc){
                     $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير صفة الخصم : من $oldoc الى $fopponent_charedit";
                }
        
                $fopponent_edit2 = (int)filter_input(INPUT_POST, 'fopponent_edit2', FILTER_SANITIZE_NUMBER_INT);
                $oldo = isset($rowr['file_opponent2']) ? safe_output($rowr['file_opponent2']) : 0;
                if(isset($fopponent_edit2) && $fopponent_edit2 != $oldo){
                     $flag2 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $oldo);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $oldon = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $fopponent_edit2);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $on = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $action2 = $action2."<br>تم تغيير الخصم 2 : من $oldon الى $on";
                }
                
                $fopponent_charedit2 = filter_input(INPUT_POST, "fopponent_charedit2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldoc = isset($rowr['fopponent_characteristic2']) ? safe_output($rowr['fopponent_characteristic2']) : '';
                if(isset($fopponent_charedit2) && $fopponent_charedit2 !== $oldoc){
                     $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير صفة الخصم 2 : من $oldoc الى $fopponent_charedit2";
                }
        
                $fopponent_edit3 = (int)filter_input(INPUT_POST, 'fopponent_edit3', FILTER_SANITIZE_NUMBER_INT);
                $oldo = isset($rowr['file_opponent3']) ? safe_output($rowr['file_opponent3']) : 0;
                if(isset($fopponent_edit3) && $fopponent_edit3 != $oldo){
                     $flag2 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $oldo);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $oldon = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $fopponent_edit3);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $on = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $action2 = $action2."<br>تم تغيير الخصم 3 : من $oldon الى $on";
                }
                
                $fopponent_charedit3 = filter_input(INPUT_POST, "fopponent_charedit3", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldoc = isset($rowr['fopponent_characteristic3']) ? safe_output($rowr['fopponent_characteristic3']) : '';
                if(isset($fopponent_charedit3) && $fopponent_charedit3 !== $oldoc){
                     $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير صفة الخصم 2 : من $oldoc الى $fopponent_charedit3";
                }
        
                $fopponent_edit4 = (int)filter_input(INPUT_POST, 'fopponent_edit4', FILTER_SANITIZE_NUMBER_INT);
                $oldo = isset($rowr['file_opponent4']) ? safe_output($rowr['file_opponent4']) : 0;
                if(isset($fopponent_edit4) && $fopponent_edit4 != $oldo){
                     $flag2 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $oldo);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $oldon = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $fopponent_edit4);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $on = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $action2 = $action2."<br>تم تغيير الخصم 4 : من $oldon الى $on";
                }
                
                $fopponent_charedit4 = filter_input(INPUT_POST, "fopponent_charedit4", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldoc = isset($rowr['fopponent_characteristic4']) ? safe_output($rowr['fopponent_characteristic4']) : '';;
                if(isset($fopponent_charedit4) && $fopponent_charedit4 !== $oldoc){
                     $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير صفة الخصم 4 : من $oldoc الى $fopponent_charedit4";
                }
        
                $fopponent_edit5 = (int)filter_input(INPUT_POST, 'fopponent_edit5', FILTER_SANITIZE_NUMBER_INT);
                $oldo = isset($rowr['file_opponent5']) ? safe_output($rowr['file_opponent5']) : 0;
                if(isset($fopponent_edit5) && $fopponent_edit5 != $oldo){
                     $flag2 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $oldo);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $oldon = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    $stmt->close();
                    
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $fopponent_edit5);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $on = isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                    
                    $action2 = $action2."<br>تم تغيير الخصم 5 : من $oldon الى $on";
                }
                
                $fopponent_charedit5 = filter_input(INPUT_POST, "fopponent_charedit5", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldoc = isset($rowr['fopponent_characteristic5']) ? safe_output($rowr['fopponent_characteristic5']) : '';;
                if(isset($fopponent_charedit5) && $fopponent_charedit5 !== $oldoc){
                     $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير صفة الخصم 5 : من $oldoc الى $fopponent_charedit5";
                }
                
                $fprosecution2 = filter_input(INPUT_POST, "fprosecution2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldprosec = isset($rowr['file_prosecution']) ? safe_output($rowr['file_prosecution']) : '';
                if(isset($fprosecution2) && $fprosecution2 !== $oldprosec){
                     $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير النيابة : من $oldprosec الى $fprosecution2";
                }
                
                $fpolice_edit = filter_input(INPUT_POST, "fpolice_edit", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldpstation = isset($rowr['fpolice_station']) ? safe_output($rowr['fpolice_station']) : '';
                if(isset($fpolice_edit) && $fpolice_edit !== $oldpstation){
                     $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير مركز الشرطة : من $oldpstation الى $fpolice_edit";
                }
                
                $fctype_edit = filter_input(INPUT_POST, "fctype_edit", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $oldfct = isset($rowr['fcase_type']) ? safe_output($rowr['fcase_type']) : '';
                if(isset($fctype_edit) && $fctype_edit !== $oldfct){
                     $flag2 = '1';
                    
                    $action2 = $action2."<br>تم تغيير نوع القضية : من $oldfct الى $fctype_edit";
                }
                
                $fresearcher_edit = (int)filter_input(INPUT_POST, 'fresearcher_edit', FILTER_SANITIZE_NUMBER_INT);
                if($fresearcher_edit === ''){
                    $fresearcher_edit = '0';
                }
                $oldfr = isset($rowr['flegal_researcher']) ? safe_output($rowr['flegal_researcher']) : 0;
                if(isset($fresearcher_edit) && $fresearcher_edit != $oldfr){
                     $flag2 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $oldfr);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $oldfrname = isset($rowc['name']) ? safe_output($rowc['name']) : '';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $fresearcher_edit);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $frname = isset($rowc['name']) ? safe_output($rowc['name']) : '';
                    
                    $action2 = $action2."<br>تم تغيير الباحث القانوني : من $oldfrname الى $frname";
                }
                
                $fresearcher2_edit = (int)filter_input(INPUT_POST, 'fresearcher2_edit', FILTER_SANITIZE_NUMBER_INT);
                if($fresearcher2_edit === ''){
                    $fresearcher2_edit = '0';
                }
                $oldfr = isset($rowr['flegal_researcher2']) ? safe_output($rowr['flegal_researcher2']) : 0;
                if(isset($fresearcher2_edit) && $fresearcher2_edit != $oldfr){
                     $flag2 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $oldfr);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $oldfrname = isset($rowc['name']) ? safe_output($rowc['name']) : '';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $fresearcher2_edit);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $frname = isset($rowc['name']) ? safe_output($rowc['name']) : '';
                    
                    $action2 = $action2."<br>تم تغيير الباحث القانوني : من $oldfrname الى $frname";
                }
                
                $file_secritary = (int)filter_input(INPUT_POST, 'fsc_edit', FILTER_SANITIZE_NUMBER_INT);
                if($file_secritary === ''){
                    $file_secritary = '0';
                }
                $oldfr = isset($rowr['file_secritary']) ? safe_output($rowr['file_secritary']) : 0;
                if(isset($file_secritary) && $file_secritary != $oldfr){
                     $flag2 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $oldfr);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $oldfrname = isset($rowc['name']) ? safe_output($rowc['name']) : '';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $file_secritary);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $frname = isset($rowc['name']) ? safe_output($rowc['name']) : '';
                    
                    $action2 = $action2."<br>تم تغيير السكرتيرة : من $oldfrname الى $frname";
                }
                
                $file_secritary2 = (int)filter_input(INPUT_POST, 'fsc2_edit', FILTER_SANITIZE_NUMBER_INT);
                if($file_secritary2 === ''){
                    $file_secritary2 = '0';
                }
                $oldfr = isset($rowr['file_secritary2']) ? safe_output($rowr['file_secritary2']) : 0;
                if(isset($file_secritary2) && $file_secritary2 != $oldfr){
                     $flag2 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $oldfr);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $oldfrname = isset($rowc['name']) ? safe_output($rowc['name']) : '';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $file_secritary2);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $frname = isset($rowc['name']) ? safe_output($rowc['name']) : '';
                    
                    $action2 = $action2."<br>تم تغيير السكرتيرة : من $oldfrname الى $frname";
                }
                
                $file_lawyer = (int)filter_input(INPUT_POST, 'file_lawyer', FILTER_SANITIZE_NUMBER_INT);
                if($file_lawyer === ''){
                    $file_lawyer = '0';
                }
                $oldfr = isset($rowr['file_lawyer']) ? safe_output($rowr['file_lawyer']) : 0;
                if(isset($file_lawyer) && $file_lawyer != $oldfr){
                     $flag2 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $oldfr);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $oldfrname = isset($rowc['name']) ? safe_output($rowc['name']) : '';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $file_lawyer);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $frname = isset($rowc['name']) ? safe_output($rowc['name']) : '';
                    
                    $action2 = $action2."<br>تم تغيير المحامي : من $oldfrname الى $frname";
                }
                
                $file_lawyer2 = (int)filter_input(INPUT_POST, 'file_lawyer2', FILTER_SANITIZE_NUMBER_INT);
                if($file_lawyer2 === ''){
                    $file_lawyer2 = '0';
                }
                $oldfr = isset($rowr['file_lawyer2']) ? safe_output($rowr['file_lawyer2']) : 0;
                if(isset($file_lawyer2) && $file_lawyer2 != $oldfr){
                     $flag2 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $oldfr);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $oldfrname = isset($rowc['name']) ? safe_output($rowc['name']) : '';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $file_lawyer);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $frname = isset($rowc['name']) ? safe_output($rowc['name']) : '';
                    
                    $action2 = $action2."<br>تم تغيير المحامي : من $oldfrname الى $frname";
                }
                
                $fadvisor_edit = (int)filter_input(INPUT_POST, 'fadvisor_edit', FILTER_SANITIZE_NUMBER_INT);
                if($fadvisor_edit === ''){
                    $fadvisor_edit = '0';
                }
                $oldfa = isset($rowr['flegal_researcher']) ? safe_output($rowr['flegal_researcher']) : 0;
                if(isset($fadvisor_edit) && intVal($fadvisor_edit) != $oldfa){
                     $flag2 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $oldfa);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $oldfrname = isset($rowc['name']) ? safe_output($rowc['name']) : '';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $fadvisor_edit);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $frname = isset($rowc['name']) ? safe_output($rowc['name']) : '';
                    
                    $action2 = $action2."<br>تم تغيير المستشار القانوني : من $oldfrname الى $frname";
                }
                
                $fadvisor2_edit = (int)filter_input(INPUT_POST, 'fadvisor2_edit', FILTER_SANITIZE_NUMBER_INT);
                if($fadvisor2_edit === ''){
                    $fadvisor2_edit = '0';
                }
                $oldfa = isset($rowr['flegal_researcher2']) ? safe_output($rowr['flegal_researcher2']) : 0;
                if(isset($fadvisor2_edit) && intVal($fadvisor2_edit) != $oldfa){
                     $flag2 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $oldfa);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $oldfrname = isset($rowc['name']) ? safe_output($rowc['name']) : '';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $fadvisor2_edit);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $stmt->close();
                    $frname = isset($rowc['name']) ? safe_output($rowc['name']) : '';
                    
                    $action2 = $action2."<br>تم تغيير المستشار القانوني : من $oldfrname الى $frname";
                }
                
                $fidd = $fid;
                
                function formatSize($bytes) {
                    if ($bytes < 1024) {
                        return $bytes . ' B';
                    } elseif ($bytes < 1048576) {
                        return round($bytes / 1024, 2) . ' KB';
                    } elseif ($bytes < 1073741824) {
                        return round($bytes / (1024 * 1024), 2) . ' MB';
                    } else {
                        return round($bytes / (1024 * 1024 * 1024), 2) . ' GB';
                    }
                }
                
                $empid = $_SESSION['id'];
                $timestamp = date("Y-m-d H:i:s");
                
                if (!empty($_FILES['attach_files_multi']['name'][0])) {
                    $multiCount = count($_FILES['attach_files_multi']['name']);
                    
                    for ($j = 0; $j < $multiCount; $j++) {
                        $file = [
                            'name'     => $_FILES['attach_files_multi']['name'][$j],
                            'type'     => $_FILES['attach_files_multi']['type'][$j],
                            'tmp_name' => $_FILES['attach_files_multi']['tmp_name'][$j],
                            'error'    => $_FILES['attach_files_multi']['error'][$j],
                            'size'     => $_FILES['attach_files_multi']['size'][$j],
                        ];
                        
                        $targetDir = "files_images/file_upload/$fid";
                        
                        $upload = secure_file_upload($file, $targetDir);
                        
                        if ($upload['status']) {
                            $destination = $upload['path'];
                            $fileExtension = strtolower(pathinfo($destination, PATHINFO_EXTENSION));
                            $fileSizeReadable = $upload['size'];
                            
                            $stmt = $conn->prepare("INSERT INTO files_attachments (fid, attachment, attachment_type, attachment_size, done_by, timestamp) VALUES (?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param("isssis", $fid, $destination, $fileExtension, $fileSizeReadable, $empid, $timestamp);
                            $stmt->execute();
                            $stmt->close();
                        } else {
                            error_log("Upload failed for file {$file['name']}: " . $upload['error']);
                        }
                    }
                }
                
                $timestamp = date('Y-m-d H:i:s');
                
                $stmt = $conn->prepare("UPDATE file SET file_type = ?, important = ?, frelated_place = ?, file_class = ?, file_status = ?, file_subject = ?, file_notes = ?, file_client = ?, file_client2 = ?, file_client3 = ?, file_client4 = ?, file_client5 = ?,
                fclient_characteristic = ?, fclient_characteristic2 = ?, fclient_characteristic3 = ?, fclient_characteristic4 = ?, fclient_characteristic5 = ?, file_opponent = ?, file_opponent2 = ?, file_opponent3 = ?, file_opponent4 = ?, file_opponent5 = ?, 
                fopponent_characteristic = ?, fopponent_characteristic2 = ?, fopponent_characteristic3 = ?, fopponent_characteristic4 = ?, fopponent_characteristic5 = ?, fpolice_station = ?, fcase_type = ?, file_court = ?, file_prosecution = ?, 
                flegal_researcher = ?, flegal_researcher2 = ?, flegal_advisor = ?, flegal_advisor2 = ?, file_secritary = ?, file_secritary2 = ?, file_lawyer = ?, file_lawyer2 = ?, done_by = ?, file_timestamp = ? WHERE file_id = ?");    
                $stmt->bind_param("sisssssiiiiisssssiiiiisssssssssiiiiiiiiisi", $type_edit, $important, $place_edit, $fclass_edit, $status_edit, $fsubject_edit, $fnotes_edit, $fclient_edit, $fclient_edit2, $fclient_edit3, $fclient_edit4, $fclient_edit5, $fchar_edit, 
                $fchar_edit2, $fchar_edit3, $fchar_edit4, $fchar_edit5, $fopponent_edit, $fopponent_edit2, $fopponent_edit3, $fopponent_edit4, $fopponent_edit5, $fopponent_charedit, $fopponent_charedit2, $fopponent_charedit3, $fopponent_charedit4, $fopponent_charedit5, 
                $fpolice_edit, $fctype_edit, $fcourt_edit, $fprosecution2, $fresearcher_edit, $fresearcher2_edit, $fadvisor_edit, $fadvisor2_edit, $file_secritary, $file_secritary2, $file_lawyer, $file_lawyer2, $emp_name, $timestamp, $fid);
                $stmt->execute();
                $stmt->close();
                
                if(isset($flag2) && $flag2 === '1'){
                    include_once 'addlog.php';
                    $timer_flag = 'file_edit';
                    include_once 'timerfunc.php';
                }
            }
        }
        if(isset($_REQUEST['fdegree_edit']) && $_REQUEST['fdegree_edit'] !== ''){
            if($row_permcheck['degree_aperm'] == 1){
                $degree = filter_input(INPUT_POST, "fdegree_edit", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                
                $case_num = filter_input(INPUT_POST, 'fcaseno_edit', FILTER_SANITIZE_NUMBER_INT);
                
                $file_year = filter_input(INPUT_POST, 'fyear_edit', FILTER_SANITIZE_NUMBER_INT);
                
                $client_characteristic = filter_input(INPUT_POST, "fccharacteristic_edit", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                
                $opponent_characteristic = filter_input(INPUT_POST, "focharacteristic_edit", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                
                $timestamp = date("Y-m-d");
                
                $stmtcheck = $conn->prepare("SELECT * FROM session WHERE session_fid=? AND resume_appeal!='0'");
                $stmtcheck->bind_param("i", $fid);
                $stmtcheck->execute();
                $resultcheck = $stmtcheck->get_result();
                while($rowcheck = $resultcheck->fetch_assoc()){
                    $sid = $rowcheck['session_id'];
                    $stmtdel = $conn->prepare("UPDATE session SET resume_appeal='0', resume_overdue='', resume_daysno='0' WHERE session_id=?");
                    $stmtdel->bind_param("i", $sid);
                    $stmtdel->execute();
                    $stmtdel->close();
                }
                $stmtcheck->close();
                
                $flag = '0';
                $action = "تم اضافة قضية جديدة :<br>رقم الملف : $fid<br>";
                
                if(isset($degree) && $degree !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>درجة التقاضي : $degree";
                }
                
                if(isset($case_num) && $case_num !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>رقم القضية : $case_num";
                }
                
                if(isset($file_year) && $file_year !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>السنة : $file_year";
                }
                
                if(isset($client_characteristic) && $client_characteristic !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>صفة الموكل : $client_characteristic";
                }
                
                if(isset($opponent_characteristic) && $opponent_characteristic !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>صفة الخصم : $opponent_characteristic";
                }
                
                $stmt_degs = $conn->prepare("INSERT INTO file_degrees (fid, degree, case_num, file_year, client_characteristic, opponent_characteristic, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt_degs->bind_param("isiisss", $fid, $degree, $case_num, $file_year, $client_characteristic, $opponent_characteristic, $timestamp);
                $stmt_degs->execute();
                $stmt_degs->close();
                
                if(isset($flag) && $flag === '1'){
                    include_once 'addlog.php';
                    $timer_flag = 'degree_add';
                    include_once 'timerfunc.php';
                }
            }
        }
        if(isset($_REQUEST['start_date']) && $_REQUEST['start_date'] !== '' && isset($_REQUEST['warning_duration']) && $_REQUEST['warning_duration'] !== ''){
            if($row_permcheck['judicialwarn_aperm'] == 1){
                $fid = filter_input(INPUT_POST, 'fid', FILTER_SANITIZE_NUMBER_INT);
                
                $flag3 = '0';
                $action3 = "تم اضافة انذار عدلي على الملف رقم : $fid<br>";
                
                $ratification_date = filter_input(INPUT_POST, "ratification_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($ratification_date) && $ratification_date !== ''){
                    $flag3 = '1';
                    
                    $action3 = $action3."<br>تاريخ التصديق : $ratification_date";
                }
                
                $start_date = filter_input(INPUT_POST, "start_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($start_date) && $start_date !== ''){
                    $flag3 = '1';
                    
                    $action3 = $action3."<br>تاريخ التسليم : $start_date";
                }
                
                $warning_duration = filter_input(INPUT_POST, 'warning_duration', FILTER_SANITIZE_NUMBER_INT);
                if(isset($warning_duration) && $warning_duration !== ''){
                    $flag3 = '1';
                    
                    $action3 = $action3."<br>مدة الانذار : $warning_duration";
                }
                
                $today = new DateTime($start_date);
                
                $future_date = clone $today;
                $future_date->modify("+$warning_duration days");
                $future = $future_date->format("Y-m-d");
                
                if($flag3 === '1'){
                    include_once 'addlog.php';
                    $timer_flag = 'judicialwarn_add';
                    include_once 'timerfunc.php';
                }
                
                $stmt = $conn->prepare("INSERT INTO judicial_warnings (fid, ratification_date, given_date, warning_duration, duedate) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issis", $fid, $ratification_date, $start_date, $warning_duration, $future);
                $stmt->execute();
                $stmt->close();
            }
        }
        if(isset($_REQUEST['Hearing_dt']) && $_REQUEST['Hearing_dt'] !== '' && isset($_REQUEST['session_degree']) && $_REQUEST['session_degree'] !== ''){
            if($row_permcheck['session_aperm'] == 1){
                $session_fid = filter_input(INPUT_POST, 'session_fid', FILTER_SANITIZE_NUMBER_INT);
                
                $flag4 = '0';
                $action4 = "تم اضافة جلسة جديدة :<br>رقم الملف : $session_fid<br>";
                
                $session_decission = filter_input(INPUT_POST, "session_decission", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($session_decission) && $session_decission !== ''){
                    $flag4 = '1';
                    
                    $action4 = $action4."<br>منطوق الحكم : $session_decission";
                }
                
                if(isset($_REQUEST['session_degree'])){
                    $session_degree = filter_input(INPUT_POST, "session_degree", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    if(isset($session_degree) && $session_degree !== ''){
                        $flag4 = '1';
                        
                        $action4 = $action4."<br>درجة التقاضي : $session_degree";
                    }
                    
                    list($ycn, $degree) = explode('-', $session_degree);
                    list($year, $case_num) = explode('/', $ycn);
                } else{
                    $session_degree = '';
                }
                
                $session_details = filter_input(INPUT_POST, "session_details", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($session_details) && $session_details !== ''){
                    $flag4 = '1';
                    
                    $action4 = $action4."<br>تفاصيل الجلسة : $session_details";
                }
                
                $session_date = filter_input(INPUT_POST, "Hearing_dt", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($session_dateN) && $session_dateN !== ''){
                    $flag4 = '1';
                    
                    $action4 = $action4."<br>تاريخ الجلسة : $session_dateN";
                }
                
                $expert_session = filter_input(INPUT_POST, 'expert_session', FILTER_SANITIZE_NUMBER_INT);
                if(isset($expert_session) && $expert_session !== ''){
                    $flag4 = '1';
                    
                    if($expert_session == 1){
                        $action4 = $action4."<br>جلسة خبرة";
                    }
                }
                
                $expert_amount = filter_input(INPUT_POST, "expert_amount", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($expert_amount) && $expert_amount !== ''){
                    $flag4 = '1';
                    
                    $action4 = $action4."<br>مبلغ امانة الخبرة : $expert_amount";
                }
                
                $expert_addr = filter_input(INPUT_POST, "expert_addr", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($expert_addr) && $expert_addr !== ''){
                    $flag4 = '1';
                    
                    $action4 = $action4."<br>عنوان الخبير : $expert_addr";
                }
                
                $expert_name = filter_input(INPUT_POST, "expert_name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($expert_name) && $expert_name !== ''){
                    $flag4 = '1';
                    
                    $action4 = $action4."<br>اسم الخبير : $expert_name";
                }
                
                $expert_phone = filter_input(INPUT_POST, "expert_phone", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($expert_phone) && $expert_phone !== ''){
                    $flag4 = '1';
                    
                    $action4 = $action4."<br>هاتف الخبير : $expert_phone";
                }
                
                $link = filter_input(INPUT_POST, "link", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($link) && $link !== ''){
                    $flag4 = '1';
                    
                    $action4 = $action4."<br>رابط الجلسة : $link";
                }
                
                $timestampdate = date('Y-m-d');
                $done_by = $_SESSION['id'];
                $timestamp = $timestampdate.'<br>'.$done_by;
                
                $fidd = $session_fid;
                
                $stmt = $conn->prepare("INSERT INTO session (session_fid, session_date, session_details, session_degree, case_num, year, session_decission, expert_session, expert_name, expert_phone, expert_amount, expert_address, link, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isssiisississs", $session_fid, $session_date, $session_details, $degree, $case_num, $year, $session_decission, $expert_session, $expert_name, $expert_phone, $expert_amount, $expert_addr, $link, $timestamp);
                $stmt->execute();
                $stmt->close();
                
                $stmt = $conn->prepare("UPDATE file SET sessions_check = ? WHERE file_id = ?");
                $sessions_check = '1';
                $stmt->bind_param("ss", $sessions_check, $fidd);
                $stmt->execute();
                $stmt->close();
                
                if(isset($_REQUEST['link']) && $_REQUEST['link'] !== ''){
                    $link = filter_input(INPUT_POST, "link", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    
                    $flag4 = '1';
                    $action4 = $action4."<br>تم تغيير رابط الجلسة : من $oldlink الى $link";
                    $linkaction = "تم تغيير رابط احد جلسات الملف رقم $session_fid : من $oldlink الى $link";
                    
                    if(isset($expert_session) && $expert_session == 1){
                        $link_for = "جلسات الخبرة";
                    } else {
                        $link_for = "جلسات المحكمة";
                    }
                    
                    $responsible = $_SESSION['id'];
                    $timestamp = date("Y-m-d");
                    
                    $stmt = $conn->prepare("INSERT INTO fast_links (link, link_for, responsible, timestamp) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssis", $link, $link_for, $responsible, $timestamp);
                    $stmt->execute();
                    $stmt->close();
                    
                    include_once 'addlog.php';
                }
                
                $empid = $_SESSION['id'];
                $timestamp = date("Y-m-d H:i:s");
                
                if (!empty($_FILES['attach_file3']['name'])) {
                    $file = [
                        'name'     => $_FILES['attach_file3']['name'],
                        'type'     => $_FILES['attach_file3']['type'],
                        'tmp_name' => $_FILES['attach_file3']['tmp_name'],
                        'error'    => $_FILES['attach_file3']['error'],
                        'size'     => $_FILES['attach_file3']['size']
                    ];
                    
                    $targetDir = "files_images/file_upload/$session_fid";
                    $upload = secure_file_upload($file, $targetDir);
                    
                    if ($upload['status']) {
                        $destination = $upload['path'];
                        $fileExtension = strtolower(pathinfo($destination, PATHINFO_EXTENSION));
                        $fileSizeReadable = $upload['size'];
                        
                        $stmt = $conn->prepare("INSERT INTO files_attachments (fid, attachment, attachment_type, attachment_size, done_by, timestamp) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("isssis", $session_fid, $destination, $fileExtension, $fileSizeReadable, $empid, $timestamp);
                        $stmt->execute();
                        $stmt->close();
                    } else {
                        error_log("Upload failed for file {$file['name']}: " . $upload['error']);
                    }
                }
                
                $document_file1 = $rowr['document_attachment'];
                if (isset($_FILES['document_file1']) && $_FILES['document_file1']['error'] == 0) {
                    $file = [
                        'name'     => $_FILES['document_file1']['name'],
                        'type'     => $_FILES['document_file1']['type'],
                        'tmp_name' => $_FILES['document_file1']['tmp_name'],
                        'error'    => $_FILES['document_file1']['error'],
                        'size'     => $_FILES['document_file1']['size']
                    ];
                    
                    $upload = secure_file_upload($file, $targetDir);
                    
                    if ($upload['status']) {
                        echo "document_file1 1 has been uploaded.<br>";
                        $flag = '1';
                        $document_file1 = $upload['path'];
                        
                        $action = $action2."<br>تم تغيير المرفق 1";
                    } else {
                        echo "Sorry, there was an error uploading attachment 1.<br>";
                    }
                }
                
                if(isset($flag4) && $flag4 === '1'){
                    include_once 'addlog.php';
                    $timer_flag = 'hearing_add';
                    include_once 'timerfunc.php';
                }
            }
        }
        if(isset($_REQUEST['employee_name']) && $_REQUEST['employee_name'] !== '' && isset($_REQUEST['job_name']) && $_REQUEST['job_name'] !== '') {
            if($row_permcheck['admjobs_aperm'] == 1){
                $job_fid = filter_input(INPUT_POST, "job_fid", FILTER_SANITIZE_NUMBER_INT);
                
                $flag5 = '0';
                $action5 = "تم اضافة مهمة ادارية جديدة :<br>رقم الملف : $job_fid<br>";
                
                $job_priority = filter_input(INPUT_POST, 'job_priority', FILTER_SANITIZE_NUMBER_INT);
                if(isset($job_priority) && $job_priority !== ''){
                    $flag5 = '1';
                    
                    if($job_priority == 1){
                        $jb = 'عاجل';
                    } else{
                        $jb = 'عادي';
                    }
                    $action5 = $action5."<br>اهمية المهمة : $jb";
                }
                
                $job_name = filter_input(INPUT_POST, 'job_name', FILTER_SANITIZE_NUMBER_INT);
                if(isset($job_name) && $job_name !== ''){
                    $flag5 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM job_name WHERE id = ?");
                    $stmt->bind_param("i", $job_name);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $jn = $rowc['job_name'];
                    $stmt->close();
                    
                    $action5 = $action5."<br>نوع المهمة : $jn";
                }
                
                $employee_name = filter_input(INPUT_POST, 'employee_name', FILTER_SANITIZE_NUMBER_INT);
                if(isset($employee_name) && $employee_name !== ''){
                    $flag5 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $employee_name);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $empn = $rowc['name'];
                    $stmt->close();
                    
                    $action5 = $action5."<br>الموظف المكلف : $empn";
                }
                
                $responsible = filter_input(INPUT_POST, 'responsible', FILTER_SANITIZE_NUMBER_INT);
                if(isset($responsible) && $responsible !== ''){
                    $flag5 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $responsible);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $empn = $rowc['name'];
                    $stmt->close();
                    
                    $action5 = $action5."<br>المسؤول عن المهمة : $empn";
                }
                
                $job_degree = filter_input(INPUT_POST, 'job_degree', FILTER_SANITIZE_NUMBER_INT);
                if(isset($job_degree) && $job_degree !== ''){
                    $flag5 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM file_degrees WHERE id = ?");
                    $stmt->bind_param("i", $job_degree);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $deg = $rowc['file_year'].'/'.$rowc['case_num'].'-'.$rowc['degree'];
                    $stmt->close();
                    
                    $action5 = $action5."<br>درجة التقاضي : $deg";
                }
                
                $job_details = filter_input(INPUT_POST, "job_details", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($job_details) && $job_details !== ''){
                    $flag5 = '1';
                    
                    $action5 = $action5."<br>تفاصيل المهمة : $job_details";
                }
                
                $job_date = filter_input(INPUT_POST, "job_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($job_date) && $job_date !== ''){
                    $flag5 = '1';
                    
                    $action5 = $action5."<br>تاريخ التنفيذ : $job_date";
                }
                
                $timestamp = date('Y-m-d');
                
                $fidd = $job_fid;
                
                $stmt = $conn->prepare("INSERT INTO tasks (file_no, responsible, task_type, employee_id, priority, degree, details, duedate, timestamp) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iiiiissss", $job_fid, $responsible, $job_name, $employee_name, $job_priority, $job_degree, $job_details, $job_date, $timestamp);
                $stmt->execute();
                $stmt->close();
                
                if(isset($flag5) && $flag5 === '1'){
                    include_once 'addlog.php';
                    $timer_flag = 'task_add';
                    include_once 'timerfunc.php';
                    
                    $respid = $_SESSION['id'];
                    $empid = $employee_name;
                    $target = "tasks /-/ $job_fid";
                    $target_id = 0;
                    $notification = "تم تكليفك بمهمة جديدة بالملف رقم $job_fid";
                    $notification_date = date("Y-m-d");
                    $status = 0;
                    $timestamp = date("Y-m-d H:i:s");
                    
                    if($empid != 0 && $empid !== ''){
                        $stmt = $conn->prepare("INSERT INTO notifications (resp_id, empid, target, target_id, notification, notification_date, status, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("iisissis", $respid, $empid, $target, $target_id, $notification, $notification_date, $status, $timestamp);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }
        }
        if(isset($_REQUEST['job_name1']) && $_REQUEST['job_name1'] !== ''){
            if($row_permcheck['admjobs_aperm'] == 1){
                $job_fid1 = filter_input(INPUT_POST, 'job_fid1', FILTER_SANITIZE_NUMBER_INT);
                
                $flag6 = '0';
                $action6 = "تم اضافة تنفيذ جديد :<br>رقم الملف : $job_fid1<br>";
                
                $employee_id = filter_input(INPUT_POST, 'responsible1', FILTER_SANITIZE_NUMBER_INT);
                if(isset($employee_id) && $employee_id !== ''){
                    $flag6 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                    $stmt->bind_param("i", $employee_id);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $empname = $rowc['name'];
                    $stmt->close();
                    
                    $action6 = $action6."<br>الموظف المسؤول : $empname";
                }
                
                $job_priority1 = filter_input(INPUT_POST, 'job_priority1', FILTER_SANITIZE_NUMBER_INT);
                if(isset($job_priority1) && $job_priority1 !== ''){
                    $flag6 = '1';
                    
                    if($job_priority1 == 1){
                        $jp = 'عاجل';
                    } else{
                        $jp = 'عادي';
                    }
                    $action6 = $action6."<br>اهمية التنفيذ : $jp";
                }
                
                $job_name1 = filter_input(INPUT_POST, 'job_name1', FILTER_SANITIZE_NUMBER_INT);
                if(isset($job_name1) && $job_name1 !== ''){
                    $flag6 = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM job_name WHERE id = ?");
                    $stmt->bind_param("i", $job_name1);
                    $stmt->execute();
                    $resultc = $stmt->get_result();
                    $rowc = $resultc->fetch_assoc();
                    $jname = $rowc['job_name'];
                    $stmt->close();
                    
                    $action6 = $action6."<br>نوع العمل الاداري : $jname";
                }
                
                $degree = filter_input(INPUT_POST, 'resapp', FILTER_SANITIZE_NUMBER_INT);
                if(isset($degree) && $degree !== ''){
                    $flag6 = '1';
                    
                    if($degree == 1){
                        $dg = 'استئناف';
                    } else if($degree == 2){
                        $dg = 'طعن';
                    } else if($degree == 3){
                        $dg = 'تظلم';
                    }
                    $action6 = $action6."<br>الدرجة : $dg";
                }
                
                $decision = filter_input(INPUT_POST, "decision", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($decision) && $decision !== ''){
                    $flag6 = '1';
                    
                    $action6 = $action6."<br>قرار القاضي : $decision";
                }
                
                $job_details1 = filter_input(INPUT_POST, "job_details1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($job_details1) && $job_details1 !== ''){
                    $flag6 = '1';
                    
                    $action6 = $action6."<br>التفاصيل : $job_details1";
                }
                
                $job_date1 = filter_input(INPUT_POST, "job_date1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($job_date1) && $job_date1 !== ''){
                    $flag6 = '1';
                    
                    $action6 = $action6."<br>تاريخ التنفيذ : $job_date1";
                }
                
                $timestamp1 = date('Y-m-d');
                $jud_session = 1;
                
                $fidd = $job_fid1;
                $degree_id_sess = 'تنفيذ';
                
                $resume_appeal = filter_input(INPUT_POST, 'resapp', FILTER_SANITIZE_NUMBER_INT);
                
                if($resume_appeal == 1){
                    $days = 15;
                } else if($resume_appeal == 2){
                    $days = 30;
                } else if($resume_appeal == 3){
                    $days = 7;
                }
                
                $newDate = new DateTime($job_date1);
                $newDate->modify("+$days days");
                $newDateFormatted = $newDate->format('Y-m-d');
                
                $today = new DateTime();
                $newDateObject = new DateTime($newDateFormatted);
                $difference = $today->diff($newDateObject);
                $differenceDays = $difference->days;
                
                $done_by = $_SESSION['id'];
        
                $stmt = $conn->prepare("INSERT INTO execution (employee_id, file_no, decision, task_type, degree, priority, details, duedate, done_by, timestamp) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iisisissis", $employee_id, $job_fid1, $decision, $job_name1, $degree, $job_priority1, $job_details1, $job_date1, $done_by, $timestamp1);
                $stmt->execute();
                $stmt->close();
        
                $empty = '';
                $stmt = $conn->prepare("INSERT INTO session (session_fid, jud_session, session_date, session_details, session_degree, timestamp, resume_appeal, resume_overdue, resume_daysno, file_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iissssisis", $job_fid1, $jud_session, $job_date1, $job_details1, $degree_id_sess, $timestamp1, $resume_appeal, $newDateFormatted, $differenceDays, $empty);
                $stmt->execute();
                $stmt->close();
                
                if(isset($flag6) && $flag6 === '1'){
                    include_once 'addlog.php';
                    $timer_flag = 'execution_add';
                    include_once 'timerfunc.php';
                }
            }
        } else if(isset($_REQUEST['petition_date']) && $_REQUEST['petition_date'] !== '' && isset($_REQUEST['petition_decision']) && $_REQUEST['petition_decision'] !== ''){
            if($row_permcheck['petition_aperm'] == 1){
                $fid = filter_input(INPUT_POST, 'petition_fid', FILTER_SANITIZE_NUMBER_INT);
                
                $flag7 = '0';
                $action7 = "تم اضافة امر على عريضة جديد على الملف : $fid<br>";
                
                $petition_date = filter_input(INPUT_POST, "petition_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($petition_date) && $petition_date !== ''){
                    $flag7 = '1';
                    
                    $action7 = $action7."<br>تاريخ التقديم : $petition_date";
                }
                
                $petition_type = filter_input(INPUT_POST, "petition_type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($petition_type) && $petition_type !== ''){
                    $flag7 = '1';
                    
                    $action7 = $action7."<br>نوع الامر : $petition_type";
                }
                
                $petition_decision = filter_input(INPUT_POST, "petition_decision", FILTER_SANITIZE_NUMBER_INT);
                
                $today = date("Y-m-d");
                $today = new DateTime($today);
                $future_date = clone $today;
                
                $notfid = $fid;
                $target = "petition // $notfid";
                $target_id = '';
                if($petition_decision == 0){
                    $future_date->modify("+7 days");
                    $appeal_lastdate = $future_date->format("Y-m-d");
                    $hearing_lastdate = '';
                    $petition_decision = "رفض";
                    
                    $flag7 = '1';
                    $action7 = $action7."<br>قرار القاضي : رفض<br>اخر تاريخ للتظلم : $appeal_lastdate";
                    $notification = "اعداد لائحة الدعوى على الامر على عريضة في الملف رقم $notfid";
                } else if($petition_decision == 1){
                    $future_date->modify("+8 days");
                    $hearing_lastdate = $future_date->format("Y-m-d");
                    $appeal_lastdate = '';
                    $petition_decision = "موافقة";
                    
                    $flag7 = '1';
                    $action7 = $action7."<br>قرار القاضي : قبول<br>اخر تاريخ لتسجيل قيد الدعوى : $hearing_lastdate";
                    $notification = "اعداد لائحة التظلم على الامر على عريضة في الملف رقم $notfid";
                }
                
                include_once 'notification_add.php';
                
                $timestamp = $_SESSION['id'] . '<br>' . date("Y-m-d");
                
                $stmt = $conn->prepare("INSERT INTO petition (fid, date, type, decision, hearing_lastdate, appeal_lastdate, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issssss", $fid, $petition_date, $petition_type, $petition_decision, $hearing_lastdate, $appeal_lastdate, $timestamp);
                $stmt->execute();
                $stmt->close();
                
                if($flag7 === '1'){
                    include_once 'addlog.php';
                    $timer_flag = 'petition_add';
                    include_once 'timerfunc.php';
                }
            }
        }
        unset($_SESSION['form_data']);
        header("Location: FileEdit.php?id=$fid");
        exit();
    } else if(isset($_GET['sid']) && $_GET['sid'] !== ''){
        if($row_permcheck['session_dperm'] == 1){
            $fid = $_GET['fid'];
            $sid = $_GET['sid'];
            
            $stmt = $conn->prepare("SELECT * FROM session WHERE session_id = ?");
            $stmt->bind_param("i", $sid);
            $stmt->execute();
            $resultr = $stmt->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmt->close();
            
            $flag = '0';
            $action = "تم حذف جلسة :<br>رقم الملف : $fid<br>";
            
            $oldjuds = $rowr['jud_session'];
            if($oldjuds == 1){
                $flag = '1';
                
                $action = $action."<br>حجزت للحكم : نعم";
            } else{
                $flag = '1';
                
                $action = $action."<br>حجزت للحكم : لا";
            }
            
            $resume_appeal = $rowr['resume_appeal'];
            
            if(isset($resume_appeal) && $resume_appeal !== '' && $resume_appeal != 0){
                if($resume_appeal == 1){
                    $ra = 'حذف استئناف';
                    $ran = 'تم حذف الاستئناف بناءا على طلب الموكل';
                } else if($resume_appeal == 2){
                    $ra = 'حذف طعن';
                    $ran = 'تم حذف الطعن بناءا على طلب الموكل';
                } else if($resume_appeal == 3){
                    $ra = 'حذف تظلم';
                    $ran = 'تم حذف تظلم بناءا على طلب الموكل';
                } else if($resume_appeal == 4){
                    $ra = 'حذف معارضة';
                    $ran = 'تم حذف المعارضة بناءا على طلب الموكل';
                }
                
                $flag = '1';
                
                $action = $action."<br>$ra";
                
                $id = $_SESSION['id'];
                $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $resultd = $stmt->get_result();
                $rowd = $resultd->fetch_assoc();
                $name = $rowd['name'];
                $stmt->close();
                
                $timestamp = date("Y-m-d");
                
                $stmt2 = $conn->prepare("INSERT INTO file_note (file_id, notename, note, doneby, timestamp) VALUES (?, ?, ?, ?, ?)");
                $stmt2->bind_param("issss", $fid, $ra, $ran, $name, $timestamp);
                $stmt2->execute();
                $stmt2->close();
            }
            
            $oldoverd = $rowr['resume_overdue'];
            if(isset($oldoverd) && $oldoverd !== ''){
                $flag = '1';
                
                $action = $action."<br>حجزت لتاريخ : $oldoverd";
            }
            
            $olddno = $rowr['resume_daysno'];
            if(isset($olddno) && $olddno !== ''){
                $flag = '1';
                
                $action = $action."<br>عدد الايام المتبقية : $olddno";
            }
            
            $olddet = $rowr['session_details'];
            if(isset($olddet) && $olddet !== ''){
                $flag = '1';
                
                $action = $action."<br>تفاصيل الجلسة : $olddet";
            }
            
            $olddno = $rowr['resume_daysno'];
            if(isset($olddno) && $olddno !== ''){
                $flag = '1';
                
                $action = $action."<br>عدد الايام المتبقية : $olddno";
            }
            
            $olddeg = $rowr['session_degree'];
            $oldcno = $rowr['case_num'];
            $oldyr = $rowr['year'];
            if(isset($olddeg) && $olddeg !== ''){
                $flag = '1';
                
                $action = $action."<br>درجة التقاضي : $oldyr/$oldcno-$olddeg";
            }
            
            $oldtr = $rowr['session_trial'];
            if(isset($oldtr) && $oldtr !== ''){
                $flag = '1';
                
                $action = $action."<br>منطوق الحكم : $oldtr";
            }
            
            $oldex = $rowr['expert_session'];
            if($oldex == 1){
                $flag = '1';
                
                $action = $action."<br>جلسة خبرة : نعم";
            } else{
                $flag = '1';
                
                $action = $action."<br>جلسة خبرة : لا";
            }
            
            $oldexn = $rowr['expert_name'];
            if(isset($oldexn) && $oldexn !== ''){
                $flag = '1';
                
                $action = $action."<br>اسم الخبير : $oldexn";
            }
            
            $oldexp = $rowr['expert_phone'];
            if(isset($oldexp) && $oldexp !== ''){
                $flag = '1';
                
                $action = $action."<br>هاتف الخبير : $oldexp";
            }
            
            $oldexa = $rowr['expert_amount'];
            if(isset($oldexa) && $oldexa !== ''){
                $flag = '1';
                
                $action = $action."<br>مبلغ امانة الخبير : $oldexa";
            }
            
            $oldexad = $rowr['expert_address'];
            if(isset($oldexad) && $oldexad !== ''){
                $flag = '1';
                
                $action = $action."<br>عنوان الخبير : $oldexad";
            }
            
            $rcc = $rowr['referral_reconciliation'];
            if($rcc = '1'){
                $flag = '1';
                
                $action = $action."<br>تمت الاحالة";
            } else if($rcc = '2'){
                $flag = '1';
                
                $action = $action."<br>تم الصلح";
            }
            
            if(isset($flag) && $flag !== ''){
                include_once 'addlog.php';
            }
            
            $fidd = $fid;
            
            $stmt5 = $conn->prepare("DELETE FROM session WHERE session_id = ?");
            $stmt5->bind_param("i", $sid);
            $stmt5->execute();
            $stmt5->close();
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$fid");
            exit();
        }
    } else if(isset($_GET['tid']) && $_GET['tid'] !== ''){
        if($row_permcheck['admjobs_dperm'] == 1){
            $tid = $_GET['tid'];
            $fid = $_GET['fid'];
            
            $stmtr = $conn->prepare("SELECT * FROM tasks WHERE id = ?"); 
            $stmtr->bind_param("i", $tid); 
            $stmtr->execute(); 
            $resultr = $stmtr->get_result(); 
            $rowr = $resultr->fetch_assoc();
            $stmtr->close();
            
            $oldres = $rowr['responsible'];
            $oldemp = $rowr['employee_id'];
            $oldfid = $rowr['file_no'];
            $oldtt = $rowr['task_type'];
            $oldpr = $rowr['priority'];
            $olddeg = $rowr['degree'];
            $olddue = $rowr['duedate'];
            $olddet = $rowr['details'];
            $oldsta = $rowr['task_status'];
            
            $flag = '0';
            $action = "تم حذف عمل اداري :<br>رقم الملف : $oldfid<br>";
            
            if(isset($oldres) && $oldres !== ''){
                $flag = '1';
                
                $stmt_user = $conn->prepare("SELECT * FROM user WHERE id = ?");
                $stmt_user->bind_param("i", $oldres);
                $stmt_user->execute();
                $resultc = $stmt_user->get_result();
                $rowc = $resultc->fetch_assoc();
                $resn = $rowc['name'];
                $stmt_user->close();
                
                $action = $action."<br>المسؤول عن العمل الاداري : $resn";
            }
            
            if(isset($oldemp) && $oldemp !== ''){
                $flag = '1';
                
                $stmt_emp = $conn->prepare("SELECT * FROM user WHERE id = ?");
                $stmt_emp->bind_param("i", $oldemp);
                $stmt_emp->execute();
                $resultc = $stmt_emp->get_result();
                $rowc = $resultc->fetch_assoc();
                $empn = $rowc['name'];
                $stmt_emp->close();
                
                $action = $action."<br>الموظف المكلف : $empn";
            }
            
            if(isset($oldtt) && $oldtt !== ''){
                $flag = '1';
                
                $stmt_job = $conn->prepare("SELECT * FROM job_name WHERE id = ?");
                $stmt_job->bind_param("i", $oldtt);
                $stmt_job->execute();
                $resultc = $stmt_job->get_result();
                $rowc = $resultc->fetch_assoc();
                $jobn = $rowc['job_name'];
                $stmt_job->close();
                
                $action = $action."<br>نوع العمل : $jobn";
            }
            
            if(isset($oldpr) && $oldpr !== ''){
                $flag = '1';
                
                if($oldpr == 0){
                    $pr = "عادي";
                } else{
                    $pr = 'عاجل';
                }
                
                $action = $action."<br>اهمية العمل : $pr";
            }
            
            if(isset($olddeg) && $olddeg !== ''){
                $flag = '1';
                
                $stmt_deg = $conn->prepare("SELECT * FROM file_degrees WHERE id = ?");
                $stmt_deg->bind_param("i", $olddeg);
                $stmt_deg->execute();
                $resultc = $stmt_deg->get_result();
                $rowc = $resultc->fetch_assoc();
                $fulldeg = $rowc['file_year'].'/'.$rowc['case_num'].'-'.$rowc['degree'];
                $stmt_deg->close();
                
                $action = $action."<br>درجة التقاضي : $fulldeg";
            }
            
            if(isset($olddue) && $olddue !== ''){
                $flag = '1';
                
                $action = $action."<br>تاريخ التنفيذ : $olddue";
            }
            
            if(isset($olddet) && $olddet !== ''){
                $flag = '1';
                
                $action = $action."<br>التفاصيل : $olddet";
            }
            
            if(isset($oldsta) && $oldsta !== ''){
                $flag = '1';
                
                if($oldsta == 1){
                    $status = 'جاري العمل عليه';
                } else if($oldsta == 2){
                    $status = 'تم الانتهاء';
                } else{
                    $status = 'لم يتخذ اجراء';
                }
                
                $action = $action."<br>حالة العمل : $status";
            }
            
            $fidd = $fid;
            
            $stmt_del = $conn->prepare("DELETE FROM tasks WHERE id = ?");
            $stmt_del->bind_param("i", $tid);
            $stmt_del->execute();
            $stmt_del->close();
            
            if(isset($flag) && $flag !== ''){
                include_once 'addlog.php';
            }
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$fid");
            exit();
        }
    } else if(isset($_GET['eid']) && $_GET['eid'] !== ''){
        if($row_permcheck['admjobs_dperm'] == 1){
            $eid = $_GET['eid'];
            $fid = $_GET['fid'];
            
            $stmt = $conn->prepare("SELECT * FROM execution WHERE id = ?");
            $stmt->bind_param("i", $eid);
            $stmt->execute();
            $resultr = $stmt->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmt->close();
            
            $oldemp = $rowr['employee_id'];
            $oldfid = $rowr['file_no'];
            $oldtt = $rowr['task_type'];
            $olddeg = $rowr['degree'];
            $oldpr = $rowr['priority'];
            $olddue = $rowr['duedate'];
            $olddet = $rowr['details'];
            $olddec = $rowr['decision'];
            
            $flag = '0';
            $action = "تم حذف تنفيذ :<br>رقم الملف : $oldfid<br>";
            
            if(isset($oldemp) && $oldemp !== ''){
                $flag = '1';
                
                $stmt_emp = $conn->prepare("SELECT * FROM user WHERE id = ?");
                $stmt_emp->bind_param("i", $oldemp);
                $stmt_emp->execute();
                $resultc = $stmt_emp->get_result();
                $rowc = $resultc->fetch_assoc();
                $empn = $rowc['name'];
                $stmt_emp->close();
                
                $action = $action."<br>الموظف المسؤول : $empn";
            }
            
            if(isset($oldtt) && $oldtt !== ''){
                $flag = '1';
                
                $stmt_job = $conn->prepare("SELECT * FROM job_name WHERE id = ?");
                $stmt_job->bind_param("i", $oldtt);
                $stmt_job->execute();
                $resultc = $stmt_job->get_result();
                $rowc = $resultc->fetch_assoc();
                $jobn = $rowc['job_name'];
                $stmt_job->close();
                
                $action = $action."<br>نوع العمل الاداري : $jobn";
            }
            
            if(isset($olddeg) && $olddeg !== ''){
                $flag = '1';
                
                if($olddeg == 1){
                    $dg = 'استئناف';
                } else{
                    $dg = 'تظلم';
                }
                $action = $action."<br>الدرجة : $dg";
            }
            
            if(isset($oldpr) && $oldpr !== ''){
                $flag = '1';
                
                if($oldpr == 1){
                    $pr = 'عاجل';
                } else{
                    $pr = 'عادي';
                }
                $action = $action."<br>اهمية العمل الاداري : $pr";
            }
            
            if(isset($olddue) && $olddue !== ''){
                $flag = '1';
                
                $action = $action."<br>تاريخ التنفيذ : $olddue";
            }
            
            if(isset($olddet) && $olddet !== ''){
                $flag = '1';
                
                $action = $action."<br>التفاصيل : $olddet";
            }
            
            if(isset($olddec) && $olddec !== ''){
                $flag = '1';
                
                $action = $action."<br>قرار القاضي : $olddec";
            }
            
            $fidd = $fid;
            
            $stmt_del = $conn->prepare("DELETE FROM execution WHERE id = ?");
            $stmt_del->bind_param("i", $eid);
            $stmt_del->execute();
            $stmt_del->close();
            
            if(isset($flag) && $flag !== ''){
                include_once 'addlog.php';
            }
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$fid");
            exit();
        }
    } else if(isset($_GET['sid2'])){
        if($row_permcheck['session_dperm'] == 1){
            $sid = $_GET['sid2'];
            $fid = $_GET['fid'];
            
            $stmt = $conn->prepare("SELECT * FROM session WHERE session_id = ?");
            $stmt->bind_param("i", $sid);
            $stmt->execute();
            $resultr = $stmt->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmt->close();
            
            $resume_appeal = $rowr['resume_appeal'];
            
            if($resume_appeal == 1){
                $ra = 'حذف استئناف';
                $ran = 'تم حذف الاستئناف بناءا على طلب الموكل';
            } else if($resume_appeal == 2){
                $ra = 'حذف طعن';
                $ran = 'تم حذف الطعن بناءا على طلب الموكل';
            } else if($resume_appeal == 3){
                $ra = 'حذف تظلم';
                $ran = 'تم حذف تظلم بناءا على طلب الموكل';
            } else if($resume_appeal == 4){
                $ra = 'حذف معارضة';
                $ran = 'تم حذف المعارضة بناءا على طلب الموكل';
            }
            
            $empid = $_SESSION['id'];
            $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
            $stmt->bind_param("i", $empid);
            $stmt->execute();
            $resultd = $stmt->get_result();
            $rowd = $resultd->fetch_assoc();
            $name = $rowd['name'];
            $stmt->close();
            
            $timestamp = date("Y-m-d");
            
            $caseno = $rowr['case_num'];
            $year = $rowr['year'];
            $degree = $rowr['session_degree'];
            $sedate = $rowr['session_date'];
            $daysno = $rowr['resume_daysno'];
            $daysno = $daysno.'يوم';
            
            $stmt2 = $conn->prepare("INSERT INTO file_note (file_id, notename, note, doneby, timestamp) VALUES (?, ?, ?, ?, ?)");
            $stmt2->bind_param("issss", $fid, $ra, $ran, $name, $timestamp);
            $stmt2->execute();
            $stmt2->close();
            
            $stmt3 = $conn->prepare("DELETE FROM session WHERE session_id = ?");
            $stmt3->bind_param("i", $sid);
            $stmt3->execute();
            $stmt3->close();
            
            $action = "تم $ra : <br><br>رقم القضية : $caseno/$year-$degree<br>رقم الملف : $fid<br>سبب الحذف : بناءا على طلب الموكل<br><br>معلومات اضافية :<br>تاريخ الجلسة : $sedate<br>المدة : $daysno";
            include_once 'addlog.php';
            
            $fidd = $fid;
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$fid");
            exit();
        }
    } else if(isset($_GET['referral']) && $_GET['referral'] === '1'){
        if($row_permcheck['session_eperm'] == 1){
            $id = $_GET['referralfid'];
            $fidd = $id;
            $sid = $_GET['referralsid'];
            $rr = 1;
            
            $action = "تم التعديل على الجلسة :<br>رقم الملف : $id<br><br> حالة الجلسة : تمت الاحالة";
            
            $stmt3 = $conn->prepare("UPDATE session SET referral_reconciliation = ? WHERE session_id = ?");
            $stmt3->bind_param("ii", $rr, $sid);
            $stmt3->execute();
            $stmt3->close();
            
            include_once 'addlog.php';
            $timer_flag = 'hearing_referral';
            include_once 'timerfunc.php';
            
            unset($_SESSION['form_data']);
            
            if(isset($_GET['page']) && $_GET['page'] === 'FileEdit.php'){
                header("Location: FileEdit.php?id=$id");
            } else if(isset($_GET['page']) && $_GET['page'] === 'index.php'){
                header("Location: index.php");
            } else{
                $queryString = '';
                if(isset($_GET['tw'])){
                    header("Location: hearing.php?tw=1");
                } else{
                    if(isset($_GET['from'])){
                        if($queryString === ''){
                            $queryString .= "?from=".$_GET['from'];
                        } else{
                            $queryString .= "&from=".$_GET['from'];
                        }
                    }
                    if(isset($_GET['to'])){
                        if($queryString === ''){
                            $queryString .= "?to=".$_GET['to'];
                        } else{
                            $queryString .= "&to=".$_GET['to'];
                        }
                    }
                    if(isset($_GET['court'])){
                        if($queryString === ''){
                            $queryString .= "?court=".$_GET['court'];
                        } else{
                            $queryString .= "&court=".$_GET['court'];
                        }
                    }
                    header("Location: hearing.php$queryString");
                }
            }
            exit();
        }
    } else if(isset($_GET['reconciliation']) && $_GET['reconciliation'] === '1'){
        if($row_permcheck['session_eperm'] == 1){
            $id = $_GET['reconciliationfid'];
            $fidd = $id;
            $sid = $_GET['reconciliationsid'];
            $rr = 2;
            
            $action = "تم التعديل على الجلسة :<br>رقم الملف : $id<br><br> حالة الجلسة : تم الصلح";
            
            include_once 'addlog.php';
            $timer_flag = 'hearing_reconciliation';
            include_once 'timerfunc.php';
            
            $stmt3 = $conn->prepare("UPDATE session SET referral_reconciliation = ? WHERE session_id = ?");
            $stmt3->bind_param("ii", $rr, $sid);
            $stmt3->execute();
            $stmt3->close();
            
            unset($_SESSION['form_data']);
            
            if(isset($_GET['page']) && $_GET['page'] === 'FileEdit.php'){
                header("Location: FileEdit.php?id=$id");
            } else if(isset($_GET['page']) && $_GET['page'] === 'index.php'){
                header("Location: index.php");
            } else{
                $queryString = '';
                if(isset($_GET['tw'])){
                    header("Location: hearing.php?tw=1");
                } else{
                    if(isset($_GET['from'])){
                        if($queryString === ''){
                            $queryString .= "?from=".$_GET['from'];
                        } else{
                            $queryString .= "&from=".$_GET['from'];
                        }
                    }
                    if(isset($_GET['to'])){
                        if($queryString === ''){
                            $queryString .= "?to=".$_GET['to'];
                        } else{
                            $queryString .= "&to=".$_GET['to'];
                        }
                    }
                    if(isset($_GET['court'])){
                        if($queryString === ''){
                            $queryString .= "?court=".$_GET['court'];
                        } else{
                            $queryString .= "&court=".$_GET['court'];
                        }
                    }
                    header("Location: hearing.php$queryString");
                }
            }
            exit();
        }
    } else if(isset($_REQUEST['investigating_start'])){
        if($row_permcheck['cfiles_eperm'] == 1){
            $id = filter_input(INPUT_POST, 'fid2_inv', FILTER_SANITIZE_NUMBER_INT);
            $fidd = $id;
            
            $action = "تم تحويل الملف رقم ($id) الى : قيد التحقيق";
            
            include_once 'addlog.php';
            $timer_flag = 'file_investigationstart';
            include_once 'timerfunc.php';
            
            $investigating_value = 1;
            $stmt3 = $conn->prepare("UPDATE file SET investigating = ? WHERE file_id = ?");
            $stmt3->bind_param("ii", $investigating_value, $id);
            $stmt3->execute();
            $stmt3->close();
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$id");
            exit();
        }
    } else if(isset($_REQUEST['investigating_done'])){
        if($row_permcheck['cfiles_eperm'] == 1){
            $id = filter_input(INPUT_POST, 'fid2_inv', FILTER_SANITIZE_NUMBER_INT);
            $fidd = $id;
            
            $action = "تم تحويل الملف رقم ($id) الى : انتهى التحقيق";
            
            include_once 'addlog.php';
            $timer_flag = 'file_investigationdone';
            include_once 'timerfunc.php';
            
            $investigating_value = 0;
            $stmt3 = $conn->prepare("UPDATE file SET investigating = ? WHERE file_id = ?");
            $stmt3->bind_param("ii", $investigating_value, $id);
            $stmt3->execute();
            $stmt3->close();
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$id");
            exit();
        }
    } else if(isset($_GET['diddel'])){
        $id = $_GET['diddel'];
        $fid = $_GET['fid'];
        
        if($row_permcheck['degree_dperm'] == 1){
            $flag = '0';
            $action = "تم حذف قضية :<br>رقم الملف : $fid<br>";
            
            $stmt = $conn->prepare("SELECT * FROM file_degrees WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultr = $stmt->get_result();
            $row = $resultr->fetch_assoc();
            $stmt->close();
            
            $olddeg = $row['degree'];
            $oldno = $row['case_num'];
            $oldyr = $row['file_year'];
            $oldcc = $row['client_characteristic'];
            $oldoc = $row['opponent_characteristic'];
            
            if(isset($olddeg) && $olddeg !== ''){
                $flag = '1';
                
                $action = $action."<br>درجة التقاضي : $olddeg";
            }
            
            if(isset($oldno) && $oldno !== ''){
                $flag = '1';
                
                $action = $action."<br>رقم القضية : $oldno";
            }
            
            if(isset($oldyr) && $oldyr !== ''){
                $flag = '1';
                
                $action = $action."<br>السنة : $oldyr";
            }
            
            if(isset($oldcc) && $oldcc !== ''){
                $flag = '1';
                
                $action = $action."<br>صفة الموكل : $oldcc";
            }
            
            if(isset($oldoc) && $oldoc !== ''){
                $flag = '1';
                
                $action = $action."<br>صفة الخصم : $oldoc";
            }
            
            if(isset($flag) && $flag === '1'){
                include_once 'addlog.php';
            }
            
            $stmt = $conn->prepare("DELETE FROM file_degrees WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$fid");
            exit();
        }
    } else if(isset($_REQUEST['edit_session'])){
        if($row_permcheck['session_eperm'] == 1){
            $session_fid = filter_input(INPUT_POST, 'session_fid', FILTER_SANITIZE_NUMBER_INT);
            
            $flag = '0';
            $action = "تم التعديل على بيانات الجلسة :<br>رقم الملف : $session_fid<br>";
            
            $session_id = filter_input(INPUT_POST, 'seid', FILTER_SANITIZE_NUMBER_INT);
            
            $stmt = $conn->prepare("SELECT * FROM session WHERE session_id = ?");
            $stmt->bind_param("i", $session_id);
            $stmt->execute();
            $resultr = $stmt->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmt->close();
            
            $oldtrial = $rowr['session_trial'];
            $olddeg = $rowr['session_deg'];
            $oldnum = $rowr['case_num'];
            $oldyear = $rowr['year'];
            $olddegree = $oldyear.'/'.$oldnum.'-'.$olddeg;
            $olddets = $rowr['session_details'];
            $olddate = $rowr['session_date'];
            $oldexs = $rowr['expert_session'];
            $oldexa = $rowr['expert_amount'];
            $oldexad = $rowr['expert_address'];
            $oldexn = $rowr['expert_name'];
            $oldexp = $rowr['expert_phone'];
            
            $session_decission = filter_input(INPUT_POST, "session_decission", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($session_decission) && $session_decission !== $oldtrial){
                $flag = '1';
                
                $action = $action."<br>تم تغيير الحكم : من $oldtrial الى $session_decission";
            }
            
            if(isset($_REQUEST['session_degree'])){
                $session_degree = filter_input(INPUT_POST, "session_degree", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($session_degree) && $session_degree !== $olddegree){
                    $flag = '1';
                    
                    $action = $action."<br>تم تغيير درجة التقاضي : من $olddegree الى $session_degree";
                }
                
                list($ycn, $degree) = explode('-', $session_degree);
                list($year, $case_num) = explode('/', $ycn);
            } else{
                $session_degree = '';
            }
            
            $session_details = filter_input(INPUT_POST, "session_details", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($session_details) && $session_details !== $olddets){
                $flag = '1';
                
                $action = $action."<br>تم تغيير تفاصيل الجلسة : من $olddets الى $session_details";
            }
            
            $session_date = filter_input(INPUT_POST, "Hearing_dt", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($session_dateN) && $session_dateN !== $olddate){
                $flag = '1';
                
                $action = $action."<br>تم تغيير تاريخ الجلسة : من $olddate الى $session_dateN";
            }
            
            $expert_session = filter_input(INPUT_POST, 'expert_session', FILTER_SANITIZE_NUMBER_INT);
            if(isset($expert_session) && $expert_session != $oldexs){
                $flag = '1';
                
                if($expert_session == 0){
                    $action = $action."<br>تم ازالة الجلسة من جلسات الخبرة";
                } else{
                    $action = $action."<br>تم تحويل الجلسة الى جلسة خبرة";
                }
            }
            
            $expert_amount = filter_input(INPUT_POST, 'expert_amount', FILTER_SANITIZE_NUMBER_INT);
            if(isset($expert_amount) && $expert_amount != $oldexa){
                $flag = '1';
                
                $action = $action."<br>تم تغيير مبلغ امانة الخبرة : من $oldexa الى $expert_amount";
            }
            
            $expert_addr = filter_input(INPUT_POST, "expert_addr", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($expert_addr) && $expert_addr !== $oldexad){
                $flag = '1';
                
                $action = $action."<br>تم تغيير عنوان الخبير : من $oldexad الى $expert_addr";
            }
            
            $expert_name = filter_input(INPUT_POST, "expert_name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($expert_name) && $expert_name !== $oldexn){
                $flag = '1';
                
                $action = $action."<br>تم تغيير اسم الخبير : من $oldexn الى $expert_name";
            }
            
            $expert_phone = filter_input(INPUT_POST, "expert_phone", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($expert_phone) && $expert_phone !== $oldexp){
                $flag = '1';
                
                $action = $action."<br>تم تغيير هاتف الخبير : من $oldexp الى $expert_phone";
            }
            
            if(isset($_REQUEST['link']) && $_REQUEST['link'] !== ''){
                $link = filter_input(INPUT_POST, "link", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                
                $flag = '1';
                $action = $action."<br>تم تغيير رابط الجلسة : من $oldlink الى $link";
                $linkaction = "تم تغيير رابط احد جلسات الملف رقم $session_fid : من $oldlink الى $link";
                
                if(isset($expert_session) && $expert_session == 1){
                    $link_for = "جلسات الخبرة";
                } else {
                    $link_for = "جلسات المحكمة";
                }
                
                $responsible = $_SESSION['id'];
                $timestamp = date("Y-m-d");
                
                $stmt_link = $conn->prepare("INSERT INTO fast_links (link, link_for, responsible, timestamp) VALUES (?, ?, ?, ?)");
                $stmt_link->bind_param("ssis", $link, $link_for, $responsible, $timestamp);
                $stmt_link->execute();
                $stmt_link->close();
                
                include_once 'addlog.php';
            }
            
            $timestamp = date('Y-m-d');
            $created_at = date("Y-m-d H:i:s");
            
            $fidd = $session_fid;
            
            $stmt = $conn->prepare("UPDATE session SET session_fid = ?, session_date = ?, session_details = ?, session_degree = ?, 
            year = ?, case_num = ?, session_decission = ?, expert_session = ?, expert_name = ?, expert_phone = ?, 
            expert_amount = ?, expert_address = ?, link = ?, timestamp = ?, created_at = ? WHERE session_id = ?");
            $stmt->bind_param("isssiisississssi", $session_fid, $session_date, $session_details, $degree, $year, $case_num, 
            $session_decission, $expert_session, $expert_name, $expert_phone, $expert_amount, $expert_addr, $link, $timestamp, $created_at, $session_id);
            $stmt->execute();
            $stmt->close();
            
            if(isset($flag) && $flag !== ''){
                include_once 'addlog.php';
                $timer_flag = 'hearing_edit';
                include_once 'timerfunc.php';
            }
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?success=1&id=$session_fid");
            exit();
        }
    } else if($_REQUEST['edit_task']) {
        if($row_permcheck['admjobs_eperm'] == 1){
            $job_fid = filter_input(INPUT_POST, 'job_fid', FILTER_SANITIZE_NUMBER_INT);
            
            $flag = '0';
            $action = "تم التعديل على عمل اداري :<br>رقم الملف : $job_fid<br>";
            
            $tid = filter_input(INPUT_POST, 'tid', FILTER_SANITIZE_NUMBER_INT);
            
            $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
            $stmt->bind_param("i", $tid);
            $stmt->execute();
            $resultr = $stmt->get_result();
            $rowr = $resultr->fetch_assoc();
            
            $oldpr = $rowr['priority'];
            $oldtt = $rowr['task_type'];
            $oldemp = $rowr['employee_id'];
            $oldres = $rowr['responsible'];
            $olddeg = $rowr['degree'];
            $olddet = $rowr['details'];
            $olddue = $rowr['duedate'];
            $stmt->close();
            
            $job_priority = filter_input(INPUT_POST, 'job_priority', FILTER_SANITIZE_NUMBER_INT);
            if(isset($job_priority) && $job_priority != $oldpr){
                $flag = '1';
                
                if($job_priority == 1){
                    $jp = 'عاجل';
                    $ojp = 'عادي';
                } else{
                    $jp = 'عادي';
                    $ojp = 'عاجل';
                }
                $action = $action."<br>تم تغيير اهمية العمل : من $ojp الى $jp";
            }
            
            $job_name = filter_input(INPUT_POST, 'job_name', FILTER_SANITIZE_NUMBER_INT);
            if(isset($job_name) && $job_name != $oldtt){
                $flag = '1';
                
                $stmt_job = $conn->prepare("SELECT * FROM job_name WHERE id = ?");
                $stmt_job->bind_param("i", $oldtt);
                $stmt_job->execute();
                $resultc = $stmt_job->get_result();
                $rowc = $resultc->fetch_assoc();
                $oldjnn = $rowc['job_name'];
                $stmt_job->close();
                
                $stmt_job2 = $conn->prepare("SELECT * FROM job_name WHERE id = ?");
                $stmt_job2->bind_param("i", $job_name);
                $stmt_job2->execute();
                $resultc = $stmt_job2->get_result();
                $rowc = $resultc->fetch_assoc();
                $jn = $rowc['job_name'];
                $stmt_job2->close();
                
                $action = $action."<br>تم تغيير نوع العمل : من $oldjnn الى $jn";
            }
            
            $employee_name = filter_input(INPUT_POST, 'employee_name', FILTER_SANITIZE_NUMBER_INT);
            if(isset($employee_name) && $employee_name != $oldemp){
                $flag = '1';
                
                $stmt_emp = $conn->prepare("SELECT * FROM user WHERE id = ?");
                $stmt_emp->bind_param("i", $oldemp);
                $stmt_emp->execute();
                $resultc = $stmt_emp->get_result();
                $rowc = $resultc->fetch_assoc();
                $empn1 = $rowc['name'];
                $stmt_emp->close();
                
                $stmt_emp2 = $conn->prepare("SELECT * FROM user WHERE id = ?");
                $stmt_emp2->bind_param("i", $employee_name);
                $stmt_emp2->execute();
                $resultc = $stmt_emp2->get_result();
                $rowc = $resultc->fetch_assoc();
                $empn = $rowc['name'];
                $stmt_emp2->close();
                
                $action = $action."<br>تم تغيير الموظف المكلف : من $empn1 الى $empn";
            }
            
            $responsible = filter_input(INPUT_POST, 'responsible', FILTER_SANITIZE_NUMBER_INT);
            if(isset($responsible) && $responsible != $oldres){
                $flag = '1';
                
                $stmt_resp = $conn->prepare("SELECT * FROM user WHERE id = ?");
                $stmt_resp->bind_param("i", $oldres);
                $stmt_resp->execute();
                $resultc = $stmt_resp->get_result();
                $rowc = $resultc->fetch_assoc();
                $empn12 = $rowc['name'];
                $stmt_resp->close();
                
                $stmt_resp2 = $conn->prepare("SELECT * FROM user WHERE id = ?");
                $stmt_resp2->bind_param("i", $responsible);
                $stmt_resp2->execute();
                $resultc = $stmt_resp2->get_result();
                $rowc = $resultc->fetch_assoc();
                $empn2 = $rowc['name'];
                $stmt_resp2->close();
                
                $action = $action."<br>تم تغيير المسؤول عن العمل : من $empn12 الى $empn2";
            }
            
            $job_degree = filter_input(INPUT_POST, 'job_degree', FILTER_SANITIZE_NUMBER_INT);
            if(isset($job_degree) && $job_degree != $olddeg){
                $flag = '1';
                
                $stmt_deg = $conn->prepare("SELECT * FROM file_degrees WHERE id = ?");
                $stmt_deg->bind_param("i", $olddeg);
                $stmt_deg->execute();
                $resultc = $stmt_deg->get_result();
                $rowc = $resultc->fetch_assoc();
                $oldd = $rowc['file_year'].'/'.$rowc['case_num'].'-'.$rowc['degree'];
                $stmt_deg->close();
                
                $stmt_deg2 = $conn->prepare("SELECT * FROM file_degrees WHERE id = ?");
                $stmt_deg2->bind_param("i", $job_degree);
                $stmt_deg2->execute();
                $resultc = $stmt_deg2->get_result();
                $rowc = $resultc->fetch_assoc();
                $fulldeg = $rowc['file_year'].'/'.$rowc['case_num'].'-'.$rowc['degree'];
                $stmt_deg2->close();
                
                $action = $action."<br>تم تغيير درجة التقاضي : من $oldd الى $fulldeg";
            }
            
            $job_details = filter_input(INPUT_POST, "job_details", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($job_details !== $olddet){
                $flag = '1';
                
                $action = $action."<br>تم تغيير تفاصيل العمل : من $olddet الى $job_details";
            }
            
            $job_date = filter_input(INPUT_POST, "job_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($job_date) && $job_date !== $olddue){
                $flag = '1';
                
                $action = $action."<br>تم تغيير تاريخ التنفيذ : من $olddue الى $job_date";
            }
            
            $timestamp = date('Y-m-d');
            
            $fidd = $job_fid;
            
            $stmt = $conn->prepare("UPDATE tasks SET file_no = ?, responsible = ?, task_type = ?, employee_id = ?, priority = ?, 
            degree = ?, details = ?, duedate = ?, timestamp = ? WHERE id = ?");
            $stmt->bind_param("iiiiiisssi", $job_fid, $responsible, $job_name, $employee_name, $job_priority, $job_degree, 
            $job_details, $job_date, $timestamp, $tid);
            $stmt->execute();
            $stmt->close();
            
            if(isset($flag) && $flag === '1'){
                include_once 'addlog.php';
                $timer_flag = 'task_edit';
                include_once 'timerfunc.php';
            }
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?success=1&id=$job_fid");
            exit();
        }
    } else if(isset($_REQUEST['edit_petition'])){
        if($row_permcheck['petition_aperm'] == 1){
            $epetid = filter_input(INPUT_POST, 'epetid', FILTER_SANITIZE_NUMBER_INT);
            
            $stmt = $conn->prepare("SELECT * FROM petition WHERE id = ?");
            $stmt->bind_param("i", $epetid);
            $stmt->execute();
            $resultr = $stmt->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmt->close();
            
            $fid = filter_input(INPUT_POST, 'petition_fid', FILTER_SANITIZE_NUMBER_INT);
            
            $flag = '0';
            $action = "تم تعديل بيانات الامر على عريضة<br>رقم الملف : $fid<br>";
            
            $oldpetition_date = $rowr['petition_date'];
            $petition_date = filter_input(INPUT_POST, "petition_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($petition_date) && $petition_date !== $oldpetition_date){
                $flag = '1';
                
                $action = $action."<br>تم تغيير تاريخ التقديم : من $oldpetition_date الى $petition_date";
            }
            
            $oldpetition_type = $rowr['type'];
            $petition_type = filter_input(INPUT_POST, "petition_type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($petition_type) && $petition_type !== $oldpetition_type){
                $flag = '1';
                
                $action = $action."<br>تم تغيير نوع الامر : من $oldpetition_type الى $petition_type";
            }
            
            $oldpetition_decision = $rowr['decision'];
            $petition_decision = filter_input(INPUT_POST, 'petition_decision', FILTER_SANITIZE_NUMBER_INT);
            
            $today = new DateTime($petition_date);
            $future_date = clone $today;
            
            if($petition_decision == 0){
                $future_date->modify("+7 days");
                $oldappeal_lastdate = $rowr['appeal_lastdate'];
                $appeal_lastdate = $future_date->format("Y-m-d");
                $hearing_lastdate = '';
                $petition_decision = "رفض";
                
                if($oldpetition_decision === "موافقة"){
                    $flag = '1';
                    
                    $action = $action."<br>تم تغيير قرار القاضي : من رفض الى موافقة<br>تم تغيير اخر تاريخ للتظلم : من $oldappeal_lastdate الى $appeal_lastdate";
                }
            } else if($petition_decision == 1){
                $future_date->modify("+8 days");
                $oldhearing_lastdate = $rowr['hearing_lastdate'];
                $hearing_lastdate = $future_date->format("Y-m-d");
                $appeal_lastdate = '';
                $petition_decision = "موافقة";
                
                if($oldpetition_decision === "رفض"){
                    $flag = '1';
                    
                    $action = $action."<br>تم تغيير قرار القاضي : من موافقة الى رفض<br>تم تغيير اخر تاريخ لتسجيل قيد الدعوى : من $oldhearing_lastdate الى $hearing_lastdate";
                }
            }
            
            $timestamp = $_SESSION['id'] . '<br>' . date("Y-m-d");
            
            $stmt = $conn->prepare("UPDATE petition SET fid = ?, date = ?, type = ?, decision = ?, hearing_lastdate = ?, 
            appeal_lastdate = ? WHERE id = ?");
            $stmt->bind_param("isssssi", $fid, $petition_date, $petition_type, $petition_decision, $hearing_lastdate, 
            $appeal_lastdate, $epetid);
            $stmt->execute();
            $stmt->close();
            
            if($flag === '1'){
                include_once 'addlog.php';
                $timer_flag = 'petition_edit';
                include_once 'timerfunc.php';
            }
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$fid");
            exit();
        }
    } else if($_REQUEST['edit_exec']) {
        if($row_permcheck['admjobs_eperm'] == 1){
            $job_fid1 = filter_input(INPUT_POST, 'job_fid1', FILTER_SANITIZE_NUMBER_INT);
            
            $flag = '0';
            $action = "تم التعديل على تنفيذ :<br>رقم الملف : $job_fid1<br>";
            
            $eid = filter_input(INPUT_POST, 'eid', FILTER_SANITIZE_NUMBER_INT);
            
            $stmt = $conn->prepare("SELECT * FROM execution WHERE id = ?");
            $stmt->bind_param("i", $eid);
            $stmt->execute();
            $resultr = $stmt->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmt->close();
            
            $oldpr = $rowr['priority'];
            $oldjn = $rowr['task_type'];
            $olddeg = $rowr['degree'];
            $olddec = $rowr['decision'];
            $olddet = $rowr['details'];
            $olddue = $rowr['duedate'];
            
            $job_priority1 = filter_input(INPUT_POST, 'job_priority1', FILTER_SANITIZE_NUMBER_INT);
            if(isset($job_priority1) && $job_priority1 != $oldpr){
                $flag = '1';
                
                if($job_priority1 == 0){
                    $jp = 'عادي';
                    $ojp = 'عاجل';
                } else{
                    $jp = 'عاجل';
                    $ojp = 'عادي';
                }
                $action = $action."<br>تم تغيير اهمية التنفيذ : من $ojp الى $jp";
            }
            
            $job_name1 = filter_input(INPUT_POST, 'job_name1', FILTER_SANITIZE_NUMBER_INT);
            if(isset($job_name1) && $job_name1 != $oldjn){
                $flag = '1';
                
                $stmt_job = $conn->prepare("SELECT * FROM job_name WHERE id = ?");
                $stmt_job->bind_param("i", $oldjn);
                $stmt_job->execute();
                $resultc = $stmt_job->get_result();
                $rowc = $resultc->fetch_assoc();
                $oldjob = $rowc['job_name'];
                $stmt_job->close();
                
                $stmt_job2 = $conn->prepare("SELECT * FROM job_name WHERE id = ?");
                $stmt_job2->bind_param("i", $job_name1);
                $stmt_job2->execute();
                $resultc = $stmt_job2->get_result();
                $rowc = $resultc->fetch_assoc();
                $job = $rowc['job_name'];
                $stmt_job2->close();
                
                $action = $action."<br>تم تغيير العمل الاداري : من $oldjob الى $job";
            }
            
            $degree = filter_input(INPUT_POST, 'resapp', FILTER_SANITIZE_NUMBER_INT);
            if(isset($degree) && $degree != $olddeg){
                $flag = '1';
                
                if($degree == 1){
                    $dg = 'استئناف';
                    $odg = 'تظلم';
                } else{
                    $dg = 'تظلم';
                    $odg = 'استئناف';
                }
                $action = $action."<br>تم تغيير الدرجة : من $odg الى $dg";
            }
            
            $decision = filter_input(INPUT_POST, "decision", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($decision) && $decision !== $olddec){
                $flag = '1';
                
                $action = $action."<br>تم تغيير قرار القاضي : من $olddec الى $decision";
            }
            
            $job_details1 = filter_input(INPUT_POST, "job_details1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($job_details1) && $job_details1 !== $olddet){
                $flag = '1';
                
                $action = $action."<br>تم تغيير التفاصيل : من $olddet الى $job_details1";
            }
            
            $job_date1 = filter_input(INPUT_POST, "job_date1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($job_date1) && $job_date1 !== $olddue){
                $flag = '1';
                
                $action = $action."<br>تم تغيير تاريخ التنفيذ : من $olddue الى $job_date1";
            }
            
            $timestamp1 = date('Y-m-d');
            
            $fidd = $job_fid1;
    
            $stmt = $conn->prepare("UPDATE execution SET file_no = ?, decision = ?, task_type = ?, degree = ?, priority = ?, 
            details = ?, duedate = ?, timestamp = ? WHERE id = ?");
            $stmt->bind_param("isiiisssi", $job_fid1, $decision, $job_name1, $degree, $job_priority1, $job_details1, 
            $job_date1, $timestamp1, $eid);
            $stmt->execute();
            $stmt->close();
            
            if(isset($flag) && $flag === '1'){
                include_once 'addlog.php';
                $timer_flag = 'execution_edit';
                include_once 'timerfunc.php';
            }
    
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?success=1&id=$job_fid1");
            exit();
        }
    } else if(isset($_REQUEST['finishf'])){
        if($row_permcheck['cfiles_eperm'] == 1){
            $fid2 = filter_input(INPUT_POST, 'fidww', FILTER_SANITIZE_NUMBER_INT);
            
            $finish = date("Y-m-d H:i:s");
            
            $stmt = $conn->prepare("SELECT * FROM file WHERE file_id = ?");
            $stmt->bind_param("i", $fid2);
            $stmt->execute();
            $result_read = $stmt->get_result();
            if($result_read->num_rows > 0){
                $row_read = $result_read->fetch_assoc();
                $stmt->close();
                $start = $row_read['file_timestamp'];
                
                $start_time = strtotime($start);
                $finish_time = strtotime($finish);
            
                $durationInSeconds = $finish_time - $start_time;
            
                $hours = floor($durationInSeconds / 3600);
                $minutes = floor(($durationInSeconds % 3600) / 60);
            
                $duration = sprintf("%02d:%02d", $hours, $minutes);
            
                $stmt_finish = $conn->prepare("UPDATE file SET finish_time = ?, duration = ? WHERE file_id = ?");
                $stmt_finish->bind_param("ssi", $finish, $duration, $fid2);
                $stmt_finish->execute();
                $stmt_finish->close();
                
                $action = "تم تغيير حالة الملف :<br>رقم الملف : $fid2<br><br>حالة الملف : منتهي<br>الوقت المستغرق بالملف : $duration";
                include_once 'addlog.php';
                $timer_flag = 'filestat_edit';
                include_once 'timerfunc.php';
            }
        }
        
        $fidd = $fid2;
    } else if(isset($_REQUEST['edit_document'])){
        if($row_permcheck['note_eperm'] == 1){
            $cdoid = filter_input(INPUT_POST, 'cdoid', FILTER_SANITIZE_NUMBER_INT);
            
            $stmt = $conn->prepare("SELECT * FROM case_document WHERE did = ?");
            $stmt->bind_param("i", $cdoid);
            $stmt->execute();
            $resultr = $stmt->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmt->close();
            
            $fid = $rowr['dfile_no'];
            $olddate = $rowr['document_date'];
            $oldsubject = $rowr['document_subject'];
            $oldnotes = $rowr['document_notes'];
            
            $action = "تم تعديل بيانات احد مذكرات الملف رقم $fid<br>عنوان المذكرة : $oldsubject";
            $flag = '0';
            
            $document_date = filter_input(INPUT_POST, "document_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($document_date) && $document_date !== $olddate){
                $action = $action."<br>تم تعديل تاريخ الجلسة : من $olddate الى $document_date";
                
                $flag = '1';
            }
            
            $document_subject = filter_input(INPUT_POST, "document_subject", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($document_subject) && $document_subject !== $oldsubject){
                $action = $action."<br>تم تعديل تاريخ الجلسة : من $olddate الى $document_subject";
                
                $flag = '1';
            }
            
            $document_notes = filter_input(INPUT_POST, "document_notes", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($document_notes) && $document_notes !== $oldnotes){
                $action = $action."<br>تم تغيير ملاحظات المذكرة : من $oldnotes الى $document_notes";
                
                $flag = '1';
            }
            
            $targetDir = "files_images/document_attachments/$fid/$cdoid";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $document_file1 = $rowr['document_attachment'];
            if (isset($_FILES['document_file1']) && $_FILES['document_file1']['error'] == 0) {
                $document_file1 = $targetDir . "/" . basename($_FILES['document_file1']['name']);
                if (move_uploaded_file($_FILES['document_file1']['tmp_name'], $document_file1)) {
                    echo "document_file1 1 has been uploaded.<br>";
                    $flag = '1';
                    
                    $action = $action2."<br>تم تغيير المرفق 1";
                } else {
                    echo "Sorry, there was an error uploading attachment 1.<br>";
                }
            }
            
            $document_file2 = $rowr['document_attachment2'];
            if (isset($_FILES['document_file2']) && $_FILES['document_file2']['error'] == 0) {
                $document_file2 = $targetDir . "/" . basename($_FILES['document_file2']['name']);
                if (move_uploaded_file($_FILES['document_file2']['tmp_name'], $document_file2)) {
                    echo "document_file2 1 has been uploaded.<br>";
                    $flag = '1';
                    
                    $action = $action2."<br>تم تغيير المرفق 2";
                } else {
                    echo "Sorry, there was an error uploading attachment 2.<br>";
                }
            }
            
            $stmt_update = $conn->prepare("UPDATE case_document SET document_date = ?, document_notes = ?, document_subject = ?, document_attachment = ?, document_attachment2 = ? WHERE did = ?");
            $stmt_update->bind_param("sssssi", $document_date, $document_notes, $document_subject, $document_file1, $document_file2, $cdoid);
            $stmt_update->execute();
            $stmt_update->close();
            
            if($flag === '1'){
                include_once 'addlog.php';
                $timer_flag = 'document_edit';
                include_once 'timerfunc.php';
            }
            
            $fidd = $fid;
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$fid");
            exit();
        }
    } else if(isset($_REQUEST['endt'])){
        
        $idddd = filter_input(INPUT_POST, 'idddd', FILTER_SANITIZE_NUMBER_INT);
    
        $stmtr = $conn->prepare("SELECT * FROM tasks WHERE id = ?"); 
        $stmtr->bind_param("i", $idddd); 
        $stmtr->execute(); 
        $resultr = $stmtr->get_result(); 
        $row = $resultr->fetch_assoc();
        $stmtr->close();

        if($_SESSION['id'] == $row['employee_id']){
            $fid = $row['file_no'];
            $task_status = $row['task_status'];
            
            $flag = '0';
            
            if($task_status != 2){
                $flag = '1';
                
                if($task_status == 0){
                    $ts = 'لم يتخذ به اجراء';
                } else if($task_status == 1){
                    $ts = 'جاري العمل عليه';
                } else if($task_status == 2){
                    $ts = 'منتهي';
                }
                
                $action = "تم تغيير حالة العمل الاداري : من $ts الى منتهي<br>رقم الملف : $fid";
            }
            
            $t_note = filter_input(INPUT_POST, "t_note", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $timestamp = date("Y-m-d");
            
            if(isset($t_note) && $t_note !== ''){
                $stmt1 = $conn->prepare("INSERT INTO task_notes (taskid, note, timestamp) VALUES (?, ?, ?)"); 
                $stmt1->bind_param("iss", $idddd, $t_note, $timestamp); 
                $stmt1->execute(); 
                $stmt1->close();
                
                $flag = '1';
                
                $action = $action."<br>تم اضافة ملاحظة على المهمة : $t_note";
            }
            
            if($flag === '1'){
                include_once 'addlog.php';
            }
            
            $stmt1 = $conn->prepare("UPDATE tasks SET task_status='2' WHERE id=?"); 
            $stmt1->bind_param("i", $idddd); 
            $stmt1->execute();
            $stmt1->close();
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$fid");
            exit();
        }
    } else if(isset($_REQUEST['inpt'])){
        $idddd = filter_input(INPUT_POST, 'idddd', FILTER_SANITIZE_NUMBER_INT);
        
        $stmtr = $conn->prepare("SELECT * FROM tasks WHERE id = ?"); 
        $stmtr->bind_param("i", $idddd); 
        $stmtr->execute(); 
        $resultr = $stmtr->get_result(); 
        $row = $resultr->fetch_assoc();
        $stmtr->close();
        
        if($_SESSION['id'] == $row['employee_id']){
            $fid = $row['file_no'];
            $task_status = $row['task_status'];
            
            $flag = '0';
            
            if($task_status != 1){
                $flag = '1';
                
                if($task_status == 0){
                    $ts = 'لم يتخذ به اجراء';
                } else if($task_status == 1){
                    $ts = 'جاري العمل عليه';
                } else if($task_status == 2){
                    $ts = 'منتهي';
                }
                
                $action = "تم تغيير حالة العمل الاداري : من $ts الى جاري العمل عليه<br>رقم الملف : $fid";
            }
            
            $t_note = filter_input(INPUT_POST, "t_note", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $timestamp = date("Y-m-d");
            
            if(isset($t_note) && $t_note !== ''){
                $stmt1 = $conn->prepare("INSERT INTO task_notes (taskid, note, timestamp) VALUES (?, ?, ?)"); 
                $stmt1->bind_param("iss", $idddd, $t_note, $timestamp); 
                $stmt1->execute(); 
                $stmt1->close();
                
                $flag = '1';
                
                $action = $action."<br>تم اضافة ملاحظة على المهمة : $t_note";
            }
            
            if($flag === '1'){
                include_once 'addlog.php';
            }
            
            $stmt1 = $conn->prepare("UPDATE tasks SET task_status='1' WHERE id=?"); 
            $stmt1->bind_param("i", $idddd); 
            $stmt1->execute();
            $stmt1->close();

            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$fid");
            exit();
        }
    } else if(isset($_REQUEST['submit_re_name'])){
        $re_name = filter_input(INPUT_POST, 're_name', FILTER_SANITIZE_NUMBER_INT);
        
        $idddd = filter_input(INPUT_POST, "idddd", FILTER_VALIDATE_INT);
        
        $stmtr = $conn->prepare("SELECT * FROM tasks WHERE id = ?"); 
        $stmtr->bind_param("i", $idddd); 
        $stmtr->execute(); 
        $resultr = $stmtr->get_result(); 
        $row = $resultr->fetch_assoc();
        $stmtr->close();
        
        if($_SESSION['id'] == $row['employee_id']){
            $t_note = filter_input(INPUT_POST, "t_note", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $timestamp = date("Y-m-d");
            
            $stmt1 = $conn->prepare("INSERT INTO task_notes (taskid, note, timestamp) VALUES (?, ?, ?)"); 
            $stmt1->bind_param("iss", $idddd, $t_note, $timestamp); 
            $stmt1->execute(); 
            $stmt1->close();
            
            $stmt = $conn->prepare("UPDATE tasks SET employee_id=? WHERE id=?"); 
            $stmt->bind_param("ii", $re_name, $idddd); 
            $stmt->execute(); 
            $stmt->close();
            
            $action = '';
            $flag = '0';
            
            $empid = $row['employee_id'];
            $stmtu = $conn->prepare("SELECT * FROM user WHERE id=?");
            $stmtu->bind_param("i", $empid);
            $stmtu->execute();
            $resultu = $stmtu->get_result();
            $rowu = $resultu->fetch_assoc();
            $stmtu->close();
            $emp = $rowu['name'];
            if(isset($emp) && $emp !== ''){
                $flag = '1';
                
                $action = "تم تحويل عمل اداري : من $emp الى $re_name<br>";
            }
            
            $fid = $row['file_no'];
            if(isset($fid) && $fid !== ''){
                $action = $action."<br>رقم الملف : $fid";
            }
            
            $jid = $row['task_type'];
            $stmtj = $conn->prepare("SELECT * FROM job_name WHERE id=?");
            $stmtj->bind_param("i", $jid);
            $stmtj->execute();
            $resultj = $stmtj->get_result();
            $rowj = $resultj->fetch_assoc();
            $stmtj->close();
            $job = $rowj['job_name'];
            
            $priority = $row['priority'];
            if($priority == 0){
                $pr = 'عادي';
            } else if($priority == 1){
                $pr = 'عاجل';
            }
            if(isset($pr) && $pr !== ''){
                $action = $action."<br>اهمية العمل : $pr";
            }
            
            $degid = $row['degree'];
            $stmtd = $conn->prepare("SELECT * FROM file_degrees WHERE id=?");
            $stmtd->bind_param("i", $degid);
            $stmtd->execute();
            $resultd = $stmtd->get_result();
            $rowd = $resultd->fetch_assoc();
            $stmtd->close();
            $deg = $rowd['case_num'].$rowd['year'].$rowd['degree'];
            if(isset($deg) && $deg !== ''){
                $action = $action."<br>درجة التقاضي : $deg";
            }
            
            $date = $row['duedate'];
            if(isset($date) && $date !== ''){
                $action = $action."<br>تاريخ التنفيذ : $date";
            }
            
            $note = $row['details'];
            if(isset($note) && $note !== ''){
                $action = $action."<br>التفاصيل : $date";
            }
            
            if($flag === '1'){
                include_once 'addlog.php';
            }
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$fid");
            exit();
        }
    } else if(isset($_GET['tsknoteid'])){
        $tsknoteid = $_GET['tsknoteid'];
        $fidnotetsk = $_GET['fidnotetsk'];
        
        $fidd = $fidnotetsk;
        
        $stmtr = $conn->prepare("SELECT * FROM task_notes WHERE id=?");
        $stmtr->bind_param("i", $tsknoteid);
        $stmtr->execute();
        $resultr = $stmtr->get_result();
        $rowr = $resultr->fetch_assoc();
        $stmtr->close();
        
        if($_SESSION['id'] == $rowr['employee_id']){
            $action = "تم حذف الملاحظة من احد مهام الملف رقم : $fidnotetsk";
            
            include_once 'addlog.php';
            
            $stmt = $conn->prepare("DELETE FROM task_notes WHERE id=?");
            $stmt->bind_param("i", $tsknoteid);
            $stmt->execute();
            $stmt->close();
            
            header("Location: FileEdit.php?id=$fidnotetsk");
            exit();
        }
    } else if (isset($_REQUEST['edit_judwar'])){
        if(isset($_REQUEST['start_date']) && $_REQUEST['start_date'] !== '' && isset($_REQUEST['warning_duration']) && $_REQUEST['warning_duration'] !== ''){
            if($row_permcheck['judicialwarn_eperm'] == 1){
                $ejudid = filter_input(INPUT_POST, 'ejudid', FILTER_SANITIZE_NUMBER_INT);
                
                $fid = filter_input(INPUT_POST, 'fid', FILTER_SANITIZE_NUMBER_INT);
                
                $flag = '0';
                $action = "تم تعديل الانذار العدلي للملف رقم : $fid<br>";
                
                $stmtr = $conn->prepare("SELECT * FROM judicial_warnings WHERE id = ?"); 
                $stmtr->bind_param("i", $ejudid); 
                $stmtr->execute(); 
                $resultr = $stmtr->get_result(); 
                $rowr = $resultr->fetch_assoc();
                $stmtr->close();
                
                $ratification_date = filter_input(INPUT_POST, "ratification_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($ratification_date) && $ratification_date !== $rowr['ratification_date']){
                    $flag = '1';
                    $oldrd = $rowr['ratification_date'];
                    
                    $action = $action."<br>تم تعديل تاريخ التصديق : من $oldrd الى $ratification_date";
                }
                
                $start_date = filter_input(INPUT_POST, "start_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if(isset($start_date) && $start_date !== $rowr['start_date']){
                    $flag = '1';
                    $oldstart = $rowr['start_date'];
                    
                    $action = $action."<br>تم تعديل تاريخ التسليم : من $oldstart الى $start_date";
                }
                
                $warning_duration = filter_input(INPUT_POST, 'warning_duration', FILTER_SANITIZE_NUMBER_INT);
                if(isset($warning_duration) && $warning_duration != $rowr['warning_duration']){
                    $flag = '1';
                    $olddur = $rowr['warning_duration'];
                    
                    $action = $action."<br>تم تعديل مدة الانذار : من $olddur الى $warning_duration";
                }
                
                $today = new DateTime($start_date);
                
                $future_date = clone $today;
                $future_date->modify("+$warning_duration days");
                $future = $future_date->format("Y-m-d");
                
                if($flag === '1'){
                    include_once 'addlog.php';
                    $timer_flag = 'judicialwarn_edit';
                    include_once 'timerfunc.php';
                }
                
                $stmt_update = $conn->prepare("UPDATE judicial_warnings SET fid = ?, ratification_date = ?, given_date = ?, warning_duration = ?, duedate = ? WHERE id = ?");
                $stmt_update->bind_param("issisi", $fid, $ratification_date, $start_date, $warning_duration, $future, $ejudid);
                $stmt_update->execute();
                $stmt_update->close();
                
                unset($_SESSION['form_data']);
                header("Location: FileEdit.php?id=$fid");
                exit();
            }
        }
    } else if(isset($_GET['delejudid']) && $_GET['delejudid'] !== ''){
        if($row_permcheck['judicialwarn_dperm'] == 1){
            $delejudid = $_GET['delejudid'];
            $fid = $_GET['judfid'];
            
            $stmt_del = $conn->prepare("DELETE FROM judicial_warnings WHERE id = ?");
            $stmt_del->bind_param("i", $delejudid);
            $stmt_del->execute();
            $stmt_del->close();
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$fid");
            exit();
        }
    } else if(isset($_GET['delpetid']) && $_GET['delpetid'] !== ''){
        if($row_permcheck['petition_dperm'] == 1){
            $delpetid = $_GET['delpetid'];
            $petfid = $_GET['petfid'];
            
            $stmt_del = $conn->prepare("DELETE FROM petition WHERE id = ?");
            $stmt_del->bind_param("i", $delpetid);
            $stmt_del->execute();
            $stmt_del->close();
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$petfid");
            exit();
        }
    } else if(isset($_REQUEST['submit_request']) && isset($_REQUEST['request']) && $_REQUEST['request'] !== ''){
        if($row_permcheck['session_eperm'] == 1){
            $fid = filter_input(INPUT_POST, 'fid', FILTER_SANITIZE_NUMBER_INT);
            $fidd = $fid;
            
            $flag = '0';
            $action = "تم اضافة طلب جديد على احد جلسات الملف رقم : $fid<br>";
            
            $sid = filter_input(INPUT_POST, 'sid', FILTER_SANITIZE_NUMBER_INT);
            
            $request = filter_input(INPUT_POST, "request", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($request) && $request !== ''){
                $flag = '1';
                
                $action = $action."<br>الطلب : $request";
            }
            
            $stmt_req = $conn->prepare("INSERT INTO session_requests (fid, sid, request) VALUES (?, ?, ?)");
            $stmt_req->bind_param("iis", $fid, $sid, $request);
            $stmt_req->execute();
            $stmt_req->close();
            
            if($flag === '1'){
                include_once 'addlog.php';
                $timer_flag = 'hearingreq_add';
                include_once 'timerfunc.php';
            }
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$fid&hid=$sid&requests=1");
            exit();
        }
    } else if(isset($_REQUEST['edit_request']) && isset($_REQUEST['request']) && $_REQUEST['request'] !== ''){
        if($row_permcheck['session_eperm'] == 1){
            $fid = filter_input(INPUT_POST, 'fid', FILTER_SANITIZE_NUMBER_INT);
            $fidd = $fid;
            
            $rid = filter_input(INPUT_POST, 'rid', FILTER_SANITIZE_NUMBER_INT);
            
            $flag = '0';
            $action = "تم تعديل طلب الجلسة لاحد جلسات الملف رقم : $fid<br>";
            
            $sid = filter_input(INPUT_POST, 'sid', FILTER_SANITIZE_NUMBER_INT);
            
            $request = filter_input(INPUT_POST, "request", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($request) && $request !== $oldreq){
                $stmt_req = $conn->prepare("SELECT * FROM session_requests WHERE id = ?");
                $stmt_req->bind_param("i", $rid);
                $stmt_req->execute();
                $resultr = $stmt_req->get_result();
                $rowr = $resultr->fetch_assoc();
                $oldreq = $rowr['request'];
                $stmt_req->close();
                
                $flag = '1';
                $action = $action."<br>تم تغيير الطلب : من $oldreq الى $request";
            }
            
            $stmt_update = $conn->prepare("UPDATE session_requests SET fid = ?, sid = ?, request = ? WHERE id = ?");
            $stmt_update->bind_param("iisi", $fid, $sid, $request, $rid);
            $stmt_update->execute();
            $stmt_update->close();
            
            if($flag === '1'){
                include_once 'addlog.php';
                $timer_flag = 'hearingreq_edit';
                include_once 'timerfunc.php';
            }
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$fid&hid=$sid&requests=1");
            exit();
        }
    } else if(isset($_GET['reqdlid']) && $_GET['reqdlid'] !== ''){
        if($row_permcheck['session_eperm'] == 1){
            $reqdlid = $_GET['reqdlid'];
            $reqfidid = $_GET['reqfidid'];
            $reqhid = $_GET['reqhid'];
            
            $stmt = $conn->prepare("SELECT * FROM session_requests WHERE id = ?");
            $stmt->bind_param("i", $reqdlid);
            $stmt->execute();
            $resultr = $stmt->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmt->close();
            
            $deletedreq = $rowr['request'];
            
            $action = "تم حذف طلب من احد جلسات الملف رقم : $reqfidid";
            include_once 'addlog.php';
            
            $stmt_del = $conn->prepare("DELETE FROM session_requests WHERE id = ?");
            $stmt_del->bind_param("i", $reqdlid);
            $stmt_del->execute();
            $stmt_del->close();
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$reqfidid&hid=$reqhid&requests=1");
            exit();
        }
    } else if(isset($_REQUEST['submit_sentstatus'])){
        if($row_permcheck['session_eperm'] == 1){
            $sid = filter_input(INPUT_POST, 'session_sid_2', FILTER_SANITIZE_NUMBER_INT);
            
            $fid = filter_input(INPUT_POST, 'fid', FILTER_SANITIZE_NUMBER_INT);
            
            $action = "تم تغيير حالة ارفاق احد جلسات الملف رقم $fid";
            
            $stmtr = $conn->prepare("SELECT * FROM session WHERE session_id = ?"); 
            $stmtr->bind_param("i", $sid); 
            $stmtr->execute(); 
            $resultr = $stmtr->get_result(); 
            $rowr = $resultr->fetch_assoc();
            $stmtr->close();
            
            if(isset($rowr['session_note']) && $rowr['session_note'] !== ''){
                $changed = 1;
                $oldsession_note = $rowr['session_note'];
            }
            
            $session_note = filter_input(INPUT_POST, "session_note", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            if($changed == 1){
                $action = $action." من $oldsession_note الى $session_note";
            } else{
                $action = $action." الى $session_note";
            }
            
            $stmt_update = $conn->prepare("UPDATE session SET session_note = ? WHERE session_id = ?");
            $stmt_update->bind_param("si", $session_note, $sid);
            $stmt_update->execute();
            $stmt_update->close();
            
            include_once 'addlog.php';
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$fid");
            exit();
        }
    }
    if(isset($_REQUEST['addtask-cut']) && isset($_REQUEST['employee_name3']) && $_REQUEST['employee_name3'] !== '' && isset($_REQUEST['job_name3']) && $_REQUEST['job_name3'] !== '') {
        if($row_permcheck['admjobs_aperm'] == 1){
            $job_fid = filter_input(INPUT_POST, 'job_fid3', FILTER_SANITIZE_NUMBER_INT);
            
            $flag5 = '0';
            $action5 = "تم اضافة مهمة ادارية جديدة :<br>رقم الملف : $job_fid<br>";
            
            $job_priority = filter_input(INPUT_POST, 'job_priority3', FILTER_SANITIZE_NUMBER_INT);
            if(isset($job_priority) && $job_priority !== ''){
                $flag5 = '1';
                
                if($job_priority == 1){
                    $jb = 'عاجل';
                } else{
                    $jb = 'عادي';
                }
                $action5 = $action5."<br>اهمية المهمة : $jb";
            }
            
            $job_name = filter_input(INPUT_POST, 'job_name3', FILTER_SANITIZE_NUMBER_INT);
            if(isset($job_name) && $job_name !== ''){
                $flag5 = '1';
                
                $stmt_job = $conn->prepare("SELECT * FROM job_name WHERE id = ?");
                $stmt_job->bind_param("i", $job_name);
                $stmt_job->execute();
                $resultc = $stmt_job->get_result();
                $rowc = $resultc->fetch_assoc();
                $jn = $rowc['job_name'];
                $stmt_job->close();
                
                $action5 = $action5."<br>نوع المهمة : $jn";
            }
            
            $employee_name = filter_input(INPUT_POST, 'employee_name3', FILTER_SANITIZE_NUMBER_INT);
            if(isset($employee_name) && $employee_name !== ''){
                $flag5 = '1';
                
                $stmt_emp = $conn->prepare("SELECT * FROM user WHERE id = ?");
                $stmt_emp->bind_param("i", $employee_name);
                $stmt_emp->execute();
                $resultc = $stmt_emp->get_result();
                $rowc = $resultc->fetch_assoc();
                $empn = $rowc['name'];
                $stmt_emp->close();
                
                $action5 = $action5."<br>الموظف المكلف : $empn";
            }
            
            $responsible = filter_input(INPUT_POST, 'responsible3', FILTER_SANITIZE_NUMBER_INT);
            if(isset($responsible) && $responsible !== ''){
                $flag5 = '1';
                
                $stmt_resp = $conn->prepare("SELECT * FROM user WHERE id = ?");
                $stmt_resp->bind_param("i", $responsible);
                $stmt_resp->execute();
                $resultc = $stmt_resp->get_result();
                $rowc = $resultc->fetch_assoc();
                $empn = $rowc['name'];
                $stmt_resp->close();
                
                $action5 = $action5."<br>المسؤول عن المهمة : $empn";
            }
            
            $job_degree = filter_input(INPUT_POST, 'job_degree3', FILTER_SANITIZE_NUMBER_INT);
            if(!isset($job_degree)){
                $job_degree = 0;
            }
            if(isset($job_degree) && $job_degree != 0){
                $flag5 = '1';
                
                $stmt_deg = $conn->prepare("SELECT * FROM file_degrees WHERE id = ?");
                $stmt_deg->bind_param("i", $job_degree);
                $stmt_deg->execute();
                $resultc = $stmt_deg->get_result();
                $rowc = $resultc->fetch_assoc();
                $deg = $rowc['file_year'].'/'.$rowc['case_num'].'-'.$rowc['degree'];
                $stmt_deg->close();
                
                $action5 = $action5."<br>درجة التقاضي : $deg";
            }
            
            $job_details = filter_input(INPUT_POST, "job_details3", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($job_details) && $job_details !== ''){
                $flag5 = '1';
                
                $action5 = $action5."<br>تفاصيل المهمة : $job_details";
            }
            
            $job_date = filter_input(INPUT_POST, "job_date3", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($job_date) && $job_date !== ''){
                $flag5 = '1';
                
                $action5 = $action5."<br>تاريخ التنفيذ : $job_date";
            }
            
            $timestamp = date('Y-m-d');
            
            $fidd = $job_fid;
            
            $stmt_task = $conn->prepare("INSERT INTO tasks (file_no, responsible, task_type, employee_id, priority, degree, details, duedate, timestamp) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_task->bind_param("iiiiiisss", $job_fid, $responsible, $job_name, $employee_name, $job_priority, $job_degree, $job_details, $job_date, $timestamp);
            $stmt_task->execute();
            $stmt_task->close();
            if(isset($flag5) && $flag5 === '1'){
                include_once 'addlog.php';
                $timer_flag = 'task_add';
                include_once 'timerfunc.php';
                
                $respid = $_SESSION['id'];
                $empid = $employee_name;
                $target = "tasks /-/ $job_fid";
                $target_id = 0;
                $notification = "تم تكليفك بمهمة جديدة بالملف رقم $job_fid";
                $notification_date = date("Y-m-d");
                $status = 0;
                $timestamp = date("Y-m-d H:i:s");
                
                if($empid != 0 && $empid !== ''){
                    $stmt = $conn->prepare("INSERT INTO notifications (resp_id, empid, target, target_id, notification, notification_date, status, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("iisissis", $respid, $empid, $target, $target_id, $notification, $notification_date, $status, $timestamp);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            
            unset($_SESSION['form_data']);
            header("Location: FileEdit.php?id=$job_fid");
            exit();
        }
    }
    if(isset($_REQUEST['add_worktime']) && 
    (isset($_REQUEST['done_action']) && $_REQUEST['done_action'] !== '') && 
    ((isset($_REQUEST['working_durationHH']) && $_REQUEST['working_durationHH'] !== '' && $_REQUEST['working_durationHH'] !== '0') || (isset($_REQUEST['working_durationMM']) && $_REQUEST['working_durationMM'] !== '' && $_REQUEST['working_durationMM'] !== '0'))){
        if($row_permcheck['workingtime_aperm'] == 1){
            $fid = filter_input(INPUT_POST, 'fid', FILTER_SANITIZE_NUMBER_INT);
            
            $subid = filter_input(INPUT_POST, 'subid', FILTER_SANITIZE_NUMBER_INT);
            
            $waction = filter_input(INPUT_POST, "action", FILTER_SANITIZE_FULL_SPECIAL_CHARS);;
            
            $subinfo = filter_input(INPUT_POST, "subinfo", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($waction === "file_attachment"){
                $section = 'المرفقات';
                $information = " على المرفق : $subinfo";
            }
            $flag = '0';
            $action = "تم اضافة مدة عمل جديدة على الملف رقم $fid في قسم $section :<br>$information<br>";
            
            $employee_id = filter_input(INPUT_POST, 'selected_employee', FILTER_SANITIZE_NUMBER_INT);
            if(isset($employee_id) && $employee_id !== ''){
                $flag = '1';
                
                $stmt_user = $conn->prepare("SELECT * FROM user WHERE id = ?");
                $stmt_user->bind_param("i", $employee_id);
                $stmt_user->execute();
                $resultr = $stmt_user->get_result();
                $rowr = $resultr->fetch_assoc();
                $employee_name = $rowr['name'];
                $stmt_user->close();
                
                $action = $action."<br>الموظف المختص : $employee_name";
            }
            
            $done_date = filter_input(INPUT_POST, "done_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($done_date) && $done_date !== ''){
                $flag = '1';
                
                $action = $action."<br>التاريخ : $done_date";
            }
            
            $done_action = filter_input(INPUT_POST, "done_action", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($done_action) && $done_action !== ''){
                $flag = '1';
                
                $action = $action."<br>الاجراء : $done_action";
            }
            
            $working_durationHH = filter_input(INPUT_POST, 'working_durationHH', FILTER_SANITIZE_NUMBER_INT);
            if($working_durationHH === ''){
                $working_durationHH = '00';
            }
            if(intVal($working_durationHH) < 10){
                $working_durationHH = "0".intVal($working_durationHH);
            }
            
            $working_durationMM = filter_input(INPUT_POST, 'working_durationMM', FILTER_SANITIZE_NUMBER_INT);
            if($working_durationMM === ''){
                $working_durationMM = '00';
            }
            if(intVal($working_durationMM) < 10){
                $working_durationMM = "0".intVal($working_durationMM);
            }
            
            $working_duration = $working_durationHH.":".$working_durationMM;
            
            $action_notes = filter_input(INPUT_POST, "action_notes", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($action_notes) && $action_notes !== ''){
                $flag = '1';
                
                $action = $action."<br>الملاحظات : $action_notes";
            }
            
            $stmt_work = $conn->prepare("INSERT INTO working_time (fid, subid, action, subinfo, done_date, done_action, duration, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_work->bind_param("iissssss", $fid, $subid, $waction, $subinfo, $done_date, $done_action, $working_duration, $action_notes);
            $stmt_work->execute();
            $stmt_work->close();
            
            if($flag === '1'){
                include_once 'addlog.php';
            }
            
            unset($_SESSION['form_data']);
        }
    }
    if(isset($_REQUEST['doc_status']) && $_REQUEST['doc_status'] === 'اعتماد'){
        $did = filter_input(INPUT_POST, 'diddocstatus', FILTER_SANITIZE_NUMBER_INT);
        $fid = filter_input(INPUT_POST, 'fiddocstatus', FILTER_SANITIZE_NUMBER_INT);
        $approve_check = filter_input(INPUT_POST, 'approve_check', FILTER_SANITIZE_NUMBER_INT);
        if($row_permcheck['note_eperm'] == 1){
            $document_notes = filter_input(INPUT_POST, 'document_notes', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        } else{
            $document_notes = '';
        }
            
        $stmtr = $conn->prepare("SELECT * FROM case_document WHERE did=?");
        $stmtr->bind_param("i", $did);
        $stmtr->execute();
        $resultr = $stmtr->get_result();
        $rowr = $resultr->fetch_assoc();
        $stmtr->close();
        
        $fidd = $fid;
        if($approve_check == 0 || $approve_check === ''){
            if($row_permcheck['doc_faperm'] == 1){
                $status1 = 1;
                
                if(isset($rowr['status2'])){
                    $status2 = $rowr['status2'];   
                } else{
                    $status2 = 0;
                }
            }
        } else if($approve_check == 1){
            if($row_permcheck['doc_laperm'] == 1){
                $status2 = 1;
                
                if(isset($rowr['status1'])){
                    $status1 = $rowr['status1'];
                } else{
                    $status1 = 0;
                }
            }
        } else{
            header("Location: FileEdit.php?id=$fid&error=0");
            exit();
        }
        
        if($status1 == 1 || $status2 == 1){
            $stmt = $conn->prepare("UPDATE case_document SET status1=?, status2=?, document_notes=? WHERE did=?");
            $stmt->bind_param("iisi", $status1, $status2, $document_notes, $did);
            $stmt->execute();
            $stmt->close();
            $timer_flag = 'doc_approval';
            include_once 'timerfunc.php';
        }
    }
    if(isset($_REQUEST['doc_editsave']) && $_REQUEST['doc_editsave'] === 'حفظ'){
        if($row_permcheck['note_eperm'] == 1){
            $did = filter_input(INPUT_POST, 'diddocstatus', FILTER_SANITIZE_NUMBER_INT);
            $fid = filter_input(INPUT_POST, 'fiddocstatus', FILTER_SANITIZE_NUMBER_INT);
            $document_notes = filter_input(INPUT_POST, 'document_notes', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $stmt = $conn->prepare("UPDATE case_document SET document_notes=? WHERE did=?");
            $stmt->bind_param("si", $document_notes, $did);
            $stmt->execute();
            $stmt->close();
            
            $timer_flag = 'case_edit';
            include_once 'timerfunc.php';
        }
    }
    if(isset($_REQUEST['doc_refuse']) && $_REQUEST['doc_refuse'] === 'ارجاع'){
        $did = filter_input(INPUT_POST, 'diddocstatus', FILTER_SANITIZE_NUMBER_INT);
        $fid = filter_input(INPUT_POST, 'fiddocstatus', FILTER_SANITIZE_NUMBER_INT);
        $approve_check = filter_input(INPUT_POST, 'approve_check', FILTER_SANITIZE_NUMBER_INT);
        if($row_permcheck['note_eperm'] == 1){
            $document_notes = filter_input(INPUT_POST, 'document_notes', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        } else{
            $document_notes = '';
        }
        
        $stmtr = $conn->prepare("SELECT * FROM case_document WHERE did=?");
        $stmtr->bind_param("i", $did);
        $stmtr->execute();
        $resultr = $stmtr->get_result();
        $rowr = $resultr->fetch_assoc();
        $stmtr->close();
        
        $fidd = $fid;
        if($approve_check == 0 || $approve_check === ''){
            if($row_permcheck['doc_faperm'] == 1){
                $status1 = 2;
                
                if(isset($rowr['status2'])){
                    $status2 = $rowr['status2'];
                } else{
                    $status2 = 0;
                }
            }
        } else if($approve_check == 1){
            if($row_permcheck['doc_laperm'] == 1){
                $status2 = 2;
                
                if(isset($rowr['status1'])){
                    $status1 = $rowr['status1'];
                } else{
                    $status1 = 0;
                }
            }
        } else{
            header("Location: FileEdit.php?id=$fid&error=0");
            exit();
        }
        
        if($status1 == 2 || $status2 == 2){
            $stmt = $conn->prepare("UPDATE case_document SET status1=?, status2=?, document_notes=? WHERE did=?");
            $stmt->bind_param("iisi", $status1, $status2, $document_notes, $did);
            $stmt->execute();
            $stmt->close();
            $timer_flag = 'doc_refusing';
            include_once 'timerfunc.php';
        }
    }
    if((isset($_REQUEST['doc_status']) && $_REQUEST['doc_status'] === 'اعتماد') || (isset($_REQUEST['doc_editsave']) && $_REQUEST['doc_editsave'] === 'حفظ') || (isset($_REQUEST['doc_refuse']) && $_REQUEST['doc_refuse'] === 'ارجاع')){
        $did = filter_input(INPUT_POST, 'diddocstatus', FILTER_SANITIZE_NUMBER_INT);
        $fid = filter_input(INPUT_POST, 'fiddocstatus', FILTER_SANITIZE_NUMBER_INT);
        
        $stmt = $conn->prepare("SELECT * FROM case_document WHERE did=?");
        $stmt->bind_param("i", $did);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        $document_notes = $row['document_notes'];
        if($document_notes !== ''){
            $notempids = [];
            $stmtf = $conn->prepare("SELECT * FROM file WHERE file_id=?");
            $stmtf->bind_param("i", $fid);
            $stmtf->execute();
            $resultf = $stmtf->get_result();
            $rowf = $resultf->fetch_assoc();
            
            $notempids[] = $rowf['flegal_researcher'];
            $notempids[] = $rowf['flegal_researcher2'];
            $notempids[] = $rowf['flegal_advisor'];
            $notempids[] = $rowf['flegal_advisor2'];
            $notempids[] = $rowf['file_secritary'];
            $notempids[] = $rowf['file_secritary2'];
            $notempids[] = $rowf['file_lawyer'];
            $notempids[] = $rowf['file_lawyer2'];
            
            $target = "case_document /-/ $fid";
            $target_id = $did;
            $notification = "يرجى تنفيذ التعديلات المطلوبة في المذكرة في الملف رقم $fid";
            $notification_date = date("Y-m-d");
            $status = 0;
            $timestamp = date("Y-m-d H:i:s");
            
            foreach($notempids as $empid){
                if($empid != 0 && $empid !== ''){
                    $respid = $empid;
                    
                    $stmt = $conn->prepare("INSERT INTO notifications (resp_id, empid, target, target_id, notification, notification_date, status, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("iisissis", $respid, $empid, $target, $target_id, $notification, $notification_date, $status, $timestamp);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
        
        header("Location: FileEdit.php?id=$fid");
        exit();
    }
    if($fidd === '' && $_REQUEST['job_fid'] !== ''){
        $fidd = filter_input(INPUT_POST, "job_fid", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    if($fidd === '' && $_REQUEST['job_fid2'] !== ''){
        $fidd = filter_input(INPUT_POST, "job_fid2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    if($fidd === '' && $_REQUEST['job_fid3'] !== ''){
        $fidd = filter_input(INPUT_POST, "job_fid3", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    if($fidd === '' && $_REQUEST['fid'] !== ''){
        $fidd = filter_input(INPUT_POST, "fid", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    if($fidd === '' && $_REQUEST['fiddocstatus'] !== ''){
        $fidd = $_REQUEST['fiddocstatus'];
    }
    if($fidd === '' && $_GET['fid'] !== ''){
        $fidd = $_GET['fid'];
    }
    if($fidd === '' && $_GET['judfid'] !== ''){
        $fidd = $_GET['judfid'];
    }
    if($fidd === '' && $_GET['session_fid'] !== ''){
        $fidd = $_GET['session_fid'];
    }
    if($fidd === '' && $_GET['job_fid'] !== ''){
        $fidd = $_GET['job_fid'];
    }
    if($fidd === '' && $_GET['job_fid1'] !== ''){
        $fidd = $_GET['job_fid1'];
    }
    if($fidd === '' && $_GET['fidww'] !== ''){
        $fidd = $_GET['fidww'];
    }
    if($fidd === '' && $_GET['fid_edit'] !== ''){
        $fidd = $_GET['fid_edit'];
    }
    if($fidd === '' && $_GET['fidget'] !== ''){
        $fidd = $_GET['fidget'];
    }
    if($fidd === '' && $_GET['fid2_inv'] !== ''){
        $fidd = $_GET['fid2_inv'];
    }
    if($fidd === '' && $_GET['session_fid2'] !== ''){
        $fidd = $_GET['session_fid2'];
    }
    if($fidd === '' && $_GET['petfid'] !== ''){
        $fidd = $_GET['petfid'];
    }
    if($fidd === '' && $_GET['reqfidid'] !== ''){
        $fidd = $_GET['reqfidid'];
    }
    if($fidd === '' && $_GET['fidnotetsk'] !== ''){
        $fidd = $_GET['fidnotetsk'];
    }
    $_SESSION['form_data'] = $_POST;
    header("Location: FileEdit.php?error=0&id=$fidd");
    exit();
?>