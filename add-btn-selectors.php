<!--<?php
    /*
    include_once 'connection.php';
    include_once 'login_check.php'
?>
<style>
    .dropdown2-content {
        display: none;
        position: absolute;
        background-color: white;
        width: 400px;
        box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
        z-index: 10000000;
        border-radius: 5px;
        overflow: hidden;
        border: 1px solid #00000140;
        z-index: 10000;
    }
    
    .dropdown2-content div {
        cursor: pointer;
        color: #676767;
        z-index: 10000000;
        font-weight: bold;
        padding: 5px 15px;
        margin: 5px 0;
        display: grid;
        grid-template-columns: 25px auto;
    }
    
    .dropdown2-content div:hover {
        background-color: #125386;
        z-index: 10000000;
        color: #fff;
    }
    
    .show {
        display: block;
    }
    
    .dropdown2-content div:hover .black-icon {
        display: none;
    }.dropdown2-content div:hover .white-icon {
        display: block;
    }
    
    .white-icon {
        display: none;
    }
</style>
<?php 
    $myid2 = $_SESSION['id'];
    $query_perm = "SELECT * FROM user WHERE id='$myid2'";
    $result_perm = mysqli_query($conn, $query_perm);
    $row_perm = mysqli_fetch_array($result_perm);
    
    $page = basename($_SERVER['PHP_SELF']);
    $paramscheck = '0';
    
    if (!empty($_SERVER['QUERY_STRING'])) {
        $params = $_SERVER['QUERY_STRING'];
        
        if (strpos($params, 'client_form') !== false) {
            parse_str($params, $queryParams);
            unset($queryParams['client_form']);
            $params = http_build_query($queryParams);
        }
        
        if (strpos($params, 'client_type') !== false) {
            parse_str($params, $queryParams);
            unset($queryParams['client_type']);
            $params = http_build_query($queryParams);
        }
        
        if (strpos($params, 'schedule_form') !== false) {
            parse_str($params, $queryParams);
            unset($queryParams['schedule_form']);
            $params = http_build_query($queryParams);
        }
        
        if (strpos($params, 'task_form') !== false) {
            parse_str($params, $queryParams);
            unset($queryParams['task_form']);
            $params = http_build_query($queryParams);
        }
        
        if (strpos($params, 'employee_form') !== false) {
            parse_str($params, $queryParams);
            unset($queryParams['employee_form']);
            $params = http_build_query($queryParams);
        }
        
        if (strpos($params, 'employee_form') !== false) {
            parse_str($params, $queryParams);
            unset($queryParams['employee_form']);
            $params = http_build_query($queryParams);
        }
        
        if (strpos($params, 'call_form') !== false) {
            parse_str($params, $queryParams);
            unset($queryParams['call_form']);
            $params = http_build_query($queryParams);
        }
        
        if (strpos($params, 'consultation_form') !== false) {
            parse_str($params, $queryParams);
            unset($queryParams['consultation_form']);
            $params = http_build_query($queryParams);
        }
        
        if (strpos($params, 'agreement_form') !== false) {
            parse_str($params, $queryParams);
            unset($queryParams['agreement_form']);
            $params = http_build_query($queryParams);
        }
        
        $page = $page.'?'.$params;
        $paramscheck = '1';
    }
?>
<div class="dropdown2">
    <button type="button" onclick="toggleDropdown2()" class="h-AdvancedSearch-Btn green-button dropdown2-btn">اضافة +</button>
    <div id="dropdown2Menu" class="dropdown2-content">
        <div onclick="location.href='<?php echo $page; if($paramscheck === '1'){ echo '&'; } else { echo '?'; }  echo 'client_form=1&client_type=company';?>';">
            <img src="img/buildingB.png" class="black-icon" width="25px" height="25px">
            <img src="img/buildingw.png" class="white-icon" width="25px" height="25px">
            <p style="padding: 0 15px">اضافة شركة جديدة</p>
        </div>
        <div onclick="location.href='<?php echo $page; if($paramscheck === '1'){ echo '&'; } else { echo '?'; }  echo 'client_form=1&client_type=person';?>';">
            <img src="img/personB.png" class="black-icon" width="25px" height="25px">
            <img src="img/personw.png" class="white-icon" width="25px" height="25px">
            <p style="padding: 0 15px">اضافة شخص جديد</p>
        </div>
        <div onclick="location.href='<?php echo $page; if($paramscheck === '1'){ echo '&'; } else { echo '?'; }  echo 'schedule_form=1';?>';">
            <img src="img/meetingB.png" class="black-icon" width="25px" height="25px">
            <img src="img/meetingw.png" class="white-icon" width="25px" height="25px">
            <p style="padding: 0 15px">ادخال موعد اجتماع</p>
        </div>
        <div style="background-color: #67676740; height: 1px; width: 100%; padding: 0;"></div>
        <div onclick="location.href='addcase.php';">
            <img src="img/fileB.png" class="black-icon" width="25px" height="25px">
            <img src="img/filew.png" class="white-icon" width="25px" height="25px">
            <p style="padding: 0 15px">اضافة ملف جديد</p>
        </div>
        <div style="background-color: #67676740; height: 1px; width: 100%; padding: 0;"></div>
        <div onclick="location.href='<?php echo $page; if($paramscheck === '1'){ echo '&'; } else { echo '?'; }  echo 'task_form=1';?>';">
            <img src="img/taskB.png" class="black-icon" width="25px" height="25px">
            <img src="img/taskw.png" class="white-icon" width="25px" height="25px">
            <p style="padding: 0 15px">تكليف موظف بمهمة</p>
        </div>
        <div onclick="location.href='<?php echo $page; if($paramscheck === '1'){ echo '&'; } else { echo '?'; }  echo 'employee_form=1';?>';">
            <img src="img/employeeB.png" class="black-icon" width="25px" height="25px">
            <img src="img/employeew.png" class="white-icon" width="25px" height="25px">
            <p style="padding: 0 15px">اضافة موظف جديد</p>
        </div>
        <div style="background-color: #67676740; height: 1px; width: 100%; padding: 0;"></div>
        <div onclick="location.href='<?php echo $page; if($paramscheck === '1'){ echo '&'; } else { echo '?'; }  echo 'call_form=1';?>';">
            <img src="img/callB.png" class="black-icon" width="25px" height="25px">
            <img src="img/callw.png" class="white-icon" width="25px" height="25px">
            <p style="padding: 0 15px">اضافة مكالمة جديدة</p>
        </div>
        <div onclick="location.href='<?php echo $page; if($paramscheck === '1'){ echo '&'; } else { echo '?'; }  echo 'consultation_form=1';?>';">
            <img src="img/consultationB.png" class="black-icon" width="25px" height="25px">
            <img src="img/consultationw.png" class="white-icon" width="25px" height="25px">
            <p style="padding: 0 15px">اضافة استشارة جديدة</p>
        </div>
        <div onclick="location.href='<?php echo $page; if($paramscheck === '1'){ echo '&'; } else { echo '?'; }  echo 'agreement_form=1';?>';">
            <img src="img/handshakeB.png" class="black-icon" width="25px" height="25px">
            <img src="img/handshakew.png" class="white-icon" width="25px" height="25px">
            <p style="padding: 0 15px">اضافة اتفاقية جديدة</p>
        </div>
    </div>
</div>

<?php if($row_perm['clients_aperm'] === '1' && $_GET['client_form'] === '1'){?>
<div id="addclient-btn" class="modal-overlay" <?php if(isset($_GET['client_form']) && $_GET['client_form'] === '1'){ echo 'style="display: block;"'; }?>>
    <div class="modal-content">
        <div class="addc-header">
            <h4 class="addc-header-parag" style="margin: auto">عميل جديد</h4>
            <div class="close-button-container">
                <p class="close-button" onclick="location.href='<?php echo $page;?>';">&times;</p>
            </div>
        </div>
        <form action="cadd.php" method="post" enctype="multipart/form-data" >
            <input type="hidden" name="cid" value="<?php echo $empid;?>">
            <?php
                $query_checkid = "SELECT id FROM client WHERE arname='' AND engname='' AND client_kind='' AND email='' 
                AND tel1='' AND fax='' AND tel2='' AND notes='' AND address='' AND country='' AND idno='' 
                AND password='' AND perm='' AND passport='' AND passport_size='' AND auth='' AND auth_size='' AND
                attach1='' AND attach1_size='' AND attach2='' AND attach2_size='' AND attach3='' AND attach3_size=''
                AND attach4='' AND attach4_size='' AND attach5='' AND attach5_size='' AND attach6='' AND attach6_size=''";
                $result_checkid = mysqli_query($conn, $query_checkid);
                
                if ($result_checkid) {
                    if (mysqli_num_rows($result_checkid) <= 0) {
                        $query_createid = "INSERT INTO client (arname, engname, client_kind, email, tel1, fax, tel2, notes, 
                        address, country, idno, password, perm, passport, passport_size, auth, auth_size, attach1, attach1_size, 
                        attach2, attach2_size, attach3, attach3_size, attach4, attach4_size, attach5, attach5_size, attach6, 
                        attach6_size) VALUES ('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',
                        '', '', '', '', '', '', '', '')";
                        $result_createid = mysqli_query($conn, $query_createid);
                        
                        if ($result_createid) {
                            $empid = mysqli_insert_id($conn);
                        } else {
                            header("Location: clients.php");
                            exit;
                        }
                    } else {
                        $row_checkid = mysqli_fetch_array($result_checkid);
                        $empid = $row_checkid['id'];
                    }
                }
            ?>
            <input type="hidden" name="page" value="<?php echo $page;?>">
            <div class="addc-body">
                <div class="addc-body-form">
                    <div class="input-container">
                        <p class="input-parag">التصنيف</p>
                        <select class="table-header-selector" name="client_kind" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                            <option value="موكل" >موكل</option>
                            <option value="خصم" >خصم</option>
                            <option value="عناوين هامة" >عناوين هامة</option>
                        </select>
                    </div>
                    <div class="input-container">
                        <p class="input-parag">الإسم<font style="color: #aa0820;">*</font></p>
                        <input class="form-input" name="arname" type="text" required>
                    </div>
                    <div class="input-container">
                        <p class="input-parag">الإسم (E)<font style="color: #aa0820;">*</font></p>
                        <input class="form-input" name="engname" type="text" required>
                    </div>
                    <div class="input-container">
                        <p class="input-parag">الفئة<font style="color: #aa0820;">*</font></p>
                        <select class="table-header-selector" name="client_type" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                            <option value="شخص" <?php if($_GET['client_type'] === 'person'){ echo 'selected'; }?>>شخص</option>
                            <option value="شركة" <?php if($_GET['client_type'] === 'company'){ echo 'selected'; }?>>شركة</option>
                        </select>
                    </div>
                    <div class="input-container">
                        <p class="input-parag">مدرج تحت قائمة الإرهاب</p>
                        <input type="radio" name="terror" value="1" style="padding: 10px 0; margin: 10px 0;"> نعم <br>
                        <input type="radio" name="terror" value="0"> لا
                    </div>
                    <div class="input-container">
                        <p class="input-parag">هاتف متحرك<font style="color: #aa0820;">*</font></p>
                        <input class="form-input" name="tel1" type="text" required>
                    </div>
                    <div class="input-container">
                        <p class="input-parag">الهاتف</p>
                        <input class="form-input" name="tel2" type="text">
                    </div>
                    <div class="input-container">
                        <p class="input-parag">العنوان</p>
                        <textarea class="form-input" name="address" rows="2"></textarea>
                    </div>
                    <div class="input-container">
                        <p class="input-parag">رقم الهوية</p>
                        <input class="form-input" name="idno" type="text">
                    </div>
                    <div class="input-container">
                        <p class="input-parag">البريد الإلكتروني</p>
                        <input class="form-input" name="email" type="email">
                    </div>
                    <div class="input-container">
                        <p class="input-parag">فاكس</p>
                        <input class="form-input" type="text" name="fax">
                    </div>
                    <div class="input-container">
                        <p class="input-parag" rows="2">ملاحظات</p>
                        <textarea class="form-input" name="notes"></textarea>
                    </div>
                    <div class="input-container">
                        <p class="input-parag">الجنسية</p>
                        <?php
                            if(isset($erow['country'])){
                                $selectedCountry = $erow['country'];
                            } else{
                                $selectedCountry = '';
                            }
                        ?>
                        <select class="table-header-selector" name="country" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;" dir="rtl">
                            <option value=""></option>
                            <option value="أثيوبيا" >أثيوبيا</option>
                            <option value="أذربيجان" <?php echo ($selectedCountry == 'أذربيجان') ? 'selected' : ''; ?> >أذربيجان</option>
                            <option value="أراضي القطب الجنوبي" <?php echo ($selectedCountry == 'أراضي القطب الجنوبي') ? 'selected' : ''; ?> >أراضي القطب الجنوبي</option>
                            <option value="أرض المحيط الهندي البريطانية" <?php echo ($selectedCountry == 'أرض المحيط الهندي البريطانية') ? 'selected' : ''; ?> >أرض المحيط الهندي البريطانية</option>
                            <option value="أرمينيا" <?php echo ($selectedCountry == 'أرمينيا') ? 'selected' : ''; ?> >أرمينيا</option>
                            <option value="أروبا" <?php echo ($selectedCountry == 'أروبا') ? 'selected' : ''; ?> >أروبا</option>
                            <option value="أسبانيا" <?php echo ($selectedCountry == 'أسبانيا') ? 'selected' : ''; ?> >أسبانيا</option>
                            <option value="أستراليا" <?php echo ($selectedCountry == 'أستراليا') ? 'selected' : ''; ?> >أستراليا</option>
                            <option value="أستونيا" <?php echo ($selectedCountry == 'أستونيا') ? 'selected' : ''; ?> >أستونيا</option>
                            <option value="أفغانستان" <?php echo ($selectedCountry == 'أفغانستان') ? 'selected' : ''; ?> >أفغانستان</option>
                            <option value="ألبانيا" <?php echo ($selectedCountry == 'ألبانيا') ? 'selected' : ''; ?> >ألبانيا</option>
                            <option value="ألمانيا" <?php echo ($selectedCountry == 'ألمانيا') ? 'selected' : ''; ?> >ألمانيا</option>
                            <option value="أنتيجوا باربدا" <?php echo ($selectedCountry == 'أنتيجوا باربدا') ? 'selected' : ''; ?> >أنتيجوا باربدا</option>
                            <option value="أنجولا" <?php echo ($selectedCountry == 'أنجولا') ? 'selected' : ''; ?> >أنجولا</option>
                            <option value="أنجويلا" <?php echo ($selectedCountry == 'أنجويلا') ? 'selected' : ''; ?> >أنجويلا</option>
                            <option value="أندر سي فيتشر" <?php echo ($selectedCountry == 'أندر سي فيتشر') ? 'selected' : ''; ?> >أندر سي فيتشر</option>
                            <option value="أندورا" <?php echo ($selectedCountry == 'أندورا') ? 'selected' : ''; ?> >أندورا</option>
                            <option value="أندونيسيا" <?php echo ($selectedCountry == 'أندونيسيا') ? 'selected' : ''; ?> >أندونيسيا</option>
                            <option value="أورجواي" <?php echo ($selectedCountry == 'أورجواي') ? 'selected' : ''; ?> >أورجواي</option>
                            <option value="أوزبكستان" <?php echo ($selectedCountry == 'أوزبكستان') ? 'selected' : ''; ?> >أوزبكستان</option>
                            <option value="أوغندا" <?php echo ($selectedCountry == 'أوغندا') ? 'selected' : ''; ?> >أوغندا</option>
                            <option value="أوكرانيا" <?php echo ($selectedCountry == 'أوكرانيا') ? 'selected' : ''; ?> >أوكرانيا</option>
                            <option value="أيرلندا" <?php echo ($selectedCountry == 'أيرلندا') ? 'selected' : ''; ?> >أيرلندا</option>
                            <option value="أيسلندا" <?php echo ($selectedCountry == 'أيسلندا') ? 'selected' : ''; ?> >أيسلندا</option>
                            <option value="إريتريا" <?php echo ($selectedCountry == 'إريتريا') ? 'selected' : ''; ?> >إريتريا</option>
                            <option value="إيران" <?php echo ($selectedCountry == 'إيران') ? 'selected' : ''; ?> >إيران</option>
                            <option value="إيطاليا" <?php echo ($selectedCountry == 'إيطاليا') ? 'selected' : ''; ?> >إيطاليا</option>
                            <option value="الأرجنتين" <?php echo ($selectedCountry == 'الأرجنتين') ? 'selected' : ''; ?> >الأرجنتين</option>
                            <option value="الأردن" <?php echo ($selectedCountry == 'الأردن') ? 'selected' : ''; ?> >الأردن</option>
                            <option value="الإكوادور" <?php echo ($selectedCountry == 'الإكوادور') ? 'selected' : ''; ?> >الإكوادور</option>
                            <option value="الإمارات العربية المتحدة" <?php echo ($selectedCountry == 'الإمارات العربية المتحدة') ? 'selected' : ''; ?> >الإمارات العربية المتحدة</option>
                            <option value="الباهاما" <?php echo ($selectedCountry == 'الباهاما') ? 'selected' : ''; ?> >الباهاما</option>
                            <option value="البحرين" <?php echo ($selectedCountry == 'البحرين') ? 'selected' : ''; ?> >البحرين</option>
                            <option value="البرازيل" <?php echo ($selectedCountry == 'البرازيل') ? 'selected' : ''; ?> >البرازيل</option>
                            <option value="البرتغال" <?php echo ($selectedCountry == 'البرتغال') ? 'selected' : ''; ?> >البرتغال</option>
                            <option value="البلوفر" <?php echo ($selectedCountry == 'البلوفر') ? 'selected' : ''; ?> >البلوفر</option>
                            <option value="البوسنة والهرسك" <?php echo ($selectedCountry == 'البوسنة والهرسك') ? 'selected' : ''; ?> >البوسنة والهرسك</option>
                            <option value="البولينيسيا الفرنسية" <?php echo ($selectedCountry == 'البولينيسيا الفرنسية') ? 'selected' : ''; ?> >البولينيسيا الفرنسية</option>
                            <option value="الجابون" <?php echo ($selectedCountry == 'الجابون') ? 'selected' : ''; ?> >الجابون</option>
                            <option value="الجاكت" <?php echo ($selectedCountry == 'الجاكت') ? 'selected' : ''; ?> >الجاكت</option>
                            <option value="الجزائر" <?php echo ($selectedCountry == 'الجزائر') ? 'selected' : ''; ?> >الجزائر</option>
                            <option value="الجزر البريطانية" <?php echo ($selectedCountry == 'الجزر البريطانية') ? 'selected' : ''; ?> >الجزر البريطانية</option>
                            <option value="الدانمارك" <?php echo ($selectedCountry == 'الدانمارك') ? 'selected' : ''; ?> >الدانمارك</option>
                            <option value="السلفادور" <?php echo ($selectedCountry == 'السلفادور') ? 'selected' : ''; ?> >السلفادور</option>
                            <option value="السنغال" <?php echo ($selectedCountry == 'السنغال') ? 'selected' : ''; ?> >السنغال</option>
                            <option value="السودان" <?php echo ($selectedCountry == 'السودان') ? 'selected' : ''; ?> >السودان</option>
                            <option value="السويد" <?php echo ($selectedCountry == 'السويد') ? 'selected' : ''; ?> >السويد</option>
                            <option value="الصومال" <?php echo ($selectedCountry == 'الصومال') ? 'selected' : ''; ?> >الصومال</option>
                            <option value="الصين" <?php echo ($selectedCountry == 'الصين') ? 'selected' : ''; ?> >الصين</option>
                            <option value="الضفة الغربية" <?php echo ($selectedCountry == 'الضفة الغربية') ? 'selected' : ''; ?> >الضفة الغربية</option>
                            <option value="العراق" <?php echo ($selectedCountry == 'العراق') ? 'selected' : ''; ?> >العراق</option>
                            <option value="الفاتيكان" <?php echo ($selectedCountry == 'الفاتيكان') ? 'selected' : ''; ?> >الفاتيكان</option>
                            <option value="الفلبين" <?php echo ($selectedCountry == 'الفلبين') ? 'selected' : ''; ?> >الفلبين</option>
                            <option value="القارة القطبية الجنوبية" <?php echo ($selectedCountry == 'القارة القطبية الجنوبية') ? 'selected' : ''; ?> >القارة القطبية الجنوبية</option>
                            <option value="الكاميرون" <?php echo ($selectedCountry == 'الكاميرون') ? 'selected' : ''; ?> >الكاميرون</option>
                            <option value="الكونغو" <?php echo ($selectedCountry == 'الكونغو') ? 'selected' : ''; ?> >الكونغو</option>
                            <option value="الكويت" <?php echo ($selectedCountry == 'الكويت') ? 'selected' : ''; ?> >الكويت</option>
                            <option value="المارتينيخ" <?php echo ($selectedCountry == 'المارتينيخ') ? 'selected' : ''; ?> >المارتينيخ</option>
                            <option value="المالديف" <?php echo ($selectedCountry == 'المالديف') ? 'selected' : ''; ?> >المالديف</option>
                            <option value="المجر" <?php echo ($selectedCountry == 'المجر') ? 'selected' : ''; ?> >المجر</option>
                            <option value="المحيطات" <?php echo ($selectedCountry == 'المحيطات') ? 'selected' : ''; ?> >المحيطات</option>
                            <option value="المغرب" <?php echo ($selectedCountry == 'المغرب') ? 'selected' : ''; ?> >المغرب</option>
                            <option value="المكسيك" <?php echo ($selectedCountry == 'المكسيك') ? 'selected' : ''; ?> >المكسيك</option>
                            <option value="المملكة العربية السعودية" <?php echo ($selectedCountry == 'المملكة العربية السعودية') ? 'selected' : ''; ?> >المملكة العربية السعودية</option>
                            <option value="المملكة المتحدة" <?php echo ($selectedCountry == 'المملكة المتحدة') ? 'selected' : ''; ?> >المملكة المتحدة</option>
                            <option value="النرويج" <?php echo ($selectedCountry == 'النرويج') ? 'selected' : ''; ?> >النرويج</option>
                            <option value="النمسا" <?php echo ($selectedCountry == 'النمسا') ? 'selected' : ''; ?> >النمسا</option>
                            <option value="النيجر" <?php echo ($selectedCountry == 'النيجر') ? 'selected' : ''; ?> >النيجر</option>
                            <option value="الهند" <?php echo ($selectedCountry == 'الهند') ? 'selected' : ''; ?> >الهند</option>
                            <option value="الهند باساس دي" <?php echo ($selectedCountry == 'الهند باساس دي') ? 'selected' : ''; ?> >الهند باساس دي</option>
                            <option value="الولايات المتحدة الأمريكية" <?php echo ($selectedCountry == 'الولايات المتحدة الأمريكية') ? 'selected' : ''; ?> >الولايات المتحدة الأمريكية</option>
                            <option value="اليابان" <?php echo ($selectedCountry == 'اليابان') ? 'selected' : ''; ?> >اليابان</option>
                            <option value="اليمن" <?php echo ($selectedCountry == 'اليمن') ? 'selected' : ''; ?> >اليمن</option>
                            <option value="اليونان" <?php echo ($selectedCountry == 'اليونان') ? 'selected' : ''; ?> >اليونان</option>
                            <option value="باراجواي" <?php echo ($selectedCountry == 'باراجواي') ? 'selected' : ''; ?> >باراجواي</option>
                            <option value="باربادوس" <?php echo ($selectedCountry == 'باربادوس') ? 'selected' : ''; ?> >باربادوس</option>
                            <option value="باكستان" <?php echo ($selectedCountry == 'باكستان') ? 'selected' : ''; ?> >باكستان</option>
                            <option value="بالو" <?php echo ($selectedCountry == 'بالو') ? 'selected' : ''; ?> >بالو</option>
                            <option value="برمودا" <?php echo ($selectedCountry == 'برمودا') ? 'selected' : ''; ?> >برمودا</option>
                            <option value="بروناي" <?php echo ($selectedCountry == 'بروناي') ? 'selected' : ''; ?> >بروناي</option>
                            <option value="بلجيكا" <?php echo ($selectedCountry == 'بلجيكا') ? 'selected' : ''; ?> >بلجيكا</option>
                            <option value="بلغاريا" <?php echo ($selectedCountry == 'بلغاريا') ? 'selected' : ''; ?> >بلغاريا</option>
                            <option value="بنجلاديش" <?php echo ($selectedCountry == 'بنجلاديش') ? 'selected' : ''; ?> >بنجلاديش</option>
                            <option value="بنما" <?php echo ($selectedCountry == 'بنما') ? 'selected' : ''; ?> >بنما</option>
                            <option value="بنين" <?php echo ($selectedCountry == 'بنين') ? 'selected' : ''; ?> >بنين</option>
                            <option value="بوتان" <?php echo ($selectedCountry == 'بوتان') ? 'selected' : ''; ?> >بوتان</option>
                            <option value="بوتسوانا" <?php echo ($selectedCountry == 'بوتسوانا') ? 'selected' : ''; ?> >بوتسوانا</option>
                            <option value="بورتوريكو" <?php echo ($selectedCountry == 'بورتوريكو') ? 'selected' : ''; ?> >بورتوريكو</option>
                            <option value="بوركينا فاسو" <?php echo ($selectedCountry == 'بوركينا فاسو') ? 'selected' : ''; ?> >بوركينا فاسو</option>
                            <option value="بورما" <?php echo ($selectedCountry == 'بورما') ? 'selected' : ''; ?> >بورما</option>
                            <option value="بوروندي" <?php echo ($selectedCountry == 'بوروندي') ? 'selected' : ''; ?> >بوروندي</option>
                            <option value="بولندا" <?php echo ($selectedCountry == 'بولندا') ? 'selected' : ''; ?> >بولندا</option>
                            <option value="بوليفيا" <?php echo ($selectedCountry == 'بوليفيا') ? 'selected' : ''; ?> >بوليفيا</option>
                            <option value="بيرو" <?php echo ($selectedCountry == 'بيرو') ? 'selected' : ''; ?> >بيرو</option>
                            <option value="بيليز" <?php echo ($selectedCountry == 'بيليز') ? 'selected' : ''; ?> >بيليز</option>
                            <option value="تايلاند" <?php echo ($selectedCountry == 'تايلاند') ? 'selected' : ''; ?> >تايلاند</option>
                            <option value="تايوان" <?php echo ($selectedCountry == 'تايوان') ? 'selected' : ''; ?> >تايوان</option>
                            <option value="تراينيداد توباجو" <?php echo ($selectedCountry == 'تراينيداد توباجو') ? 'selected' : ''; ?> >تراينيداد توباجو</option>
                            <option value="تركيا" <?php echo ($selectedCountry == 'تركيا') ? 'selected' : ''; ?> >تركيا</option>
                            <option value="تشاد" <?php echo ($selectedCountry == 'تشاد') ? 'selected' : ''; ?> >تشاد</option>
                            <option value="تنزانيا" <?php echo ($selectedCountry == 'تنزانيا') ? 'selected' : ''; ?> >تنزانيا</option>
                            <option value="توجو" <?php echo ($selectedCountry == 'توجو') ? 'selected' : ''; ?> >توجو</option>
                            <option value="توركمنستان" <?php echo ($selectedCountry == 'توركمنستان') ? 'selected' : ''; ?> >توركمنستان</option>
                            <option value="توفالو" <?php echo ($selectedCountry == 'توفالو') ? 'selected' : ''; ?> >توفالو</option>
                            <option value="توكيلاو" <?php echo ($selectedCountry == 'توكيلاو') ? 'selected' : ''; ?> >توكيلاو</option>
                            <option value="تونجا" <?php echo ($selectedCountry == 'تونجا') ? 'selected' : ''; ?> >تونجا</option>
                            <option value="تونس" <?php echo ($selectedCountry == 'تونس') ? 'selected' : ''; ?> >تونس</option>
                            <option value="تيمور الشرقية" <?php echo ($selectedCountry == 'تيمور الشرقية') ? 'selected' : ''; ?> >تيمور الشرقية</option>
                            <option value="جامبيا" <?php echo ($selectedCountry == 'جامبيا') ? 'selected' : ''; ?> >جامبيا</option>
                            <option value="جاميكا" <?php echo ($selectedCountry == 'جاميكا') ? 'selected' : ''; ?> >جاميكا</option>
                            <option value="جان ميين" <?php echo ($selectedCountry == 'جان ميين') ? 'selected' : ''; ?> >جان ميين</option>
                            <option value="جبل طارق" <?php echo ($selectedCountry == 'جبل طارق') ? 'selected' : ''; ?> >جبل طارق</option>
                            <option value="جرينادا" <?php echo ($selectedCountry == 'جرينادا') ? 'selected' : ''; ?> >جرينادا</option>
                            <option value="جرينلاند" <?php echo ($selectedCountry == 'جرينلاند') ? 'selected' : ''; ?> >جرينلاند</option>
                            <option value="جزر أشمور كارتير" <?php echo ($selectedCountry == 'جزر أشمور كارتير') ? 'selected' : ''; ?> >جزر أشمور كارتير</option>
                            <option value="جزر الأنتيل الهولندية" <?php echo ($selectedCountry == 'جزر الأنتيل الهولندية') ? 'selected' : ''; ?> >جزر الأنتيل الهولندية</option>
                            <option value="جزر البحر المرجانية" <?php echo ($selectedCountry == 'جزر البحر المرجانية') ? 'selected' : ''; ?> >جزر البحر المرجانية</option>
                            <option value="جزر القمر" <?php echo ($selectedCountry == 'جزر القمر') ? 'selected' : ''; ?> >جزر القمر</option>
                            <option value="جزر الكوك" <?php echo ($selectedCountry == 'جزر الكوك') ? 'selected' : ''; ?> >جزر الكوك</option>
                            <option value="جزر باراسيل" <?php echo ($selectedCountry == 'جزر باراسيل') ? 'selected' : ''; ?> >جزر باراسيل</option>
                            <option value="جزر بيتكيرن" <?php echo ($selectedCountry == 'جزر بيتكيرن') ? 'selected' : ''; ?> >جزر بيتكيرن</option>
                            <option value="جزر جلوريوسو" <?php echo ($selectedCountry == 'جزر جلوريوسو') ? 'selected' : ''; ?> >جزر جلوريوسو</option>
                            <option value="جزر جوز الهند" <?php echo ($selectedCountry == 'جزر جوز الهند') ? 'selected' : ''; ?> >جزر جوز الهند</option>
                            <option value="جزر سبراتلي" <?php echo ($selectedCountry == 'جزر سبراتلي') ? 'selected' : ''; ?> >جزر سبراتلي</option>
                            <option value="جزر سولومون" <?php echo ($selectedCountry == 'جزر سولومون') ? 'selected' : ''; ?> >جزر سولومون</option>
                            <option value="جزر فارو" <?php echo ($selectedCountry == 'جزر فارو') ? 'selected' : ''; ?> >جزر فارو</option>
                            <option value="جزر فوكلاند" <?php echo ($selectedCountry == 'جزر فوكلاند') ? 'selected' : ''; ?> >جزر فوكلاند</option>
                            <option value="جزر كيكوس التركية" <?php echo ($selectedCountry == 'جزر كيكوس التركية') ? 'selected' : ''; ?> >جزر كيكوس التركية</option>
                            <option value="جزر مارشال" <?php echo ($selectedCountry == 'جزر مارشال') ? 'selected' : ''; ?> >جزر مارشال</option>
                            <option value="جزر مكدونالد" <?php echo ($selectedCountry == 'جزر مكدونالد') ? 'selected' : ''; ?> >جزر مكدونالد</option>
                            <option value="جزرالكايمان" <?php echo ($selectedCountry == 'جزرالكايمان') ? 'selected' : ''; ?> >جزرالكايمان</option>
                            <option value="جزيرة الأوروبا" <?php echo ($selectedCountry == 'جزيرة الأوروبا') ? 'selected' : ''; ?> >جزيرة الأوروبا</option>
                            <option value="جزيرة الرجل" <?php echo ($selectedCountry == 'جزيرة الرجل') ? 'selected' : ''; ?> >جزيرة الرجل</option>
                            <option value="جزيرة الكريستمز" <?php echo ($selectedCountry == 'جزيرة الكريستمز') ? 'selected' : ''; ?> >جزيرة الكريستمز</option>
                            <option value="جزيرة بوفيت" <?php echo ($selectedCountry == 'جزيرة بوفيت') ? 'selected' : ''; ?> >جزيرة بوفيت</option>
                            <option value="جزيرة تروميلين" <?php echo ($selectedCountry == 'جزيرة تروميلين') ? 'selected' : ''; ?> >جزيرة تروميلين</option>
                            <option value="جزيرة جوان دونوفا" <?php echo ($selectedCountry == 'جزيرة جوان دونوفا') ? 'selected' : ''; ?> >جزيرة جوان دونوفا</option>
                            <option value="جزيرة كليبيرتون" <?php echo ($selectedCountry == 'جزيرة كليبيرتون') ? 'selected' : ''; ?> >جزيرة كليبيرتون</option>
                            <option value="جزيرة نورفولك" <?php echo ($selectedCountry == 'جزيرة نورفولك') ? 'selected' : ''; ?> >جزيرة نورفولك</option>
                            <option value="جمهورية إفريقيا الوسطى" <?php echo ($selectedCountry == 'جمهورية إفريقيا الوسطى') ? 'selected' : ''; ?> >جمهورية إفريقيا الوسطى</option>
                            <option value="جمهورية التشيك" <?php echo ($selectedCountry == 'جمهورية التشيك') ? 'selected' : ''; ?> >جمهورية التشيك</option>
                            <option value="جمهورية الدومينكان" <?php echo ($selectedCountry == 'جمهورية الدومينكان') ? 'selected' : ''; ?> >جمهورية الدومينكان</option>
                            <option value="جمهورية الكونغو الديمقراطية" <?php echo ($selectedCountry == 'جمهورية الكونغو الديمقراطية') ? 'selected' : ''; ?> >جمهورية الكونغو الديمقراطية</option>
                            <option value="جنوب أفريقيا" <?php echo ($selectedCountry == 'جنوب أفريقيا') ? 'selected' : ''; ?> >جنوب أفريقيا</option>
                            <option value="جوادلوب" <?php echo ($selectedCountry == 'جوادلوب') ? 'selected' : ''; ?> >جوادلوب</option>
                            <option value="جورجيا" <?php echo ($selectedCountry == 'جورجيا') ? 'selected' : ''; ?> >جورجيا</option>
                            <option value="جورجيا الجنوبية" <?php echo ($selectedCountry == 'جورجيا الجنوبية') ? 'selected' : ''; ?> >جورجيا الجنوبية</option>
                            <option value="جويانا" <?php echo ($selectedCountry == 'جويانا') ? 'selected' : ''; ?> >جويانا</option>
                            <option value="جيبوتي" <?php echo ($selectedCountry == 'جيبوتي') ? 'selected' : ''; ?> >جيبوتي</option>
                            <option value="دومينيكا" <?php echo ($selectedCountry == 'دومينيكا') ? 'selected' : ''; ?> >دومينيكا</option>
                            <option value="رواندا" <?php echo ($selectedCountry == 'رواندا') ? 'selected' : ''; ?> >رواندا</option>
                            <option value="روسيا" <?php echo ($selectedCountry == 'روسيا') ? 'selected' : ''; ?> >روسيا</option>
                            <option value="روسيا البيضاء" <?php echo ($selectedCountry == 'روسيا البيضاء') ? 'selected' : ''; ?> >روسيا البيضاء</option>
                            <option value="رومانيا" <?php echo ($selectedCountry == 'رومانيا') ? 'selected' : ''; ?> >رومانيا</option>
                            <option value="ريونيون" <?php echo ($selectedCountry == 'ريونيون') ? 'selected' : ''; ?> >ريونيون</option>
                            <option value="زامبيا" <?php echo ($selectedCountry == 'زامبيا') ? 'selected' : ''; ?> >زامبيا</option>
                            <option value="زيمبابوي" <?php echo ($selectedCountry == 'زيمبابوي') ? 'selected' : ''; ?> >زيمبابوي</option>
                            <option value="سامو" <?php echo ($selectedCountry == 'سامو') ? 'selected' : ''; ?> >سامو</option>
                            <option value="سان مارينو" <?php echo ($selectedCountry == 'سان مارينو') ? 'selected' : ''; ?> >سان مارينو</option>
                            <option value="سانت بيير ميكيلون" <?php echo ($selectedCountry == 'سانت بيير ميكيلون') ? 'selected' : ''; ?> >سانت بيير ميكيلون</option>
                            <option value="سانت فينسينت" <?php echo ($selectedCountry == 'سانت فينسينت') ? 'selected' : ''; ?> >سانت فينسينت</option>
                            <option value="سانت كيتس نيفيس" <?php echo ($selectedCountry == 'سانت كيتس نيفيس') ? 'selected' : ''; ?> >سانت كيتس نيفيس</option>
                            <option value="سانت لوتشيا" <?php echo ($selectedCountry == 'سانت لوتشيا') ? 'selected' : ''; ?> >سانت لوتشيا</option>
                            <option value="سانت هيلينا" <?php echo ($selectedCountry == 'سانت هيلينا') ? 'selected' : ''; ?> >سانت هيلينا</option>
                            <option value="ساو توم" <?php echo ($selectedCountry == 'ساو توم') ? 'selected' : ''; ?> >ساو توم</option>
                            <option value="سريلانكا" <?php echo ($selectedCountry == 'سريلانكا') ? 'selected' : ''; ?> >سريلانكا</option>
                            <option value="سفالبارد" <?php echo ($selectedCountry == 'سفالبارد') ? 'selected' : ''; ?> >سفالبارد</option>
                            <option value="سلوفاكيا" <?php echo ($selectedCountry == 'سلوفاكيا') ? 'selected' : ''; ?> >سلوفاكيا</option>
                            <option value="سلوفانيا" <?php echo ($selectedCountry == 'سلوفانيا') ? 'selected' : ''; ?> >سلوفانيا</option>
                            <option value="سنغافورة" <?php echo ($selectedCountry == 'سنغافورة') ? 'selected' : ''; ?> >سنغافورة</option>
                            <option value="سوازيلاند" <?php echo ($selectedCountry == 'سوازيلاند') ? 'selected' : ''; ?> >سوازيلاند</option>
                            <option value="سوريا" <?php echo ($selectedCountry == 'سوريا') ? 'selected' : ''; ?> >سوريا</option>
                            <option value="سورينام" <?php echo ($selectedCountry == 'سورينام') ? 'selected' : ''; ?> >سورينام</option>
                            <option value="سويسرا" <?php echo ($selectedCountry == 'سويسرا') ? 'selected' : ''; ?> >سويسرا</option>
                            <option value="سيراليون" <?php echo ($selectedCountry == 'سيراليون') ? 'selected' : ''; ?> >سيراليون</option>
                            <option value="سيشل" <?php echo ($selectedCountry == 'سيشل') ? 'selected' : ''; ?> >سيشل</option>
                            <option value="شيلي" <?php echo ($selectedCountry == 'شيلي') ? 'selected' : ''; ?> >شيلي</option>
                            <option value="طاجكستان" <?php echo ($selectedCountry == 'طاجكستان') ? 'selected' : ''; ?> >طاجكستان</option>
                            <option value="عمان" <?php echo ($selectedCountry == 'عمان') ? 'selected' : ''; ?> >عمان</option>
                            <option value="غانا" <?php echo ($selectedCountry == 'غانا') ? 'selected' : ''; ?> >غانا</option>
                            <option value="غواتيمالا" <?php echo ($selectedCountry == 'غواتيمالا') ? 'selected' : ''; ?> >غواتيمالا</option>
                            <option value="غينيا" <?php echo ($selectedCountry == 'غينيا') ? 'selected' : ''; ?> >غينيا</option>
                            <option value="غينيا الاستوائية" <?php echo ($selectedCountry == 'غينيا الاستوائية') ? 'selected' : ''; ?> >غينيا الاستوائية</option>
                            <option value="غينيا الجديدة" <?php echo ($selectedCountry == 'غينيا الجديدة') ? 'selected' : ''; ?> >غينيا الجديدة</option>
                            <option value="غينيا الفرنسية" <?php echo ($selectedCountry == 'غينيا الفرنسية') ? 'selected' : ''; ?> >غينيا الفرنسية</option>
                            <option value="فانوتو" <?php echo ($selectedCountry == 'فانوتو') ? 'selected' : ''; ?> >فانوتو</option>
                            <option value="فرنسا" <?php echo ($selectedCountry == 'فرنسا') ? 'selected' : ''; ?> >فرنسا</option>
                            <option value="فلسطين" <?php echo ($selectedCountry == 'فلسطين') ? 'selected' : ''; ?> >فلسطين</option>
                            <option value="فنزويلا" <?php echo ($selectedCountry == 'فنزويلا') ? 'selected' : ''; ?> >فنزويلا</option>
                            <option value="فنلندا" <?php echo ($selectedCountry == 'فنلندا') ? 'selected' : ''; ?> >فنلندا</option>
                            <option value="فيتنام" <?php echo ($selectedCountry == 'فيتنام') ? 'selected' : ''; ?> >فيتنام</option>
                            <option value="فيجي" <?php echo ($selectedCountry == 'فيجي') ? 'selected' : ''; ?> >فيجي</option>
                            <option value="قبرص" <?php echo ($selectedCountry == 'قبرص') ? 'selected' : ''; ?> >قبرص</option>
                            <option value="قطر" <?php echo ($selectedCountry == 'قطر') ? 'selected' : ''; ?> >قطر</option>
                            <option value="كازاكستان" <?php echo ($selectedCountry == 'كازاكستان') ? 'selected' : ''; ?> >كازاكستان</option>
                            <option value="كالدونيا الجديدة" <?php echo ($selectedCountry == 'كالدونيا الجديدة') ? 'selected' : ''; ?> >كالدونيا الجديدة</option>
                            <option value="كامبوديا" <?php echo ($selectedCountry == 'كامبوديا') ? 'selected' : ''; ?> >كامبوديا</option>
                            <option value="كرواتيا" <?php echo ($selectedCountry == 'كرواتيا') ? 'selected' : ''; ?> >كرواتيا</option>
                            <option value="كندا" <?php echo ($selectedCountry == 'كندا') ? 'selected' : ''; ?> >كندا</option>
                            <option value="كوبا" <?php echo ($selectedCountry == 'كوبا') ? 'selected' : ''; ?> >كوبا</option>
                            <option value="كوتي دلفوير" <?php echo ($selectedCountry == 'كوتي دلفوير') ? 'selected' : ''; ?> >كوتي دلفوير</option>
                            <option value="كورجستان" <?php echo ($selectedCountry == 'كورجستان') ? 'selected' : ''; ?> >كورجستان</option>
                            <option value="كوريا الجنوبية" <?php echo ($selectedCountry == 'كوريا الجنوبية') ? 'selected' : ''; ?> >كوريا الجنوبية</option>
                            <option value="كوريا الشمالية" <?php echo ($selectedCountry == 'كوريا الشمالية') ? 'selected' : ''; ?> >كوريا الشمالية</option>
                            <option value="كوستا ريكا" <?php echo ($selectedCountry == 'كوستا ريكا') ? 'selected' : ''; ?> >كوستا ريكا</option>
                            <option value="كولومبيا" <?php echo ($selectedCountry == 'كولومبيا') ? 'selected' : ''; ?> >كولومبيا</option>
                            <option value="كيب فيردي" <?php echo ($selectedCountry == 'كيب فيردي') ? 'selected' : ''; ?> >كيب فيردي</option>
                            <option value="كيريباتي" <?php echo ($selectedCountry == 'كيريباتي') ? 'selected' : ''; ?> >كيريباتي</option>
                            <option value="كينيا" <?php echo ($selectedCountry == 'كينيا') ? 'selected' : ''; ?> >كينيا</option>
                            <option value="لاتفيا" <?php echo ($selectedCountry == 'لاتفيا') ? 'selected' : ''; ?> >لاتفيا</option>
                            <option value="لاو" <?php echo ($selectedCountry == 'لاو') ? 'selected' : ''; ?> >لاو</option>
                            <option value="لبنان" <?php echo ($selectedCountry == 'لبنان') ? 'selected' : ''; ?> >لبنان</option>
                            <option value="لوكسمبورج" <?php echo ($selectedCountry == 'لوكسمبورج') ? 'selected' : ''; ?> >لوكسمبورج</option>
                            <option value="ليبيا" <?php echo ($selectedCountry == 'ليبيا') ? 'selected' : ''; ?> >ليبيا</option>
                            <option value="ليبيريا" <?php echo ($selectedCountry == 'ليبيريا') ? 'selected' : ''; ?> >ليبيريا</option>
                            <option value="ليتوانيا" <?php echo ($selectedCountry == 'ليتوانيا') ? 'selected' : ''; ?> >ليتوانيا</option>
                            <option value="ليختنشتين" <?php echo ($selectedCountry == 'ليختنشتين') ? 'selected' : ''; ?> >ليختنشتين</option>
                            <option value="ليسوتو" <?php echo ($selectedCountry == 'ليسوتو') ? 'selected' : ''; ?> >ليسوتو</option>
                            <option value="ماكاو" <?php echo ($selectedCountry == 'ماكاو') ? 'selected' : ''; ?> >ماكاو</option>
                            <option value="مالاوي" <?php echo ($selectedCountry == 'مالاوي') ? 'selected' : ''; ?> >مالاوي</option>
                            <option value="مالطا" <?php echo ($selectedCountry == 'مالطا') ? 'selected' : ''; ?> >مالطا</option>
                            <option value="مالي" <?php echo ($selectedCountry == 'مالي') ? 'selected' : ''; ?> >مالي</option>
                            <option value="ماليزيا" <?php echo ($selectedCountry == 'ماليزيا') ? 'selected' : ''; ?> >ماليزيا</option>
                            <option value="مدغشقر" <?php echo ($selectedCountry == 'مدغشقر') ? 'selected' : ''; ?> >مدغشقر</option>
                            <option value="مصر" <?php echo ($selectedCountry == 'مصر') ? 'selected' : ''; ?> >مصر</option>
                            <option value="مقدونيا" <?php echo ($selectedCountry == 'مقدونيا') ? 'selected' : ''; ?> >مقدونيا</option>
                            <option value="منغوليا" <?php echo ($selectedCountry == 'منغوليا') ? 'selected' : ''; ?> >منغوليا</option>
                            <option value="موريتانيا" <?php echo ($selectedCountry == 'موريتانيا') ? 'selected' : ''; ?> >موريتانيا</option>
                            <option value="موريشيوس" <?php echo ($selectedCountry == 'موريشيوس') ? 'selected' : ''; ?> >موريشيوس</option>
                            <option value="موزمبيق" <?php echo ($selectedCountry == 'موزمبيق') ? 'selected' : ''; ?> >موزمبيق</option>
                            <option value="مولدافيا" <?php echo ($selectedCountry == 'مولدافيا') ? 'selected' : ''; ?> >مولدافيا</option>
                            <option value="موناكو" <?php echo ($selectedCountry == 'موناكو') ? 'selected' : ''; ?> >موناكو</option>
                            <option value="مونتسيرات" <?php echo ($selectedCountry == 'مونتسيرات') ? 'selected' : ''; ?> >مونتسيرات</option>
                            <option value="ميكرونيسيا" <?php echo ($selectedCountry == 'ميكرونيسيا') ? 'selected' : ''; ?> >ميكرونيسيا</option>
                            <option value="ميوت" <?php echo ($selectedCountry == 'ميوت') ? 'selected' : ''; ?> >ميوت</option>
                            <option value="ناميبيا" <?php echo ($selectedCountry == 'ناميبيا') ? 'selected' : ''; ?> >ناميبيا</option>
                            <option value="نورو" <?php echo ($selectedCountry == 'نورو') ? 'selected' : ''; ?> >نورو</option>
                            <option value="نومانز لاند" <?php echo ($selectedCountry == 'نومانز لاند') ? 'selected' : ''; ?> >نومانز لاند</option>
                            <option value="ني" <?php echo ($selectedCountry == 'ني') ? 'selected' : ''; ?> >ني</option>
                            <option value="نيبال" <?php echo ($selectedCountry == 'نيبال') ? 'selected' : ''; ?> >نيبال</option>
                            <option value="نيجيريا" <?php echo ($selectedCountry == 'نيجيريا') ? 'selected' : ''; ?> >نيجيريا</option>
                            <option value="نيكاراجوا" <?php echo ($selectedCountry == 'نيكاراجوا') ? 'selected' : ''; ?> >نيكاراجوا</option>
                            <option value="نيوزيلندا" <?php echo ($selectedCountry == 'نيوزيلندا') ? 'selected' : ''; ?> >نيوزيلندا</option>
                            <option value="هايتي" <?php echo ($selectedCountry == 'هايتي') ? 'selected' : ''; ?> >هايتي</option>
                            <option value="هندوراس" <?php echo ($selectedCountry == 'هندوراس') ? 'selected' : ''; ?> >هندوراس</option>
                            <option value="هولندا" <?php echo ($selectedCountry == 'هولندا') ? 'selected' : ''; ?> >هولندا</option>
                            <option value="هونج كونج" <?php echo ($selectedCountry == 'هونج كونج') ? 'selected' : ''; ?> >هونج كونج</option>
                            <option value="واليس فيوتنا" <?php echo ($selectedCountry == 'واليس فيوتنا') ? 'selected' : ''; ?> >واليس فيوتنا</option>
                            <option value="يوغسلافيا" <?php echo ($selectedCountry == 'يوغسلافيا') ? 'selected' : ''; ?> >يوغسلافيا</option>
                        </select> 
                    </div>
                    <div class="moreinps-container">
                        <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                            <span><i class='bx bxs-folder-open' ></i> <p>المرفقات</p></span> 
                            <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                            <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                        </button>
                        <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                            <div class="input-container">
                                <h4 class="input-parag" style="padding-bottom: 10px;">الوكالة</h4>
                                <div class="drop-zone" id="dropZone1">
                                    <input type="file" id="fileInput1" name="auth_file" hidden>
                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput1').click()">إرفاق مستند</span></p>
                                </div>
                                <div id="fileList1"></div>
                            </div>
                            
                            <div class="input-container">
                                <h4 class="input-parag" style="padding-bottom: 10px;">صورة الهوية</h4>
                                <div class="drop-zone" id="dropZone2">
                                    <input type="file" id="fileInput2" name="id_file" hidden>
                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput2').click()">إرفاق مستند</span></p>
                                </div>
                                <div id="fileList2"></div>
                            </div>
                            
                            <div class="input-container">
                                <h4 class="input-parag" style="padding-bottom: 10px;">صورة جواز السفر</h4>
                                <div class="drop-zone" id="dropZone3">
                                    <input type="file" id="fileInput3" name="passport_file" hidden>
                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput3').click()">إرفاق مستند</span></p>
                                </div>
                                <div id="fileList3"></div>
                            </div>
                            
                            <div class="input-container">
                                <h4 class="input-parag" style="padding-bottom: 10px;">مرفقات أخرى</h4>
                                <div class="drop-zone" id="dropZone4">
                                    <input type="file" id="fileInput4" name="attach_file" hidden>
                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput4').click()">إرفاق مستند</span></p>
                                </div>
                                <div id="fileList4"></div><br>
                                
                                <div class="drop-zone" id="dropZone5">
                                    <input type="file" id="fileInput5" name="attach_file2" hidden>
                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput5').click()">إرفاق مستند</span></p>
                                </div>
                                <div id="fileList5"></div><br>
                                
                                <div class="drop-zone" id="dropZone6">
                                    <input type="file" id="fileInput6" name="attach_file3" hidden>
                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput6').click()">إرفاق مستند</span></p>
                                </div>
                                <div id="fileList6"></div><br>
                                
                                <div class="drop-zone" id="dropZone7">
                                    <input type="file" id="fileInput7" name="attach_file4" hidden>
                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput7').click()">إرفاق مستند</span></p>
                                </div>
                                <div id="fileList7"></div><br>
                                
                                <div class="drop-zone" id="dropZone8">
                                    <input type="file" id="fileInput8" name="attach_file5" hidden>
                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput8').click()">إرفاق مستند</span></p>
                                </div>
                                <div id="fileList8"></div><br>
                                
                                <div class="drop-zone" id="dropZone9">
                                    <input type="file" id="fileInput9" name="attach_file6" hidden>
                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput9').click()">إرفاق مستند</span></p>
                                </div>
                                <div id="fileList9"></div><br>
                            </div>
                        </div>
                    </div>
                    
                    <div class="moreinps-container">
                        <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                            <span><i class='bx bxs-user' ></i></i> <p>حساب المستخدم</p></span> 
                            <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                            <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                        </button>
                        <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                            <div class="input-container">
                                <p class="input-parag">صلاحية الدخول</p>
                                <input type="radio" name="perm" value="1" style="padding: 10px 0; margin: 10px 0;"> نعم <br>
                                <input type="radio" name="perm" value="0" checked> لا
                            </div>
                            <div class="input-container">
                                <p class="input-parag">إسم المستخدم</p>
                                <input class="form-input" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['engname']; } else{ echo $empid; }?>" type="text" disabled>
                                <input type="hidden" name="cid" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['id']; } else{ echo $empid; }?>">
                            </div>
                            <?php 
                                if(!isset($_GET['edit']) || $_GET['edit'] !== '1'){
                            ?>
                            <div class="input-container">
                                <p class="input-parag">كلمة المرور<font style="color: #aa0820;">*</font></p>
                                <input class="form-input" id="passwordField" name="password" type="text" requiured>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="addc-footer">
                <button style="cursor: pointer" type="submit" class="form-btn submit-btn">حفظ</button>
                <p></p>
                <button type="button" class="form-btn cancel-btn" onclick="location.href='<?php echo $page?>';">الغاء</button>
            </div>
        </form>
    </div>
</div>
<?php } else if($row_perm['csched_aperm'] === '1' && $_GET['schedule_form'] === '1'){?>
<div id="addclient-btn" class="modal-overlay" <?php if(isset($_GET['schedule_form']) && $_GET['schedule_form'] === '1'){ echo 'style="display: block;"'; }?>>
    <div class="modal-content">
        <div class="addc-header">
            <h4 class="addc-header-parag" style="margin: auto">اضافة موعد اجتماع</h4>
            <div class="close-button-container">
                <p class="close-button" onclick="location.href='<?php echo $page;?>';">&times;</p>
            </div>
        </div>
        <form action="addschedule.php" method="post" enctype="multipart/form-data" >
            <input type="hidden" name="page" value="<?php echo $page;?>">
            <div class="addc-body">
                <div class="addc-body-form">
                    <div class="input-container">
                        <p class="input-parag">الموكل<font style="color: #aa0820;">*</font></p>
                        <input id="searchInput" onkeyup="searchDatabase()" class="form-input" name="name" type="text" required>
                        <div id="dropdown-cname" class="dropdown-content" style="display: none;"></div>
                        
                        <script>
                            function searchDatabase() {
                                var input = document.getElementById('searchInput').value;
                                
                                if (input.length > 0) {
                                    var xhr = new XMLHttpRequest();
                                    xhr.onreadystatechange = function() {
                                        if (xhr.readyState === 4 && xhr.status === 200) {
                                            document.getElementById('dropdown-cname').innerHTML = xhr.responseText;
                                            document.getElementById('dropdown-cname').style.display = 'block';
                                        }
                                    };
                                    
                                    xhr.open('GET', 'search.php?q=' + encodeURIComponent(input), true);
                                    xhr.send();
                                } else {
                                    document.getElementById('dropdown-cname').style.display = 'none';
                                }
                            }
                            
                            function selectOption(value) {
                                document.getElementById('searchInput').value = value;
                                document.getElementById('dropdown-cname').style.display = 'none';
                            }
                        </script>
                    </div>
                    <div class="input-container">
                        <p class="input-parag">هاتف الموكل<font style="color: #aa0820;">*</font></p>
                        <input class="form-input" name="Cell_no" placeholder='009715xxxxxxxx' type="text" required>
                    </div>
                    <div class="input-container">
                        <p class="input-parag">تاريخ الموعد<font style="color: #aa0820;">*</font></p>
                        <input type="date" class="form-input" name="date" rows="2" required>
                    </div>
                    <div class="input-container">
                        <p class="input-parag">وقت الموعد<font style="color: #aa0820;">*</font></p>
                        <div>
                            <?php
                                $time = $erow['time'];
                                list($timeHH, $timeMM) = explode(":", $time);
                            ?>
                            <input type="number" min="0" max="59" placeholder="MM" class="form-input" style="width: 50px;" name="timeMM" rows="2" required>
                            <input type="number" min="0" max="23" placeholder="HH" class="form-input" style="width: 50px;" name="timeHH" rows="2" required>
                        </div>
                    </div>
                    <div class="input-container">
                        <p class="input-parag">الاجتماع مع</p>
                        <select class="table-header-selector" name="meet_with" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;" dir="rtl">
                            <option></option>
                            <?php
                                if(isset($_GET['edit'])){
                                    $meet_with = $erow['meet_with'];
                                } else{
                                    $meet_with = '';
                                }
                                
                                $queryus = "SELECT * FROM user";
                                $resultus = mysqli_query($conn, $queryus);
                                while($rowus = mysqli_fetch_array($resultus)){
                            ?>
                            <option value="<?php echo $rowus['id'];?>"><?php echo $rowus['name'];?></option>
                            <?php
                                }
                            ?>
                        </select> 
                    </div>
                    <div class="input-container">
                        <p class="input-parag">التفاصيل</p>
                        <textarea class="form-input" name="details" rows="2"></textarea>
                    </div>
                    <div class="input-container">
                        <h4 class="input-parag" style="padding-bottom: 10px;">محضر الاجتماع</h4>
                        <div class="drop-zone" id="dropZone1">
                            <input type="file" id="fileInput1" name="meeting" hidden>
                            <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                            <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput1').click()">إرفاق مستند</span></p>
                        </div>
                        <div id="fileList1"></div>
                    </div>
                </div>
            </div>
            <div class="addc-footer">
                <button style="cursor: pointer" type="submit" class="form-btn submit-btn">حفظ</button>
                <p></p>
                <button type="button" class="form-btn cancel-btn" onclick="<?php echo $page;?>">الغاء</button>
            </div>
        </form>
    </div>
</div>
<?php } else if($row_perm['admjobs_aperm'] === '1' && $_GET['task_form'] === '1'){?>
<div id="addclient-btn" class="modal-overlay" <?php if((isset($_GET['task_form']) && $_GET['task_form'] === '1')){ echo 'style="display: block;"'; }?>>
    <div class="modal-content">
        <div class="addc-header">
            <h4 class="addc-header-parag" style="margin: auto">مهمه جديدة</h4>
            <div class="close-button-container">
                <p class="close-button" onclick="location.href='<?php echo $page;?>';">&times;</p>
            </div>
        </div>
        <form action="task_process.php" method="post" enctype="multipart/form-data" >
            <div class="addc-body">
                <div class="addc-body-form">
                    <div class="input-container" style="border-bottom: 2px solid #f8f8f8;">
                        <p class="input-parag">اضافة المهمة حسب</p>
                        <input type="radio" name="SearchType" value="1" onclick="submit()" style="color: #43425d; padding: 10px 0; margin: 10px 0;" <?php if(isset($_GET['section']) && $_GET['section'] === '1'){echo 'checked';}?>> رقم الملف <br>
                        <input type="radio" name="SearchType" value="2" onclick="submit()" style="color: #43425d; padding: 10px 0; margin: 10px 0;" <?php if(isset($_GET['section']) && $_GET['section'] === '2'){echo 'checked';}?>> اسم الموكل/الخصم <br>
                        <input type="radio" name="SearchType" value="3" onclick="submit()" style="color: #43425d; padding: 10px 0; margin: 10px 0;" <?php if(isset($_GET['section']) && $_GET['section'] === '3'){echo 'checked';}?>> رقم القضية <br>
                        <input type="radio" name="SearchType" value="4" onclick="submit()" style="color: #43425d; padding: 10px 0; margin: 10px 0;" <?php if(isset($_GET['section']) && $_GET['section'] === '4'){echo 'checked';}?>> اضافة عمل إدارى بدون رقم ملف 
                    </div><br>
                    <?php 
                        if(isset($_GET['section']) && $_GET['section'] === '1'){
                    ?>
                    <div class="input-container">
                        <p class="input-parag">رقم الملف</p>
                        <input type="number" class="form-input" value="<?php if(isset($_GET['fno']) && $_GET['fno'] !== ''){echo $_GET['fno'];}?>" name="file_id" onChange="submit()">
                    </div>
                    <?php 
                        } elseif(isset($_GET['section']) && $_GET['section'] === '2'){
                    ?>
                    <div class="input-container">
                        <p class="input-parag">الموكل/الخصم</p>
                        <input type="text" class="form-input" value="<?php if(isset($_GET['cn']) && $_GET['cn'] !== ''){echo $_GET['cn'];}?>" name="SearchByClient" onChange="submit()">
                        <input type="radio" name="Ckind" value="1" onchange="submit()" style="color: #43425d; padding: 10px 0; margin: 10px 0;" <?php if(isset($_GET['ck']) && $_GET['ck'] === '1'){echo 'checked';}?>> موكل <br>
                        <input type="radio" name="Ckind" value="2" onchange="submit()" style="color: #43425d; padding: 10px 0; margin: 10px 0;" <?php if(isset($_GET['ck']) && $_GET['ck'] === '2'){echo 'checked';}?>> خصم 
                    </div>
                    <?php 
                        } elseif(isset($_GET['section']) && $_GET['section'] === '3'){
                    ?>
                    <div class="input-container">
                        <p class="input-parag">رقم القضية</p>
                        <input type="number" name="case_no" class="form-input" placeholder="رقم القضية" value="" style="display: inline-block; width: 80px"> / <input type="number" name="case_no_year" class="form-input" placeholder="السنة" value="" style="display: inline-block; width: 80px">
                        <input type="image" src="img/magnifying-glass.png" width="20px" height="20px" align="absmiddle" onClick="submit()" style="border:none">
                    </div>
                    <?php 
                        } 
                        if((isset($_GET['fno']) && $_GET['fno'] !== '') || (isset($_GET['cn']) && $_GET['cn'] !== '') || (isset($_GET['cno']) && $_GET['cno'] !== '' && isset($_GET['cy']) && $_GET['cy'] !== '')){
                            if(isset($_GET['section']) && $_GET['section'] !== '4'){
                    ?>
                    <div class="input-container">
                        <?php
                            if(isset($_GET['fno'])){
                                $fid = $_GET['fno'];
                                $query = "SELECT * FROM file WHERE file_id='$fid'";
                                $result = mysqli_query($conn, $query);
                            } else if(isset($_GET['cn']) && isset($_GET['ck'])){
                                $cn = $_GET['cn'];
                                $ck = $_GET['ck'];
                                
                                $queryc = "SELECT * FROM client WHERE arname LIKE '%$cn%'";
                                $resultc = mysqli_query($conn, $queryc);
                                if($resultc->num_rows > 0){
                                    $rowc = mysqli_fetch_array($resultc);
                                    $cn = $rowc['id'];
                                }
                                
                                if($_GET['ck'] === '1'){
                                    $query = "SELECT * FROM file WHERE file_client='$cn' OR file_client2='$cn' OR file_client3='$cn' OR file_client4='$cn' OR file_client5='$cn'";
                                    $result = mysqli_query($conn, $query);
                                } else{
                                    $query = "SELECT * FROM file WHERE file_opponent='$cn' OR file_opponent2='$cn' OR file_opponent3='$cn' OR file_opponent4='$cn' OR file_opponent5='$cn'";
                                    $result = mysqli_query($conn, $query);
                                }
                            } else if(isset($_GET['cno']) && $_GET['cno'] !== '' && isset($_GET['cy']) &&$_GET['cy'] !== ''){
                                $cno = $_GET['cno'];
                                $cy = $_GET['cy'];
                                $query1 = "SELECT * FROM file_degrees WHERE case_num='$cno' AND file_year='$cy'";
                                $result1 = mysqli_query($conn, $query1);
                                $row1 = mysqli_fetch_array($result1);
                                $fid = $row1['fid'];
                                $query = "SELECT * FROM file WHERE file_id = '$fid'";
                                $result = mysqli_query($conn, $query);
                            }
                            
                            if(isset($_GET['section']) && $_GET['section'] !== '4' && $result->num_rows == 0){
                                exit();
                            }
                            
                            if(($result->num_rows > 0)){
                        ?>
                        <table class="info-table" style="max-width: 100%; height: 400px overflow: visible;">
                            <tr class="infotable-header" style="text-align: center">
                                <td width="14%">رقم الملف</td>
                                <td width="25%">الموضوع</td>
                                <td width="11%">رقم القضية</td>
                                <td width="25%">الموكل</td>
                                <td width="25%">الخصم</td>
                            </tr>
                            
                            <?php
                                while($row = mysqli_fetch_array($result)){
                            ?>
                            <tr class="infotable-body" onclick="location.href='Tasks.php?addmore=1&section=<?php if(isset($_GET['section']) && $_GET['section'] !== ''){ echo '1';} if(isset($row['file_id']) && $row['file_id'] !== ''){echo '&fno=' . $row['file_id'];}?>&agree=1';">
                                <td width="14%">
                                    <?php 
                                        if(isset($row['frelated_place']) && $row['frelated_place'] !== ''){
                                            $place = $row['frelated_place'];
                                            if($place === 'عجمان'){
                                                echo 'AJM';
                                            }
                                            else if($place === 'دبي'){
                                                echo 'DXB';
                                            }
                                            else if($place === 'الشارقة'){
                                                echo 'SHJ';
                                            }
                                        }
                                        echo ' '.$row['file_id'];
                                    ?>
                                </td>
                                <td width="25%">
                                    <?php 
                                        if(isset($row['file_subject']) && $row['file_subject'] !== ''){ 
                                            echo $row['file_subject']; 
                                        }
                                    ?>
                                </td>
                                <td width="11%">
                                    <?php 
                                        $fid = $row['file_id'];
                                        $queryfid = "SELECT * FROM file_degrees WHERE fid='$fid' ORDER BY created_at DESC";
                                        $resultfid = mysqli_query($conn, $queryfid);
                                        $rowfid = mysqli_fetch_array($resultfid);
                                        if(isset($rowfid['degree']) && $rowfid['degree'] !== ''){ 
                                            echo $rowfid['degree'];
                                        }
                                        echo '<br>'; 
                                        if(isset($rowfid['case_num']) && $rowfid['case_num'] !== ''){ 
                                            echo '-'.$rowfid['case_num']; 
                                        }
                                        if(isset($rowfid['file_year']) && $rowfid['file_year'] !== ''){ 
                                            echo '/'.$rowfid['file_year']; 
                                        }
                                    ?>
                                </td>
                                <td width="25%">
                                    <?php 
                                        if(isset($row['file_client']) && $row['file_client'] !== ''){ 
                                            $cid1 = $row['file_client'];
                                            $queryc1 = "SELECT * FROM client WHERE id='$cid1'";
                                            $resultc1 = mysqli_query($conn, $queryc1);
                                            $rowc1 = mysqli_fetch_array($resultc1);
                                            echo $rowc1['arname'];
                                            
                                            if(isset($row['fclient_characteristic']) && $row['fclient_characteristic'] !== ''){ 
                                                echo ' / ' . $row['fclient_characteristic'] . '<br>'; 
                                            }
                                        }
                                        
                                        if(isset($row['file_client2']) && $row['file_client2'] !== ''){ 
                                            $cid2 = $row['file_client2'];
                                            $queryc2 = "SELECT * FROM client WHERE id='$cid2'";
                                            $resultc2 = mysqli_query($conn, $queryc2);
                                            $rowc2 = mysqli_fetch_array($resultc2);
                                            echo $rowc2['arname'];
                                            
                                            if(isset($row['fclient_characteristic2']) && $row['fclient_characteristic2'] !== ''){ 
                                                echo ' / ' . $row['fclient_characteristic2'] . '<br>'; 
                                            }
                                        }
                                        
                                        if(isset($row['file_client3']) && $row['file_client3'] !== ''){ 
                                            $cid3 = $row['file_client3'];
                                            $queryc3 = "SELECT * FROM client WHERE id='$cid3'";
                                            $resultc3 = mysqli_query($conn, $queryc3);
                                            $rowc3 = mysqli_fetch_array($resultc3);
                                            echo $rowc3['arname'];
                                            
                                            if(isset($row['fclient_characteristic3']) && $row['fclient_characteristic3'] !== ''){ 
                                                echo ' / ' . $row['fclient_characteristic3'] . '<br>'; 
                                            }
                                        }
                                        
                                        if(isset($row['file_client4']) && $row['file_client4'] !== ''){ 
                                            $cid4 = $row['file_client4'];
                                            $queryc4 = "SELECT * FROM client WHERE id='$cid4'";
                                            $resultc4 = mysqli_query($conn, $queryc4);
                                            $rowc4 = mysqli_fetch_array($resultc4);
                                            echo $rowc4['arname'];
                                            
                                            if(isset($row['fclient_characteristic4']) && $row['fclient_characteristic4'] !== ''){ 
                                                echo ' / ' . $row['fclient_characteristic4'] . '<br>'; 
                                            }
                                        }
                                        
                                        if(isset($row['file_client5']) && $row['file_client5'] !== ''){ 
                                            $cid5 = $row['file_client5'];
                                            $queryc5 = "SELECT * FROM client WHERE id='$cid5'";
                                            $resultc5 = mysqli_query($conn, $queryc5);
                                            $rowc5 = mysqli_fetch_array($resultc5);
                                            echo $rowc5['arname'];
                                            
                                            if(isset($row['fclient_characteristic5']) && $row['fclient_characteristic5'] !== ''){ 
                                                echo ' / ' . $row['fclient_characteristic5'] . '<br>'; 
                                            }
                                        }
                                    ?> 
                                </td>
                                <td width="25%">
                                    <?php 
                                        if(isset($row['file_opponent']) && $row['file_opponent'] !== ''){ 
                                            $oid = $row['file_opponent'];
                                            $queryo = "SELECT * FROM client WHERE id='$oid'";
                                            $resulto = mysqli_query($conn, $queryo);
                                            $rowo = mysqli_fetch_array($resulto);
                                            echo $rowo['arname'];
                                            
                                            if(isset($row['fopponent_characteristic']) && $row['fopponent_characteristic'] !== ''){ 
                                                echo ' / ' . $row['fopponent_characteristic'] . '<br>'; 
                                            }
                                        }
                                        
                                        if(isset($row['file_opponent2']) && $row['file_opponent2'] !== ''){ 
                                            $oid2 = $row['file_opponent2'];
                                            $queryo2 = "SELECT * FROM client WHERE id='$oid2'";
                                            $resulto2 = mysqli_query($conn, $queryo2);
                                            $rowo2 = mysqli_fetch_array($resulto2);
                                            echo $rowo2['arname'];
                                            
                                            if(isset($row['fopponent_characteristic2']) && $row['fopponent_characteristic2'] !== ''){ 
                                                echo ' / ' . $row['fopponent_characteristic2'] . '<br>'; 
                                            }
                                        }
                                        
                                        if(isset($row['file_opponent3']) && $row['file_opponent3'] !== ''){ 
                                            $oid3 = $row['file_opponent3'];
                                            $queryo3 = "SELECT * FROM client WHERE id='$oid3'";
                                            $resulto3 = mysqli_query($conn, $queryo3);
                                            $rowo3 = mysqli_fetch_array($resulto3);
                                            echo $rowo3['arname'];
                                            
                                            if(isset($row['fopponent_characteristic3']) && $row['fopponent_characteristic3'] !== ''){ 
                                                echo ' / ' . $row['fopponent_characteristic3'] . '<br>'; 
                                            }
                                        }
                                        
                                        if(isset($row['file_opponent4']) && $row['file_opponent4'] !== ''){ 
                                            $oid4 = $row['file_opponent4'];
                                            $queryo4 = "SELECT * FROM client WHERE id='$oid4'";
                                            $resulto4 = mysqli_query($conn, $queryo4);
                                            $rowo4 = mysqli_fetch_array($resulto4);
                                            echo $rowo4['arname'];
                                            
                                            if(isset($row['fopponent_characteristic4']) && $row['fopponent_characteristic4'] !== ''){ 
                                                echo ' / ' . $row['fopponent_characteristic4'] . '<br>'; 
                                            }
                                        }
                                        
                                        if(isset($row['file_opponent5']) && $row['file_opponent5'] !== ''){ 
                                            $oid5 = $row['file_opponent5'];
                                            $queryo5 = "SELECT * FROM client WHERE id='$oid5'";
                                            $resulto5 = mysqli_query($conn, $queryo5);
                                            $rowo5 = mysqli_fetch_array($resulto5);
                                            echo $rowo5['arname'];
                                            
                                            if(isset($row['fopponent_characteristic5']) && $row['fopponent_characteristic5'] !== ''){ 
                                                echo ' / ' . $row['fopponent_characteristic5'] . '<br>'; 
                                            }
                                        }
                                    ?>
                                </td>
                            </tr>
                            <?php }?>
                        </table><br>
                        <?php }?>
                    </div>
                    <?php
                            }
                        }
                        if(isset($_GET['agree']) && $_GET['agree'] === '1'){
                            echo '<input type="hidden" name="agree" value="1">';
                            
                            if(isset($_GET['fno']) && $_GET['fno'] !== ''){
                                $fid = $_GET['fno'];
                                $query1 = "SELECT * FROM file WHERE file_id='$fid'";
                                $result1 = mysqli_query($conn, $query1);
                                $row1 = mysqli_fetch_array($result1);
                            }
                            
                            if(isset($_GET['section']) && $_GET['section'] !== '4'){
                    ?>
                    <div class="input-container" style="border-bottom: 2px solid #f8f8f8;">
                        <p class="input-parag">الفرع</p>
                        <p class="form-input">
                            <?php
                                if(isset($row1['frelated_place']) && $row1['frelated_place'] !== ''){
                                    echo $row1['frelated_place'];
                                }
                            ?>
                        <p class="input-parag">نوع القضية</p>
                        <p class="form-input">
                            <?php
                                if(isset($row1['fcase_type']) && $row1['fcase_type'] !== ''){ 
                                    echo $row1['fcase_type'];
                                }
                            ?>
                        </p>
                        </p>
                    </div><br>
                    <?php }?>
                    <div class="input-container">
                        <p class="input-parag">الموظف المكلف<font color="#FF0000">*</font></p>
                        <?php if(isset($_GET['pi']) && $_GET['pi'] !== ''){ $selected_position = $_GET['pi']; } else{ $selected_position = ''; }?>
                        <select class="table-header-selector" name="position_id" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;" onchange="submit()" >
                            <option value=""></option>
                            <?php
                                $query_positions = "SELECT * FROM positions";
                                $result_positions = mysqli_query($conn, $query_positions);
                                if($result_positions->num_rows > 0){
                                    while($row_positions=mysqli_fetch_array($result_positions)){
                            ?>
                            <option value="<?php echo $row_positions['id'];?>"<?php if($row_positions['id'] === $selected_position){ echo 'selected'; } ?>><?php echo $row_positions['position_name'];?></option>
                            <?php
                                    }
                                }
                            ?>
                        </select>
                        <select class="table-header-selector" name="re_name" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                            <?php 
                                if(isset($_GET['pi']) && $_GET['pi'] !== ''){ 
                                    $pnid = $_GET['pi'];
                                    
                                    if(isset($_GET['rn']) && $_GET['rn'] !== ''){ $selected_emp = $_GET['rn']; } else{ $selected_emp = ''; }
                                    $queryemps = "SELECT * FROM user WHERE job_title = '$pnid'";
                                    $resultemps = mysqli_query($conn, $queryemps);
                                    if($resultemps->num_rows > 0){
                                        while($rowemps = mysqli_fetch_array($resultemps)){
                            ?>
                            <option value="<?php echo $rowemps['id'];?>" <?php if($rowemps['id'] === $selected_emp){ echo 'selected'; }?>><?php echo $rowemps['name'];?></option>
                            <?php
                                        }
                                    }
                                } else{
                            ?>
                            <option value="" ></option>
                            <?php }?>
                        </select>
                        
                        <?php if(isset($_GET['error']) && $_GET['error'] === '1'){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى اختيار الموظف</span><?php }?>
                    </div>
                    <div class="input-container">
                        <p class="input-parag">نوع العمل<font color="#FF0000">*</font></p>
                        <?php if(isset($_GET['pi']) && $_GET['pi'] !== ''){ $selected_position = $_GET['pi']; } else{ $selected_position = ''; }?>
                        <select class="table-header-selector" name="type_name" dir="rtl" style="width: 60%; padding: 10px 0; margin: 10px 0; padding: 5px;" required>
                            <option value=""></option>
                            <?php
                              $query_job = "SELECT * FROM job_name";
                              $result_job = mysqli_query($conn, $query_job);
                              if(isset($_GET['tn']) && $_GET['tn'] !== ''){$selectedTn = $_GET['tn'];} else {$selectedTn = '';}
                              if($result_job->num_rows > 0){
                                while($row_job = mysqli_fetch_array($result_job)){
                            ?>
                            <option value="<?php echo $row_job['id'];?>" <?php if($row_job['id'] === $selectedTn){ echo 'selected'; }?>><?php echo $row_job['job_name'];?></option>
                            <?php
                                }
                              } else{
                            ?>
                            <option value=""></option>
                            <?php
                              }
                            ?>
                        </select> 
                        <?php if($row_permcheck['admjobtypes_rperm'] === '1'){?> 
                        <img src="img/add-button.png" align="absmiddle" width="25px" height="25px" title="اضافة" onclick="addnew()" style="cursor:pointer"/>
                        <?php }?>
                    </div>
                    <?php if(isset($_GET['section']) && $_GET['section'] !== '4'){?>
                    <div class="input-container">
                        <p class="input-parag">درجة التقاضي</p>
                        <?php if(isset($_GET['pi']) && $_GET['pi'] !== ''){ $selected_position = $_GET['pi']; } else{ $selected_position = ''; }?>
                        <select class="table-header-selector" name="degree_id" dir="rtl" style="width: 60%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                            <?php 
                                if(isset($_GET['di']) && $_GET['di'] !== ''){$selectedDeg = $_GET['di'];} else{$selectedDeg = '';}
                                $fid = $_GET['fno'];
                                $querydegs = "SELECT * FROM file_degrees WHERE fid='$fid' ORDER BY created_at DESC";
                                $resultdegs = mysqli_query($conn, $querydegs);
                                if($resultdegs->num_rows > 0){
                                    while($rowdegs = mysqli_fetch_array($resultdegs)){
                            ?>
                            <option value="<?php echo $rowdegs['id'];?>" <?php if($rowdegs['id'] === $selectedDeg){ echo 'selected'; }?>><?php echo $rowdegs['case_num'].'/'.$rowdegs['file_year'].'-'.$rowdegs['degree'];?></option>
                            <?php }}?>
                        </select> 
                    </div>
                    <?php }?>
                    <div class="input-container">
                        <p class="input-parag">الاهمية</p>
                        <input type="radio" name="busi_priority" value="0" style="color: #43425d; padding: 10px 0; margin: 10px 0;" <?php if(isset($_GET['bp']) && $_GET['bp'] === '0'){ echo 'checked'; } if(!isset($_GET['bp'])){ echo 'checked'; }?>> عادي <br>
                        <input type="radio" name="busi_priority" value="1" style="color: #43425d; padding: 10px 0; margin: 10px 0;" <?php if(isset($_GET['bp']) && $_GET['bp'] === '1'){ echo 'checked'; }?>> عاجل <br>
                    </div>
                    <div class="input-container">
                        <p class="input-parag">تاريخ تنفيذ العمل</p>
                        <input type="date" class="form-input" value="<?php if(isset($_GET['bd']) && $_GET['bd'] !== ''){ echo $_GET['bd'];}?>" name="busi_date">
                    </div>
                    <div class="input-container">
                        <p class="input-parag">التفاصيل</p>
                        <textarea class="form-input" name="busi_notes" rows="2"><?php if(isset($_GET['bn']) && $_GET['bn'] !== ''){echo $_GET['bn'];}?></textarea>
                    </div>
                    <?php }?>
                </div>
            </div>
            <div class="addc-footer">
                <button style="cursor: pointer;" type="submit" name="save_task_fid" class="form-btn submit-btn">حفظ</button>
                <button style="cursor: pointer;" type="submit" name="submit_back" value="addmore" class="form-btn cancel-btn">حفظ و انشاء آخر</button>
                <button type="button" class="form-btn cancel-btn" onclick="location.href='Tasks.php';">الغاء</button>
            </div>
        </form>
    </div>
</div>
<?php }*/?>

<script>
    function toggleDropdown2() {
        document.getElementById("dropdown2Menu").classList.toggle("show");
    }
    
    window.onclick = function(event) {
        if (!event.target.matches('.dropdown2-btn')) {
            const dropdown2s = document.getElementsByClassName("dropdown2-content");
            for (let i = 0; i < dropdown2s.length; i++) {
                dropdown2s[i].classList.remove("show");
            }
        }
    }
</script>

<script src="js/popups.js"></script>
<script src="js/dropdown.js"></script>-->