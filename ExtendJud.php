<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
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
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1256" />
    </head>
    <body>
        <br>
        <?php
            if(isset($_GET['sid']) && $_GET['sid'] !== ''){
                $sid = $_GET['sid'];
            }
            if(isset($_GET['fid']) && $_GET['fid'] !== ''){
                $fid = $_GET['fid'];
                
                $stmtid = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                $stmtid->bind_param("i", $fid);
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
            }
            
            if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){
        ?>
        <div class="advinputs-container" style="height: fit-content; overflow-y: auto">
            <form method="post" action="javascript:void(0);" name="addform" enctype="multipart/form-data" onsubmit="submitForm()">
                <input type="hidden" name="session_id" value="<?php echo safe_output($_GET['sid']);?>"/>
                <input type="hidden" name="session_fid" value="<?php echo safe_output($_GET['fid']);?>"/>
                <h2 class="advinputs-h2">مد أجل الحكم</h2>
                <div class="advinputs">
                    <div class="input-container">
                        <p class="input-parag"><font class="blue-parag">حتى تاريخ <font color="#FF0000">*</font></font></p>
                        <input class="form-input" type="date" name="booked_todate" required>
                    </div>
                    
                    <div class="input-container">
                        <p class="input-parag"><font class="blue-parag">التفاصيل</font></p>
                        <textarea class="form-input" name="booked_detail" rows="2"></textarea> 
                    </div>
                    
                    <div class="advinputs3">
                        <button type="submit" value="حفظ البيانات" class="green-button">حفظ البيانات</button>
                    </div>
                </div>
            </form>
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
        <script>
            function submitForm() {
                const form = document.forms['addform'];
                const formData = new FormData(form);
                
                fetch('extend.php', {
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