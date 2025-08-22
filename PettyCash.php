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
                                <form name="BusiForm" method="post" enctype="multipart/form-data">
                                    <table width="99%" border="0" cellspacing="1" cellpadding="1" dir="rtl">
                                        <tr valign="top" bgcolor="#FFFFFF">
                                            <th align="right" colspan="2" dir="rtl" class="table2 " >
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr valign="top">
                                                        <td class="red" width="60%">
                                                            <a href="index.php?LoadMenue=BS" class="Main">
                                                                <img src="images/homepage.png"   align="absmiddle" border="0"/> الصفحة الرئيسية
                                                            </a> &raquo; 
                                                            <a href="index.php?pg=Accounts_PG&LoadMenue=Acc" class="Main">الحسابات</a> &raquo; نثريات الموظفين 
                                                        </td>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                                                <tr style=" font-size:16px; color:#000; cursor:pointer" align="center" onclick="location.href='index.php?pg=Accounts_PettyCash&LoadMenue=Acc'">
                                                                    <td>&nbsp;</td>
                                                                    <td width="5%" class="red"  style="font-size:20px">0</td>
                                                                    <td width="90%" align="right"> الرصيد المتوفر</td>
                                                                </tr>
                                                                
                                                                <tr >
                                                                    <td colspan="3"><hr /></td>
                                                                </tr>
                                                                
                                                                <tr style=" font-size:16px; cursor:pointer" align="center" onclick="location.href='index.php?pg=Accounts_PettyCash&busi_status=1&LoadMenue=Acc'">
                                                                    <td  bgcolor="#FFFF00" >&nbsp;</td>
                                                                    <td class="red">0</td>
                                                                    <td align="right" >رصيد فى الانتظار</td>
                                                                </tr>
                                                                
                                                                <tr style=" font-size:16px; cursor:pointer" align="center" onclick="location.href='index.php?pg=Accounts_PettyCash&busi_status=0&LoadMenue=Acc'">
                                                                    <td  bgcolor="#FF0000" >&nbsp;</td>
                                                                    <td class="red">0</td>
                                                                    <td align="right" >الرصيد المرفوض</td>
                                                                </tr>
                                                                
                                                                <tr style="font-size:16px; cursor:pointer" align="center" onclick="location.href='index.php?pg=Accounts_PettyCash&busi_status=2&LoadMenue=Acc'">
                                                                    <td  bgcolor="#006600">&nbsp;</td>
                                                                    <td class="red"></td>
                                                                    <td align="right" >الرصيد المصروف</td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </th>
                                        </tr>
                                        
                                        <tr>
                                            <th> 
                                                <table width="100%" border="1" cellspacing="0" cellpadding="0"  bordercolor="#CCCCCC"></table>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                <table width="100%"  border="0" cellspacing="2" cellpadding="2" class="table" align="center"  dir="ltr" >
                                                    <tr >
                                                        <th width="76%" align="right" dir="rtl" >
                                                            <input type="text" name="Fno" dir="ltr" value="" style="width:20%;text-align:center; font-weight:bold; color:#00F" onChange="submit()">   رقم القضية :
                                                            <input type="text"  name="case_no" value="" dir="rtl" style="text-align:center; width:8%; font-weight:bold; color:#00F"/> / 
                                                            <input type="text"  name="case_no_year" value="" dir="rtl" style="text-align:center; width:10%; font-weight:bold; color:#00F"/>
                                                            <input type="image"  src="images/1392507723_search.png" align="absmiddle" onClick="submit()"  style="border:none">
                                                        </th>
                                                        
                                                        <th width="24%" align="left"  dir="rtl" >رقم الملف :</th>
                                                    </tr>
                                                    
                                                    <tr >
                                                        <th width="76%" align="right" dir="rtl" >
                                                            <input type="text" name="amount" dir="rtl" value="" style="width:20%; text-align:center;"> تاريخ الصرف :
                                                            <input type="text" name="amount_date" dir="rtl" size="10" value="30/01/2025" style="text-align:center; font-weight:bold; color:#F00" > 
                                                            <label style="cursor:pointer" onClick="cal13.select(document.addform.amount_date,'amount_date','dd/MM/yyyy'); return false;"  NAME="amount_date" ID="amount_date">
                                                                <img src="images/calendar.png" align="absmiddle">
                                                            </label>
                                                        </th>
                                                        
                                                        <th width="24%" align="left"  dir="rtl"> مبلغ الصرف  :</th>
                                                    </tr>
                                                    
                                                    <tr >
                                                        <th align="right"  dir="rtl"><textarea dir="rtl" wrap="physical" rows="2" style="width:60%" name="amount_notes"></textarea></th>
                                                        <th align="left"  dir="rtl">ملاحظات :</th>
                                                    </tr>
                                                    
                                                    <tr valign="top" >
                                                        <th align="right"  dir="rtl" valign="top">
                                                            <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                                                <tr>
                                                                    <td width="33%">
                                                                        <input type="file" name="attach_file4" dir="ltr" style="font-size:11px"> 
                                                                    </td>
                                                                    <td width="33%">
                                                                        <input type="file" name="attach_file1" dir="ltr" style="font-size:11px"> 
                                                                    </td>
                                                                    <td width="33%">
                                                                        <input type="file" name="attach_file2" dir="ltr" style="font-size:11px"> 
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </th>
                                                        <th align="left"  dir="rtl">مرفقات :</th>
                                                    </tr>
                                                    
                                                    <tr >
                                                        <th align="right" class="red">
                                                            <input type="button" value=" افراغ الحقول" onClick="location.href='index.php?pg=Accounts_PettyCash';" class="button"> 
                                                            ليس لديك صلاحيات اضافة                
                                                        </th>
                                                        <th>&nbsp;</th>
                                                    </tr>
                                                </table>
                                            </th>
                                        </tr>
                                        
                                        <tr>
                                            <th>
                                                <table  style="font-size:14px" width="100%" border="1" cellspacing="0" cellpadding="0" align="center"  bordercolor="#cccccc" >
                                                    <tr   class="header_table" height="40">
                                                        <th width="5%" align="center">م</th>
                                                        <th width="30%" align="center">بيانات الموكل</th>
                                                        <th width="11%" align="center">مبلغ الصرف</th>
                                                        <th width="9%" align="center">تاريخ الصرف</th>
                                                        <th width="27%" align="center">ملاحظات</th>
                                                        <th width="13%" align="center">م.ت/ الإدخال</th>
                                                    </tr>
                                                </table>
                                            </th>
                                        </tr>
                                    </table>
                                </form><br>
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