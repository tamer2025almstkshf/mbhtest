<?php
    include_once 'connection.php';
    include_once 'login_check.php';

    if(!isset($_GET['id'])){
        header("Location: Contracts.php");
        exit();
    }

    $id = $_GET['id'];
    if($_GET['id'] === ''){
        header("Location: Contracts.php");
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
                                <form action="contedit.php" method="post" enctype="multipart/form-data" >
                                    <?php $id=$_GET['id'];?>
                                    <input type="hidden" name="id" value="<?php echo $id;?>">
                                    <table width="99%" border="0" cellspacing="1" cellpadding="1" dir="rtl">
                                        <tr valign="top" bgcolor="#FFFFFF">
                                            <th align="right" colspan="2" dir="rtl" class="table2">
                                                <a href="index.php" class="Main">
                                                    <img src="images/homepage.png"   align="absmiddle" border="0"/> الصفحة الرئيسية
                                                </a> &raquo; 
                                                <a href="employees.php" class="Main">شؤون الموظفين والموارد</a> 
                                                &raquo; الموارد - عقود الإيجار والرخص التجارية
                                            </th>
                                        </tr>
     
                                        <tr>
                                            <th align="center" colspan="2" dir="ltr" >
                                                <div style="display:block">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"  dir="rtl">
                                                        <tr valign="top" >
                                                            <td width="60%">
                                                                <table width="100%"  border="0" cellspacing="1" cellpadding="3" class="table" align="center"  dir="ltr"  bgcolor="#FFFFFF" >
                                                                    <tr id="edit">
                                                                        <th colspan="2" class="header_table" style="font-size:18px;"> تعديل بيانات المركبة </th>
                                                                    </tr>

                                                                    <?php
                                                                        $id = $_GET['id'];

                                                                        $querye = "SELECT * FROM contracts WHERE id='$id'";
                                                                        $resulte = mysqli_query($conn, $querye);

                                                                        if($resulte->num_rows == 0){
                                                                            exit();
                                                                        }

                                                                        $rowe = mysqli_fetch_array($resulte);
                                                                    ?>
                                                                    <tr >
                                                                        <th width="76%" align="right" >
                                                                            <?php $selected_type = $rowe['rent_lic']; ?>
                                                                            <select name="rent_lic" dir="rtl" style="width:30%;">
                                                                                <option value="عقد إيجار" <?php echo ($selected_type == 'عقد إيجار') ? 'selected' : ''; ?>>عقد إيجار</option>
                                                                                <option value="رخصة تجارية" <?php echo ($selected_type == 'رخصة تجارية') ? 'selected' : ''; ?>>رخصة تجارية</option>
                                                                            </select>
                                                                        </th>
                                                                        <th width="24%" align="left"  dir="rtl">عقد ايجار/رخصة تجارية :</th>
                                                                    </tr>
            
                                                                    <tr >
                                                                        <th width="76%" align="right" >
                                                                            <input type="text" name="owner" dir="rtl" value="<?php if(isset($rowe['owner']) && $rowe['owner'] !== ''){echo $rowe['owner'];}?>"  style="width:30%;"><br>
                                                                            <?php if(isset($_GET['error']) && $_GET['error'] === 'owner'){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى كتابة اسم المالك</span><?php }?>
                                                                        </th>
                                                                        <th width="24%" align="left"  dir="rtl">المالك :</th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th width="76%" align="right" >
                                                                            <input type="text" name="place"  value="<?php if(isset($rowe['place']) && $rowe['place'] !== ''){echo $rowe['place'];}?>" style="width:30%; text-align:center">
                                                                        </th>
                                                                        <th width="24%" align="left"  dir="rtl" >المكان :</th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <input type="date" name="starting_d" dir="rtl" size="10" value="<?php if(isset($rowe['starting_d']) && $rowe['starting_d'] !== ''){echo $rowe['starting_d'];}?>" style="text-align:center; font-weight:bold; color:#F00" >
                                                                            الى 
                                                                            <input type="date" name="ending_d" dir="rtl" size="10" value="<?php if(isset($rowe['ending_d']) && $rowe['ending_d'] !== ''){echo $rowe['ending_d'];}?>" style="text-align:center; font-weight:bold; color:#F00" >
                                                                        </th>
                                                                        <th align="left"  dir="rtl">بداية من :</th>
                                                                    </tr>
    
                                                                    <tr >
                                                                        <th align="right" dir="rtl">
                                                                            <?php $selected_branch = $rowe['branch']; ?>
                                                                            <select name="branch" dir="rtl" style="width:30%;">
                                                                                <option value="الشارقة" <?php echo ($selected_branch == 'الشارقة') ? 'selected' : ''; ?>>الشارقة</option>
                                                                                <option value="دبي" <?php echo ($selected_branch == 'دبي') ? 'selected' : ''; ?>>دبي</option>
                                                                                <option value="عجمان" <?php echo ($selected_branch == 'عجمان') ? 'selected' : ''; ?>>عجمان</option>
                                                                            </select> 
                                                                        </th>
                                                                        <th align="left"  dir="rtl">الفرع :</th>
                                                                    </tr>
    
                                                                    <tr valign="top" >
                                                                        <th align="right"  dir="rtl" valign="top">
                                                                            <input type="file" name="cont_lic_pic" dir="ltr" style="font-size:12px">
                                                                            <?php if(isset($rowe['cont_lic_pic']) && $rowe['cont_lic_pic'] !== ''){?><br>
                                                                            <a href="<?php echo $rowe['cont_lic_pic'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                                                <img src="<?php echo $rowe['cont_lic_pic'];?>"  width="120" height="120" align="absmiddle" border="1" style="border-color:#333"/>
                                                                            </a><br>
                                                                            <input type="checkbox" name="remove_contlic" value="1"  style="border:0"/> حذف الصورة
                                                                            <?php }?>
                                                                        </th>
                                                                        <th align="left"  dir="rtl">صورة العقد/الرخصة :</th>
                                                                    </tr>

                                                                    <tr valign="top" >
                                                                        <th align="right"  dir="rtl" valign="top">
                                                                            <input type="file" name="attachment1" dir="ltr" style="font-size:12px"> 
                                                                            <?php if(isset($rowe['attachment1']) && $rowe['attachment1'] !== ''){?>
                                                                            <a href="<?php echo $rowe['attachment1'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                                                <img src="images/DownloadB.png" align="absmiddle" border="0"/>
                                                                            </a> 
                                                                            <input type="checkbox" name="remove_att1" value="1"  style="border:0"/> حذف المرفق
                                                                            <?php }?>
                                                                        </th>
                                                                        <th align="left"  dir="rtl">مرفق2 :</th>
                                                                    </tr>
                                                                
                                                                    <tr valign="top" >
                                                                        <th align="right"  dir="rtl" valign="top">
                                                                            <input type="file" name="attachment2" dir="ltr" style="font-size:12px"> 
                                                                        </th>
                                                                        <th align="left"  dir="rtl">مرفق3 :</th>
                                                                    </tr>
  
                                                                    <tr valign="top" >
                                                                        <th align="right"  dir="rtl" valign="top">
                                                                            <input type="file" name="attachment3" dir="ltr" style="font-size:12px"> 
                                                                        </th>
                                                                        <th align="left"  dir="rtl">مرفق4 :</th>
                                                                    </tr>

                                                                    <tr valign="top" >
                                                                        <th align="right"  dir="rtl" valign="top">
                                                                            <input type="file" name="attachment4" dir="ltr" style="font-size:12px">
                                                                        </th>
                                                                        <th align="left"  dir="rtl">مرفق5 :</th>
                                                                    </tr>
                                                                        
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <textarea dir="rtl" wrap="physical" rows="2" style="width:98%" name="notes"><?php if(isset($rowe['notes']) && $rowe['notes'] !== ''){echo $rowe['notes'];}?></textarea>
                                                                        </th>
                                                                        <th align="left"  dir="rtl"> ملاحظات :</th>
                                                                    </tr>
    
                                                                    <tr >
                                                                        <th colspan="2">
                                                                            <input type="button" value=" افراغ الحقول" onClick="location.href='Contracts.php';" class="button">
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
                                <table width="99%"  border="0" cellspacing="2" cellpadding="2" align="center"  >
                                    <tr > 
                                        <th >
                                            <table width="100%"  border="0" cellspacing="1" cellpadding="1" class="table" dir="ltr">
                                                <form action="contdel.php" method="post">
                                                    <tr  class="header_table">
                                                        <th width="4%" dir="ltr"> الكل 
                                                            <input type="checkbox" style="border:0px;" onClick="var T=null; T=document.getElementsByName('datachk');  for(var y=0; y<T.length; y++)	T[y].checked=checked;">
                                                        </th>
                                                        <th width="4%" dir="ltr">تعديل</th>
                                                        <th width="11%" dir="ltr">صورة العقد / الرخصة</th>
                                                        <th width="17%" dir="ltr">الفرع</th>
                                                        <th width="11%" dir="ltr">المكان</th>
                                                        <th width="23%" dir="ltr">المالك</th>
                                                        <th width="17%" dir="ltr">الفترة الزمنية</th>
                                                        <th width="13%" dir="ltr">عقد ايجار/رخصة تجارية</th>
                                                    </tr>

                                                    <?php
                                                        $query = "SELECT * FROM contracts";
                                                        $result = mysqli_query($conn, $query);

                                                        if($result->num_rows > 0){
                                                            while($row=mysqli_fetch_array($result)){
                                                    ?>
                                                    <tr valign="top" class="title1" bgcolor="#ffffff" onMouseOver="bgColor='#eaeaea'" onMouseOut="bgColor='#ffffff'">
                                                        <th width="1%">
                                                            <input type="checkbox" name="CheckedD[]" style="border:0;background : transparent;" value=<?php echo $row['id'];?>>
                                                        </th>
                                                        <th class="reg"  >
                                                            <?php if($row_permcheck['emp_perms_edit'] === '1'){?>
                                                            <img src="images/Edit.png"  border="0" onClick="location.href='contractEdit.php?id=<?php echo $row['id'];?>';"  title="اضغط هنا للتعديل" style="cursor:pointer" />
                                                            <?php }?>
                                                        </th>
                                                        <th >
                                                            <?php if(isset($row['cont_lic_pic']) && $row['cont_lic_pic'] !== ''){?>
                                                            <a href="<?php echo $row['cont_lic_pic'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                                <img src="<?php echo $row['cont_lic_pic'];?>"  width="120" height="120" align="absmiddle" border="1"  style="border-color:#333"/>
                                                            </a> 
                                                            <?php } else{echo '-';}?>
                                                        </th>
                                                        <th >
                                                            <?php 
                                                                if(isset($row['branch']) && $row['branch'] !== ''){
                                                                    echo $row['branch'] . '-';
                                                                    if($row['branch'] === 'عجمان'){
                                                                        echo 'AJM';
                                                                    }else if($row['branch'] === 'دبي'){
                                                                        echo 'DXB';
                                                                    }else if($row['branch'] === 'الشارقة'){
                                                                        echo 'SHJ';
                                                                    }
                                                                }
                                                            ?>
                                                        </th>
                                                        <th ><?php if(isset($row['place']) && $row['place'] !== ''){echo $row['place'];}?></th>
                                                        <th ><?php if(isset($row['owner']) && $row['owner'] !== ''){echo $row['owner'];}?></th>
                                                        <th dir="rtl" style="font-size:12px" >
                                                            من تاريخ  (<font color="#FF0000"> <?php if(isset($row['starting_d']) && $row['starting_d'] !== ''){echo $row['starting_d'];}?></font>)<br> 
                                                            الى تاريخ (<font color="#FF0000"> <?php if(isset($row['ending_d']) && $row['ending_d'] !== ''){echo $row['ending_d'];}?></font>)
                                                        </th>
                                                        <th  style="color:#00F" ><?php if(isset($row['rent_lic']) && $row['rent_lic'] !== ''){echo $row['rent_lic'];}?></th>
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