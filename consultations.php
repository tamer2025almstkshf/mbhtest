<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'AES256.php';
    
    $stmtbranch = $conn->prepare("SELECT * FROM user WHERE id=?");
    $stmtbranch->bind_param("i", $_SESSION['id']);
    $stmtbranch->execute();
    $resultbranch = $stmtbranch->get_result();
    $rowbranch = $resultbranch->fetch_assoc();
    $stmtbranch->close();
    $branch = $rowbranch['work_place'];
    if($admin != 1){
        if(!isset($_GET['branch']) || $_GET['branch'] !== $branch){
            header("Location: consultations.php?branch=$branch");
            exit();
        }
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="shortcut icon" href="files/images/instance/favicon.ico?v=35265" type="image/icon">
        <link href="css/styles.css" rel="stylesheet">
        <link rel="SHORTCUT ICON" href="img/favicon.ico">
    </head>
    <body style="overflow: auto">
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['cons_rperm'] == 1){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <?php if($admin == 1){?>
                                <form action="ctypechange.php" method="post" enctype="multipart/form-data">
                                    <h3 style="display: inline-block"><font id="clients-translate">الاستشارات القانونية</font></h3>
                                    <input type="hidden" name="page" value="consultations.php">
                                    <select class="table-header-selector" name="branch" onchange="submit()" style="padding: 0 5px;">
                                        <option value="select">اختر الفرع</option>
                                        <option value="Dubai" <?php if($_GET['branch'] === 'Dubai'){ echo 'selected'; }?>><font id="clients-translate">Dubai</font></option>
                                        <option value="Sharjah" <?php if($_GET['branch'] === 'Sharjah'){ echo 'selected'; }?>><font id="opponents-translate">Sharjah</font></option>
                                        <option value="Ajman" <?php if($_GET['branch'] === 'Ajman'){ echo 'selected'; }?>><font id="subs-translate">Ajman</font></option>
                                        <?php
                                            $stmtbranchs = $conn->prepare("SELECT * FROM branchs");
                                            $stmtbranchs->execute();
                                            $resultbranchs = $stmtbranchs->get_result();
                                            if($resultbranchs->num_rows > 0){
                                                while($rowbranchs = $resultbranchs->fetch_assoc()){
                                        ?>
                                        <option value="<?php echo safe_output($rowbranchs['branch']);?>"<?php if($_GET['branch'] === $rowbranchs['branch']){ echo 'selected'; };?>><?php echo safe_output($rowbranchs['branch']);?></option>
                                        <?php
                                                }
                                            }
                                            $stmtbranchs->close();
                                        ?>
                                    </select>
                                    <?php if($row_permcheck['selectors_rperm'] == 1){?>
                                    <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="MM_openBrWindow('selector/Branchs.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=400')" align="absmiddle" style="cursor:pointer"/>
                                    <?php }?>
                                </form>
                                <?php }?>
                            </div>
                            <div class="table-header-left" style="align-content: center;">
                                <div class="table-header-icons" style="margin-right: 10px;"><img src="img/xlsx.png" width="25px" height="25px" onclick="exportToExcel()"></div>
                                <div class="table-header-icons" style="background-image: url('img/edit.png'); margin-right: 20px; height: 25px; width: 25px;" onclick="location.href='consultations.php?moreinfo=1<?php if(isset($_GET['branch']) && $_GET['branch'] !== ''){ echo '&branch='.$_GET['branch']; }?>';"></div>
                                <?php
                                    if($row_permcheck['cons_aperm'] == 1){
                                ?>
                                <div class="table-header-icons" style="background-image: url('img/arrow.png'); margin-right: 30px;" onclick="addclient()"></div>
                                <?php
                                    }
                                    if($row_permcheck['cons_aperm'] == 1 || $row_permcheck['cons_eperm'] == 1){
                                ?>
                                <div id="addclient-btn" class="modal-overlay" <?php if((isset($_GET['addmore']) && $_GET['addmore'] === '1') || (isset($_GET['edit']) && $_GET['edit'] === '1')){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "تعديل بيانات الاستشارة"; } else { echo 'استشارة جديدة'; }?></h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ $getbranch = $_GET['branch']; echo "location.href='consultations.php?branch=$getbranch';"; } else{ echo 'addclient()'; }?>">&times;</p>
                                            </div>
                                        </div>
                                        <?php
                                            if(isset($_GET['edit']) && $_GET['edit'] === '1'){
                                                $id = $_GET['id'];
                                                $estmt = $conn->prepare("SELECT * FROM consultations WHERE id=?");
                                                $estmt->bind_param("i", $id);
                                                $estmt->execute();
                                                $eresult = $estmt->get_result();
                                                $erow = $eresult->fetch_assoc();
                                                $estmt->close();
                                            }
                                        ?>
                                        <form action="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo 'consedit.php'; } else{ echo 'consadd.php'; }?>" method="post" enctype="multipart/form-data" >
                                            <input type="hidden" name="branch" value="<?php echo safe_output($_GET['branch']);?>">
                                            <div class="addc-body">
                                                <div class="addc-body-form">
                                                    <div class="input-container">
                                                        <p class="input-parag">اسم الموكل<font style="color: #aa0820;">*</font></p>
                                                        <input class="form-input" name="client_name" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['client_name']); }?>" type="text" required>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">الفرع<font style="color: #aa0820;">*</font></p>
                                                        <?php 
                                                            if($admin == 1){
                                                                $selectedwp = $erow['work_place'];
                                                        ?>
                                                        <select class="table-header-selector" name="branch" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                            <option value="Dubai" <?php if($_GET['branch'] === 'Dubai'){ echo 'selected'; }?>><font id="clients-translate">Dubai</font></option>
                                                            <option value="Sharjah" <?php if($_GET['branch'] === 'Sharjah'){ echo 'selected'; }?>><font id="opponents-translate">Sharjah</font></option>
                                                            <option value="Ajman" <?php if($_GET['branch'] === 'Ajman'){ echo 'selected'; }?>><font id="subs-translate">Ajman</font></option>
                                                            <?php
                                                                $stmtbranchs = $conn->prepare("SELECT * FROM branchs");
                                                                $stmtbranchs->execute();
                                                                $resultbranchs = $stmtbranchs->get_result();
                                                                if($resultbranchs->num_rows > 0){
                                                                    while($rowbranchs = $resultbranchs->fetch_assoc()){
                                                            ?>
                                                            <option value="<?php echo safe_output($rowbranchs['branch']);?>"<?php if($_GET['branch'] === $rowbranchs['branch']){ echo 'selected'; };?>><?php echo safe_output($rowbranchs['branch']);?></option>
                                                            <?php
                                                                    }
                                                                }
                                                                $stmtbranchs->close();
                                                            ?>
                                                        </select>
                                                        <?php 
                                                            } else{
                                                                $stmtme = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                                $stmtme->bind_param("i", $myid);
                                                                $stmtme->execute();
                                                                $resultme = $stmtme->get_result();
                                                                $rowme = $resultme->fetch_assoc();
                                                                $stmtme->close();
                                                        ?>
                                                        <p class="form-input"><?php echo safe_output($rowme['work_place']);?></p>
                                                        <input type="hidden" name="branch" value="<?php echo safe_output($rowme['work_place']);?>">
                                                        <?php }?>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">الفئة<font style="color: #aa0820;">*</font></p>
                                                        <select class="table-header-selector" name="client_type" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                            <option value="شخص" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['client_type'] === 'شخص'){ echo 'selected'; }?>>شخص</option>
                                                            <option value="شركة" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['client_type'] === 'شركة'){ echo 'selected'; }?>>شركة</option>
                                                            <option value="حكومة / مؤسسات" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['client_type'] === 'حكومة / مؤسسات'){ echo 'selected'; }?>>حكومة / مؤسسات</option>
                                                        </select>
                                                    </div>
                                                    <?php if(isset($_GET['id']) && $_GET['id'] !== ''){?>
                                                    <input type="hidden" name="id" value="<?php echo safe_output($_GET['id']);?>">
                                                    <?php }?>
                                                    <div class="input-container">
                                                        <p class="input-parag">الهاتف<font style="color: #aa0820;">*</font></p>
                                                        <input type="text" class="form-input" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['telno']); }?>" name="telno" rows="2">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">البريد الالكتروني<font style="color: #aa0820;">*</font></p>
                                                        <input type="email" class="form-input" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['email']); }?>" name="email" rows="2">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">التفاصيل</p>
                                                        <input type="text" class="form-input" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['details']); }?>" name="details" rows="2">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">كيف عرفت عن المكتب</p>
                                                        <input type="text" class="form-input" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['reference']); }?>" name="reference" rows="2">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">النتيجة</p>
                                                        <input type="text" class="form-input" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['result']); }?>" name="result" rows="2">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">المتابعة - 1</p>
                                                        <input type="text" class="form-input" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['followup1']); }?>" name="followup1" rows="2">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">المتابعة - 2</p>
                                                        <input type="text" class="form-input" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['followup2']); }?>" name="followup2" rows="2">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">المتابعة - 3</p>
                                                        <input type="text" class="form-input" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['followup3']); }?>" name="followup3" rows="2">
                                                    </div>
                                                    <div class="input-container">
                                                        <h4 class="input-parag" style="padding-bottom: 10px;">جواز السفر</h4>
                                                        <div class="drop-zone" id="dropZone1" data-target="fileInput1" data-list="fileList1" data-multiple="false">
                                                            <input type="file" id="fileInput1" name="passport" hidden>
                                                            <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                            <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput1').click()">إرفاق مستند</span></p>
                                                        </div>
                                                        <div id="fileList1"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['passport'] !== '') { echo '<p>📄' . basename($erow['passport']) . '</p>'; }?></div>
                                                    </div>
                                                    <div class="input-container">
                                                        <h4 class="input-parag" style="padding-bottom: 10px;">المرفقات</h4>
                                                        <div class="drop-zone" id="dropZone2" data-target="fileInput2" data-list="fileList2" data-multiple="true">
                                                            <input type="file" id="fileInput2" name="attach_files_multi[]" hidden multiple>
                                                            <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                            <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput2').click()">إرفاق مستند</span></p>
                                                        </div>
                                                        <div id="fileList2"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="addc-footer">
                                                <button 
                                                style="cursor: <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['cons_eperm'] == 1){ echo 'pointer'; } else{ echo 'not-allowed'; }} else{ if($row_permcheck['cons_aperm'] == 1){ echo 'pointer'; } else{ echo 'not-allowed'; }}?>" 
                                                type="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['cons_eperm'] == 1){ echo 'submit'; } else{ echo 'button'; }} else{ if($row_permcheck['cons_aperm'] == 1){ echo 'submit'; } else{ echo 'button'; }}?>" 
                                                class="form-btn submit-btn">حفظ</button>
                                                <button 
                                                style="cursor: <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['cons_eperm'] == 1){ echo 'pointer'; } else{ echo 'not-allowed'; }} else{ if($row_permcheck['cons_aperm'] == 1){ echo 'pointer'; } else{ echo 'not-allowed'; }}?>" 
                                                type="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['cons_eperm'] == 1){ echo 'submit'; } else{ echo 'button'; }} else{ if($row_permcheck['cons_aperm'] == 1){ echo 'submit'; } else{ echo 'button'; }}?>" 
                                                name="submit_back" value="addmore" class="form-btn cancel-btn"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "حفظ و انشاء آخر"; } else{ echo 'حفظ و انشاء آخر'; }?></button>
                                                <button type="button" class="form-btn cancel-btn" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "location.href='consultations.php';"; } else{ echo 'addclient()'; }?>">الغاء</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                        </div>
                        <div class="table-body">
                            <form action="consdel.php" method="post">
                                <table class="info-table" id="myTable">
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
                                            <th>التاريخ</th>
                                            <th>اسم العميل</th>
                                            <th>رقم العميل</th>
                                            <th>البريد الالكتروني</th>
                                            <?php if(isset($_GET['moreinfo']) && $_GET['moreinfo'] === '1'){?>
                                            <th>المرجع</th>
                                            <th>تفاصيل الاجتماع</th>
                                            <th>نتيجة الاجتماع</th>
                                            <th>المتابعة 1</th>
                                            <th>المتابعة 2</th>
                                            <th>المتابعة 3</th>
                                            <th>الحالة النهائية</th>
                                            <?php }?>
                                            <th>اسم الموظف</th>
                                            <th width="80px">الاجراءات</th>
                                            <?php
                                            
                                            ?>
                                        </tr>
                                    </thead>
                                    
                                    <?php
                                        if($admin != 1){
                                            $stmtme = $conn->prepare("SELECT work_place FROM user WHERE id=?");
                                            $stmtme->bind_param("i", $myid);
                                            $stmtme->execute();
                                            $resultme = $stmtme->get_result();
                                            $rowme = $resultme->fetch_assoc();
                                            $stmtme->close();
                                            
                                            $work_place = $rowme['work_place'];
                                            $stmt = $conn->prepare("SELECT * FROM consultations WHERE branch=? ORDER BY id DESC");
                                            $stmt->bind_param("s", $work_place);
                                        } else{
                                            if(isset($_GET['branch']) && $_GET['branch'] !== ''){
                                                $branch = $_GET['branch'];
                                                $stmt = $conn->prepare("SELECT * FROM consultations WHERE branch=? ORDER BY id DESC");
                                                $stmt->bind_param("s", $branch);
                                            } else{
                                                $stmt = $conn->prepare("SELECT * FROM consultations ORDER BY id DESC");
                                            }
                                        }
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                    ?>
                                    <tbody id="table1">
                                        <tr class="infotable-body">
                                            <td style="<?php if($row['status'] == 1){ echo 'background-color: #00FF0010;'; }?>">
                                                <?php 
                                                    if(isset($row['timestamp']) && $row['timestamp'] !== ''){
                                                        $tmid = $row['timestamp'];
                                                        list($date, $time) = explode(" ", $tmid);
                                                        
                                                        echo safe_output($date);
                                                    }
                                                ?>
                                            </td>
                                            <td style="<?php if($row['status'] == 1){ echo 'background-color: #00FF0010;'; }?>">
                                                <?php 
                                                    if(isset($row['client_name']) && $row['client_name'] !== ''){
                                                        echo safe_output($row['client_name']);
                                                    }
                                                ?>
                                            </td>
                                            <td style="<?php if($row['status'] == 1){ echo 'background-color: #00FF0010;'; }?>">
                                                <?php 
                                                    if(isset($row['telno']) && $row['telno'] !== ''){
                                                        echo safe_output($row['telno']);
                                                    }
                                                ?>
                                            </td>
                                            <td style="<?php if($row['status'] == 1){ echo 'background-color: #00FF0010;'; }?>">
                                                <?php 
                                                    if(isset($row['email']) && $row['email'] !== ''){
                                                        echo safe_output($row['email']);
                                                    }
                                                ?>
                                            </td>
                                            <?php if(isset($_GET['moreinfo']) && $_GET['moreinfo'] === '1'){?>
                                            <td style="<?php if($row['status'] == 1){ echo 'background-color: #00FF0010;'; }?>">
                                                <?php 
                                                    if(isset($row['reference']) && $row['reference'] !== ''){
                                                        echo safe_output($row['reference']);
                                                    }
                                                ?>
                                            </td>
                                            <td style="<?php if($row['status'] == 1){ echo 'background-color: #00FF0010;'; }?>">
                                                <?php 
                                                    if(isset($row['details']) && $row['details'] !== ''){
                                                        echo safe_output($row['details']);
                                                    }
                                                ?>
                                            </td>
                                            <td style="<?php if($row['status'] == 1){ echo 'background-color: #00FF0010;'; }?>">
                                                <?php 
                                                    if(isset($row['result']) && $row['result'] !== ''){
                                                        echo safe_output($row['result']);
                                                    }
                                                ?>
                                            </td>
                                            <td style="<?php if($row['status'] == 1){ echo 'background-color: #00FF0010;'; }?>">
                                                <?php 
                                                    if(isset($row['followup1']) && $row['followup1'] !== ''){
                                                        echo safe_output($row['followup1']);
                                                    }
                                                ?>
                                            </td>
                                            <td style="<?php if($row['status'] == 1){ echo 'background-color: #00FF0010;'; }?>">
                                                <?php 
                                                    if(isset($row['followup2']) && $row['followup2'] !== ''){
                                                        echo safe_output($row['followup2']);
                                                    }
                                                ?>
                                            </td>
                                            <td style="<?php if($row['status'] == 1){ echo 'background-color: #00FF0010;'; }?>">
                                                <?php 
                                                    if(isset($row['followup3']) && $row['followup3'] !== ''){
                                                        echo safe_output($row['followup3']);
                                                    }
                                                ?>
                                            </td>
                                            <td style="<?php if($row['status'] == 1){ echo 'background-color: #00FF0010;'; }?>">
                                                <?php 
                                                    if(isset($row['status']) && $row['status'] !== ''){
                                                        if($row['status'] == 1){
                                                            echo 'عميل حالي';
                                                        } else{
                                                            echo 'عميل محتمل';
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <?php }?>
                                            <td style="<?php if($row['status'] == 1){ echo 'background-color: #00FF0010;'; }?>">
                                                <?php 
                                                    if(isset($row['timestamp']) && $row['timestamp'] !== ''){
                                                        $tmid = $row['timestamp'];
                                                        list($date, $time) = explode(" ", $tmid);
                                                        
                                                        $myid = $row['empid'];
                                                        $stmtme = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                        $stmtme->bind_param("i", $myid);
                                                        $stmtme->execute();
                                                        $resultme = $stmtme->get_result();
                                                        $rowme = $resultme->fetch_assoc();
                                                        $stmtme->close();
                                                        $myname = $rowme['name'];
                                                        echo safe_output($myname);
                                                    }
                                                ?>
                                            </td>
                                            <td style="<?php if($row['status'] == 1){ echo 'background-color: #00FF0010;'; }?>">
                                                <?php if($row_permcheck['cons_eperm'] == 1){?>
                                                <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='consultations.php?branch=<?php echo $_GET['branch'];?>&edit=1&id=<?php echo safe_output($row['id']);?>';">
                                                <?php } if($row_permcheck['cons_dperm'] == 1){?>
                                                <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='deleteconsultations.php?id=<?php echo safe_output($row['id']);?>';">
                                                <?php } if($row_permcheck['clients_aperm'] && $row['status'] != 1){?>
                                                <img src="img/referral.png" title="عميل حالي" width="20px" height="20px" style="cursor: pointer;" onclick="location.href='cons_client.php?id=<?php echo $row['id'];?>&branch=<?php echo $_GET['branch'];?>';"> 
                                                <?php }?>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php 
                                            }
                                        }
                                        $stmt->close();
                                    ?>
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
        
        <script src="js/sweetAlerts2.js"></script>
        <script src="js/newWindow.js"></script>
        <script src="js/translate.js"></script>
        <script src="js/toggleSection.js"></script>
        <script src="js/dropmultiplefiles.js"></script>
        <script src="js/popups.js"></script>
        <script src="js/sweetAlerts.js"></script>
        <script src="js/randomPassGenerator.js"></script>
        <script src="js/tablePages.js"></script>
        <script src="js/checkAll.js"></script>
        <script src="js/dropdown.js"></script>
        <script>
            async function exportToExcel() {
                const response = await fetch("consxlsx.php?branch=<?php echo safe_output($_GET['branch']);?>");
                const data = await response.json();
                
                if (data.error) {
                    alert("Error: " + data.error);
                    return;
                }
                
                const workbook = new ExcelJS.Workbook();
                const sheet = workbook.addWorksheet("consultations");
                
                sheet.columns = [
                    { 
                        header: "Date", 
                        key: "date", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    },
                    { 
                        header: "Contact Name", 
                        key: "contact_name", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    },
                    { 
                        header: "Contact Number", 
                        key: "contact_number", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    },
                    { 
                        header: "Email Address", 
                        key: "email", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    },
                    { 
                        header: "Reference", 
                        key: "reference", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    },
                    { 
                        header: "Meeting Details", 
                        key: "meeting_details", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    },
                    { 
                        header: "Meeting Result", 
                        key: "meeting_result", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    },
                    { 
                        header: "Follow-up 1", 
                        key: "followup1", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    },
                    { 
                        header: "Follow-up 2", 
                        key: "followup2", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    },
                    { 
                        header: "Follow-up 3", 
                        key: "followup3", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    },
                    { 
                        header: "Final Status", 
                        key: "final_status", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    },
                    { 
                        header: "Employee Name", 
                        key: "employee_name", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    },
                ];
                
                const headerRow = sheet.getRow(1);
                headerRow.font = { color: { argb: "FFFFFFFF" }, bold: true };
                headerRow.fill = {
                    type: "pattern",
                    pattern: "solid",
                    fgColor: { argb: "FF006400" }
                };
                
                data.forEach((rowData, index) => {
                    const row = sheet.addRow(rowData);
                    
                    row.font = { color: { argb: "FF000000" } };
                    row.alignment = { wrapText: true, vertical: 'top' };
                    row.fill = {
                        type: "pattern",
                        pattern: "solid",
                        fgColor: { argb: index % 2 === 0 ? "FF90EE90" : "FFFFFFFF" }
                    };
                    
                    const longestText = Math.max(
                    ...Object.values(rowData).map(val => (val ? val.toString().length : 0))
                    );
                    
                    row.height = Math.ceil(longestText / 50) * 20;
                });
                
                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: "application/octet-stream" });
                const link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.download = "consultations.xlsx";
                link.click();
            }
        </script>
    </body>
</html>