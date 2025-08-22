<?php
    include_once 'connection.php';
    include_once 'login_check.php';
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
            $id = $_SESSION['id'];
            $querymain = "SELECT * FROM user WHERE id='$id'";
            $resultmain = mysqli_query($conn, $querymain);
            $rowmain = mysqli_fetch_array($resultmain);
            
            $myid = $_SESSION['id'];
            $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
            $result_permcheck = mysqli_query($conn, $query_permcheck);
            $row_permcheck = mysqli_fetch_array($result_permcheck);
            
            include_once 'AES256.php';
            
            $query_emptyc = "SELECT * FROM lawyers WHERE name='' AND tel_no='' AND about=''";
            $result_emptyc = mysqli_query($conn, $query_emptyc);
            if($result_emptyc->num_rows == 0){
                $query_create = "INSERT INTO lawyers (name, tel_no, about, attachment1, attachment2, attachment3,
                attachment4, attachment5, attachment6, timestamp) VALUES ('', '', '', '', '', '', '', '', '', '')";
                $result_create = mysqli_query($conn, $query_create);
            }
        ?>
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['emp_perms_add'] === '1' || $row_permcheck['emp_perms_edit'] === '1' || $row_permcheck['emp_perms_delete'] === '1'){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">مكاتب المحامين</font></h3>
                            </div>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"></div>
                                <?php
                                    if($row_permcheck['emp_perms_add'] === '1'){
                                ?>
                                <div class="table-header-icons" style="background-image: url('img/arrow.png'); margin-right: 30px;" onclick="addclient()"></div>
                                <?php
                                    }
                                    if($row_permcheck['emp_perms_add'] === '1' || $row_permcheck['emp_perms_edit'] === '1'){
                                ?>
                                <div id="addclient-btn" class="modal-overlay" <?php if((isset($_GET['addmore']) && $_GET['addmore'] === '1') || (isset($_GET['edit']) && $_GET['edit'] === '1')){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "تعديل بيانات المكتب"; } else { echo 'مكتب جديد'; }?></h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "location.href='Lawyers.php';"; } else{ echo 'addclient()'; }?>">&times;</p>
                                            </div>
                                        </div>
                                        <?php
                                            if(isset($_GET['edit']) && $_GET['edit'] === '1'){
                                                $id = $_GET['id'];
                                                $equery = "SELECT * FROM lawyers WHERE id='$id'";
                                                $eresult = mysqli_query($conn, $equery);
                                                $erow = mysqli_fetch_array($eresult);
                                            }
                                            
                                            $query_lawyers = "SELECT * FROM lawyers WHERE name='' AND tel_no='' AND about='' AND attachment1='' AND attachment2=''
                                            AND attachment3='' AND attachment4='' AND attachment5='' AND attachment6=''";
                                            $result_lawyers = mysqli_query($conn, $query_lawyers);
                                            $row_lawyers = mysqli_fetch_array($result_lawyers);
                                        ?>
                                        <form action="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo 'lawyeredit.php'; } else{ echo 'lawyeradd.php'; }?>" method="post" enctype="multipart/form-data" >
                                            <div class="addc-body">
                                                <div class="addc-body-form">
                                                    <div class="input-container">
                                                        <p class="input-parag">اسم المحامي<font style="color: #aa0820;">*</font></p>
                                                        <input class="form-input" name="name" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['name']; }?>" type="text" required>
                                                        <input type="hidden" name="id" value="<?php if(isset($_GET['id']) && $_GET['id'] !== ''){ echo $_GET['id']; } else{ echo $row_lawyers['id']; }?>">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">هاتف متحرك<font style="color: #aa0820;">*</font></p>
                                                        <input class="form-input" name="tel_no" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['tel_no']; }?>" type="text" required>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">نبذة مختصرة</p>
                                                        <textarea class="form-input" name="about" rows="2"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['about']; }?></textarea>
                                                    </div>
                                                    <div class="moreinps-container">
                                                        <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                                            <span><i class='bx bxs-folder-open' ></i> <p>المرفقات</p></span> 
                                                            <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                                            <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                                        </button>
                                                        <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                                            <div class="input-container">
                                                                <div class="drop-zone" id="dropZone1">
                                                                    <input type="file" id="fileInput1" name="attachment1" hidden>
                                                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput1').click()">إرفاق مستند</span></p>
                                                                </div>
                                                                <div id="fileList1"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['attachment1'] !== ''){ echo '<p>📄'.basename($erow['attachment1']).'</p>'; }?></div><br>
                                                                
                                                                <div class="drop-zone" id="dropZone2">
                                                                    <input type="file" id="fileInput2" name="attachment2" hidden>
                                                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput2').click()">إرفاق مستند</span></p>
                                                                </div>
                                                                <div id="fileList2"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['attachment2'] !== ''){ echo '<p>📄'.basename($erow['attachment2']).'</p>'; }?></div><br>
                                                                
                                                                <div class="drop-zone" id="dropZone3">
                                                                    <input type="file" id="fileInput3" name="attachment3" hidden>
                                                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput3').click()">إرفاق مستند</span></p>
                                                                </div>
                                                                <div id="fileList3"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['attachment3'] !== ''){ echo '<p>📄'.basename($erow['attachment3']).'</p>'; }?></div><br>
                                                                
                                                                <div class="drop-zone" id="dropZone4">
                                                                    <input type="file" id="fileInput4" name="attachment4" hidden>
                                                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput4').click()">إرفاق مستند</span></p>
                                                                </div>
                                                                <div id="fileList4"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['attachment4'] !== ''){ echo '<p>📄'.basename($erow['attachment4']).'</p>'; }?></div><br>
                                                                
                                                                <div class="drop-zone" id="dropZone5">
                                                                    <input type="file" id="fileInput5" name="attachment5" hidden>
                                                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput5').click()">إرفاق مستند</span></p>
                                                                </div>
                                                                <div id="fileList5"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['attachment5'] !== ''){ echo '<p>📄'.basename($erow['attachment5']).'</p>'; }?></div><br>
                                                                
                                                                <div class="drop-zone" id="dropZone6">
                                                                    <input type="file" id="fileInput6" name="attachment6" hidden>
                                                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput6').click()">إرفاق مستند</span></p>
                                                                </div>
                                                                <div id="fileList6"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['attachment6'] !== ''){ echo '<p>📄'.basename($erow['attachment6']).'</p>'; }?></div><br>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="addc-footer">
                                                <button 
                                                style="cursor: <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['emp_perms_edit'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }} else{ if($row_permcheck['emp_perms_add'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }}?>" 
                                                type="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['emp_perms_edit'] === '1'){ echo 'submit'; } else{ echo 'button'; }} else{ if($row_permcheck['emp_perms_add'] === '1'){ echo 'submit'; } else{ echo 'button'; }}?>" 
                                                class="form-btn submit-btn">حفظ</button>
                                                <button 
                                                style="cursor: <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['emp_perms_edit'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }} else{ if($row_permcheck['emp_perms_add'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }}?>" 
                                                type="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['emp_perms_edit'] === '1'){ echo 'submit'; } else{ echo 'button'; }} else{ if($row_permcheck['emp_perms_add'] === '1'){ echo 'submit'; } else{ echo 'button'; }}?>" 
                                                name="submit_back" value="addmore" class="form-btn cancel-btn"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "حفظ و انشاء جديد"; } else{ echo 'حفظ و انشاء آخر'; }?></button>
                                                <button type="button" class="form-btn cancel-btn" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "location.href='clientsCalls.php';"; } else{ echo 'addclient()'; }?>">الغاء</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php }?>
                                
                                <div class="modal-overlay" <?php if(isset($_GET['attachments']) && $_GET['attachments'] === '1'){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content" style="margin: auto; align-content: center">
                                        <div class="notes-displayer">
                                            <div class="addc-header">
                                                <h4 class="addc-header-parag" style="margin: auto">مرفقات المكتب</h4>
                                                <div class="close-button-container">
                                                    <p class="close-button" onclick="location.href='Lawyers.php';" style="display: inline-block">&times;</p>
                                                </div>
                                            </div>
                                            <div class="notes-body" style="padding: 10px; text-align: right;">
                                                <?php
                                                    $getid = $_GET['id'];
                                                    $queryatt = "SELECT * FROM lawyers WHERE id='$getid'";
                                                    $resultatt = mysqli_query($conn, $queryatt);
                                                    $rowatt = mysqli_fetch_array($resultatt);
                                                    
                                                    if(isset($rowatt['attachment1']) && $rowatt['attachment1'] !== ''){
                                                ?>
                                                <div class="attachment-row">
                                                    <p></p>
                                                    <a href="<?php echo $rowatt['attachment1'];?>" onClick="window.open('<?php echo $rowatt['attachment1'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                        <?php echo basename($rowatt['attachment1']);?>
                                                    </a>
                                                    <?php if($row_permcheck['clients_eperm'] === '1'){?>
                                                    <div class="perms-check" onclick="location.href='lawattachdel.php?id=<?php echo $rowatt['id'];?>&del=attachment1&page=Lawyers.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                    <?php }?>
                                                </div>
                                                <?php
                                                    }
                                                    if(isset($rowatt['attachment2']) && $rowatt['attachment2'] !== ''){
                                                ?>
                                                <div class="attachment-row">
                                                    <p></p>
                                                    <a href="<?php echo $rowatt['attachment2'];?>" onClick="window.open('<?php echo $rowatt['attachment2'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                        <?php echo basename($rowatt['attachment2']);?>
                                                    </a>
                                                    <?php if($row_permcheck['clients_eperm'] === '1'){?>
                                                    <div class="perms-check" onclick="location.href='lawattachdel.php?id=<?php echo $rowatt['id'];?>&del=attachment2&page=Lawyers.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                    <?php }?>
                                                </div>
                                                <?php
                                                    }
                                                    if(isset($rowatt['attachment3']) && $rowatt['attachment3'] !== ''){
                                                ?>
                                                <div class="attachment-row">
                                                    <p></p>
                                                    <a href="<?php echo $rowatt['attachment3'];?>" onClick="window.open('<?php echo $rowatt['attachment3'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                        <?php echo basename($rowatt['attachment3']);?>
                                                    </a>
                                                    <?php if($row_permcheck['clients_eperm'] === '1'){?>
                                                    <div class="perms-check" onclick="location.href='lawattachdel.php?id=<?php echo $rowatt['id'];?>&del=attachment3&page=Lawyers.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                    <?php }?>
                                                </div>
                                                <?php
                                                    }
                                                    if(isset($rowatt['attachment4']) && $rowatt['attachment4'] !== ''){
                                                ?>
                                                <div class="attachment-row">
                                                    <p></p>
                                                    <a href="<?php echo $rowatt['attachment4'];?>" onClick="window.open('<?php echo $rowatt['attachment4'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                        <?php echo basename($rowatt['attachment4']);?>
                                                    </a>
                                                    <?php if($row_permcheck['clients_eperm'] === '1'){?>
                                                    <div class="perms-check" onclick="location.href='lawattachdel.php?id=<?php echo $rowatt['id'];?>&del=attachment4&page=Lawyers.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                    <?php }?>
                                                </div>
                                                <?php
                                                    }
                                                    if(isset($rowatt['attachment5']) && $rowatt['attachment5'] !== ''){
                                                ?>
                                                <div class="attachment-row">
                                                    <p></p>
                                                    <a href="<?php echo $rowatt['attachment5'];?>" onClick="window.open('<?php echo $rowatt['attachment5'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                        <?php echo basename($rowatt['attachment5']);?>
                                                    </a>
                                                    <?php if($row_permcheck['clients_eperm'] === '1'){?>
                                                    <div class="perms-check" onclick="location.href='lawattachdel.php?id=<?php echo $rowatt['id'];?>&del=attachment5&page=Lawyers.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                    <?php }?>
                                                </div>
                                                <?php
                                                    }
                                                    if(isset($rowatt['attachment6']) && $rowatt['attachment6'] !== ''){
                                                ?>
                                                <div class="attachment-row">
                                                    <p></p>
                                                    <a href="<?php echo $rowatt['attachment6'];?>" onClick="window.open('<?php echo $rowatt['attachment6'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                        <?php echo basename($rowatt['attachment6']);?>
                                                    </a>
                                                    <?php if($row_permcheck['clients_eperm'] === '1'){?>
                                                    <div class="perms-check" onclick="location.href='lawattachdel.php?id=<?php echo $rowatt['id'];?>&del=attachment6&page=Lawyers.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
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
                            <form action="lawdel.php" method="post">
                                <table class="info-table" id="myTable" style="width: 100%;">
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
                                            <th>اسم المحامي</th>
                                            <th>هاتف متحرك / ثابت</th>
                                            <th>نبذة مختصرة</th>
                                            <th width="80px">الاجراءات</th>

                                        </tr>
                                    </thead>
                                    
                                    <?php
                                        $query = "SELECT * FROM lawyers WHERE name!='' AND tel_no!='' ORDER BY id DESC";
                                        $result = mysqli_query($conn, $query);
                                        if($result->num_rows > 0){
                                            while($row = mysqli_fetch_array($result)){
                                    ?>
                                    <tbody id="table1">
                                        <tr class="infotable-body">
                                            <td>
                                                <?php 
                                                    if(isset($row['name']) && $row['name'] !== ''){
                                                        echo $row['name'];
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if(isset($row['tel_no']) && $row['tel_no'] !== ''){
                                                        echo $row['tel_no'];
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if(isset($row['about']) && $row['about'] !== ''){
                                                        echo $row['about'];
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if($row_permcheck['emp_perms_edit'] === '1'){?>
                                                <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='Lawyers.php?edit=1&id=<?php echo $row['id'];?>';">
                                                <?php } if($row_permcheck['emp_perms_delete'] === '1'){?>
                                                <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='deletelawyer.php?id=<?php echo $row['id'];?>';">
                                                <?php }?>
                                                <img src="img/attachments.png" style="cursor: pointer;" title="المرفقات" height="20px" width="20px" onclick="location.href='Lawyers.php?attachments=1&id=<?php echo $row['id'];?>';">
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