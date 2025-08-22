<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'AES256.php';
    include_once 'safe_output.php';
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
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">طلبات الموكلين</font></h3>
                            </div>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"></div>
                                <div class="table-header-icons" style="background-image: url('img/arrow.png'); margin-right: 30px;" onclick="addclient()"></div>
                                <div id="addclient-btn" class="modal-overlay" <?php if((isset($_GET['addmore']) && $_GET['addmore'] === '1') || (isset($_GET['edit']) && $_GET['edit'] === '1') || (isset($_GET['error']) && $_GET['error'] === 'fid')){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "تعديل بيانات الطلب"; } else { echo 'طلب جديد'; }?></h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "location.href='clientsrequests.php';"; } else{ echo 'addclient()'; }?>">&times;</p>
                                            </div>
                                        </div>
                                        <?php
                                            if(isset($_GET['edit']) && $_GET['edit'] === '1'){
                                                $id = $_GET['id'];
                                                $estmt = $conn->prepare("SELECT * FROM clients_requests WHERE id=?");
                                                $estmt->bind_param("i", $id);
                                                $estmt->execute();
                                                $eresult = $estmt->get_result();
                                                $erow = $eresult->fetch_assoc();
                                            }
                                        ?>
                                        <form action="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo 'creqedit.php'; } else{ echo 'creq.php'; }?>" method="post" enctype="multipart/form-data" >
                                            <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){?>
                                            <input type="hidden" name="id" value="<?php echo safe_output($_GET['id']);?>">
                                            <?php }?>
                                            <div class="addc-body">
                                                <div class="addc-body-form">
                                                    <div class="input-container">
                                                        <p class="input-parag">الموكل<font style="color: #aa0820;">*</font></p>
                                                        <select class="table-header-selector" name="client_id" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                            <option></option>
                                                            <?php 
                                                                $stmtc = $conn->prepare("SELECT * FROM client WHERE client_kind!='' AND arname!='' AND engname!='' AND password!='' AND terror!='1' ORDER BY id DESC");
                                                                $stmtc->execute();
                                                                $resultc = $stmtc->get_result();
                                                                
                                                                if(isset($_REQUEST['client_id'])){
                                                                    $select_cid = $_GET['client_id'];
                                                                }
                                                                
                                                                if($resultc->num_rows > 0){
                                                                    while($rowc = $resultc->fetch_assoc()){
                                                                        $client_id = $rowc['id'];
                                                                        $client_name = $rowc['arname'];
                                                            ?>
                                                            <option value='<?php echo safe_output($client_id);?>' <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['client_id'] == $client_id){ echo 'selected'; }?>><?php echo safe_output($client_id . ' # ' . $client_name);?></option>
                                                            <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <?php 
                                                        if(isset($_GET['client_id']) && $_GET['client_id'] !== ''){
                                                            $fcid = $_GET['client_id'];
                                                            $stmtc = $conn->prepare("SELECT * FROM file WHERE file_client=? OR file_client2=? OR file_client3=? OR file_client4=? OR file_client5=? ORDER BY file_id DESC");
                                                            $stmtc->bind_param("iiiii", $fcid, $fcid, $fcid, $fcid, $fcid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            
                                                            if($resultc->num_rows > 1){
                                                    ?>
                                                    <div class="input-container">
                                                        <p class="input-parag">رقم الملف</p>
                                                        <select class="table-header-selector" name="file_id" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                            <option></option>
                                                            <?php
                                                                while($rowc = $resultc->fetch_assoc()){
                                                                    $file_id = $rowc['file_id'];
                                                                    if($rowc['frelated_place'] === 'الشارقة'){
                                                                        $place = "SHJ";
                                                                    } else if($rowc['frelated_place'] === 'دبي'){
                                                                        $place = "DXB";
                                                                    } else if($rowc['frelated_place'] === 'عجمان'){
                                                                        $place = "AJM";
                                                                    }
                                                            ?>
                                                            <option value='<?php echo safe_output($file_id);?>' <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['file_id'] == $file_id){ echo 'selected'; }?>><?php echo safe_output($place . ' ' . $file_id);?></option>
                                                            <?php
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <?php } else if($resultc->num_rows === '1'){ $rowc=$resultc->fetch_assoc();?> 
                                                    <div class="input-container">
                                                        <p class="input-parag">رقم الملف</p>
                                                        <p class="form-input" name="file_id"><?php echo safe_output($rowc['file_id']);?></p>
                                                        <input class="form-input" type="hidden" name="file_id" value="<?php echo safe_output($rowc['file_id']);?>"> 
                                                    </div>
                                                    <?php }}?>
                                                    <div class="input-container">
                                                        <p class="input-parag">عنوان الطلب<font style="color: #aa0820;">*</font></p>
                                                        <input class="form-input" name="subject" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['subject']); }?>" required>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">تفاصيل الطلب<font style="color: #aa0820;">*</font></p>
                                                        <textarea class="form-input" name="details" rows="2" required><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['details']); }?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="addc-footer">
                                                <button style="cursor: pointer;" type="submit" name="submit_btn" class="form-btn submit-btn">حفظ</button>
                                                <button style="cursor: pointer;" type="submit" name="submit_back" value="addmore" class="form-btn cancel-btn"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "حفظ و انشاء جديد"; } else{ echo 'حفظ و انشاء آخر'; }?></button>
                                                <button type="button" class="form-btn cancel-btn" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "location.href='clientsrequests.php';"; } else{ echo 'addclient()'; }?>">الغاء</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-body">
                            <form action="creq.php" method="post">
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
                                            <th>الموكل</th>
                                            <th width="50px">رقم الملف</th>
                                            <th>عنوان الطلب</th>
                                            <th>تفاصيل الطلب</th>
                                            <th width="100px">تاريخ الطلب</th>
                                            <th>الرد</th>
                                            <th width="50px">الاجراءات</th>
                                        </tr>
                                    </thead>
                                    
                                    <?php
                                        $stmt = $conn->prepare("SELECT * FROM clients_requests ORDER BY id DESC");
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                    ?>
                                    <tbody id="table1">
                                        <tr class="infotable-body">
                                            <td style="font-weight: bold; color: #00F;">
                                                <?php 
                                                    if(isset($row['client_id']) && $row['client_id'] !== ''){
                                                        $cid = $row['client_id'];
                                                        $stmtclient = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtclient->bind_param("i", $cid);
                                                        $stmtclient->execute();
                                                        $resultclient = $stmtclient->get_result();
                                                        if($resultclient->num_rows > 0){
                                                            $rowclient = $resultclient->fetch_assoc();
                                                            $cn = $rowclient['arname'];
                                                            echo safe_output($cn);
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <td width="50px">
                                                <font color="#FF0000"><?php if(isset($row['file_id']) && $row['file_id'] !== ''){echo safe_output($row['file_id']);}?></font>
                                            </td>
                                            <td><?php if(isset($row['subject']) && $row['subject'] !== ''){echo safe_output($row['subject']);}?></td>
                                            <td><?php if(isset($row['details']) && $row['details'] !== ''){echo safe_output($row['details']);}?></td>
                                            <td width="100px"><?php if(isset($row['date']) && $row['date'] !== ''){echo safe_output($row['date']);}?></td>
                                            <td><?php if(isset($row['reply']) && $row['reply'] !== ''){echo safe_output($row['reply']);}?></td>
                                            <td>
                                                <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='clientsrequests.php?edit=1&id=<?php echo safe_output($row['id']);?>';">
                                                <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='deleteclientreq.php?id=<?php echo safe_output($row['id']);?>';">
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
            </div>
        </div>
        
        <script src="js/newWindow.js"></script>
        <script src="js/translate.js"></script>
        <script src="js/toggleSection.js"></script>
        <script src="js/dropfiles.js"></script>
        <script src="js/popups.js"></script>
        <script src="js/randomPassGenerator.js"></script>
        <script src="js/sweetAlerts.js"></script>
        <script src="js/tablePages.js"></script>
        <script src="js/checkAll.js"></script>
        <script src="js/dropdown.js"></script>
    </body>
</html>