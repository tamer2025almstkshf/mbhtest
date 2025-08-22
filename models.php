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
    <?php
        $stmt = $conn->prepare("SELECT id FROM client WHERE arname='' AND engname='' AND client_kind='' AND email='' 
        AND tel1='' AND fax='' AND tel2='' AND notes='' AND address='' AND country='' AND idno='' 
        AND password='' AND perm='' AND passport='' AND passport_size='' AND auth='' AND auth_size='' AND
        attach1='' AND attach1_size='' AND attach2='' AND attach2_size='' AND attach3='' AND attach3_size=''
        AND attach4='' AND attach4_size='' AND attach5='' AND attach5_size='' AND attach6='' AND attach6_size=''");
        $stmt->execute();
        $result_checkid = $stmt->get_result();
        
        if ($result_checkid) {
            if (mysqli_num_rows($result_checkid) <= 0) {
                $stmt2 = $conn->prepare("INSERT INTO client (arname, engname, client_kind, email, tel1, fax, tel2, notes, 
                address, country, idno, password, perm, passport, passport_size, auth, auth_size, attach1, attach1_size, 
                attach2, attach2_size, attach3, attach3_size, attach4, attach4_size, attach5, attach5_size, attach6, 
                attach6_size) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $empty = '';
                $stmt2->bind_param("sssssssssssssssssssssssssssss", $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty);
                $stmt2->execute();
                $stmt2->close();
                
                if ($stmt2) {
                    $cid = $conn->insert_id;
                } else {
                    header("Location: clients.php");
                    exit;
                }
            } else {
                $row_checkid = $result_checkid->fetch_assoc();
                $cid = $row_checkid['id'];
            }
        }
        $stmt->close();
    ?>
    <body style="overflow: auto; padding-bottom: 50px;">
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['sdocs_rperm'] == 1){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">النماذج</font></h3>
                            </div>
                        </div>
                        <div class="table-body" style="overflow-x: hidden">
                            <div class="moreinps-container">
                                <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                    <span><i class='bx bxs-user' ></i> <p>عرض الأسعار</p></span> 
                                    <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                    <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                </button>
                                <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <form action="prices-disp.php" method="post">
                                            <div class="info-item">
                                                <p>الموكل : </p>
                                                <select class="table-header-selector" name="cid" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;" dir="rtl" required>
                                                    <option></option>
                                                    <?php
                                                        $stmt1 = $conn->prepare("SELECT * FROM client WHERE terror!=?");
                                                        $one = 1;
                                                        $stmt1->bind_param("i", $one);
                                                        $stmt1->execute();
                                                        $result1 = $stmt1->get_result();
                                                        if($result1->num_rows > 0){
                                                            while($row1 = $result1->fetch_assoc()){
                                                    ?>
                                                    <option value="<?php echo $row1['id'];?>"><?php echo $row1['arname'];?></option>
                                                    <?php 
                                                            }
                                                        }
                                                        $stmt1->close();
                                                    ?>
                                                </select>
                                            </div>
                                            <p></p>
                                            <div class="info-item">
                                                <button type="submit" class="h-AdvancedSearch-Btn green-button">اصدار النموذج</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
        <script src="js/tablePages.js"></script>
        <script src="js/checkAll.js"></script>
        <script src="js/dropdown.js"></script>
    </body>
</html>