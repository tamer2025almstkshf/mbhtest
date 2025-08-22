<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'golden_pass.php';
    include_once 'permissions_check.php';
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
        <link href="https://mbhtest.com/css/styles.css" rel="stylesheet">
        <link rel="SHORTCUT ICON" href="img/favicon.ico">
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1256" />
    </head>
    <body>
        <?php if($row_permcheck['levels_eperm'] == 1){?>
        <br>
        <?php 
            if(isset($_GET['id']) && $_GET['id'] !== ''){
                $id = $_GET['id'];
                
                $stmtid = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                $stmtid->bind_param("i", $id);
                $stmtid->execute();
                $resultid = $stmtid->get_result();
                $row_details = $resultid->fetch_assoc();
                $stmtid->close();
                if($admin != 1){
                    if($row_details['secret_folder'] == 1){
                        $empids = $row_details['secret_emps'];
                        $empids = array_filter(array_map('trim', explode(',', $empids)));
                        if (!in_array($_SESSION['id'], $empids)) {
                            exit();
                        }
                    }
                }
            } else{
                exit();
            }
        ?>
        <div class="advinputs-container" style="height: fit-content; overflow-y: auto">
            <form method="post" action="javascript:void(0);" name="addform" enctype="multipart/form-data" onsubmit="submitForm()">
                <div class="advinputs">
                    <div class="input-container">
                        <input type="hidden" name="fid" value="<?php echo safe_output($_GET['id']);?>">
                        <p class="input-parag">
                            <font class="blue-parag">
                                مراحل الاتفاقية 
                                <?php if($row_permcheck['selectors_rperm'] == 1){?>
                                <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="MM_openBrWindow('selector/Levels.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=400')" align="absmiddle" style="cursor:pointer"/>
                                <?php }?>
                            </font>
                        </p>
                        <?php
                            $stmtfid = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                            $stmtfid->bind_param("i", $id);
                            $stmtfid->execute();
                            $resultfid = $stmtfid->get_result();
                            $rowfid = $resultfid->fetch_assoc();
                            $stmtfid->close();
                            
                            $file_levels = $rowfid['file_levels'];
                            $levels_array = array_map('trim', explode(',', $file_levels));
                            
                            $stmtlevs = $conn->prepare("SELECT * FROM levels");
                            $stmtlevs->execute();
                            $resultlevs = $stmtlevs->get_result();
                            
                            if($resultlevs->num_rows > 0){
                                while($rowlevs = $resultlevs->fetch_assoc()){
                                    $level_id = $rowlevs['id'];
                                    $level_name = $rowlevs['level_name'];
                        ?>
                        <input type="checkbox" name="levels[]" class="user-checkbox" value="<?php echo safe_output($level_id); ?>" <?php if (in_array($level_name, $levels_array)) echo 'checked'; ?>> 
                            <p style="display: inline-block; padding: 5px;" class="blue-parag"><?php echo safe_output($level_name); ?></p><br>
                        <?php 
                                }
                            }
                            $stmtlevs->close();
                        ?>
                    </div>
                </div>
                <div class="advinputs">
                    <button type="submit" class="h-AdvancedSearch-Btn green-button">حفظ البيانات</button>
                </div>
            </form>
        </div>
        
        <script src="https://mbhtest.com/js/newWindow.js"></script>
        <script src="https://mbhtest.com/js/translate.js"></script>
        <script src="https://mbhtest.com/js/toggleSection.js"></script>
        <script src="https://mbhtest.com/js/dropfiles.js"></script>
        <script src="https://mbhtest.com/js/popups.js"></script>
        <script src="https://mbhtest.com/js/randomPassGenerator.js"></script>
        <script src="https://mbhtest.com/js/sweetAlerts.js"></script>
        <script src="https://mbhtest.com/js/sweetAlerts2.js"></script>
        <script src="https://mbhtest.com/js/tablePages.js"></script>
        <script src="https://mbhtest.com/js/checkAll.js"></script>
        <script src="https://mbhtest.com/js/dropdown.js"></script>
        <script>
            function submitForm() {
                const form = document.forms['addform'];
                const formData = new FormData(form);
                
                fetch('addfilefees.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        window.close();
                        window.opener.location.reload();
                    } else {
                        alert('Error: حدث خطأ');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error: حدث خطأ');
                });
            }
        </script>
        <?php }?>
    </body>
</html>