<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    include_once 'permissions_check.php';
    include_once 'AES256.php';

    /** @var mysqli $conn */
    /** @var array $row_permcheck */
    /** @var array $row_details */
    $row_details = $row_details ?? [];
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
        <link rel="shortcut icon" href="files/images/instance/favicon.ico?v=35265" type="image/icon">
        <link href="css/styles.css" rel="stylesheet">
        <link rel="SHORTCUT ICON" href="img/favicon.ico">
    </head>
    <body style="overflow: auto">
        <?php 
            $stmtce = $conn->prepare("SELECT * FROM file WHERE client_id='' AND file_type='' AND frelated_place='' AND file_class='' AND file_status='' AND file_client=''");
            $stmtce->execute();
            $resultce = $stmtce->get_result();
            
            if($resultce->num_rows == 0){
                $stmtcn = $conn->prepare("INSERT INTO file (client_id, file_type, frelated_place, file_class, file_status, file_client) VALUES ('', '', '', '', '', '')");
                $stmtcn->execute();
                $result_cn = $stmtcn->get_result();
                $stmtcn->close();
            }
            $stmtce->close();
        ?>
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['cfiles_aperm'] == 1){
                        if(isset($_GET['archive']) && $_GET['archive'] === '1'){
                            if($row_permcheck['cfiles_archive_perm'] != 1){
                                exit();
                            }
                        }
                ?>
                
                <div class="web-page">
                    <br><br>
                    <div class="advinputs-container" style="height: 80vh; overflow-y: auto">
                        <form action="fileadd.php" method="post" enctype="multipart/form-data">
                            <?php
                                $stmtid = $conn->prepare("SELECT * FROM file WHERE client_id='' AND file_type='' AND frelated_place='' AND file_class='' AND file_status='' AND file_client=''");
                                $stmtid->execute();
                                $resultid = $stmtid->get_result();
                                $rowid = $resultid->fetch_assoc();
                                $stmtid->close();
                                $id = $rowid['file_id'];
                            ?>
                            <input type="hidden" name="timermainid" value="<?php echo safe_output($id);?>">
                            <input type="hidden" name="timeraction" value="file_add">
                            <input type="hidden" name="timerdone_action" value="اضافة ملف جديد برقم <?php echo $id;?>">
                            <input type="hidden" name="timerdone_date" value="<?php echo date("Y-m-d");?>">
                            <input type="hidden" name="timer_timestamp" value="<?php echo time();?>">
                            <h2 class="advinputs-h2 blue-parag" style="border-radius: 5px 5px 0 0; background-color: #12538640">بيانات الملف</h2>
                            <?php
                                $userid = $_SESSION['id'];
                                
                                $stmt_res = $conn->prepare("SELECT * FROM user WHERE id=?");
                                $stmt_res->bind_param("i", $id);
                                $stmt_res->execute();
                                $result_res = $stmt_res->get_result();
                                $row_res = $result_res->fetch_assoc();
                                $stmt_res->close();
                                
                                $resp_name = $row_res['name'];
                            ?>
                            <input type="hidden" name="resp_name" value="<?php echo safe_output($resp_name);?>">
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">نوع الملف <font color="#FF0000">*</font></font> <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="MM_openBrWindow('selector/fileType.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=400')" align="absmiddle" style="cursor:pointer"/></p>
                                    <select class="table-header-selector" name="type" style="width: calc(100% - 50px); height: fit-content; margin: 10px 0;">
                                        <option value="مدني -عمالى" style="background-color:#FFFF99"  >مدني -عمالى</option>
                                        <option value="أحوال شخصية" style="background-color:#FAB8BA"  >أحوال شخصية</option>
                                        <option value="جزاء" style="background-color:#99B5E8"  >جزاء</option>
                                        <option value="المنازعات الإيجارية" style="background-color:#e1e1e1"  >المنازعات الإيجارية</option>
                                        <?php
                                            $stmtft = $conn->prepare("SELECT * FROM file_types");
                                            $stmtft->execute();
                                            $resultft = $stmtft->get_result();
                                            while($rowft = $resultft->fetch_assoc()){
                                        ?>
                                        <option value="<?php echo safe_output($rowft['file_type']);?>" style="background-color: <?php echo safe_output($rowft['type_color']);?>;"><?php echo safe_output($rowft['file_type']);?></option>
                                        <?php
                                            }
                                            $stmtft->close();
                                        ?>
                                    </select>
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
                                    <select name="class" class="table-header-selector" style="width: 80%; height: fit-content; margin: 10px 0;" required>
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
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الفرع المختص</font><font color="#ff0000">*</font>
                                        <?php if($row_permcheck['selectors_rperm'] == 1){?>
                                        <img src="img/add-button.png" width="20px" heigh="20px" title="اضافة فرع" style="cursor: pointer;" onclick="MM_openBrWindow('selector/Branchs.php?fid=<?php echo safe_output($_GET['id']); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=200')">
                                        <?php }?></p>
                                    <select name="place" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                            </div>
                            <div class="advinputs4">
                                <div class="input-container">
                                    <p class="input-parag">
                                        <font class="blue-parag">
                                            <input type="checkbox" name="important" value="1" <?php if(isset($row_details['important']) && $row_details['important'] == 1){ echo 'checked'; }?>>
                                            تسجيل كدعوى مهمة
                                        </font>
                                        <br><br>
                                        <font class="blue-parag">
                                            رقم الملف : <?php echo safe_output($id);?>
                                        </font> 
                                    </p>
                                    <input class="form-input" type="hidden" name="id" value="<?php echo safe_output($id);?>">
                                </div>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الموضوع</font></p>
                                    <textarea class="form-input" rows="2" type="text" name="subject"></textarea>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الملاحظات</font></p>
                                    <textarea class="form-input" rows="2" type="text" name="note"></textarea>
                                </div>
                                <div style="text-align: left; align-content: center">
                                    <img src="img/pending.png" width="25px" height="25px" style="padding-left: 10px" title="في الانتظار"><input type="radio" name="file_stat" value="في الانتظار" style="margin: 10px 0;" <?php if(isset($row_details['file_status']) && $row_details['file_status'] === 'في الانتظار'){ echo 'checked'; }?>><br>
                                    <img src="img/Circulating.png" width="25px" height="25px" style="padding-left: 10px" title="متداول"><input type="radio" name="file_stat" value="متداول" style="margin: 10px 0;" checked><br>
                                    <img src="img/archive.png" width="25px" height="25px" style="padding-left: 10px" title="مؤرشف"><input type="radio" name="file_stat" value="مؤرشف" <?php if(isset($row_details['file_status']) && $row_details['file_status'] === 'مؤرشف'){ echo 'checked'; }?>>
                                </div>
                            </div><br>
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
                                        <select name="client" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                            <select name="client2" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                            <select name="client3" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                            <select name="client4" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                            <select name="client5" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                        <select name="cchar" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                            <select name="cchar2" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                            <select name="cchar3" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                            <select name="cchar4" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                            <select name="cchar5" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                        <select name="opponent" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                            <select name="opponent2" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                            <select name="opponent3" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                            <select name="opponent4" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                            <select name="opponent5" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                        <select name="ochar" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                            <select name="ochar2" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                            <select name="ochar3" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                            <select name="ochar4" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                            <select name="ochar5" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                    <select name="ctype" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                    <select name="pstation" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                    <select name="prosecution" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                    <select name="court" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                    <select name="fsc" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                    <select name="fsc2" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php
                                            $stmt_la = $conn->prepare("SELECT * FROM user WHERE job_title='14'");
                                            $stmt_la->execute();
                                            $result_la = $stmt_la->get_result();
                                            if($result_la->num_rows > 0){
                                                while($row_la = $result_la->fetch_assoc()){
                                                    $lr_id = $row_la['id'];
                                                    $la_name = $row_la['name'];
                                        ?>
                                        <option value='<?php echo safe_output($lr_id); ?>' <?php if($row_details['fsc2'] == $lr_id){ echo 'selected'; }?>><?php echo safe_output($la_name); ?></option>
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
                                            الباحث القانوني
                                        </font>
                                        <?php if($row_permcheck['emp_perms_add'] == 1){?>
                                        <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="open('mbhEmps.php?newEmp=1','Pic','width=600 height=700 scrollbars=yes')" align="absmiddle" style="cursor:pointer">
                                        <?php }?>
                                    </p>
                                    <select name="flegal_researcher" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                    <select name="flegal_researcher2" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php
                                            $stmt_la = $conn->prepare("SELECT * FROM user WHERE job_title='11'");
                                            $stmt_la->execute();
                                            $result_la = $stmt_la->get_result();
                                            if($result_la->num_rows > 0){
                                                while($row_la = $result_la->fetch_assoc()){
                                                    $lr_id = $row_la['id'];
                                                    $la_name = $row_la['name'];
                                        ?>
                                        <option value='<?php echo safe_output($lr_id); ?>' <?php if($row_details['flegal_researcher2'] == $lr_id){ echo 'selected'; }?>><?php echo safe_output($la_name); ?></option>
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
                                            المستشار القانوني
                                        </font>
                                        <?php if($row_permcheck['emp_perms_add'] == 1){?>
                                        <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="open('mbhEmps.php?newEmp=1','Pic','width=600 height=700 scrollbars=yes')" align="absmiddle" style="cursor:pointer">
                                        <?php }?>
                                    </p>
                                    <select name="flegal_advisor" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                    <select name="flegal_advisor2" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                        <option value='<?php echo safe_output($lr_id); ?>' <?php if($row_details['flegal_advisor2'] == $lr_id){ echo 'selected'; }?>><?php echo safe_output($la_name); ?></option>
                                        <?php
                                                }
                                            }
                                            $stmt_la->close();
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="advinputs">
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
                                                <select name="degree" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
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
                                            <td align="center" style="background-color: #e4e5e4"><input class="form-input" style="width: 50px" name="cnum" type="number"></td>
                                            <td align="center" style="background-color: #e4e5e4"><input class="form-input" name="year" style="width: 50px" type="number"></td>
                                            <td align="center" style="background-color: #e4e5e4">
                                                <select name="ccharedit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                    <option value=""></option>
                                                    <?php 
                                                        $stmt_scharachteristics11 = $conn->prepare("SELECT * FROM client_status");
                                                        $stmt_scharachteristics11->execute();
                                                        $result_scharachteristics11 = $stmt_scharachteristics11->get_result();
                                                        
                                                        if($result_scharachteristics11->num_rows > 0){
                                                            while($row_scharachteristics11 = $result_scharachteristics11->fetch_assoc()){
                                                                $stname11 = $row_scharachteristics11['arname'];
                                                    ?>
                                                    <option value='<?php echo safe_output($stname11);?>'><?php echo safe_output($stname11);?></option>
                                                    <?php
                                                            }
                                                        }
                                                        $stmt_scharachteristics11->close();
                                                    ?>
                                                </select>
                                            </td>
                                            <td align="center" style="background-color: #e4e5e4">
                                                <select name="ocharedit" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                                    <option value=""></option>
                                                    <?php 
                                                        $stmt_scharachteristics12 = $conn->prepare("SELECT * FROM client_status");
                                                        $stmt_scharachteristics12->execute();
                                                        $result_scharachteristics12 = $stmt_scharachteristics12->get_result();
                                                        
                                                        if($result_scharachteristics12->num_rows > 0){
                                                            while($row_scharachteristics12 = $result_scharachteristics12->fetch_assoc()){
                                                                $stname12 = $row_scharachteristics12['arname'];
                                                    ?>
                                                    <option value='<?php echo safe_output($stname12);?>'><?php echo safe_output($stname12);?></option>
                                                    <?php
                                                            }
                                                        }
                                                        $stmt_scharachteristics12->close();
                                                    ?>
                                                </select>
                                            </td>
                                            <td align="center" style="background-color: #e4e5e4"></td>
                                            <td align="center" style="background-color: #e4e5e4"></td>
                                        </tr>
                                        <?php }?>
                                    </table>
                                </div>
                            </div>
                            <div class="advinputs" style="margin-top: 40px; padding-bottom: 0; padding-top: 5px; background-color: #12538640">
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
                            </div>
                            <div class="profedit-footer">
                                <div style="text-align: center">
                                    <button type="submit" name="saveinfo" value=" اضغط هنا لحفظ البيانات" class="h-AdvancedSearch-Btn green-button" style="font-size: 18px">حفظ البيانات</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        
        <script src="js/newWindow.js"></script>
        <script src="js/translate.js"></script>
        <script src="js/toggleSection.js"></script>
        <script src="js/dropfiles.js"></script>
        <script src="js/popups.js"></script>
        <script src="js/randomPassGenerator.js"></script>
        <script src="js/dropmultiplefiles.js"></script>
        <script src="js/sweetAlerts.js"></script>
        <script src="js/sweetAlerts2.js"></script>
        <script src="js/tablePages.js"></script>
        <script src="js/checkAll.js"></script>
        <script src="js/dropdown.js"></script>
        
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
    </body>
</html>