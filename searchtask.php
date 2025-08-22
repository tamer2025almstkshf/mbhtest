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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <div class="container">
            <?php 
                include_once 'userInfo.php';
                include_once 'sidebar.php';
                
                $myid = $_SESSION['id'];
                $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
                $result_permcheck = mysqli_query($conn, $query_permcheck);
                $row_permcheck = mysqli_fetch_array($result_permcheck);
                
                if($row_permcheck['admjobs_rperm'] === '1'){
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
                            <div <?php if($row_permcheck['admjobs_pperm'] === '1'){?>id="content-to-print"<?php }?>>
                                <table width="99%" border="0" cellspacing="1" cellpadding="1" dir="rtl">
                                    <tr valign="top" bgcolor="#FFFFFF">
                                        <th align="right" colspan="2" dir="rtl" class="table2 " >
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr valign="top">
                                                    <td class="red" width="60%">
                                                        <a href="index.php" class="Main">
                                                            <img src="images/homepage.png"   align="absmiddle" border="0"/> الصفحة الرئيسية
                                                        </a> &raquo; استعلام اعمال إدارية
                                                        <br /><br />
                                                    </td>
                                                    
                                                    <td>
                                                        <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                                            <tr style=" font-size:16px; color:#000; cursor:pointer" align="center" onclick="location.href='searchtask.php'">
                                                                <td>&nbsp;</td>
                                                                <?php 
                                                                    $queryca = "SELECT COUNT(*) as countca FROM tasks WHERE task_status!='2'";
                                                                    $resultca = mysqli_query($conn,$queryca);
                                                                    $rowca = mysqli_fetch_array($resultca)
                                                                ?>
                                                                <td width="5%" class="red"><?php echo $rowca['countca'];?></td>
                                                                <td width="90%" align="right">اجمالى الاعمال الإدارية</td>
                                                            </tr>
                                                            
                                                            <tr style=" font-size:16px; cursor:pointer" align="center" onclick="location.href='searchtask.php?type=inprogress'">
                                                                <td  bgcolor="#FFFF00" >&nbsp;</td>
                                                                <?php 
                                                                    $querycp = "SELECT COUNT(*) as countcp FROM tasks WHERE task_status='1'";
                                                                    $resultcp = mysqli_query($conn,$querycp);
                                                                    $rowcp = mysqli_fetch_array($resultcp)
                                                                ?>
                                                                <td class="red"><?php echo $rowcp['countcp'];?></td>
                                                                <td align="right" >أعمال إدارية جارى العمل عليها</td>
                                                            </tr>
    
                                                            <tr style=" font-size:16px; cursor:pointer" align="center" onclick="location.href='searchtask.php?type=pending'">
                                                                <td  bgcolor="#FF0000" >&nbsp;</td>
                                                                <?php 
                                                                    $querycn = "SELECT COUNT(*) as countcn FROM tasks WHERE task_status='0'";
                                                                    $resultcn = mysqli_query($conn,$querycn);
                                                                    $rowcn = mysqli_fetch_array($resultcn)
                                                                ?>
                                                                <td class="red"><?php echo $rowcn['countcn'];?></td>
                                                                <td align="right" >اعمال إدارية لم يتخذ بها إجراء</td>
                                                            </tr>
    
                                                            <tr >
                                                                <td colspan="3"><hr /></td>
                                                            </tr>
                                                            
                                                            <tr style="font-size:16px; cursor:pointer" align="center" onclick="location.href='searchtask.php?type=finished'">
                                                                <td  bgcolor="#006600">&nbsp;</td>
                                                                <?php 
                                                                    $querycf = "SELECT COUNT(*) as countcf FROM tasks WHERE task_status='2'";
                                                                    $resultcf = mysqli_query($conn,$querycf);
                                                                    $rowcf = mysqli_fetch_array($resultcf)
                                                                ?>
                                                                <td class="red"><?php echo $rowcf['countcf'];?></td>
                                                                <td align="right" >اعمال إدارية منتهية</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </th>
                                    </tr>
                                    
                                    <tr>
                                        <th>
                                            <div id="printableDiv">
                                                <table  style="font-size:14px" width="100%" border="1" cellspacing="0" cellpadding="0" align="center"  bordercolor="#c0b89c"  bgcolor="#FFFFFF" >
                                                    <tr class="header_table" height="40">
                                                        <th colspan="5" align="center" bgcolor="#FFFFFF" style="color:#333">
                                                            <?php if($row_permcheck['admjobs_pperm'] === '1'){?><img alt="" src="images/Print.png"  align="left" style="cursor:pointer" onclick="printDiv('printableDiv')" /><?php }?>
                                                        </th>
                                                    </tr>
    
                                                    <tr class="header_table" height="40" valign="top">
                                                        <th colspan="5" align="center" style="align-content: center;">
                                                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                                                <tr>
                                                                    <td width="16%" align="left">البحث :</td>
                                                                    <td width="84%">
                                                                        <input type="text" placeholder="Search...." dir="ltr" id="SearchBox">
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </th>
                                                    </tr>
    
                                                        
                                                    <tr  class="header_table" height="40">
                                                        <th width="3%" align="center">تسلسل</th>
                                                        <th width="27%" align="center">تفاصيل القضية</th>
                                                        <th width="19%" align="center">الموظف المكلف</th>
                                                        <th width="48%" align="center">التفاصيل</th>
                                                        <th width="3%" align="center">اجراءات</th>
                                                    </tr>
    
                                                    <?php
                                                        if(!isset($_GET['type']) || $_GET['type'] === ''){
                                                            $query = "SELECT * FROM tasks WHERE task_status!='2'";
                                                            $result = mysqli_query($conn, $query);
                                                        }
                                                        if(isset($_GET['type']) && $_GET['type'] === 'pending'){
                                                            $query = "SELECT * FROM tasks WHERE task_status='0'";
                                                            $result = mysqli_query($conn, $query);
                                                        }
                                                        if(isset($_GET['type']) && $_GET['type'] === 'inprogress'){
                                                            $query = "SELECT * FROM tasks WHERE task_status='1'";
                                                            $result = mysqli_query($conn, $query);
                                                        }
                                                        if(isset($_GET['type']) && $_GET['type'] === 'finished'){
                                                            $query = "SELECT * FROM tasks WHERE task_status='2'";
                                                            $result = mysqli_query($conn, $query);
                                                        }
                                                        $serial = 0;
                                                        if($result->num_rows > 0){
                                                            while($row=mysqli_fetch_array($result)){
                                                                $serial++;
                                                    ?>
                                                            
                                                    <tbody id="table1">
                                                        <tr valign="top" style="color:#000;font-weight:normal;background-color:<?php if(isset($row['task_status']) && $row['task_status'] === '0'){ echo '#FDD0D0';} else if(isset($row['task_status']) && $row['task_status'] === '1'){echo '#FDDF00';} else if(isset($row['task_status']) && $row['task_status'] === '2'){echo '#B7F9A6';}?>">
                                                            <td align="center"  bgcolor="<?php if(isset($row['task_status']) && $row['task_status'] === '0'){ echo '#FF0000';} else if(isset($row['task_status']) && $row['task_status'] === '1'){echo '#FFFF00';} else if(isset($row['task_status']) && $row['task_status'] === '2'){echo '#006600';}?>"  width="3%"><?php echo $serial;?></td>
                                                            
                                                            <td align="right" style="padding-right:2px;cursor:pointer" onclick="MM_openBrWindow('CasePreview.php?fid=<?php if(isset($row['file_no']) && $row['file_no'] !== ''){ echo $row['file_no'];}?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                                                <?php 
                                                                    if(isset($row['file_no']) && $row['file_no'] !== ''){ 
                                                                        $fid = $row['file_no'];
                                                                        $queryfile = "SELECT * FROM file WHERE file_id='$fid'";
                                                                        $resultfile = mysqli_query($conn, $queryfile);
                                                                        $rowfile = mysqli_fetch_array($resultfile);
                                                                ?>
                                                                <b>درجة التقاضي : </b><font color="blue"><?php
                                                                if(isset($row['degree']) && $row['degree'] !== '0'){
                                                                    $deg = $row['degree'];
                                                                    
                                                                    $querydeg = "SELECT * FROM file_degrees WHERE id='$deg'";
                                                                    $resultdeg = mysqli_query($conn, $querydeg);
                                                                    $rowdeg = mysqli_fetch_array($resultdeg);
                                                                    
                                                                    if(isset($rowdeg['degree']) && $rowdeg['degree'] !== ''){ 
                                                                        echo $rowdeg['degree'];
                                                                    } if(isset($rowdeg['case_num']) && $rowdeg['case_num'] !== ''){ 
                                                                        echo '-'.$rowdeg['case_num'];
                                                                    } if(isset($rowdeg['file_year']) && $rowdeg['file_year'] !== ''){ 
                                                                        echo '/'.$rowdeg['file_year'];
                                                                    }
                                                                }?> </font><br>
                                                                <b>نوع القضية :</b><?php if(isset($rowfile['fcase_type']) && $rowfile['fcase_type'] !== ''){ echo $rowfile['fcase_type'];}?> <br>
                                                                <b>المحكمة :</b> <?php if(isset($rowfile['file_court']) && $rowfile['file_court'] !== ''){ echo $rowfile['file_court'];}?> <br>
                                                                <b>الموكل : </b>
                                                                <?php 
                                                                    if(isset($rowfile['file_client']) && $rowfile['file_client'] !== ''){ 
                                                                        $cid = $rowfile['file_client'];
                                                                        $querycc = "SELECT * FROM client WHERE id='$cid'";
                                                                        $resultcc = mysqli_query($conn, $querycc);
                                                                        $rowcc = mysqli_fetch_array($resultcc);
                                                                        echo '<font color="green">'.$rowcc['arname'];
                                                                    }
                                                                    if(isset($rowfile['fclient_characteristic']) && $rowfile['fclient_characteristic'] !== ''){ 
                                                                        echo ' / </font><font color="blue">' . $rowfile['fclient_characteristic'] . '</font>';
                                                                    }
                                                                ?><br>
                                                                <?php
                                                                    } else{
                                                                        echo '-';
                                                                    }
                                                                ?>
                                                            </td>
                                                            
                                                            <td align="center" style="font-size:14px; font-weight:bold" >
                                                                <font color="#FF0000">
                                                                    <?php 
                                                                        if(isset($row['task_type']) && $row['task_type'] !== ''){ 
                                                                            $tt = $row['task_type']; 
                                                                            $queryt = "SELECT * FROM job_name WHERE id='$tt'"; 
                                                                            $resultt = mysqli_query($conn, $queryt); 
                                                                            $rowt = mysqli_fetch_array($resultt); 
                                                                            echo $rowt['job_name'];
                                                                        }
                                                                    ?>
                                                                </font>
                                                                <br />ت.التكليف :<?php if(isset($row['duedate']) && $row['duedate'] !== ''){echo $row['duedate'];}?>	
                                                                <?php
                                                                    if(isset($row['employee_id']) && $row['employee_id'] !== ''){
                                                                        $empid = $row['employee_id'];
                                                                        $queryemp = "SELECT * FROM user WHERE id='$empid'";
                                                                        $resultemp = mysqli_query($conn, $queryemp);
                                                                        $rowemp = mysqli_fetch_array($resultemp);
                                                                        if(isset($rowemp['name']) && $rowemp['name'] !== ''){
                                                                            $empname = $rowemp['name'];
                                                                            echo $empname;
                                                                        }
                                                                    }
                                                                ?>
                                                                <br />(<font color=#0000FF><?php if(isset($row['priority']) && $row['priority'] !== ''){ if($row['priority'] === '0'){ echo 'عادي';} else{ echo '<img src="images/ajel.gif" align="absmiddle" height="25" border="0">'; }}?></font>)    
                                                            </td>
                                                            
                                                            <th align="center" bgcolor="#FFFFCC"><?php if(isset($row['details']) && $row['details'] !== ''){ echo $row['details'];}?></th>
                                                            <td align="center" style=" color:#333" title="">
                                                                <br />
                                                                <?php if($row_permcheck['admjobs_eperm'] === '1'){?>
                                                                <a href="#null" onclick="open('EditTask.php?id=<?php if(isset($row['id']) && $row['id'] !== ''){ echo $row['id'];}?>','Pic','width=800 height=800 scrollbars=yes')">
                                                                    <img src="images/EditB.png"  border="0" title="تعديل"/>
                                                                </a>
                                                                <br />
                                                                <?php }?>
                                                                
                                                                <img src="images/Action.png" border="0" title="<?php if(isset($row['id']) && $row['id'] !== ''){ echo $row['id'] . ' - ';}?>اضغط هنا للأجراء " onclick="javascript:funHS('<?php echo $row['id'];?>')" style="cursor:pointer"/>
                                                            </td>
                                                        </tr>
                                                            
                                                        <tr >
                                                            <td colspan="5"  dir="rtl" style="padding:3px;">
                                                                <table id="item<?php echo $row['id'];?>"  style="display:none" align="center" border="0" cellspacing="0" cellpadding="0" class="table2" >
                                                                    <form action="taskmove.php" method="post" enctype="multipart/form-data">    
                                                                        <tr valign="top">
                                                                            <th width="95%" align="right">
                                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                    <tr>
                                                                                        <td>
                                                                                            <font  style="font-size:16px; font-weight:bold">تحويل الى :</font> 
                                                                                            <input type="hidden" name="idddd" value="<?php echo $row['id'];?>" id="">
                                                                                            <select name="re_name" dir="rtl" style="width:300px">
                                                                                                <option value="" ></option>
                                                                                                <?php
                                                                                                    $oldemp = $row['employee_id'];
                                                                                                    $queryemps = "SELECT * FROM user WHERE id!='$oldemp'";
                                                                                                    $resultemps = mysqli_query($conn, $queryemps);
                                                                                                    if($resultemps->num_rows > 0){
                                                                                                        while($rowemps = mysqli_fetch_array($resultemps)){
                                                                                                ?>
                                                                                                <option value="<?php echo $rowemps['id'];?>"><?php echo $rowemps['name'];?></option>
                                                                                                <?php
                                                                                                        }
                                                                                                    }
                                                                                                ?>
                                                                                            </select>
                                                                                            <br><br>
                                                                                            <?php if($row_permcheck['admjobs_dperm'] === '1'){?>
                                                                                            <input type="submit" value="الغاء العمل" style="cursor:pointer;" class="button" name="delete">
                                                                                            <?php }?>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </th>
                                                                        
                                                                            <td width="5%" align="right">
                                                                                <input type="image"  src="images/saveDate.png" title="اعتماد"  onClick="submit()" style="border:0px;">
                                                                            </td>
                                                                        </tr>
                                                                    </form>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <?php 
                                                            } 
                                                        }
                                                    ?>
                                                </table>
                                            </div>
                                        </th>
                                    </tr>
                                </table> <br>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php }?>
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

<script language="JavaScript" type="text/JavaScript">
    function MM_openBrWindow(theURL,winName,features) {
        window.open(theURL,winName,features);
    }
</script>

<?php if($row_permcheck['admjobs_pperm'] === '1'){?>
<script>
    function printDiv(divId) {
        // Ensure only the target div is visible during printing
        const originalContent = document.body.innerHTML; // Save the original content of the page
        const divContent = document.getElementById(divId).outerHTML; // Get the full HTML of the div

        // Replace the body content with the div content
        document.body.innerHTML = divContent;

        // Trigger the print dialog
        window.print();

        // Restore the original page content
        document.body.innerHTML = originalContent;
        location.reload(); // Reload the page to restore event listeners and scripts
    }
</script>
<?php }?>

<script>
    $(document).ready(function(){
        $('#SearchBox').on("keyup", function(){
            var value = $(this).val().toLowerCase();
            $("#table1 tr").filter(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>
<SCRIPT LANGUAGE="JavaScript" SRC="CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" ID="js13">var cal13 = new CalendarPopup();</SCRIPT>