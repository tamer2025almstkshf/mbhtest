<?php
    include_once 'connection.php';
    include_once 'login_check.php';

    if(!isset($_GET['id'])){
        header("Location: employeeAdd.php");
        exit();
    }
    
    if($_GET['id'] === ''){
        header("Location: employeeAdd.php");
        exit();
    }
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
        
        <div class="container">
            <?php 
                include_once 'userInfo.php';
                include_once 'sidebar.php';
                
                $myid = $_SESSION['id'];
                $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
                $result_permcheck = mysqli_query($conn, $query_permcheck);
                $row_permcheck = mysqli_fetch_array($result_permcheck);
                
                if($row_permcheck['emp_perms_edit'] === '1'){
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
                                <SCRIPT LANGUAGE="JavaScript" SRC="CalendarPopup.js"></SCRIPT>
                                <SCRIPT LANGUAGE="JavaScript" ID="js13">var cal13 = new CalendarPopup();</SCRIPT>
                                <form action="editemp.php" method="post" enctype="multipart/form-data" >
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
                                        
                                        <?php
                                            include_once 'AES256.php';
                                            $id = $_GET['id'];
                                            
                                            $query = "SELECT * FROM user WHERE id = '$id'";
                                            $result = mysqli_query($conn, $query);
                                            
                                            if($result->num_rows == 0){
                                                exit();
                                            }
                                            
                                            $row = mysqli_fetch_array($result);
                                        ?>
                                        
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
                                                                    <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
                                                                    
                                                                    <tr >
                                                                        <th width="76%" align="right" >
                                                                            <input type="text" name="username" dir="rtl" value="<?php if(isset($row['username']) && $row['username'] !== ''){echo $row['username'];}?>" style="width:30%; text-align:center; color:#00F; font-weight:bold;"><br>
                                                                            <?php if(isset($_GET['u']) && $_GET['u'] == 0){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى ادخال اسم الدخول</span><?php }?>
                                                                        </th>
                                                                        <th width="24%" align="left"  dir="rtl">اسم الدخول :<?php if(isset($_GET['u']) && $_GET['u'] == 0){?><br><br><?php }?></th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th width="76%" align="right" >
                                                                            <?php
                                                                                if(isset($row['password']) && $row['password'] !== ''){
                                                                                    $password = $row['password'];
                                                                                    $decrypted_password = openssl_decrypt($password, $cipher, $key, $options, $iv);
                                                                                }
                                                                            ?>
                                                                            <input type="text" name="password" dir="rtl" value="<?php if(isset($row['password']) && $row['password'] !== ''){echo $decrypted_password;}?>" style="width:30%; text-align:center; color:#00F; font-weight:bold;"><br>
                                                                            <?php if(isset($_GET['p']) && $_GET['p'] == 0){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى ادخال كلمة المرور</span><?php }?>
                                                                        </th>
                                                                        <th width="24%" align="left"  dir="rtl">كلمة المرور :<?php if(isset($_GET['p']) && $_GET['p'] == 0){?><br><br><?php }?></th>
                                                                    </tr>
                                                                        
                                                                    <tr >
                                                                        <th width="76%" align="right" >
                                                                            <input type="text" name="name" dir="rtl" value="<?php if(isset($row['name']) && $row['name'] !== ''){echo $row['name'];}?>"  style="width:50%;"><br>
                                                                            <?php if(isset($_GET['n']) && $_GET['n'] == 0){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى ادخال اسم الموظف</span><?php }?>
                                                                        </th>
                                                                        <th width="24%" align="left"  dir="rtl">اسم الموظف :<?php if(isset($_GET['n']) && $_GET['n'] == 0){?><br><br><?php }?></th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th width="76%" align="right"  dir="rtl">
                                                                            <?php
                                                                                if(isset($row['tel1']) && $row['tel1'] !== ''){
                                                                                    $tel1 = $row['tel1'];
                                                                                    $decrypted_tel1 = openssl_decrypt($tel1, $cipher, $key, $options, $iv);
                                                                                }
                                                                                
                                                                                if(isset($row['tel2']) && $row['tel2'] !== ''){
                                                                                    $tel2 = $row['tel2'];
                                                                                    $decrypted_tel2 = openssl_decrypt($tel2, $cipher, $key, $options, $iv);
                                                                                }
                                                                            ?>
                                                                            1 <input type="text" name="tel1" dir="rtl" value="<?php if(isset($row['tel1']) && $row['tel1'] !== ''){echo $decrypted_tel1;}?>" style="width:20%;"><br />
                                                                            <?php if(isset($_GET['t']) && $_GET['t'] == 0){?><span class="blink" style="color:#FF0000; font-size:14px;">يجب عليك ادخال هاتف متحرك 1 على الاقل</span><br><?php }?>
                                                                            2 <input type="text" name="tel2" dir="rtl" value="<?php if(isset($row['tel2']) && $row['tel2'] !== ''){echo $decrypted_tel2;}?>" style="width:20%;">
                                                                        </th>
                                                                        <th width="24%" align="left"  dir="rtl" > هاتف متحرك  :</th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th width="76%" align="right" >
                                                                            <?php
                                                                            if(isset($row['email']) && $row['email'] !== ''){
                                                                                $email = $row['email'];
                                                                                $decrypted_email = openssl_decrypt($email, $cipher, $key, $options, $iv);
                                                                            }
                                                                            ?>
                                                                            <input type="text" name="email" dir="ltr" value="<?php if(isset($row['email']) && $row['email'] !== ''){echo $decrypted_email;}?>" style="width:50%;"><br>
                                                                            <?php if(isset($_GET['e']) && $_GET['e'] == 0){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى ادخال البريد الالكتروني</span><?php }?>
                                                                        </th>
                                                                        <th width="24%" align="left"  dir="rtl" >البريد الالكتروني :<?php if(isset($_GET['e']) && $_GET['e'] == 0){?><br><br><?php }?></th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right" >
                                                                            <?php if(isset($row['nationality'])){ $selectedCountry = $row['nationality']; }else{ $selectedCountry = ''; }?>
                                                                            <select name="nationality" dir="rtl" style="width:30%;">
                                                                                <option value="0" <?php echo ($selectedCountry == '0') ? 'selected' : ''; ?>></option>
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
                                                                        </th>
                                                                        <th align="left"  dir="rtl">الجنسية :</th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <input type="radio" name="sex" <?php if($row['sex'] === 'ذكر'){ echo 'checked'; }?> value="ذكر"> ذكر <br>
                                                                            <input type="radio" name="sex" <?php if($row['sex'] === 'انثى'){ echo 'checked'; }?> value="انثى"> انثى
                                                                        </th>
                                                                        <th align="left"  dir="rtl">الجنس :</th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <input type="text" dir="rtl" name="social" value="<?php echo $row['social'];?>">
                                                                        </th>
                                                                        <th align="left"  dir="rtl">الحالة الاجتماعية :</th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <?php
                                                                            if(isset($row['address']) && $row['address'] !== ''){
                                                                                $address = $row['address'];
                                                                                $decrypted_address = openssl_decrypt($address, $cipher, $key, $options, $iv);
                                                                            }
                                                                            ?>
                                                                            <textarea dir="rtl" wrap="physical" rows="2" style="width:98%" name="address"><?php if(isset($row['address']) && $row['address'] !== ''){echo $decrypted_address;}?></textarea>
                                                                        </th>
                                                                        <th align="left"  dir="rtl">عنوان السكن :</th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <?php
                                                                            if(isset($row['passport_no']) && $row['passport_no'] !== ''){
                                                                                $passno = $row['passport_no'];
                                                                                $decrypted_passno = openssl_decrypt($passno, $cipher, $key, $options, $iv);
                                                                            }
                                                                            ?>
                                                                            <input type="text" name="passport_no" dir="rtl" value="<?php if(isset($row['passport_no']) && $row['passport_no'] !== ''){echo $decrypted_passno;}?>" style="width:20%; text-align:center"> &nbsp;&nbsp;&nbsp; تاريخ الانتهاء : 
                                                                            <input type="date" name="passport_exp" dir="rtl" size="10" value="<?php if(isset($row['passport_exp']) && $row['passport_exp'] !== ''){echo date('Y-m-d', strtotime($row['passport_exp']));}?>" style="text-align:center; font-weight:bold; color:#F00" > <br>
                                                                            <?php if(isset($_GET['pn']) && $_GET['pn'] == 0){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى ادخال رقم جواز السفر</span><?php }?>
                                                                        </th>
                                                                        <th align="left"  dir="rtl">رقم جواز السفر :<?php if(isset($_GET['pn']) && $_GET['pn'] == 0){?><br><br><?php }?></th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <input type="text" name="residence_no" dir="rtl" size="10" value="<?php if(isset($row['residence_no']) && $row['residence_no'] !== ''){echo $row['residence_no'];}?>" style="text-align:center; font-weight:bold; color:#F00" > تاريخ صدور الإقامة :
                                                                            <input type="date" name="residence_date" dir="rtl" size="10" value="<?php if(isset($row['residence_date']) && $row['residence_date'] !== ''){echo date('Y-m-d', strtotime($row['residence_date']));}?>" style="text-align:center; font-weight:bold; color:#F00" > تاريخ انتهاء الإقامة : 
                                                                            <input type="date" name="residence_exp" dir="rtl" size="10" value="<?php if(isset($row['residence_exp']) && $row['residence_exp'] !== ''){echo date('Y-m-d', strtotime($row['residence_exp']));}?>" style="text-align:center; font-weight:bold; color:#F00" >
                                                                        </th>
                                                                        <th align="left"  dir="rtl">رقم الهوية/الاقامة : </th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <input type="text" name="card_no" dir="rtl" size="10" value="<?php if(isset($row['card_no']) && $row['card_no'] !== ''){echo $row['card_no'];}?>" style="text-align:center; font-weight:bold; color:#F00" > تاريخ التعيين : 
                                                                            <input type="date" name="app_date" dir="rtl" size="10" value="<?php if(isset($row['app_date']) && $row['app_date'] !== ''){echo date('Y-m-d', strtotime($row['app_date']));}?>" style="text-align:center; font-weight:bold; color:#F00" > تاريخ انتهاء عقد العمل : 
                                                                            <input type="date" name="contract_exp" dir="rtl" size="10" value="<?php if(isset($row['contract_exp']) && $row['contract_exp'] !== ''){echo date('Y-m-d', strtotime($row['contract_exp']));}?>" style="text-align:center; font-weight:bold; color:#F00" > 
                                                                        </th>
                                                                        <th align="left"  dir="rtl">رقم بطاقة العمل : </th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <input type="date" name="dob" dir="rtl" size="10" value="<?php if(isset($row['dob']) && $row['dob'] !== ''){echo date('Y-m-d', strtotime($row['dob']));}?>" style="text-align:center; font-weight:bold; color:#F00" > 
                                                                        </th>
                                                                        <th align="left"  dir="rtl">تاريخ الميلاد :</th>
                                                                    </tr>
        
                                                                    <tr >
                                                                        <th align="right" dir="rtl">
                                                                            <select name="app_type">
                                                                                <option <?php if($row['app_type'] === 'كامل'){ echo "selected"; }?> value="كامل">كامل</option>
                                                                                <option <?php if($row['app_type'] === 'جزئي'){ echo "selected"; }?> value="جزئي">جزئي</option>
                                                                                <option <?php if($row['app_type'] === 'متدرب'){ echo "selected"; }?> value="متدرب">متدرب</option>
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
                                                                                <option value="<?php echo $rowru['id'];?>" <?php if($row['responsible'] === $rowru['id']){ echo 'selected'; }?>><?php echo $rowru['name'];?></option>
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
                                                                                    if(isset($row['job_title'])){ 
                                                                                        $select_jobtitle = $row['job_title']; 
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
                                                                            
                                                                            <img src="images/addmore.jpg"  title="اضافة"  onClick="open('AddPostion.php','Pic','width=800 height=600 scrollbars=yes')" align="absmiddle"  style="cursor:pointer"/>
                                                                        </th>
                                                                        <th align="left"  dir="rtl">المسمي الوظيفي :</th>
                                                                    </tr>
        
                                                                    <tr >
                                                                        <th align="right" dir="rtl">
                                                                            <input type="text" name="section" value="<?php echo $row['section'];?>">
                                                                        </th>
                                                                        <th align="left"  dir="rtl">القسم :</th>
                                                                    </tr>
        
                                                                    <tr >
                                                                        <th align="right" dir="rtl">
                                                                            <?php if(isset($row['work_place'])){ $select_workplace = $row['work_place']; }else{ $select_workplace = ''; }?>
                                                                            <select name="work_place" dir="rtl" style="width:50%;">
                                                                                <option value="الشارقة" <?php echo ($select_workplace == 'الشارقة') ? 'selected' : '';?> >الشارقة</option>
                                                                                <option value="دبي" <?php echo ($select_workplace == 'دبي') ? 'selected' : '';?> >دبي</option>
                                                                                <option value="عجمان" <?php echo ($select_workplace == 'عجمان') ? 'selected' : '';?> >عجمان</option>
                                                                            </select> 
                                                                        </th>
                                                                        <th align="left"  dir="rtl">يعمل فى فرع :</th>
                                                                    </tr>
                                                                    
                                                                    <tr valign="top" >
                                                                        <th align="right"  dir="ltr" valign="top">
                                                                            <?php if(isset($row['signin_perm'])){ $selected_signinperm = $row['signin_perm']; }else{ $selected_signinperm = ''; }?>
                                                                            <select name="signin_perm" dir="rtl" >
                                                                                <option value="1" <?php echo ($selected_signinperm == '1') ? 'selected' : '';?> >فعال</option>
                                                                                <option value="0" <?php echo ($selected_signinperm == '0') ? 'selected' : '';?> >غير فعال</option>
                                                                            </select>
                                                                        </th>
                                                                        <th align="left"  dir="rtl">حالة الدخول على البرنامج :</th>
                                                                    </tr>
                                                                    
                                                                    <tr valign="top" >
                                                                        <th align="right"  dir="rtl" valign="top">
                                                                            <input type="file" name="photo" dir="ltr" style="font-size:12px">

                                                                            <?php if(isset($row['photo']) && $row['photo'] !== ''){?>
                                                                            <a href="<?php echo $row['photo'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                                                <img src="images/DownloadB.png"  align="absmiddle" border="0"/>
                                                                            </a> 

                                                                            <input type="checkbox" name="remove_photo" value="1"  style="border:0"/> حذف المرفق
                                                                            <?php }?>
                                                                        </th>
                                                                        <th align="left"  dir="rtl">صورة شخصية :</th>
                                                                    </tr>
                                                                    
                                                                    <tr valign="top" >
                                                                        <th align="right"  dir="rtl" valign="top">
                                                                            <input type="file" name="passport_residence" dir="ltr" style="font-size:12px">

                                                                            <?php if(isset($row['passport_residence']) && $row['passport_residence'] !== ''){?>
                                                                            <a href="<?php echo $row['passport_residence'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                                                <img src="images/DownloadB.png"  align="absmiddle" border="0"/>
                                                                            </a> 

                                                                            <input type="checkbox" name="remove_pr" value="1"  style="border:0"/> حذف المرفق
                                                                            <?php }?>
                                                                        </th>
                                                                        <th align="left"  dir="rtl">جواز السفر :</th>
                                                                    </tr>
                                                                    
                                                                    <tr valign="top" >
                                                                        <th align="right"  dir="rtl" valign="top">
                                                                            <input type="file" name="uaeresidence" dir="ltr" style="font-size:12px">

                                                                            <?php 
                                                                                $userrrid = $_GET['id'];
                                                                                $queryatts = "SELECT * FROM user_attachments WHERE user_id='$userrrid'";
                                                                                $resultatts = mysqli_query($conn, $queryatts);
                                                                                
                                                                                while($rowatts = mysqli_fetch_array($resultatts)){
                                                                                    if(isset($rowatts['uaeresidence']) && $rowatts['uaeresidence'] !== ''){
                                                                            ?>
                                                                            <a href="<?php echo $rowatts['uaeresidence'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                                                <img src="images/DownloadB.png"  align="absmiddle" border="0"/>
                                                                            </a> 

                                                                            <input type="checkbox" name="remove_residence" value="<?php echo $rowatts['uaeresidence'];?>"  style="border:0"/> حذف المرفق
                                                                            <?php
                                                                                    }
                                                                                }
                                                                            ?>
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
                                                                                        <input type="checkbox" name="emp_perms_add" style="border:0;background : transparent;"  value="1" <?php if(isset($row['emp_perms_add']) && $row['emp_perms_add'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="emp_perms_edit" style="border:0;background : transparent;"  value="1" <?php if(isset($row['emp_perms_edit']) && $row['emp_perms_edit'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="emp_perms_delete" style="border:0;background : transparent;"  value="1" <?php if(isset($row['emp_perms_delete']) && $row['emp_perms_delete'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left"> ملفات القضايا :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="cfiles_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['cfiles_rperm']) && $row['cfiles_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="cfiles_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['cfiles_aperm']) && $row['cfiles_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="cfiles_eperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['cfiles_eperm']) && $row['cfiles_eperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="cfiles_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['cfiles_dperm']) && $row['cfiles_dperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="cfiles_archive_perm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['cfiles_archive_perm']) && $row['cfiles_archive_perm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">الجلسات :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="session_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['session_rperm']) && $row['session_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="session_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['session_aperm']) && $row['session_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="session_eperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['session_eperm']) && $row['session_eperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="session_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['session_dperm']) && $row['session_dperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                </tr>
            
                                                                                <tr>
                                                                                    <td align="left">درجة التقاضي :</td>
                                                                                    <td align="center"></td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="degree_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['degree_aperm']) && $row['degree_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="degree_eperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['degree_eperm']) && $row['degree_eperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="degree_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['degree_dperm']) && $row['degree_dperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">المذكرات :</td>
                                                                                    <td align="center"></td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="note_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['note_aperm']) && $row['note_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="note_eperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['note_eperm']) && $row['note_eperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="note_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['note_dperm']) && $row['note_dperm'] == 1){ echo 'checked';}?>>
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
                                                                                        <input type="checkbox" name="attachments_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['attachments_dperm']) && $row['attachments_dperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                </tr>
                                                                                    
                                                                                <tr>
                                                                                    <td align="left"> الأعمال الإدارية :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admjobs_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['admjobs_rperm']) && $row['admjobs_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admjobs_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['admjobs_aperm']) && $row['admjobs_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admjobs_eperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['admjobs_eperm']) && $row['admjobs_eperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admjobs_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['admjobs_dperm']) && $row['admjobs_dperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admjobs_pperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['admjobs_pperm']) && $row['admjobs_pperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                </tr>
            
                                                                                <tr>
                                                                                    <td align="left">انواع الأعمال الإدارية :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admjobtypes_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['admjobtypes_rperm']) && $row['admjobtypes_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admjobtypes_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['admjobtypes_aperm']) && $row['admjobtypes_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admjobtypes_eperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['admjobtypes_eperm']) && $row['admjobtypes_eperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admjobtypes_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['admjobtypes_dperm']) && $row['admjobtypes_dperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                </tr>
                                                                                    
                                                                                <tr>
                                                                                    <td align="left">اعمال ادارية خاصة :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="admprivjobs_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['admprivjobs_rperm']) && $row['admprivjobs_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
            
                                                                                <tr>
                                                                                    <td width="52%" align="left"> الموكلين / الخصوم :</td>
                                                                                    <td width="10%" align="center">
                                                                                        <input type="checkbox" name="clients_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['clients_rperm']) && $row['clients_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td width="10%" align="center">
                                                                                        <input type="checkbox" name="clients_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['clients_aperm']) && $row['clients_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td width="10%" align="center">
                                                                                        <input type="checkbox" name="clients_eperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['clients_eperm']) && $row['clients_eperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td width="10%" align="center">
                                                                                        <input type="checkbox" name="clients_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['clients_dperm']) && $row['clients_dperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td width="9%" align="right">&nbsp;</td>
                                                                                    <td width="9%" align="right">&nbsp;</td>
                                                                                </tr>
        
                                                                                <tr>
                                                                                    <td align="left">رول الجلسات  :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="sessionrole_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['sessionrole_rperm']) && $row['sessionrole_rperm'] == 1){ echo 'checked';}?>>
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
                                                                                        <input type="checkbox" name="logs_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['logs_rperm']) && $row['logs_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center"></td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="logs_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['logs_dperm']) && $row['logs_dperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">القبول المبدئي للاجازات :</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="vacf_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['vacf_aperm']) && $row['vacf_aperm'] == 1){ echo 'checked';}?>>
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
                                                                                        <input type="checkbox" name="vacl_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['vacl_aperm']) && $row['vacl_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">تقارير الموظفين :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="emp_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['emp_rperm']) && $row['emp_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="emp_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['emp_aperm']) && $row['emp_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="emp_eperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['emp_eperm']) && $row['emp_eperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="emp_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['emp_dperm']) && $row['emp_dperm'] == 1){ echo 'checked';}?>>
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
                                                                                        <input type="checkbox" name="call_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['call_rperm']) && $row['call_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="call_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['call_aperm']) && $row['call_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="call_eperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['call_eperm']) && $row['call_eperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="call_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['call_dperm']) && $row['call_dperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">مواعيد الموكلين :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="csched_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['csched_rperm']) && $row['csched_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="csched_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['csched_aperm']) && $row['csched_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="csched_eperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['csched_eperm']) && $row['csched_eperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="csched_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['csched_dperm']) && $row['csched_dperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">كشف قائمة الارهاب المحلية goAML :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="goaml_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['goaml_rperm']) && $row['goaml_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="goaml_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['goaml_aperm']) && $row['goaml_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="goaml_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['goaml_dperm']) && $row['goaml_dperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">المدرجين تحت قائمة الارهاب :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="terror_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['terror_rperm']) && $row['terror_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="terror_eperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['terror_eperm']) && $row['terror_eperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="terror_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['terror_dperm']) && $row['terror_dperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">الاستشارات القانونية :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="cons_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['cons_rperm']) && $row['cons_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="cons_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['cons_aperm']) && $row['cons_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="cons_eperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['cons_eperm']) && $row['cons_eperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="cons_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['cons_dperm']) && $row['cons_dperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">الاتفاقيات :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="agr_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['agr_rperm']) && $row['agr_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="agr_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['agr_aperm']) && $row['agr_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="agr_eperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['agr_eperm']) && $row['agr_eperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="agr_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['agr_dperm']) && $row['agr_dperm'] == 1){ echo 'checked';}?>>
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
                                                                                        <input type="checkbox" name="accfinance_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accfinance_rperm']) && $row['accfinance_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accfinance_eperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accfinance_eperm']) && $row['accfinance_eperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                
                                                                                <tr> 
                                                                                    <td align="left">البنود الرئيسية : </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accmainterms_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accmainterms_rperm']) && $row['accmainterms_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accmainterms_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accmainterms_aperm']) && $row['accmainterms_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accmainterms_eperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accmainterms_eperm']) && $row['accmainterms_eperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accmainterms_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accmainterms_dperm']) && $row['accmainterms_dperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
        
                                                                                <tr>
                                                                                    <td align="left">البنود الفرعية :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accsecterms_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accsecterms_rperm']) && $row['accsecterms_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accsecterms_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accsecterms_aperm']) && $row['accsecterms_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accsecterms_eperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accsecterms_eperm']) && $row['accsecterms_eperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accsecterms_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accsecterms_dperm']) && $row['accsecterms_dperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                    
                                                                                <tr>
                                                                                    <td align="left">حسابات البنوك :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accbankaccs_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accbankaccs_rperm']) && $row['accbankaccs_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accbankaccs_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accbankaccs_aperm']) && $row['accbankaccs_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accbankaccs_eperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accbankaccs_eperm']) && $row['accbankaccs_eperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accbankaccs_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accbankaccs_dperm']) && $row['accbankaccs_dperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                    
                                                                                <tr>
                                                                                    <td align="left">اتعاب القضايا :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="acccasecost_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['acccasecost_rperm']) && $row['acccasecost_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="acccasecost_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['acccasecost_aperm']) && $row['acccasecost_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
        
                                                                                <tr>
                                                                                    <td align="left">الايرادات :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accrevenues_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accrevenues_rperm']) && $row['accrevenues_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accrevenues_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accrevenues_aperm']) && $row['accrevenues_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accrevenues_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accrevenues_dperm']) && $row['accrevenues_dperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                    <td align="right">&nbsp;</td>
                                                                                </tr>
                                                                                    
                                                                                <tr>
                                                                                    <td align="left">المصروفات :</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accexpenses_rperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accexpenses_rperm']) && $row['accexpenses_rperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accexpenses_aperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accexpenses_aperm']) && $row['accexpenses_aperm'] == 1){ echo 'checked';}?>>
                                                                                    </td>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">
                                                                                        <input type="checkbox" name="accexpenses_dperm" style="border:0;background : transparent;"  value="1" <?php if(isset($row['accexpenses_dperm']) && $row['accexpenses_dperm'] == 1){ echo 'checked';}?>>
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

                                                        <th colspan="2">
                                                            <table width="100%" border="0" cellspacing="1" cellpadding="1"  bgcolor="#F9FB91">
                                                                <tr align="center">
                                                                    <td><img src="images/SalaryBT.png" onClick="javascript:funHS(101010)" align="absmiddle"  style="cursor:pointer" /></td>
                                                                    <td><img src="images/HolBT.png" onClick="javascript:funHS(101020)" align="absmiddle"  style="cursor:pointer" /></td>
                                                                    <td><img src="images/WarningBT.png" onClick="javascript:funHS(101030)" align="absmiddle"  style="cursor:pointer" /></td>
                                                                </tr>
                                                        
                                                                <tr align="center">
                                                                    <td colspan="3">
                                                                        <div style="display:none; background-color:#FFB7B7" id="item101010">
                                                                            <table width="100%" border="0" cellspacing="2" cellpadding="2" dir="rtl">
                                                                                <tr>
                                                                                    <td width="27%" align="left">الراتب الاساسي :</td>
                                                                                    <td width="73%" align="right">
                                                                                        <input type="text" name="basic_salary" dir="rtl" <?php if(!isset($row['basic_salary'])){?>$value="0"<?php }else{?> value="<?php echo $row['basic_salary'];?>" <?php }?> style="width:50%; text-align:center; font-size:18px; color:#F00; font-weight:bold">
                                                                                    </td>
                                                                                </tr>
                                                    
                                                                                <tr>
                                                                                    <td align="left">تذاكر سفر :</td>
                                                                                    <td align="right">
                                                                                        <input type="text" name="travel_tickets" dir="rtl" <?php if(!isset($row['travel_tickets'])){?>$value="0"<?php }else{?> value="<?php echo $row['travel_tickets'];?>" <?php }?> style="width:50%; text-align:center; font-size:18px; color:#000; font-weight:bold">
                                                                                    </td>
                                                                                </tr>
                                                    
                                                                                <tr>
                                                                                    <td align="left">بترول :</td>
                                                                                    <td align="right">
                                                                                        <input type="text" name="oil_cost" dir="rtl"  <?php if(!isset($row['oil_cost'])){?>$value="0"<?php }else{?> value="<?php echo $row['oil_cost'];?>" <?php }?>  style="width:50%; text-align:center; font-size:18px; color:#000; font-weight:bold">
                                                                                    </td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">سكن :</td>
                                                                                    <td align="right">
                                                                                        <input type="text" name="housing_cost" dir="rtl"  <?php if(!isset($row['housing_cost'])){?>$value="0"<?php }else{?> value="<?php echo $row['housing_cost'];?>" <?php }?>  style="width:50%; text-align:center; font-size:18px; color:#000; font-weight:bold">
                                                                                    </td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">معيشة :</td>
                                                                                    <td align="right">
                                                                                        <input type="text" name="living_cost" dir="rtl"  <?php if(!isset($row['living_cost'])){?>$value="0"<?php }else{?> value="<?php echo $row['living_cost'];?>" <?php }?>  style="width:50%; text-align:center; font-size:18px; color:#000; font-weight:bold">
                                                                                    </td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td colspan="2" align="center" bgcolor="#FFFFFF" class="table">اجمالى الراتب 
                                                                                        ( <font color="#0000FF" style="font-size:20px; font-weight:bold">
                                                                                            <?php
                                                                                                if(isset($row['basic_salary']) && $row['basic_salary'] != 0){
                                                                                                    $basic_salary = $row['basic_salary'];
                                                                                                } else{
                                                                                                    $basic_salary = 0;
                                                                                                }
                                                                                                
                                                                                                if(isset($row['travel_tickets']) && $row['travel_tickets'] != 0){
                                                                                                    $travel_tickets = $row['travel_tickets'];
                                                                                                } else{
                                                                                                    $travel_tickets = 0;
                                                                                                }
                                                                                                
                                                                                                if(isset($row['oil_cost']) && $row['oil_cost'] != 0){
                                                                                                    $oil_cost = $row['oil_cost'];
                                                                                                } else{
                                                                                                    $oil_cost = 0;
                                                                                                }
                                                                                                
                                                                                                if(isset($row['housing_cost']) && $row['housing_cost'] != 0){
                                                                                                    $housing_cost = $row['housing_cost'];
                                                                                                } else{
                                                                                                    $housing_cost = 0;
                                                                                                }
                                                                                                
                                                                                                if(isset($row['living_cost']) && $row['living_cost'] != 0){
                                                                                                    $living_cost = $row['living_cost'];
                                                                                                } else{
                                                                                                    $living_cost = 0;
                                                                                                }
                                                                                                
                                                                                                $total = $basic_salary + $travel_tickets + $oil_cost + $housing_cost + $living_cost;
                                                                                                echo $total;
                                                                                            ?>
                                                                                        </font> )
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                    </form>
                                                                    
                                                                    <form action="savevacation.php">
                                                                        <div style="display:none; background-color:#B1FAA5" id="item101020">
                                                                            <table width="100%" border="0" cellspacing="2" cellpadding="2" dir="rtl">
                                                                                <tr>
                                                                                    <td width="27%" align="left">نوع الإجازة :</td>
                                                                                    <td width="73%" align="right">
                                                                                        <select name="type" dir="rtl" style="width:50%;">
                                                                                            <option value="سنوية" >سنوية</option>
                                                                                            <option value="مرضية" >مرضية</option>
                                                                                            <option value="إدارية" >إدارية</option>
                                                                                            <option value="اضطرارية" >اضطرارية</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left">من تاريخ  :</td>
                                                                                    <td align="right">
                                                                                        <input type="date" name="starting_date" dir="rtl" size="10" value="<?php $today = date('Y-m-d'); echo date('Y-m-d', strtotime($today));?>" style="text-align:center; font-weight:bold; color:#F00" > 
                                                                                         الى تاريخ : 
                                                                                        <input type="date" name="ending_date" dir="rtl" size="10"  style="text-align:center; font-weight:bold; color:#F00" > 
                                                                                    </td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td align="left"></td>
                                                                                    <td align="right">
                                                                                        <input type="hidden" name="emp_id" value="<?php echo $_GET['id'];?>">
                                                                                        <br>
                                                                                        <input type="submit" value="حفظ بيانات الاجازة" class="button">
                                                                                        <br><br>
                                                                                    </td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td colspan="2" align="left">
                                                                                        <table width="100%" border="1" bordercolor="#999999" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                                                                                            <tr align="center" valign="top" bgcolor="#eaeaea" style="font-weight:bold">
                                                                                                <td width="47%">نوع الإجازة</td>
                                                                                                <td width="25%">من</td>
                                                                                                <td width="28%">الى</td>
                                                                                            </tr>
                                                                                            <?php
                                                                                                $query_vacation = "SELECT * FROM vacations WHERE emp_id='$id' AND ask!='1' AND ask!='3'";
                                                                                                $result_vacation = mysqli_query($conn, $query_vacation);
                                                                                                if($result_vacation->num_rows > 0){
                                                                                                    while($row_vacation = mysqli_fetch_array($result_vacation)){
                                                                                            ?>
                                                                                            <tr align="center" valign="top" style="font-weight:normal">
                                                                                                <td><?php echo $row_vacation['type'];?></td>
                                                                                                <td><?php echo $row_vacation['starting_date'];?></td>
                                                                                                <td><?php echo $row_vacation['ending_date'];?></td>
                                                                                            </tr>
                                                                                            <?php
                                                                                                    }
                                                                                                }
                                                                                            ?>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                    </form>
                                                                    
                                                                    <form action="savewarning.php" method="post">
                                                                        <input type="hidden" name="emp_id" value="<?php echo $_GET['id'];?>">
                                                                        <div style="display:none; background-color: #B1B6EB" id="item101030">
                                                                            <table width="100%" border="0" cellspacing="2" cellpadding="2" dir="rtl">
                                                                                <tr valign="top">
                                                                                    <td width="27%" align="left">سبب الانذار لمحاكمة :</td>
                                                                                    <td width="73%" align="right">
                                                                                        <textarea name="warning_reason" style="width:95%" rows="5" dir="rtl"></textarea>
                                                                                    </td>
                                                                                </tr>
                                                                        
                                                                                <tr>
                                                                            <td align="left">في تاريخ  :</td>
                                                                            <td align="right">
                                                                                <input type="date" name="warning_date" dir="rtl" size="10" value="<?php $today = date('Y-m-d'); echo date('Y-m-d', strtotime($today));?>" style="text-align:center; font-weight:bold; color:#F00" > 
                                                                                <?php if(isset($_GET['wd']) && $_GET['wd'] == 0){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى ادخال التاريخ</span><?php }?>
                                                                            </td>
                                                                        </tr>
                                                                        
                                                                        <tr>
                                                                            <td align="left"></td>
                                                                            <td align="right">
                                                                                <input type="hidden" name="ForUserID" value="1">
                                                                                <input type="submit" value="حفظ بيانات الانذارات" onClick="CheckWarning()" class="button" >
                                                                            </td>
                                                                        </tr>
                                                                            
                                                                        <tr>
                                                                            <td colspan="2" align="left">
                                                                                <table width="100%" border="1" bordercolor="#999999" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                                                                                    <tr align="center" valign="top" bgcolor="#eaeaea" style="font-weight:bold">
                                                                                        <td width="47%">سبب الانذار/المحاكمة</td>
                                                                                        <td width="25%">التاريخ</td>
                                                                                    </tr>
                                                                            
                                                                                    <?php
                                                                                        $query_warnings = "SELECT * FROM warnings WHERE emp_id=$id";
                                                                                        $result_warnings = mysqli_query($conn, $query_warnings);
                                                                                        if($result_warnings->num_rows > 0){
                                                                                            while($row_warnings=mysqli_fetch_array($result_warnings)){
                                                                                    ?>
                                                                                    <tr align="center" valign="top" style="font-weight:normal">
                                                                                        <td><?php echo $row_warnings['warning_reason'];?></td>
                                                                                        <td><?php echo $row_warnings['warning_date'];?></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                            }
                                                                                        }
                                                                                    ?>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </form>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </th>
                                            </tr>
                                            
                                            <tr >
                                                <th colspan="2">
                                                    <input type="button" value=" افراغ الحقول" onClick="location.href='employeeEdit.php?id=<?php echo $id;?>';" class="button"> 
                                                    <input type="submit" value=" حفظ البيانات " class="button" name="save_user" >
                                                </th>
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
                                    <form action="editemp.php" method="post">
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