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
        <div class="container">
            <?php 
                include_once 'userInfo.php';
                include_once 'sidebar.php';
                
                $myid = $_SESSION['id'];
                $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
                $result_permcheck = mysqli_query($conn, $query_permcheck);
                $row_permcheck = mysqli_fetch_array($result_permcheck);
                
                if($row_permcheck['goaml_rperm'] === '1' || $row_permcheck['terror_rperm'] === '1'){
            ?>
            <div class="l_data">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center" dir="rtl">
                            <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <?php include_once 'search_main.php';?>
                            </table>
                        </td>
                    </tr>
                    
                    <tr>
                        <td align="center" dir="rtl">
                            <div id="PrintMainDiv">
                                <table width="100%"  border="0" cellspacing="2" cellpadding="2" align="center" >
                                    <tr >
                                        <th style="padding-bottom:20px;" dir="rtl"  align="right" class="table2 red">
                                            <a href="index.php" class="Main">
                                                <img src="images/homepage.png"   align="absmiddle" border="0"/> الصفحة الرئيسية
                                            </a> &raquo; قائمة الارهاب
                                        </th>
                                    </tr>
                                    <tr bgcolor="#FFFFFF">
                                        <th align="right"  colspan="2" ><hr /></th>
                                    </tr>
                                </table>
                                
                                <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <?php
                                            if($row_permcheck['goaml_rperm'] === '1'){
                                        ?>
                                        <td align="center" width="33%" class="table2">
                                            <a href="goAMList.php" class="Main">كشف قائمة الارهاب المحلية goAML</a>
                                        </td>
                                        <?php
                                            }
                                            if($row_permcheck['terror_rperm'] === '1'){
                                        ?>
                                        <td align="center" width="33%" class="table2">
                                            <a href="Terrors.php" class="Main">المدرجين تحت قائمة الارهاب</a>
                                        </td>
                                        <?php }?>
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