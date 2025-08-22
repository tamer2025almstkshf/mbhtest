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
                                        <th style="padding-bottom:20px;" dir="rtl"  align="right" class="table2">
                                            <a href="index.php" class="Main">
                                                <img src="images/homepage.png"   align="absmiddle" border="0"/> الصفحة الرئيسية
                                            </a> &raquo; شؤون الموظفين والموارد 
                                        </th>
                                    </tr>
                                    
                                    <tr bgcolor="#FFFFFF">
                                        <th align="right"  colspan="2" ><hr /></th>
                                    </tr>
                                </table>
                                
                                <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                    <?php
                                        if($row_permcheck['emp_perms_add'] === '1' || $row_permcheck['emp_perms_edit'] === '1' || $row_permcheck['emp_perms_delete'] === '1'){
                                    ?>
                                    <tr>
                                        <td align="center" width="33%" class="table2">
                                            <a href="mbhEmps.php" class="Main">الموظفين- الصلاحيات</a>
                                        </td>
                                        <td align="center" width="33%" class="table2">
                                            <a href="Office_Vehicles.php" class="Main">الموارد والمركبات</a>
                                        </td>
                                        <td align="center" width="33%" class="table2">
                                            <a href="Contracts.php" class="Main">عقود الإيجار والرخص التجارية</a>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                    <tr>
                                        <?php
                                            if($row_permcheck['emp_perms_add'] === '1' || $row_permcheck['emp_perms_edit'] === '1' || $row_permcheck['emp_perms_delete'] === '1'){
                                        ?>
                                        <td align="center" width="33%" class="table2">
                                            <a href="Lawyers.php" class="Main">مكاتب المحامين</a>
                                        </td>
                                        <?php
                                            } if($row_permcheck['logs_rperm'] === '1'){
                                        ?>
                                        <td align="center" width="33%" class="table2">
                                            <a href="Logs.php" class="Main">سجلات البرنامج</a>
                                        </td>
                                        <?php } if($rowuserid['vacf_aperm'] === '1'){?>
                                        <td align="center" width="33%" class="table2">
                                            <?php
                                                $queryno = "SELECT COUNT(*) as countvacs FROM vacations WHERE ask='1'";
                                                $resultno = mysqli_query($conn, $queryno);
                                                $rowno = mysqli_fetch_array($resultno);
                                                $reqsno = $rowno['countvacs'];
                                            ?>
                                            <a href="vacationReqs.php" class="Main">طلبات الاجازات ( <?php echo $reqsno;?> )</a>
                                        </td>
                                        <?php }?>
                                    </tr>
                                    <tr>
                                        <?php if($rowuserid['vacl_aperm'] === '1'){?>
                                        <td align="center" width="33%" class="table2">
                                            <?php
                                                $queryno = "SELECT COUNT(*) as countvacs FROM vacations WHERE ask='3' AND ask2='1'";
                                                $resultno = mysqli_query($conn, $queryno);
                                                $rowno = mysqli_fetch_array($resultno);
                                                $reqsno = $rowno['countvacs'];
                                            ?>
                                            <a href="vacationReqs2.php" class="Main">القبول النهائي للاجازات ( <?php echo $reqsno;?> )</a>
                                        </td>
                                        <?php }?>
                                        <td align="center" width="33%" class="table2">
                                            <a href="empinfo.php" class="Main">بيانات الموظفين</a>
                                        </td>
                                    </tr>
                                </table>
                                
                                <?php
                                    if($row_permcheck['emp_perms_add'] === '1' || $row_permcheck['emp_perms_edit'] === '1' || $row_permcheck['emp_perms_delete'] === '1'){
                                ?>
                                <div class="block1">
                                    <div class="h1">
                                        <img alt="" src="images/a.png" width="47" height="45" />تنبيهات  الموظفين
                                    </div>
                                    
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                        <?php 
                                            $query_usersc = "SELECT COUNT(*) as count_emps FROM user WHERE username != '' AND
                                            tel1 != '' AND password != ''";
                                            $result_usersc = mysqli_query($conn, $query_usersc);
                                            $row_usersc = mysqli_fetch_array($result_usersc);
                                        ?>
                                        
                                        <tr>
                                            <td>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table"> 
                                                    <tr align="center" style="font-weight:bold" class="header_table">
                                                        <td width="20%">اسم الموظف</td>
                                                        <td width="20%">تاريخ الميلاد</td>
                                                        <td width="20%">تاريخ انتهاء جواز السفر</td>
                                                        <td width="20%">تاريخ انتهاء الاقامة</td>
                                                        <td width="20%">تاريخ انتهاء عقد العمل</td>
                                                    </tr>
                                                    
                                                    <?php
                                                        $query_emps = "SELECT * FROM user";
                                                        $result_emps = mysqli_query($conn, $query_emps);
                                                        if($result_emps->num_rows > 0){
                                                            while($row_emps = mysqli_fetch_array($result_emps)){
                                                    ?>
                                                    <tr style="cursor:pointer" bgcolor='#ecdec4' class="color-change" >
                                                        <td align="center" onclick="location.href='employeeEdit.php?id=<?php echo $row_emps['id'];?>';"><?php if(isset($row_emps['name']) && $row_emps['name'] !== ''){echo $row_emps['name'];}?></td>
                                                        <td align="center" onclick="location.href='employeeEdit.php?id=<?php echo $row_emps['id'];?>';"><?php if(isset($row_emps['dob']) && $row_emps['dob'] !== ''){echo $row_emps['dob'];}?></td>
                                                        <td align="center" onclick="location.href='employeeEdit.php?id=<?php echo $row_emps['id'];?>';" style=" color:red"><?php if(isset($row_emps['passport_exp']) && $row_emps['passport_exp'] !== ''){echo $row_emps['passport_exp'];}?></td>
                                                        <td align="center" onclick="location.href='employeeEdit.php?id=<?php echo $row_emps['id'];?>';" style=" color:red"><?php if(isset($row_emps['residence_exp']) && $row_emps['residence_exp'] !== ''){echo $row_emps['residence_exp'];}?></td>
                                                        <td align="center" onclick="location.href='employeeEdit.php?id=<?php echo $row_emps['id'];?>';" style=" color:red"><?php if(isset($row_emps['contract_exp']) && $row_emps['contract_exp'] !== ''){echo $row_emps['contract_exp'];}?></td>
                                                    </tr>
                                                    <?php
                                                            }
                                                        }
                                                    ?>
                                                </table>
                                            </td>
                                        </tr>
                                        <?php 
                                            $query_vehsc = "SELECT COUNT(*) as count_vehs FROM vehicles WHERE emp_id != '' AND
                                            type != ''";
                                            $result_vehsc = mysqli_query($conn, $query_vehsc);
                                            $row_vehsc = mysqli_fetch_array($result_vehsc);
                                        ?>
                                        <tr>
                                            <th style="font-size:18px; color:#000">الموارد والمركبات 
                                                (<font color="#0000FF" style="font-size:18px"><?php echo $row_vehsc['count_vehs'];?></font>)
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table"> 
                                                    <tr align="center" style="font-weight:bold " class="header_table">
                                                        <td>رقم المركبة</td>
                                                        <td>ت.انتهاء الرخصة</td>
                                                        <td>ت.انتهاء التأمين</td>
                                                    </tr>
                                                    
                                                    <?php
                                                        $query_vehs = "SELECT * FROM vehicles";
                                                        $result_vehs = mysqli_query($conn, $query_vehs);
                                                        if($result_vehs->num_rows > 0){
                                                            while($row_vehs = mysqli_fetch_array($result_vehs)){
                                                    ?>
                                                    <tr bgcolor='#ecdec4' class="color-change" style="cursor:pointer;" onclick="location.href='vehicleEdit.php?id=<?php echo $row_vehs['id'];?>'">
                                                        <td align="center"><?php if(isset($row_vehs['num']) && $row_vehs['num'] !== ''){echo $row_vehs['num'];}?> </td>
                                                        <td align="center" style=" color:red"><?php if(isset($row_vehs['lic_expir']) && $row_vehs['lic_expir'] !== ''){echo $row_vehs['lic_expir'];}?></td>
                                                        <td align="center" style="color:red"><?php if(isset($row_vehs['insur_expir']) && $row_vehs['insur_expir'] !== ''){echo $row_vehs['insur_expir'];}?></td>
                                                    </tr>
                                                    <?php
                                                            }
                                                        }
                                                    ?>
                                                </table>
                                            </td>
                                        </tr>
                                        <?php
                                            $query_contsc = "SELECT COUNT(*) as count_conts FROM contracts WHERE owner != ''";
                                            $result_contsc = mysqli_query($conn, $query_contsc);
                                            $row_contsc = mysqli_fetch_array($result_contsc);
                                        ?>
                                        
                                        <tr>
                                            <th style="font-size:18px; color:#000">عقود الإيجار والرخص التجارية 
                                                (<font color="#0000FF" style="font-size:18px"><?php echo $row_contsc['count_conts'];?></font>)
                                            </th>
                                        </tr>
                                        
                                        <tr>
                                            <td>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table"> 
                                                    <tr align="center" style="font-weight:bold" class="header_table">
                                                        <td>النوع</td>
                                                        <td>المالك</td>
                                                        <td>ت.انتهاء</td>
                                                    </tr>
                                                    
                                                    <?php
                                                        $query_conts = "SELECT * FROM contracts";
                                                        $result_conts = mysqli_query($conn, $query_conts);
                                                        
                                                        if($result_conts->num_rows > 0){
                                                            while($row_conts=mysqli_fetch_array($result_conts)){
                                                    ?>
                                                    <tr bgcolor='#ecdec4' class="color-change" style="cursor:pointer;" onclick="location.href='contractEdit.php?id=<?php echo $row_conts['id'];?>';">
                                                        <td align="center"><?php if(isset($row_conts['rent_lic']) && $row_conts['rent_lic'] !== ''){echo $row_conts['rent_lic'];}?></td>
                                                        <td align="center" style=" color:red"><?php if(isset($row_conts['owner']) && $row_conts['owner'] !== ''){echo $row_conts['owner'];}?></td>
                                                        <td align="center" style="color:red"><?php if(isset($row_conts['ending_d']) && $row_conts['ending_d'] !== ''){echo $row_conts['ending_d'];}?></td>
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
                                <?php }?>
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