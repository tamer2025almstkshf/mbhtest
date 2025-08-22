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
        <div class="block1">
            <form action="emp_form.php" method="post" enctype="multipart/form-data">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>
                        <td>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table"> 
                                <tr align="center" style="font-weight:bold" class="header_table">
                                    <td width="34%">اسم المدخل</td>
                                    <td width="26%">اسم المستند</td>
                                    <td width="40%">المستند او الملف المرفق</td>
                                </tr>
                                
                                <?php
                                    $user_id = $_GET['id'];
                                    $query_user = "SELECT * FROM user_attachments WHERE user_id='$user_id'";
                                    $result_user = mysqli_query($conn, $query_user);
                                    $row_user = mysqli_fetch_array($result_user);
                                ?>
                                <tr bgcolor='#ecdec4' class="color-change" >
                                    <td align="center">
                                        <?php
                                            if(isset($row_user['biography_by']) && $row_user['biography_by'] !== ''){
                                                $done_by = $row_user['biography_by'];
                                                $querydone = "SELECT * FROM user WHERE id='$done_by'";
                                                $resultdone = mysqli_query($conn, $querydone);
                                                $rowdone = mysqli_fetch_array($resultdone);
                                                echo $rowdone['name'];
                                            }
                                        ?>
                                    </td>
                                    <td align="center">السيرة الذاتية</td>
                                    <td align="center">
                                        <?php 
                                            if(isset($row_user['biography']) && $row_user['biography'] !== ''){
                                        ?>
                                        <a href="<?php echo $row_user['biography'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                            <?php echo basename($row_user['biography']);?>
                                        </a> <br>
                                        <input type="file" name="biography" dir="ltr" style="font-size:10px; background-color:#FFF">
                                        <?php } else{?>
                                        <input type="file" name="biography" dir="ltr" style="font-size:10px; background-color:#FFF">
                                        <?php }?>
                                    </td>
                                </tr>
                                <tr bgcolor='#ecdec4' class="color-change" >
                                    <td align="center"> - </td>
                                    <td align="center">جواز السفر</td>
                                    <td align="center">
                                        <?php 
                                            $queryu = "SELECT * FROM user WHERE id='$user_id'";
                                            $resultu = mysqli_query($conn, $queryu);
                                            $rowu = mysqli_fetch_array($resultu);
                                            if(isset($rowu['passport_residence']) && $rowu['passport_residence'] !== ''){
                                        ?>
                                        <a href="<?php echo $rowu['passport_residence'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                            <?php echo basename($rowu['passport_residence']);?>
                                        </a> <br>
                                        <input type="file" name="passport_residence" dir="ltr" style="font-size:10px; background-color:#FFF">
                                        <?php } else{?>
                                        <input type="file" name="passport_residence" dir="ltr" style="font-size:10px; background-color:#FFF">
                                        <?php }?>
                                    </td>
                                </tr>
                                <tr bgcolor='#ecdec4' class="color-change" >
                                    <td align="center">
                                        <?php
                                            if(isset($row_user['uaeresidence_by']) && $row_user['uaeresidence_by'] !== ''){
                                                $done_by = $row_user['uaeresidence_by'];
                                                $querydone = "SELECT * FROM user WHERE id='$done_by'";
                                                $resultdone = mysqli_query($conn, $querydone);
                                                $rowdone = mysqli_fetch_array($resultdone);
                                                echo $rowdone['name'];
                                            }
                                        ?>
                                    </td>
                                    <td align="center">الهوية الاماراتية</td>
                                    <td align="center">
                                        <?php 
                                            if(isset($row_user['uaeresidence']) && $row_user['uaeresidence'] !== ''){
                                        ?>
                                        <a href="<?php echo $row_user['uaeresidence'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                            <?php echo basename($row_user['uaeresidence']);?>
                                        </a> <br>
                                        <input type="file" name="uaeresidence" dir="ltr" style="font-size:10px; background-color:#FFF">
                                        <?php } else{?>
                                        <input type="file" name="uaeresidence" dir="ltr" style="font-size:10px; background-color:#FFF">
                                        <?php }?>
                                    </td>
                                </tr>
                                <tr bgcolor='#ecdec4' class="color-change" >
                                    <td align="center">
                                        <?php
                                            if(isset($row_user['behaviour_by']) && $row_user['behaviour_by'] !== ''){
                                                $done_by = $row_user['behaviour_by'];
                                                $querydone = "SELECT * FROM user WHERE id='$done_by'";
                                                $resultdone = mysqli_query($conn, $querydone);
                                                $rowdone = mysqli_fetch_array($resultdone);
                                                echo $rowdone['name'];
                                            }
                                        ?>
                                    </td>
                                    <td align="center">شهادة حسن السيرة و السلوك</td>
                                    <td align="center">
                                        <?php 
                                            if(isset($row_user['behaviour']) && $row_user['behaviour'] !== ''){
                                        ?>
                                        <a href="<?php echo $row_user['behaviour'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                            <?php echo basename($row_user['behaviour']);?>
                                        </a> <br>
                                        <input type="file" name="behaviour" dir="ltr" style="font-size:10px; background-color:#FFF">
                                        <?php } else{?>
                                        <input type="file" name="behaviour" dir="ltr" style="font-size:10px; background-color:#FFF">
                                        <?php }?>
                                    </td>
                                </tr>
                                <tr bgcolor='#ecdec4' class="color-change" >
                                    <td align="center">
                                        <?php
                                            if(isset($row_user['university_by']) && $row_user['university_by'] !== ''){
                                                $done_by = $row_user['university_by'];
                                                $querydone = "SELECT * FROM user WHERE id='$done_by'";
                                                $resultdone = mysqli_query($conn, $querydone);
                                                $rowdone = mysqli_fetch_array($resultdone);
                                                echo $rowdone['name'];
                                            }
                                        ?>
                                    </td>
                                    <td align="center">الشهادة الجامعية</td>
                                    <td align="center">
                                        <?php 
                                            if(isset($row_user['university']) && $row_user['university'] !== ''){
                                        ?>
                                        <a href="<?php echo $row_user['university'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                            <?php echo basename($row_user['university']);?>
                                        </a> <br>
                                        <input type="file" name="university" dir="ltr" style="font-size:10px; background-color:#FFF">
                                        <?php } else{?>
                                        <input type="file" name="university" dir="ltr" style="font-size:10px; background-color:#FFF">
                                        <?php }?>
                                    </td>
                                </tr>
                                <tr bgcolor='#ecdec4' class="color-change" >
                                    <td align="center">
                                        <?php
                                            if(isset($row_user['contract_by']) && $row_user['contract_by'] !== ''){
                                                $done_by = $row_user['contract_by'];
                                                $querydone = "SELECT * FROM user WHERE id='$done_by'";
                                                $resultdone = mysqli_query($conn, $querydone);
                                                $rowdone = mysqli_fetch_array($resultdone);
                                                echo $rowdone['name'];
                                            }
                                        ?>
                                    </td>
                                    <td align="center">عقد العمل</td>
                                    <td align="center">
                                        <?php 
                                            if(isset($row_user['contract']) && $row_user['contract'] !== ''){
                                        ?>
                                        <a href="<?php echo $row_user['contract'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                            <?php echo basename($row_user['contract']);?>
                                        </a> <br>
                                        <input type="file" name="contract" dir="ltr" style="font-size:10px; background-color:#FFF">
                                        <?php } else{?>
                                        <input type="file" name="contract" dir="ltr" style="font-size:10px; background-color:#FFF">
                                        <?php }?>
                                    </td>
                                </tr>
                                <tr bgcolor='#ecdec4' class="color-change" >
                                    <td align="center">
                                        <?php
                                            if(isset($row_user['card_by']) && $row_user['card_by'] !== ''){
                                                $done_by = $row_user['card_by'];
                                                $querydone = "SELECT * FROM user WHERE id='$done_by'";
                                                $resultdone = mysqli_query($conn, $querydone);
                                                $rowdone = mysqli_fetch_array($resultdone);
                                                echo $rowdone['name'];
                                            }
                                        ?>
                                    </td>
                                    <td align="center">بطاقة العمل</td>
                                    <td align="center">
                                        <?php 
                                            if(isset($row_user['card']) && $row_user['card'] !== ''){
                                        ?>
                                        <a href="<?php echo $row_user['card'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                            <?php echo basename($row_user['card']);?>
                                        </a> <br>
                                        <input type="file" name="card" dir="ltr" style="font-size:10px; background-color:#FFF">
                                        <?php } else{?>
                                        <input type="file" name="card" dir="ltr" style="font-size:10px; background-color:#FFF">
                                        <?php }?>
                                    </td>
                                </tr>
                                <tr bgcolor='#ecdec4' class="color-change" >
                                    <td align="center">
                                        <?php
                                            if(isset($row_user['sigorta_by']) && $row_user['sigorta_by'] !== ''){
                                                $done_by = $row_user['sigorta_by'];
                                                $querydone = "SELECT * FROM user WHERE id='$done_by'";
                                                $resultdone = mysqli_query($conn, $querydone);
                                                $rowdone = mysqli_fetch_array($resultdone);
                                                echo $rowdone['name'];
                                            }
                                        ?>
                                    </td>
                                    <td align="center">التأمين الصحي</td>
                                    <td align="center">
                                        <?php 
                                            if(isset($row_user['sigorta']) && $row_user['sigorta'] !== ''){
                                        ?>
                                        <a href="<?php echo $row_user['sigorta'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                            <?php echo basename($row_user['sigorta']);?>
                                        </a> <br>
                                        <input type="file" name="sigorta" dir="ltr" style="font-size:10px; background-color:#FFF">
                                        <?php } else{?>
                                        <input type="file" name="sigorta" dir="ltr" style="font-size:10px; background-color:#FFF">
                                        <?php }?>
                                    </td>
                                </tr>
                                <tr bgcolor='#ecdec4' class="color-change" >
                                    <td align="center">
                                        <?php
                                            if(isset($row_user['other_by']) && $row_user['other_by'] !== ''){
                                                $done_by = $row_user['other_by'];
                                                $querydone = "SELECT * FROM user WHERE id='$done_by'";
                                                $resultdone = mysqli_query($conn, $querydone);
                                                $rowdone = mysqli_fetch_array($resultdone);
                                                echo $rowdone['name'];
                                            }
                                        ?>
                                    </td>
                                    <td align="center">أخرى</td>
                                    <td align="center">
                                        <?php 
                                            if(isset($row_user['other']) && $row_user['other'] !== ''){
                                        ?>
                                        <a href="<?php echo $row_user['other'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                            <?php echo basename($row_user['other']);?>
                                        </a> <br>
                                        <input type="file" name="other" dir="ltr" style="font-size:10px; background-color:#FFF">
                                        <?php } else{?>
                                        <input type="file" name="other" dir="ltr" style="font-size:10px; background-color:#FFF">
                                        <?php }?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table> <br>
                
                <table width="100%" border="0" cellspacing="1" cellpadding="1" class="table" dir="ltr">
                    <tbody>
                        <tr class="header_table">
                            <th width="5%" dir="rtl">موافقة المدير</th>
                            <th width="7%" dir="rtl">موافقة الموارد البشرية</th>
                            <th width="7%" dir="rtl">مدة الاجازة</th>
                            <th width="7%" dir="rtl">المتبقي من الاجازة</th>
                            <th width="7%" dir="rtl">الى تاريخ</th>
                            <th width="3%" dir="rtl">من تاريخ</th>
                            <th width="5%" dir="rtl">نوع الاجازه</th>
                            <th width="5%" dir="rtl">تاريخ تقديم الطلب</th>
                        </tr>
                    </tbody>
                    
                    <tbody id="table1">
                        <?php
                            $user_id = $_GET['id'];
                            $queryv = "SELECT * FROM vacations WHERE emp_id='$user_id'";
                            $resultv = mysqli_query($conn, $queryv);
                            if($resultv > 0){
                                while($rowv = mysqli_fetch_array($resultv)){
                        ?>
                        <tr valign="top" class="title1" bgcolor="
                        <?php 
                            if(($rowv['ask'] === '1' || $rowv['ask'] === '0') && ($rowv['ask2'] === '0' || $rowv['ask2'] === '1')){ 
                                echo '#ffffff'; 
                            } else if($rowv['ask'] === '2' || $rowv['ask2'] === '2'){ 
                                echo '#ffc6c7'; 
                            } else if($rowv['ask'] === '3' && ($rowv['ask2'] === '0' || $rowv['ask2'] === '1')){ 
                                echo 'yellow'; 
                            } else if($rowv['ask'] === '3' && $rowv['ask2'] === '2'){ 
                                echo '#ffc6c7'; 
                            } else if($rowv['ask'] === '3' && $rowv['ask2'] === '3'){ 
                                echo '#00ff0080'; 
                            } else{
                                echo '#ffffff'; 
                            }
                        ?>">
                            
                            <th>
                                <?php 
                                    $ask2 = $rowv['ask2'];
                                    if($ask2 === '0' || $ask2 === '1'){
                                        echo 'في الانتظار';
                                    } else if($ask2 === '2'){
                                        echo 'رفض';
                                    } else if($ask2 === '3'){
                                        echo 'قبول';
                                    }
                                ?>
                            </th>
                            <th>
                                <?php 
                                    $ask = $rowv['ask'];
                                    if($ask === '0' || $ask === '1'){
                                        echo 'في الانتظار';
                                    } else if($ask === '2'){
                                        echo 'رفض';
                                    } else if($ask === '3'){
                                        echo 'قبول';
                                    }
                                ?>
                            </th>
                            <th>
                                <?php
                                    $sd = $rowv['starting_date'];
                                    $ed = $rowv['ending_date'];
                                    
                                    $d1 = new DateTime($sd);
                                    $d2 = new DateTime($ed);
                                    $interval = $d1->diff($d2);
                                    
                                    echo $interval->days;
                                ?>
                            </th>
                            <th>
                                <?php
                                    $today = date("Y-m-d"); // Get today's date
                                    $ed = $rowv['ending_date']; // Get ending date from database
                                    
                                    $d1 = new DateTime($today);
                                    $d2 = new DateTime($ed);
                                    
                                    // Calculate the difference
                                    $interval = $d1->diff($d2)->days;
                                    
                                    // If today is before the ending date, set the interval to 0
                                    if ($ed <= $today) {
                                        $interval = 0;
                                    }
                                    
                                    echo $interval;
                                ?>
                            </th>
                            <th><?php echo $rowv['ending_date'];?></th>
                            <th><?php echo $rowv['starting_date'];?></th>
                            <th><?php echo $rowv['type'];?></th>
                            <th><?php echo $rowv['ask_date'];?></th>
                        </tr>
                        <?php }}?>
                    </tbody>
                </table><br>
                
                <table width="100%" border="0" cellspacing="1" cellpadding="1" class="table" dir="ltr">
                    <tbody>
                        <tr class="header_table">
                            <th width="5%" dir="rtl">المرفقات</th>
                            <th width="7%" dir="rtl">السبب</th>
                            <th width="7%" dir="rtl">نوع الانذار</th>
                            <th width="3%" dir="rtl">تاريخ الانذار</th>
                        </tr>
                    </tbody>
                    
                    <tbody id="table1">
                        <?php
                            $user_id = $_GET['id'];
                            $queryw = "SELECT * FROM warnings WHERE emp_id='$user_id'";
                            $resultw = mysqli_query($conn, $queryw);
                            if($resultw > 0){
                                while($roww = mysqli_fetch_array($resultw)){
                        ?>
                        <tr valign="top" class="title1" bgcolor="#eaeaea" onmouseover="bgColor='#ffffff'" onmouseout="bgColor='#eaeaea'">
                            <input type="hidden" name="warning_id" value="<?php echo $roww['id'];?>">
                            <th>
                                <?php 
                                    if(isset($roww['attachments']) && $roww['attachments'] !== ''){
                                ?>
                                <a href="<?php echo $roww['attachments'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                    <?php echo basename($roww['attachments']);?>
                                </a> 
                                <?php } else{?>
                                <input type="file" name="attachments[<?php echo $roww['id']; ?>]" dir="ltr" style="font-size:10px; background-color:#FFF">
                                <?php }?>
                            </th>
                            <th><?php echo $roww['warning_reason'];?></th>
                            <th>
                                <?php 
                                    if(isset($roww['warning_type']) && $roww['warning_type'] !== ''){
                                        echo $roww['warning_type'];
                                    } else{
                                ?>
                                <select name="warning_type[<?php echo $roww['id']; ?>]">
                                    <option value=""></option>
                                    <option value="شفهي">شفهي</option>
                                    <option value="كتابي">كتابي</option>
                                </select>
                                <?php
                                    }
                                ?>
                            </th>
                            <th><?php echo $roww['warning_date'];?></th>
                        </tr>
                        <?php }}?>
                    </tbody>
                </table><br>
                
                <table width="100%" border="0" cellspacing="1" cellpadding="1" class="table" dir="ltr">
                    <tbody>
                        <tr class="header_table">
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
                        <tr valign="top" class="title1" bgcolor="#eaeaea" onmouseover="bgColor='#ffffff'" onmouseout="bgColor='#eaeaea'">
                            <th><input type="file" name="attachment" dir="ltr" style="font-size:10px; background-color:#FFF"></th>
                            <th>
                                <select name="rating_type">
                                    <option value=""></option>
                                    <option value="ذاتي">ذاتي</option>
                                    <option value="سنوي">سنوي</option>
                                </select>
                            </th>
                            <th><?php echo date("Y-m-d");?></th>
                        </tr>
                    </tbody>
                </table><br>
                
                <div style="width: 100%; text-align: center;">
                    <input type="hidden" name="user_id" value="<?php echo $_GET['id'];?>">
                    <input type="button" value="افراغ الحقول" onclick="location.href='employee.php?id=<?php echo $_GET['id'];?>'" class="button"> 
                    <input type="submit" name="saveinfo" value=" اضغط هنا لحفظ البيانات" class="button">
                </div>
            </form>
        </div>
    </body>
</html>