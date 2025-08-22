<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'safe_output.php';
    include_once 'AES256.php';
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
    <body style="overflow: auto; padding-bottom: 50px;">
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['emp_perms_add'] == 1 || $row_permcheck['emp_perms_edit'] == 1 || $row_permcheck['emp_perms_delete'] == 1){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">العقود والرخص</font></h3>
                            </div>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"></div>
                                <?php
                                    if($row_permcheck['emp_perms_add'] == 1){
                                ?>
                                <div class="table-header-icons" style="background-image: url('img/arrow.png'); margin-right: 30px;" onclick="addclient()"></div>
                                <?php
                                    }
                                    if($row_permcheck['emp_perms_add'] == 1 || $row_permcheck['emp_perms_edit'] == 1){
                                ?>
                                <div id="addclient-btn" class="modal-overlay" <?php if((isset($_GET['addmore']) && $_GET['addmore'] === '1') || (isset($_GET['edit']) && $_GET['edit'] === '1')){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "تعديل بيانات العقد"; } else { echo 'عقد جديد'; }?></h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "location.href='Contracts.php';"; } else{ echo 'addclient()'; }?>">&times;</p>
                                            </div>
                                        </div>
                                        <?php
                                            if(isset($_GET['edit']) && $_GET['edit'] === '1'){
                                                $id = $_GET['id'];
                                                $estmt = $conn->prepare("SELECT * FROM contracts WHERE id=?");
                                                $estmt->bind_param("i", $id);
                                                $estmt->execute();
                                                $eresult = $estmt->get_result();
                                                $erow = $eresult->fetch_assoc();
                                                $estmt->close();
                                            }
                                        ?>
                                        <form action="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo 'contedit.php'; } else{ echo 'contadd.php'; }?>" method="post" enctype="multipart/form-data" >
                                            <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){?>
                                                <input type="hidden" name="id" value="<?php echo safe_output($_GET['id']);?>">
                                            <?php }?>
                                            <div class="addc-body">
                                                <div class="addc-body-form">
                                                    <div class="input-container">
                                                        <p class="input-parag">المالك<font style="color: #aa0820;">*</font></p>
                                                        <input class="form-input" name="owner" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['owner']); }?>" type="text" required>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">عقد ايجار/رخصة تجارية</p>
                                                        <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ $selectedemp = $erow['emp_id']; }?>
                                                        <select class="table-header-selector" name="rent_lic" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                            <option value="عقد إيجار" <?php if($erow['rent_lic'] === 'عقد إيجار'){ echo 'selected'; }?>>عقد إيجار</option>
                                                            <option value="رخصة تجارية" <?php if($erow['rent_lic'] === 'رخصة تجارية'){ echo 'selected'; }?>>رخصة تجارية</option>
                                                        </select>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">المكان</p>
                                                        <input class="form-input" name="place" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['place']); }?>" type="text">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">بداية من</p>
                                                        <input class="form-input" name="starting_d" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['starting_d']); }?>" type="date">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">الى</p>
                                                        <input class="form-input" name="ending_d" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['ending_d']); }?>" type="date">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">الفرع</p>
                                                        <select class="table-header-selector" name="branch" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                            <option value="الشارقة" <?php if($erow['branch'] === 'الشارقة'){ echo 'selected'; }?>>الشارقة</option>
                                                            <option value="دبي" <?php if($erow['branch'] === 'دبي'){ echo 'selected'; }?>>دبي</option>
                                                            <option value="عجمان" <?php if($erow['branch'] === 'عجمان'){ echo 'selected'; }?>>عجمان</option>
                                                        </select>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">العنوان</p>
                                                        <textarea class="form-input" name="notes" rows="2"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['notes']); }?></textarea>
                                                    </div>
                                                    <div class="moreinps-container">
                                                        <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                                            <span><i class='bx bxs-folder-open' ></i> <p>المرفقات</p></span> 
                                                            <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                                            <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                                        </button>
                                                        <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                                            <div class="input-container">
                                                                <h4 class="input-parag" style="padding-bottom: 10px;">صورة العقد / الرخصة</h4>
                                                                <div class="drop-zone" id="dropZone1">
                                                                    <input type="file" id="fileInput1" name="cont_lic_pic" hidden>
                                                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput1').click()">إرفاق مستند</span></p>
                                                                </div>
                                                                <div id="fileList1"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['cont_lic_pic'] !== ''){ echo '<p>📄'.basename(safe_output($erow['cont_lic_pic'])).'</p>'; }?></div>
                                                            </div>
                                                            
                                                            <div class="input-container">
                                                                <h4 class="input-parag" style="padding-bottom: 10px;">مرفقات أُخرى</h4>
                                                                <div class="drop-zone" id="dropZone2">
                                                                    <input type="file" id="fileInput2" name="attachment1" hidden>
                                                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput2').click()">إرفاق مستند</span></p>
                                                                </div>
                                                                <div id="fileList2"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['attachment1'] !== ''){ echo '<p>📄'.basename(safe_output($erow['attachment1'])).'</p>'; }?></div><br>
                                                                
                                                                <div class="drop-zone" id="dropZone3">
                                                                    <input type="file" id="fileInput3" name="attachment2" hidden>
                                                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput3').click()">إرفاق مستند</span></p>
                                                                </div>
                                                                <div id="fileList3"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['attachment2'] !== ''){ echo '<p>📄'.basename(safe_output($erow['attachment2'])).'</p>'; }?></div><br>
                                                                
                                                                <div class="drop-zone" id="dropZone4">
                                                                    <input type="file" id="fileInput4" name="attachment3" hidden>
                                                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput4').click()">إرفاق مستند</span></p>
                                                                </div>
                                                                <div id="fileList4"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['attachment3'] !== ''){ echo '<p>📄'.basename(safe_output($erow['attachment3'])).'</p>'; }?></div><br>
                                                                
                                                                <div class="drop-zone" id="dropZone5">
                                                                    <input type="file" id="fileInput5" name="attachment4" hidden>
                                                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput5').click()">إرفاق مستند</span></p>
                                                                </div>
                                                                <div id="fileList5"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['attachment4'] !== ''){ echo '<p>📄'.basename(safe_output($erow['attachment4'])).'</p>'; }?></div><br>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="addc-footer">
                                                <button 
                                                style="cursor: <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['emp_perms_edit'] == 1){ echo 'pointer'; } else{ echo 'not-allowed'; }} else{ if($row_permcheck['emp_perms_add'] == 1){ echo 'pointer'; } else{ echo 'not-allowed'; }}?>" 
                                                type="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['emp_perms_edit'] == 1){ echo 'submit'; } else{ echo 'button'; }} else{ if($row_permcheck['emp_perms_add'] == 1){ echo 'submit'; } else{ echo 'button'; }}?>" 
                                                class="form-btn submit-btn">حفظ</button>
                                                <button 
                                                style="cursor: <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['emp_perms_edit'] == 1){ echo 'pointer'; } else{ echo 'not-allowed'; }} else{ if($row_permcheck['emp_perms_add'] == 1){ echo 'pointer'; } else{ echo 'not-allowed'; }}?>" 
                                                type="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['emp_perms_edit'] == 1){ echo 'submit'; } else{ echo 'button'; }} else{ if($row_permcheck['emp_perms_add'] == 1){ echo 'submit'; } else{ echo 'button'; }}?>" 
                                                name="submit_back" value="addmore" class="form-btn cancel-btn"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "حفظ و انشاء جديد"; } else{ echo 'حفظ و انشاء آخر'; }?></button>
                                                <button type="button" class="form-btn cancel-btn" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "location.href='Contracts.php';"; } else{ echo 'addclient()'; }?>">الغاء</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php }?>
                                
                                <div class="modal-overlay" <?php if(isset($_GET['attachments']) && $_GET['attachments'] === '1'){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content" style="margin: auto; align-content: center">
                                        <div class="notes-displayer">
                                            <div class="addc-header">
                                                <h4 class="addc-header-parag" style="margin: auto">مرفقات العقد</h4>
                                                <div class="close-button-container">
                                                    <p class="close-button" onclick="location.href='Contracts.php';" style="display: inline-block">&times;</p>
                                                </div>
                                            </div>
                                            <div class="notes-body" style="padding: 10px; text-align: right;">
                                                <?php
                                                    $getid = $_GET['id'];
                                                    $stmtatt = $conn->prepare("SELECT * FROM contracts WHERE id=?");
                                                    $stmtatt->bind_param("i", $getid);
                                                    $stmtatt->execute();
                                                    $resultatt = $stmtatt->get_result();
                                                    $rowatt = $resultatt->fetch_assoc();
                                                    $stmtatt->close();
                                                    
                                                    if(isset($rowatt['cont_lic_pic']) && $rowatt['cont_lic_pic'] !== ''){
                                                ?>
                                                <div class="attachment-row">
                                                    <p>صورة العقد / الرخصة : </p>
                                                    <a href="<?php echo safe_output($rowatt['cont_lic_pic']);?>" onClick="window.open('<?php echo safe_output($rowatt['cont_lic_pic']);?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                        <?php echo basename(safe_output($rowatt['cont_lic_pic']));?>
                                                    </a>
                                                    <?php if($row_permcheck['emp_perms_edit'] == 1){?>
                                                    <div class="perms-check" onclick="location.href='contattachdel.php?id=<?php echo safe_output($rowatt['id']);?>&del=cont_lic_pic&page=Contracts.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                    <?php }?>
                                                </div>
                                                <?php
                                                    }
                                                    if(isset($rowatt['attachment1']) && $rowatt['attachment1'] !== ''){
                                                ?>
                                                <div class="attachment-row">
                                                    <p>المرفق 1 : </p>
                                                    <a href="<?php echo safe_output($rowatt['attachment1']);?>" onClick="window.open('<?php echo safe_output($rowatt['attachment1']);?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                        <?php echo basename(safe_output($rowatt['attachment1']));?>
                                                    </a>
                                                    <?php if($row_permcheck['emp_perms_edit'] == 1){?>
                                                    <div class="perms-check" onclick="location.href='contattachdel.php?id=<?php echo safe_output($rowatt['id']);?>&del=attachment1&page=Contracts.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                    <?php }?>
                                                </div>
                                                <?php
                                                    }
                                                    if(isset($rowatt['attachment2']) && $rowatt['attachment2'] !== ''){
                                                ?>
                                                <div class="attachment-row">
                                                    <p>المرفق 2 : </p>
                                                    <a href="<?php echo safe_output($rowatt['attachment2']);?>" onClick="window.open('<?php echo safe_output($rowatt['attachment2']);?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                        <?php echo basename(safe_output($rowatt['attachment2']));?>
                                                    </a>
                                                    <?php if($row_permcheck['emp_perms_edit'] == 1){?>
                                                    <div class="perms-check" onclick="location.href='contattachdel.php?id=<?php echo safe_output($rowatt['id']);?>&del=attachment2&page=Contracts.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                    <?php }?>
                                                </div>
                                                <?php
                                                    }
                                                    if(isset($rowatt['attachment3']) && $rowatt['attachment3'] !== ''){
                                                ?>
                                                <div class="attachment-row">
                                                    <p>المرفق 3 : </p>
                                                    <a href="<?php echo safe_output($rowatt['attachment3']);?>" onClick="window.open('<?php echo safe_output($rowatt['attachment3']);?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                        <?php echo basename(safe_output($rowatt['attachment3']));?>
                                                    </a>
                                                    <?php if($row_permcheck['emp_perms_edit'] == 1){?>
                                                    <div class="perms-check" onclick="location.href='contattachdel.php?id=<?php echo safe_output($rowatt['id']);?>&del=attachment3&page=Contracts.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                    <?php }?>
                                                </div>
                                                <?php
                                                    }
                                                    if(isset($rowatt['attachment4']) && $rowatt['attachment4'] !== ''){
                                                ?>
                                                <div class="attachment-row">
                                                    <p>المرفق 4 : </p>
                                                    <a href="<?php echo safe_output($rowatt['attachment4']);?>" onClick="window.open('<?php echo safe_output($rowatt['attachment4']);?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                        <?php echo basename(safe_output($rowatt['attachment4']));?>
                                                    </a>
                                                    <?php if($row_permcheck['emp_perms_edit'] == 1){?>
                                                    <div class="perms-check" onclick="location.href='contattachdel.php?id=<?php echo safe_output($rowatt['id']);?>&del=attachment4&page=Contracts.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                    <?php }?>
                                                </div>
                                                <?php }?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-body">
                            <form action="contdel.php" method="post">
                                <table class="info-table" id="myTable" style="width: 100%">
                                    <thead>
                                        <tr class="infotable-search">
                                            <td colspan="19">
                                                <div class="input-container">
                                                    <p class="input-parag" style="display: inline-block">البحث : </p>
                                                    <input class="form-input" style="display: inline-block; width: 50%;" type="text" id="SearchBox">
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr class="infotable-header" style="position: sticky; top: 0; z-index: 3;">
                                            <th>المالك</th>
                                            <th>عقد ايجار / رخصة تجارية</th>
                                            <th>الفترة الزمنية</th>
                                            <th>الفرع</th>
                                            <th>المكان</th>
                                            <th width="80px">الاجراءات</th>
                                        </tr>
                                    </thead>
                                    
                                    <?php
                                        $stmt = $conn->prepare("SELECT * FROM contracts ORDER BY id DESC");
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                    ?>
                                    <tbody id="table1">
                                        <tr class="infotable-body">
                                            <td style="color: #007bff;"><?php echo safe_output($row['owner']);?></td>
                                            <td><?php echo safe_output($row['rent_lic']);?></td>
                                            <td><?php echo 'من' . safe_output($row['starting_d']) . '<br>الى' . safe_output($row['ending_d']);?></td>
                                            <td><?php echo safe_output($row['branch']);?></td>
                                            <td><?php echo safe_output($row['place']);?></td>
                                            <td>
                                                <?php if($row_permcheck['emp_perms_edit'] == 1){?>
                                                <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='Contracts.php?edit=1&id=<?php echo safe_output($row['id']);?>';">
                                                <?php } if($row_permcheck['emp_perms_delete'] == 1){?>
                                                <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='deletecontract.php?id=<?php echo safe_output($row['id']);?>';">
                                                <?php }?>
                                                <img src="img/attachments.png" style="cursor: pointer;" title="المرفقات" height="20px" width="20px" onclick="location.href='Contracts.php?attachments=1&id=<?php echo safe_output($row['id']);?>';">
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php }}?>
                                </table>
                            </div>
                            
                            <div class="table-footer">
                                <p></p>
                                <div id="pagination"></div>
                                <div id="pageInfo"></div>
                            </form>
                        </div>
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
        <script src="js/sweetAlerts.js"></script>
        <script src="js/sweetAlerts2.js"></script>
        <script src="js/tablePages.js"></script>
        <script src="js/checkAll.js"></script>
        <script src="js/dropdown.js"></script>
    </body>
</html>