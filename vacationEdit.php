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
                                    <form action="reqedit.php" method="post" enctype="multipart/form-data" >
                                        <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
                                        <table width="99%" border="0" cellspacing="2" cellpadding="2" dir="rtl">
                                            <tr valign="top" >
                                                <th align="right" colspan="2" dir="rtl" class="table2 red">
                                                    <a href="index.php" class="Main">
                                                        <img src="images/homepage.png"   align="absmiddle" border="0"/> الصفحة الرئيسية
                                                    </a> &raquo; 
                                                    طلب اجازة
                                                </th>
                                            </tr>

                                            <tr bgcolor="#FFFFFF">
                                                <th align="right"  colspan="2" ><hr /></th>
                                            </tr>
     
                                            <tr>
                                                <th align="center" colspan="2" dir="ltr" >
                                                    <div>
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0"  dir="rtl">
                                                            <tr valign="top" >
                                                                <td width="60%">
                                                                    <table width="100%"  border="0" cellspacing="1" cellpadding="3" class="table" align="center"  dir="ltr"  bgcolor="#FFFFFF" >
                                                                        <tr id="edit">
                                                                            <th colspan="2" class="header_table"> تعديل على الطلب </th>
                                                                        </tr>
                                                                        <?php
                                                                           $idd = $_GET['id'];  
                                                                            $queryr = "SELECT * FROM vacations WHERE id='$idd'";
                                                                            $resultr = mysqli_query($conn, $queryr);
                                                                            $rowr = mysqli_fetch_array($resultr);
                                                                        ?>
                                                                        <tr>
                                                                            <th width="76%" align="right" >
                                                                                <select name="vtype">
                                            <option value="سنوية" <?php if($rowr['type'] === 'سنوية'){ echo 'selected';}?>>سنوية</option>
                                            <option value="مرضية" <?php if($rowr['type'] === 'مرضية'){ echo 'selected';}?>>مرضية</option>
                                            <option value="ادارية" <?php if($rowr['type'] === 'ادارية'){ echo 'selected';}?>>ادارية</option>
                                            <option value="اضطرارية" <?php if($rowr['type'] === 'اضطرارية'){ echo 'selected';}?>>اضطرارية</option>
                                        </select><br>
                                                                                <?php if(isset($_GET['vtype']) && $_GET['vtype'] == 0){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى اختيار نوع الاجازة</span><?php }?>
                                                                            </th>
                                                                            <th width="24%" align="left"  dir="rtl"> نوع الاجازة :<?php if(isset($_GET['vtype']) && $_GET['vtype'] == 0){?><br><br><?php }?></th>
                                                                        </tr>
                                                                                
                                                                        <tr >
                                                                            <th width="76%" align="right" >
                                                                                <input type="date" name="starting_date" value="<?php if(isset($rowr['starting_date']) && $rowr['starting_date']){echo $rowr['starting_date'];}?>" style="width:30%; text-align:center">
                                                                            </th>
                                                                            <th width="24%" align="left"  dir="rtl">من تاريخ :</th>
                                                                        </tr>
    
                                                                        <tr >
                                                                            <th width="76%" align="right" >
                                                                                <input type="date" name="ending_date" value="<?php if(isset($rowr['ending_date']) && $rowr['ending_date']){echo $rowr['ending_date'];}?>" style="width:30%; text-align:center">
                                                                            </th>
                                                                            <th width="24%" align="left"  dir="rtl" > الى تاريخ :</th>
                                                                        </tr>
                                    
                                                                        <tr >
                                                                            <th align="right" >
                                                                                
                                                                                <textarea name="notes" dir="rtl" style="width:30%;"><?php if(isset($rowr['notes']) && $rowr['notes']){echo $rowr['notes'];}?></textarea>
                                                                            </th>
                                                                            <th align="left"  dir="rtl">ملاحظات :</th>
                                                                        </tr>
                                    
                                                                        <tr >
                                                                            <th colspan="2">
                                                                                <input type="button" value=" افراغ الحقول" onClick="location.href='vacationEdit.php?id=<?php echo $_GET['id'];?>';" class="button"> 
                                                                                <input type="submit" value=" تعديل البيانات" class="button"> 
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
                                                    <form action="reqdel.php" method="post">
                                                        <tr  class="header_table">
                                                            <th width="1%" dir="ltr">
                                                            </th>
                                                            <th width="1%" dir="ltr">تعديل</th>
                                                            <th width="14%" dir="ltr">ملاحظات</th>
                                                            <th width="17%" dir="ltr">الى تاريخ</th>
                                                            <th width="17%" dir="ltr">من تاريخ</th>
                                                            <th width="14%" dir="ltr">نوع الاجازة</th>
                                                        </tr>

                                                        <?php
                                                            $use = $_SESSION['id'];
                                                            $query_vacs = "SELECT * FROM vacations WHERE (ask='1' OR ask='2' OR ask='3') AND emp_id='$use'";
                                                            $result_vacs = mysqli_query($conn, $query_vacs);
                                                            if($result_vacs->num_rows > 0){
                                                                while($row_vacs= mysqli_fetch_array($result_vacs)){
                                                        ?>
                                                        <tr valign="top" bgcolor="<?php if(isset($row_vacs['ask']) && $row_vacs['ask'] === '1'){ echo '#ffffff'; } else if(isset($row_vacs['ask']) && $row_vacs['ask'] === '2'){ echo '#00ff0080'; } else if(isset($row_vacs['ask']) && $row_vacs['ask'] === '3'){ echo '#ffc6c7'; }?>">
                                                            <th>
                                                                <?php if(isset($row_vacs['ask']) && $row_vacs['ask'] === '1'){?>
                                                                <input type="checkbox" name="CheckedD[]" style="border:0;background : transparent;" value= <?php echo $row_vacs['id'];?>>
                                                                <?php }?>
                                                            </th>
                                                            <th class="reg"  >
                                                                <?php if(isset($row_vacs['ask']) && $row_vacs['ask'] === '1'){?>
                                                                <img src="images/Edit.png"  border="0" onClick="location.href='vacationEdit.php?id=<?php echo $row_vacs['id'];?>';"  title="اضغط هنا للتعديل" style="cursor:pointer" />
                                                                <?php }?>
                                                            </th>
                                                            <th ><?php if(isset($row_vacs['notes']) && $row_vacs['notes'] !== ''){echo $row_vacs['notes'];}?></th>
                                                            <th ><?php if(isset($row_vacs['ending_date']) && $row_vacs['ending_date'] !== ''){echo $row_vacs['ending_date'];}?></th>
                                                            <th ><?php if(isset($row_vacs['starting_date']) && $row_vacs['starting_date'] !== ''){echo $row_vacs['starting_date'];}?> </th>
                                                            <th ><?php if(isset($row_vacs['type']) && $row_vacs['type'] !== ''){echo $row_vacs['type'];}?></th>
                                                        </tr>
                                                        <?php
                                                                }
                                                            }
                                                        ?> 
                                                        <td>
                                                            <input type="submit" value="حذف" class="button">
                                                        </td>
                                                        <?php if(isset($_GET['error']) && $_GET['error'] === 'null'){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى اختيار الاجازات المراد حذفها</span><?php }?>
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

<script>
    function collapse() {
        var section = document.getElementById("item1000");
        if (section.style.display === "block") {
            section.style.display = "none";
        } else {
            section.style.display = "block";
        }
    }
</script>