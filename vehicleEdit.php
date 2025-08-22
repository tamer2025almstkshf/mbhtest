<?php
    include_once 'connection.php';
    include_once 'login_check.php';

    if(!isset($_GET['id']) || $_GET['id'] === ''){
        header("Location: office_vehicles.php");
        exit();
    }

    $id = $_GET['id'];
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
    <?php
    ?>

    <body>
        <div class="container">
            <?php 
                include_once 'userInfo.php';
                include_once 'sidebar.php';
                
                if(!isset($_GET['id'])){
                    header("Location: office_vehicles.php");
                    exit();
                }
                
                $id = $_GET['id'];
                if($_GET['id'] === ''){
                    header("Location: office_vehicles.php");
                    exit();
                }
                
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
                                    <form action="vehedit.php" method="post" enctype="multipart/form-data" >
                                        <?php $id = $_GET['id'];?>
                                        <input type="hidden" name="id" value="<?php echo $id?>">
                                        <table width="99%" border="0" cellspacing="2" cellpadding="2" dir="rtl">
                                            <tr valign="top" >
                                                <th align="right" colspan="2" dir="rtl" class="table2 red">
                                                    <a href="index.php" class="Main">
                                                        <img src="images/homepage.png"   align="absmiddle" border="0"/> الصفحة الرئيسية
                                                    </a> &raquo; 
                                                    <a href="employees.php" class="Main">شؤون الموظفين والموارد</a> 
                                                    &raquo; الموارد - المركبات
                                                </th>
                                            </tr>
                                            
                                            <tr bgcolor="#FFFFFF">
                                                <th align="right"  colspan="2" ><hr /></th>
                                            </tr>
                                            
                                            <tr>
                                                <th align="right" colspan="2" dir="ltr" >
                                                    <div style="display: block;">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0"  dir="rtl">
                                                            <tr valign="top" >
                                                                <td width="60%">
                                                                    <table width="100%"  border="0" cellspacing="1" cellpadding="3" class="table" align="center"  dir="ltr"  bgcolor="#FFFFFF" >
                                                                        <tr id="edit">
                                                                            <th colspan="2" class="header_table"> تعديل بيانات مركبة </th>
                                                                        </tr>
                                                                        
                                                                        <?php
                                                                            $query = "SELECT * FROM vehicles WHERE id='$id'";
                                                                            $result = mysqli_query($conn, $query);
                                                                            
                                                                            if($result->num_rows == 0){
                                                                                exit();
                                                                            }
                                                                            
                                                                            $row = mysqli_fetch_array($result);
                                                                        ?>
                                                                        
                                                                        <tr >
                                                                            <th width="76%" align="right" >
                                                                                <input type="text" name="type" dir="rtl" value="<?php if(isset($row['type']) && $row['type'] !== ''){echo $row['type'];}?>" style="width:30%; text-align:center; color:#00F; font-weight:bold;"><br>
                                                                                <?php if(isset($_GET['t']) && $_GET['t'] == 0){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى كتابة نوع السيارة</span><?php }?>
                                                                            </th>
                                                                            <th width="24%" align="left"  dir="rtl">نوع السيارة :<?php if(isset($_GET['t']) && $_GET['t'] == 0){?><br><br><?php }?></th>
                                                                        </tr>
                                                                        
                                                                        <tr >
                                                                            <th width="76%" align="right" >
                                                                                <input type="text" name="model" dir="rtl" value="<?php if(isset($row['model']) && $row['model'] !== ''){echo $row['model'];}?>"  style="width:30%;">
                                                                            </th>
                                                                            <th width="24%" align="left"  dir="rtl">موديل السيارة :</th>
                                                                        </tr>
                                                                        
                                                                        <tr >
                                                                            <th width="76%" align="right" >
                                                                                <input type="text" name="num"  value="<?php if(isset($row['num']) && $row['num'] !== ''){echo $row['num'];}?>" style="width:30%; text-align:center">
                                                                            </th>
                                                                            <th width="24%" align="left"  dir="rtl" >رقم السيارة :</th>
                                                                        </tr>
                                                                        
                                                                        <tr >
                                                                            <th align="right" >
                                                                                <?php
                                                                                    $selectId = $row['emp_id'];
                                                                                ?>
                                                                                <select name="emp_id" dir="rtl" style="width:30%;">
                                                                                    <?php
                                                                                        $query_empid = "SELECT * FROM user";
                                                                                        $result_empid = mysqli_query($conn, $query_empid);
                                                                                        if($result_empid->num_rows > 0){
                                                                                            while($row_empid = mysqli_fetch_array($result_empid)){
                                                                                    ?>
                                                                                    <option value="<?php echo $row_empid['id'];?>" <?php $empid = $row_empid['id']; echo ($selectId == "$empid") ? 'selected' : ''; ?>><?php echo $row_empid['name'];?></option>
                                                                                    <?php
                                                                                            }
                                                                                        }
                                                                                    ?>
                                                                                </select>
                                                                            </th>
                                                                            <th align="left"  dir="rtl">عهدة الموظف :</th>
                                                                        </tr>
                                                                        
                                                                        <tr >
                                                                            <th align="right"  dir="rtl">
                                                                                <input type="date" name="lic_expir" dir="rtl" size="10" value="<?php if(isset($row['lic_expir']) && $row['lic_expir'] !== ''){echo $row['lic_expir'];}?>" style="text-align:center; font-weight:bold; color:#F00" > 
                                                                                تاريخ انتهاء التأمين 
                                                                                <input type="date" name="insur_expir" dir="rtl" size="10" value="<?php if(isset($row['insur_expir']) && $row['insur_expir'] !== ''){echo $row['insur_expir'];}?>" style="text-align:center; font-weight:bold; color:#F00" >
                                                                            </th>
                                                                            <th align="left"  dir="rtl">انتهاء الملكية :</th>
                                                                        </tr>
                                                                        
                                                                        <tr >
                                                                            <th align="right" dir="rtl">
                                                                                <?php $selectedBranch = $row['branch'];?>
                                                                                <select name="branch" dir="rtl" style="width:30%;">
                                                                                    <option value="الشارقة" <?php echo ($selectedBranch == "الشارقة") ? 'selected' : ''; ?>>الشارقة</option>
                                                                                    <option value="دبي" <?php $branch = $row['branch']; echo ($selectedBranch == "دبي") ? 'selected' : ''; ?>>دبي</option>
                                                                                    <option value="عجمان" <?php $branch = $row['branch']; echo ($selectedBranch == "عجمان") ? 'selected' : ''; ?>>عجمان</option>
                                                                                </select>
                                                                            </th>
                                                                            <th align="left"  dir="rtl">الفرع :</th>
                                                                        </tr>
                                                                        
                                                                        <tr valign="top" >
                                                                            <th align="right"  dir="rtl" valign="top">
                                                                                <input type="file" name="photo" dir="ltr" style="font-size:12px"><br>
                                                                                <?php
                                                                                    if(isset($row['photo']) && $row['photo'] !== ''){
                                                                                ?>
                                                                                <a href="<?php echo $row['photo'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                                                    <img src="<?php echo $row['photo'];?>"  width="120" height="120" align="absmiddle" border="1" style="border-color:#333"/>
                                                                                </a><br>
                                                                                <input type="checkbox" name="remove_photo" value="1"  style="border:0"/> حذف المرفق
                                                                                <?php }?>
                                                                            </th>
                                                                            <th align="left"  dir="rtl">صورة المركبة :</th>
                                                                        </tr>
                                                                        
                                                                        <tr >
                                                                            <th align="right"  dir="rtl">
                                                                                <textarea dir="rtl" wrap="physical" rows="2" style="width:98%" name="notes"><?php if(isset($row['notes']) && $row['notes'] !== ''){echo $row['notes'];}?></textarea>
                                                                            </th>
                                                                            <th align="left"  dir="rtl"> ملاحظات :</th>
                                                                        </tr>
                                                                        
                                                                        <tr >
                                                                            <th colspan="2">
                                                                                <input type="button" value=" افراغ الحقول" onClick="location.href='Office_Vehicles.php';" class="button"> 
                                                                                <input type="submit" value=" حفظ البيانات" class="button"> 
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
                                                    <form action="vehdel.php" method="post">
                                                        <tr  class="header_table">
                                                            <th dir="ltr"> الكل 
                                                                <input type="checkbox" style="border:0px;" onClick="var T=null; T=document.getElementsByName('datachk');  for(var y=0; y<T.length; y++)	T[y].checked=checked;">
                                                            </th>
                                                            <th width="4%" dir="ltr">تعديل</th>
                                                            <th width="10%" dir="ltr">الفرع</th>
                                                            <th width="14%" dir="ltr">انتهاء التأمين</th>
                                                            <th width="14%" dir="ltr">النتهاء الملكية</th>
                                                            <th width="10%" dir="ltr">رقم السيارة</th>
                                                            <th width="29%" dir="ltr">موديل السيارة</th>
                                                            <th width="14%" dir="ltr">نوع السيارة</th>
                                                        </tr>
                                                        
                                                        <?php
                                                            $query_vehs = "SELECT * FROM vehicles";
                                                            $result_vehs = mysqli_query($conn, $query_vehs);
                                                            if($result_vehs->num_rows > 0){
                                                                while($row_vehs = mysqli_fetch_array($result_vehs)){
                                                        ?>
                                                        <tr valign="top" bgcolor="#ffffff" onMouseOver="bgColor='#eaeaea'" onMouseOut="bgColor='#ffffff'">
                                                            <th>
                                                                <input type="checkbox" name="CheckedD[]" style="border:0;background : transparent;" value= <?php echo $row_vehs['id'];?>>
                                                            </th>
                                                            <th class="reg"  >
                                                                <?php
                                                                    if($row_permcheck['emp_perms_edit'] === '1'){
                                                                ?>
                                                                <img src="images/Edit.png"  border="0" onClick="location.href='vehicleEdit.php?id=<?php echo $row_vehs['id'];?>';"  title="اضغط هنا للتعديل" style="cursor:pointer" />
                                                                <?php }?>
                                                            </th>
                                                            <th >
                                                                <?php 
                                                                    if(isset($row_vehs['branch']) && $row_vehs['branch'] !== ''){
                                                                        echo $row_vehs['branch'] . '-';
                                                                        if($row_vehs['branch'] === 'عجمان'){
                                                                            echo 'AJM';
                                                                        }else if($row_vehs['branch'] === 'دبي'){
                                                                            echo 'DXB';
                                                                        }else if($row_vehs['branch'] === 'الشارقة'){
                                                                            echo 'SHJ';
                                                                        }
                                                                    }
                                                                ?>
                                                            </th>
                                                            <th ><?php if(isset($row_vehs['insur_expir']) && $row_vehs['insur_expir'] !== ''){echo $row_vehs['insur_expir'];}?></th>
                                                            <th ><?php if(isset($row_vehs['lic_expir']) && $row_vehs['lic_expir'] !== ''){echo $row_vehs['lic_expir'];}?></th>
                                                            <th ><?php if(isset($row_vehs['num']) && $row_vehs['num'] !== ''){echo $row_vehs['num'];}?> </th>
                                                            <th ><?php if(isset($row_vehs['model']) && $row_vehs['model'] !== ''){echo $row_vehs['model'];}?></th>
                                                            <th  style="color:#00F" ><?php if(isset($row_vehs['type']) && $row_vehs['type'] !== ''){echo $row_vehs['type'];}?> </th>
                                                        </tr>
                                                        <?php
                                                                }
                                                            }
                                                        ?> 
                                                        <td>
                                                            <?php if($row_permcheck['emp_perms_delete'] === '1'){?>
                                                            <input type="submit" value="حذف" class="button">
                                                            <?php }?>
                                                        </td>
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