<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
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
    <body style="overflow: auto">
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['goaml_rperm'] == 1){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">قائمة goAML</font></h3>
                            </div>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"></div>
                                <?php
                                    if($row_permcheck['goaml_aperm'] == 1){
                                ?>
                                <div class="table-header-icons" style="background-image: url('img/arrow.png'); margin-right: 30px;" onclick="addclient()"></div>
                                <?php
                                    }
                                    if($row_permcheck['goaml_aperm'] == 1){
                                ?>
                                <div id="addclient-btn" class="modal-overlay" <?php if((isset($_GET['addmore']) && $_GET['addmore'] === '1')){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto">مكالمة جديدة</h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="addclient()">&times;</p>
                                            </div>
                                        </div>
                                        <form action="goamladd.php" method="post" enctype="multipart/form-data" >
                                            <div class="addc-body">
                                                <div class="addc-body-form">
                                                    <div class="input-container">
                                                        <h4 class="input-parag" style="padding-bottom: 10px;">المستند<font style="color: #aa0820;">*</font></h4>
                                                        <div class="drop-zone" id="dropZone1">
                                                            <input type="file" id="fileInput1" name="AML_attachment" hidden>
                                                            <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                            <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput1').click()">إرفاق مستند</span></p>
                                                        </div>
                                                        <div id="fileList1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="addc-footer">
                                                <button style="cursor: pointer;" type="submit" class="form-btn submit-btn">حفظ</button>
                                                <button style="cursor: pointer;" type="submit" name="submit_back" value="addmore" class="form-btn cancel-btn">حفظ و انشاء آخر</button>
                                                <button type="button" class="form-btn cancel-btn" onclick="addclient()">الغاء</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                        </div>
                        <div class="table-body">
                            <form action="goamldel.php" method="post">
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
                                            <th style="position: sticky; right: 0;">المستند</th>
                                            <th style="width: 150px;">مُدخل البيانات</th>
                                            <th style="width: 50px;">الاجراءات</th>
                                        </tr>
                                    </thead>
                                    
                                    <?php
                                        $stmt = $conn->prepare("SELECT * FROM goAML ORDER BY id DESC");
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                    ?>
                                    <tbody id="table1">
                                        <tr class="infotable-body">
                                            <td>
                                                <a href="<?php echo safe_output($row['goAML_attachment']);?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;"><?php echo basename(safe_output($row['goAML_attachment']));?></a> 
                                            </td>
                                            <td>
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
                                                        echo safe_output($myname) . '<br>' . safe_output($date);
                                                    }
                                                ?>
                                            </td>
                                            <td style="text-align: -webkit-center">
                                                <div class="perms-check" onclick="location.href='deletegoaml.php?id=<?php echo safe_output($row['id']);?>';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
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