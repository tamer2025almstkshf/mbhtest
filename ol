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
        <style>
            .color-change:nth-of-type(even){
                background-color: white
            }
        </style>
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
            ?>
            
            <div class="l_data">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center" dir="rtl">
                            <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <?php include_once 'search_main.php';?>
                            </table>
                            <br />
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
                                            </a> &raquo; 
                                            <a href="employees.php" class="Main">شؤون الموظفين و الموارد</a> 
                                            &raquo; بيانات الموظفين
                                        </th>
                                    </tr>
                                    
                                    <tr bgcolor="#FFFFFF">
                                        <th align="right"  colspan="2" ><hr /></th>
                                    </tr>
                                </table>
                                <br>
                                <div class="block1">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                        <?php 
                                            $query_usersc = "SELECT COUNT(*) as count_emps FROM user WHERE username != '' AND
                                            tel1 != '' AND password != ''";
                                            $result_usersc = mysqli_query($conn, $query_usersc);
                                            $row_usersc = mysqli_fetch_array($result_usersc);
                                        ?>
                                        <tr>
                                            <th style="font-size:18px; color:#000">سجل الموظفين 
                                                (<font color="#0000FF" style="font-size:18px"><?php echo $row_usersc['count_emps'];?></font>)
                                            </th>
                                        </tr>
                                        
                                        <tr>
                                            <td>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table"> 
                                                    <tr align="center" style="font-weight:bold" class="header_table">
                                                        <td width="15%">الرقم الوظيفي</td>
                                                        <td width="15%">اسم الموظف</td>
                                                        <td width="2%"></td>
                                                    </tr>
                                                    
                                                    <?php
                                                        if($row_permcheck['emp_rperm'] === '1'){
                                                            $query_emps = "SELECT * FROM user";
                                                        } else{
                                                            $query_emps = "SELECT * FROM user WHERE id='$myid'";
                                                        }
                                                            $result_emps = mysqli_query($conn, $query_emps);
                                                            if($result_emps->num_rows > 0){
                                                                while($row_emps = mysqli_fetch_array($result_emps)){
                                                    ?>
                                                    <tr style="cursor:pointer" bgcolor='#ecdec4' class="color-change" >
                                                        <td align="center"><?php if(isset($row_emps['id']) && $row_emps['id'] !== ''){echo $row_emps['id'];}?></td>
                                                        <td align="center" style=" color:red"><?php if(isset($row_emps['name']) && $row_emps['name'] !== ''){echo $row_emps['name'];}?></td>
                                                        <td align="center" style=" color:#333">
                                                            <img src="images/Action.png" border="0" onclick="javascript:funHS('<?php echo $row_emps['id'];?>')" style="cursor:pointer"/>
                                                        </td>
                                                    </tr>
                                                    
                                                    <tr style="cursor:pointer" bgcolor='#ecdec4' class="color-change" >
                                                        <td colspan="5"  dir="rtl" style="padding:3px;">
                                                            <table id="item<?php echo $row_emps['id'];?>"  style="display:none" align="center" border="0" cellspacing="0" cellpadding="0" class="table2" >
                                                                <tr>
                                                                    <th>ادارة البيانات : <br><br></th>
                                                                    <td>
                                                                        <button type="button" onclick="location.href='emp_data.php?id=<?php echo $row_emps['id'];?>';" class="button" style="width: 150px; border: 0; border-radius:4px; padding: 7px 40px;">ادارة البيانات</button><br><br>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>مستندات الموظف : <br><br></th>
                                                                    <td>
                                                                        <button type="button" onclick="location.href='emp_atts.php?id=<?php echo $row_emps['id'];?>';" class="button" style="width: 150px; border: 0; border-radius:4px; padding: 7px 40px;">المستندات</button><br><br>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>ادارة الوقت : <br><br></th>
                                                                    <td>
                                                                        <button type="button" onclick="location.href='TimeManagement.php?id=<?php echo $row_emps['id'];?>';" class="button" style="width: 150px; border: 0; border-radius:4px; padding: 7px 40px;">ادارة الوقت</button><br><br>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>تقييمات الموظف : <br><br></th>
                                                                    <td>
                                                                        <button type="button" onclick="location.href='emp_rates.php?id=<?php echo $row_emps['id'];?>';" class="button" style="width: 150px; border: 0; border-radius:4px; padding: 7px 40px;">التقييمات</button><br><br>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>الاجازات : <br><br></th>
                                                                    <td>
                                                                        <img src="images/HolBT.png" onclick="location.href='<?php if($row_emps['id'] === $myid){ echo 'vacationReq.php'; } else{ $id=$row_emps['id']; echo "emp_holidays.php?id=$id"; }?>';" align="absmiddle"  style="cursor:pointer" /><br><br>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>الانذارات : </th>
                                                                    <td>
                                                                        <img src="images/WarningBT.png" onclick="location.href='emp_warns.php?id=<?php echo $row_emps['id'];?>';" align="absmiddle"  style="cursor:pointer" />
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                            }
                                                        }
                                                    ?>
                                                    
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
	                            </div><br>    
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

<script>
    function funHS(vID2){
        if(document.getElementById("item" + vID2).style.display=="none"){
            document.getElementById("item" + vID2).style.display="block";
        }
        else{
            document.getElementById("item" + vID2).style.display="none";
        }
    }
</script>