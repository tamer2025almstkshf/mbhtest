<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    if(!isset($_GET['fid']) || $_GET['fid'] === ''){
        header("Location: index.php");
        exit();
    }
    $fid = $_GET['fid'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="rtl">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1256" />
        <title>اضافة وتعديل الاعمال الإدارية</title>
        <link rel="stylesheet" type="text/css" href="css/sites.css">
    </head>

    <body style="margin-left: 0px; margin-top: 0px; margin-right: 0px; margin-bottom: 0px; background-color: #c3bb9e; background-image: url(images/bg.png); background-repeat: repeat; font-family: 'Times New Roman', Times, serif; font-size: 14px; color: #4b1807;">
        <script language="JavaScript" type="text/JavaScript">
            <!--
            function MM_openBrWindow(theURL,winName,features) { //v2.0
              window.open(theURL,winName,features);
            }
            //-->
        </script>
        <SCRIPT LANGUAGE="JavaScript" SRC="CalendarPopup.js"></SCRIPT>
        <SCRIPT LANGUAGE="JavaScript" ID="js13">var cal13 = new CalendarPopup();</SCRIPT>

        <?php
            $query = "SELECT * FROM file WHERE file_id='$fid'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result);
        ?>
        <form name="SearchForm" action="<?php if(isset($_GET['tid']) && $_GET['tid'] !== ''){ echo 'taedit.php'; } else{ echo 'tadd.php'; }?>" method="post" enctype="multipart/form-data" method="post"  >
            <input type="hidden" name="job_fid" value="<?php echo $_GET['fid'];?>"/>
            <table width="100%" border="0" cellspacing="2" cellpadding="2" align="center" bgcolor="#FFFFff"  >
                <tr>
                    <th width="16%">&nbsp;</th>
                    <th width="58%" align="right"><b><font color="#4b1807" style="font-size:16px">الاعمال الإدارية</font></b></th>
                    <th width="26%" rowspan="8" align="center" valign="top">
                        <img src="QrCode/500670.png" border="0" width="100" height="100"   align="absmiddle"/>  <br />
                        الموكل : <?php $cid = $row['file_client']; $queryc = "SELECT * FROM client WHERE id='$cid'"; $resultc = mysqli_query($conn, $queryc); $rowc = mysqli_fetch_array($resultc); echo $rowc['arname'];?> /<font color=blue>منفذ ضده</font> <br />الموضوع : <?php echo $row['file_subject'];?>  
                    </th>
                </tr>
                <?php
                    $selected_lr = '';
                    $selected_job = '';
                    $selecteds = '';
                    $selectedp = '';
                    $selectedDate = date("d/m/Y");
                    $selecteddets = '';
                    if(isset($_GET['tid']) && $_GET['tid'] !== ''){
                        $tid = $_GET['tid'];
                        $query_tid = "SELECT * FROM tasks WHERE id='$tid'";
                        $result_tid = mysqli_query($conn, $query_tid);
                        $rowtid = mysqli_fetch_array($result_tid);
                        
                        $selected_lr = $rowtid['employee_id'];
                        $selected_job = $rowtid['task_type'];
                        $selecteds = $rowtid['degree'];
                        $selectedp = $rowtid['priority'];
                        
                        $selectedDateUnformatted = $rowtid['duedate'];
                        list($y, $m, $d) = explode("-", $selectedDateUnformatted);
                        $selectedDate = "$d/$m/$y";
                        
                        $selecteddets = $rowtid['details'];
                        
                        echo "<input type='hidden' name='tid' value='$tid'";
                    }
                ?>

                <tr>
                    <th align="left">رقم الملف :</th>
                    <th align="right" dir="ltr" style=" font-size:18px; color:#00F">
                        <font color="#FF0000">
                            <?php
                                if(isset($row['frelated_place']) && $row['frelated_place'] !== ''){
                                    if($row['frelated_place'] === 'الشارقة'){
                                        echo 'SHJ';
                                    } else if($row['frelated_place'] === 'دبي'){
                                        echo 'DXB';
                                    } else if($row['frelated_place'] === 'عجمان'){
                                        echo 'AJM';
                                    } 
                                }
                            ?>
                        </font>
                        <?php 
                            echo $row['file_id'];
                        ?>
                    </th>
                </tr>
                
                 <tr valign="middle">
                    <th align="left">الباحث القانوني المكلف :</th>
                    <th align="right">
                        <select name="flegal_researcher" dir="rtl" style="width:50%;">
                            <?php
                                $query_lr = "SELECT * FROM user WHERE job_title='11'";
                                $result_lr = mysqli_query($conn, $query_lr);
                                if($result_lr->num_rows > 0){
                                    while($row_lr = mysqli_fetch_array($result_lr)){
                                        $lr_name = $row_lr['name'];
                            ?>
                            <option value='<?php echo $row_lr['id'];?>' <?php if($row_lr['id'] === $selected_lr){ echo 'selected';}?>><?php echo $lr_name;?></option>
                            <?php }}?>
                        </select>
                        <font color="#FF0000">*</font>	
                    </th>
                </tr>
                
                <tr valign="top">
                    <th align="left">نوع العمل :</th>
                    <th align="right"> 
                        <select name="type_name" dir="rtl" style="width:50%;">
                            <?php
                                $query_job = "SELECT * FROM job_name";
                                $result_job = mysqli_query($conn, $query_job);
                                if(isset($_GET['tn']) && $_GET['tn'] !== ''){$selectedTn = $_GET['tn'];}
                                if($result_job->num_rows > 0){
                                    while($row_job = mysqli_fetch_array($result_job)){
                            ?>
                            <option value="<?php echo $row_job['id'];?>" <?php if($row_job['id'] === $selected_job){ echo 'selected';}?>><?php echo $row_job['job_name'];?></option>
                            <?php }} else{?>
                            <option value=""></option>
                            <?php }?>
                        </select> 
                        <font color="#FF0000">*</font> 
                        <img src="images/addmore.jpg" align="absmiddle" title="اضافة" onclick="MM_openBrWindow('selector/addJob.php','','toolbar=yes,menubar=yes,scrollbars=yes,resizable=yes,width=400,height=400')" style="cursor:pointer"/>
                    </th>
                </tr>
         
                <tr valign="middle">
                    <th align="left">درجة التقاضي : </th>
                    <th align="right">
                        <select name="degree_id" dir="rtl" style="width:50%;">
                            <option value="0"></option>
                            <?php
                                $querys = "SELECT * FROM file_degrees WHERE fid='$fid'";
                                $results = mysqli_query($conn, $querys);
                                if($results->num_rows > 0){
                                    while($rows=mysqli_fetch_array($results)){
                            ?>
                            <option value="<?php echo $rows['id'];?>" <?php if($rows['id'] === $selecteds){ echo 'selected';}?>><?php echo $rows['case_num'].'/'.$rows['file_year'].'-'.$rows['degree'];?></option>
                            <?php }} else{?>
                            <option value=""></option>
                            <?php }?>
                        </select>
                    </th>
                </tr>
        
                <tr>
                    <th align="left">الاهمية :</th>
                    <td align="right" dir="rtl" style="font-size:16px">
                        <input type="radio"  name="priority" value="0" style="border:none" checked="checked" /><font color="#009900">عادي</font>
                        <input type="radio"  name="priority"  value="1" style="border:none" <?php if($selectedp === '1'){ echo "checked";}?> /><font color="#FF0000">عاجل</font>
                    </td>
                </tr>
              
                <tr>
                    <th align="left">تاريخ تنفيذ العمل  :</th>
                    <td align="right"> 
                        <input type="text" name="date" dir="rtl" size="10" value="<?php echo $selectedDate;?>" style="text-align:center; font-weight:bold; color:#F00" > 
                        <label style="cursor:pointer" onClick="cal13.select(document.SearchForm.date,'date','dd/MM/yyyy'); return false;"  NAME="date" ID="date"><img src="images/calendar.png" align="absmiddle"></label>
                    </td>
                </tr>
      
                <tr valign="top">
                    <th align="left">التفاصيل:</th>
                    <th align="right">
                        <textarea dir="rtl" wrap="physical" rows="3" style="width:98%" name="details"><?php echo $selecteddets;?></textarea>
                    </th>
                </tr>
      
                <tr>
                    <th>&nbsp;</th>
                    <th align="right">
                        <?php
                            if(isset($_GET['tid']) && $_GET['tid'] !== ''){
                        ?>
                        <input type="submit"  value="حفظ + تعديل البيانات" class="button"/>
                        <?php } else{?>
                        <input type="submit"  value="حفظ وتخزين البيانات" class="button"/>
                        <?php }?>
                        <input type="button"  value="مسح الحقول"  class="button" onclick="location.href='Business_Management.php?fid=<?php echo $_GET['fid'];?>'"/>
                    </th>
                </tr>
            </table><br />
            
            <table  style="font-size:16px" width="100%" border="1" cellspacing="0" cellpadding="0" align="center"  bordercolor="#c0b89c"  bgcolor="#FFFFFF" >
                <tr  bgcolor="#96693e"  style=" color:#FFF" height="40">
                    <th width="14%" align="center">درجة التقاضي</th>
                    <th width="20%" align="center">نوع العمل الإداري</th>
                    <th width="11%" align="center">الموظف المكلف</th>
                    <th width="11%" align="center">ت.التكليف </th>
                    <th width="31%" align="center">التفاصيل</th>
                    <th width="13%" align="center">م.ت/ الإدخال</th>
                    <th width="4%" align="center">طباعة</th>
                    <th width="5%" align="center">تعديل</th>
                    <th width="5%" align="center">حذف</th>
                </tr>
                <?php
                    $fid = $_GET['fid'];
                    $queryt = "SELECT * FROM tasks WHERE file_no='$fid'";
                    $resultt = mysqli_query($conn, $queryt);
                    if($resultt->num_rows > 0){
                        while($rowt = mysqli_fetch_array($resultt)){
                ?>
                <tr style=" background-color:<?php if(isset($rowt['task_status']) && $rowt['task_status'] === '0'){ echo '#FDD0D0';} else if(isset($rowt['task_status']) && $rowt['task_status'] === '1'){echo '#FFFF00';} else if(isset($rowt['task_status']) && $rowt['task_status'] === '2'){echo '#B7F9A6';}?>">
                    <td>
                        <?php 
                            $degreeid = $rowt['degree'];
                            
                            $queryu2 = "SELECT * FROM file_degrees WHERE id='$degreeid'";
                            $resultu2 = mysqli_query($conn, $queryu2);
                            $rowu2 = mysqli_fetch_array($resultu2);
                            $caseno = $rowu2['case_num'];
                            $year = $rowu2['file_year'];
                            $degree2 = $rowu2['degree'];
                            
                            echo $caseno.'/'.$year.'-'.$degree2;
                        ?>
                    </td>
                    <td>
                        <?php 
                            $task_type = $rowt['task_type'];
                            
                            $queryta = "SELECT * FROM job_name WHERE id='$task_type'";
                            $resultta = mysqli_query($conn, $queryta);
                            $rowta = mysqli_fetch_array($resultta);
                            $task_type2 = $rowta['job_name'];
                            
                            echo $task_type2;
                        ?>
                    </td>
                    <td>
                    <?php
                        $employee_id = $rowt['employee_id'];
                        
                        $queryu2 = "SELECT * FROM user WHERE id='$employee_id'";
                        $resultu2 = mysqli_query($conn, $queryu2);
                        $rowu2 = mysqli_fetch_array($resultu2);
                        $employee_name = $rowu2['name'];
                        
                        echo $employee_name;
                        
                        if(isset($rowt['priority']) && $rowt['priority'] !== ''){
                            $priority = $rowt['priority'];
                            if($priority === '1'){
                                echo '<br>';
                    ?>
                    <img src="images/urgent2-300x220.jpg" align="absmiddle" border="0">
                    <?php
                            }
                        }
                    ?>
                    </td>
                    <td><?php echo $rowt['duedate'];?></td>
                    <td><?php echo $rowt['details'];?></td>
                    <td>
                        <?php 
                            $responsible = $rowt['responsible'];
                            
                            $queryu = "SELECT * FROM user WHERE id='$responsible'";
                            $resultu = mysqli_query($conn, $queryu);
                            $rowu = mysqli_fetch_array($resultu);
                            $name = $rowu['name'];
                            
                            echo $rowt['timestamp'].' '.$name;
                        ?>
                    </td>
                    <td></td>
                    <td onclick="location.href='Business_Management.php?fid=<?php echo $_GET['fid'];?>&tid=<?php echo $rowt['id'];?>'" style="cursor: pointer;" title="اضغط هنا للتعديل"><img src="images/EditB.png" border="0"/></td>
                    <td align="center" width="5%" onclick="location.href='tdel.php?fid=<?php echo $_GET['fid'];?>&tid=<?php echo $rowt['id'];?>';" style="cursor: pointer;">
                        <img src="images/delete.png" align="absmiddle" border="0"/>
                    </td>
                </tr>
                <?php }}?>
            </table>
        </form>
    </body>
</html>	