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
                    if($row_permcheck['emp_perms_read'] == 1){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">الموارد و المركبات</font></h3>
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
                                            <h4 class="addc-header-parag" style="margin: auto"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "تعديل بيانات المركبة"; } else { echo 'مركبة جديدة'; }?></h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "location.href='Office_Vehicles.php';"; } else{ echo 'addclient()'; }?>">&times;</p>
                                            </div>
                                        </div>
                                        <?php
                                            if(isset($_GET['edit']) && $_GET['edit'] === '1'){
                                                $id = $_GET['id'];
                                                $estmt = $conn->prepare("SELECT * FROM vehicles WHERE id=?");
                                                $estmt->bind_param("i", $id);
                                                $estmt->execute();
                                                $eresult = $estmt->get_result();
                                                $erow = $eresult->fetch_assoc();
                                                $estmt->close();
                                            }
                                        ?>
                                        <form action="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo 'vehedit.php'; } else{ echo 'vehicleadd.php'; }?>" method="post" enctype="multipart/form-data" >
                                            <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){?>
                                                <input type="hidden" name="id" value="<?php echo safe_output($_GET['id']);?>">
                                            <?php }?>
                                            <div class="addc-body">
                                                <div class="addc-body-form">
                                                    <div class="input-container">
                                                        <p class="input-parag">عهدة الموظف<font style="color: #aa0820;">*</font></p>
                                                        <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ $selectedemp = $erow['emp_id']; }?>
                                                        <select class="table-header-selector" name="emp_id" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;" required>
                                                            <option></option>
                                                            <?php
                                                                $stmt_empid = $conn->prepare("SELECT * FROM user");
                                                                $stmt_empid->execute();
                                                                $result_empid = $stmt_empid->get_result();
                                                                if($result_empid->num_rows > 0){
                                                                    while($row_empid = $result_empid->fetch_assoc()){
                                                            ?>
                                                            <option value="<?php echo safe_output($row_empid['id']);?>" <?php if($selectedemp === $row_empid['id']){ echo 'selected'; }?>><?php echo safe_output($row_empid['name']);?></option>
                                                            <?php
                                                                    }
                                                                }
                                                                $stmt_empid->close();
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">نوع السيارة<font style="color: #aa0820;">*</font></p>
                                                        <input class="form-input" name="type" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['type']); }?>" type="text" required>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">موديل السيارة</p>
                                                        <input class="form-input" name="model" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['model']); }?>" type="text">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">رقم السيارة</p>
                                                        <input class="form-input" name="num" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['num']); }?>" type="text">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">تاريخ انتهاء التأمين</p>
                                                        <input class="form-input" name="insur_expir" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['insur_expir']); }?>" type="date">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">تاريخ انتهاء الملكية</p>
                                                        <input class="form-input" name="lic_expir" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['lic_expir']); }?>" type="date">
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
                                                        <p class="input-parag" rows="2">ملاحظات</p>
                                                        <textarea class="form-input" name="notes"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['notes']); }?></textarea>
                                                    </div>
                                                    <div class="input-container">
                                                        <h4 class="input-parag" style="padding-bottom: 10px;">صورة المركبة</h4>
                                                        <div class="drop-zone" id="dropZone1">
                                                            <input type="file" id="fileInput1" name="photo" hidden>
                                                            <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                            <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput1').click()">إرفاق مستند</span></p>
                                                        </div>
                                                        <div id="fileList1"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['photo'] !== ''){ echo '<p>📄'.basename(safe_output($erow['photo'])).'</p>'; }?></div>
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
                                                <button type="button" class="form-btn cancel-btn" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "location.href='Office_Vehicles.php';"; } else{ echo 'addclient()'; }?>">الغاء</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                        </div>
                        <div class="table-body">
                            <form action="vehdel.php" method="post">
                                <table class="info-table" id="myTable" style="width: 100%; background-color: #99999940">
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
                                            <th>عهدة الموظف</th>
                                            <th>الفرع</th>
                                            <th>نوع السيارة</th>
                                            <th>موديل السيارة</th>
                                            <th>رقم السيارة</th>
                                            <th>النتهاء الملكية</th>
                                            <th>انتهاء التأمين</th>
                                            <th width="80px"></th>
                                        </tr>
                                    </thead>
                                    
                                    <?php
                                        $stmt = $conn->prepare("SELECT * FROM vehicles ORDER BY id DESC");
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                    ?>
                                    <tbody id="table1">
                                        <tr class="infotable-body">
                                            <td style="color: #007bff;">
                                                <?php
                                                    $emp_id = $row['emp_id'];
                                                    
                                                    $stmtemp = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                    $stmtemp->bind_param("i", $emp_id);
                                                    $stmtemp->execute();
                                                    $resultemp = $stmtemp->get_result();
                                                    $rowemp = $resultemp->fetch_assoc();
                                                    $stmtemp->close();
                                                    
                                                    echo safe_output($rowemp['name']);
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if(isset($row['branch']) && $row['branch'] !== ''){
                                                        echo safe_output($row['branch']) . ' - ';
                                                        if($row['branch'] === 'عجمان'){
                                                            echo 'AJM';
                                                        }else if($row['branch'] === 'دبي'){
                                                            echo 'DXB';
                                                        }else if($row['branch'] === 'الشارقة'){
                                                            echo 'SHJ';
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <td><?php if(isset($row['type']) && $row['type'] !== ''){echo safe_output($row['type']);}?></td>
                                            <td><?php if(isset($row['model']) && $row['model'] !== ''){echo safe_output($row['model']);}?></td>
                                            <td><?php if(isset($row['num']) && $row['num'] !== ''){echo safe_output($row['num']);}?></td>
                                            <td><?php if(isset($row['lic_expir']) && $row['lic_expir'] !== ''){echo safe_output($row['lic_expir']);}?></td>
                                            <td><?php if(isset($row['insur_expir']) && $row['insur_expir'] !== ''){echo safe_output($row['insur_expir']);}?></td>
                                            <td>
                                                <?php if($row_permcheck['emp_perms_edit'] == 1){?>
                                                <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='Office_Vehicles.php?edit=1&id=<?php echo safe_output($row['id']);?>';">
                                                <?php } if($row_permcheck['emp_perms_delete'] == 1){?>
                                                <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='deletevehicle.php?id=<?php echo safe_output($row['id']);?>';">
                                                <?php } if($row['photo'] !== ''){?>
                                                <img src="img/attachments.png" style="cursor: pointer;" title="المرفق" height="20px" width="20px" onclick="window.open('<?php echo safe_output($row['photo']);?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                <?php }?>
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