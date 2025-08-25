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
            $myid = $_SESSION['id'];
            $stmt = $conn->prepare("SELECT * FROM user WHERE id=?");
            $stmt->bind_param("i", $myid);
            $stmt->execute();
            $result_permcheck = $stmt->get_result();
            $row_permcheck = mysqli_fetch_array($result_permcheck);

            if(isset($_GET['id']) && $_GET['id'] !== $_SESSION['id'] && ($row_permcheck['emp_rperm'] === '1' || $row_permcheck['emp_aperm'] === '1')){
                $id = (int)$_GET['id'];
            } else{
                $id = $_SESSION['id'];
            }
            $stmt = $conn->prepare("SELECT * FROM user WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultmain = $stmt->get_result();
            $rowmain = mysqli_fetch_array($resultmain);
            
            include_once 'AES256.php';
        ?>
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php include_once 'header.php';?>
                
                <div class="web-page">
                    <?php if(($row_permcheck['emp_rperm'] === '1' && isset($_GET['id']) && $_GET['id'] !== '') || ($row_permcheck['emp_rperm'] !== '1' && (!isset($_GET['id']) || $_GET['id'] === $_SESSION['id']))){?>
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate" style="color:#125386;">رصيد الاجازات <?php if($_GET['type'] === 'yearly'){ echo 'السنوية'; } else if($_GET['type'] === 'sick'){ echo 'المرضية'; } else if($_GET['type'] === 'other'){ echo 'الأخرى'; }?></font></h3>
                            </div>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"></div>
                                <div class="table-header-icons" style="background-image: url('img/arrow.png'); margin-right: 30px;" onclick="addclient()"></div>
                                <div id="addclient-btn" class="modal-overlay">
                                    <div class="modal-content">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto"><?php if($_GET['id'] !== $_SESSION['id'] && $row_permcheck['emp_aperm'] === '1'){ echo 'صرف اجازة للموظف'; } else{ echo 'تقديم طلب اجازة'; }?></h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="addclient()">&times;</p>
                                            </div>
                                        </div>
                                        <form action="balanceadd.php" method="post" enctype="multipart/form-data" >
                                            <input type="hidden" name="id" value="<?php if($_GET['id'] !== $_SESSION['id'] && $row_permcheck['emp_aperm'] === '1'){ echo $_GET['id']; } else{ echo $_SESSION['id']; }?>">
                                            <input type="hidden" name="action" value="<?php if($_GET['id'] !== $_SESSION['id'] && $row_permcheck['emp_aperm'] === '1'){ echo 'give'; } else{ echo 'ask'; }?>">
                                            <input type="hidden" name="ask_date" value="<?php echo date('Y-m-d');?>">
                                            <input type="hidden" name="type" value="<?php echo $_GET['type'];?>">
                                            <div class="addc-body">
                                                <div class="addc-body-form">
                                                    <div class="input-container">
                                                        <p class="input-parag">نوع الاجازة<font style="color: red;">*</font></p>
                                                        <select class="table-header-selector" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;" name="vactype" required>
                                                            <option></option>
                                                            <option value="مدفوعة" <?php if($row['vactype'] === 'مدفوعة'){ echo 'selected'; }?>>مدفوعة</option>
                                                            <option value="غير مدفوعة" <?php if($row['vactype'] === 'غير مدفوعة'){ echo 'selected'; }?>>غير مدفوعة</option>
                                                        </select>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">من تاريخ<font style="color: red;">*</font></p>
                                                        <input type="date" class="form-input" name="vacfrom" required>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">الى تاريخ<font style="color: red;">*</font></p>
                                                        <input type="date" class="form-input" name="vacto" required>
                                                    </div>
                                                    <?php if($_GET['type'] === 'other'){?>
                                                    <div class="input-container">
                                                        <p class="input-parag">الاجازة <font color="red">*</font></p>
                                                        <select class="table-header-selector" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;" name="vac" required>
                                                            <option></option>
                                                            <option value="امومة" <?php if(isset($row['vac']) && $row['vac'] === 'امومة'){ echo 'selected'; }?>>امومة</option>
                                                            <option value="ابوية" <?php if(isset($row['vac']) && $row['vac'] === 'ابوية'){ echo 'selected'; }?>>ابوية</option>
                                                            <option value="دراسية" <?php if(isset($row['vac']) && $row['vac'] === 'دراسية'){ echo 'selected'; }?>>دراسية</option>
                                                            <option value="طارئة" <?php if(isset($row['vac']) && $row['vac'] === 'طارئة'){ echo 'selected'; }?>>طارئة</option>
                                                        </select>
                                                    </div>
                                                    <?php } else if($_GET['type'] === 'yearly'){?>
                                                    <input type="hidden" name="vac" value="سنوية">
                                                    <?php } else if($_GET['type'] === 'sick'){?>
                                                    <input type="hidden" name="vac" value="مرضية">
                                                    <?php }?>
                                                    <div class="input-container">
                                                        <p class="input-parag">الملاحظات</p>
                                                        <textarea class="form-input" rows="2" name="notes"></textarea>
                                                    </div>
                                                    <?php if($_GET['type'] === 'sick'){?>
                                                    <div class="input-container">
                                                        <h4 class="input-parag" style="padding-bottom: 10px;">الشهادة المرضية</h4>
                                                        <div class="drop-zone" id="dropZone1">
                                                            <input type="file" id="fileInput1" name="certificate" hidden>
                                                            <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                            <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput1').click()">إرفاق مستند</span></p>
                                                        </div>
                                                        <div id="fileList1"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['auth'] !== ''){ echo '<p>📄'.basename($erow['auth']).'</p>'; }?></div>
                                                    </div>
                                                    <?php }?>
                                                </div>
                                            </div>
                                            <div class="addc-footer">
                                                <button style="cursor: pointer" type="submit" class="form-btn submit-btn">حفظ</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                        </div>
                        <div class="table-body">
                            <input type="hidden" name="page" value="clients.php">
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
                                        <th>نوع الاجازة</th>
                                        <th>الاجازة</th>
                                        <th>من تاريخ</th>
                                        <th>الى تاريخ</th>
                                        <th>الملاحظات</th>
                                    </tr>
                                </thead>
                                
                                <?php
                                    $where = '';
                                    if($_GET['type'] === 'yearly'){
                                        $where = "type='سنوية' AND";
                                    } else if($_GET['type'] === 'sick'){
                                        $where = "type='مرضية' AND";
                                    } else{
                                        $where = "type!='سنوية' AND type!='مرضية' AND";
                                    }
                                    $stmt = $conn->prepare("SELECT * FROM vacations WHERE $where emp_id=? ORDER BY id DESC");
                                    $stmt->bind_param("i", $id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if($result->num_rows > 0){
                                        while($row = mysqli_fetch_array($result)){
                                ?>
                                <tbody id="table1">
                                    <?php
                                        $ask1 = $row['ask'];
                                        $ask2 = $row['ask2'];
                                    ?>
                                    <tr class="infotable-body">
                                        <td style="background-color: <?php if($ask1 === '2' || $ask2 === '2'){ echo "#ff000040"; } else if($ask1 === '3' && $ask2 === '1'){ echo "#FFFF0040"; } else if($ask1 === '3' && $ask2 === '3'){ echo "#00FF0040"; } else{ echo "#fff"; }?>"><?php echo $row['vactype'];?></td>
                                        <td style="background-color: <?php if($ask1 === '2' || $ask2 === '2'){ echo "#ff000040"; } else if($ask1 === '3' && $ask2 === '1'){ echo "#FFFF0040"; } else if($ask1 === '3' && $ask2 === '3'){ echo "#00FF0040"; } else{ echo "#fff"; }?>"><?php echo $row['type'];?></td>
                                        <td style="background-color: <?php if($ask1 === '2' || $ask2 === '2'){ echo "#ff000040"; } else if($ask1 === '3' && $ask2 === '1'){ echo "#FFFF0040"; } else if($ask1 === '3' && $ask2 === '3'){ echo "#00FF0040"; } else{ echo "#fff"; }?>"><?php echo $row['starting_date'];?></td>
                                        <td style="background-color: <?php if($ask1 === '2' || $ask2 === '2'){ echo "#ff000040"; } else if($ask1 === '3' && $ask2 === '1'){ echo "#FFFF0040"; } else if($ask1 === '3' && $ask2 === '3'){ echo "#00FF0040"; } else{ echo "#fff"; }?>"><?php echo $row['ending_date'];?></td>
                                        <td style="background-color: <?php if($ask1 === '2' || $ask2 === '2'){ echo "#ff000040"; } else if($ask1 === '3' && $ask2 === '1'){ echo "#FFFF0040"; } else if($ask1 === '3' && $ask2 === '3'){ echo "#00FF0040"; } else{ echo "#fff"; }?>"><?php echo $row['notes'];?></td>
                                    </tr>
                                </tbody>
                                <?php }}?>
                            </table>
                        </div>
                        
                        <div class="table-footer">
                            <p></p>
                            <div id="pagination"></div>
                            <div id="pageInfo"></div>
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
        <script src="js/sweetAlerts2.js"></script>
        <script src="js/tablePages.js"></script>
        <script src="js/checkAll.js"></script>
        <script src="js/dropdown.js"></script>
    </body>
</html>