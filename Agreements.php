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
                    if($row_permcheck['agr_rperm'] === '1'){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">الاتفاقيات</font></h3>
                            </div>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"></div>
                                <?php
                                    if($row_permcheck['agr_aperm'] === '1'){
                                ?>
                                <div class="table-header-icons" style="background-image: url('img/arrow.png'); margin-right: 30px;" onclick="addclient()"></div>
                                <?php
                                    }
                                    if($row_permcheck['agr_aperm'] === '1' || $row_permcheck['agr_eperm'] === '1'){
                                ?>
                                <div id="addclient-btn" class="modal-overlay" <?php if((isset($_GET['addmore']) && $_GET['addmore'] === '1') || (isset($_GET['edit']) && $_GET['edit'] === '1')){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "تعديل بيانات الاستشارة"; } else { echo 'استشارة جديدة'; }?></h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "location.href='Agreements.php';"; } else{ echo 'addclient()'; }?>">&times;</p>
                                            </div>
                                        </div>
                                        <?php
                                            if(isset($_GET['edit']) && $_GET['edit'] === '1'){
                                                $id = $_GET['id'];
                                                $equery = "SELECT * FROM consultations WHERE id='$id'";
                                                $eresult = mysqli_query($conn, $equery);
                                                $erow = mysqli_fetch_array($eresult);
                                            }
                                        ?>
                                        <form action="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo 'agredit.php'; } else{ echo 'agradd.php'; }?>" method="post" enctype="multipart/form-data" >
                                            <div class="addc-body">
                                                <div class="addc-body-form">
                                                    <div class="input-container">
                                                        <p class="input-parag">اسم الموكل<font style="color: #aa0820;">*</font></p>
                                                        <input class="form-input" name="client_name" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['client_name']; }?>" type="text" required>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">الجنسية</p>
                                                        <?php
                                                            if(isset($erow['nationality'])){
                                                                $selectedCountry = $erow['nationality'];
                                                            } else{
                                                                $selectedCountry = '';
                                                            }
                                                        ?>
                                                        <select class="table-header-selector" name="nationality" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;" dir="rtl">
                                                            <option value="" <?php echo ($selectedCountry == '') ? 'selected' : ''; ?>></option>
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
                                                    <?php if(isset($_GET['id']) && $_GET['id'] !== ''){?>
                                                    <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
                                                    <?php }?>
                                                    <div class="input-container">
                                                        <p class="input-parag">الهاتف</p>
                                                        <input type="text" class="form-input" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['telno']; }?>" name="telno">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">البريد الالكتروني</p>
                                                        <input type="email" class="form-input" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['email']; }?>" name="email">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">الحضور</p>
                                                        <select name="others1" class="table-header-selector" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                            <option></option>
                                                            <?php
                                                                $queryop = "SELECT * FROM user";
                                                                $resultop = mysqli_query($conn, $queryop);
                                                                
                                                                if(isset($_GET['edit']) && $_GET['edit'] === '1'){
                                                                    $selecteduser = $erow['others1'];
                                                                } else{
                                                                    $selecteduser = '';
                                                                }
                                                                if($resultop->num_rows > 0){
                                                                    while($rowop = mysqli_fetch_array($resultop)){
                                                            ?>
                                                            <option value="<?php echo $rowop['id'];?>" <?php if($rowop['id'] === $selecteduser){ echo 'selected'; }?>><?php echo $rowop['name'];?></option>
                                                            <?php }}?>
                                                        </select>
                                                        <select name="others2" class="table-header-selector" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                            <option></option>
                                                            <?php
                                                                $queryop = "SELECT * FROM user";
                                                                $resultop = mysqli_query($conn, $queryop);
                                                                
                                                                if(isset($_GET['edit']) && $_GET['edit'] === '1'){
                                                                    $selecteduser = $erow['others2'];
                                                                } else{
                                                                    $selecteduser = '';
                                                                }
                                                                if($resultop->num_rows > 0){
                                                                    while($rowop = mysqli_fetch_array($resultop)){
                                                            ?>
                                                            <option value="<?php echo $rowop['id'];?>" <?php if($rowop['id'] === $selecteduser){ echo 'selected'; }?>><?php echo $rowop['name'];?></option>
                                                            <?php }}?>
                                                        </select>
                                                        <select name="others3" class="table-header-selector" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                            <option></option>
                                                            <?php
                                                                $queryop = "SELECT * FROM user";
                                                                $resultop = mysqli_query($conn, $queryop);
                                                                
                                                                if(isset($_GET['edit']) && $_GET['edit'] === '1'){
                                                                    $selecteduser = $erow['others3'];
                                                                } else{
                                                                    $selecteduser = '';
                                                                }
                                                                if($resultop->num_rows > 0){
                                                                    while($rowop = mysqli_fetch_array($resultop)){
                                                            ?>
                                                            <option value="<?php echo $rowop['id'];?>" <?php if($rowop['id'] === $selecteduser){ echo 'selected'; }?>><?php echo $rowop['name'];?></option>
                                                            <?php }}?>
                                                        </select>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">الفرع</p>
                                                        <select name="place" class="table-header-selector" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                            <option value="دبي" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($erow['place'] === 'دبي'){ echo 'selected'; }}?>>دبي</option>
                                                            <option value="عجمان" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($erow['place'] === 'عجمان'){ echo 'selected'; }}?>>عجمان</option>
                                                            <option value="الشارقة" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($erow['place'] === 'الشارقة'){ echo 'selected'; }}?>>الشارقة</option>
                                                        </select>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">كيف عرفت عن المكتب</p>
                                                        <textarea class="form-input" name="way"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['way']; }?></textarea>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">تاريخ توقيع الاتفاقية</p>
                                                        <input type="date" class="form-input" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['sign_date']; }?>" name="sign_date">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="addc-footer">
                                                <button 
                                                style="cursor: <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['agr_eperm'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }} else{ if($row_permcheck['agr_aperm'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }}?>" 
                                                type="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['agr_eperm'] === '1'){ echo 'submit'; } else{ echo 'button'; }} else{ if($row_permcheck['agr_aperm'] === '1'){ echo 'submit'; } else{ echo 'button'; }}?>" 
                                                class="form-btn submit-btn">حفظ</button>
                                                <button 
                                                style="cursor: <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['agr_eperm'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }} else{ if($row_permcheck['agr_aperm'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }}?>" 
                                                type="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['agr_eperm'] === '1'){ echo 'submit'; } else{ echo 'button'; }} else{ if($row_permcheck['agr_aperm'] === '1'){ echo 'submit'; } else{ echo 'button'; }}?>" 
                                                name="submit_back" value="addmore" class="form-btn cancel-btn"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "حفظ و انشاء جديد"; } else{ echo 'حفظ و انشاء آخر'; }?></button>
                                                <button type="button" class="form-btn cancel-btn" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "location.href='Agreements.php';"; } else{ echo 'addclient()'; }?>">الغاء</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                        </div>
                        <div class="table-body">
                            <form action="agrdel.php" method="post">
                                <table class="info-table" id="myTable" style="width: 2000px;">
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
                                            <th style="position: sticky; right: 0; width: 40px;"><input type="checkbox" name="delall" id="selectAll"></th>
                                            <th>اسم الموكل</th>
                                            <th>الجنسية</th>
                                            <th>الهاتف</th>
                                            <th>الايميل</th>
                                            <th>الفرع</th>
                                            <th>الحضور</th>
                                            <th>كيف عرفت عن المكتب</th>
                                            <th>تاريخ توقيع الاتفاقية</th>
                                            <th width="150px">المُدخل</th>
                                        </tr>
                                    </thead>
                                    
                                    <?php
                                        $query = "SELECT * FROM consultations WHERE type='agreement' ORDER BY id DESC";
                                        $result = mysqli_query($conn, $query);
                                        if($result->num_rows > 0){
                                            while($row = mysqli_fetch_array($result)){
                                    ?>
                                    <tbody id="table1">
                                        <tr class="infotable-body">
                                            <td class="options-td" style="background-color: #fff;" width="50px">
                                                <i class='bx bx-dots-vertical-rounded bx-xs dropbtn' style="cursor: pointer;" onclick="toggleDropdown(event)"></i>
                                                <div class="dropdown">
                                                    <?php if($row_permcheck['agr_eperm'] === '1'){?>
                                                    <button type="button" onclick="location.href='Agreements.php?edit=1&id=<?php echo $row['id'];?>';">تعديل</button>
                                                    <?php 
                                                        }
                                                        if($row_permcheck['agr_dperm'] === '1'){
                                                    ?>
                                                    <button type="button" onclick="location.href='deleteagreement.php?id=<?php echo $row['id'];?>';">حذف</button>
                                                    <?php }?>
                                                </div>
                                            </td>
                                            <td style="position: sticky; right: 0; width: 40px;"><input type="checkbox" name="CheckedD[]" class="user-checkbox" value="<?php echo $row['id'];?>"></td>
                                            <td>
                                                <?php 
                                                    if(isset($row['client_name']) && $row['client_name'] !== ''){
                                                        echo $row['client_name'];
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if(isset($row['nationality']) && $row['nationality'] !== ''){
                                                        echo $row['nationality'];
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if(isset($row['telno']) && $row['telno'] !== ''){
                                                        echo $row['telno'];
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if(isset($row['email']) && $row['email'] !== ''){
                                                        echo $row['email'];
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if(isset($row['place']) && $row['place'] !== ''){
                                                        echo $row['place'];
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    $others1 = $row['others1'];
                                                    $queryo1 = "SELECT * FROM user WHERE id='$others1'";
                                                    $resulto1 = mysqli_query($conn, $queryo1);
                                                    if($resulto1->num_rows > 0){
                                                        $rowo1 = mysqli_fetch_array($resulto1);
                                                        $others1 = $rowo1['name'];
                                                    }
                                                    
                                                    $others2 = $row['others2'];
                                                    $queryo2 = "SELECT * FROM user WHERE id='$others2'";
                                                    $resulto2 = mysqli_query($conn, $queryo2);
                                                    if($resulto2->num_rows > 0){
                                                        $rowo2 = mysqli_fetch_array($resulto2);
                                                        $others2 = $rowo2['name'];
                                                    }
                                                    
                                                    $others3 = $row['others3'];
                                                    $queryo3 = "SELECT * FROM user WHERE id='$others3'";
                                                    $resulto3 = mysqli_query($conn, $queryo3);
                                                    if($resulto3->num_rows > 0){
                                                        $rowo3 = mysqli_fetch_array($resulto3);
                                                        $others3 = $rowo3['name'];
                                                    }
                                                    $others = '';
                                                    if(isset($others1) && $others1 !== ''){
                                                        if($others !== ''){
                                                            $others = "$others, $others1";
                                                        } else{
                                                            $others = $others1;
                                                        }
                                                    }
                                                    if(isset($others2) && $others2 !== ''){
                                                        if($others !== ''){
                                                            $others = "$others, $others2";
                                                        } else{
                                                            $others = $others2;
                                                        }
                                                    }
                                                    if(isset($others3) && $others3 !== ''){
                                                        if($others !== ''){
                                                            $others = "$others, $others3";
                                                        } else{
                                                            $others = $others3;
                                                        }
                                                    }
                                                    echo $others;
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if(isset($row['way']) && $row['way'] !== ''){
                                                        echo $row['way'];
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
                                            <td width="150px">
                                                <?php 
                                                    if(isset($row['timestamp']) && $row['timestamp'] !== ''){
                                                        $tmid = $row['timestamp'];
                                                        list($date, $time) = explode(" ", $tmid);
                                                        
                                                        $myid = $row['empid'];
                                                        $queryme = "SELECT * FROM user WHERE id='$myid'";
                                                        $resultme = mysqli_query($conn, $queryme);
                                                        $rowme = mysqli_fetch_array($resultme);
                                                        $myname = $rowme['name'];
                                                        echo $myname . '<br>' . $date;
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php }}?>
                                </table>
                            </div>
                            
                            <div class="table-footer">
                                <?php if($row_permcheck['agr_dperm'] === '1'){?>
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
        
        <script src="js/sweetAlerts2.js"></script>
        <script src="js/newWindow.js"></script>
        <script src="js/translate.js"></script>
        <script src="js/toggleSection.js"></script>
        <script src="js/dropfiles.js"></script>
        <script src="js/popups.js"></script>
        <script src="js/sweetAlerts.js"></script>
        <script src="js/randomPassGenerator.js"></script>
        <script src="js/tablePages.js"></script>
        <script src="js/checkAll.js"></script>
        <script src="js/dropdown.js"></script>
    </body>
</html>