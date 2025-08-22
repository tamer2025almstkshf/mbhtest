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
                
                if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){
            }
        ?>
        <div class="advinputs-container" style="height: fit-content; overflow-y: auto">
            <form method="post" action="javascript:void(0);" name="addform" enctype="multipart/form-data" onsubmit="submitForm()">
                <?php
                ?>
                <input name="fid" type="hidden" value="<?php echo safe_output($fid);?>">
                <h2 class="advinputs-h2">حجزت القضية للحكم</h2>
                <div class="advinputs">
                    <div class="input-container">
                        <p class="input-parag"><font class="blue-parag">حتى تاريخ <font color="#FF0000">*</font></font></p>
                        <input class="form-input" type="date" name="booked_todate" required>
                    </div>
                    
                    <div class="input-container">
                        <p class="input-parag"><font class="blue-parag">درجة التقاضي</font> <font color="#ff0000">*</font></p>
                        <select name="degree_id_sess" class="table-header-selector" style="width: 80%; height: fit-content; margin: 10px 0;" required>
                            <option value=""></option>
                            <?php
                                $fiddif = $_GET['fid'];
                                
                                $stmt_ade = $conn->prepare("SELECT * FROM file_degrees WHERE fid=?");
                                $stmt_ade->bind_param("i", $fiddif);
                                $stmt_ade->execute();
                                $result_ade = $stmt_ade->get_result();
                                if($result_ade->num_rows > 0){
                                    while($row_ade = $result_ade->fetch_assoc()){
                            ?>
                            <option value="<?php echo safe_output($row_ade['file_year'].'/'.$row_ade['case_num'].'-'.$row_ade['degree']);?>" <?php echo ($selectedDegree == $row_ade['degree']) ? 'selected' : ''; ?>><?php echo safe_output($row_ade['file_year'].' / '.$row_ade['case_num'].' - '.$row_ade['degree']);?></option>
                            <?php
                                    }
                                }
                                $stmt_ade->close();
                            ?>
                        </select> 
                    </div>
                </div>
                <div class="advinputs">
                    <div class="input-container">
                        <p class="input-parag"><font class="blue-parag">التفاصيل</font></p>
                        <textarea class="form-input" name="booked_detail" rows="2"></textarea> 
                    </div>
                    
                    <?php
                        $stmtread = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                        $stmtread->bind_param("i", $fid);
                        $stmtread->execute();
                        $resultread = $stmtread->get_result();
                        $rowread = $resultread->fetch_assoc();
                        $stmtread->close();
                        
                        if(isset($rowread['file_type']) && $rowread['file_type'] === 'مدني -عمالى' && isset($_GET['deg']) && ($_GET['deg'] === 'ابتدائي' || $_GET['deg'] === 'استئناف')){
                    ?>
                    <div class="input-container">
                        <input type="checkbox" name="amount" value="1"> اكثر من 500,000 درهم
                    </div>
                    <?php } else if(isset($_GET['deg']) && ($_GET['deg'] === 'امر على عريضة' || $_GET['deg'] === 'حجز تحفظي')){?>
                    <div class="input-container">
                        <font class="blue-parag">قرار القاضي </font>
                        <p></p>
                        <input type="radio" name="decission" value="0"> رفض<br>
                        <input type="radio" name="decission" value="1"> قبول
                    </div>
                    <?php } else if(isset($_GET['deg']) && $_GET['deg'] === 'امر اداء'){?>
                    <div class="input-container">
                        <input type="checkbox" name="amount" value="2"> اكثر من 50,000 درهم
                    </div>
                    <?php }?>
                </div>
                <div class="advinputs3">
                    <button type="submit" value="حفظ البيانات" class="green-button">حفظ البيانات</button>
                </div>
                <?php }?>
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
                
                fetch('booked.php', {
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
    </body>
</html>