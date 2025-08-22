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
                
                $myid = $_SESSION['id'];
                $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
                $result_permcheck = mysqli_query($conn, $query_permcheck);
                $row_permcheck = mysqli_fetch_array($result_permcheck);
                
                if($_GET['id'] === $myid || $row_permcheck['emp_rperm'] === '1'){
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
                                        <tr>
                                            <th align="center" colspan="2" dir="ltr" >
                                                <?php 
                                                    if(!isset($_GET['rid']) || $_GET['rid'] === ''){
                                                        if($_GET['id'] !== $myid && $row_permcheck['emp_aperm'] === '1'){
                                                ?>
                                                <button type="button" class="button" style="border:none;" onclick="collapse()">اضافة</button>
                                                <?php }}?>
                                                <div style="display:<?php if(isset($_GET['rid']) && $_GET['rid'] !== ''){ echo 'block'; } else{ echo 'none'; }?>" id="item1000">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"  dir="rtl">
                                                        <tr valign="top" >
                                                            <td width="60%">
                                                                <table width="100%"  border="0" cellspacing="1" cellpadding="3" class="table" align="center"  dir="ltr"  bgcolor="#FFFFFF" >
                                                                    <tr id="edit">
                                                                        <th colspan="2" class="header_table" style="font-size:18px;"> <?php if(isset($_GET['rid']) && $_GET['rid'] !== ''){ echo 'تعديل التقييم'; } else{ echo 'اضافة تقييم جديد'; }?></th>
                                                                    </tr>
                                                                    
                                                                    <form action="<?php if(isset($_GET['rid']) && $_GET['rid'] !== ''){ echo 'emp_formratesedit.php'; } else{ echo 'emp_formrates.php'; }?>" method="post" enctype="multipart/form-data">
                                                                        <?php
                                                                            if(isset($_GET['rid']) && $_GET['rid'] !== ''){
                                                                                $rid = $_GET['rid'];
                                                                                $queryr = "SELECT * FROM ratings WHERE id='$rid'";
                                                                                $resultr = mysqli_query($conn, $queryr);
                                                                                $rowr = mysqli_fetch_array($resultr);
                                                                            }
                                                                        ?>
                                                                        <tr >
                                                                            <th width="76%" align="right" >
                                                                                <?php if(isset($_GET['rid']) && $_GET['rid'] !== ''){?>
                                                                                <input type="hidden" name="id" value="<?php echo $_GET['rid'];?>">
                                                                                <?php }?>
                                                                                <input type="date" value="<?php if(isset($rowr['rating_date']) && $rowr['rating_date'] !== ''){ echo $rowr['rating_date']; } else{ echo date('Y-m-d'); }?>" name="rating_date" dir="ltr" style="font-size:14px; background-color:#FFF"><br>
                                                                            </th>
                                                                            <th width="24%" align="left"  dir="rtl">تاريخ التقييم :</th>
                                                                        </tr>
                                                                        
                                                                        <tr >
                                                                            <th width="76%" align="right" >
                                                                                <select name="rating_type">
                                                                                    <option value=""></option>
                                                                                    <option <?php if(isset($rowr['rating_type']) && $rowr['rating_type'] === 'ذاتي'){ echo 'selected'; }?> value="ذاتي">ذاتي</option>
                                                                                    <option <?php if(isset($rowr['rating_type']) && $rowr['rating_type'] === 'سنوي'){ echo 'selected'; }?> value="سنوي">سنوي</option>
                                                                                </select>
                                                                            </th>
                                                                            <th width="24%" align="left"  dir="rtl">نوع التقييم :</th>
                                                                        </tr>
                                                                        
                                                                        <tr >
                                                                            <th width="76%" align="right" >
                                                                                <input type="file" name="attachment" dir="ltr" style="font-size:10px; background-color:#FFF">
                                                                                <input type="hidden" name="user_id" value="<?php echo $_GET['id'];?>">
                                                                            </th>
                                                                            <th width="24%" align="left"  dir="rtl" >المرفقات :</th>
                                                                        </tr>
                                                                        
                                                                        <tr >
                                                                            <th colspan="2">
                                                                                <input type="button" value=" افراغ الحقول" onClick="location.href='<?php $id = $_GET['id']; $rid = $_GET['rid']; if(isset($_GET['rid']) && $_GET['rid'] !== ''){ echo "emp_rates.php?id=$id&rid=$rid"; } else{ echo "emp_rates.php?id=$id"; }?>';" class="button">
                                                                                <?php if($_GET['id'] !== $myid && ($row_permcheck['emp_aperm'] === '1' || $row_permcheck['emp_eperm'])){?>
                                                                                <input type="submit" value="<?php if(isset($_GET['rid']) && $_GET['rid'] !== ''){ echo 'تعديل البيانات'; } else{ echo 'حفظ البيانات'; }?>" class="button"> 
                                                                                <?php } else{ echo "<font style='color:red;'>ليس لديك الصلاحية لاضافة و تعديل التقييمات</font>"; }?>
                                                                            </th>
                                                                        </tr>
                                                                    </form>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </th>
                                        </tr>    
                                    
                                    <tr bgcolor="#FFFFFF">
                                        <th align="right"  colspan="2" ><hr /></th>
                                    </tr>
                                </table>
                                <br>
                                <div class="block1">
                                    <form action="delurates.php" method="post" enctype="multipart/form-data">
                                        <table width="100%" border="0" cellspacing="1" cellpadding="1" class="table" dir="ltr">
                                            <tbody>
                                                <tr class="header_table">
                                                    <th width="1%" dir="ltr"> الكل 
                                                        <input type="checkbox" style="border:0px;" onClick="var T=null; T=document.getElementsByName('datachk');  for(var y=0; y<T.length; y++)	T[y].checked=checked;">
                                                    </th>
                                                    <th width="1%" dir="rtl">تعديل</th>
                                                    <th width="7%" dir="rtl">المرفقات</th>
                                                    <th width="5%" dir="rtl">نوع التقييم</th>
                                                    <th width="7%" dir="rtl">تاريخ التقييم</th>
                                                </tr>
                                            </tbody>
                                            
                                            <tbody id="table1">
                                                <?php
                                                    $user_id = $_GET['id'];
                                                    $queryr = "SELECT * FROM ratings WHERE emp_id='$user_id'";
                                                    $resultr = mysqli_query($conn, $queryr);
                                                    if($resultr > 0){
                                                        while($rowr = mysqli_fetch_array($resultr)){
                                                ?>
                                                <tr valign="top" class="title1" bgcolor="#eaeaea" onmouseover="bgColor='#ffffff'" onmouseout="bgColor='#eaeaea'">
                                                    <th>
                                                        <input type="checkbox" name="CheckedD[]" style="border:0;background : transparent;" value= <?php echo $rowr['id'];?>>
                                                    </th>
                                                    <th>
                                                        <?php if($_GET['id'] !== $myid && $row_permcheck['emp_eperm'] === '1'){?>
                                                        <img src="images/Edit.png"  border="0" onClick="location.href='emp_rates.php?id=<?php echo $_GET['id'];?>&rid=<?php echo $rowr['id'];?>';"  title="اضغط هنا للتعديل" style="cursor:pointer" />
                                                        <?php }?>
                                                    </th>
                                                    <th>
                                                        <?php 
                                                            if(isset($rowr['attachment']) && $rowr['attachment'] !== ''){
                                                        ?>
                                                        <a href="<?php echo $rowr['attachment'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                            <?php echo basename($rowr['attachment']);?>
                                                        </a> 
                                                        <?php }?>
                                                    </th>
                                                    <th><?php echo $rowr['rating_type'];?></th>
                                                    <th><?php echo $rowr['rating_date'];?></th>
                                                </tr>
                                                <?php }}?>
                                                <td>
                                                    <input type="hidden" name="userid" value="<?php echo $_GET['id'];?>">
                                                    <?php if($_GET['id'] !== $myid && $row_permcheck['emp_dperm'] === '1'){?>
                                                    <input type="submit" value="حذف" class="button">
                                                    <?php }?>
                                                </td>
                                            </tbody>
                                        </table><br>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="footer">محمد بني هاشم للمحاماة والاستشارات القانونية<img alt="" src="images/f.png" width="29" height="31" /><img alt="" src="images/w.png" width="29" height="31" /></div>
        </div>
        <?php }?>
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

<script>
    function toggleModal(id){ 
        var toggleModal = document.getElementById('taskaddplus-btn-' + id); 
        if(toggleModal.style.display === 'block'){ 
            toggleModal.style.display = 'none'; 
        } else { 
            toggleModal.style.display = 'block'; 
        } 
    }
</script>