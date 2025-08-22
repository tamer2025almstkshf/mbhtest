<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    include_once 'permissions_check.php';
    include_once 'AES256.php';
    
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $formData = $_SESSION['form_data'] ?? [];
    
    $stmtce = $conn->prepare("SELECT * FROM file WHERE client_id='' AND file_type='' AND frelated_place='' AND file_class='' AND file_status='' AND file_client=''");
    $stmtce->execute();
    $resultce = $stmtce->get_result();
    $stmtce->close();
    
    if($resultce->num_rows == 0){
        $stmtcn = $conn->prepare("INSERT INTO file (client_id, file_type, frelated_place, file_class, file_status, file_client) VALUES ('', '', '', '', '', '')");
        $stmtcn->execute();
        $resultcn = $stmtcn->get_result();
        $stmtcn->close();
    }
?>
<!DOCTYPE html>
<html dir="rtl">
    <head>
        <title>محمد بني هاشم للمحاماة و الاستشارات القانونية</title>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <meta name="google-site-verification" content="_xmqQ0kTuDS9ta1v4E4je5rweWQ4qtH1l8_cnWro7Tk" />
        <meta name="robots" content="noindex, nofollow">
        <meta name="googlebot" content="noindex">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link href="css/styles.css" rel="stylesheet">
        <link rel="SHORTCUT ICON" href="img/favicon.ico">
        <style>
            .input-column {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            .hidden-trs {
                display: none;
            }
            .hidden-trs2 {
                display: none;
            }
            .hidden-trs3 {
                display: none;
            }
            .hidden-trs4 {
                display: none;
            }
            .hidden-trs5 {
                display: none
            }
            .hidden-trs6 {
                display: none
            }
            .hidden-trs7 {
                display: none
            }
            .hidden-trs8 {
                display: none
            }
        </style>
    </head>
    <body style="overflow: auto">
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['cfiles_eperm'] == 1){
                        if(isset($_GET['archive']) && $_GET['archive'] == 1){
                            if($row_permcheck['cfiles_archive_perm'] != 1){
                                exit();
                            }
                        }
                ?>
                
                <div class="web-page">
                    <br><br>
                    <?php
                        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                        
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
                    ?>
                    <div class="advinputs-container" id="scrollContainer" style="height: 80vh; overflow-y: auto; border: 5px solid 
                    <?php 
                        if($row_details['file_type'] === 'مدني -عمالى'){ 
                            echo '#FFFF99'; 
                        } else if($row_details['file_type'] === 'جزاء'){ 
                            echo '#99B5E8'; 
                        } else if($row_details['file_type'] === 'أحوال شخصية'){ 
                            echo '#FAB8BA'; 
                        } else if($row_details['file_type'] === 'أحوال شخصية'){ 
                            echo '#e1e1e1'; 
                        } else{ 
                            $stmtft = $conn->prepare("SELECT * FROM file_types WHERE file_type=?"); 
                            $stmtft->bind_param("s", $ft); 
                            $stmtft->execute(); 
                            $resultft = $stmtft->get_result(); 
                            $rowft = $resultft->fetch_assoc(); 
                            $stmtft->close(); 
                            echo $rowft['type_color']; 
                        }?>">
                        <form action="editfile.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="timermainid" value="<?php echo safe_output($_GET['id']);?>">
                            <input type="hidden" name="timeraction" value="file_edit">
                            <input type="hidden" name="timerdone_action" value="تعديل بيانات الملف رقم <?php echo $_GET['id'];?>">
                            <input type="hidden" name="timerdone_date" value="<?php echo date("Y-m-d");?>">
                            <input type="hidden" name="timer_timestamp" value="<?php echo time();?>">
                            <h2 class="advinputs-h2 blue-parag" style="border-radius: 5px 5px 0 0; background-color: #12538640">بيانات الملف <?php if(isset($row_details['file_levels']) && $row_details['file_levels'] !== ''){ echo '(<font color="#ff0000"> ' . safe_output($row_details['file_levels']) . ' </font>)'; }?></h2>
                            <?php
                                $userid = intVal($_SESSION['id']);
                                $stmt_res = $conn->prepare("SELECT * FROM user WHERE id=?");
                                $stmt_res->bind_param("i", $userid);
                                $stmt_res->execute();
                                $result_res = $stmt_res->get_result();
                                $row_res = $result_res->fetch_assoc();
                                $stmt_res->close();
                                
                                $resp_name = $row_res['name'];
                            ?>
                            <input type="hidden" name="resp_name" value="<?php echo safe_output($resp_name);?>">
                            <div class="advinputs3">
                                <div style="width: 100%; text-align: -webkit-right">
                                    <?php if($row_permcheck['levels_eperm'] == 1){?>
                                    <div style="border-radius: 3px; border: 1px solid #00000140; margin-top: 10px; padding: 10px; width: fit-content; display: grid; grid-template-columns: auto 1fr; cursor: pointer" onclick="MM_openBrWindow('Fees.php?id=<?php echo safe_output($_GET['id']); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                        <img src="img/expenses.png" width="30px" height="30px" style="padding-left: 10px">
                                        <p style="align-content: center;" class="blue-parag">مراحل الدعوى</p>
                                    </div>
                                    <?php }?>
                                </div>
                                <div style="width: 100%; text-align: -webkit-center">
                                    <?php
                                        $fid = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                                        
                                        $stmtfrc = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                                        $stmtfrc->bind_param("i", $fid);
                                        $stmtfrc->execute();
                                        $resultfrc = $stmtfrc->get_result();
                                        $rowfrc = $resultfrc->fetch_assoc();
                                        $stmtfrc->close();
                                        
                                        $querystringfrc = "mfid=$fid";
                                        if(isset($rowfrc['related_file1']) && $rowfrc['related_file1'] !== ''){
                                            $querystringfrc = $querystringfrc."&fid1=".$rowfrc['related_file1'];
                                        }
                                        if(isset($rowfrc['related_file2']) && $rowfrc['related_file2'] !== ''){
                                            $querystringfrc = $querystringfrc."&fid2=".$rowfrc['related_file2'];
                                        }
                                        if(isset($rowfrc['related_file3']) && $rowfrc['related_file3'] !== ''){
                                            $querystringfrc = $querystringfrc."&fid3=".$rowfrc['related_file3'];
                                        }
                                    ?>
                                    <div style="border-radius: 3px; border: 1px solid #00000140; margin-top: 10px; padding: 10px; width: fit-content; display: grid; grid-template-columns: auto 1fr; cursor: pointer" onclick="MM_openBrWindow('relatedfiles.php?<?php echo safe_output($querystringfrc); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                        <img src="img/link.png" width="30px" height="30px" style="padding-left: 10px">
                                        <p style="align-content: center;" class="blue-parag">الملفات المرتبطة</p>
                                    </div>
                                </div>
                                <div style="text-align: -webkit-left; width: 100%;">
                                    <div style="border-radius: 3px; border: 1px solid #00000140; margin-top: 10px; padding: 10px; width: fit-content; display: grid; grid-template-columns: auto 1fr;">
                                        <?php if(isset($row_details['file_status']) && $row_details['file_status'] === 'مؤرشف'){?>
                                        <img src="img/archive.png" width="30px" height="30px" style="padding-left: 10px">
                                        <p style="align-content: center;" class="blue-parag">ملف مؤرشف</p>
                                        <?php } if(isset($row_details['file_status']) && $row_details['file_status'] === 'متداول'){?>
                                        <img src="img/Circulating.png" width="30px" height="30px" style="padding-left: 10px">
                                        <p style="align-content: center;" class="blue-parag">ملف متداول</p>
                                        <?php } if(isset($row_details['file_status']) && $row_details['file_status'] === 'في الانتظار'){?>
                                        <img src="img/pending.png" width="30px" height="30px" style="padding-left: 10px">
                                        <p style="align-content: center;" class="blue-parag">ملف في الانتظار</p>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <div class="grid-fid">
                                        <div>
                                            <p class="input-parag">
                                                <font class="blue-parag">
                                                    <input type="checkbox" name="important" value="1" <?php if(isset($row_details['important']) && $row_details['important'] == 1){ echo 'checked'; }?>>
                                                    تسجيل كدعوى مهمة
                                                </font>
                                                <br><br>
                                                <font class="blue-parag">
                                                    رقم الملف : 
                                                    <?php 
                                                        $fplace = isset($row_details['frelated_place']) ? safe_output($row_details['frelated_place']) : '';
                                                        
                                                        if($fplace === 'الشارقة'){ 
                                                            echo 'SHJ '; 
                                                        } else if($fplace === 'دبي'){ 
                                                            echo 'DXB '; 
                                                        } else if($fplace === 'عجمان'){ 
                                                            echo 'AJM '; 
                                                        } 
                                                        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                                                        
                                                        echo safe_output($id);
                                                    ?>
                                                </font> 
                                                <?php 
                                                    if(isset($row_details['investigating']) && $row_details['investigating'] == '1'){ 
                                                        echo '<font color="red">( هذا الملف قيد التحقيق )</font>'; 
                                                    }
                                                ?>
                                            </p>
                                            <br>
                                            <button class="green-button" type="button" onclick="addclient()">الملاحظات</button>
                                            <div id="addclient-btn" class="modal-overlay" <?php if(isset($_GET['checknotes']) && $_GET['checknotes'] == 1){?>style="display: block;"<?php }?>>
                                                <div class="modal-content" style="margin: auto; align-content: center">
                                                    <div class="notes-displayer">
                                                        <div class="addc-header">
                                                            <?php $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;?>
                                                            <h4 class="addc-header-parag" style="margin: auto"><img src="img/add-button.png" width="20px" height="20px" title="اضافة ملاحظة" style="cursor: pointer;" onclick="MM_openBrWindow('selector/Notes.php?fid=<?php echo $_GET['id'];?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=400');"> ملاحظات الملف : <?php echo safe_output($id); ?></h4>
                                                            <div class="close-button-container">
                                                                <p class="close-button" onclick="<?php if(isset($_GET['checknotes']) && $_GET['checknotes'] == 1){ echo "location.href='FileEdit.php?id=$id';"; } else{ echo 'addclient()'; }?>">&times;</p>
                                                            </div>
                                                        </div>
                                                        <div class="notes-body" style="padding: 10px; text-align: right;">
                                                            <?php
                                                                $fid = safe_output($_GET['id']);
                                                                $stmtnotes = $conn->prepare("SELECT * FROM file_note WHERE file_id = ? ORDER BY id DESC");
                                                                $stmtnotes->bind_param("i", $fid);
                                                                $stmtnotes->execute();
                                                                $resultnotes = $stmtnotes->get_result();
                                                                if($resultnotes->num_rows > 0){
                                                                    $count = 0;
                                                                    while($rownotes = $resultnotes->fetch_assoc()){
                                                                        $count ++
                                                            ?>
                                                            <div class="attachment-row">
                                                                <?php $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;?>
                                                                <p style="font-size: 12px"><?php echo safe_output($rownotes['doneby']); ?><br><?php echo safe_output($rownotes['timestamp']); ?></p>
                                                                <p style="cursor: pointer" onClick="window.open('selector/Notes.php?fid=<?php echo safe_output($id); ?>&id=<?php echo safe_output($rownotes['id']); ?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;"><?php echo safe_output($rownotes['notename']); ?>...</p>
                                                                <div class="perms-check" onclick="location.href='notedel.php?id=<?php echo safe_output($rownotes['id']); ?>';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                            </div>
                                                            <?php 
                                                                    }
                                                                    $stmtnotes->close();
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input class="form-input" type="hidden" name="fidget" value="<?php $id = isset($_GET['id']) ? (int)$_GET['id'] : 0; echo safe_output($id); ?>">
                                        </div>
                                        <div>
                                            <?php 
                                                $count = 0; 
                                                $rfids = '';
                                                if(isset($row_details['related_file1']) && $row_details['related_file1'] != ''){ 
                                                    $count++; 
                                                    $rfid = isset($row_details['related_file1']) ? $row_details['related_file1'] : 0;
                                                    if($rfids === ''){
                                                        $rfids = "$rfid";
                                                    } else{
                                                        $rfids = $rfids . ", $rfid";
                                                    }
                                                }
                                                if(isset($row_details['related_file2']) && $row_details['related_file2'] != ''){ 
                                                    $count++; 
                                                    $rfid = isset($row_details['related_file2']) ? $row_details['related_file2'] : 0;
                                                    if($rfids === ''){
                                                        $rfids = "$rfid";
                                                    } else{
                                                        $rfids = $rfids . ", $rfid";
                                                    }
                                                } 
                                                if(isset($row_details['related_file3']) && $row_details['related_file3'] != ''){ 
                                                    $count++; 
                                                    $rfid = isset($row_details['related_file3']) ? $row_details['related_file3'] : 0;
                                                    if($rfids === ''){
                                                        $rfids = "$rfid";
                                                    } else{
                                                        $rfids = $rfids . ", $rfid";
                                                    }
                                                }
                                                if($count == 1){
                                                    $stmtrfi = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                                                    $stmtrfi->bind_param("i", $rfid);
                                                } else{
                                                    $ids = explode(',', $rfids);
                                                    $placeholders = implode(',', array_fill(0, count($ids), '?'));
                                                    $types = str_repeat('i', count($ids));
                                                    
                                                    $sql = "SELECT * FROM file WHERE file_id IN ($placeholders)";
                                                    $stmtrfi = $conn->prepare($sql);
                                                    
                                                    $stmtrfi->bind_param($types, ...$ids);
                                                }
                                                $stmtrfi->execute();
                                                $resultrfi = $stmtrfi->get_result();
                                                if($resultrfi->num_rows > 0){
                                                    $num = 0;
                                            ?>
                                            <div class="related_file">
                                                <?php
                                                    while($rowrfi = $resultrfi->fetch_assoc()){
                                                        $num++;
                                                ?>
                                                <div class="exclamation-mark" onclick="MM_openBrWindow('FileEdit.php?id=<?php echo safe_output($rowrfi['file_id']); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')" style="cursor:pointer; display: inline-block;"></div>
                                                <font color="red">
                                                    <?php
                                                        $fplace = $rowrfi['frelated_place']; 
                                                        if($fplace === 'الشارقة'){ 
                                                            echo 'SHJ '; 
                                                        } else if($fplace === 'دبي'){ 
                                                            echo 'DXB '; 
                                                        } else if($fplace === 'عجمان'){ 
                                                            echo 'AJM '; 
                                                        }
                                                    ?>
                                                </font>
                                                <?php 
                                                        echo safe_output($rowrfi['file_id']).'<br>';
                                                    }
                                                ?>
                                            </div>
                                            <?php
                                                }
                                                $stmtrfi->close();
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag">
                                        <font class="blue-parag">
                                            تصنيف الملف <font color="#FF0000">*</font> 
                                            <?php if($row_permcheck['selectors_rperm'] == 1){?>
                                            <img src="img/add-button.png" width="20px" height="20px" title="اضافة تصنيف" style="cursor: pointer;" onclick="MM_openBrWindow('selector/Classes.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=400');">
                                            <?php }?>
                                        </font>
                                    </p>
                                    <select name="fclass_edit" class="table-header-selector" style="width: 80%; height: fit-content; margin: 10px 0;">
                                        <?php
                                            $stmtclass = $conn->prepare("SELECT * FROM fclass");
                                            $stmtclass->execute();
                                            $resultclass = $stmtclass->get_result();
                                            if($resultclass->num_rows > 0){
                                                while($rowclass = $resultclass->fetch_assoc()){
                                        ?>
                                        <option value="<?php echo safe_output($rowclass['class_name']); ?>" <?php if($rowclass['class_name'] === $row_details['file_class']){ echo 'selected'; }?>><?php echo safe_output($rowclass['class_name']); ?></option>
                                        <?php
                                                }
                                            }
                                            $stmtclass->close();
                                        ?>
                                    </select>
                                </div>
                                <div style="text-align: -webkit-left; width: 100%;">
                                    <div style="padding: 10px;"></div>
                                    <?php if($row_permcheck['session_rperm'] == 1){?>
                                    <div style="border-radius: 3px; border: 1px solid #00000140; margin-top: 10px; padding: 10px; width: fit-content; display: grid; grid-template-columns: auto 1fr; cursor:pointer;" onclick="open('oppCase.php?fid=<?php echo safe_output($_GET['id']); ?>','Pic','width=600 height=700 scrollbars=yes')">
                                        <img src="img/judge.png" width="30px" height="30px" style="padding-left: 10px">
                                        <p style="align-content: center;" class="blue-parag">قضية متقابلة</p>
                                    </div>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container" style="align-content: center;">
                                    <p class="input-parag">
                                        <div class="exclamation-mark" onclick="MM_openBrWindow('CasePreview.php?fid=<?php echo safe_output($row_details['file_id']); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')" style="cursor:pointer; display: inline-block;"></div> 
                                        <font class="blue-parag">
                                            نوع الملف :
                                        </font>
                                        <?php 
                                            echo safe_output($row_details['file_type']); 
                                            if($row_permcheck['selectors_rperm'] == 1){
                                        ?> 
                                        <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="MM_openBrWindow('selector/fileType.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=400')" align="absmiddle" style="cursor:pointer"/>
                                        <?php }?>
                                    </p>
                                    <input type="hidden" value="<?php echo safe_output($row_details['file_type']); ?>" name="type_edit">
                                </div>
                                <div class="input-container">
                                    <p class="input-parag">
                                        <font class="blue-parag">
                                            الفرع المختص
                                        </font> 
                                        <?php if($row_permcheck['selectors_rperm'] == 1){?>
                                        <img src="img/add-button.png" width="20px" heigh="20px" title="اضافة فرع" style="cursor: pointer;" onclick="MM_openBrWindow('selector/Branchs.php?fid=<?php echo safe_output($_GET['id']); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=200')">
                                        <?php }?>
                                    </p>
                                    <select name="place_edit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value="الشارقة" <?php if($row_details['frelated_place'] === 'الشارقة'){ echo 'selected'; }?>>الشارقة</option>
                                        <option value="دبي" <?php if($row_details['frelated_place'] === 'دبي'){ echo 'selected'; }?>>دبي</option>
                                        <option value="عجمان" <?php if($row_details['frelated_place'] === 'عجمان'){ echo 'selected'; }?>>عجمان</option>
                                        <?php
                                            $stmtbranch = $conn->prepare("SELECT * FROM branchs");
                                            $stmtbranch->execute();
                                            $resultbranch = $stmtbranch->get_result();
                                            if($resultbranch->num_rows > 0){
                                                while($rowbranch = $resultbranch->fetch_assoc()){
                                        ?>
                                        <option value="<?php echo safe_output($rowbranch['branch']); ?>" <?php if($rowbranch['branch'] === $row_details['frelated_place']){ echo 'selected'; }?>><?php echo safe_output($rowbranch['branch']); ?></option>
                                        <?php
                                                }
                                            }
                                            $stmtbranch->close();
                                        ?>
                                    </select>
                                </div>
                                <div style="text-align: left">
                                    <?php if($row_permcheck['secretf_aperm'] == 1){?>
                                    <img src="img/secret-folder.png" width="25px" height="25px" title="ملف سري" style="padding-left: 10px; cursor: pointer" onclick="MM_openBrWindow('SecretFolder.php?id=<?php echo safe_output($_GET['id']); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')"><br>
                                    <?php }?>
                                    <img src="img/pending.png" width="25px" height="25px" style="padding-left: 10px" title="في الانتظار"><input type="radio" name="fstatus_edit" value="في الانتظار" style="margin: 10px 0;" <?php if(isset($row_details['file_status']) && $row_details['file_status'] === 'في الانتظار'){ echo 'checked'; }?>><br>
                                    <img src="img/Circulating.png" width="25px" height="25px" style="padding-left: 10px" title="متداول"><input type="radio" name="fstatus_edit" value="متداول" style="margin: 10px 0;" <?php if(isset($row_details['file_status']) && $row_details['file_status'] === 'متداول'){ echo 'checked'; }?>><br>
                                    <img src="img/archive.png" width="25px" height="25px" style="padding-left: 10px" title="مؤرشف"><input type="radio" name="fstatus_edit" value="مؤرشف" <?php if(isset($row_details['file_status']) && $row_details['file_status'] === 'مؤرشف'){ echo 'checked'; }?>>
                                </div>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الموضوع</font></p>
                                    <textarea class="form-input" rows="2" type="text" name="fsubject_edit"><?php echo safe_output($row_details['file_subject']); ?></textarea>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الملاحظات</font></p>
                                    <textarea class="form-input" rows="2" type="text" name="fnotes_edit"><?php echo safe_output($row_details['file_notes']); ?></textarea>
                                </div>
                                <div style="text-align: -webkit-left; width: 100%;">
                                    <?php if($row_details['file_type'] === 'جزاء'){?>
                                    <div style="border-radius: 3px; border: 1px solid #00000140; margin-top: 10px; padding: 10px; width: fit-content; display: grid; grid-template-columns: auto 1fr; cursor:pointer;">
                                        <img src="img/investigating.png" width="30px" height="30px" style="padding-left: 10px">
                                        <?php if($row_details['investigating'] == 1 && $row_details['file_type'] === 'جزاء'){?>
                                        <input type="hidden" name="fid2_inv" value="<?php echo safe_output($_GET['id']); ?>">
                                        <button type="submit" name="investigating_done" value="انتهاء التحقيق" class="green-button">انتهى التحقيق</button>
                                        <?php } else if($row_details['investigating'] != 1 && $row_details['file_type'] === 'جزاء'){?>
                                        <input type="hidden" name="fid2_inv" value="<?php echo safe_output($_GET['id']); ?>">
                                        <button type="submit" name="investigating_start" value="تحويل الملف الى (قيد التحقيق)" class="green-button">قيد التحقيق</button>
                                        <?php }?>
                                    </div>
                                    <?php }?>
                                </div>
                            </div>
                            <br>
                            <div class="advinputs4">
                                <h2 class="advinputs-h2 blue-parag" style="grid-column: span 4; border-radius: 5px 5px 0 0; background-color: #12538640">بيانات الاطراف</h2>
                                <div class="input-column">
                                    <div class="input-container">
                                        <p class="input-parag">
                                            <font class="blue-parag">
                                                <?php if($row_permcheck['clients_rperm'] == 1){?>
                                                <img src="img/menu-bar.png" width="20px" height="20px" title="اضافة موكلين على الملف" onclick="toggleMoreClients()" align="absmiddle" style="cursor:pointer">
                                                <?php }?>
                                                الموكل <font color="#FF0000">*</font>
                                            </font>
                                            <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="MM_openBrWindow('clients.php?addmore=1','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=400')" align="absmiddle" style="cursor:pointer">
                                        </p>
                                        <select name="fclient_edit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                            <?php 
                                                $stmt_sclients = $conn->prepare("SELECT * FROM client WHERE terror!='1' AND arname!='' AND client_kind='موكل' ORDER BY id DESC");
                                                $stmt_sclients->execute();
                                                $result_sclients = $stmt_sclients->get_result();
                                                if($result_sclients->num_rows > 0){
                                                    while($row_sclients = $result_sclients->fetch_assoc()){
                                                        $cli_id = $row_sclients['id'];
                                                        $cli_name = $row_sclients['arname'];
                                            ?>
                                            <option value='<?php echo safe_output($cli_id)?>' <?php if($row_details['file_client'] == $cli_id){ echo 'selected'; }?>><?php echo safe_output($cli_id).' # '.safe_output($cli_name)?></option>
                                            <?php
                                                    }
                                                } else{
                                                    echo '<option value=""></option>';
                                                }
                                                $stmt_sclients->close();
                                            ?>
                                        </select>	
                                    </div>
                                    <div id="more-clients1" style="display: none;">
                                        <div class="input-container">
                                            <p class="input-parag"><font class="blue-parag">الموكل 2</font></p>
                                            <select name="fclient_edit2" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php 
                                                    $stmt_sclients = $conn->prepare("SELECT * FROM client WHERE terror!='1' AND arname!='' AND client_kind='موكل' ORDER BY id DESC");
                                                    $stmt_sclients->execute();
                                                    $result_sclients = $stmt_sclients->get_result();
                                                    if($result_sclients->num_rows > 0){
                                                        while($row_sclients = $result_sclients->fetch_assoc()){
                                                            $cli_id = $row_sclients['id'];
                                                            $cli_name = $row_sclients['arname'];
                                                ?>
                                                <option value='<?php echo safe_output($cli_id)?>' <?php if($row_details['file_client2'] == $cli_id){ echo 'selected'; }?>><?php echo safe_output($cli_id).' # '.safe_output($cli_name)?></option>
                                                <?php
                                                        }
                                                    } else{
                                                        echo '<option value=""></option>';
                                                    }
                                                    $stmt_sclients->close();
                                                ?>
                                            </select>	
                                        </div>
                                        <div class="input-container">
                                            <p class="input-parag"><font class="blue-parag">الموكل 3</font></p>
                                            <select name="fclient_edit3" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php 
                                                    $stmt_sclients = $conn->prepare("SELECT * FROM client WHERE terror!='1' AND arname!='' AND client_kind='موكل' ORDER BY id DESC");
                                                    $stmt_sclients->execute();
                                                    $result_sclients = $stmt_sclients->get_result();
                                                    if($result_sclients->num_rows > 0){
                                                        while($row_sclients = $result_sclients->fetch_assoc()){
                                                            $cli_id = $row_sclients['id'];
                                                            $cli_name = $row_sclients['arname'];
                                                ?>
                                                <option value='<?php echo safe_output($cli_id)?>' <?php if($row_details['file_client3'] == $cli_id){ echo 'selected'; }?>><?php echo safe_output($cli_id).' # '.safe_output($cli_name)?></option>
                                                <?php
                                                        }
                                                    } else{
                                                        echo '<option value=""></option>';
                                                    }
                                                    $stmt_sclients->close();
                                                ?>
                                            </select>	
                                        </div>
                                        <div class="input-container">
                                            <p class="input-parag"><font class="blue-parag">الموكل 4</font></p>
                                            <select name="fclient_edit4" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php 
                                                    $stmt_sclients = $conn->prepare("SELECT * FROM client WHERE terror!='1' AND arname!='' AND client_kind='موكل' ORDER BY id DESC");
                                                    $stmt_sclients->execute();
                                                    $result_sclients = $stmt_sclients->get_result();
                                                    if($result_sclients->num_rows > 0){
                                                        while($row_sclients = $result_sclients->fetch_assoc()){
                                                            $cli_id = $row_sclients['id'];
                                                            $cli_name = $row_sclients['arname'];
                                                ?>
                                                <option value='<?php echo safe_output($cli_id);?>' <?php if($row_details['file_client4'] == $cli_id){ echo 'selected'; }?>><?php echo safe_output($cli_id).' # '.safe_output($cli_name);?></option>
                                                <?php
                                                        }
                                                    } else{
                                                        echo '<option value=""></option>';
                                                    }
                                                    $stmt_sclients->close();
                                                ?>
                                            </select>	
                                        </div>
                                        <div class="input-container">
                                            <p class="input-parag"><font class="blue-parag">الموكل 5</font></p>
                                            <select name="fclient_edit5" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php 
                                                    $stmt_sclients = $conn->prepare("SELECT * FROM client WHERE terror!='1' AND arname!='' AND client_kind='موكل' ORDER BY id DESC");
                                                    $stmt_sclients->execute();
                                                    $result_sclients = $stmt_sclients->get_result();
                                                    if($result_sclients->num_rows > 0){
                                                        while($row_sclients = $result_sclients->fetch_assoc()){
                                                            $cli_id = $row_sclients['id'];
                                                            $cli_name = $row_sclients['arname'];
                                                ?>
                                                <option value='<?php echo safe_output($cli_id)?>' <?php if($row_details['file_client5'] == $cli_id){ echo 'selected'; }?>><?php echo safe_output($cli_id).' # '.safe_output($cli_name)?></option>
                                                <?php
                                                        }
                                                    } else{
                                                        echo '<option value=""></option>';
                                                    }
                                                    $stmt_sclients->close();
                                                ?>
                                            </select>	
                                        </div>
                                    </div>
                                </div>
                                <div class="input-column">
                                    <div class="input-container">
                                        <p class="input-parag">
                                            <font class="blue-parag">
                                                صفة الموكل
                                            </font> 
                                            <?php if($row_permcheck['selectors_rperm'] == 1){?>
                                            <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="open('selector/AddClientStatus.php','Pic','width=600 height=700 scrollbars=yes')" align="absmiddle" style="cursor:pointer"/>
                                            <?php }?>
                                        </p>
                                        <select name="fchar_edit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                            <option value=""></option>
                                            <?php 
                                                $stmt_scharachteristics = $conn->prepare("SELECT * FROM client_status");
                                                $stmt_scharachteristics->execute();
                                                $result_scharachteristics = $stmt_scharachteristics->get_result();
                                                if($result_scharachteristics->num_rows > 0){
                                                    while($row_scharachteristics = $result_scharachteristics->fetch_assoc()){
                                                        $stname = $row_scharachteristics['arname'];
                                            ?>
                                            <option value='<?php echo safe_output($stname)?>' <?php if($row_details['fclient_characteristic'] === $stname){ echo 'selected'; }?>><?php echo safe_output($stname)?></option>
                                            <?php
                                                    }
                                                }
                                                $stmt_scharachteristics->close();
                                            ?>
                                        </select>
                                    </div>
                                    <div id="more-clients2" style="display: none;">
                                        <div class="input-container">
                                            <p class="input-parag"><font class="blue-parag">صفة الموكل 2</font></p>
                                            <select name="fchar_edit2" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php 
                                                    $stmt_scharachteristics = $conn->prepare("SELECT * FROM client_status");
                                                    $stmt_scharachteristics->execute();
                                                    $result_scharachteristics = $stmt_scharachteristics->get_result();
                                                    if($result_scharachteristics->num_rows > 0){
                                                        while($row_scharachteristics = $result_scharachteristics->fetch_assoc()){
                                                            $stname = $row_scharachteristics['arname'];
                                                ?>
                                                <option value='<?php echo safe_output($stname)?>' <?php if($row_details['fclient_characteristic2'] === $stname){ echo 'selected'; }?>><?php echo safe_output($stname)?></option>
                                                <?php
                                                        }
                                                    }
                                                    $stmt_scharachteristics->close();
                                                ?>
                                            </select>
                                        </div>
                                        <div class="input-container">
                                            <p class="input-parag"><font class="blue-parag">صفة الموكل 3</font></p>
                                            <select name="fchar_edit3" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php 
                                                    $stmt_scharachteristics = $conn->prepare("SELECT * FROM client_status");
                                                    $stmt_scharachteristics->execute();
                                                    $result_scharachteristics = $stmt_scharachteristics->get_result();
                                                    if($result_scharachteristics->num_rows > 0){
                                                        while($row_scharachteristics = $result_scharachteristics->fetch_assoc()){
                                                            $stname = $row_scharachteristics['arname'];
                                                ?>
                                                <option value='<?php echo safe_output($stname)?>' <?php if($row_details['fclient_characteristic3'] === $stname){ echo 'selected'; }?>><?php echo safe_output($stname)?></option>
                                                <?php
                                                        }
                                                    }
                                                    $stmt_scharachteristics->close();
                                                ?>
                                            </select>
                                        </div>
                                        <div class="input-container">
                                            <p class="input-parag"><font class="blue-parag">صفة الموكل 4</font></p>
                                            <select name="fchar_edit4" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php 
                                                    $stmt_scharachteristics = $conn->prepare("SELECT * FROM client_status");
                                                    $stmt_scharachteristics->execute();
                                                    $result_scharachteristics = $stmt_scharachteristics->get_result();
                                                    if($result_scharachteristics->num_rows > 0){
                                                        while($row_scharachteristics = $result_scharachteristics->fetch_assoc()){
                                                            $stname = $row_scharachteristics['arname'];
                                                ?>
                                                <option value='<?php echo safe_output($stname)?>' <?php if($row_details['fclient_characteristic4'] === $stname){ echo 'selected'; }?>><?php echo safe_output($stname)?></option>
                                                <?php
                                                        }
                                                    }
                                                    $stmt_scharachteristics->close();
                                                ?>
                                            </select>
                                        </div>
                                        <div class="input-container">
                                            <p class="input-parag"><font class="blue-parag">صفة الموكل 5</font></p>
                                            <select name="fchar_edit5" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php 
                                                    $stmt_scharachteristics = $conn->prepare("SELECT * FROM client_status");
                                                    $stmt_scharachteristics->execute();
                                                    $result_scharachteristics = $stmt_scharachteristics->get_result();
                                                    if($result_scharachteristics->num_rows > 0){
                                                        while($row_scharachteristics = $result_scharachteristics->fetch_assoc()){
                                                            $stname = $row_scharachteristics['arname'];
                                                ?>
                                                <option value='<?php echo safe_output($stname)?>' <?php if($row_details['fclient_characteristic5'] === $stname){ echo 'selected'; }?>><?php echo safe_output($stname)?></option>
                                                <?php
                                                        }
                                                    }
                                                    $stmt_scharachteristics->close();
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-column">
                                    <div class="input-container">
                                        <p class="input-parag">
                                            <img src="img/menu-bar.png" width="20px" height="20px" title="اضافة خصوم على الملف" onclick="toggleMoreOpponents()" align="absmiddle" style="cursor:pointer"/>
                                            <font class="blue-parag">
                                                الخصم
                                            </font>
                                            <?php if($row_permcheck['clients_rperm'] == 1){?>
                                            <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="MM_openBrWindow('clients.php?addmore=1','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=400')" align="absmiddle" style="cursor:pointer"/>
                                            <?php }?>
                                        </p>
                                        <select name="fopponent_edit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                            <option value=""></option>
                                            <?php 
                                                $stmt_sclients = $conn->prepare("SELECT * FROM client WHERE terror!='1' AND arname!='' AND client_kind='خصم' ORDER BY id DESC");
                                                $stmt_sclients->execute();
                                                $result_sclients = $stmt_sclients->get_result();
                                                if($result_sclients->num_rows > 0){
                                                    while($row_sclients = $result_sclients->fetch_assoc()){
                                                        $cli_id = $row_sclients['id'];
                                                        $cli_name = $row_sclients['arname'];
                                            ?>
                                            <option value='<?php echo safe_output($cli_id)?>' <?php if($row_details['file_opponent'] == $cli_id){ echo 'selected'; }?>><?php echo safe_output($cli_id).' # '.safe_output($cli_name)?></option>
                                            <?php
                                                    }
                                                } else{
                                                    echo '<option value=""></option>';
                                                }
                                                $stmt_sclients->close();
                                            ?>
                                        </select>
                                    </div>
                                    <div id="more-opponents1" style="display: none;">
                                        <div class="input-container">
                                            <p class="input-parag"><font class="blue-parag">الخصم 2</font></p>
                                            <select name="fopponent_edit2" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php 
                                                    $stmt_sclients = $conn->prepare("SELECT * FROM client WHERE terror!='1' AND arname!='' AND client_kind='خصم' ORDER BY id DESC");
                                                    $stmt_sclients->execute();
                                                    $result_sclients = $stmt_sclients->get_result();
                                                    if($result_sclients->num_rows > 0){
                                                        while($row_sclients = $result_sclients->fetch_assoc()){
                                                            $cli_id = $row_sclients['id'];
                                                            $cli_name = $row_sclients['arname'];
                                                ?>
                                                <option value='<?php echo safe_output($cli_id)?>' <?php if($row_details['file_opponent2'] == $cli_id){ echo 'selected'; }?>><?php echo safe_output($cli_id).' # '.safe_output($cli_name)?></option>
                                                <?php
                                                        }
                                                    } else{
                                                        echo '<option value=""></option>';
                                                    }
                                                    $stmt_sclients->close();
                                                ?>
                                            </select>
                                        </div>
                                        <div class="input-container">
                                            <p class="input-parag"><font class="blue-parag">الخصم 3</font></p>
                                            <select name="fopponent_edit3" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php 
                                                    $stmt_sclients = $conn->prepare("SELECT * FROM client WHERE terror!='1' AND arname!='' AND client_kind='خصم' ORDER BY id DESC");
                                                    $stmt_sclients->execute();
                                                    $result_sclients = $stmt_sclients->get_result();
                                                    if($result_sclients->num_rows > 0){
                                                        while($row_sclients = $result_sclients->fetch_assoc()){
                                                            $cli_id = $row_sclients['id'];
                                                            $cli_name = $row_sclients['arname'];
                                                ?>
                                                <option value='<?php echo safe_output($cli_id)?>' <?php if($row_details['file_opponent3'] == $cli_id){ echo 'selected'; }?>><?php echo safe_output($cli_id).' # '.safe_output($cli_name)?></option>
                                                <?php
                                                        }
                                                    } else{
                                                        echo '<option value=""></option>';
                                                    }
                                                    $stmt_sclients->close();
                                                ?>
                                            </select>
                                        </div>
                                        <div class="input-container">
                                            <p class="input-parag"><font class="blue-parag">الخصم 4</font></p>
                                            <select name="fopponent_edit4" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php 
                                                    $stmt_sclients = $conn->prepare("SELECT * FROM client WHERE terror!='1' AND arname!='' AND client_kind='خصم' ORDER BY id DESC");
                                                    $stmt_sclients->execute();
                                                    $result_sclients = $stmt_sclients->get_result();
                                                    if($result_sclients->num_rows > 0){
                                                        while($row_sclients = $result_sclients->fetch_assoc()){
                                                            $cli_id = $row_sclients['id'];
                                                            $cli_name = $row_sclients['arname'];
                                                ?>
                                                <option value='<?php echo safe_output($cli_id)?>' <?php if($row_details['file_opponent4'] == $cli_id){ echo 'selected'; }?>><?php echo safe_output($cli_id).' # '.safe_output($cli_name)?></option>
                                                <?php
                                                        }
                                                    } else{
                                                        echo '<option value=""></option>';
                                                    }
                                                    $stmt_sclients->close();
                                                ?>
                                            </select>
                                        </div>
                                        <div class="input-container">
                                            <p class="input-parag"><font class="blue-parag">الخصم 5</font></p>
                                            <select name="fopponent_edit5" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php 
                                                    $stmt_sclients = $conn->prepare("SELECT * FROM client WHERE terror!='1' AND arname!='' AND client_kind='خصم' ORDER BY id DESC");
                                                    $stmt_sclients->execute();
                                                    $result_sclients = $stmt_sclients->get_result();
                                                    if($result_sclients->num_rows > 0){
                                                        while($row_sclients = $result_sclients->fetch_assoc()){
                                                            $cli_id = $row_sclients['id'];
                                                            $cli_name = $row_sclients['arname'];
                                                ?>
                                                <option value='<?php echo safe_output($cli_id)?>' <?php if($row_details['file_opponent5'] == $cli_id){ echo 'selected'; }?>><?php echo safe_output($cli_id).' # '.safe_output($cli_name)?></option>
                                                <?php
                                                        }
                                                    } else{
                                                        echo '<option value=""></option>';
                                                    }
                                                    $stmt_sclients->close();
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-column">
                                    <div class="input-container">
                                        <p class="input-parag">
                                            <font class="blue-parag">صفة الخصم</font>
                                            <?php if($row_permcheck['selectors_rperm'] == 1){?>
                                            <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="open('selector/AddClientStatus.php','Pic','width=600 height=700 scrollbars=yes')" align="absmiddle" style="cursor:pointer">
                                            <?php }?>
                                        </p>
                                        <select name="fopponent_charedit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                            <option value=""></option>
                                            <?php 
                                                $stmt_scharachteristics = $conn->prepare("SELECT * FROM client_status");
                                                $stmt_scharachteristics->execute();
                                                $result_scharachteristics = $stmt_scharachteristics->get_result();
                                                if($result_scharachteristics->num_rows > 0){
                                                    while($row_scharachteristics = $result_scharachteristics->fetch_assoc()){
                                                        $stname = $row_scharachteristics['arname'];
                                            ?>
                                            <option value='<?php echo safe_output($stname)?>' <?php if($row_details['fopponent_characteristic'] === $stname){ echo 'selected'; }?>><?php echo safe_output($stname)?></option>";
                                            <?php
                                                    }
                                                }
                                                $stmt_scharachteristics->close();
                                            ?>
                                        </select>
                                    </div>
                                    <div id="more-opponents2" style="display: none;">
                                        <div class="input-container">
                                            <p class="input-parag"><font class="blue-parag">صفة الخصم 2</font></p>
                                            <select name="fopponent_charedit2" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php 
                                                    $stmt_scharachteristics = $conn->prepare("SELECT * FROM client_status");
                                                    $stmt_scharachteristics->execute();
                                                    $result_scharachteristics = $stmt_scharachteristics->get_result();
                                                    if($result_scharachteristics->num_rows > 0){
                                                        while($row_scharachteristics = $result_scharachteristics->fetch_assoc()){
                                                            $stname = $row_scharachteristics['arname'];
                                                ?>
                                                <option value='<?php echo safe_output($stname)?>' <?php if($row_details['fopponent_characteristic2'] === $stname){ echo 'selected'; }?>><?php echo safe_output($stname)?></option>";
                                                <?php
                                                        }
                                                    }
                                                    $stmt_scharachteristics->close();
                                                ?>
                                            </select>
                                        </div>
                                        <div class="input-container">
                                            <p class="input-parag"><font class="blue-parag">صفة الخصم 3</font></p>
                                            <select name="fopponent_charedit3" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php 
                                                    $stmt_scharachteristics = $conn->prepare("SELECT * FROM client_status");
                                                    $stmt_scharachteristics->execute();
                                                    $result_scharachteristics = $stmt_scharachteristics->get_result();
                                                    if($result_scharachteristics->num_rows > 0){
                                                        while($row_scharachteristics = $result_scharachteristics->fetch_assoc()){
                                                            $stname = $row_scharachteristics['arname'];
                                                ?>
                                                <option value='<?php echo safe_output($stname)?>' <?php if($row_details['fopponent_characteristic3'] === $stname){ echo 'selected'; }?>><?php echo safe_output($stname)?></option>";
                                                <?php
                                                        }
                                                    }
                                                    $stmt_scharachteristics->close();
                                                ?>
                                            </select>
                                        </div>
                                        <div class="input-container">
                                            <p class="input-parag"><font class="blue-parag">صفة الخصم 4</font></p>
                                            <select name="fopponent_charedit4" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php 
                                                    $stmt_scharachteristics = $conn->prepare("SELECT * FROM client_status");
                                                    $stmt_scharachteristics->execute();
                                                    $result_scharachteristics = $stmt_scharachteristics->get_result();
                                                    if($result_scharachteristics->num_rows > 0){
                                                        while($row_scharachteristics = $result_scharachteristics->fetch_assoc()){
                                                            $stname = $row_scharachteristics['arname'];
                                                ?>
                                                <option value='<?php echo safe_output($stname)?>' <?php if($row_details['fopponent_characteristic4'] === $stname){ echo 'selected'; }?>><?php echo safe_output($stname)?></option>";
                                                <?php
                                                        }
                                                    }
                                                    $stmt_scharachteristics->close();
                                                ?>
                                            </select>
                                        </div>
                                        <div class="input-container">
                                            <p class="input-parag"><font class="blue-parag">صفة الخصم 5</font></p>
                                            <select name="fopponent_charedit5" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php 
                                                    $stmt_scharachteristics = $conn->prepare("SELECT * FROM client_status");
                                                    $stmt_scharachteristics->execute();
                                                    $result_scharachteristics = $stmt_scharachteristics->get_result();
                                                    if($result_scharachteristics->num_rows > 0){
                                                        while($row_scharachteristics = $result_scharachteristics->fetch_assoc()){
                                                            $stname = $row_scharachteristics['arname'];
                                                ?>
                                                <option value='<?php echo safe_output($stname)?>' <?php if($row_details['fopponent_characteristic5'] === $stname){ echo 'selected'; }?>><?php echo safe_output($stname)?></option>";
                                                <?php
                                                        }
                                                    }
                                                    $stmt_scharachteristics->close();
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="advinputs4">
                                <h2 class="advinputs-h2 blue-parag" style="grid-column: span 4; border-radius: 5px 5px 0 0; background-color: #12538640">المحاكم و مراكز الشرطة</h2>
                                <div class="input-container">
                                    <p class="input-parag">
                                        <font class="blue-parag">
                                            نوع القضية
                                        </font>
                                        <?php if($row_permcheck['selectors_rperm'] == 1){?>
                                        <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="MM_openBrWindow('selector/CaseType.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=400')" align="absmiddle" style="cursor:pointer"/>
                                        <?php }?>
                                    </p>
                                    <select name="fctype_edit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php 
                                            $stmt_ct = $conn->prepare("SELECT * FROM case_type");
                                            $stmt_ct->execute();
                                            $result_ct = $stmt_ct->get_result();
                                            if($result_ct->num_rows > 0){
                                                while($row_ct = $result_ct->fetch_assoc()){
                                                    $case_name = $row_ct['ct_name'];
                                        ?>
                                        <option value='<?php echo safe_output($case_name); ?>' <?php if($row_details['fcase_type'] === $case_name){ echo 'selected'; }?>><?php echo safe_output($case_name); ?></option>
                                        <?php
                                                }
                                            }
                                            $stmt_ct->close();
                                        ?>
                                    </select>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag">
                                        <font class="blue-parag">
                                            مركز الشرطة
                                        </font>
                                        <?php if($row_permcheck['selectors_rperm'] == 1){?>
                                        <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="MM_openBrWindow('selector/AddPS.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=400')" align="absmiddle" style="cursor:pointer"/>
                                        <?php }?>
                                    </p>
                                    <select name="fpolice_edit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php 
                                            $stmt_ps = $conn->prepare("SELECT * FROM police_station");
                                            $stmt_ps->execute();
                                            $result_ps = $stmt_ps->get_result();
                                            if($result_ps->num_rows > 0){
                                                while($row_ps = $result_ps->fetch_assoc()){
                                                    $cli_name = $row_ps['policestation_name'];
                                        ?>
                                        <option value='<?php echo safe_output($cli_name)?>' <?php if($row_details['fpolice_station'] === $cli_name){ echo 'selected'; }?>><?php echo safe_output($cli_name); ?></option>
                                        <?php
                                                }
                                            }
                                            $stmt_ps->close();
                                        ?>
                                    </select>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag">
                                        <font class="blue-parag">
                                            النيابة
                                        </font>
                                        <?php if($row_permcheck['selectors_rperm'] == 1){?>
                                        <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="MM_openBrWindow('selector/AddProsecution.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=400')" align="absmiddle" style="cursor:pointer"/>
                                        <?php }?>
                                    </p>
                                    <select name="fprosecution2" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php 
                                            $stmt_prosecution = $conn->prepare("SELECT * FROM prosecution");
                                            $stmt_prosecution->execute();
                                            $result_prosecution = $stmt_prosecution->get_result();
                                            if($result_prosecution->num_rows > 0){
                                                while($row_prosecution = $result_prosecution->fetch_assoc()){
                                                    $prosname = $row_prosecution['prosecution_name'];
                                        ?>
                                        <option value='<?php echo safe_output($prosname); ?>' <?php if($row_details['file_prosecution'] === $prosname){ echo 'selected'; }?>><?php echo safe_output($prosname)?></option>
                                        <?php
                                                }
                                            }
                                            $stmt_prosecution->close();
                                        ?>
                                    </select>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag">
                                        <font class="blue-parag">
                                            المحكمة
                                        </font>
                                        <?php if($row_permcheck['selectors_rperm'] == 1){?>
                                        <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="MM_openBrWindow('selector/courtAdd.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=400')" align="absmiddle" style="cursor:pointer"/>
                                        <?php }?>
                                    </p>
                                    <select name="fcourt_edit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php 
                                            $stmt_scourt = $conn->prepare("SELECT * FROM court");
                                            $stmt_scourt->execute();
                                            $result_scourt = $stmt_scourt->get_result();
                                            if($result_scourt->num_rows > 0){
                                                while($row_scourt = $result_scourt->fetch_assoc()){
                                                    $court_name = $row_scourt['court_name'];
                                        ?>
                                        <option value='<?php echo safe_output($court_name)?>' <?php if($row_details['file_court'] === $court_name){ echo 'selected'; }?>><?php echo safe_output($court_name)?></option>
                                        <?php
                                                }
                                            }
                                            $stmt_scourt->close();
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="advinputs4">
                                <h2 class="advinputs-h2 blue-parag" style="grid-column: span 4; border-radius: 5px 5px 0 0; background-color: #12538640">مسؤول الملف</h2>
                                <div class="input-container">
                                    <p class="input-parag">
                                        <font class="blue-parag">
                                            السكرتيرة
                                        </font>
                                        <?php if($row_permcheck['emp_perms_add'] == 1){?>
                                        <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="open('mbhEmps.php?newEmp=1','Pic','width=600 height=700 scrollbars=yes')" align="absmiddle" style="cursor:pointer">
                                        <?php }?>
                                    </p>
                                    <select name="fsc_edit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php
                                            $stmt_lr = $conn->prepare("SELECT * FROM user WHERE job_title='14'");
                                            $stmt_lr->execute();
                                            $result_lr = $stmt_lr->get_result();
                                            if($result_lr->num_rows > 0){
                                                while($row_lr = $result_lr->fetch_assoc()){
                                                    $lr_id = $row_lr['id'];
                                                    $lr_name = $row_lr['name'];
                                        ?>
                                        <option value='<?php echo safe_output($lr_id); ?>' <?php if($row_details['file_secritary'] == $lr_id){ echo 'selected'; }?>><?php echo safe_output($lr_name); ?></option>
                                        <?php
                                                }
                                            }
                                            $stmt_lr->close();
                                        ?>
                                    </select>
                                    <select name="fsc2_edit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php
                                            $stmt_lr = $conn->prepare("SELECT * FROM user WHERE job_title='14'");
                                            $stmt_lr->execute();
                                            $result_lr = $stmt_lr->get_result();
                                            if($result_lr->num_rows > 0){
                                                while($row_lr = $result_lr->fetch_assoc()){
                                                    $lr_id = $row_lr['id'];
                                                    $lr_name = $row_lr['name'];
                                        ?>
                                        <option value='<?php echo safe_output($lr_id); ?>' <?php if($row_details['file_secritary2'] == $lr_id){ echo 'selected'; }?>><?php echo safe_output($lr_name); ?></option>
                                        <?php
                                                }
                                            }
                                            $stmt_lr->close();
                                        ?>
                                    </select>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag">
                                        <font class="blue-parag">
                                            الباحث القانوني
                                        </font>
                                        <?php if($row_permcheck['emp_perms_add'] == 1){?>
                                        <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="open('mbhEmps.php?newEmp=1','Pic','width=600 height=700 scrollbars=yes')" align="absmiddle" style="cursor:pointer">
                                        <?php }?>
                                    </p>
                                    <select name="fresearcher_edit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php
                                            $stmt_lr = $conn->prepare("SELECT * FROM user WHERE job_title='11'");
                                            $stmt_lr->execute();
                                            $result_lr = $stmt_lr->get_result();
                                            if($result_lr->num_rows > 0){
                                                while($row_lr = $result_lr->fetch_assoc()){
                                                    $lr_id = $row_lr['id'];
                                                    $lr_name = $row_lr['name'];
                                        ?>
                                        <option value='<?php echo safe_output($lr_id); ?>' <?php if($row_details['flegal_researcher'] == $lr_id){ echo 'selected'; }?>><?php echo safe_output($lr_name); ?></option>
                                        <?php
                                                }
                                            }
                                            $stmt_lr->close();
                                        ?>
                                    </select>
                                    <select name="fresearcher2_edit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php
                                            $stmt_lr = $conn->prepare("SELECT * FROM user WHERE job_title='11'");
                                            $stmt_lr->execute();
                                            $result_lr = $stmt_lr->get_result();
                                            if($result_lr->num_rows > 0){
                                                while($row_lr = $result_lr->fetch_assoc()){
                                                    $lr_id = $row_lr['id'];
                                                    $lr_name = $row_lr['name'];
                                        ?>
                                        <option value='<?php echo safe_output($lr_id); ?>' <?php if($row_details['flegal_researcher2'] == $lr_id){ echo 'selected'; }?>><?php echo safe_output($lr_name); ?></option>
                                        <?php
                                                }
                                            }
                                            $stmt_lr->close();
                                        ?>
                                    </select>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag">
                                        <font class="blue-parag">
                                            المستشار القانوني
                                        </font>
                                        <?php if($row_permcheck['emp_perms_add'] == 1){?>
                                        <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="open('mbhEmps.php?newEmp=1','Pic','width=600 height=700 scrollbars=yes')" align="absmiddle" style="cursor:pointer">
                                        <?php }?>
                                    </p>
                                    <select name="fadvisor_edit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php
                                            $stmt_la = $conn->prepare("SELECT * FROM user WHERE job_title='10'");
                                            $stmt_la->execute();
                                            $result_la = $stmt_la->get_result();
                                            if($result_la->num_rows > 0){
                                                while($row_la = $result_la->fetch_assoc()){
                                                    $lr_id = $row_la['id'];
                                                    $la_name = $row_la['name'];
                                        ?>
                                        <option value='<?php echo safe_output($lr_id); ?>' <?php if($row_details['flegal_advisor'] == $lr_id){ echo 'selected'; }?>><?php echo safe_output($la_name); ?></option>
                                        <?php
                                                }
                                            }
                                            $stmt_la->close();
                                        ?>
                                    </select>
                                    <select name="fadvisor2_edit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php
                                            $stmt_la = $conn->prepare("SELECT * FROM user WHERE job_title='10'");
                                            $stmt_la->execute();
                                            $result_la = $stmt_la->get_result();
                                            if($result_la->num_rows > 0){
                                                while($row_la = $result_la->fetch_assoc()){
                                                    $lr_id = $row_la['id'];
                                                    $la_name = $row_la['name'];
                                        ?>
                                        <option value='<?php echo safe_output($lr_id); ?>' <?php if($row_details['flegal_advisor2'] == $lr_id){ echo 'selected'; }?>><?php echo safe_output($la_name); ?></option>
                                        <?php
                                                }
                                            }
                                            $stmt_la->close();
                                        ?>
                                    </select>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag">
                                        <font class="blue-parag">
                                            المحامي
                                        </font>
                                        <?php if($row_permcheck['emp_perms_add'] == 1){?>
                                        <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="open('mbhEmps.php?newEmp=1','Pic','width=600 height=700 scrollbars=yes')" align="absmiddle" style="cursor:pointer">
                                        <?php }?>
                                    </p>
                                    <select name="file_lawyer" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php
                                            $stmt_la = $conn->prepare("SELECT * FROM user WHERE job_title='13'");
                                            $stmt_la->execute();
                                            $result_la = $stmt_la->get_result();
                                            if($result_la->num_rows > 0){
                                                while($row_la = $result_la->fetch_assoc()){
                                                    $lr_id = $row_la['id'];
                                                    $la_name = $row_la['name'];
                                        ?>
                                        <option value='<?php echo safe_output($lr_id); ?>' <?php if($row_details['file_lawyer'] == $lr_id){ echo 'selected'; }?>><?php echo safe_output($la_name); ?></option>
                                        <?php
                                                }
                                            }
                                            $stmt_la->close();
                                        ?>
                                    </select>
                                    <select name="file_lawyer2" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php
                                            $stmt_la = $conn->prepare("SELECT * FROM user WHERE job_title='13'");
                                            $stmt_la->execute();
                                            $result_la = $stmt_la->get_result();
                                            if($result_la->num_rows > 0){
                                                while($row_la = $result_la->fetch_assoc()){
                                                    $lr_id = $row_la['id'];
                                                    $la_name = $row_la['name'];
                                        ?>
                                        <option value='<?php echo safe_output($lr_id); ?>' <?php if($row_details['file_lawyer2'] == $lr_id){ echo 'selected'; }?>><?php echo safe_output($la_name); ?></option>
                                        <?php
                                                }
                                            }
                                            $stmt_la->close();
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php if($row_permcheck['degree_rperm'] == 1){?>
                            <div class="advinputs" style="margin-top: 40px; background-color: #12538640; padding-bottom: 0; padding-top: 5px">
                                <h2 class="advinputs-h2 blue-parag" style="grid-column: span 2">
                                    درجات التقاضي 
                                    (<font color="#FF0000">
                                        <?php 
                                            $fid = safe_output($_GET['id']);
                                            
                                            $stmt_counts = $conn->prepare("SELECT COUNT(*) as counts FROM file_degrees WHERE fid=?");
                                            $stmt_counts->bind_param("i", $fid);
                                            $stmt_counts->execute();
                                            $result_counts = $stmt_counts->get_result();
                                            $row_counts = $result_counts->fetch_assoc();
                                            $stmt_counts->close();
                                            
                                            if(isset($row_counts['counts'])){ echo safe_output($row_counts['counts']); }
                                        ?>
                                    </font>)
                                </h2>
                                <div style="grid-column: span 2">
                                    <table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" class="table" style="height: fit-content; background-color: #999999;">
                                        <tr align="center" style="font-weight:bold;font-size:16px" class="header_table">
                                            <th align="center">الدرجة <font color="#ff0000">*</font>
                                                <?php if($row_permcheck['selectors_rperm'] == 1){?>
                                                <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="MM_openBrWindow('selector/AddDegree.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=400,height=400')" align="absmiddle"  style="cursor:pointer"/>
                                                <?php }?>
                                            </th>
                                            <th align="center">رقم القضية </th>
                                            <th align="center">السنة </th>
                                            <th align="center">صفة الموكل</th>
                                            <th align="center">صفة الخصم</th>
                                            <th align="center">ت.م.الإدخال</th>
                                            <th align="center" width="50px">الاجراءات</th>
                                        </tr>
                                        <?php if($row_permcheck['degree_aperm'] == 1){?>
                                        <tr align="center" style="font-weight: bold; font-size: 16px;" class="infotable-body">
                                            <td align="center" style="background-color: #e4e5e4">
                                                <select name="fdegree_edit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                    <option value=""></option>
                                                    <?php 
                                                        $stmt_deg = $conn->prepare("SELECT * FROM degree");
                                                        $stmt_deg->execute();
                                                        $result_deg = $stmt_deg->get_result();
                                                        if($result_deg->num_rows > 0){
                                                            while($row_deg = $result_deg->fetch_assoc()){
                                                                $degree_name = $row_deg['degree_name'];
                                                    ?>
                                                    <option value='<?php echo safe_output($degree_name);?>'><?php echo safe_output($degree_name);?></option>
                                                    <?php
                                                            }
                                                        }
                                                        $stmt_deg->close();
                                                    ?>
                                                </select>
                                            </td>
                                            <td align="center" style="background-color: #e4e5e4"><input class="form-input" style="width: 50px" name="fcaseno_edit" type="number"></td>
                                            <td align="center" style="background-color: #e4e5e4"><input class="form-input" name="fyear_edit" style="width: 50px" type="number"></td>
                                            <td align="center" style="background-color: #e4e5e4">
                                                <select name="fccharacteristic_edit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                    <option value=""></option>
                                                    <?php 
                                                        $stmt_scharachteristics11 = $conn->prepare("SELECT * FROM client_status");
                                                        $stmt_scharachteristics11->execute();
                                                        $result_scharachteristics11 = $stmt_scharachteristics11->get_result();
                                                        if($result_scharachteristics11->num_rows > 0){
                                                            while($row_scharachteristics11 = $result_scharachteristics11->fetch_assoc()){
                                                                $stname11 = $row_scharachteristics11['arname'];
                                                    ?>
                                                    <option value='<?php echo safe_output($stname11)?>'><?php echo safe_output($stname11);?></option>
                                                    <?php
                                                            }
                                                        }
                                                        $stmt_scharachteristics11->close();
                                                    ?>
                                                </select>
                                            </td>
                                            <td align="center" style="background-color: #e4e5e4">
                                                <select name="focharacteristic_edit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                    <option value=""></option>
                                                    <?php 
                                                        $stmt_scharachteristics12 = $conn->prepare("SELECT * FROM client_status");
                                                        $stmt_scharachteristics12->execute();
                                                        $result_scharachteristics12 = $stmt_scharachteristics12->get_result();
                                                        if($result_scharachteristics12->num_rows > 0){
                                                            while($row_scharachteristics12 = $result_scharachteristics12->fetch_assoc()){
                                                                $stname12 = $row_scharachteristics12['arname'];
                                                    ?>
                                                    <option value='<?php echo safe_output($stname12);?>'><?php echo safe_output($stname12)?></option>
                                                    <?php
                                                            }
                                                        }
                                                        $stmt_scharachteristics12->close();
                                                    ?>
                                                </select>
                                            </td>
                                            <td align="center" style="background-color: #e4e5e4"><?php echo safe_output(date("Y-m-d")); ?></td>
                                            <td align="center" style="background-color: #e4e5e4">
                                                <?php if($row_permcheck['admjobs_aperm'] == 1){?>
                                                <img src='img/add-button.png' style="cursor: pointer" title="اضافة مهمة" width="25px" height="25px" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>&taskadd=1';">
                                                <?php }?>
                                            </td>
                                        </tr>
                                        <?php
                                            }
                                            $fiddeg = safe_output($_GET['id']);
                                            $stmt_degs = $conn->prepare("SELECT * FROM file_degrees WHERE fid=? ORDER BY created_at DESC");
                                            $stmt_degs->bind_param("i", $fiddeg);
                                            $stmt_degs->execute();
                                            $result_degs = $stmt_degs->get_result();
                                            if($result_degs->num_rows > 0){
                                                $countdegs = 0;
                                                while($row_degs = $result_degs->fetch_assoc()){
                                                    $countdegs++;
                                        ?>
                                        <tr align="center" class="infotable-body <?php if($countdegs >= 5){ echo 'hidden-trs'; }?>">
                                            <td style="color:#00F;"><?php echo safe_output($row_degs['degree'] ?? ''); ?></td>
                                            <td style="color:#00F;"><?php echo safe_output($row_degs['case_num'] ?? ''); ?></td>
                                            <td style="color:#00F;"><?php echo safe_output($row_degs['file_year'] ?? ''); ?></td>
                                            <td style="color:#00F;"><?php echo safe_output($row_degs['client_characteristic'] ?? ''); ?></td>
                                            <td style="color:#00F;"><?php echo safe_output($row_degs['opponent_characteristic'] ?? ''); ?></td>
                                            <td style="color:#999;"><?php echo safe_output($row_degs['timestamp'] ?? ''); ?></td>
                                            <td>
                                                <?php if($row_permcheck['degree_dperm'] == 1){ ?>
                                                <img src="img/recycle-bin.png" onclick="location.href='editfile.php?diddel=<?php echo safe_output($row_degs['id']); ?>&fid=<?php echo safe_output($_GET['id']); ?>';" style="cursor:pointer;" width="20">
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        
                                        <?php
                                        $fdeg = $row_degs['id'];
                                        $stmtopp = $conn->prepare("SELECT * FROM oppcase WHERE file_degree_id=?");
                                        $stmtopp->bind_param("i", $fdeg);
                                        $stmtopp->execute();
                                        $resultopp = $stmtopp->get_result();
                                        if($resultopp->num_rows > 0){
                                            $count = 0;
                                            while($rowopp = $resultopp->fetch_assoc()){
                                                $countdegs++;
                                                $deg_name = '';
                                                if (!empty($rowopp['degree'])) {
                                                    $stmtdeg2 = $conn->prepare("SELECT degree_name FROM degree WHERE id=?");
                                                    $stmtdeg2->bind_param("i", $rowopp['degree']);
                                                    $stmtdeg2->execute();
                                                    $resultdeg2 = $stmtdeg2->get_result();
                                                    $rowdeg2 = $resultdeg2->fetch_assoc();
                                                    $stmtdeg2->close();
                                                    $deg_name = $rowdeg2['degree_name'] ?? '';
                                                }
                                        ?>
                                        <tr align="center" class="infotable-body <?php if($countdegs >= 5){ echo 'hidden-trs'; }?>">
                                            <td style="color:red;"><?php echo safe_output($deg_name); ?></td>
                                            <td style="color:red;"><?php echo safe_output($rowopp['case_no'] ?? ''); ?></td>
                                            <td style="color:red;"><?php echo safe_output($rowopp['year'] ?? ''); ?></td>
                                            <td style="color:red;"><?php echo safe_output($rowopp['client_characteristic'] ?? ''); ?></td>
                                            <td style="color:red;"><?php echo safe_output($rowopp['opponent_characteristic'] ?? ''); ?></td>
                                            <td style="color:#999;"><?php echo safe_output($rowopp['created_at'] ?? ''); ?></td>
                                            <td></td>
                                        </tr>
                                        <?php 
                                                        }
                                                    }
                                                    $stmtopp->close();
                                                }
                                            }
                                            $stmt_degs->close();
                                        ?>
                                    </table>
                                    <div style="padding: 10px; text-align: center; background-color: #fff;">
                                        <?php if ($countdegs >= 5){?>
                                        <button type="button" id="expandBtn" class="green-button" onclick="expandmore()">اظهار المزيد</button>
                                        <?php }?>
                                        <button type="button" id="collapseBtn" style="#fff; display: none;" class="green-button" onclick="collapseRows()">اخفاء</button>
                                    </div>
                                </div>
                            </div>
                            <?php } if($row_permcheck['judicialwarn_rperm'] == 1){?>
                            <div class="advinputs" style="background-color: #12538640; margin-top: 40px; padding-bottom: 0; padding-top: 5px;">
                                <h2 class="advinputs-h2 blue-parag" style="grid-column: span 2">
                                    الانذارات العدلية
                                    (<font color="#FF0000">
                                        <?php 
                                            $fid = safe_output($_GET['id']);
                                            
                                            $stmt_counts = $conn->prepare("SELECT COUNT(*) as counts FROM judicial_warnings WHERE fid=?");
                                            $stmt_counts->bind_param("i", $fid);
                                            $stmt_counts->execute();
                                            $result_counts = $stmt_counts->get_result();
                                            $row_counts = $result_counts->fetch_assoc();
                                            $stmt_counts->close();
                                            
                                            if(isset($row_counts['counts'])){ echo safe_output($row_counts['counts']); }
                                        ?>
                                    </font>)
                                </h2>
                                <?php
                                    if(isset($_GET['ejudid']) && $_GET['ejudid'] !== ''){
                                        $ejudid = safe_output($_GET['ejudid']);
                                        
                                        $stmtjudid = $conn->prepare("SELECT * FROM judicial_warnings WHERE id=?");
                                        $stmtjudid->bind_param("i", $ejudid);
                                        $stmtjudid->execute();
                                        $resultjudid = $stmtjudid->get_result();
                                        $rowjudid = $resultjudid->fetch_assoc();
                                        $stmtjudid->close();
                                    }
                                ?>   
                                <input type="hidden" value="<?php if(isset($rowjudid['id']) && $rowjudid['id'] !== ''){ echo safe_output($rowjudid['id']); }?>" name="ejudid">
                                <input type="hidden" value="<?php echo safe_output($_GET['id']); ?>" name="fid">
                                <table width="100%" class="info-table" align="center" cellspacing="1" style="grid-column: span 2; background-color: #999999;">
                                    <tr align="center" class="header_table">
                                        <td align="center" width="20px">ت</td>
                                        <td align="center">تاريخ التصديق</td>
                                        <td align="center">مدة الانذار <font color="#ff0000">*</font></td>
                                        <td align="center">تاريخ تمام الاعلان <font color="#ff0000">*</font></td>
                                        <td align="center">قيد الدعوى</td>
                                        <td align="center" width="50px">الاجراءات</td>
                                    </tr>
                                    
                                    <?php if($row_permcheck['judicialwarn_aperm'] == 1){?>
                                    <tr align="center" class="infotable-body">
                                        <td align="center" style="background-color: #e4e5e4"></td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <input type="date" class="form-input" style="width: 80%;" placeholder="YYYY/MM/DD" name="ratification_date" value="<?php if(isset($_GET['ejudid']) && $_GET['ejudid'] !== '' && isset($rowjudid['ratification_date']) && $rowjudid['ratification_date'] !== ''){ echo safe_output($rowjudid['ratification_date']); }?>">
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <input type="number" class="form-input" style="width: 60px" name="warning_duration" value="<?php if(isset($_GET['ejudid']) && $_GET['ejudid'] !== '' && isset($rowjudid['warning_duration']) && $rowjudid['warning_duration'] !== ''){ echo safe_output($rowjudid['warning_duration']); }?>">
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <input type="date" class="form-input" style="width: 80%;" name="start_date" value="<?php if(isset($_GET['ejudid']) && $_GET['ejudid'] !== '' && isset($rowjudid['given_date']) && $rowjudid['given_date'] !== ''){ echo safe_output($rowjudid['given_date']); }?>">
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4"></td>
                                        <td align="center" style="background-color: #e4e5e4" width="50px">
                                            <?php if($row_permcheck['admjobs_aperm']){?>
                                            <img src='img/add-button.png' style="cursor: pointer" title="اضافة مهمة" width="25px" height="25px" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>&taskadd=1';">
                                            <?php } if(isset($_GET['ejudid']) && $_GET['ejudid'] !== ''){ ?>
                                            <input type="submit" title="تعديل" value="تعديل الانذار" class="green-button" name="edit_judwar">
                                            <?php }?> 
                                        </td>
                                    </tr>
                                    
                                    <?php 
                                        }
                                        $fid = safe_output($_GET['id']);
                                        
                                        $stmtjw = $conn->prepare("SELECT * FROM judicial_warnings WHERE fid=? ORDER BY id DESC");
                                        $stmtjw->bind_param("i", $fid);
                                        $stmtjw->execute();
                                        $resuljw = $stmtjw->get_result();
                                        if($resuljw->num_rows > 0){
                                            $countjw = 0;
                                            while($rowjw = $resuljw->fetch_assoc()){
                                                $countjw++;
                                    ?>
                                    <tr valign="center" class="infotable-body <?php if($countjw >= 5){ echo 'hidden-trs2'; }?>" style="font-weight: normal;">
                                        <?php $fidjudid = safe_output($_GET['id']);?>
                                        <td><?php echo safe_output($countjw); ?></td>
                                        
                                        <td>
                                            <?php 
                                                if(isset($rowjw['ratification_date']) && $rowjw['ratification_date'] !== ''){ 
                                                    echo safe_output($rowjw['ratification_date']);
                                                }
                                            ?>
                                        </td>
                                        
                                        <td>
                                            <?php 
                                                $daysname = "ايام";
                                                $warning_duration = $rowjw['warning_duration'];
                                                
                                                if(intVal($warning_duration) == 0){
                                                    echo 'لا يوجد مدة (0)';
                                                } else if(intVal($warning_duration) == 1){
                                                    echo 'يوم واحد';
                                                } else if(intVal($warning_duration) == 2){
                                                    echo 'يومان';
                                                } else if(intVal($warning_duration) > 2 && intVal($warning_duration) < 11){
                                                    echo safe_output($warning_duration) . ' ايام';
                                                } else if(intVal($warning_duration) > 10){
                                                    echo safe_output($warning_duration) . ' يوم';
                                                }
                                            ?>
                                        </td>
                                        
                                        <td>
                                            <?php 
                                                if(isset($rowjw['given_date']) && $rowjw['given_date'] !== ''){ 
                                                    echo safe_output($rowjw['given_date']);
                                                }
                                            ?>
                                        </td>
                                        
                                        <td>
                                            <?php
                                                if(date("Y-m-d") >= $rowjw['duedate']){
                                                    echo '<font color="red" class="blink" style="font-weight: bold;">انتهت المدة</font>';
                                                }
                                            ?>
                                        </td>
                                        
                                        <td style="align-content: center;" class="no-print">
                                            <?php $fidsidd = safe_output($_GET['id']); if($row_permcheck['judicialwarn_eperm'] == 1){?>
                                            <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='FileEdit.php?id=<?php echo safe_output($fidsidd); ?>&ejudid=<?php echo safe_output($rowjw['id']); ?>';">
                                            <?php } if($row_permcheck['judicialwarn_dperm'] == 1){?>
                                            <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='editfile.php?delejudid=<?php echo safe_output($rowjw['id']); ?>&judfid=<?php echo safe_output($fidsidd); ?>';">
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php 
                                            }
                                        }
                                        $stmtjw->close();
                                    ?>
                                </table>
                                <div style="padding: 10px; text-align: center; background-color: #fff; grid-column: span 2;">
                                    <?php if ($countjw >= 5){?>
                                    <button type="button" id="expandBtn2" class="green-button" onclick="expandmore2()">اظهار المزيد</button>
                                    <?php }?>
                                    <button type="button" id="collapseBtn2" style="#fff; display: none;" class="green-button" onclick="collapseRows2()">اخفاء</button>
                                </div>
                            </div>
                            <?php } if($row_permcheck['petition_rperm'] == 1){?>
                            <div class="advinputs" style="background-color: #12538640; margin-top: 40px; padding-bottom: 0; padding-top: 5px">
                                <h2 class="advinputs-h2 blue-parag" style="grid-column: span 2">
                                    اوامر على عريضة
                                    (<font color="#FF0000">
                                        <?php 
                                            $fid = safe_output($_GET['id']);
                                            
                                            $stmt_counts = $conn->prepare("SELECT COUNT(*) as counts FROM petition WHERE fid=?");
                                            $stmt_counts->bind_param("i", $fid);
                                            $stmt_counts->execute();
                                            $result_counts = $stmt_counts->get_result();
                                            $row_counts = $result_counts->fetch_assoc();
                                            $stmt_counts->close();
                                            
                                            if(isset($row_counts['counts'])){ echo safe_output($row_counts['counts']); }
                                        ?>
                                    </font>)
                                </h2>
                                <div style="grid-column: span 2">
                                    <?php
                                        if(isset($_GET['epetid']) && $_GET['epetid'] !== ''){
                                            $epetid = safe_output($_GET['epetid']);
                                            
                                            $stmtpid = $conn->prepare("SELECT * FROM petition WHERE id=?");
                                            $stmtpid->bind_param("i", $epetid);
                                            $stmtpid->execute();
                                            $resultpid = $stmtpid->get_result();
                                            $rowpid = $resultpid->fetch_assoc();
                                            $stmtpid->close();
                                        }
                                    ?>
                                    <input type="hidden" value="<?php if(isset($rowpid['id']) && $rowpid['id'] !== ''){ echo safe_output($rowpid['id']); }?>" name="epetid">
                                </div>
                                <table width="100%" class="info-table" align="center" cellspacing="1" style="grid-column: span 2; background-color: #999999;">
                                    <tr align="center" class="header_table">
                                        <td align="center" width="20px">ت</td>
                                        <td align="center">تاريخ التقديم <font color="#ff0000">*</font></td>
                                        <td align="center">نوع الامر</td>
                                        <td align="center">قرار القاضي <font color="#ff0000">*</font></td>
                                        <td align="center">تاريخ انتهاء المدة</td>
                                        <td align="center">ت.م الادخال</td>
                                        <td align="center" width="50px">الاجراءات</td>
                                    </tr>
                                    
                                    <?php if($row_permcheck['petition_aperm'] == 1){?>
                                    <tr align="center" class="infotable-body">
                                        <td align="center" style="background-color: #e4e5e4"></td>
                                        <td align="center" style="background-color: #e4e5e4"><input type="hidden" name="petition_fid" value="<?php echo safe_output($_GET['id']); ?>"><input type="date" class="form-input" style="width: 80%;" name="petition_date" value="<?php if(isset($_GET['epetid']) && $_GET['epetid'] !== ''){ echo safe_output($rowpid['date']); }?>"></td>
                                        <td align="center" style="background-color: #e4e5e4"><input type="text" class="form-input" style="width: 80%;" name="petition_type" value="<?php if(isset($_GET['epetid']) && $_GET['epetid'] !== ''){ echo safe_output($rowpid['type']); }?>"></td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <input type="radio" name="petition_decision" value="1" style="padding: 10px 0; margin: 10px 0;" <?php if(isset($rowpid['decision']) && $rowpid['decision'] === 'موافقة'){ echo 'checked'; } if(!isset($rowpid['decision'])){echo 'checked';}?>> <font color="#000000">موافقة</font><br>
                                            <input type="radio" name="petition_decision" value="0" <?php if(isset($rowpid['decision']) && $rowpid['decision'] === 'رفض'){echo 'checked';}?>> <font color="#000000">رفض</font>
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4"></td>
                                        <td align="center" style="background-color: #e4e5e4"></td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <img src='img/add-button.png' style="cursor: pointer" title="اضافة مهمة" width="25px" height="25px" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>&taskadd=1';">
                                            <?php if(isset($_GET['epetid']) && $_GET['epetid'] !== ''){ ?>
                                                <input type="submit" title="تعديل" value="تعديل الامر" class="green-button" name="edit_petition">
                                            <?php }?>
                                        </td>
                                    </tr>
                                    
                                    <?php
                                        }
                                        $fid = safe_output($_GET['id']);
                                        
                                        $stmtpt = $conn->prepare("SELECT * FROM petition WHERE fid=? ORDER BY id DESC");
                                        $stmtpt->bind_param("i", $fid);
                                        $stmtpt->execute();
                                        $resultpt = $stmtpt->get_result();
                                        if($resultpt->num_rows > 0){
                                            $countpt = 0;
                                            while($rowpt = $resultpt->fetch_assoc()){
                                                $countpt++;
                                    ?>
                                    <tr valign="center" class="infotable-body <?php if($countpt >= 5){ echo 'hidden-trs3'; }?>" style="font-weight: normal;">
                                        <td><?php echo safe_output($countpt); ?></td>
                                        
                                        <td>
                                            <?php 
                                                if(isset($rowpt['date']) && $rowpt['date'] !== ''){ 
                                                    echo safe_output($rowpt['date']);
                                                }
                                            ?>
                                        </td>
                                        
                                        <td>
                                            <?php 
                                                if(isset($rowpt['type']) && $rowpt['type'] !== ''){ 
                                                    echo safe_output($rowpt['type']);
                                                }
                                            ?>
                                        </td>
                                        
                                        <td>
                                            <?php 
                                                if(isset($rowpt['decision']) && $rowpt['decision'] !== ''){ 
                                                    echo safe_output($rowpt['decision']);
                                                }
                                            ?>
                                        </td>
                                        
                                        <td>
                                            <?php
                                                if($rowpt['decision'] === 'موافقة'){
                                                    echo safe_output($rowpt['hearing_lastdate']);
                                                } else if($rowpt['decision'] === 'رفض'){
                                                    echo safe_output($rowpt['appeal_lastdate']);
                                                }
                                            ?>
                                        </td>
                                        
                                        <td style="color: #333; font-size: 12px;">
                                            <?php
                                                if(isset($rowpt['timestamp']) && $rowpt['timestamp'] !== ''){
                                                    $timestamp = $rowpt['timestamp'];
                                                    
                                                    list($empid, $date) = explode("<br>", $timestamp);
                                                    $stmtu = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                    $stmtu->bind_param("i", $empid);
                                                    $stmtu->execute();
                                                    $resultu = $stmtu->get_result();
                                                    $rowu = $resultu->fetch_assoc();
                                                    $stmtu->close();
                                                    
                                                    echo safe_output($rowu['name']).'<br>'.safe_output($date);
                                                }
                                            ?>
                                        </td>
                                        
                                        <td style="align-content: center;">
                                            <?php $fid = safe_output($_GET['id']); if($row_permcheck['petition_eperm'] == 1){?>
                                            <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='FileEdit.php?id=<?php echo safe_output($fid); ?>&epetid=<?php echo safe_output($rowpt['id']); ?>';">
                                            <?php } if($row_permcheck['petition_dperm'] == 1){?>
                                            <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='editfile.php?delpetid=<?php echo safe_output($rowpt['id']); ?>&petfid=<?php echo safe_output($fid); ?>';">
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php 
                                            }
                                        }
                                        $stmtpt->close();
                                    ?>
                                </table>
                                <div style="padding: 10px; text-align: center; background-color: #fff; grid-column: span 2;">
                                    <?php if ($countpt >= 5){?>
                                    <button type="button" id="expandBtn3" class="green-button" onclick="expandmore3()">اظهار المزيد</button>
                                    <?php }?>
                                    <button type="button" id="collapseBtn3" style="#fff; display: none;" class="green-button" onclick="collapseRows3()">اخفاء</button>
                                </div>
                            </div>
                            <?php } if($row_permcheck['session_rperm'] == 1){?>
                            <div class="advinputs" style="background-color: #12538640; margin-top: 40px; padding-bottom: 0; padding-top: 5px">
                                <h2 class="advinputs-h2 blue-parag" style="grid-column: span 2">
                                    الجلسات 
                                    (<font color="#FF0000">
                                        <?php 
                                            $fid = safe_output($_GET['id']);
                                            
                                            $stmt_counts = $conn->prepare("SELECT COUNT(*) as counts FROM session WHERE session_fid=? AND session_degree!='تنفيذ'");
                                            $stmt_counts->bind_param("i", $fid);
                                            $stmt_counts->execute();
                                            $result_counts = $stmt_counts->get_result();
                                            $row_counts = $result_counts->fetch_assoc();
                                            $stmt_counts->close();
                                            
                                            if(isset($row_counts['counts'])){ echo safe_output($row_counts['counts']); }
                                        ?>
                                    </font>)
                                </h2>
                                
                                <?php if($row_permcheck['session_aperm'] == 1){?>
                                <div style="grid-column: span 2">
                                    <input type="hidden" value="<?php echo safe_output($_GET['id']); ?>" name="session_fid">
                                    <?php
                                        if(isset($_GET['esid']) && $_GET['esid'] !== ''){
                                            $esid = safe_output($_GET['esid']);
                                            
                                            $stmtsid = $conn->prepare("SELECT * FROM session WHERE session_id=?");
                                            $stmtsid->bind_param("i", $esid);
                                            $stmtsid->execute();
                                            $resultsid = $stmtsid->get_result();
                                            $rowsid = $resultsid->fetch_assoc();
                                            $stmtsid->close();
                                        }
                                    ?>   
                                    <input type="hidden" value="<?php if(isset($rowsid['session_id']) && $rowsid['session_id'] !== ''){ echo safe_output($rowsid['session_id']); }?>" name="seid">
                                    <div class="advinputs5">
                                        <div class="input-container" style="align-content: center;">
                                            <input type="checkbox" name="expert_session" value="1" <?php if(isset($_GET['esid']) && $_GET['esid'] !== '' && isset($rowsid['expert_session']) && $rowsid['expert_session'] == '1'){ echo 'checked'; }?>> <b class="blue-parag">جلسة خبرة</b>
                                        </div>
                                        <div class="input-container inputs-rowgrid" style="padding:0 10px;">
                                            <font class="blue-parag" style="align-content: center;">اسم الخبير : </font>
                                            <input type="text" class="form-input" style="width: 70%; margin-right: 5px;" name="expert_name" value="<?php if(isset($_GET['esid']) && $_GET['esid'] !== '' && isset($rowsid['expert_name']) && $rowsid['expert_name'] !== ''){ echo safe_output($rowsid['expert_name']); }?>">
                                        </div>
                                        <div class="input-container inputs-rowgrid" style="padding:0 10px;">
                                            <font class="blue-parag" style="align-content: center;">هاتف الخبير : </font>
                                            <input type="text" class="form-input" style="width: 70%; margin-right: 5px;" name="expert_phone" value="<?php if(isset($_GET['esid']) && $_GET['esid'] !== '' && isset($rowsid['expert_phone']) && $rowsid['expert_phone'] !== ''){ echo safe_output($rowsid['expert_phone']); }?>">
                                        </div>
                                        <div class="input-container inputs-rowgrid" style="padding:0 10px;">
                                            <font class="blue-parag" style="align-content: center;">عنوان الخبير : </font>
                                            <input type="text" class="form-input" style="width: 70%; margin-right: 5px;" name="expert_addr" value="<?php if(isset($_GET['esid']) && $_GET['esid'] !== '' && isset($rowsid['expert_address']) && $rowsid['expert_address'] !== ''){ echo safe_output($rowsid['expert_address']); }?>">
                                        </div>
                                        <div class="input-container inputs-rowgrid" style="padding:0 10px;">
                                            <font class="blue-parag" style="align-content: center;">مبلغ امانة الخبرة : </font>
                                            <input type="number" class="form-input" style="width: 70%; margin-right: 5px;" name="expert_amount" value="<?php if(isset($_GET['esid']) && $_GET['esid'] !== '' && isset($rowsid['expert_amount']) && $rowsid['expert_amount'] !== ''){ echo safe_output($rowsid['expert_amount']); }?>">
                                        </div>
                                    </div>
                                </div>
                                <?php } if($row_permcheck['session_eperm'] == 1){?>
                                <div class="modal-overlay" <?php if(isset($_GET['sentcheck']) && $_GET['sentcheck'] == 1){?>style="display: block;"<?php }?>>
                                    <div class="modal-content" style="margin: auto; align-content: center">
                                        <div class="notes-displayer">
                                            <div class="addc-header">
                                                <h4 class="addc-header-parag" style="margin: auto">حالة الارفاق على نظام المحكمة</h4>
                                                <div class="close-button-container">
                                                    <p class="close-button" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>';">&times;</p>
                                                </div>
                                            </div>
                                            <div class="notes-body" style="padding: 10px; text-align: right;">
                                                <?php
                                                    if(isset($_GET['hsid']) && $_GET['hsid'] !== ''){
                                                        $hsid = safe_output($_GET['hsid']);
                                                        
                                                        $stmthsid = $conn->prepare("SELECT * FROM session WHERE session_id=?");
                                                        $stmthsid->bind_param("i", $hsid);
                                                        $stmthsid->execute();
                                                        $resulthsid = $stmthsid->get_result();
                                                        $rowhsid = $resulthsid->fetch_assoc();
                                                        $stmthsid->close();
                                                    }
                                                ?>
                                                <div class="input-container" style="padding:0 10px">
                                                    <p class="blue-parag">حالة الارفاق</p>
                                                    <input type="hidden" name="session_sid_2" value="<?php if(isset($_GET['hsid'])){ echo safe_output($_GET['hsid']); }?>">
                                                    <input type="hidden" name="fid" value="<?php echo safe_output($_GET['id']); ?>">
                                                    <textarea class="form-input" style="width: 70%; margin-right: 5px" name="session_note"><?php if(isset($rowhsid['session_note']) && $rowhsid['session_note'] !== ''){ echo safe_output($rowhsid['session_note']); }?></textarea>
                                                </div>
                                                <button type="submit" name="submit_sentstatus" class="green-button">حفظ البيانات</button><br><br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-overlay" <?php if(isset($_GET['requests']) && $_GET['requests'] == 1){?>style="display: block;"<?php }?>>
                                    <div class="modal-content" style="margin: auto; align-content: center; width: 80%">
                                        <div class="notes-displayer">
                                            <div class="addc-header">
                                                <h4 class="addc-header-parag" style="margin: auto">طلبات الجلسة</h4>
                                                <div class="close-button-container">
                                                    <p class="close-button" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>';">&times;</p>
                                                </div>
                                            </div>
                                            <div class="notes-body" style="padding: 10px; text-align: right;">
                                                <?php
                                                    if(isset($_GET['rid']) && $_GET['rid'] !== ''){
                                                        $rid = safe_output($_GET['rid']);
                                                        
                                                        $stmtrid = $conn->prepare("SELECT * FROM session_requests WHERE id=?");
                                                        $stmtrid->bind_param("i", $rid);
                                                        $stmtrid->execute();
                                                        $resultrid = $stmtrid->get_result();
                                                        $rowrid = $resultrid->fetch_assoc();
                                                        $stmtrid->close();
                                                    }
                                                ?>
                                                <div class="input-container" style="padding:0 10px">
                                                    <p class="blue-parag">الطلب</p>
                                                    <?php if(isset($_GET['rid']) && $_GET['rid'] !== ''){?>
                                                    <input type="hidden" name="rid" value="<?php echo safe_output($_GET['rid']); ?>">
                                                    <?php }?>
                                                    <input type="hidden" name="fid" value="<?php if(isset($_GET['rid']) && $_GET['rid'] !== ''){ echo safe_output($rowrid['fid']); } else{ echo safe_output($_GET['id']); }?>">
                                                    <input type="hidden" name="sid" value="<?php if(isset($_GET['rid']) && $_GET['rid'] !== ''){ echo safe_output($rowrid['sid']); } else{ if(isset($_GET['hid'])){ echo safe_output($_GET['hid']); }}?>">
                                                    <textarea class="form-input" style="width: 70%; margin-right: 5px" name="request"><?php if(isset($_GET['rid']) && $_GET['rid'] !== ''){ echo safe_output($rowrid['request']); }?></textarea>
                                                </div>
                                                <button type="submit" name="<?php if(isset($_GET['rid']) && $_GET['rid'] !== ''){?>edit_request<?php } else{?>submit_request<?php }?>" class="green-button"><?php if(isset($_GET['rid']) && $_GET['rid'] !== ''){ echo 'تعديل'; } else{ echo 'حفظ'; }?> البيانات</button><br><br>
                                                <table width="100%" class="info-table" align="center" cellspacing="1" style="grid-column: span 2; background-color: #999999;">
                                                    <tr align="center" class="header_table">
                                                        <td align="center" width="20px">ت</td>
                                                        <td align="center">تاريخ الجلسة</td>
                                                        <td align="center">الطلب</td>
                                                        <td align="center" width="50px">الاجراءات</td>
                                                    </tr>
                                                    
                                                    <?php
                                                        if(isset($_GET['hid'])){
                                                            $hid = safe_output($_GET['hid']);
                                                        }
                                                        
                                                        $stmtreq = $conn->prepare("SELECT * FROM session_requests WHERE sid=? ORDER BY id DESC");
                                                        $stmtreq->bind_param("i", $hid);
                                                        $stmtreq->execute();
                                                        $resultreq = $stmtreq->get_result();
                                                        if($resultreq->num_rows > 0){
                                                            $count = 0;
                                                            while($rowreq = $resultreq->fetch_assoc()){
                                                                $count++;
                                                    ?>
                                                    <tr align="center" class="infotable-body">
                                                        <td align="center"><?php echo safe_output($count); ?></td>
                                                        <td align="center">
                                                            <?php
                                                                $stmtsre = $conn->prepare("SELECT * FROM session WHERE session_id=?");
                                                                $stmtsre->bind_param("i", $rowreq['sid']);
                                                                $stmtsre->execute();
                                                                $resultsre = $stmtsre->get_result();
                                                                $rowsre = $resultsre->fetch_assoc();
                                                                $stmtsre->close();
                                                                
                                                                echo safe_output($rowsre['session_date']);
                                                            ?>
                                                        </td>
                                                        <td align="center"><?php echo safe_output($rowreq['request']); ?></td>
                                                        <td align="center">
                                                            <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>&hid=<?php echo safe_output($rowreq['sid']); ?>&rid=<?php echo safe_output($rowreq['id']); ?>&requests=1';">
                                                            <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='editfile.php?reqdlid=<?php echo safe_output($rowreq['id']); ?>&reqfidid=<?php echo safe_output($fidsidd); ?>&reqhid=<?php echo safe_output($rowreq['sid'])?>';">
                                                        </td>
                                                    </tr>
                                                    <?php 
                                                            }
                                                        }
                                                        $stmtreq->close();
                                                    ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } if($row_permcheck['admjobs_aperm'] == 1){?>
                                <div class="modal-overlay" <?php if(isset($_GET['taskadd']) && $_GET['taskadd'] == 1){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto">اضافة مهمة جديدة</h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>';" style="display: inline-block">&times;</p>
                                            </div>
                                        </div>
                                        <div class="addc-body">
                                            <div class="addc-body-form">
                                                <?php 
                                                    $emmp = $_SESSION['id'];
                                                ?>
                                                <input type="hidden" value="<?php echo safe_output($_GET['id']); ?>" name="job_fid3">
                                                <input type="hidden" name="responsible3" value="<?php echo safe_output($emmp); ?>">
                                                <div class="input-container">
                                                    <p class="input-parag"><font class="blue-parag">تاريخ التنفيذ</font></p>
                                                    <input type="date" class="form-input" style="width: 80%;" name="job_date3" value="<?php if(isset($formData['job_date3']) && $formData['job_date3'] !== ''){ echo safe_output($formData['job_date3']); }?>">
                                                </div>
                                                <div class="input-container">
                                                    <p class="input-parag">
                                                        <font class="blue-parag">
                                                            المهمة
                                                        </font>
                                                        <font color="#ff0000">*</font>
                                                        <img src="img/add-button.png" width="20px" heigh="20px" title="اضافة مهمة" style="cursor: pointer;" onclick="MM_openBrWindow('selector/Tasks.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=200')">
                                                    </p>
                                                    <select name="job_name3" class="table-header-selector" style="width: 80%; height: fit-content; margin: 10px 0;">
                                                        <option value=""></option>
                                                        <?php
                                                            if(isset($formData['job_name3']) && $formData['job_name3'] !== ''){ 
                                                                $selectedjn3 = $formData['job_name3'];
                                                            } else{
                                                                $selectedjn3 = '';
                                                            }
                                                        
                                                            $stmtTT = $conn->prepare("SELECT * FROM job_name");
                                                            $stmtTT->execute();
                                                            $resultTT = $stmtTT->get_result();
                                                            if($resultTT->num_rows > 0){
                                                                while($rowTT = $resultTT->fetch_assoc()){
                                                        ?>
                                                        <option value="<?php echo safe_output($rowTT['id']); ?>" <?php if($rowTT['id'] == $selectedjn3){ echo 'selected'; }?>><?php echo safe_output($rowTT['job_name']); ?></option>
                                                        <?php
                                                                }
                                                            }
                                                            $stmtTT->close();
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="input-container">
                                                    <p class="input-parag"><font class="blue-parag">التفاصيل</font></p>
                                                    <textarea class="form-input" rows="2" type="text" name="job_details3" style="margin: 0; width: 80%"><?php if(isset($formData['job_details3']) && $formData['job_details3'] !== ''){ echo safe_output($formData['job_details3']); }?></textarea>
                                                </div>
                                                <div class="input-container">
                                                    <p class="input-parag"><font class="blue-parag">اهمية المهمة</font></p>
                                                    <?php 
                                                        if(isset($formData['job_priority3']) && $formData['job_priority3'] !== ''){ 
                                                            $job_priority3 = $formData['job_priority3'];
                                                        } else{
                                                            $job_priority3 = '';
                                                        }
                                                    ?>
                                                    <input type="radio" name="job_priority3" value="0" style="padding: 10px 0; margin: 10px 0;" <?php if(isset($job_priority3) && $job_priority3 !== ''){ if($job_priority3 == 0){ echo 'checked'; }} else{ echo 'checked'; }?>> <font color="#000000">عادي</font><br>
                                                    <input type="radio" name="job_priority3" value="1" <?php if($job_priority3 == 1){ echo 'checked'; }?>> <font color="#000000">عاجل</font>
                                                </div>
                                                <div class="input-container">
                                                    <p class="input-parag"><font class="blue-parag">الموظف المكلف</font><font color="#ff0000">*</font></p>
                                                    <select name="employee_name3" class="table-header-selector" style="width: 80%; height: fit-content; margin: 10px 0;">
                                                        <option value=""></option>
                                                        <?php
                                                            if(isset($formData['employee_name3']) && $formData['employee_name3'] !== ''){ 
                                                                $selectedemp3 = $formData['employee_name3'];
                                                            } else{
                                                                $selectedemp3 = '';
                                                            }
                                                            $stmtNM = $conn->prepare("SELECT * FROM user");
                                                            $stmtNM->execute();
                                                            $resultNM = $stmtNM->get_result();
                                                            if($resultNM->num_rows > 0){
                                                                while($rowNM = $resultNM->fetch_assoc()){
                                                        ?>
                                                        <option value="<?php echo safe_output($rowNM['id']); ?>" <?php if($rowNM['id'] == $selectedemp3){ echo 'selected'; }?>><?php echo safe_output($rowNM['name']); ?></option>
                                                        <?php
                                                                }
                                                            }
                                                            $stmtNM->close();
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="addc-footer">
                                            <button style="cursor: pointer" type="submit" name="addtask-cut" class="form-btn submit-btn">حفظ البيانات</button>
                                            <button type="button" class="form-btn cancel-btn" onclick="FileEdit.php?id=<?php $id = isset($_GET['id']) ? (int)$_GET['id'] : 0; echo safe_output($id)?>">الغاء</button>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                                <table width="100%" class="info-table" align="center" cellspacing="1" style="grid-column: span 2; background-color: #999999;">
                                    <tr align="center" class="header_table">
                                        <td align="center" width="20px">ت</td>
                                        <td align="center">تاريخ الجلسة <font color="#ff0000">*</font></td>
                                        <td align="center">درجة التقاضي <font color="#ff0000">*</font></td>
                                        <td align="center">القرار</td>
                                        <td align="center">الرابط و الطلبات</td>
                                        <td align="center">حالة الارفاق</td>
                                        <td align="center">الاجراءات</td>
                                        <td align="center" width="50px">اجراءات عامة</td>
                                    </tr>
                                    
                                    <?php if($row_permcheck['session_aperm'] == 1){?>
                                    <tr align="center" class="infotable-body">
                                        <td align="center" style="background-color: #e4e5e4"></td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <input type="date" class="form-input" style="width: 80%;" name="Hearing_dt" value="<?php if(isset($_GET['esid']) && $_GET['esid'] !== '' && isset($rowsid['session_date']) && $rowsid['session_date'] !== ''){ echo safe_output($rowsid['session_date']); } else{ echo date("Y-m-d"); }?>">
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <select name="session_degree" class="table-header-selector" style="width: 80%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php
                                                    $selectedDegree = '';
                                                    if(isset($_GET['esid']) && $_GET['esid'] !== '' && isset($rowsid['session_degree']) && $rowsid['session_degree'] !== ''){ 
                                                        $selectedDegree = $rowsid['session_degree']; 
                                                    }
                                                    $fiddif = safe_output($_GET['id']);
                                                    
                                                    $stmt_ade = $conn->prepare("SELECT * FROM file_degrees WHERE fid=?");
                                                    $stmt_ade->bind_param("i", $fiddif);
                                                    $stmt_ade->execute();
                                                    $result_ade = $stmt_ade->get_result();
                                                    if($result_ade->num_rows > 0){
                                                        while($row_ade = $result_ade->fetch_assoc()){
                                                ?>
                                                <option value="<?php echo safe_output($row_ade['file_year'].'/'.$row_ade['case_num'].'-'.$row_ade['degree']); ?>" <?php echo ($selectedDegree == $row_ade['degree']) ? 'selected' : ''; ?>><?php echo safe_output($row_ade['degree']); ?></option>
                                                <?php
                                                        }
                                                    }
                                                    $stmt_ade->close();
                                                ?>
                                            </select>
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <textarea class="form-input" rows="2" type="text" name="session_decission" style="margin: 0; width: 80%"><?php if(isset($_GET['esid']) && $_GET['esid'] !== ''){ echo safe_output($rowsid['session_decission']); }?></textarea>
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4;">
                                            <input type="text" class="form-input" style="width: 100px;" name="link" value="<?php if(isset($_GET['esid']) && $_GET['esid'] !== '' && isset($rowsid['link']) && $rowsid['link'] !== ''){ echo safe_output($rowsid['link']); }?>">
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4"></td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <?php
                                                $fid = safe_output($_GET['id']);
                                                
                                                $stmtss1 = $conn->prepare("SELECT * FROM session WHERE session_fid=? AND session_degree!='تنفيذ' ORDER BY session_date DESC, session_id DESC");
                                                $stmtss1->bind_param("i", $fiddif);
                                                $stmtss1->execute();
                                                $resultss1 = $stmtss1->get_result();
                                                if($resultss1->num_rows > 0){
                                                    $rowss1 = $resultss1->fetch_assoc();
                                                    if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){
                                            ?>
                                            <img src="img/BookedJud2.png" title="منطوق الحكم" width="20px" height="20px" style="cursor:pointer" onclick="MM_openBrWindow('Judgement.php?sid=<?php echo safe_output($rowss1['session_id']); ?>&fid=<?php echo safe_output($rowss1['session_fid']); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=200')"> 
                                            <img src="img/ExtendedJud.png" title="مد أجل الحكم" width="20px" height="20px" style="cursor:pointer" onclick="MM_openBrWindow('ExtendJud.php?fid=<?php echo safe_output($rowss1['session_fid']); ?>&sid=<?php echo safe_output($rowss1['session_id']); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=200')"> 
                                            <img src="img/referral.png" title="تمت الاحالة" width="20px" height="20px" style="cursor: pointer;" onclick="location.href='editfile.php?page=FileEdit.php&referral=1&referralfid=<?php echo safe_output($_GET['id']); ?>&referralsid=<?php echo safe_output($rowss1['session_id']); ?>';"> 
                                            <br>
                                            <img src="img/Handshake.png" title="تم الصلح" width="20px" height="20px" style="cursor: pointer;" onclick="location.href='editfile.php?page=FileEdit.php&reconciliation=1&reconciliationfid=<?php echo safe_output($_GET['id']); ?>&reconciliationsid=<?php echo safe_output($rowss1['session_id']); ?>';"></div>
                                            <img src="img/BookedJud.png" title="حجزت للحكم" width="20px" height="20px" style="cursor: pointer;" onclick="MM_openBrWindow('BookedJud.php?sid=<?php echo safe_output($rowss1['session_id']); ?>&fid=<?php echo safe_output($rowss1['session_fid']);?>&deg=<?php echo safe_output($rowss1['session_degree']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=300')"> 
                                            <img src="img/decission.png" title="قرار الجلسة" width="20px" height="20px" style="cursor: pointer;" onclick="MM_openBrWindow('decission.php?sid=<?php echo safe_output($rowss1['session_id']); ?>&fid=<?php echo safe_output($row_details['file_id']); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=300')"> 
                                            <?php 
                                                    }
                                                }
                                                $stmtss1->close();
                                            ?>
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4" width="50px">
                                            <img src='img/add-button.png' style="cursor: pointer" title="اضافة مهمة" width="25px" height="25px" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>&taskadd=1';">
                                            <?php if(isset($_GET['esid']) && $_GET['esid'] !== '' && $row_permcheck['session_eperm'] == 1){ ?>
                                                <input type="submit" title="تعديل" value="حفظ التعديلات" class="green-button" name="edit_session">
                                            <?php }?>
                                        </td>
                                    </tr>
                                    
                                    <?php 
                                        }
                                        $fid = safe_output($_GET['id']);
                                        
                                        $stmtss = $conn->prepare("SELECT * FROM session WHERE session_fid=? AND session_degree!='تنفيذ' ORDER BY session_date DESC, session_id DESC");
                                        $stmtss->bind_param("i", $fid);
                                        $stmtss->execute();
                                        $resultss = $stmtss->get_result();
                                        if($resultss->num_rows > 0){
                                            $countss = 0;
                                            while($rowss = $resultss->fetch_assoc()){
                                                $countss++;
                                    ?>
                                    <tr class="infotable-body <?php if($countss >= 11){ echo 'hidden-trs4'; }?>" style="font-weight: normal; background-color: <?php if(isset($rowss['extended']) && $rowss['extended'] == 1){ echo '#B5F3A3;';} else if(isset($rowss['jud_session']) && $rowss['jud_session'] == 1){ echo '#ffc6c7;';}?>">
                                        <?php $fidsidd = safe_output($_GET['id']);?>
                                        <td style="background-color: <?php if(isset($rowss['extended']) && $rowss['extended'] == 1){ echo '#B5F3A3;';} else if(isset($rowss['jud_session']) && $rowss['jud_session'] == 1){ echo '#ffc6c7;';}?>"><?php echo safe_output($countss); ?></td>
                                        
                                        <td style="background-color: <?php if(isset($rowss['extended']) && $rowss['extended'] == 1){ echo '#B5F3A3;';} else if(isset($rowss['jud_session']) && $rowss['jud_session'] == 1){ echo '#ffc6c7;';}?>">
                                            <?php 
                                                if(isset($rowss['session_date']) && $rowss['session_date'] !== ''){ 
                                                    echo safe_output($rowss['session_date']);
                                                }
                                                echo '<br>';
                                                if(isset($rowss['expert_session']) && $rowss['expert_session'] == 1){
                                                    echo '<img src="img/suitcase.png" width="25px" height="25px" title="جلسات الخبرة"><font color="#0000FF">جلسات الخبرة</font>';
                                                }
                                            ?>
                                            <font color="#4132d1">
                                                <?php 
                                                    if(isset($rowss['investigating']) && $rowss['investigating'] == 1){ 
                                                        echo 'قيد التحقيق'; 
                                                    } 
                                                    if(isset($rowss['extended']) && $rowss['extended'] == 1){ 
                                                        echo 'مد اجل'; 
                                                    } else if(isset($rowss['jud_session']) && $rowss['jud_session'] !== ''){ 
                                                        echo 'حجزت للحكم';
                                                    } else if(isset($rowss['referral_reconciliation']) && $rowss['referral_reconciliation'] != 0){ 
                                                        if($rowss['referral_reconciliation'] == 1){
                                                            echo 'تمت الاحالة';
                                                        } else if($rowss['referral_reconciliation'] == 2){
                                                            echo 'تم الصلح';
                                                        }
                                                    }
                                                ?>
                                            </font>
                                        </td>
                                        
                                        <td align="center" style="background-color: <?php if(isset($rowss['extended']) && $rowss['extended'] == 1){ echo '#B5F3A3;';} else if(isset($rowss['jud_session']) && $rowss['jud_session'] == 1){ echo '#ffc6c7;';}?>">
                                            <?php
                                                if(isset($rowss['session_degree']) && $rowss['session_degree'] !== ''){
                                                    echo safe_output($rowss['session_degree']);
                                                }
                                            ?>
                                        </td>
                                        
                                        <td align="center" style="color: #000; background-color: <?php if(isset($rowss['extended']) && $rowss['extended'] == 1){ echo '#B5F3A3;';} else if(isset($rowss['jud_session']) && $rowss['jud_session'] == 1){ echo '#ffc6c7;';}?>">
                                            <?php if(isset($rowss['expert_session']) && $rowss['expert_session'] == 1){?>
                                            <div align="justify" class="table" style="background-color:#FFF; padding:2px; color:#00F">
                                                اسم الخبير : <?php if(isset($rowss['expert_name']) && $rowss['expert_name'] !== ''){ echo safe_output($rowss['expert_name']);}?><br>
                                                هاتف الخبير :  <?php if(isset($rowss['expert_phone']) && $rowss['expert_phone'] !== ''){ echo safe_output($rowss['expert_phone']);}?><br>
                                                مبلغ امانه الخبرة : <?php if(isset($rowss['expert_amount']) && $rowss['expert_amount'] !== ''){ echo safe_output($rowss['expert_amount']);}?><br>
                                                العنوان : <?php if(isset($rowss['expert_address']) && $rowss['expert_address'] !== ''){ echo safe_output($rowss['expert_address']);}?>
                                            </div>
                                            <?php 
                                                } else{ 
                                                    if(isset($rowss['session_decission']) && $rowss['session_decission'] !== '' && isset($rowss['session_trial']) && $rowss['session_trial'] !== ''){ 
                                                        echo safe_output($rowss['session_decission']) . '<br><font color="red">' . $rowss['session_trial'] . '</font>'; 
                                                    } else if((isset($rowss['session_decission']) && $rowss['session_decission'] !== '') && (!isset($rowss['session_trial']) || $rowss['session_trial'] === '')){
                                                        echo safe_output($rowss['session_decission']); 
                                                    } else if((isset($rowss['session_trial']) && $rowss['session_trial'] !== '') && (!isset($rowss['session_decission']) || $rowss['session_decission'] === '')){
                                                        echo '<font color="red">'.$rowss['session_trial'].'</font>';
                                                    }
                                                }
                                            ?>
                                        </td>
                                        
                                        <td align="center" style="background-color: <?php if(isset($rowss['extended']) && $rowss['extended'] == 1){ echo '#B5F3A3;';} else if(isset($rowss['jud_session']) && $rowss['jud_session'] == 1){ echo '#ffc6c7;';}?>">
                                            <?php if(isset($rowss['link']) && $rowss['link'] !== ''){?>
                                            <img src="img/visitlink.png" style="cursor: pointer;" title="زيارة رابط الجلسة" height="20px" width="20px" onclick="open('<?php echo safe_output($rowss['link']); ?>','Pic','width=800 height=700 scrollbars=yes');">
                                            <?php }?>
                                            <img src="img/request.png" title="الطلبات" width="18px" height="18px" style="cursor: pointer;" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>&hid=<?php echo safe_output($rowss['session_id']); ?>&requests=1';">
                                        </td>
                                        
                                        <td align="center" style="background-color: <?php if(isset($rowss['extended']) && $rowss['extended'] == 1){ echo '#B5F3A3;';} else if(isset($rowss['jud_session']) && $rowss['jud_session'] == 1){ echo '#ffc6c7;';}?>">
                                            <?php if(isset($rowss['session_note']) && $rowss['session_note'] !== ''){?>
                                            <p class="blue-parag" style="cursor: pointer;" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>&hsid=<?php echo safe_output($rowss['session_id']); ?>&sentcheck=1';">تم الارفاق</p>
                                            <?php } else{?>
                                            <img src="img/add-button.png" width="25px" height="25px" style="cursor:pointer;" title="تعديل حالة الارفاق" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>&hsid=<?php echo safe_output($rowss['session_id']); ?>&sentcheck=1';">
                                            <?php }?>
                                        </td>
                                        
                                        <td align="center" style="background-color: <?php if(isset($rowss['extended']) && $rowss['extended'] == 1){ echo '#B5F3A3;';} else if(isset($rowss['jud_session']) && $rowss['jud_session'] == 1){ echo '#ffc6c7;';}?>"></td>
                                        
                                        <td style="align-content: center; background-color: <?php if(isset($rowss['extended']) && $rowss['extended'] == 1){ echo '#B5F3A3;';} else if(isset($rowss['jud_session']) && $rowss['jud_session'] == 1){ echo '#ffc6c7;';}?>">
                                            <?php if($row_permcheck['session_eperm'] == 1){?>
                                            <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='FileEdit.php?id=<?php echo safe_output($fidsidd); ?>&esid=<?php echo safe_output($rowss['session_id']); ?>';">
                                            <?php } if($row_permcheck['session_dperm'] == 1){?>
                                            <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='editfile.php?sid=<?php echo safe_output($rowss['session_id']); ?>&fid=<?php echo safe_output($fidsidd); ?>';">
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php 
                                            }
                                        }
                                        $stmtss->close();
                                    ?>
                                </table>
                                <div style="padding: 10px; text-align: center; background-color: #fff; grid-column: span 2;">
                                    <?php if ($countss >= 11){?>
                                    <button type="button" id="expandBtn4" class="green-button" onclick="expandmore4()">اظهار المزيد</button>
                                    <?php }?>
                                    <button type="button" id="collapseBtn4" style="#fff; display: none;" class="green-button" onclick="collapseRows4()">اخفاء</button>
                                </div>
                            </div>
                            <?php } if($row_permcheck['note_rperm'] == 1){?> 
                            <div class="advinputs" style="background-color: #12538640;  margin-top: 40px; padding-bottom: 0; padding-top: 5px;">
                                <h2 class="advinputs-h2 blue-parag" style="grid-column: span 2">
                                    <?php if($row_permcheck['note_aperm'] == 1){?><img src="img/note.png" width="30px" height="30px" onclick="open('AddNotes.php?fno=<?php echo safe_output($_GET['id']); ?>','Pic','width=800 height=700 scrollbars=yes')" style="cursor: pointer;"><?php }?> 
                                    المذكرات
                                    (<font color="#FF0000">
                                        <?php 
                                            $fidtask = safe_output($_GET['id']);
                                            
                                            $stmt_countjob = $conn->prepare("SELECT COUNT(*) AS count FROM case_document WHERE dfile_no=?");
                                            $stmt_countjob->bind_param("i", $fidtask);
                                            $stmt_countjob->execute();
                                            $result_countjob = $stmt_countjob->get_result();
                                            $row_countjob = $result_countjob->fetch_assoc();
                                            $stmt_countjob->close();
                                            
                                            $total_jobrows = $row_countjob['count'];
                                            echo safe_output($total_jobrows);
                                        ?>
                                    </font>)
                                </h2>
                                
                                <table width="100%" class="info-table" align="center" cellspacing="1" style="grid-column: span 2; background-color: #999999">
                                    <tr align="center" class="header_table">
                                        <td align="center" width="20px">ت</td>
                                        <td align="center">عنوان المذكرة</td>
                                        <td align="center">تاريخ المذكرة</td>
                                        <td align="center">التعديلات</td>
                                        <td align="center">الاعتماد</td>
                                        <td align="center" width="80px">الاجراءات</td>
                                    </tr>
                                    
                                    <?php
                                        $fidtsks = safe_output($_GET['id']);
                                        
                                        $stmttsk = $conn->prepare("SELECT * FROM case_document WHERE dfile_no=?");
                                        $stmttsk->bind_param("i", $fidtsks);
                                        $stmttsk->execute();
                                        $resulttsk = $stmttsk->get_result();
                                        if($resulttsk->num_rows > 0){
                                            $counttsk = 0;
                                            while($rowtsk = $resulttsk->fetch_assoc()){
                                                $counttsk++;
                                    ?>
                                    <tr class="infotable-body <?php if($counttsk >= 5){ echo 'hidden-trs5'; }?>">
                                        <?php $fidsidd = safe_output($_GET['id']);?>
                                        <td align="center"><?php echo safe_output($counttsk); ?></td>
                                        
                                        <td align="center"><?php if(isset($rowtsk['document_subject']) && $rowtsk['document_subject'] !== ''){echo safe_output($rowtsk['document_subject']);}?></td>
                                        
                                        <td align="center"><?php if(isset($rowtsk['document_date']) && $rowtsk['document_date'] !== ''){echo safe_output($rowtsk['document_date']);}?></td>
                                        
                                        <td align="center"><?php if(isset($rowtsk['document_notes']) && $rowtsk['document_notes'] !== ''){ echo safe_output($rowtsk['document_notes']); }?></td>
                                        
                                        <td align="center">
                                            <div style="display: grid; grid-template-rows: 1fr 1fr; gap: 5px;">
                                                <input type="button" value="الاعتماد المبدئي" style="cursor: pointer; background: none; outline: none; border: none; <?php if($rowtsk['status1'] == 1){ echo "background-color: #B5F3A3;"; } else if($rowtsk['status1'] == 2){ echo "background-color: #ffc6c7;"; } else{ echo "background-color: #fff;"; }?> color: #000000; border-radius: 4px; padding: 5px; text-align: center;" <?php if($row_permcheck['doc_faperm'] == 1){?> onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']);?>&approve_check=0&docid=<?php echo safe_output($rowtsk['did']);?>';" <?php }?>>
                                                <input type="button" value="الاعتماد النهائي" style="cursor: pointer; background: none; outline: none; border: none; <?php if($rowtsk['status2'] == 1){ echo "background-color: #B5F3A3;"; } else if($rowtsk['status2'] == 2){ echo "background-color: #ffc6c7;"; } else{ echo "background-color: #fff;"; }?> color: #000000; border-radius: 4px; padding: 5px; text-align: center;" <?php if($row_permcheck['doc_laperm'] == 1){?> onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']);?>&approve_check=1&docid=<?php echo safe_output($rowtsk['did']);?>';" <?php }?>>
                                            </div>
                                        </td>
                                        
                                        <td style="align-content: center;">
                                            <?php if($row_permcheck['admjobs_aperm'] == 1){?>
                                            <img src='img/add-button.png' style="cursor: pointer" title="اضافة مهمة" width="25px" height="25px" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>&taskadd=1';">
                                            <?php } if($row_permcheck['note_eperm'] == 1){?>
                                            <img src="img/actions.png" style="cursor: pointer;" title="تعديل البيانات" height="20px" width="20px" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>&notedetails=1&did=<?php echo safe_output($rowtsk['did']); ?>';">
                                            <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="open('AddNotes.php?fno=<?php echo safe_output($_GET['id']); ?>&id=<?php echo safe_output($rowtsk['did']); ?>&edit=1','Pic','width=800 height=700 scrollbars=yes')">
                                            <?php } if($row_permcheck['note_dperm'] == 1){?>
                                            <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='documentdel.php?did=<?php echo safe_output($rowtsk['did']); ?>&id=<?php echo safe_output($fidtsks); ?>';">
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php 
                                            }
                                        }
                                        $stmttsk->close();
                                    ?>
                                </table>
                                <div style="padding: 10px; text-align: center; background-color: #fff; grid-column: span 2;">
                                    <?php if ($counttsk >= 4){?>
                                    <button type="button" id="expandBtn5" class="green-button" onclick="expandmore5()">اظهار المزيد</button>
                                    <?php }?>
                                    <button type="button" id="collapseBtn5" style="#fff; display: none;" class="green-button" onclick="collapseRows5()">اخفاء</button>
                                </div>
                                <?php if(($row_permcheck['doc_faperm'] == 1 || $row_permcheck['doc_laperm'] == 1) && isset($_GET['approve_check'])){?>
                                <div id="addclient-btn" class="modal-overlay" <?php if(isset($_GET['approve_check']) && ($_GET['approve_check'] === '0' || $_GET['approve_check'] === '1')){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content">
                                        <?php
                                            $getid = safe_output($_GET['docid']);
                                            
                                            $stmtatt = $conn->prepare("SELECT * FROM case_document WHERE did=?");
                                            $stmtatt->bind_param("i", $getid);
                                            $stmtatt->execute();
                                            $resultatt = $stmtatt->get_result();
                                            $rowatt = $resultatt->fetch_assoc();
                                            $stmtatt->close();
                                        ?>
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto">اعتماد المذكرة : <?php echo safe_output($rowatt['document_subject']); ?></h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>';" style="display: inline-block">&times;</p>
                                            </div>
                                        </div>
                                        <div class="addc-body">
                                            <div class="addc-body-form">
                                                <input type="hidden" name="approve_check" value="<?php echo safe_output($_GET['approve_check']);?>">
                                                <input type="hidden" name="diddocstatus" value="<?php echo safe_output($_GET['docid']); ?>">
                                                <input type="hidden" name="fiddocstatus" value="<?php echo safe_output($_GET['id']); ?>">
                                                <div class="input-container">
                                                    <p class="input-parag"><font class="blue-parag">التعديلات</font></p>
                                                    <textarea class="form-input" name="document_notes" rows="10"><?php echo safe_output($rowatt['document_notes']); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="addc-footer">
                                            <input style="cursor: pointer" type="submit" name="doc_status" class="form-btn submit-btn" value="اعتماد">
                                            <input style="cursor: pointer" type="submit" name="doc_editsave" class="form-btn submit-btn" value="حفظ">
                                            <input style="cursor: pointer" type="submit" name="doc_refuse" class="form-btn submit-btn" value="ارجاع">
                                        </div>
                                    </div>
                                </div>
                                <?php } if($row_permcheck['note_eperm'] == 1){?>
                                <div id="addclient-btn" class="modal-overlay" <?php if(isset($_GET['notedetails']) && $_GET['notedetails'] == 1){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content">
                                        <?php
                                            $getid = safe_output($_GET['did']);
                                            
                                            $stmtatt = $conn->prepare("SELECT * FROM case_document WHERE did=?");
                                            $stmtatt->bind_param("i", $getid);
                                            $stmtatt->execute();
                                            $resultatt = $stmtatt->get_result();
                                            $rowatt = $resultatt->fetch_assoc();
                                            $stmtatt->close();
                                        ?>
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto">تعديل بيانات المذكرة : <?php echo safe_output($rowatt['document_subject']); ?></h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>';" style="display: inline-block">&times;</p>
                                            </div>
                                        </div>
                                        <div class="addc-body">
                                            <div class="addc-body-form">
                                                <input type="hidden" name="cdoid" value="<?php echo safe_output($_GET['did']); ?>">
                                                <div class="input-container">
                                                    <p class="input-parag"><font class="blue-parag">عنوان المذكرة</font></p>
                                                    <input class="form-input" type="text" name="document_subject" value="<?php echo safe_output($rowatt['document_subject']); ?>">
                                                </div>
                                                <div class="input-container">
                                                    <p class="input-parag"><font class="blue-parag">تاريخ المذكرة</font></p>
                                                    <input class="form-input" type="date" name="document_date" value="<?php echo safe_output($rowatt['document_date']); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="addc-footer">
                                            <button style="cursor: pointer" type="submit" name="edit_document" class="form-btn submit-btn">حفظ التعديلات</button>
                                            <button type="button" class="form-btn cancel-btn" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id'])?>';">الغاء</button>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                            <?php } if($row_permcheck['admjobs_rperm'] == 1){?>
                            <div class="advinputs" style="background-color: #12538640;  margin-top: 40px; padding-bottom: 0; padding-top: 5px">
                                <div style="grid-column: span 2">
                                    <h2 class="advinputs-h2 blue-parag">
                                        المهام 
                                        (<font color="#FF0000">
                                            <?php 
                                                $fidtask = safe_output($_GET['id']);
                                                
                                                $stmt_countjob = $conn->prepare("SELECT COUNT(*) AS count FROM tasks WHERE file_no=?");
                                                $stmt_countjob->bind_param("i", $fidtask);
                                                $stmt_countjob->execute();
                                                $result_countjob = $stmt_countjob->get_result();
                                                $row_countjob = $result_countjob->fetch_assoc();
                                                $stmt_countjob->close();
                                                
                                                $total_jobrows = $row_countjob['count'];
                                                echo safe_output($total_jobrows);
                                            ?>
                                        </font>)
                                    </h2>
                                    <?php
                                        if(isset($_GET['tid']) && $_GET['tid'] !== ''){
                                            $tid22 = safe_output($_GET['tid']);
                                            
                                            $stmttid = $conn->prepare("SELECT * FROM tasks WHERE id=?");
                                            $stmttid->bind_param("i", $tid22);
                                            $stmttid->execute();
                                            $resulttid = $stmttid->get_result();
                                            $rowtid = $resulttid->fetch_assoc();
                                            $stmttid->close();
                                        }
                                    ?>   
                                    <input type="hidden" value="<?php if(isset($rowsid['session_id']) && $rowsid['session_id'] !== ''){ echo safe_output($rowsid['session_id']); }?>" name="seid">
                                </div>
                                <?php $emmp = $_SESSION['id'];?>
                                <input type="hidden" value="<?php echo safe_output($_GET['id']); ?>" name="job_fid">
                                <input type="hidden" name="task_responsible" value="<?php echo safe_output($emmp); ?>">
                                <?php if(isset($_GET['tid']) && $_GET['tid'] !== ''){?>
                                <input type="hidden" value="<?php echo safe_output($_GET['tid']); ?>" name="tid">
                                <?php }?>
                                
                                <table width="100%" class="info-table" align="center" cellspacing="1" style="grid-column: span 2; background-color: #999999">
                                    <tr align="center" class="header_table">
                                        <td align="center" width="20px">ت</td>
                                        <td align="center">ت.التنفيذ</td>
                                        <td align="center">
                                            المهمة 
                                            <font color="#ff0000">*</font>
                                            <img src="img/add-button.png" width="20px" heigh="20px" title="اضافة مهمة" style="cursor: pointer;" onclick="MM_openBrWindow('selector/Tasks.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=200')">
                                        </td>
                                        <td align="center">التفاصيل</td>
                                        <td align="center">اهمية المهمة</td>
                                        <td align="center">درجة التقاضي</td>
                                        <td align="center">الموظف المكلف <font color="#ff0000">*</font></td>
                                        <td align="center">ت.م الادخال</td>
                                        <td align="center" width="80px">الاجراءات</td>
                                    </tr>
                                    
                                    <?php if($row_permcheck['admjobs_aperm'] == 1){?>
                                    <tr align="center" class="infotable-body">
                                        <td align="center" style="background-color: #e4e5e4"></td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <input type="date" class="form-input" style="width: 80%;" name="job_date" value="<?php if(isset($rowtid['duedate']) && $rowtid['duedate'] !== '' && isset($rowtid['duedate']) && $rowtid['duedate'] !== ''){ echo safe_output($rowtid['duedate']); }?>">
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <select name="job_name" class="table-header-selector" style="width: 80%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php
                                                    if(isset($_GET['tid']) && $_GET['tid'] !== '' && isset($rowtid['task_type']) && $rowtid['task_type'] !== ''){
                                                        $selectTT = $rowtid['task_type']; 
                                                    }
                                                    else{
                                                        $selectTT = '';
                                                    }
                                                    $tidTT = $rowtid['task_type'];
                                                    
                                                    $stmtTT = $conn->prepare("SELECT * FROM job_name");
                                                    $stmtTT->execute();
                                                    $resultTT = $stmtTT->get_result();
                                                    if($resultTT->num_rows > 0){
                                                        while($rowTT = $resultTT->fetch_assoc()){
                                                ?>
                                                <option value="<?php echo safe_output($rowTT['id']); ?>" <?php echo safe_output(($selectTT == $rowTT['id']) ? 'selected' : ''); ?>><?php echo safe_output($rowTT['job_name']); ?></option>
                                                <?php
                                                        }
                                                    }
                                                    $stmtTT->close();
                                                ?>
                                            </select>
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <textarea class="form-input" rows="2" type="text" name="job_details" style="margin: 0; width: 80%"><?php if(isset($rowtid['details']) && $rowtid['details'] !== ''){ echo safe_output($rowtid['details']); }?></textarea>
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <input type="radio" name="job_priority" value="0" style="padding: 10px 0; margin: 10px 0;" <?php if(isset($rowtid['priority']) && $rowtid['priority'] == 0){ echo 'checked'; } if(!isset($rowtid['priority'])){echo 'checked';}?>> <font color="#000000">عادي</font><br>
                                            <input type="radio" name="job_priority" value="1" <?php if(isset($rowtid['priority']) && $rowtid['priority'] == 1){echo 'checked';}?>> <font color="#000000">عاجل</font>
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <select name="job_degree" class="table-header-selector" style="width: 80%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php
                                                    $selectDt = '';
                                                    if(isset($_GET['tid']) && $_GET['tid'] !== '' && isset($rowtid['degree']) && $rowtid['degree'] !== ''){
                                                        $selectDt = $rowtid['degree']; 
                                                    }
                                                    
                                                    $tiddeg = $rowtid['degree'];
                                                    $fidfdeg = safe_output($_GET['id']);
                                                    
                                                    $stmtfdeg = $conn->prepare("SELECT * FROM file_degrees WHERE fid=?");
                                                    $stmtfdeg->bind_param("i", $fidfdeg);
                                                    $stmtfdeg->execute();
                                                    $resultfdeg = $stmtfdeg->get_result();
                                                    
                                                    if($resultfdeg->num_rows > 0){
                                                        while($rowfdeg=$resultfdeg->fetch_assoc()){
                                                ?>
                                                <option value="<?php echo safe_output($rowfdeg['id']); ?>" <?php echo safe_output(($selectDt == $rowfdeg['id']) ? 'selected' : ''); ?>><?php echo safe_output($rowfdeg['degree']); ?></option>
                                                <?php
                                                        }
                                                    }
                                                    $stmtfdeg->close();
                                                ?>
                                            </select>
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <select name="employee_name" class="table-header-selector" style="width: 80%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php
                                                    if(isset($_GET['tid']) && $_GET['tid'] !== '' && isset($rowtid['employee_id']) && $rowtid['employee_id'] !== ''){
                                                        $selectNM = $rowtid['employee_id']; 
                                                    }
                                                    else{
                                                        $selectNM = '';
                                                    }
                                                    
                                                    $tidNM = $rowtid['employee_id'];
                                                    
                                                    $stmtNM = $conn->prepare("SELECT * FROM user");
                                                    $stmtNM->execute();
                                                    $resultNM = $stmtNM->get_result();
                                                    if($resultNM->num_rows > 0){
                                                        while($rowNM = $resultNM->fetch_assoc()){
                                                ?>
                                                <option value="<?php echo safe_output($rowNM['id']); ?>" <?php echo safe_output(($selectNM == $rowNM['id']) ? 'selected' : ''); ?>><?php echo safe_output($rowNM['name']); ?></option>
                                                <?php
                                                        }
                                                    }
                                                    $stmtNM->close();
                                                ?>
                                            </select>
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4"></td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <?php if(isset($_GET['tid']) && $_GET['tid'] !== ''){?>
                                            <input type="submit" value="تعديل المهمة" class="green-button" name="edit_task">
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php 
                                        }
                                        $fidtsks = safe_output($_GET['id']);
                                        $stmttsk = $conn->prepare("SELECT * FROM tasks WHERE file_no=? ORDER BY duedate DESC");
                                        $stmttsk->bind_param("i", $fidtsks);
                                        $stmttsk->execute();
                                        $resulttsk = $stmttsk->get_result();
                                        if($resulttsk->num_rows > 0){
                                            $counttsk = 0;
                                            while($rowtsk = $resulttsk->fetch_assoc()){
                                                $counttsk++;
                                    ?>
                                    <tr class="infotable-body <?php if($counttsk >= 5){ echo 'hidden-trs6'; }?>">
                                        <?php $fidsidd = safe_output($_GET['id']);?>
                                        <td align="center" <?php if($rowtsk['task_status'] == 1){ echo "style = 'background-color: #faffa6;'"; } else if($rowtsk['task_status'] == 2){ echo "style = 'background-color: #B5F3A3;'"; }?>><?php echo safe_output($counttsk); ?></td>
                                        
                                        <td align="center"><?php if(isset($rowtsk['duedate']) && $rowtsk['duedate'] !== ''){echo safe_output($rowtsk['duedate']);}?></td>
                                        
                                        <td align="center">
                                            <?php
                                                if(isset($rowtsk['task_type']) && $rowtsk['task_type'] !== ''){
                                                    $tskkid = $rowtsk['task_type'];
                                                    
                                                    $stmttskkid = $conn->prepare("SELECT * FROM job_name WHERE id=?");
                                                    $stmttskkid->bind_param("i", $tskkid);
                                                    $stmttskkid->execute();
                                                    $resulttskkid = $stmttskkid->get_result();
                                                    if($resulttskkid->num_rows > 0){
                                                        $rowtskkid = $resulttskkid->fetch_assoc();
                                                        if(isset($rowtskkid['job_name'])){ echo safe_output($rowtskkid['job_name']); }
                                                    }
                                                    $stmttskkid->close();
                                                }
                                            ?>
                                        </td>
                                        
                                        <td align="center">
                                            <?php 
                                                if(isset($rowtsk['details']) && $rowtsk['details'] !== ''){
                                                    echo safe_output($rowtsk['details']).'<br>';
                                                }
                                                
                                                $stmttsknotes = $conn->prepare("SELECT * FROM task_notes WHERE taskid=?");
                                                $stmttsknotes->bind_param("i", $rowtsk['id']);
                                                $stmttsknotes->execute();
                                                $resulttsknotes = $stmttsknotes->get_result();
                                                if($resulttsknotes->num_rows > 0){
                                                    $counttsknotes = 0;
                                                    while($rowtsknotes = $resulttsknotes->fetch_assoc()){
                                                        $counttsknotes++;
                                            ?>
                                            <br>
                                            <ul>
                                                <li style='display: grid; grid-template-columns: 1fr 20px;'>
                                                    <p><?php echo '&#8226; '. safe_output($rowtsknotes['note']);?></p>
                                                    <?php if($rowtsk['employee_id'] == $_SESSION['id'] || $row_permcheck['admin'] == 1){?>
                                                    <img src="img/recycle-bin.png" width="20px" height="20px" style="cursor: pointer" onclick="location.href='editfile.php?tsknoteid=<?php echo safe_output($rowtsknotes['id']);?>&fidnotetsk=<?php echo safe_output($_GET['id']);?>';">
                                                    <?php }?>
                                                </li>
                                            </ul>
                                            <?php
                                                    }
                                                }
                                                $stmttsknotes->close();
                                            ?>
                                        </td>
                                        
                                        <td align="center">
                                            <?php 
                                                if(isset($rowtsk['priority']) && $rowtsk['priority'] !== ''){
                                                    if($rowtsk['priority'] == 0){
                                                        echo 'عادي';
                                                    } else if($rowtsk['priority'] == 1){
                                                        echo 'عاجل';
                                                    }
                                                }
                                            ?>
                                        </td>
                                        
                                        <td align="center">
                                            <?php 
                                                if(isset($rowtsk['degree']) && $rowtsk['degree'] !== ''){
                                                    $didd = $rowtsk['degree'];
                                                    
                                                    $stmtdidd = $conn->prepare("SELECT * FROM file_degrees WHERE id=?");
                                                    $stmtdidd->bind_param("i", $didd);
                                                    $stmtdidd->execute();
                                                    $resultdidd = $stmtdidd->get_result();
                                                    if($resultdidd->num_rows > 0){
                                                        $rowdidd = $resultdidd->fetch_assoc();
                                                        if(isset($rowdidd['degree'])){ echo safe_output($rowdidd['degree']); }
                                                    }
                                                    $stmtdidd->close();
                                                }
                                            ?>
                                        </td>
                                        
                                        <td align="center" style="<?php if($rowtsk['employee_id'] == $_SESSION['id']){echo 'color: red';}?>">
                                            <?php
                                                if(isset($rowtsk['employee_id']) && $rowtsk['employee_id'] !== ''){
                                                    $empppid = $rowtsk['employee_id'];
                                                    
                                                    $stmtemppidd = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                    $stmtemppidd->bind_param("i", $empppid);
                                                    $stmtemppidd->execute();
                                                    $resultemppidd = $stmtemppidd->get_result();
                                                    if($resultemppidd->num_rows > 0){
                                                        $rowemppidd = $resultemppidd->fetch_assoc();
                                                        if(isset($rowemppidd['name'])){ echo safe_output($rowemppidd['name']); }
                                                    }
                                                    $stmtemppidd->close();
                                                }
                                            ?>
                                        </td>
                                        
                                        <td align="center" style="color: #333; font-size: 12px;">
                                            <?php 
                                                if(isset($rowtsk['responsible']) && $rowtsk['responsible'] !== ''){
                                                    $etid = $rowtsk['responsible'];
                                                    
                                                    $stmtem = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                    $stmtem->bind_param("i", $etid);
                                                    $stmtem->execute();
                                                    $resultem = $stmtem->get_result();
                                                    $rowem = $resultem->fetch_assoc();
                                                    $stmtem->close();
                                                    echo safe_output($rowem['name']).'<br>'.safe_output($rowtsk['timestamp']);
                                                }
                                            ?>
                                        </td>
                                        
                                        <td style="align-content: center;">
                                            <?php if($_SESSION['id'] == $rowtsk['employee_id']){?>
                                            <img src="img/actions.png" style="cursor: pointer;" title="الاجراءات" height="20px" width="20px" onclick="location.href='FileEdit.php?taskid=<?php echo safe_output($rowtsk['id']); ?>&id=<?php echo safe_output($_GET['id']); ?>&actionsc=1';">
                                            <?php } if($row_permcheck['session_eperm'] == 1){?>
                                            <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>&tid=<?php echo safe_output($rowtsk['id']); ?>';">
                                            <?php } if($row_permcheck['session_dperm'] == 1){?>
                                            <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='editfile.php?tid=<?php echo safe_output($rowtsk['id']); ?>&fid=<?php echo safe_output($fidtsks); ?>';">
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php 
                                            }
                                        }
                                        $stmttsk->close();
                                    ?>
                                </table>
                                <div style="padding: 10px; text-align: center; background-color: #fff; grid-column: span 2;">
                                    <?php if ($counttsk >= 5){?>
                                    <button type="button" id="expandBtn6" class="green-button" onclick="expandmore6()">اظهار المزيد</button>
                                    <?php }?>
                                    <button type="button" id="collapseBtn6" style="#fff; display: none;" class="green-button" onclick="collapseRows6()">اخفاء</button>
                                </div>
                            </div>
                            <?php }?>
                            <div class="modal-overlay" <?php if(isset($_GET['actionsc']) && $_GET['actionsc'] == 1){ echo 'style="display: block;"'; }?>>
                                <div class="modal-content" style="margin: auto; align-content: center">
                                    <div class="notes-displayer">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto">اجراءات المهام</h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>';" style="display: inline-block">&times;</p>
                                            </div>
                                        </div>
                                        <div class="notes-body" style="padding: 10px; text-align: right;">
                                            <th width="95%" align="right">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td>
                                                            <font  style="font-size:16px; font-weight:bold">تحويل الى :</font> 
                                                            <input type="hidden" name="idddd" value="<?php echo safe_output($_GET['taskid']); ?>">
                                                            <select class="table-header-selector" name="re_name" style="width: 60%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                                <?php
                                                                    $oldemp = '';
                                                                    
                                                                    $oldtskold = safe_output($_GET['taskid']);
                                                                    $stmttskold = $conn->prepare("SELECT * FROM tasks WHERE id=?");
                                                                    $stmttskold->bind_param("i", $oldtskold);
                                                                    $stmttskold->execute();
                                                                    $resulttskold = $stmttskold->get_result();
                                                                    if($resulttskold->num_rows > 0){
                                                                        $rowtskold = $resulttskold->fetch_assoc();
                                                                        $oldemp = $rowtskold['employee_id'];
                                                                    }
                                                                    $stmttskold->close();
                                                                    
                                                                    $stmtemps = $conn->prepare("SELECT * FROM user");
                                                                    $stmtemps->execute();
                                                                    $resultemps = $stmtemps->get_result();
                                                                    if($resultemps->num_rows > 0){
                                                                        while($rowemps = $resultemps->fetch_assoc()){
                                                                ?>
                                                                <option value="<?php echo safe_output($rowemps['id']); ?>" <?php if($rowemps['id'] == $oldemp){ echo 'selected'; }?>><?php echo safe_output($rowemps['name']); ?></option>
                                                                <?php
                                                                        }
                                                                    }
                                                                    $stmtemps->close();
                                                                ?>
                                                            </select>
                                                            <br>
                                                            <p style="text-align: right"><font style="font-size:16px; font-weight:bold">الملاحظة :</font> </p>
                                                            <textarea name="t_note" rows="2" class="form-input"></textarea>
                                                            <input type="hidden" name="getfid" value="<?php echo safe_output($_GET['id']); ?>">
                                                            <br><br>
                                                            <input type="submit" value="جاري العمل" name="inpt" style="cursor:pointer;" class="form-btn submit-btn">
                                                            <input type="submit" value="تم الانتهاء" name="endt" style="cursor:pointer;" class="form-btn submit-btn">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </th>
                                            
                                            <br>
                                            <td width="5%" align="right">
                                                <button type="submit" name="submit_re_name" class="form-btn submit-btn">حفظ</button>
                                            </td>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if($row_permcheck['admjobs_rperm'] == 1){?>
                            <div class="advinputs" style="background-color: #12538640; margin-top: 40px; padding-bottom: 0; padding-top: 5px">
                                <div style="grid-column: span 2">
                                    <h2 class="advinputs-h2 blue-parag">
                                        التنفيذ 
                                        (<font color="#FF0000">
                                            <?php 
                                                $fidtask = safe_output($_GET['id']);
                                                
                                                $stmt_countjob = $conn->prepare("SELECT COUNT(*) AS count FROM execution WHERE file_no=?");
                                                $stmt_countjob->bind_param("i", $fidtask);
                                                $stmt_countjob->execute();
                                                $result_countjob = $stmt_countjob->get_result();
                                                $row_countjob = $result_countjob->fetch_assoc();
                                                $stmt_countjob->close();
                                                
                                                $total_jobrows = $row_countjob['count'];
                                                echo safe_output($total_jobrows);
                                            ?>
                                        </font>)
                                    </h2>
                                </div>
                                <?php
                                    if(isset($_GET['eid']) && $_GET['eid'] !== ''){
                                        $eid22 = safe_output($_GET['eid']);
                                        
                                        $stmttsk1 = $conn->prepare("SELECT * FROM execution WHERE id=?");
                                        $stmttsk1->bind_param("i", $eid22);
                                        $stmttsk1->execute();
                                        $resulttsk1 = $stmttsk1->get_result();
                                        
                                        if($resulttsk1->num_rows > 0){
                                            $rowtsk1 = $resulttsk1->fetch_assoc();
                                        }
                                        $stmttsk1->close();
                                    }
                                    
                                    $fidtsks = safe_output($_GET['id']);
                                    $stmttsks = $conn->prepare("SELECT * FROM execution WHERE file_no=?");
                                    $stmttsks->bind_param("i", $fidtsks);
                                    $stmttsks->execute();
                                    $resulttsks = $stmttsks->get_result();
                                ?>
                                
                                <table width="100%" class="info-table" align="center" cellspacing="1" style="grid-column: span 2; background-color: #999999">
                                    <tr align="center" class="header_table">
                                        <th align="center" width="50px">ت</th>
                                        <th align="center">ت.التنفيذ</th>
                                        <th align="center">
                                            المهمة 
                                            <font color="#ff0000">*</font>
                                            <img src="img/add-button.png" width="20px" heigh="20px" title="اضافة مهمة" style="cursor: pointer;" onclick="MM_openBrWindow('selector/Tasks.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=200')">
                                        </th>
                                        <th align="center">التفاصيل</th>
                                        <th align="center">الاهمية</th>
                                        <th align="center">القرار</th>
                                        <th align="center">الدرجة</th>
                                        <th align="center">ت.م الادخال</th>
                                        <th align="center" width="50px">الاجراءات</th>
                                    </tr>
                                    
                                    <?php if($row_permcheck['admjobs_aperm'] == 1){?>
                                    <tr align="center" class="infotable-body">
                                        <td align="center" style="background-color: #e4e5e4"></td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <input type="date" class="form-input" name="job_date1" style="margin: 0; width: 80%" value="<?php if(isset($_GET['eid']) && $_GET['eid'] !== ''){ echo safe_output($rowtsk1['duedate']); }?>">
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <select name="job_name1" class="table-header-selector" style="width: 80%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php
                                                    if(isset($_GET['eid']) && $_GET['eid'] !== '' && isset($rowtsk1['task_type']) && $rowtsk1['task_type'] !== ''){
                                                        $selectTT = $rowtsk1['task_type']; 
                                                    }
                                                    else{
                                                        $selectTT = '';
                                                    }
                                                    $tidTT = $rowtsk1['task_type'];
                                                    
                                                    $stmtTT = $conn->prepare("SELECT * FROM job_name");
                                                    $stmtTT->execute();
                                                    $resultTT = $stmtTT->get_result();
                                                    if($resultTT->num_rows > 0){
                                                        while($rowTT = $resultTT->fetch_assoc()){
                                                ?>
                                                <option value="<?php echo safe_output($rowTT['id']); ?>" <?php echo safe_output(($selectTT == $rowTT['id']) ? 'selected' : ''); ?>><?php echo safe_output($rowTT['job_name']); ?></option>
                                                <?php
                                                        }
                                                    }
                                                    $stmtTT->close();
                                                ?>
                                            </select>
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <textarea class="form-input" rows="2" type="text" name="job_details1" style="margin: 0; width: 80%"><?php if(isset($rowtsk1['details']) && $rowtsk1['details'] !== ''){ echo safe_output($rowtsk1['details']); }?></textarea>
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <input type="radio" name="job_priority1" value="0" style="padding: 10px 0; margin: 10px 0;" <?php if(isset($rowtsk1['priority']) && $rowtsk1['priority'] == 0){ echo 'checked'; } if(!isset($rowtsk1['priority'])){echo 'checked';}?>> <font color="#000000">عادي</font><br>
                                            <input type="radio" name="job_priority1" value="1" <?php if(isset($rowtsk1['priority']) && $rowtsk1['priority'] == 1){echo 'checked';}?>> <font color="#000000">عاجل</font>
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <textarea class="form-input" rows="2" type="text" name="decision" style="margin: 0; width: 80%"><?php if(isset($rowtsk1['decision']) && $rowtsk1['decision'] !== ''){ echo safe_output($rowtsk1['decision']); }?></textarea>
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4">
                                            <input type="radio" name="resapp" value="3" style="padding: 10px 0; margin: 10px 0;" <?php if(isset($rowtsk1['degree']) && $rowtsk1['degree'] == 3){ echo 'checked'; }?>> <font color="#000000">تظلم</font><br>
                                            <input type="radio" name="resapp" value="1" style="padding: 10px 0; margin: 10px 0;" <?php if(isset($rowtsk1['degree']) && $rowtsk1['degree'] == 1){ echo 'checked'; }?>> <font color="#000000">استتئناف</font><br>
                                            <input type="radio" name="resapp" value="2" style="padding: 10px 0; margin: 10px 0;" <?php if(isset($rowtsk1['degree']) && $rowtsk1['degree'] == 2){ echo 'checked'; }?>> <font color="#000000">طعن</font><br>
                                        </td>
                                        <td align="center" style="background-color: #e4e5e4">ت.م الادخال</td>
                                        <td width="50px" style="background-color: #e4e5e4">
                                            <img src='img/add-button.png' style="cursor: pointer" title="اضافة مهمة" width="25px" height="25px" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>&taskadd=1';">
                                            <?php if(isset($_GET['eid']) && $_GET['eid'] !== ''){?>
                                            <input type="hidden" value="<?php echo safe_output($_GET['eid']); ?>" name="eid">
                                            <?php } $fid = safe_output($_GET['id']);?>
                                            <input type="hidden" value="<?php echo safe_output($fid); ?>" name="job_fid1">
                                            <input type="hidden" name="responsible" value="<?php echo safe_output($_SESSION['id']); ?>">
                                            <?php if(isset($_GET['eid']) && $_GET['eid'] !== ''){?>
                                            <input type="hidden" value="<?php echo safe_output($_GET['eid']); ?>" name="eid">
                                            <?php }?>
                                            <input type="hidden" value="<?php echo safe_output($_GET['id']); ?>" name="job_fid">
                                            <div class="input-container">
                                                <?php if(isset($_GET['eid']) && $_GET['eid'] !== ''){?>
                                                <input type="submit" value="تعديل التنفيذ" class="green-button" name="edit_exec">
                                                <?php }?>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <?php 
                                        }
                                        $counttsks = 0;
                                        if($resulttsks->num_rows > 0){
                                            while($rowtsk = $resulttsks->fetch_assoc()){
                                                $counttsks++;
                                    ?>
                                    <tr class="infotable-body <?php if($counttsks >= 5){ echo 'hidden-trs7'; }?>">
                                        <?php $fidsidd = safe_output($_GET['id']);?>
                                        <td align="center"><?php echo safe_output($counttsks); ?></td>
                                        
                                        <td align="center"><?php if(isset($rowtsk['duedate']) && $rowtsk['duedate'] !== ''){echo safe_output($rowtsk['duedate']);}?></td>
                                        
                                        <td align="center">
                                            <?php
                                                if(isset($rowtsk['task_type']) && $rowtsk['task_type'] !== ''){
                                                    $tskkid = $rowtsk['task_type'];
                                                    
                                                    $stmttskkid = $conn->prepare("SELECT * FROM job_name WHERE id=?");
                                                    $stmttskkid->bind_param("i", $tskkid);
                                                    $stmttskkid->execute();
                                                    $resulttskkid = $stmttskkid->get_result();
                                                    
                                                    if($resulttskkid->num_rows > 0){
                                                        $rowtskkid = $resulttskkid->fetch_assoc();
                                                        if(isset($rowtskkid['job_name'])){ echo safe_output($rowtskkid['job_name']); }
                                                    }
                                                    $stmttskkid->close();
                                                }
                                            ?>
                                        </td>
                                        
                                        <td align="center"><?php if(isset($rowtsk['details']) && $rowtsk['details'] !== ''){echo safe_output($rowtsk['details']);}?></td>
                                        
                                        <td align="center">
                                            <?php 
                                                if(isset($rowtsk['priority']) && $rowtsk['priority'] !== ''){
                                                    if($rowtsk['priority'] == 0){
                                                        echo 'عادي';
                                                    } else if($rowtsk['priority'] == 1){
                                                        echo 'عاجل';
                                                    } else{
                                                        echo '';
                                                    }
                                                } else{
                                                    echo '';
                                                }
                                            ?>
                                        </td>
                                        
                                        <td align="center"><?php if(isset($rowtsk['decision']) && $rowtsk['decision'] !== ''){ echo safe_output($rowtsk['decision']); }?></td>
                                        
                                        <td align="center">
                                            <?php
                                                if(isset($rowtsk['degree']) && $rowtsk['degree'] !== ''){
                                                    if($rowtsk['degree'] == 1){
                                                        echo 'استئناف';
                                                    } else if($rowtsk['degree'] == 3){
                                                        echo 'تظلم';
                                                    } else if($rowtsk['degree'] == 2){
                                                        echo 'طعن';
                                                    }
                                                }
                                            ?>
                                        </td>
                                        
                                        <td align="center" style="color: #333; font-size: 12px;">
                                            <?php 
                                                $done_by = safe_output($rowtsk['done_by']);
                                                
                                                $stmtdone = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                $stmtdone->bind_param("i", $done_by);
                                                $stmtdone->execute();
                                                $resultdone = $stmtdone->get_result();
                                                $rowdone = $resultdone->fetch_assoc();
                                                $stmtdone->close();
                                                
                                                echo safe_output($rowdone['name']).'<br>';
                                                if(isset($rowtsk['timestamp']) && $rowtsk['timestamp'] !== ''){ 
                                                    echo safe_output($rowtsk['timestamp']); 
                                                } 
                                            ?>
                                        </td>
                                        
                                        <td style="align-content: center;">
                                            <?php if($row_permcheck['admjobs_eperm'] == 1){?>
                                            <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>&eid=<?php echo safe_output($rowtsk['id']); ?>';">
                                            <?php } if($row_permcheck['admjobs_dperm'] == 1){?>
                                            <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='editfile.php?eid=<?php echo safe_output($rowtsk['id']); ?>&fid=<?php echo safe_output($fidtsks); ?>';">
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php 
                                            }
                                        }
                                        $stmttsks->close();
                                    ?>
                                </table>
                                <div style="padding: 10px; text-align: center; background-color: #fff; grid-column: span 2;">
                                    <?php if ($counttsks >= 5){?>
                                    <button type="button" id="expandBtn7" class="green-button" onclick="expandmore7()">اظهار المزيد</button>
                                    <?php }?>
                                    <button type="button" id="collapseBtn7" style="#fff; display: none;" class="green-button" onclick="collapseRows7()">اخفاء</button>
                                </div>
                            </div>
                            <?php
                                }
                                $fid = safe_output($_GET['id']);
                                
                                $stmtfat = $conn->prepare("SELECT * FROM files_attachments WHERE fid=? ORDER BY id DESC");
                                $stmtfat->bind_param("i", $fid);
                                $stmtfat->execute();
                                $resultfat = $stmtfat->get_result();
                            ?>
                            <div class="advinputs" style="margin-top: 40px; padding-bottom: 0; padding-top: 5px; background-color: #12538640">
                                <h2 class="advinputs-h2 blue-parag" style="grid-column: span 2">
                                    المرفقات 
                                    (<font color="#FF0000">
                                        <?php 
                                            $fid = safe_output($_GET['id']);
                                            
                                            $stmt_counts = $conn->prepare("SELECT COUNT(*) as counts FROM files_attachments WHERE fid=?");
                                            $stmt_counts->bind_param("i", $fid);
                                            $stmt_counts->execute();
                                            $result_counts = $stmt_counts->get_result();
                                            $row_counts = $result_counts->fetch_assoc();
                                            $stmt_counts->close();
                                            
                                            if(isset($row_counts['counts'])){ echo safe_output($row_counts['counts']); }
                                        ?>
                                    </font>)
                                </h2>
                                <div style="grid-column: span 2; padding-bottom: 10px" class="advinputs3">
                                    <p></p>
                                    <div class="input-container">
                                        <div class="drop-zone" id="dropZone2" data-target="fileInput2" data-list="fileList2" data-multiple="true">
                                            <input type="file" id="fileInput2" name="attach_files_multi[]" hidden multiple>
                                            <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                            <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput2').click()">إرفاق مستند</span></p>
                                        </div>
                                        <div id="fileList2"></div>
                                    </div>
                                </div>
                                <table width="100%" class="info-table" cellspacing="1" align="center" style="grid-column: span 2; background-color: #999999">
                                    <tr align="center" class="header_table">
                                        <td align="center" width="20px">ت</td>
                                        <td align="center">المرفق</td>
                                        <td align="center">النوع / الحجم</td>
                                        <td align="center">ت.م الادخال</td>
                                        <td align="center" width="50px">الاجراءات</td>
                                    </tr>
                                    
                                    <?php 
                                        if($resultfat->num_rows > 0){
                                            $countfat = 0;
                                            while($rowfat = $resultfat->fetch_assoc()){
                                                $countfat++;
                                    ?>
                                    <tr align="center" class="infotable-body <?php if($countfat >= 5){ echo 'hidden-trs8'; }?>">
                                        <td><?php echo safe_output($countfat); ?></td>
                                        <td align="center">
                                            <a style="text-align: center;" href="<?php echo safe_output($rowfat['attachment']); ?>" onClick="window.open('<?php echo safe_output($rowfat['attachment']); ?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                <?php echo safe_output(basename($rowfat['attachment'])); ?>
                                            </a>
                                        </td>
                                        <td align="center">
                                            <div style="text-align: center;">
                                                <img src="img/<?php echo safe_output($rowfat['attachment_type']); ?>.png" alt="<?php echo safe_output($rowfat['attachment_type']); ?>" width="30px" height="30px">
                                                <?php echo safe_output($rowfat['attachment_size']); ?>
                                            </div>
                                        </td>
                                        <td align="center">
                                            <div style="text-align: center;">
                                                <?php echo safe_output($rowfat['timestamp']); ?>
                                                <br>
                                                <?php
                                                    $empid = $rowfat['done_by'];
                                                    
                                                    $stmtempfat = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                    $stmtempfat->bind_param("i", $empid);
                                                    $stmtempfat->execute();
                                                    $resultempfat = $stmtempfat->get_result();
                                                    if($resultempfat->num_rows > 0){
                                                        $rowempfat = $resultempfat->fetch_assoc();
                                                        if(isset($rowempfat['name'])){ echo safe_output($rowempfat['name']); }
                                                    }
                                                    $stmtempfat->close();
                                                ?>
                                            </div>
                                        </td>
                                        <td align="center">
                                            <?php if($row_permcheck['attachments_dperm'] == 1){?><img src="img/recycle-bin.png" width="20px" height="20px" style="cursor:pointer;" onclick="location.href='fattachdel.php?id=<?php echo safe_output($rowfat['id']); ?>&fid=<?php echo safe_output($_GET['id']); ?>&page=FileEdit.php';"><?php }?>
                                            <?php if($row_permcheck['workingtime_aperm'] == 1){?><img src="img/clock.png" width="20px" height="20px" title="مدة العمل" style="cursor:pointer;" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>&timer=1&done_action=file_attachment&timed_id=<?php echo safe_output($rowfat['id']); ?>&timed_name=<?php echo safe_output($rowfat['attachment']); ?>';"><?php }?>
                                        </td>
                                    </tr>
                                    <?php 
                                            }
                                        }
                                        $stmtfat->close();
                                    ?>
                                </table>
                                <div style="padding: 10px; text-align: center; background-color: #fff; grid-column: span 2;">
                                    <?php if ($countfat >= 5){?>
                                    <button type="button" id="expandBtn8" class="green-button" onclick="expandmore8()">اظهار المزيد</button>
                                    <?php }?>
                                    <button type="button" id="collapseBtn8" style="#fff; display: none;" class="green-button" onclick="collapseRows8()">اخفاء</button>
                                </div>
                                <?php if($row_permcheck['workingtime_aperm'] == 1 && isset($_GET['timer'])){?>
                                <div id="workingtime-1" class="modal-overlay" <?php if(isset($_GET['timer']) && $_GET['timer'] == 1){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto">مدة العمل</h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>';" style="display: inline-block">&times;</p>
                                            </div>
                                        </div>
                                        <div class="addc-body">
                                            <div class="addc-body-form">
                                                <input type="hidden" name="fid" value="<?php echo safe_output($_GET['id']); ?>">
                                                <input type="hidden" name="subid" value="<?php echo safe_output($_GET['timed_id']); ?>">
                                                <input type="hidden" name="action" value="<?php echo safe_output($_GET['done_action']); ?>">
                                                <input type="hidden" name="subinfo" value="<?php echo safe_output($_GET['timed_name']); ?>">
                                                <input type="hidden" name="selected_employee" value="<?php echo safe_output($_SESSION['id']); ?>">
                                                <div class="input-container">
                                                    <p class="input-parag"><font class="blue-parag">التاريخ</font></p>
                                                    <input class="form-input" type="date" name="done_date" value="<?php if(isset($formData['done_date']) && $formData['done_date'] !== ''){ echo safe_output($formData['done_date']); } else { echo date('Y-m-d'); }?>">
                                                </div>
                                                <div class="input-container">
                                                    <p class="blue-parag">الاجراء المنفذ <font color="#ff0000">*</font></p>
                                                    <?php
                                                        if(isset($_GET['done_action']) && $_GET['done_action'] !== ''){
                                                            if($_GET['done_action'] === 'file_attachment'){
                                                                $done_action = 'ارفاق المستند للمحكمة';
                                                            }
                                                        }
                                                    ?>
                                                    <input type="text" name="done_action" value="<?php if(isset($formData['done_action']) && $formData['done_action'] !== ''){ echo safe_output($formData['done_action']); } else{ echo safe_output($done_action); }?>" class="form-input">
                                                </div>
                                                <div class="input-container">
                                                    <p class="blue-parag">مدة العمل <font color="#ff0000">*</font></p>
                                                    <input type="number" name="working_durationMM" placeholder="MM" min="0" max="59" value="<?php if(isset($formData['working_durationMM']) && $formData['working_durationMM'] !== ''){ echo safe_output($formData['working_durationMM']); }?>" class="form-input" style="width: 50px;"> :
                                                    <input type="number" name="working_durationHH" placeholder="HH" value="<?php if(isset($formData['working_durationHH']) && $formData['working_durationHH'] !== ''){ echo safe_output($formData['working_durationHH']); }?>" class="form-input" style="width: 50px;">
                                                </div>
                                                <div class="input-container">
                                                    <p class="blue-parag">الملاحظات</p>
                                                    <textarea class="form-input" rows="2" type="text" name="action_notes" style="margin: 0; width: 80%"><?php if(isset($formData['action_notes']) && $formData['action_notes'] !== ''){ echo safe_output($formData['action_notes']); }?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="addc-footer">
                                            <button style="cursor: pointer" type="submit" name="add_worktime" class="form-btn submit-btn">حفظ البيانات</button>
                                            <p></p>
                                            <button type="button" class="form-btn cancel-btn" onclick="location.href='FileEdit.php?id=<?php echo safe_output($_GET['id']); ?>';">الغاء</button>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                            </div><br>
                            <div class="profedit-footer">
                                <div style="text-align: center; grid-column: span 2">
                                    <button type="submit" name="save_file" value=" اضغط هنا لحفظ البيانات" class="h-AdvancedSearch-Btn green-button" style="font-size: 18px">حفظ البيانات</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        
        <script src="js/newWindow.js"></script>
        <script src="js/toggleSection.js"></script>
        <script src="js/dropmultiplefiles.js"></script>
        <script src="js/popups.js"></script>
        <script src="js/sweetAlerts.js"></script>
        <script src="js/sweetAlerts2.js"></script>
        <script src="js/dropdown.js"></script>
        <script>
            const scrollContainer = document.getElementById("scrollContainer");
            
            window.addEventListener("beforeunload", function () {
                if (scrollContainer) {
                    localStorage.setItem("scrollPos", scrollContainer.scrollTop);
                }
            });
            
            window.addEventListener("load", function () {
                const scrollPos = localStorage.getItem("scrollPos");
                if (scrollPos && scrollContainer) {
                    scrollContainer.scrollTop = parseInt(scrollPos);
                }
            });
        </script>
        
        <script>
            function toggleMoreClients() {
                const div1 = document.getElementById("more-clients1");
                const div2 = document.getElementById("more-clients2");
                const div3 = document.getElementById("more-clients3");
                const div4 = document.getElementById("more-clients4");
                const div5 = document.getElementById("more-clients5");
                const div6 = document.getElementById("more-clients6");
                const div7 = document.getElementById("more-clients7");
                const div8 = document.getElementById("more-clients8");
                
                if (div1.style.display === "none" || div1.style.display === "") {
                    div1.style.display = "block";
                } else {
                    div1.style.display = "none";
                }
                
                if (div2.style.display === "none" || div2.style.display === "") {
                    div2.style.display = "block";
                } else {
                    div2.style.display = "none";
                }
                
                if (div3.style.display === "none" || div3.style.display === "") {
                    div3.style.display = "block";
                } else {
                    div3.style.display = "none";
                }
                
                if (div4.style.display === "none" || div4.style.display === "") {
                    div4.style.display = "block";
                } else {
                    div4.style.display = "none";
                }
                
                if (div5.style.display === "none" || div5.style.display === "") {
                    div5.style.display = "block";
                } else {
                    div5.style.display = "none";
                }
                
                if (div6.style.display === "none" || div6.style.display === "") {
                    div6.style.display = "block";
                } else {
                    div6.style.display = "none";
                }
                
                if (div7.style.display === "none" || div7.style.display === "") {
                    div7.style.display = "block";
                } else {
                    div7.style.display = "none";
                }
                
                if (div8.style.display === "none" || div8.style.display === "") {
                    div8.style.display = "block";
                } else {
                    div8.style.display = "none";
                }
            }
            function toggleMoreOpponents() {
                const div1 = document.getElementById("more-opponents1");
                const div2 = document.getElementById("more-opponents2");
                const div3 = document.getElementById("more-opponents3");
                const div4 = document.getElementById("more-opponents4");
                const div5 = document.getElementById("more-opponents5");
                const div6 = document.getElementById("more-opponents6");
                const div7 = document.getElementById("more-opponents7");
                const div8 = document.getElementById("more-opponents8");
                
                if (div1.style.display === "none" || div1.style.display === "") {
                    div1.style.display = "block";
                } else {
                    div1.style.display = "none";
                }
                
                if (div2.style.display === "none" || div2.style.display === "") {
                    div2.style.display = "block";
                } else {
                    div2.style.display = "none";
                }
                
                if (div3.style.display === "none" || div3.style.display === "") {
                    div3.style.display = "block";
                } else {
                    div3.style.display = "none";
                }
                
                if (div4.style.display === "none" || div4.style.display === "") {
                    div4.style.display = "block";
                } else {
                    div4.style.display = "none";
                }
                
                if (div5.style.display === "none" || div5.style.display === "") {
                    div5.style.display = "block";
                } else {
                    div5.style.display = "none";
                }
                
                if (div6.style.display === "none" || div6.style.display === "") {
                    div6.style.display = "block";
                } else {
                    div6.style.display = "none";
                }
                
                if (div7.style.display === "none" || div7.style.display === "") {
                    div7.style.display = "block";
                } else {
                    div7.style.display = "none";
                }
                
                if (div8.style.display === "none" || div8.style.display === "") {
                    div8.style.display = "block";
                } else {
                    div8.style.display = "none";
                }
            }
        </script>
        <script>
            function togglewt(id) {
                const workingtime = document.getElementById("workingtime-" + id);
                
                if (workingtime.style.display === "none" || workingtime.style.display === "") {
                    workingtime.style.display = "block";
                } else {
                    workingtime.style.display = "none";
                }
            }
            function expandmore() {
                const hiddenRows = document.querySelectorAll('.hidden-trs');
                hiddenRows.forEach(row => {
                    row.style.display = 'table-row';
                });
                document.getElementById('expandBtn').style.display = 'none';
                document.getElementById('collapseBtn').style.display = 'inline-block';
            }
            function collapseRows() {
                const hiddenRows = document.querySelectorAll('.hidden-trs');
                hiddenRows.forEach(row => {
                    row.style.display = 'none';
                });
                document.getElementById('expandBtn').style.display = 'inline-block';
                document.getElementById('collapseBtn').style.display = 'none';
            }
            
            function expandmore2() {
                const hiddenRows2 = document.querySelectorAll('.hidden-trs2');
                hiddenRows2.forEach(row2 => {
                    row2.style.display = 'table-row';
                });
                document.getElementById('expandBtn2').style.display = 'none';
                document.getElementById('collapseBtn2').style.display = 'inline-block';
            }
            function collapseRows2() {
                const hiddenRows2 = document.querySelectorAll('.hidden-trs2');
                hiddenRows2.forEach(row2 => {
                    row2.style.display = 'none';
                });
                document.getElementById('expandBtn2').style.display = 'inline-block';
                document.getElementById('collapseBtn2').style.display = 'none';
            }
            
            function expandmore3() {
                const hiddenRows3 = document.querySelectorAll('.hidden-trs3');
                hiddenRows3.forEach(row3 => {
                    row3.style.display = 'table-row';
                });
                document.getElementById('expandBtn3').style.display = 'none';
                document.getElementById('collapseBtn3').style.display = 'inline-block';
            }
            function collapseRows3() {
                const hiddenRows3 = document.querySelectorAll('.hidden-trs3');
                hiddenRows3.forEach(row3 => {
                    row3.style.display = 'none';
                });
                document.getElementById('expandBtn3').style.display = 'inline-block';
                document.getElementById('collapseBtn3').style.display = 'none';
            }
            
            function expandmore4() {
                const hiddenRows4 = document.querySelectorAll('.hidden-trs4');
                hiddenRows4.forEach(row4 => {
                    row4.style.display = 'table-row';
                });
                document.getElementById('expandBtn4').style.display = 'none';
                document.getElementById('collapseBtn4').style.display = 'inline-block';
            }
            function collapseRows4() {
                const hiddenRows4 = document.querySelectorAll('.hidden-trs4');
                hiddenRows4.forEach(row4 => {
                    row4.style.display = 'none';
                });
                document.getElementById('expandBtn4').style.display = 'inline-block';
                document.getElementById('collapseBtn4').style.display = 'none';
            }
            
            function expandmore5() {
                const hiddenRows5 = document.querySelectorAll('.hidden-trs5');
                hiddenRows5.forEach(row5 => {
                    row5.style.display = 'table-row';
                });
                document.getElementById('expandBtn5').style.display = 'none';
                document.getElementById('collapseBtn5').style.display = 'inline-block';
            }
            function collapseRows5() {
                const hiddenRows5 = document.querySelectorAll('.hidden-trs5');
                hiddenRows5.forEach(row5 => {
                    row5.style.display = 'none';
                });
                document.getElementById('expandBtn5').style.display = 'inline-block';
                document.getElementById('collapseBtn5').style.display = 'none';
            }
            
            function expandmore6() {
                const hiddenRows6 = document.querySelectorAll('.hidden-trs6');
                hiddenRows6.forEach(row6 => {
                    row6.style.display = 'table-row';
                });
                document.getElementById('expandBtn6').style.display = 'none';
                document.getElementById('collapseBtn6').style.display = 'inline-block';
            }
            function collapseRows6() {
                const hiddenRows6 = document.querySelectorAll('.hidden-trs6');
                hiddenRows6.forEach(row6 => {
                    row6.style.display = 'none';
                });
                document.getElementById('expandBtn6').style.display = 'inline-block';
                document.getElementById('collapseBtn6').style.display = 'none';
            }
            
            function expandmore7() {
                const hiddenRows7 = document.querySelectorAll('.hidden-trs7');
                hiddenRows7.forEach(row7 => {
                    row7.style.display = 'table-row';
                });
                document.getElementById('expandBtn7').style.display = 'none';
                document.getElementById('collapseBtn7').style.display = 'inline-block';
            }
            function collapseRows7() {
                const hiddenRows7 = document.querySelectorAll('.hidden-trs7');
                hiddenRows7.forEach(row7 => {
                    row7.style.display = 'none';
                });
                document.getElementById('expandBtn7').style.display = 'inline-block';
                document.getElementById('collapseBtn7').style.display = 'none';
            }
            
            function expandmore8() {
                const hiddenRows8 = document.querySelectorAll('.hidden-trs8');
                hiddenRows8.forEach(row8 => {
                    row8.style.display = 'table-row';
                });
                document.getElementById('expandBtn8').style.display = 'none';
                document.getElementById('collapseBtn8').style.display = 'inline-block';
            }
            function collapseRows8() {
                const hiddenRows8 = document.querySelectorAll('.hidden-trs8');
                hiddenRows8.forEach(row8 => {
                    row8.style.display = 'none';
                });
                document.getElementById('expandBtn8').style.display = 'inline-block';
                document.getElementById('collapseBtn8').style.display = 'none';
            }
        </script>
    </body>
</html>