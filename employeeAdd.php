<?php
    include_once 'connection.php';
    include_once 'login_check.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="rtl">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <title>محمد بني هاشم للمحاماة والاستشارات القانونية</title>
        <meta name="google-site-verification" content="_xmqQ0kTuDS9ta1v4E4je5rweWQ4qtH1l8_cnWro7Tk" />
        <meta name="robots" content="noindex, nofollow">
        <meta name="googlebot" content="noindex">
        <link rel="SHORTCUT ICON" href="images/favicon.ico">
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link  rel="alternate stylesheet" type="text/css" media="screen" title="selver-theme"  href="css/selver.css" />
        <link  rel="alternate stylesheet" type="text/css" media="screen" title="blue-theme"  href="css/blue.css" />
        <script type="text/javascript" src="js/switch.js.txt"></script>
        <SCRIPT LANGUAGE="JavaScript" SRC="../CalendarPopup.js"></SCRIPT>
        <SCRIPT LANGUAGE="JavaScript" ID="js13">var cal13 = new CalendarPopup();</SCRIPT>
        <script language="javascript" type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
        <script language="javascript" type="text/javascript">
            tinyMCE.init({
                mode : "exact",
                elements : "elm1,elm2,elm3,elm4",
                theme : "advanced",
                plugins : "style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable",
                theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,fontformat",
                theme_advanced_buttons1_add : "fontselect,fontsizeselect",
                theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,separator,forecolor,backcolor",
                theme_advanced_buttons2 : "bullist,numlist,separator,undo,redo,separator,link,unlink",
                theme_advanced_buttons3_add : "emotions,advhr,image,code,separator",
                theme_advanced_buttons3 : "charmap",
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_buttons2_add_before: "cut,copy,paste,separator,search,replace,separator",
                plugin_insertdate_dateFormat : "%Y-%m-%d",
                plugin_insertdate_timeFormat : "%H:%M:%S",	
                external_image_list_url : "example_image_list.php",
                extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]"
            });
            function fileBrowserCallBack(field_name, url, type, win) {
                my_window= window.open ("uploadImage.php","mywindow1","status=0,width=250,height=100");
                win.location.reload(true);
            }
        </script>
    </head>
    <body>
        <?php
            $query_del = "DELETE FROM user WHERE name = '' AND username = '' AND email = '' AND tel1 = '' AND work_place = ''";
            $result_del = mysqli_query($conn, $query_del);
        ?>
        
        <div class="container">
            <?php 
                include_once 'userInfo.php';
                include_once 'sidebar.php';
                
                $myid = $_SESSION['id'];
                $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
                $result_permcheck = mysqli_query($conn, $query_permcheck);
                $row_permcheck = mysqli_fetch_array($result_permcheck);
                
                if($row_permcheck['emp_perms_add'] === '1'){
            ?>
            
            <div class="l_data">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center" dir="rtl">
                            <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <?php include_once 'search_main.php';?>
                            </table><br />
                        </td>
                    </tr>
                    
                    <tr>
                        <td align="center" dir="rtl">
                            <div id="PrintMainDiv">
                                <form action="empadd.php" method="post" enctype="multipart/form-data" >
                                    <table width="99%" border="0" cellspacing="1" cellpadding="1" dir="rtl">
                                        <tr valign="top" bgcolor="#FFFFFF">
                                            <th align="right" colspan="2" dir="rtl" class="table2">
                                                <a href="index.php" class="Main">
                                                    <img src="images/homepage.png"   align="absmiddle" border="0"/> الصفحة الرئيسية
                                                </a> &raquo;  
                                                <a href="employees.php" class="Main">شؤون الموظفين والموارد</a> 
                                                &raquo; الموظفين العاملين والصلاحيات لمستخدمي البرنامج
                                            </th>
                                        </tr>
                                        <tr>
                                            <th align="right" colspan="2" dir="ltr" >
                                                <div style="display:block" id="item1000">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"  dir="rtl">
                                                        <tr valign="top" >
                                                            <td width="70%">
                                                                <table width="100%"  border="0" cellspacing="1" cellpadding="3" class="table" align="center"  dir="ltr"  bgcolor="#FFFFFF" >
                                                                    <tr id="edit">
                                                                        <th colspan="2"  class="header_table">اضافة موظف</th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th width="76%" align="right" >
                                                                            <input type="text" name="username" dir="rtl" value="" style="width:30%; text-align:center; color:#00F; font-weight:bold;"><br>
                                                                            <?php if(isset($_GET['u']) && $_GET['u'] == 0){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى ادخال اسم الدخول</span><?php }?>
                                                                        </th>
                                                                        <th width="24%" align="left"  dir="rtl">اسم الدخول :<?php if(isset($_GET['u']) && $_GET['u'] == 0){?><br><br><?php }?></th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th width="76%" align="right" >
                                                                            <input type="text" name="password" dir="rtl" value="" style="width:30%; text-align:center; color:#00F; font-weight:bold;"><br>
                                                                            <?php if(isset($_GET['p']) && $_GET['p'] == 0){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى ادخال كلمة المرور</span><?php }?>
                                                                        </th>
                                                                        <th width="24%" align="left"  dir="rtl">كلمة المرور :<?php if(isset($_GET['p']) && $_GET['p'] == 0){?><br><br><?php }?></th>
                                                                    </tr>
                                                                        
                                                                    <tr >
                                                                        <th width="76%" align="right" >
                                                                            <input type="text" name="name" dir="rtl" value=""  style="width:50%;"><br>
                                                                            <?php if(isset($_GET['n']) && $_GET['n'] == 0){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى ادخال اسم الموظف</span><?php }?>
                                                                        </th>
                                                                        <th width="24%" align="left"  dir="rtl">اسم الموظف :<?php if(isset($_GET['n']) && $_GET['n'] == 0){?><br><br><?php }?></th>
                                                                    </tr>

                                                                    <tr >
                                                                        <th width="76%" align="right"  dir="rtl">
                                                                            1 <input type="text" name="tel1" dir="rtl" value="" style="width:20%;"> <br />
                                                                            <?php if(isset($_GET['t']) && $_GET['t'] == 0){?><span class="blink" style="color:#FF0000; font-size:14px;">يجب عليك ادخال هاتف متحرك 1 على الاقل</span><br><?php }?>
                                                                            2 <input type="text" name="tel2" dir="rtl" value="" style="width:20%;">
                                                                        </th>
                                                                        <th width="24%" align="left"  dir="rtl" > هاتف متحرك  :</th>
                                                                    </tr>
                                                                        
                                                                    <tr >
                                                                        <th width="76%" align="right" >
                                                                            <input type="text" name="email" dir="ltr" value="" style="width:50%;"><br>
                                                                            <?php if(isset($_GET['e']) && $_GET['e'] == 0){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى ادخال البريد الالكتروني</span><?php }?>
                                                                        </th>
                                                                        <th width="24%" align="left"  dir="rtl" >البريد الالكتروني :<?php if(isset($_GET['e']) && $_GET['e'] == 0){?><br><br><?php }?></th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right" >
                                                                            <select name="nationality" dir="rtl" style="width:30%;">
                                                                                <option value="0" ></option>
                                                                                <option value="أثيوبيا"  >أثيوبيا</option>
                                                                                <option value="أذربيجان"  >أذربيجان</option>
                                                                                <option value="أراضي القطب الجنوبي"  >أراضي القطب الجنوبي</option>
                                                                                <option value="أرض المحيط الهندي البريطانية"  >أرض المحيط الهندي البريطانية</option>
                                                                                <option value="أرمينيا"  >أرمينيا</option>
                                                                                <option value="أروبا"  >أروبا</option>
                                                                                <option value="أسبانيا"  >أسبانيا</option>
                                                                                <option value="أستراليا"  >أستراليا</option>
                                                                                <option value="أستونيا"  >أستونيا</option>
                                                                                <option value="أفغانستان"  >أفغانستان</option>
                                                                                <option value="ألبانيا"  >ألبانيا</option>
                                                                                <option value="ألمانيا"  >ألمانيا</option>
                                                                                <option value="أنتيجوا باربدا"  >أنتيجوا باربدا</option>
                                                                                <option value="أنجولا"  >أنجولا</option>
                                                                                <option value="أنجويلا"  >أنجويلا</option>
                                                                                <option value="أندر سي فيتشر"  >أندر سي فيتشر</option>
                                                                                <option value="أندورا"  >أندورا</option>
                                                                                <option value="أندونيسيا"  >أندونيسيا</option>
                                                                                <option value="أورجواي"  >أورجواي</option>
                                                                                <option value="أوزبكستان"  >أوزبكستان</option>
                                                                                <option value="أوغندا"  >أوغندا</option>
                                                                                <option value="أوكرانيا"  >أوكرانيا</option>
                                                                                <option value="أيرلندا"  >أيرلندا</option>
                                                                                <option value="أيسلندا"  >أيسلندا</option>
                                                                                <option value="إريتريا"  >إريتريا</option>
                                                                                <option value="إيران"  >إيران</option>
                                                                                <option value="إيطاليا"  >إيطاليا</option>
                                                                                <option value="الأرجنتين"  >الأرجنتين</option>
                                                                                <option value="الأردن"  >الأردن</option>
                                                                                <option value="الإكوادور"  >الإكوادور</option>
                                                                                <option value="الإمارات العربية المتحدة"  >الإمارات العربية المتحدة</option>
                                                                                <option value="الباهاما"  >الباهاما</option>
                                                                                <option value="البحرين"  >البحرين</option>
                                                                                <option value="البرازيل"  >البرازيل</option>
                                                                                <option value="البرتغال"  >البرتغال</option>
                                                                                <option value="البلوفر"  >البلوفر</option>
                                                                                <option value="البوسنة والهرسك"  >البوسنة والهرسك</option>
                                                                                <option value="البولينيسيا الفرنسية"  >البولينيسيا الفرنسية</option>
                                                                                <option value="الجابون"  >الجابون</option>
                                                                                <option value="الجاكت"  >الجاكت</option>
                                                                                <option value="الجزائر"  >الجزائر</option>
                                                                                <option value="الجزر البريطانية"  >الجزر البريطانية</option>
                                                                                <option value="الدانمارك"  >الدانمارك</option>
                                                                                <option value="السلفادور"  >السلفادور</option>
                                                                                <option value="السنغال"  >السنغال</option>
                                                                                <option value="السودان"  >السودان</option>
                                                                                <option value="السويد"  >السويد</option>
                                                                                <option value="الصومال"  >الصومال</option>
                                                                                <option value="الصين"  >الصين</option>
                                                                                <option value="الضفة الغربية"  >الضفة الغربية</option>
                                                                                <option value="العراق"  >العراق</option>
                                                                                <option value="الفاتيكان"  >الفاتيكان</option>
                                                                                <option value="الفلبين"  >الفلبين</option>
                                                                                <option value="القارة القطبية الجنوبية"  >القارة القطبية الجنوبية</option>
                                                                                <option value="الكاميرون"  >الكاميرون</option>
                                                                                <option value="الكونغو"  >الكونغو</option>
                                                                                <option value="الكويت"  >الكويت</option>
                                                                                <option value="المارتينيخ"  >المارتينيخ</option>
                                                                                <option value="المالديف"  >المالديف</option>
                                                                                <option value="المجر"  >المجر</option>
                                                                                <option value="المحيطات"  >المحيطات</option>
                                                                                <option value="المغرب"  >المغرب</option>
                                                                                <option value="المكسيك"  >المكسيك</option>
                                                                                <option value="المملكة العربية السعودية"  >المملكة العربية السعودية</option>
                                                                                <option value="المملكة المتحدة"  >المملكة المتحدة</option>
                                                                                <option value="النرويج"  >النرويج</option>
                                                                                <option value="النمسا"  >النمسا</option>
                                                                                <option value="النيجر"  >النيجر</option>
                                                                                <option value="الهند"  >الهند</option>
                                                                                <option value="الهند باساس دي"  >الهند باساس دي</option>
                                                                                <option value="الولايات المتحدة الأمريكية"  >الولايات المتحدة الأمريكية</option>
                                                                                <option value="اليابان"  >اليابان</option>
                                                                                <option value="اليمن"  >اليمن</option>
                                                                                <option value="اليونان"  >اليونان</option>
                                                                                <option value="باراجواي"  >باراجواي</option>
                                                                                <option value="باربادوس"  >باربادوس</option>
                                                                                <option value="باكستان"  >باكستان</option>
                                                                                <option value="بالو"  >بالو</option>
                                                                                <option value="برمودا"  >برمودا</option>
                                                                                <option value="بروناي"  >بروناي</option>
                                                                                <option value="بلجيكا"  >بلجيكا</option>
                                                                                <option value="بلغاريا"  >بلغاريا</option>
                                                                                <option value="بنجلاديش"  >بنجلاديش</option>
                                                                                <option value="بنما"  >بنما</option>
                                                                                <option value="بنين"  >بنين</option>
                                                                                <option value="بوتان"  >بوتان</option>
                                                                                <option value="بوتسوانا"  >بوتسوانا</option>
                                                                                <option value="بورتوريكو"  >بورتوريكو</option>
                                                                                <option value="بوركينا فاسو"  >بوركينا فاسو</option>
                                                                                <option value="بورما"  >بورما</option>
                                                                                <option value="بوروندي"  >بوروندي</option>
                                                                                <option value="بولندا"  >بولندا</option>
                                                                                <option value="بوليفيا"  >بوليفيا</option>
                                                                                <option value="بيرو"  >بيرو</option>
                                                                                <option value="بيليز"  >بيليز</option>
                                                                                <option value="تايلاند"  >تايلاند</option>
                                                                                <option value="تايوان"  >تايوان</option>
                                                                                <option value="تراينيداد توباجو"  >تراينيداد توباجو</option>
                                                                                <option value="تركيا"  >تركيا</option>
                                                                                <option value="تشاد"  >تشاد</option>
                                                                                <option value="تنزانيا"  >تنزانيا</option>
                                                                                <option value="توجو"  >توجو</option>
                                                                                <option value="توركمنستان"  >توركمنستان</option>
                                                                                <option value="توفالو"  >توفالو</option>
                                                                                <option value="توكيلاو"  >توكيلاو</option>
                                                                                <option value="تونجا"  >تونجا</option>
                                                                                <option value="تونس"  >تونس</option>
                                                                                <option value="تيمور الشرقية"  >تيمور الشرقية</option>
                                                                                <option value="جامبيا"  >جامبيا</option>
                                                                                <option value="جاميكا"  >جاميكا</option>
                                                                                <option value="جان ميين"  >جان ميين</option>
                                                                                <option value="جبل طارق"  >جبل طارق</option>
                                                                                <option value="جرينادا"  >جرينادا</option>
                                                                                <option value="جرينلاند"  >جرينلاند</option>
                                                                                <option value="جزر أشمور كارتير"  >جزر أشمور كارتير</option>
                                                                                <option value="جزر الأنتيل الهولندية"  >جزر الأنتيل الهولندية</option>
                                                                                <option value="جزر البحر المرجانية"  >جزر البحر المرجانية</option>
                                                                                <option value="جزر القمر"  >جزر القمر</option>
                                                                                <option value="جزر الكوك"  >جزر الكوك</option>
                                                                                <option value="جزر باراسيل"  >جزر باراسيل</option>
                                                                                <option value="جزر بيتكيرن"  >جزر بيتكيرن</option>
                                                                                <option value="جزر جلوريوسو"  >جزر جلوريوسو</option>
                                                                                <option value="جزر جوز الهند"  >جزر جوز الهند</option>
                                                                                <option value="جزر سبراتلي"  >جزر سبراتلي</option>
                                                                                <option value="جزر سولومون"  >جزر سولومون</option>
                                                                                <option value="جزر فارو"  >جزر فارو</option>
                                                                                <option value="جزر فوكلاند"  >جزر فوكلاند</option>
                                                                                <option value="جزر كيكوس التركية"  >جزر كيكوس التركية</option>
                                                                                <option value="جزر مارشال"  >جزر مارشال</option>
                                                                                <option value="جزر مكدونالد"  >جزر مكدونالد</option>
                                                                                <option value="جزرالكايمان"  >جزرالكايمان</option>
                                                                                <option value="جزيرة الأوروبا"  >جزيرة الأوروبا</option>
                                                                                <option value="جزيرة الرجل"  >جزيرة الرجل</option>
                                                                                <option value="جزيرة الكريستمز"  >جزيرة الكريستمز</option>
                                                                                <option value="جزيرة بوفيت"  >جزيرة بوفيت</option>
                                                                                <option value="جزيرة تروميلين"  >جزيرة تروميلين</option>
                                                                                <option value="جزيرة جوان دونوفا"  >جزيرة جوان دونوفا</option>
                                                                                <option value="جزيرة كليبيرتون"  >جزيرة كليبيرتون</option>
                                                                                <option value="جزيرة نورفولك"  >جزيرة نورفولك</option>
                                                                                <option value="جمهورية إفريقيا الوسطى"  >جمهورية إفريقيا الوسطى</option>
                                                                                <option value="جمهورية التشيك"  >جمهورية التشيك</option>
                                                                                <option value="جمهورية الدومينكان"  >جمهورية الدومينكان</option>
                                                                                <option value="جمهورية الكونغو الديمقراطية"  >جمهورية الكونغو الديمقراطية</option>
                                                                                <option value="جنوب أفريقيا"  >جنوب أفريقيا</option>
                                                                                <option value="جوادلوب"  >جوادلوب</option>
                                                                                <option value="جورجيا"  >جورجيا</option>
                                                                                <option value="جورجيا الجنوبية"  >جورجيا الجنوبية</option>
                                                                                <option value="جويانا"  >جويانا</option>
                                                                                <option value="جيبوتي"  >جيبوتي</option>
                                                                                <option value="دومينيكا"  >دومينيكا</option>
                                                                                <option value="رواندا"  >رواندا</option>
                                                                                <option value="روسيا"  >روسيا</option>
                                                                                <option value="روسيا البيضاء"  >روسيا البيضاء</option>
                                                                                <option value="رومانيا"  >رومانيا</option>
                                                                                <option value="ريونيون"  >ريونيون</option>
                                                                                <option value="زامبيا"  >زامبيا</option>
                                                                                <option value="زيمبابوي"  >زيمبابوي</option>
                                                                                <option value="سامو"  >سامو</option>
                                                                                <option value="سان مارينو"  >سان مارينو</option>
                                                                                <option value="سانت بيير ميكيلون"  >سانت بيير ميكيلون</option>
                                                                                <option value="سانت فينسينت"  >سانت فينسينت</option>
                                                                                <option value="سانت كيتس نيفيس"  >سانت كيتس نيفيس</option>
                                                                                <option value="سانت لوتشيا"  >سانت لوتشيا</option>
                                                                                <option value="سانت هيلينا"  >سانت هيلينا</option>
                                                                                <option value="ساو توم"  >ساو توم</option>
                                                                                <option value="سريلانكا"  >سريلانكا</option>
                                                                                <option value="سفالبارد"  >سفالبارد</option>
                                                                                <option value="سلوفاكيا"  >سلوفاكيا</option>
                                                                                <option value="سلوفانيا"  >سلوفانيا</option>
                                                                                <option value="سنغافورة"  >سنغافورة</option>
                                                                                <option value="سوازيلاند"  >سوازيلاند</option>
                                                                                <option value="سوريا"  >سوريا</option>
                                                                                <option value="سورينام"  >سورينام</option>
                                                                                <option value="سويسرا"  >سويسرا</option>
                                                                                <option value="سيراليون"  >سيراليون</option>
                                                                                <option value="سيشل"  >سيشل</option>
                                                                                <option value="شيلي"  >شيلي</option>
                                                                                <option value="طاجكستان"  >طاجكستان</option>
                                                                                <option value="عمان"  >عمان</option>
                                                                                <option value="غانا"  >غانا</option>
                                                                                <option value="غواتيمالا"  >غواتيمالا</option>
                                                                                <option value="غينيا"  >غينيا</option>
                                                                                <option value="غينيا الاستوائية"  >غينيا الاستوائية</option>
                                                                                <option value="غينيا الجديدة"  >غينيا الجديدة</option>
                                                                                <option value="غينيا الفرنسية"  >غينيا الفرنسية</option>
                                                                                <option value="فانوتو"  >فانوتو</option>
                                                                                <option value="فرنسا"  >فرنسا</option>
                                                                                <option value="فلسطين"  >فلسطين</option>
                                                                                <option value="فنزويلا"  >فنزويلا</option>
                                                                                <option value="فنلندا"  >فنلندا</option>
                                                                                <option value="فيتنام"  >فيتنام</option>
                                                                                <option value="فيجي"  >فيجي</option>
                                                                                <option value="قبرص"  >قبرص</option>
                                                                                <option value="قطر"  >قطر</option>
                                                                                <option value="كازاكستان"  >كازاكستان</option>
                                                                                <option value="كالدونيا الجديدة"  >كالدونيا الجديدة</option>
                                                                                <option value="كامبوديا"  >كامبوديا</option>
                                                                                <option value="كرواتيا"  >كرواتيا</option>
                                                                                <option value="كندا"  >كندا</option>
                                                                                <option value="كوبا"  >كوبا</option>
                                                                                <option value="كوتي دلفوير"  >كوتي دلفوير</option>
                                                                                <option value="كورجستان"  >كورجستان</option>
                                                                                <option value="كوريا الجنوبية"  >كوريا الجنوبية</option>
                                                                                <option value="كوريا الشمالية"  >كوريا الشمالية</option>
                                                                                <option value="كوستا ريكا"  >كوستا ريكا</option>
                                                                                <option value="كولومبيا"  >كولومبيا</option>
                                                                                <option value="كيب فيردي"  >كيب فيردي</option>
                                                                                <option value="كيريباتي"  >كيريباتي</option>
                                                                                <option value="كينيا"  >كينيا</option>
                                                                                <option value="لاتفيا"  >لاتفيا</option>
                                                                                <option value="لاو"  >لاو</option>
                                                                                <option value="لبنان"  >لبنان</option>
                                                                                <option value="لوكسمبورج"  >لوكسمبورج</option>
                                                                                <option value="ليبيا"  >ليبيا</option>
                                                                                <option value="ليبيريا"  >ليبيريا</option>
                                                                                <option value="ليتوانيا"  >ليتوانيا</option>
                                                                                <option value="ليختنشتين"  >ليختنشتين</option>
                                                                                <option value="ليسوتو"  >ليسوتو</option>
                                                                                <option value="ماكاو"  >ماكاو</option>
                                                                                <option value="مالاوي"  >مالاوي</option>
                                                                                <option value="مالطا"  >مالطا</option>
                                                                                <option value="مالي"  >مالي</option>
                                                                                <option value="ماليزيا"  >ماليزيا</option>
                                                                                <option value="مدغشقر"  >مدغشقر</option>
                                                                                <option value="مصر"  >مصر</option>
                                                                                <option value="مقدونيا"  >مقدونيا</option>
                                                                                <option value="منغوليا"  >منغوليا</option>
                                                                                <option value="موريتانيا"  >موريتانيا</option>
                                                                                <option value="موريشيوس"  >موريشيوس</option>
                                                                                <option value="موزمبيق"  >موزمبيق</option>
                                                                                <option value="مولدافيا"  >مولدافيا</option>
                                                                                <option value="موناكو"  >موناكو</option>
                                                                                <option value="مونتسيرات"  >مونتسيرات</option>
                                                                                <option value="ميكرونيسيا"  >ميكرونيسيا</option>
                                                                                <option value="ميوت"  >ميوت</option>
                                                                                <option value="ناميبيا"  >ناميبيا</option>
                                                                                <option value="نورو"  >نورو</option>
                                                                                <option value="نومانز لاند"  >نومانز لاند</option>
                                                                                <option value="ني"  >ني</option>
                                                                                <option value="نيبال"  >نيبال</option>
                                                                                <option value="نيجيريا"  >نيجيريا</option>
                                                                                <option value="نيكاراجوا"  >نيكاراجوا</option>
                                                                                <option value="نيوزيلندا"  >نيوزيلندا</option>
                                                                                <option value="هايتي"  >هايتي</option>
                                                                                <option value="هندوراس"  >هندوراس</option>
                                                                                <option value="هولندا"  >هولندا</option>
                                                                                <option value="هونج كونج"  >هونج كونج</option>
                                                                                <option value="واليس فيوتنا"  >واليس فيوتنا</option>
                                                                                <option value="يوغسلافيا"  >يوغسلافيا</option>
                                                                            </select>
                                                                        </th>
                                                                        <th align="left"  dir="rtl">الجنسية :</th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <input type="radio" name="sex" value="ذكر"> ذكر <br>
                                                                            <input type="radio" name="sex" value="انثى"> انثى
                                                                        </th>
                                                                        <th align="left"  dir="rtl">الجنس :</th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <input type="text" dir="rtl" name="social">
                                                                        </th>
                                                                        <th align="left"  dir="rtl">الحالة الاجتماعية :</th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <textarea dir="rtl" wrap="physical" rows="2" style="width:98%" name="address"></textarea>
                                                                        </th>
                                                                        <th align="left"  dir="rtl">عنوان السكن :</th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <input type="text" name="passport_no" dir="rtl" value="" style="width:20%; text-align:center"> &nbsp;&nbsp;&nbsp; تاريخ الانتهاء : 
                                                                            <input type="date" name="passport_exp" dir="rtl" size="10" value="" style="text-align:center; font-weight:bold; color:#F00" > <br>
                                                                            <?php if(isset($_GET['pn']) && $_GET['pn'] == 0){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى ادخال رقم جواز السفر</span><?php }?>
                                                                        </th>
                                                                        <th align="left"  dir="rtl">رقم جواز السفر :<?php if(isset($_GET['pn']) && $_GET['pn'] == 0){?><br><br><?php }?></th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <input type="text" name="residence_no" dir="rtl" size="10" value="" style="text-align:center; font-weight:bold; color:#F00" > تاريخ صدور الإقامة :
                                                                            <input type="date" name="residence_date" dir="rtl" size="10" value="" style="text-align:center; font-weight:bold; color:#F00" > تاريخ انتهاء الإقامة : 
                                                                            <input type="date" name="residence_exp" dir="rtl" size="10" value="" style="text-align:center; font-weight:bold; color:#F00" >
                                                                        </th>
                                                                        <th align="left"  dir="rtl">رقم الهوية/الاقامة : </th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <input type="text" name="card_no" dir="rtl" size="10" value="" style="text-align:center; font-weight:bold; color:#F00" > تاريخ التعيين : 
                                                                            <input type="date" name="app_date" dir="rtl" size="10" value="" style="text-align:center; font-weight:bold; color:#F00" > تاريخ انتهاء عقد العمل : 
                                                                            <input type="date" name="contract_exp" dir="rtl" size="10" value="" style="text-align:center; font-weight:bold; color:#F00" > 
                                                                        </th>
                                                                        <th align="left"  dir="rtl">رقم بطاقة العمل : </th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <input type="date" name="dob" dir="rtl" size="10" value="" style="text-align:center; font-weight:bold; color:#F00" > 
                                                                        </th>
                                                                        <th align="left"  dir="rtl">تاريخ الميلاد :</th>
                                                                    </tr>
        
                                                                    <tr >
                                                                        <th align="right" dir="rtl">
                                                                            <select name="app_type">
                                                                                <option value="كامل">كامل</option>
                                                                                <option value="جزئي">جزئي</option>
                                                                                <option value="متدرب">متدرب</option>
                                                                            </select>
                                                                        </th>
                                                                        <th align="left"  dir="rtl">نوع العقد :</th>
                                                                    </tr>
        
                                                                    <tr >
                                                                        <th align="right" dir="rtl">
                                                                            <select name="responsible">
                                                                                <option value=""></option>
                                                                                <?php
                                                                                    $queryru = "SELECT * FROM user";
                                                                                    $resultru = mysqli_query($conn, $queryru);
                                                                                    while($rowru = mysqli_fetch_array($resultru)){
                                                                                ?>
                                                                                <option value="<?php echo $rowru['id'];?>"><?php echo $rowru['name'];?></option>
                                                                                <?php
                                                                                    }
                                                                                ?>
                                                                            </select>
                                                                        </th>
                                                                        <th align="left"  dir="rtl">المسؤول المباشر :</th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <select name="job_title" dir="rtl" style="width:50%;">
                                                                                <?php 
                                                                                $queryjs = "SELECT * FROM positions";
                                                                                $resultjs = mysqli_query($conn, $queryjs);
                                                                                if($resultjs->num_rows > 0){
                                                                                    while($rowjs=mysqli_fetch_array($resultjs)){
                                                                                ?>
                                                                                
                                                                                <option value="<?php echo $rowjs['id'];?>"><?php echo $rowjs['position_name'];?></option>
                                                                                
                                                                                <?php
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                            
                                                                            <img src="images/addmore.jpg"  title="اضافة"  onClick="open('selector/AddPosition.php','Pic','width=800 height=600 scrollbars=yes')" align="absmiddle"  style="cursor:pointer"/>
                                                                        </th>
                                                                        <th align="left"  dir="rtl">المسمي الوظيفي :</th>
                                                                    </tr>
        
                                                                    <tr >
                                                                        <th align="right" dir="rtl">
                                                                            <input type="text" name="section">
                                                                        </th>
                                                                        <th align="left"  dir="rtl">القسم :</th>
                                                                    </tr>
        
                                                                    <tr >
                                                                        <th align="right" dir="rtl">
                                                                            <select name="work_place" dir="rtl" style="width:50%;">
                                                                                <option value="الشارقة"  >الشارقة</option>
                                                                                <option value="دبي" >دبي</option>
                                                                                <option value="عجمان"  >عجمان</option>
                                                                            </select> 
                                                                        </th>
                                                                        <th align="left"  dir="rtl">يعمل فى فرع :</th>
                                                                    </tr>
                                                                    
                                                                    <tr valign="top" >
                                                                        <th align="right"  dir="ltr" valign="top">
                                                                            <select name="signin_perm" dir="rtl" >
                                                                                <option value="1"   >فعال</option>
                                                                                <option value="0" >غير فعال</option>
                                                                            </select>
                                                                        </th>
                                                                        <th align="left"  dir="rtl">حالة الدخول على البرنامج :</th>
                                                                    </tr>
                                                                    
                                                                    <tr valign="top" >
                                                                        <th align="right"  dir="rtl" valign="top">
                                                                            <input type="file" name="photo" dir="ltr" style="font-size:12px">
                                                                        </th>
                                                                        <th align="left"  dir="rtl">صورة شخصية :</th>
                                                                    </tr>
                                                                    
                                                                    <tr valign="top" >
                                                                        <th align="right"  dir="rtl" valign="top">
                                                                            <input type="file" name="passport_residence" dir="ltr" style="font-size:12px"> 
                                                                        </th>
                                                                        <th align="left"  dir="rtl">جواز السفر :</th>
                                                                    </tr>
                                                                    
                                                                    <tr valign="top" >
                                                                        <th align="right"  dir="rtl" valign="top">
                                                                            <input type="file" name="uaeresidence" dir="ltr" style="font-size:12px"> 
                                                                        </th>
                                                                        <th align="left"  dir="rtl">الإقامة :</th>
                                                                    </tr>
                                                                    
                                                                    <tr valign="top" >
                                                                        <th align="right"  dir="rtl" valign="top">
                                                                            <input type="file" name="practical_qualification" dir="ltr" style="font-size:12px"> 
                                                                        </th>
                                                                        <th align="left"  dir="rtl">المؤهل العملي :</th>
                                                                    </tr>
                                                                    
                                                                    <tr valign="top" >
                                                                        <th align="right"  dir="rtl" valign="top">
                                                                            <input type="file" name="more_files" dir="ltr" style="font-size:12px"> 
                                                                        </th>
                                                                        <th align="left"  dir="rtl">مرفقات اضافية :</th>
                                                                    </tr>
        
                                                                    <tr >
                                                                        <th colspan="2">
                                                                            <table width="100%" align="center" border="1" cellspacing="0"  bordercolor="#CCCCCC" cellpadding="0"  bgcolor="#FFFFFF" dir="rtl">
                                                                                <tr>
                                                                                    <td align="center" colspan="7" class="header_table">الصلاحيات على النظام </td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">&nbsp;</td>
                                                                                    <td align="center">استعلام</td>
                                                                                    <td align="center">اضافة</td>
                                                                                    <td align="center">تعديل</td>
                                                                                    <td align="center">حذف</td>
                                                                                    <td align="center">ارشفة</td>
                                                                                    <td align="center">طباعة</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left"> صلاحيات الموظفين :</td>
                                                                                    <td align="center"></td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="emp_perms_add" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="emp_perms_edit" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="emp_perms_delete" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left"> ملفات القضايا :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="cfiles_rperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="cfiles_aperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="cfiles_eperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="cfiles_dperm" style="border:0;background : transparent;"  value="1"  >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="cfiles_archive_perm" style="border:0;background : transparent;"  value="1"  >
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">الجلسات :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="session_rperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="session_aperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="session_eperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="session_dperm" style="border:0;background : transparent;"  value="1"  >
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                </tr>
            
                                                                                <tr>
                                                                                    <td align="left">درجة التقاضي :</td>
                                                                                    <td align="center"></td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="degree_aperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="degree_eperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="degree_dperm" style="border:0;background : transparent;"  value="1"  >
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">المذكرات :</td>
                                                                                    <td align="center"></td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="note_aperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="note_eperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="note_dperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                </tr>
            
                                                                                <tr>
                                                                                    <td align="left">المرفقات :</td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="attachments_dperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                </tr>
                                                                                    
                                                                                <tr>
                                                                                    <td align="left"> الأعمال الإدارية :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admjobs_rperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admjobs_aperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admjobs_eperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admjobs_dperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admjobs_pperm" style="border:0;background : transparent;"  value="1"  >
                                                                                    </td>
                                                                                </tr>
            
                                                                                <tr>
                                                                                    <td align="left">انواع الأعمال الإدارية :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admjobtypes_rperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admjobtypes_aperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admjobtypes_eperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admjobtypes_dperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                </tr>
                                                                                    
                                                                                <tr>
                                                                                    <td align="left">اعمال ادارية خاصة :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admprivjobs_rperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
            
                                                                                <tr>
                                                                                    <td width="52%" align="left">الموكلين / الخصوم :</td>
                                                                                    <td width="10%" align="center">
                                                                                        <input type="checkbox" name="clients_rperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td width="10%" align="center">
                                                                                        <input type="checkbox" name="clients_aperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td width="10%" align="center">
                                                                                        <input type="checkbox" name="clients_eperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td width="10%" align="center">
                                                                                        <input type="checkbox" name="clients_dperm" style="border:0;background : transparent;"  value="1" >
                                                                                    </td>
                                                                                    <td width="9%" align="right">&nbsp;</td>
                                                                                    <td width="9%" align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">رول الجلسات  :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="sessionrole_rperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">سجلات العمل :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="logs_rperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center"></td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="logs_dperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">القبول المبدئي للاجازات :</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="vacf_aperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">القبول النهائي للاجازات :</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="vacl_aperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">تقارير الموظفين :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="emp_rperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="emp_aperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="emp_eperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="emp_dperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="center" colspan="7" class="header_table">الصلاحيات السكرتارية </td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">سجل المكالمات :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="call_rperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="call_aperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="call_eperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="call_dperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">مواعيد الموكلين :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="csched_rperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="csched_aperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="csched_eperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="csched_dperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">كشف قائمة الارهاب المحلية goAML :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="goaml_rperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="goaml_aperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="goaml_dperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">المدرجين تحت قائمة الارهاب :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="terror_rperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="terror_eperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="terror_dperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">الاستشارات القانونية :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="cons_rperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="cons_aperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="cons_eperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="cons_dperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">الاتفاقيات :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="agr_rperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="agr_aperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="agr_eperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="agr_dperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <th colspan="7" align="right" class="header_table">صلاحيات الحسابات </th>
                                                                                </tr>
                                                                                
                                                                                <tr> 
                                                                                    <td align="left">قسم المالية : </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accfinance_rperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accfinance_eperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr> 
                                                                                    <td align="left">البنود الرئيسية : </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accmainterms_rperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accmainterms_aperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accmainterms_eperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accmainterms_dperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
        
                                                                                <tr>
                                                                                    <td align="left">البنود الفرعية :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accsecterms_rperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accsecterms_aperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accsecterms_eperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accsecterms_dperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                    
                                                                                <tr>
                                                                                    <td align="left">حسابات البنوك :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accbankaccs_rperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accbankaccs_aperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accbankaccs_eperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accbankaccs_dperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                    
                                                                                <tr>
                                                                                    <td align="left">اتعاب القضايا :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="acccasecost_rperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="acccasecost_aperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
        
                                                                                <tr>
                                                                                    <td align="left">الايرادات :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accrevenues_rperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accrevenues_aperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accrevenues_dperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                    
                                                                                <tr>
                                                                                    <td align="left">المصروفات :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accexpenses_rperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accexpenses_aperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accexpenses_dperm" style="border:0;background : transparent;"  value="1"  />
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                            </table>
                                                                        </th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
    
                                                        <tr >
                                                            <th colspan="2">
                                                                <input type="button" value=" افراغ الحقول" onClick="location.href='employeeAdd.php';" class="button"> 
                                                                <input type="submit" value=" حفظ البيانات " class="button" name="save_user" >
                                                            </th>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </th>
                            </tr>
                        </table>
                    </form>

                    <table width="99%"  border="0" cellspacing="2" cellpadding="2" align="center" >
                        <tr > 
                            <th >
                                <table width="100%"  border="0" cellspacing="1" cellpadding="1" class="table" dir="ltr">
                                    <form action="empadd.php" method="post">
                                        <tr  class="header_table">
                                            <th dir="ltr"> الكل 
                                                <input type="checkbox" style="border:0px;" onClick="var T=null; T=document.getElementsByName('datachk');  for(var y=0; y<T.length; y++)	T[y].checked=checked;">
                                            </th>
                                            <th width="6%" dir="ltr">الحالة</th>
                                            <th width="4%" dir="ltr">تعديل</th>
                                            <th width="9%" dir="ltr">ت.اخر دخول</th>
                                            <th width="10%" dir="ltr">اجمالى الراتب</th>
                                            <th width="10%" dir="ltr">عدد الإنذارات</th>
                                            <th width="10%" dir="ltr">الفرع</th>
                                            <th width="14%" dir="ltr">المسمي الوظيفي</th>
                                            <th width="10%" dir="ltr">هاتف التواصل</th>
                                            <th width="29%" dir="ltr">الموظف</th>
                                            <th width="14%" dir="ltr">اسم المستخدم  </th>
                                        </tr>

                                        <?php
                                            $query_r = "SELECT * FROM user";
                                            $result_r = mysqli_query($conn, $query_r);

                                            if($result_r->num_rows > 0){
                                                while($rowr=mysqli_fetch_array($result_r)){
                                        ?>

                                        <tr valign="top" class="title1" bgcolor="#ffffff" onMouseOver="bgColor='#eaeaea'" onMouseOut="bgColor='#ffffff'">
                                            <th width="1%">
                                                <input type="checkbox" name="CheckedD[]" style="border:0;background : transparent;" value= <?php echo $rowr['id'];?>>
                                            </th>
                                            <th width="1%"><?php if(isset($rowr['signin_perm']) && $rowr['signin_perm'] == 1){?><img src='images/ok_copy.gif' alt='فعال' border='0'><?php } else if(isset($rowr['signin_perm']) && $rowr['signin_perm'] == 0){?><img src='images/agt_action_fail_copy.gif' alt='غير فعال' border='0'><?php }?></th>
                                            <th class="reg" <?php if($row_permcheck['emp_perms_edit'] === '1'){?> onclick="location.href='employeeEdit.php?id=<?php echo $rowr['id'];?>'" style="cursor:pointer" <?php }?>>
                                                <?php if($row_permcheck['emp_perms_edit'] === '1'){?>
                                                <img src="images/Edit.png"  border="0"  title="اضغط هنا للتعديل" style="cursor:pointer" />
                                                <?php }?>
                                            </th>
                                            <?php 
                                                if(isset($rowr['lastlogin']) && $rowr['lastlogin'] !== ''){
                                                    $lastlogin = $rowr['lastlogin'];
                                                    list($mn, $time, $date) = explode(' ', $lastlogin);
                                                }
                                            ?>
                                            <th dir="rtl" width="20%"><?php if(isset($rowr['lastlogin']) && $rowr['lastlogin'] !== ''){echo $date;}else{echo '-';}?><br>مرات الدخول (<font color="#FF0000"><?php if(isset($rowr['logins_num']) && $rowr['logins_num'] !== ''){echo $rowr['logins_num'];} else{ echo '0';}?></font>)</th>
                                            <th ><font color="#FF0000" style="font-size:20px; font-weight:bold"><?php $emid1 = $rowr['id']; $querysal = "SELECT * FROM user WHERE id='$emid1'"; $resultsal = mysqli_query($conn, $querysal); $rowsal = mysqli_fetch_array($resultsal); $sal1 = $rowsal['basic_salary']; $sal2 = $rowsal['travel_tickets']; $sal3 = $rowsal['oil_cost']; $sal4 = $rowsal['housing_cost']; $sal5 = $rowsal['living_cost']; $totalsal = $sal1+$sal2+$sal3+$sal4+$sal5; echo $totalsal;?></font></th>
                                            <th  style="font-size:18px; color:#00F"><?php $emid = $rowr['id']; $querywars = "SELECT COUNT(*) as countwars FROM warnings WHERE emp_id='$emid'"; $resultwars = mysqli_query($conn, $querywars); $rowwars = mysqli_fetch_array($resultwars); echo $rowwars['countwars'];?></th>
                                            <th ><?php if(isset($rowr['work_place']) && $rowr['work_place'] !== ''){ echo $rowr['work_place'];}?></th>
                                            <th ><?php if(isset($rowr['job_title']) && $rowr['job_title'] !== ''){ $psjt = $rowr['job_title']; $queryposti = "SELECT * FROM positions WHERE id='$psjt'"; $resultposti=mysqli_query($conn, $queryposti); if($resultposti->num_rows > 0){$rowposti=mysqli_fetch_array($resultposti); echo $rowposti['position_name'];}}?></th>
                                            <?php
                                                include_once 'AES256.php';
                                                $tel1 = $rowr['tel1'];
                                                $decrypted_tel1 = openssl_decrypt($tel1, $cipher, $key, $options, $iv);

                                                $tel2 = $rowr['tel2'];
                                                $decrypted_tel2 = openssl_decrypt($tel2, $cipher, $key, $options, $iv);
                                            ?>
                                            <th ><?php if(isset($rowr['tel1']) && $rowr['tel1'] !== ''){ echo $decrypted_tel1;}?><br /></th>
                                            <th ><?php if(isset($rowr['name']) && $rowr['name'] !== ''){ echo $rowr['name'];}?></th>
                                            <th  style="color:#00F" ><?php if(isset($rowr['username']) && $rowr['username'] !== ''){ echo $rowr['username'];}?></th>
                                        </tr>

                                        <?php 
                                                }
                                            }
                                        ?>
                                            
                                        <tr>
                                            <?php if(isset($_GET['error']) && $_GET['error'] === 'null'){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى اختيار الاسماء المراد تغيير نظام تفعيلهم</span><?php }?>
                                            <th align="center"></th>
                                            <th align="center">
                                                <?php if($row_permcheck['emp_perms_edit'] === '1'){?>
                                                <input name="upd_userperms" type="submit" value="غير / فعال" class="button" >
                                                <?php }?>
                                            </th>
                                            <th colspan="9" align="left">&nbsp;</th>
                                        </tr>
                                    </form>
                                </table>
                            </th>
                        </tr>
                    </table><br>
                </div>
            </td>
        </tr>
    </table>
</div>
<?php }?>
</div>

<div class="footer">محمد بني هاشم للمحاماة والاستشارات القانونية<img alt="" src="images/f.png" width="29" height="31" /><img alt="" src="images/w.png" width="29" height="31" /></div>
</div>
</body>
</html>

<script>		
    function funHS(vID2){
        if(document.getElementById("item" + vID2).style.display=="none"){
            document.getElementById("item" + vID2).style.display="block";
        } else{
            document.getElementById("item" + vID2).style.display="none";
        }
    }
</script>