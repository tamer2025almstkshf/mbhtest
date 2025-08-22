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
        ?>
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['accbankaccs_rperm'] === '1'){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">حسابات البنوك</font></h3>
                            </div>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"></div>
                                <?php
                                    if($row_permcheck['accbankaccs_aperm'] === '1'){
                                ?>
                                <div class="table-header-icons" style="background-image: url('img/arrow.png'); margin-right: 30px;" onclick="addclient()"></div>
                                <?php
                                    }
                                    if($row_permcheck['accbankaccs_aperm'] === '1' || $row_permcheck['accbankaccs_eperm'] === '1'){
                                ?>
                                <div id="addclient-btn" class="modal-overlay" <?php if((isset($_GET['addmore']) && $_GET['addmore'] === '1') || (isset($_GET['edit']) && $_GET['edit'] === '1')){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "تعديل بيانات حساب البنك"; } else { echo 'حساب بنك جديد'; }?></h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "location.href='BankAccs.php';"; } else{ echo 'addclient()'; }?>">&times;</p>
                                            </div>
                                        </div>
                                        <?php
                                            if(isset($_GET['edit']) && $_GET['edit'] === '1'){
                                                $id = $_GET['id'];
                                                $equery = "SELECT * FROM bank_accounts WHERE id='$id'";
                                                $eresult = mysqli_query($conn, $equery);
                                                $erow = mysqli_fetch_array($eresult);
                                            }
                                        ?>
                                        
                                        <form action="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo 'editbankacc.php'; } else{ echo 'bankacc.php'; }?>" method="post" enctype="multipart/form-data" >
                                            <div class="addc-body">
                                                <div class="addc-body-form">
                                                    <div class="input-container">
                                                        <p class="input-parag">اسم البنك<font style="color: #aa0820;">*</font></p>
                                                        <input class="form-input" name="name" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['name']; }?>" type="text" required>
                                                        <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){?>
                                                        <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
                                                        <?php }?>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">رقم الحساب<font style="color: #aa0820;">*</font></p>
                                                        <input class="form-input" name="account_no" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['account_no']; }?>" type="text" required>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">الفرع</p>
                                                        <input class="form-input" name="branch" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['branch']; }?>" type="text">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">المبلغ الاستفتاحي</p>
                                                        <input class="form-input" name="account_amount" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['account_amount']; }?>" type="text">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">تاريخ التسجيل</p>
                                                        <input class="form-input" name="sign_date" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['sign_date']; }?>" type="date">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">ملاحظات</p>
                                                        <textarea class="form-input" name="notes" rows="2"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['notes']; }?></textarea>
                                                    </div>
                                                    <div class="input-container">
                                                        <h4 class="input-parag" style="padding-bottom: 10px;">صورة الايصال</h4>
                                                        <div class="drop-zone" id="dropZone1">
                                                            <input type="file" id="fileInput1" name="receipt_photo" hidden>
                                                            <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                            <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput1').click()">إرفاق مستند</span></p>
                                                        </div>
                                                        <div id="fileList1"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['receipt_photo'] !== ''){ echo '<p>📄'.basename($erow['receipt_photo']).'</p>'; }?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="addc-footer">
                                                <button 
                                                style="cursor: <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['accbankaccs_eperm'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }} else{ if($row_permcheck['accbankaccs_aperm'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }}?>" 
                                                type="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['accbankaccs_eperm'] === '1'){ echo 'submit'; } else{ echo 'button'; }} else{ if($row_permcheck['accbankaccs_aperm'] === '1'){ echo 'submit'; } else{ echo 'button'; }}?>" 
                                                class="form-btn submit-btn">حفظ</button>
                                                <button 
                                                style="cursor: <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['accbankaccs_eperm'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }} else{ if($row_permcheck['accbankaccs_aperm'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }}?>" 
                                                type="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['accbankaccs_eperm'] === '1'){ echo 'submit'; } else{ echo 'button'; }} else{ if($row_permcheck['accbankaccs_aperm'] === '1'){ echo 'submit'; } else{ echo 'button'; }}?>" 
                                                name="submit_back" value="addmore" class="form-btn cancel-btn"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "حفظ و انشاء جديد"; } else{ echo 'حفظ و انشاء آخر'; }?></button>
                                                <button type="button" class="form-btn cancel-btn" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "location.href='BankAccs.php';"; } else{ echo 'addclient()'; }?>">الغاء</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php }?>
                                
                                <div class="modal-overlay" <?php if(isset($_GET['attachments']) && $_GET['attachments'] === '1'){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content" style="margin: auto; align-content: center">
                                        <div class="notes-displayer">
                                            <div class="addc-header">
                                                <h4 class="addc-header-parag" style="margin: auto">مرفقات الحساب البنكي</h4>
                                                <div class="close-button-container">
                                                    <p class="close-button" onclick="location.href='BankAccs.php';" style="display: inline-block">&times;</p>
                                                </div>
                                            </div>
                                            <div class="notes-body" style="padding: 10px; text-align: right;">
                                                <?php
                                                    $id = $_GET['id'];
                                                    $queryatt = "SELECT * FROM bank_accounts WHERE id='$id'";
                                                    $resultatt = mysqli_query($conn, $queryatt);
                                                    $rowatt = mysqli_fetch_array($resultatt);
                                                    
                                                    if(isset($rowatt['receipt_photo']) && $rowatt['receipt_photo'] !== ''){
                                                ?>
                                                <div class="attachment-row">
                                                    <p>الايصال : </p>
                                                    <a href="<?php echo $rowatt['receipt_photo'];?>" onClick="window.open('<?php echo $rowatt['receipt_photo'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                        <?php echo basename($rowatt['receipt_photo']);?>
                                                    </a>
                                                    <?php if($row_permcheck['accbankaccs_eperm'] === '1'){?>
                                                    <div class="perms-check" onclick="location.href='baattachdel.php?id=<?php echo $rowatt['id'];?>&del=receipt_photo';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
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
                            <form action="delbankaccs.php" method="post">
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
                                            <th width="50px"></th>
                                            <th style="position: sticky; right: 0; width: 1%;"><input type="checkbox" name="delall" id="selectAll"></th>
                                            <th width="19%">اسم البنك</th>
                                            <th width="19%">رقم الحساب</th>
                                            <th width="18%">الفرع</th>
                                            <th width="18%">المبلغ الاستفتاحي</th>
                                            <th width="18%">تاريخ التسجيل</th>
                                        </tr>
                                    </thead>
                                    
                                    <?php
                                        $query = "SELECT * FROM bank_accounts ORDER BY id DESC";
                                        $result = mysqli_query($conn, $query);
                                        if($result->num_rows > 0){
                                            while($row = mysqli_fetch_array($result)){
                                    ?>
                                    <tbody id="table1">
                                        <tr class="infotable-body">
                                            <td class="options-td" style="background-color: #fff;" width="50px">
                                                <i class='bx bx-dots-vertical-rounded bx-xs dropbtn' style="cursor: pointer;" onclick="toggleDropdown(event)"></i>
                                                <div class="dropdown">
                                                    <?php if($row_permcheck['accbankaccs_eperm'] === '1'){?>
                                                    <button type="button" onclick="location.href='BankAccs.php?edit=1&id=<?php echo $row['id'];?>';">تعديل</button>
                                                    <?php 
                                                        }
                                                        if($row_permcheck['accbankaccs_dperm'] === '1'){
                                                    ?>
                                                    <button type="button" onclick="location.href='deletebankacc.php?id=<?php echo $row['id'];?>';">حذف</button>
                                                    <?php }?>
                                                    <button type="button" onclick="location.href='BankAccs.php?attachments=1&id=<?php echo $row['id'];?>';">المرفقات</button>
                                                </div>
                                            </td>
                                            <td style="position: sticky; right: 0; width: 40px;"><input type="checkbox" name="CheckedD[]" class="user-checkbox" value="<?php echo $row['id'];?>"></td>
                                            <td>
                                                <?php 
                                                    if(isset($row['name']) && $row['name'] !== ''){
                                                        echo $row['name'];
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if(isset($row['account_no']) && $row['account_no'] !== ''){
                                                        echo $row['account_no'];
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if(isset($row['branch']) && $row['branch'] !== ''){
                                                        echo $row['branch'];
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if(isset($row['account_amount']) && $row['account_amount'] !== ''){
                                                        echo $row['account_amount'];
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if(isset($row['sign_date']) && $row['sign_date'] !== ''){
                                                        echo $row['sign_date'];
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php }}?>
                                </table>
                            </div>
                            
                            <div class="table-footer">
                                <?php if($row_permcheck['accbankaccs_dperm'] === '1'){?>
                                <input name="button2" type="submit" value="حذف" class="delete-selected" >
                                <?php } else{ echo '<p></p>'; }?>
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