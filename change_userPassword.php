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
        <div class="colors">
            <span class="change">تغيير اللون</span>

            <a href="javascript:chooseStyle('bage-theme', 60)"><span  class="bage_color"></span></a>
            <a href="javascript:chooseStyle('selver-theme', 60)"><span class="selver_color"></span></a>
            <a href="javascript:chooseStyle('blue-theme', 60)"><span class="blue_color"></span></a>
        </div>

        <div class="container">
            <?php 
                include_once 'userInfo.php';
                include_once 'sidebar.php';
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
                                <table width="99%" border="0" cellspacing="1" cellpadding="1" dir="rtl">
                                    <tr valign="top" bgcolor="#FFFFFF">
                                        <th align="right" colspan="2" dir="rtl" class="table2">
                                            <a href="index.php" class="Main">
                                                <img src="images/homepage.png" align="absmiddle" border="0"/> الصفحة الرئيسية
                                            </a> &raquo; تغيير كلمة المرور
                                        </th>
                                    </tr>
                    
                                    <tr>
                                        <th  bgcolor="#FFFFFF" style=" padding:5px;">
                                            <table border="0" width="80%" cellspacing="3" cellpadding="3" align="center" dir="ltr" >
                                                <?php
                                                    $id = $_SESSION['id'];
                                                    $query = "SELECT * FROM user WHERE id = '$id'";
                                                    $result = mysqli_query($conn, $query);
                                                    $row = mysqli_fetch_array($result);
                                                ?>
                                                <form method="post" action="cmypass.php" onSubmit="return check_change_pass()">
                                                    <tr>
                                                        <th width="80%" align="right" style="color:#000000">
                                                            <?php if(isset($row['name']) && $row['name'] !== ''){ echo $row['name']; }?><br />
                                                            <?php if(isset($row['username']) && $row['username'] !== ''){ echo $row['username']; }?><br />
                                                        </th>
                                                        <th width="20%" align="left">: اسم الدخول</th>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th align="right">
                                                            <input type="password" name="current_password"  style="width:30%; text-align:center" >
                                                        </th>
                                                        <th align="left">: كلمة المرور الحالية </th>
                                                    </tr>

                                                    <th align="right">
                                                        <?php if(isset($_GET['error']) && $_GET['error'] === 'notmatched'){?><span style="color:#ba0000; font-size:12px">كلمة المرور الحالية التي ادخلتها غير صحيحة</span><?php }?>
                                                    </th>

                                                    <tr>
                                                        <th align="right">
                                                            <input type="password" name="new_password" style="width:30%; text-align:center">
                                                        </th>
                                                        <th align="left">: كلمة المرور </th>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th align="right">
                                                            <input type="password" name="new_password2" style="width:30%; text-align:center">
                                                        </th>
                                                        <th align="left" > : تأكيد كلمة المرور</th>
                                                    </tr>

                                                    <th align="right">
                                                        <?php if(isset($_GET['error']) && $_GET['error'] === 'unmatched'){?><span style="color:#c6c600; font-size:12px">كلمة المرور غير متطابقة</span><?php }?>
                                                    </th>

                                                    <tr>
                                                        <th align="right" >
                                                            <input type="submit" value="اضغط هنا لتغيير كلمة المرور" class="button">
                                                        </th>
                                                        <th >&nbsp;</th>
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
        </div>
        <div class="footer">محمد بني هاشم للمحاماة والاستشارات القانونية<img alt="" src="images/f.png" width="29" height="31" /><img alt="" src="images/w.png" width="29" height="31" /></div>
        </div>
    </body>
</html>