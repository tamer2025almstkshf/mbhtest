<?php
    include_once 'connection.php';
    include_once 'login_check.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="rtl">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1256" />
        <title>اضافة وتعديل الاعمال الإدارية</title>
        <link rel="stylesheet" type="text/css" href="css/sites.css">
    </head>
    
    <body>
        <?php
            $myid = $_SESSION['id'];
            $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
            $result_permcheck = mysqli_query($conn, $query_permcheck);
            $row_permcheck = mysqli_fetch_array($result_permcheck);
            
        ?>
        <script language="JavaScript" type="text/JavaScript">
            function MM_openBrWindow(theURL,winName,features) {
                window.open(theURL,winName,features);
            }
        </script>
        
        <SCRIPT LANGUAGE="JavaScript" SRC="CalendarPopup.js"></SCRIPT>
        <SCRIPT LANGUAGE="JavaScript" ID="js13">var cal13 = new CalendarPopup();</SCRIPT>
        
        <form action="tedit.php" enctype="multipart/form-data" method="post"  >
            <input type="hidden" name="Action" />
            <table width="100%" border="0" cellspacing="2" cellpadding="2" align="center" bgcolor="#FFFFff"  >
                <tr>
                    <th width="16%">&nbsp;</th>
                    <th width="58%" align="right">
                        <b>
                            <font color="#4b1807" style="font-size:16px">
                                <font color="#FF0000" style=" font-size:18px">تعديل</font>
                            </font>
                        </b>
                    </th>
                    
                    <th width="26%" rowspan="8" align="center" valign="top">
                        <img src="QrCode/102403.png" border="0" width="100" height="100"   align="absmiddle"/>  <br />
                        <?php 
                            $id = $_GET['id'];
                            $query = "SELECT * FROM tasks WHERE id='$id'";
                            $result = mysqli_query($conn, $query);
                            $row = mysqli_fetch_array($result);
                            if(isset($row['file_no']) && $row['file_no'] !== ''){
                                $fid = $row['file_no'];
                                $queryf = "SELECT * FROM file WHERE file_id='$fid'";
                                $resultf = mysqli_query($conn, $queryf);
                                $rowf = mysqli_fetch_array($resultf);
                                
                                $cid = $rowf['file_client'];
                                $queryc2 = "SELECT * FROM client WHERE id='$cid'";
                                $resultc2 = mysqli_query($conn, $queryc2);
                                $rowc2 = mysqli_fetch_array($resultc2);
                                
                                $clientname = $rowc2['arname'];
                                echo "الموكل : $clientname";
                            } else{
                                echo '-';
                            }
                        ?>
                        <font color=blue><?php if(isset($rowf['fclient_characteristic']) && $rowf['fclient_characteristic'] !== ''){ echo ' / ' . $rowf['fclient_characteristic'];}?></font> <br />
                        الموضوع : <?php if(isset($rowf['file_subject']) && $rowf['file_subject'] !== ''){ echo $rowf['file_subject'];}?>
                    </th>
                </tr>
                
                <input type="hidden" name="tid" value="<?php echo $id;?>">
                
                <tr>
                    <th align="left">رقم الملف :</th>
                    <th align="right"  dir="ltr" style=" font-size:18px; color:#00F">
                        <font color="#FF0000">
                            <?php
                                if(isset($rowf['frelated_place']) && $rowf['frelated_place'] !== ''){
                                    if($rowf['frelated_place'] === 'الشارقة'){
                                        echo 'SHJ';
                                    } else if($rowf['frelated_place'] === 'دبي'){
                                        echo 'DXB';
                                    } else if($rowf['frelated_place'] === 'عجمان'){
                                        echo 'AJM';
                                    }
                                }
                            ?>
                        </font>
                        <font color="#0000FF">
                            <?php 
                                if(isset($row['file_no']) && $row['file_no'] !== ''){ 
                                    echo $row['file_no'];
                                }
                            ?>
                        </font>
                    </th>
                </tr>
                
                <tr valign="middle">
                    <th align="left">الموظف المكلف :</th>
                    <th align="right">
                        <?php if(isset($row['employee_id'])){ $select_emp = $row['employee_id']; } else{ $select_emp = ''; }?>
                        <select name="re_name" dir="rtl" style="width:50%;">
                            <?php
                                $queryemps = "SELECT * FROM user";
                                $resultemps = mysqli_query($conn, $queryemps);
                                if($resultemps->num_rows > 0){
                                    while($rowemps = mysqli_fetch_array($resultemps)){
                            ?>
                            <option value="<?php echo $rowemps['id'];?>" <?php $idd = $rowemps['id']; echo ($select_emp == "$idd") ? 'selected' : ''; ?>><?php echo $rowemps['name'];?></option>
                            <?php
                                    }
                                }
                            ?>
                        </select>	<font color="#FF0000">*</font>	
                    </th>
                </tr>
                
                <tr valign="top">
                    <th align="left">نوع العمل :</th>
                    <th align="right"> 
                        <select name="type_name" dir="rtl" style="width:50%;">
                            <?php
                                $query_job = "SELECT * FROM job_name";
                                $result_job = mysqli_query($conn, $query_job);
                                if(isset($row['task_type']) && $row['task_type'] !== ''){$selectedTn = $row['task_type'];}
                                if($result_job->num_rows > 0){
                                    while($row_job = mysqli_fetch_array($result_job)){
                            ?>
                            <option value="<?php echo $row_job['id'];?>" <?php if($row_job['id'] === $selectedTn){ echo 'selected'; }?>><?php echo $row_job['job_name'];?></option>
                            <?php
                                    }
                                } else{
                            ?>
                            <option value=""></option>
                            <?php
                                }
                            ?>
                        </select> <font color="#FF0000">*</font> 
                        <img src="images/addmore.jpg" align="absmiddle"  title="اضافة" onclick="MM_openBrWindow('selector/addJob.php','','toolbar=yes,menubar=yes,scrollbars=yes,resizable=yes,width=400,height=400')"   style="cursor:pointer"/>
                    </th>
                </tr>
                
                <tr valign="middle">
                    <th align="left">درجة التقاضي : </th>
                    <th align="right">
                        <?php if(isset($row['degree']) && $row['degree'] !== ''){$selectedDeg = $row['degree'];}?>
                        <select name="degree_id" dir="rtl" style="width:50%;">
                            <option value=""></option>
                            <?php
                                if(isset($row['degree']) && $row['degree'] !== ''){$selectedTn = $row['degree'];}
                                $fid = $row['file_no'];
                                $queryff = "SELECT * FROM file_degrees WHERE fid='$fid'";
                                $resultff = mysqli_query($conn, $queryff);
                                if($resultff->num_rows > 0){
                                    while($rowff = mysqli_fetch_array($resultff)){
                            ?>
                            <option value="<?php echo $rowff['id'];?>" <?php if($rowff['id'] === $row['degree']){ echo 'selected'; }?>><?php echo $rowff['case_num'].'/'.$rowff['file_year'].'-'.$rowff['degree'];?></option>
                            <?php }}?>
                        </select>
                    </th>
                </tr>
                
                <tr>
                    <th align="left">الاهمية :</th>
                    <td align="right" dir="rtl" style="font-size:16px">
                        <input type="radio"  name="busi_priority" value="0" style="border:none" <?php if(isset($row['priority']) && $row['priority'] === '0'){ echo 'checked';}?>/><font color="#009900">عادي</font>
                        <input type="radio"  name="busi_priority"  value="1" style="border:none" <?php if(isset($row['priority']) && $row['priority'] === '1'){ echo 'checked';}?>/><font color="#FF0000">عاجل</font>
                    </td>
                </tr>
                
                <tr>
                    <th align="left">تاريخ تنفيذ العمل  :</th>
                    <td align="right"> 
                        <input type="date" name="busi_date" dir="rtl" size="10" value="<?php if(isset($row['duedate']) && $row['duedate'] !== ''){ echo $row['duedate'];}?>" style="text-align:center; font-weight:bold; color:#F00" >
                    </td>
                </tr>
                    
                <tr valign="top">
                    <th align="left">التفاصيل:</th>
                    <th align="right">
                        <textarea dir="rtl" wrap="physical" rows="3" style="width:98%" name="busi_notes"><?php if(isset($row['details']) && $row['details'] !== ''){ echo $row['details'];}?></textarea>
                    </th>
                </tr>
                
                <tr>
                    <th>&nbsp;</th>
                    <th align="right">
                        <?php if($row_permcheck['admjobs_eperm'] === '1'){?>
                        <input type="submit"  class="button" value=" تعديل + حفظ البيانات" />
                        <?php 
                            } else{
                                echo '<font style="color:red;">ليس لديك الصلاحية لتعديل الاعمال الادارية</font>';
                            }
                        ?>
                        <input type="button"  value="مسح الحقول"  class="button" onclick="location.href='EditTask.php?id=<?php if(isset($_GET['id']) && $_GET['id'] !== ''){ echo $_GET['id'];}?>'"/>
                    </th>
                </tr>
            </table> <br />
            
            <table  style="font-size:16px" width="100%" border="1" cellspacing="0" cellpadding="0" align="center"  bordercolor="#c0b89c"  bgcolor="#FFFFFF" >
                <tr  bgcolor="#96693e"  style=" color:#FFF" height="40">
                    <th width="14%" align="center">درجة التقاضي</th>
                    <th width="20%" align="center">نوع العمل الإداري</th>
                    <th width="9%" align="center">الموظف المكلف</th>
                    <th width="6%" align="center">ت.التكليف </th>
                    <th width="31%" align="center">التفاصيل</th>
                    <th width="6%" align="center">م.ت/ الإدخال</th>
                    <th width="4%" align="center">طباعة</th>
                    <th width="5%" align="center">تعديل</th>
                    <th width="5%" align="center">حذف</th>
                </tr>
                
                <?php 
                    if(isset($row['file_no']) && $row['file_no'] !== ''){ 
                        $fid = $row['file_no']; 
                        $queryempp = "SELECT * FROM tasks WHERE file_no='$fid'";
                        $resultempp = mysqli_query($conn, $queryempp);
                        if($resultempp->num_rows > 0){
                            while($rowempp = mysqli_fetch_array($resultempp)){
                ?>
                
                <tr valign="top"  style=" background-color:<?php if(isset($rowempp['task_status']) && $rowempp['task_status'] === '0'){ echo '#FDD0D0';} else if(isset($rowempp['task_status']) && $rowempp['task_status'] === '1'){echo '#FFFF00';} else if(isset($rowempp['task_status']) && $rowempp['task_status'] === '2'){echo '#B7F9A6';}?>">
                    <td align="center" >
                        <br />
                        <?php 
                            if(isset($rowempp['degree']) && $rowempp['degree'] !== ''){ 
                                $degid = $rowempp['degree'];
                                $queryde = "SELECT * FROM file_degrees WHERE id='$degid'";
                                $resultde = mysqli_query($conn, $queryde);
                                $rowde = mysqli_fetch_array($resultde);
                                echo $rowde['case_num'].'/'.$rowde['file_year'].'-'.$rowde['degree'];
                            }
                        ?>
                    </td>
                    <th align="center" >
                        <?php 
                            if(isset($rowempp['task_type']) && $rowempp['task_type'] !== ''){ 
                                $ttype = $rowempp['task_type'];
                                $querytt = "SELECT * FROM job_name WHERE id='$ttype'";
                                $resulttt = mysqli_query($conn, $querytt);
                                $rowtt = mysqli_fetch_array($resulttt);
                                echo $rowtt['job_name'];
                            }
                        ?>
                    </th>
                    <th align="center" >
                        <?php 
                            if(isset($rowempp['employee_id']) && $rowempp['employee_id'] !== ''){ 
                                $empid = $rowempp['employee_id'];
                                $queryemp = "SELECT * FROM user WHERE id='$empid'";
                                $resultemp = mysqli_query($conn, $queryemp);
                                $rowemp = mysqli_fetch_array($resultemp);
                                echo $rowemp['name'];
                            }
                        ?> <br />
                        <?php 
                            if(isset($rowempp['priority']) && $rowempp['priority'] !== ''){ 
                                if($rowempp['priority'] === '0'){
                                    echo 'عادي';
                                } else if($rowempp['priority'] === '1'){
                        ?>
                        <img src="images/urgent2-300x220.jpg" align="absmiddle" border="0">
                        <?php
                                }
                            }
                        ?>
                    </th>
                    <th align="center" style="color:#F00"><?php if(isset($rowempp['duedate']) && $rowempp['duedate'] !== ''){ $timestampp = $rowempp['duedate']; list($DD, $TT) = explode(' ', $timestampp); echo $DD;}?></th>
                    <td align="center"><?php if(isset($rowempp['details']) && $rowempp['details'] !== ''){ echo $rowempp['details'];}?></td>
                    <td align="center" style=" color:#999" title="<?php if(isset($row['timestamp']) && $row['timestamp'] !== ''){ echo $row['timestamp'];}?>">
                        <?php 
                            if(isset($rowempp['responsible']) && $rowempp['responsible'] !== ''){ 
                                $respid = $rowempp['responsible'];
                                $queryresp = "SELECT * FROM user WHERE id='$respid'";
                                $resultresp = mysqli_query($conn, $queryresp);
                                $rowresp = mysqli_fetch_array($resultresp);
                                echo $rowresp['name'];
                            }
                        ?>
                    </td>
                    <td align="center" style="padding:2px;cursor:pointer" ></td>
                    <td align="center">
                        <?php if($row_permcheck['admjobs_eperm'] === '1'){?>
                        <a href="EditTask.php?id=<?php if(isset($rowempp['id']) && $rowempp['id'] !== ''){ echo $rowempp['id'];}?>">
                            <img src="images/EditB.png" border="0" title="تعديل"/>
                        </a>
                        <?php }?>
                    </td>
                    <td align="center" <?php if($row_permcheck['admjobs_dperm'] === '1'){?> onclick="location.href='tdel.php?tid=<?php echo $rowempp['id'];?>&fid=<?php echo $rowempp['file_no'];?>&page=EditTask';" style="cursor: pointer;" <?php }?>>
                        <?php if($row_permcheck['admjobs_dperm'] === '1'){?> <img src="images/delete.png" align="absmiddle" border="0"/> <?php }?>
                    </td>
                </tr>
                
                <?php }}}?>
            </table>
        </form>
    </body>
</html>