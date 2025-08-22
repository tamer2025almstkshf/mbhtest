<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
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
        <style>
            .small-page-header {
                background-color: #125483;
                color: #fff;
                padding: 10px;
                font-size: 18px;
                text-align: center;
            }
            .small-page-body {
                margin: 20px;
                border-radius: 5px;
                padding: 20px;
                background-color: #fff;
                color: #125483;
            }
            .small-body-header {
                text-align: center;
                padding: 10px;
                border-bottom: 1px solid #67676725;
            }
            .small-body-container {
                padding: 10px;
            }
            .small-submit-container {
                text-align: center;
                padding: 10px;
                padding-bottom: 0;
            }
        </style>
    </head>
    <body>
        <?php
            $id = $_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM session WHERE session_id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
        ?>
        <div class="small-page-container">
            <div class="small-page-header">
                قيد الـ
                <?php
                    if($row['resume_appeal'] == 1){
                        echo 'استئناف';
                    } else if($row['resume_appeal'] == 2){
                        echo 'طعن';
                    } else if($row['resume_appeal'] == 3){
                        echo 'تظلم';
                    }
                ?>
            </div>
            <div class="small-page-body">
                <h3 class="small-body-header">
                    قيد الـ
                    <?php
                        if($row['resume_appeal'] == 1){
                            echo 'استئناف';
                        } else if($row['resume_appeal'] == 2){
                            echo 'طعن';
                        } else if($row['resume_appeal'] == 3){
                            echo 'تظلم';
                        }
                    ?> 
                    للدعوى رقم : 
                    <?php echo safe_output($row['session_degree']).' - '.safe_output($row['case_num']).' / '.safe_output($row['year']);?>
                    في الملف 
                    <?php echo safe_output($row['session_fid']);?>
                </h3>
                <div class="small-body-container">
                    <form method="post" action="javascript:void(0);" name="addform" enctype="multipart/form-data" onsubmit="submitForm()">
                        <input type="radio" value="signed" name="signcheck" onchange="radiocheck('signed')" required> تم قيد الدعوى <br>
                        <input type="radio" value="unsigned" name="signcheck" onchange="radiocheck('unsigned')" required> لم يتم قيد الدعوى
                        <input type="hidden" name="sid" value="<?php echo safe_output($_GET['id']);?>">
                        <input type="hidden" name="fid" value="<?php echo safe_output($row['session_fid']);?>">
                        <div class="small-body-forms">
                            <div style="display: none" id="signed">
                                <div class="input-container">
                                    <p class="input-parag blue-parag">
                                        الدرجة
                                        <font style="color: #aa0820;">*</font>
                                        <?php if($row_permcheck['selectors_rperm'] == 1){?>
                                        <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="MM_openBrWindow('selector/AddDegree.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=400,height=400')" align="absmiddle"  style="cursor:pointer"/>
                                        <?php }?>
                                    </p>
                                    <select name="degree" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php 
                                            $stmt_deg = $conn->prepare("SELECT * FROM degree");
                                            $stmt_deg->execute();
                                            $result_deg = $stmt_deg->get_result();
                                            if($result_deg->num_rows > 0){
                                                while($row_deg = $result_deg->fetch_assoc()){
                                                    $degree_name = $row_deg['degree_name'];
                                        ?>
                                        <option value='<?php echo safe_output($degree_name);?>'><?php echo safe_output($degree_name);?></option>
                                        <?php
                                                }
                                            }
                                            $stmt_deg->close();
                                        ?>
                                    </select>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag blue-parag">رقم القضية<font style="color: #aa0820;">*</font></p>
                                    <input class="form-input" style="width: 50px" name="case_num" type="number">
                                </div>
                                <div class="input-container">
                                    <p class="input-parag blue-parag">السنة</p>
                                    <input class="form-input" name="year" style="width: 50px" type="number">
                                </div>
                                <div class="input-container">
                                    <p class="input-parag blue-parag">
                                        صفة الموكل
                                        <?php if($row_permcheck['selectors_rperm'] == 1){?>
                                        <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="open('selector/AddClientStatus.php','Pic','width=600 height=700 scrollbars=yes')" align="absmiddle" style="cursor:pointer"/>
                                        <?php }?>
                                    </p>
                                    <select name="client_characteristic" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php 
                                            $stmt_scharachteristics11 = $conn->prepare("SELECT * FROM client_status");
                                            $stmt_scharachteristics11->execute();
                                            $result_scharachteristics11 = $stmt_scharachteristics11->get_result();
                                            if($result_scharachteristics11->num_rows > 0){
                                                while($row_scharachteristics11 = $result_scharachteristics11->fetch_assoc()){
                                                    $stname11 = $row_scharachteristics11['arname'];
                                        ?>
                                        <option value='<?php echo safe_output($stname11)?>'><?php echo safe_output($stname11);?></option>
                                        <?php
                                                }
                                            }
                                            $stmt_scharachteristics11->close();
                                        ?>
                                    </select>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag blue-parag">
                                        صفة الخصم
                                        <?php if($row_permcheck['selectors_rperm'] == 1){?>
                                        <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="open('selector/AddClientStatus.php','Pic','width=600 height=700 scrollbars=yes')" align="absmiddle" style="cursor:pointer"/>
                                        <?php }?>
                                    </p>
                                    <select name="opponent_characteristic" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php 
                                            $stmt_scharachteristics12 = $conn->prepare("SELECT * FROM client_status");
                                            $stmt_scharachteristics12->execute();
                                            $result_scharachteristics12 = $stmt_scharachteristics12->get_result();
                                            if($result_scharachteristics12->num_rows > 0){
                                                while($row_scharachteristics12 = $result_scharachteristics12->fetch_assoc()){
                                                    $stname12 = $row_scharachteristics12['arname'];
                                        ?>
                                        <option value='<?php echo safe_output($stname12);?>'><?php echo safe_output($stname12)?></option>
                                        <?php
                                                }
                                            }
                                            $stmt_scharachteristics12->close();
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div style="display: none" id="unsigned">
                                <div class="input-container">
                                    <p class="input-parag blue-parag">الملاحظة<font style="color: #aa0820;">*</font></p>
                                    <textarea class="form-input" name="note"></textarea>
                                </div>
                            </div>
                            
                            <div class="small-submit-container" id="btn" style="display: none">
                                <input type="submit" value="حفظ البيانات" class="green-button">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            function submitForm() {
                const form = document.forms['addform'];
                const formData = new FormData(form);
                
                fetch('signcheck.php', {
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
        <script>
            function radiocheck(action){
                var display = document.getElementById(action);
                var unsigned = document.getElementById('unsigned');
                var signed = document.getElementById('signed');
                var btn = document.getElementById('btn');
                
                if (unsigned.style.display === 'block'){
                    unsigned.style.display = 'none';
                } if (signed.style.display === 'block'){
                    signed.style.display = 'none';
                }
                
                if (display.style.display === 'none') {
                    display.style.display = 'block';
                }
                
                if(btn.style.display === 'none'){
                    btn.style.display = 'block';
                }
            }
        </script>
    </body>
</html>