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
            $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
            $result_permcheck = mysqli_query($conn, $query_permcheck);
            $row_permcheck = mysqli_fetch_array($result_permcheck);
            
            if(isset($_GET['id']) && $_GET['id'] !== $_SESSION['id'] && ($row_permcheck['emp_rperm'] === '1' || $row_permcheck['emp_eperm'] === '1')){
                $id = $_GET['id'];
                $querymain = "SELECT * FROM user WHERE id='$id'";
            } else{
                $id = $_SESSION['id'];
                $querymain = "SELECT * FROM user WHERE id='$id'";
            }
            $resultmain = mysqli_query($conn, $querymain);
            $rowmain = mysqli_fetch_array($resultmain);
            
            include_once 'AES256.php';
        ?>
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php include_once 'header.php';?>
                
                <div class="web-page">
                    <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && (($row_permcheck['emp_eperm'] === '1' && isset($_GET['id']) && $_GET['id'] !== '') || ($row_permcheck['emp_eperm'] !== '1' && (!isset($_GET['id']) || $_GET['id'] === $_SESSION['id'])))){?>
                    <br><br>
                    <div class="advinputs-container" style="height: 80vh; overflow-y: auto">
                        <form action="updateuserprofile.php" method="post" enctype="multipart/form-data">
                            <h2 class="advinputs-h2">تعديل بيانات الملف التعريفي</h2>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الاسم كامل<font color="red">*</font></font></p>
                                    <input class="form-input" name="name" value="<?php echo $rowmain['name'];?>" type="text" required>
                                </div>
                                <p></p>
                                <p></p>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">اسم الدخول</font><font color="red">*</font></p>
                                    <input class="form-input" name="username" value="<?php echo $rowmain['username'];?>" type="text" required>
                                </div>
                                <p></p>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">كلمة المرور</font><font color="red">*</font></p>
                                    <input class="form-input" name="password" value="<?php $password = $rowmain['password']; $decrypted_password = openssl_decrypt($password, $cipher, $key, $options, $iv); echo $decrypted_password;?>" type="password" required>
                                </div>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">البريد الإلكتروني</font><font color="red">*</font></p>
                                    <input class="form-input" name="email" value="<?php $email = $rowmain['email']; $decrypted_email = openssl_decrypt($email, $cipher, $key, $options, $iv); echo $decrypted_email;?>" type="email" required>
                                </div>
                                <p></p>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">العنوان</font></p>
                                    <textarea class="form-input" name="address" rows="2"><?php $address = $rowmain['address']; $decrypted_address = openssl_decrypt($address, $cipher, $key, $options, $iv); echo $decrypted_address;?></textarea>
                                </div>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الهاتف 1</font><font color="red">*</font></p>
                                    <input class="form-input" name="tel1" value="<?php $tel1 = $rowmain['tel1']; $decrypted_tel1 = openssl_decrypt($tel1, $cipher, $key, $options, $iv); echo $decrypted_tel1;?>" type="text" required>
                                </div>
                                <p></p>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الهاتف 2</font></p>
                                    <input class="form-input" name="tel2" value="<?php $tel2 = $rowmain['tel2']; $decrypted_tel2 = openssl_decrypt($tel2, $cipher, $key, $options, $iv); echo $decrypted_tel2;?>" type="text">
                                </div>
                            </div>
                            <h2 class="advinputs-h2">الحالة الاجتماعية</h2>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag">الجنس</p>
                                    <input type="radio" name="sex" style="padding: 10px 0; margin: 10px 0;" <?php if($rowmain['sex'] === 'ذكر'){ echo 'checked'; }?> value="ذكر"> ذكر <br>
                                    <input type="radio" name="sex" style="padding: 10px 0; margin: 10px 0;" <?php if($rowmain['sex'] === 'انثى'){ echo 'checked'; }?> value="انثى"> انثى
                                </div>
                                <p></p>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الحالة الاجتماعية</font></p>
                                    <input class="form-input" name="social" value="<?php echo $rowmain['social'];?>" type="text">
                                </div>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الجنسية</font></p>
                                    <?php $selectedCountry = $rowmain['nationality'];?>
                                    <select class="table-header-selector" name="nationality" style="width: 100%; margin: 10px 0; padding: 5px; height: fit-content">
                                        <option value="" <?php echo ($selectedCountry == '0') ? 'selected' : ''; ?>></option>
                                        <option value="أثيوبيا" <?php echo ($selectedCountry == 'أثيوبيا') ? 'selected' : ''; ?> >أثيوبيا</option>
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
                                <p></p>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">تاريخ الميلاد</font></p>
                                    <input class="form-input" name="dob" value="<?php echo $rowmain['dob'];?>" type="date">
                                </div>
                            </div>
                            <h2 class="advinputs-h2">معلومات خاصة</h2>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">رقم جواز السفر<font color="red">*</font></font></p>
                                    <input class="form-input" name="passport_no" value="<?php $passport_no = $rowmain['passport_no']; $decrypted_passport_no = openssl_decrypt($passport_no, $cipher, $key, $options, $iv); echo $decrypted_passport_no;?>" type="text" required>
                                </div>
                                <p></p>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">تاريخ انتهاء جواز السفر</font></p>
                                    <input class="form-input" name="passport_exp" value="<?php echo $rowmain['passport_exp'];?>" type="date">
                                </div>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">رقم الهوية</font></p>
                                    <input class="form-input" name="residence_no" value="<?php echo $rowmain['residence_no'];?>" type="text">
                                </div>
                                <p></p>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">تاريخ صدور الهوية</font></p>
                                    <input class="form-input" name="residence_date" value="<?php echo $rowmain['residence_date'];?>" type="date">
                                </div>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">المسمى الوظيفي</font></p>
                                    <select class="table-header-selector" name="job_title" style="width: 100%; margin: 10px 0; padding: 5px; height: fit-content">
                                        <?php 
                                            if(isset($rowmain['job_title'])){ 
                                                $select_jobtitle = $rowmain['job_title']; 
                                            } else{ 
                                                $select_jobtitle = '';
                                            }
                                            $queryjs = "SELECT * FROM positions";
                                            $resultjs = mysqli_query($conn, $queryjs);
                                            if($resultjs->num_rows > 0){
                                                while($rowjs=mysqli_fetch_array($resultjs)){
                                        ?>
                                        <option value="<?php echo $rowjs['id']?>" <?php echo ($select_jobtitle == $rowjs['id']) ? 'selected' : ''; ?>  ><?php echo $rowjs['position_name'];?> </option>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <p></p>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">تاريخ انتهاء الهوية</font></p>
                                    <input class="form-input" name="residence_exp" value="<?php echo $rowmain['residence_exp'];?>" type="date">
                                </div>
                            </div>
                            <h2 class="advinputs-h2">المرفقات</h2>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">جواز السفر</font></p>
                                    <input class="form-input" name="passport_residence" value="<?php echo $rowmain['passport_residence'];?>" type="file">
                                    <a href="<?php echo $rowmain['passport_residence'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                        <?php echo basename($rowmain['passport_residence']);?>
                                    </a>
                                </div>
                                <p></p>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">المؤهل العملي</font></p>
                                    <input class="form-input" name="practical_qualification" value="<?php echo $rowmain['practical_qualification'];?>" type="file">
                                    <a href="<?php echo $rowmain['practical_qualification'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                        <?php echo basename($rowmain['practical_qualification']);?>
                                    </a>
                                </div>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">السيرة الذاتية</font></p>
                                    <input class="form-input" name="biography" value="<?php echo $rowmain['biography'];?>" type="file">
                                    <?php
                                        $queryuserbiography = "SELECT * FROM user_attachments WHERE biography!='' AND user_id='$id'";
                                        $resultuserbiography = mysqli_query($conn, $queryuserbiography);
                                        while($rowuserbiography = mysqli_fetch_array($resultuserbiography)){
                                    ?>
                                    <a href="<?php echo $rowuserbiography['biography'];?>" onClick="window.open('<?php echo $rowuserbiography['biography'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                        <?php echo basename($rowuserbiography['biography']);?>
                                    </a><br>
                                    <?php }?>
                                    <input type="hidden" name="biography_by" value="<?php echo $_SESSION['id'];?>">
                                </div>
                                <p></p>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الهوية الاماراتية</font></p>
                                    <input class="form-input" name="uaeresidence" value="<?php echo $rowmain['uaeresidence'];?>" type="file">
                                    <?php
                                        $queryuseruaeresidence = "SELECT * FROM user_attachments WHERE uaeresidence!='' AND user_id='$id'";
                                        $resultuseruaeresidence = mysqli_query($conn, $queryuseruaeresidence);
                                        while($rowuseruaeresidence = mysqli_fetch_array($resultuseruaeresidence)){
                                    ?>
                                    <a href="<?php echo $rowuseruaeresidence['uaeresidence'];?>" onClick="window.open('<?php echo $rowuseruaeresidence['uaeresidence'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                        <?php echo basename($rowuseruaeresidence['uaeresidence']);?>
                                    </a><br>
                                    <?php }?>
                                    <input type="hidden" name="uaeresidence_by" value="<?php echo $_SESSION['id'];?>">
                                </div>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">شهادة حسن السيرة و السلوك</font></p>
                                    <input class="form-input" name="behaviour" value="<?php echo $rowmain['behaviour'];?>" type="file">
                                    <?php
                                        $queryuserbehaviour = "SELECT * FROM user_attachments WHERE behaviour!='' AND user_id='$id'";
                                        $resultuserbehaviour = mysqli_query($conn, $queryuserbehaviour);
                                        while($rowuserbehaviour = mysqli_fetch_array($resultuserbehaviour)){
                                    ?>
                                    <a href="<?php echo $rowuserbehaviour['behaviour'];?>" onClick="window.open('<?php echo $rowuserbehaviour['behaviour'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                        <?php echo basename($rowuserbehaviour['behaviour']);?>
                                    </a><br>
                                    <?php }?>
                                    <input type="hidden" name="behaviour_by" value="<?php echo $_SESSION['id'];?>">
                                </div>
                                <p></p>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الشهادة الجامعية</font></p>
                                    <input class="form-input" name="university" value="<?php echo $rowmain['university'];?>" type="file">
                                    <?php
                                        $queryuseruniversity = "SELECT * FROM user_attachments WHERE university!='' AND user_id='$id'";
                                        $resultuseruniversity = mysqli_query($conn, $queryuseruniversity);
                                        while($rowuseruniversity = mysqli_fetch_array($resultuseruniversity)){
                                    ?>
                                    <a href="<?php echo $rowuseruniversity['university'];?>" onClick="window.open('<?php echo $rowuseruniversity['university'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                        <?php echo basename($rowuseruniversity['university']);?>
                                    </a><br>
                                    <?php }?>
                                    <input type="hidden" name="university_by" value="<?php echo $_SESSION['id'];?>">
                                </div>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">عقد العمل</font></p>
                                    <input class="form-input" name="contract" value="<?php echo $rowmain['contract'];?>" type="file">
                                    <?php
                                        $queryusercontract = "SELECT * FROM user_attachments WHERE contract!='' AND user_id='$id'";
                                        $resultusercontract = mysqli_query($conn, $queryusercontract);
                                        while($rowusercontract = mysqli_fetch_array($resultusercontract)){
                                    ?>
                                    <a href="<?php echo $rowusercontract['contract'];?>" onClick="window.open('<?php echo $rowusercontract['contract'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                        <?php echo basename($rowusercontract['contract']);?>
                                    </a><br>
                                    <?php }?>
                                    <input type="hidden" name="contract_by" value="<?php echo $_SESSION['id'];?>">
                                </div>
                                <p></p>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">بطاقة العمل</font></p>
                                    <input class="form-input" name="card" value="<?php echo $rowmain['card'];?>" type="file">
                                    <?php
                                        $queryusercard = "SELECT * FROM user_attachments WHERE card!='' AND user_id='$id'";
                                        $resultusercard = mysqli_query($conn, $queryusercard);
                                        while($rowusercard = mysqli_fetch_array($resultusercard)){
                                    ?>
                                    <a href="<?php echo $rowusercard['card'];?>" onClick="window.open('<?php echo $rowusercard['card'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                        <?php echo basename($rowusercard['card']);?>
                                    </a><br>
                                    <?php }?>
                                    <input type="hidden" name="card_by" value="<?php echo $_SESSION['id'];?>">
                                </div>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">التأمين الصحي</font></p>
                                    <input class="form-input" name="sigorta" value="<?php echo $rowmain['sigorta'];?>" type="file">
                                    <?php
                                        $queryusersigorta = "SELECT * FROM user_attachments WHERE sigorta!='' AND user_id='$id'";
                                        $resultusersigorta = mysqli_query($conn, $queryusersigorta);
                                        while($rowusersigorta = mysqli_fetch_array($resultusersigorta)){
                                    ?>
                                    <a href="<?php echo $rowusersigorta['sigorta'];?>" onClick="window.open('<?php echo $rowusersigorta['sigorta'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                        <?php echo basename($rowusersigorta['sigorta']);?>
                                    </a><br>
                                    <?php }?>
                                    <input type="hidden" name="sigorta_by" value="<?php echo $_SESSION['id'];?>">
                                </div>
                                <p></p>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">أُخرى</font></p>
                                    <input class="form-input" name="other" value="<?php echo $rowmain['other'];?>" type="file">
                                    <?php
                                        $queryuserother = "SELECT * FROM user_attachments WHERE other!='' AND user_id='$id'";
                                        $resultuserother = mysqli_query($conn, $queryuserother);
                                        while($rowuserother = mysqli_fetch_array($resultuserother)){
                                    ?>
                                    <a href="<?php echo $rowuserother['other'];?>" onClick="window.open('<?php echo $rowuserother['other'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                        <?php echo basename($rowuserother['other']);?>
                                    </a><br>
                                    <?php }?>
                                    <input type="hidden" name="other_by" value="<?php echo $_SESSION['id'];?>">
                                </div>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الصورة الشخصية</font></p>
                                    <input class="form-input" name="personal_image" value="<?php echo $rowmain['personal_image'];?>" type="file">
                                    <a href="<?php echo $rowmain['personal_image'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                        <?php echo basename($rowmain['personal_image']);?>
                                    </a>
                                </div>
                                <p></p>
                                <p></p>
                            </div>
                            <h2 class="advinputs-h2">معلومات الحساب البنكي</h2>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">اسم البنك</font></p>
                                    <input class="form-input" name="bank_name" value="<?php echo $rowmain['bank_name'];?>" type="text">
                                </div>
                                <p></p>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">رقم الحساب</font></p>
                                    <input class="form-input" name="acc_no" value="<?php echo $rowmain['acc_no'];?>" type="text">
                                </div>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">رقم الايبان</font></p>
                                    <input class="form-input" name="iban" value="<?php echo $rowmain['iban'];?>" type="text">
                                </div>
                                <p></p>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">طريقة الدفع</font></p>
                                    <input type="radio" name="pay_way" style="padding: 10px 0; margin: 10px 0;" <?php if($rowmain['pay_way'] === 'شيك'){ echo 'checked'; }?> value="شيك"> شيك <br>
                                    <input type="radio" name="pay_way" style="padding: 10px 0; margin: 10px 0;" <?php if($rowmain['pay_way'] === 'كاش'){ echo 'checked'; }?> value="كاش"> كاش
                                </div>
                            </div>
                            <h2 class="advinputs-h2">معلومات في حالات الطوارئ</h2>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الاسم 1</font></p>
                                    <input class="form-input" name="emergency_name1" value="<?php echo $rowmain['emergency_name1'];?>" type="text">
                                </div>
                                <p></p>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">صلة القرابة</font></p>
                                    <input class="form-input" name="emergency_relate1" value="<?php echo $rowmain['emergency_relate1'];?>" type="text">
                                </div>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">رقم الهاتف</font></p>
                                    <input class="form-input" name="emergency_tel1" value="<?php echo $rowmain['emergency_tel1'];?>" type="text">
                                </div>
                                <p></p>
                                <p></p>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الاسم 2</font></p>
                                    <input class="form-input" name="emergency_name2" value="<?php echo $rowmain['emergency_name2'];?>" type="text">
                                </div>
                                <p></p>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">صلة القرابة</font></p>
                                    <input class="form-input" name="emergency_relate2" value="<?php echo $rowmain['emergency_relate2'];?>" type="text">
                                </div>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">رقم الهاتف</font></p>
                                    <input class="form-input" name="emergency_tel2" value="<?php echo $rowmain['emergency_tel2'];?>" type="text">
                                </div>
                                <p></p>
                                <p></p>
                            </div>
                            <div class="profedit-footer">
                                <div style="text-align: center">
                                    <button type="button" class="h-AdvancedSearch-Btn green-button" onclick="location.href='UserProfile.php'">الغاء التعديلات</button>
                                </div>
                                <div style="text-align: center">
                                    <button type="button" onclick="edituserprofile.submit();" class="h-AdvancedSearch-Btn green-button" style="font-size: 18px">تأكيد التعديلات</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php } else{ if(($row_permcheck['emp_rperm'] === '1' && isset($_GET['id']) && $_GET['id'] !== '') || ($row_permcheck['emp_rperm'] !== '1' && (!isset($_GET['id']) || $_GET['id'] === $_SESSION['id']))){?>
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate" style="color:#125386;">الملف التعريفي للمستخدم</font></h3>
                            </div>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"></div>
                                <div class="table-header-icons" style="background-image: url('img/edit.png'); margin-right: 30px;" onclick="location.href='UserProfile.php?edit=1';"></div>
                            </div>
                        </div>
                        <div class="table-body" style="overflow: hidden">
                            <div class="table-prof-grid">
                                <div class="profile-header">
                                    <div class="profile-grid" style="background-color: #125386">
                                        <div class="header-info-item">
                                            <p>الاسم الكامل : </p>
                                            <p><?php echo $rowmain['name']; ?></p>
                                        </div>
                                        <p></p>
                                        <div class="header-info-item">
                                            <p>آخر دخول : </p>
                                            <p><?php echo $rowmain['lastlogin']; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="profile-body">
                                    <div class="moreinps-container">
                                        <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                            <span><i class='bx bxs-user' ></i> <p>حساب المستخدم</p></span> 
                                            <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                            <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                        </button>
                                        <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>اسم الدخول : </p>
                                                    <p class="profile-information"><?php echo $rowmain['username']; ?></p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>
                                                        <?php if(isset($_GET['showpassword'])){?> 
                                                            <i class='bx bx-hide' style="color: #3c88cc; font-size: 20px; cursor: pointer;" onclick="location.href='<?php if(isset($_GET['showpassword'])){ echo 'UserProfile.php'; } else{ echo 'UserProfile.php?showpassword=1'; }?>';"></i>
                                                        <?php } else{?> 
                                                            <i class='bx bx-show' style="color: #3c88cc; font-size: 20px; cursor: pointer;" onclick="location.href='<?php if(isset($_GET['showpassword'])){ echo 'UserProfile.php'; } else{ echo 'UserProfile.php?showpassword=1'; }?>';"></i>
                                                        <?php }?>
                                                        كلمة المرور : 
                                                    </p>
                                                    <p class="profile-information">
                                                        <?php 
                                                            if(isset($_GET['showpassword'])){
                                                                $password = $rowmain['password'];
                                                                $decrypted_password = openssl_decrypt($password, $cipher, $key, $options, $iv);
                                                                echo $decrypted_password;
                                                            } else{
                                                                echo '********';
                                                            }
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>عدد مرات الدخول : </p>
                                                    <p class="profile-information"><?php echo $rowmain['logins_num']; ?></p>
                                                </div>
                                                <p></p>
                                                <p></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="moreinps-container">
                                        <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                            <span><i class='bx bxs-contact' ></i> <p>معلومات التواصل</p></span> 
                                            <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                            <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                        </button>
                                        <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>العنوان : </p>
                                                    <p class="profile-information">
                                                        <?php
                                                            $address = $rowmain['address'];
                                                            $decrypted_address = openssl_decrypt($address, $cipher, $key, $options, $iv);
                                                            echo $decrypted_address;
                                                        ?>
                                                    </p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>الهاتف 1 : </p>
                                                    <p class="profile-information">
                                                        <?php
                                                            $tel1 = $rowmain['tel1'];
                                                            $decrypted_tel1 = openssl_decrypt($tel1, $cipher, $key, $options, $iv);
                                                            echo $decrypted_tel1;
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>الهاتف 2 : </p>
                                                    <p class="profile-information">
                                                        <?php
                                                            $tel2 = $rowmain['tel2'];
                                                            $decrypted_tel2 = openssl_decrypt($tel2, $cipher, $key, $options, $iv);
                                                            echo $decrypted_tel2;
                                                        ?>
                                                    </p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>البريد الالكتروني : </p>
                                                    <p class="profile-information">
                                                        <?php
                                                            $email = $rowmain['email'];
                                                            $decrypted_email = openssl_decrypt($email, $cipher, $key, $options, $iv);
                                                            echo $decrypted_email;
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="moreinps-container">
                                        <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                            <span><i class='bx bxs-user-pin' ></i> <p>الحالة الاجتماعية</p></span> 
                                            <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                            <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                        </button>
                                        <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>الجنس : </p>
                                                    <p class="profile-information"><?php echo $rowmain['sex']; ?></p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>الحالة الاجتماعية : </p>
                                                    <p class="profile-information"><?php echo $rowmain['social']; ?></p>
                                                </div>
                                            </div>
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>الجنسية : </p>
                                                    <p class="profile-information"><?php echo $rowmain['nationality']; ?></p>
                                                </div>
                                                <p></p>
                                                <p></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="moreinps-container">
                                        <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                            <span><i class='bx bxs-lock' ></i> <p>معلومات خاصة</p></span> 
                                            <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                            <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                        </button>
                                        <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>رقم جواز السفر : </p>
                                                    <p class="profile-information">
                                                        <?php
                                                            $passport_no = $rowmain['passport_no'];
                                                            $decrypted_passport_no = openssl_decrypt($passport_no, $cipher, $key, $options, $iv);
                                                            echo $decrypted_passport_no;
                                                        ?>
                                                    </p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>تاريخ انتهاء جواز السفر : </p>
                                                    <p class="profile-information"><?php echo $rowmain['passport_exp']; ?></p>
                                                </div>
                                            </div>
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>رقم الهوية : </p>
                                                    <p class="profile-information"><?php echo $rowmain['residence_no']; ?></p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>تاريخ انتهاء الهوية : </p>
                                                    <p class="profile-information"><?php echo $rowmain['residence_exp']; ?></p>
                                                </div>
                                            </div>
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>تاريخ الميلاد : </p>
                                                    <p class="profile-information"><?php echo $rowmain['dob']; ?></p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>تاريخ صدور الهوية : </p>
                                                    <p class="profile-information"><?php echo $rowmain['residence_date']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="moreinps-container">
                                        <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                            <span><i class='bx bxs-briefcase' ></i> <p>معلومات الوظيفة</p></span> 
                                            <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                            <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                        </button>
                                        <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>المسمى الوظيفي : </p>
                                                    <p class="profile-information">
                                                        <?php
                                                            $job_title = $rowmain['job_title'];
                                                            
                                                            $queryjt = "SELECT * FROM positions WHERE id='$job_title'";
                                                            $resultjt = mysqli_query($conn, $queryjt);
                                                            $rowjt = mysqli_fetch_array($resultjt);
                                                            echo $rowjt['position_name'];
                                                        ?>
                                                    </p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>المسؤول المباشر : </p>
                                                    <p class="profile-information">
                                                        <?php
                                                            $responsible = $rowmain['responsible'];
                                                            
                                                            $queryru = "SELECT * FROM user WHERE id='$responsible'";
                                                            $resultru = mysqli_query($conn, $queryru);
                                                            $rowru = mysqli_fetch_array($resultru);
                                                            echo $rowru['name'];
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>فرع العمل : </p>
                                                    <p class="profile-information"><?php echo $rowmain['work_place']; ?></p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>القسم : </p>
                                                    <p class="profile-information"><?php echo $rowmain['section']; ?></p>
                                                </div>
                                            </div>
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>تاريخ التعيين : </p>
                                                    <p class="profile-information"><?php echo $rowmain['app_date']; ?></p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>رقم بطاقة العمل : </p>
                                                    <p class="profile-information"><?php echo $rowmain['card_no']; ?></p>
                                                </div>
                                            </div>
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>نوع العقد : </p>
                                                    <p class="profile-information"><?php echo $rowmain['app_type']; ?></p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>تاريخ انتهاء عقد العمل : </p>
                                                    <p class="profile-information"><?php echo $rowmain['contract_exp']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="moreinps-container">
                                        <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                            <span><i class='bx bxs-photo-album' ></i> <p>المرفقات</p></span> 
                                            <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                            <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                        </button>
                                        <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>جواز السفر : </p>
                                                    <p class="profile-information">
                                                        <a href="<?php echo $rowmain['passport_residence'];?>" onClick="window.open('<?php echo $rowmain['passport_residence'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                            <?php echo basename($rowmain['passport_residence']);?>
                                                        </a>
                                                    </p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>المؤهل العملي : </p>
                                                    <p class="profile-information">
                                                        <a href="<?php echo $rowmain['practical_qualification'];?>" onClick="window.open('<?php echo $rowmain['practical_qualification'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                            <?php echo basename($rowmain['practical_qualification']);?>
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>السيرة الذاتية : </p>
                                                    <p class="profile-information">
                                                        <?php
                                                            $queryuserbiography = "SELECT * FROM user_attachments WHERE biography!='' AND user_id='$id'";
                                                            $resultuserbiography = mysqli_query($conn, $queryuserbiography);
                                                            while($rowuserbiography = mysqli_fetch_array($resultuserbiography)){
                                                        ?>
                                                        <a href="<?php echo $rowuserbiography['biography'];?>" onClick="window.open('<?php echo $rowuserbiography['biography'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                            <?php echo basename($rowuserbiography['biography']);?>
                                                        </a><br>
                                                        <?php
                                                            }
                                                        ?>
                                                    </p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>الهوية الاماراتية : </p>
                                                    <p class="profile-information">
                                                        <?php
                                                            $queryuseruaeresidence = "SELECT * FROM user_attachments WHERE uaeresidence!='' AND user_id='$id'";
                                                            $resultuseruaeresidence = mysqli_query($conn, $queryuseruaeresidence);
                                                            while($rowuseruaeresidence = mysqli_fetch_array($resultuseruaeresidence)){
                                                        ?>
                                                        <a href="<?php echo $rowuseruaeresidence['uaeresidence'];?>" onClick="window.open('<?php echo $rowuseruaeresidence['uaeresidence'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                            <?php echo basename($rowuseruaeresidence['uaeresidence']);?>
                                                        </a><br>
                                                        <?php
                                                            }
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>شهادة حسن السيرة و السلوك : </p>
                                                    <p class="profile-information">
                                                        <?php
                                                            $queryuserbehaviour = "SELECT * FROM user_attachments WHERE behaviour!='' AND user_id='$id'";
                                                            $resultuserbehaviour = mysqli_query($conn, $queryuserbehaviour);
                                                            while($rowuserbehaviour = mysqli_fetch_array($resultuserbehaviour)){
                                                        ?>
                                                        <a href="<?php echo $rowuserbehaviour['behaviour'];?>" onClick="window.open('<?php echo $rowuserbehaviour['behaviour'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                            <?php echo basename($rowuserbehaviour['behaviour']);?>
                                                        </a><br>
                                                        <?php
                                                            }
                                                        ?>
                                                    </p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>الشهادة الجامعية : </p>
                                                    <p class="profile-information">
                                                        <?php
                                                            $queryuseruniversity = "SELECT * FROM user_attachments WHERE university!='' AND user_id='$id'";
                                                            $resultuseruniversity = mysqli_query($conn, $queryuseruniversity);
                                                            while($rowuseruniversity = mysqli_fetch_array($resultuseruniversity)){
                                                        ?>
                                                        <a href="<?php echo $rowuseruniversity['university'];?>" onClick="window.open('<?php echo $rowuseruniversity['university'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                            <?php echo basename($rowuseruniversity['university']);?>
                                                        </a><br>
                                                        <?php
                                                            }
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>عقد العمل : </p>
                                                    <p class="profile-information">
                                                        <?php
                                                            $queryusercontract = "SELECT * FROM user_attachments WHERE contract!='' AND user_id='$id'";
                                                            $resultusercontract = mysqli_query($conn, $queryusercontract);
                                                            while($rowusercontract = mysqli_fetch_array($resultusercontract)){
                                                        ?>
                                                        <a href="<?php echo $rowusercontract['contract'];?>" onClick="window.open('<?php echo $rowusercontract['contract'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                            <?php echo basename($rowusercontract['contract']);?>
                                                        </a><br>
                                                        <?php
                                                            }
                                                        ?>
                                                    </p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>بطاقة العمل : </p>
                                                    <p class="profile-information">
                                                        <?php
                                                            $queryusercard = "SELECT * FROM user_attachments WHERE card!='' AND user_id='$id'";
                                                            $resultusercard = mysqli_query($conn, $queryusercard);
                                                            while($rowusercard = mysqli_fetch_array($resultusercard)){
                                                        ?>
                                                        <a href="<?php echo $rowusercard['card'];?>" onClick="window.open('<?php echo $rowusercard['card'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                            <?php echo basename($rowusercard['card']);?>
                                                        </a><br>
                                                        <?php
                                                            }
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>التأمين الصحي : </p>
                                                    <p class="profile-information">
                                                        <?php
                                                            $queryusersigorta = "SELECT * FROM user_attachments WHERE sigorta!='' AND user_id='$id'";
                                                            $resultusersigorta = mysqli_query($conn, $queryusersigorta);
                                                            while($rowusersigorta = mysqli_fetch_array($resultusersigorta)){
                                                        ?>
                                                        <a href="<?php echo $rowusersigorta['sigorta'];?>" onClick="window.open('<?php echo $rowusersigorta['sigorta'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                            <?php echo basename($rowusersigorta['sigorta']);?>
                                                        </a><br>
                                                        <?php
                                                            }
                                                        ?>
                                                    </p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>أُخرى : </p>
                                                    <p class="profile-information">
                                                        <a href="<?php echo $rowmain['more_files'];?>" onClick="window.open('<?php echo $rowmain['more_files'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                            <?php echo basename($rowmain['more_files']);?>
                                                        </a>
                                                        <?php
                                                            $queryuserother = "SELECT * FROM user_attachments WHERE other!='' AND user_id='$id'";
                                                            $resultuserother = mysqli_query($conn, $queryuserother);
                                                            while($rowuserother = mysqli_fetch_array($resultuserother)){
                                                        ?>
                                                        <a href="<?php echo $rowuserother['other'];?>" onClick="window.open('<?php echo $rowuserother['other'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                            <?php echo basename($rowuserother['other']);?>
                                                        </a><br>
                                                        <?php
                                                            }
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="moreinps-container">
                                        <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                            <span><i class='bx bxs-credit-card-alt' ></i> <p>مفردات الراتب</p></span> 
                                            <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                            <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                        </button>
                                        <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>الراتب الأساسي : </p>
                                                    <p class="profile-information"><?php echo $rowmain['basic_salary'];?> AED</p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>تذاكر السفر : </p>
                                                    <p class="profile-information"><?php echo $rowmain['travel_tickets'];?> AED</p>
                                                </div>
                                            </div>
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>البترول : </p>
                                                    <p class="profile-information"><?php echo $rowmain['oil_cost'];?> AED</p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>السكن : </p>
                                                    <p class="profile-information"><?php echo $rowmain['housing_cost'];?> AED</p>
                                                </div>
                                            </div>
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>المعيشة : </p>
                                                    <p class="profile-information"><?php echo $rowmain['living_cost'];?> AED</p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>اجمالي الراتب : </p>
                                                    <p class="profile-information">
                                                        <?php 
                                                            $total_salary = $rowmain['basic_salary']+$rowmain['travel_tickets']+$rowmain['oil_cost']+$rowmain['housing_cost']+$rowmain['living_cost'];
                                                            echo $total_salary.' AED';
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="moreinps-container">
                                        <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                            <span><i class='bx bxs-bank' ></i> <p>معلومات الحساب البنكي</p></span> 
                                            <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                            <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                        </button>
                                        <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>اسم البنك : </p>
                                                    <p class="profile-information"><?php echo $rowmain['bank_name'];?></p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>رقم الiban : </p>
                                                    <p class="profile-information"><?php echo $rowmain['iban'];?></p>
                                                </div>
                                            </div>
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>رقم الحساب : </p>
                                                    <p class="profile-information"><?php echo $rowmain['acc_no'];?></p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>طريقة الدفع : </p>
                                                    <p class="profile-information"><?php echo $rowmain['pay_way'];?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="moreinps-container">
                                        <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                            <span><i class='bx bx-plus-medical' ></i> <p>معلومات في حالات الطوارئ</p></span> 
                                            <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                            <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                        </button>
                                        <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>الاسم : </p>
                                                    <p class="profile-information"><?php echo $rowmain['emergency_name1'];?></p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>صلة القرابة : </p>
                                                    <p class="profile-information"><?php echo $rowmain['emergency_relate1'];?></p>
                                                </div>
                                            </div>
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px border-bottom: 1px solid #00000140">
                                                <div class="info-item">
                                                    <p>رقم الهاتف : </p>
                                                    <p class="profile-information"><?php echo $rowmain['emergency_tel1'];?></p>
                                                </div>
                                                <p></p>
                                                <p></p>
                                            </div>
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>الاسم : </p>
                                                    <p class="profile-information"><?php echo $rowmain['emergency_name2'];?></p>
                                                </div>
                                                <p></p>
                                                <div class="info-item">
                                                    <p>صلة القرابة : </p>
                                                    <p class="profile-information"><?php echo $rowmain['emergency_relate2'];?></p>
                                                </div>
                                            </div>
                                            <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                                <div class="info-item">
                                                    <p>رقم الهاتف : </p>
                                                    <p class="profile-information"><?php echo $rowmain['emergency_tel2'];?> AED</p>
                                                </div>
                                                <p></p>
                                                <p></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }}?>
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