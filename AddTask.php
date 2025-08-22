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
        
        if($row_permcheck['admjobs_aperm'] === '1'){
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
                <table width="99%" border="0" cellspacing="1" cellpadding="1" dir="rtl">
                  <tr valign="top" bgcolor="#FFFFFF">
                    <th align="right" colspan="2" dir="rtl" class="table2 red" >
                      <a href="index.php" class="Main">
                        <img src="images/homepage.png"   align="absmiddle" border="0"/> الصفحة الرئيسية
                      </a> &raquo; اضافة اعمال إدارية
                    </th>
                  </tr>
                                
                  <tr>
                    <th>
                      <form action="task_process.php" enctype="multipart/form-data" method="post" >
                        <table width="100%" border="0" cellspacing="2" cellpadding="2" align="center" class="table">
                          <tr>
                            <th colspan="2" align="center">
                              <font style="font-size:18px">
                                <input type="radio" name="SearchType" value="1" onchange="submit()" <?php if(isset($_GET['section']) && $_GET['section'] === '1'){echo 'checked';}?>/> <font color="#0000FF">رقم الملف</font>
                                <input type="radio" name="SearchType" value="2" onchange="submit()" <?php if(isset($_GET['section']) && $_GET['section'] === '2'){echo 'checked';}?>/> اسم<font color="#009900"> الموكل</font>/<font color="#FF0000"> الخصم </font>
                                <input type="radio" name="SearchType" value="3" onchange="submit()" <?php if(isset($_GET['section']) && $_GET['section'] === '3'){echo 'checked';}?>/> رقم القضية
                                &nbsp; &nbsp; &nbsp; &nbsp;
                                <input type="radio" value="4" name="SearchType" style="border:none" onclick="submit();" <?php if(isset($_GET['section']) && $_GET['section'] === '4'){echo 'checked';}?>/>  
                                <font  style="background-color:#FF9; color:#F00; font-size:18px">
                                  اضافة عمل إدارى بدون رقم ملف
                                </font>
                              </font>
                            </th>
                          </tr>
   
                          <?php if(isset($_GET['section']) && $_GET['section'] === '1'){?>
                          <tr>
                            <th width="18%"  align="left">رقم الملف :</th>
                            <th width="82%"  align="right" dir="rtl">
                              <input type="text" name="file_id" style="font-size:16px; text-align:center; width:20%; color: #00F"  value="<?php if(isset($_GET['fno']) && $_GET['fno'] !== ''){echo $_GET['fno'];}?>" onChange="submit()">
                            </th>
                          </tr>

                          <?php } elseif(isset($_GET['section']) && $_GET['section'] === '2'){?>
                          <tr>
                            <th align="left">الموكل/الخصم :</th>
                            <th align="right" dir="rtl">
                              <input type="radio"  name="Ckind" value="1"  onchange="submit()" /> موكل
                              <input type="radio" name="Ckind" value="2" onchange="submit()" /> خصم
                              <input type="text"  name="SearchByClient" value="<?php if(isset($_GET['cn']) && $_GET['cn'] !== ''){echo $_GET['cn'];}?>" dir="rtl" style="text-align:center; width:30%"/>
                            </th>
                        </tr>
                          <?php } elseif(isset($_GET['section']) && $_GET['section'] === '3'){?>
                            <tr>
                              <th align="left">رقم القضية :</th>
                              <th align="right" dir="rtl">
                              <input type="text"  name="case_no" value="" dir="rtl" style="text-align:center; width:5%"/> / <input type="text"  name="case_no_year" value="" dir="rtl" style="text-align:center; width:10%"/>
                              
                              <input type="image"  src="images/1392507723_search.png" align="absmiddle" onClick="submit()"  style="border:none">
                              </th>
                            </tr>
                          <?php }
                            if((isset($_GET['fno']) && $_GET['fno'] !== '') || (isset($_GET['cn']) && $_GET['cn'] !== '') || (isset($_GET['cno']) && $_GET['cno'] !== '' && isset($_GET['cy']) && $_GET['cy'] !== '')){
                          ?>
                          <tr>
                            <th align="right" dir="rtl" colspan="2">
                              <?php
                                if(isset($_GET['section']) && $_GET['section'] !== '4'){
                              ?>
                              <table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="#DFC6BD">
                                <tr align="center" class="header_table">
                                  <td width="14%">رقم الملف</td>
                                  <td width="24%">الموضوع</td>
                                  <td width="11%">رقم القضية</td>
                                  <td width="25%">الموكل</td>
                                  <td width="26%">الخصم</td>
                                </tr>

                                <?php
                                  if(isset($_GET['fno'])){
                                    $fid = $_GET['fno'];
                                    $query = "SELECT * FROM file WHERE file_id='$fid'";
                                    $result = mysqli_query($conn, $query);
                                  } else if(isset($_GET['cn']) && isset($_GET['ck'])){
                                    $cn = $_GET['cn'];
                                    $ck = $_GET['ck'];
                                    
                                    $queryc = "SELECT * FROM client WHERE arname LIKE '%$cn%'";
                                    $resultc = mysqli_query($conn, $queryc);
                                    if($resultc->num_rows > 0){
                                        $rowc = mysqli_fetch_array($resultc);
                                        $cn = $rowc['id'];
                                    }

                                    if($_GET['ck'] === '1'){
                                      $query = "SELECT * FROM file WHERE file_client='$cn' OR file_client2='$cn' OR file_client3='$cn' OR file_client4='$cn' OR file_client5='$cn'";
                                      $result = mysqli_query($conn, $query);
                                    } else{
                                      $query = "SELECT * FROM file WHERE file_opponent='$cn' OR file_opponent2='$cn' OR file_opponent3='$cn' OR file_opponent4='$cn' OR file_opponent5='$cn'";
                                      $result = mysqli_query($conn, $query);
                                    }
                                  } else if(isset($_GET['cno']) && $_GET['cno'] !== '' && isset($_GET['cy']) &&$_GET['cy'] !== ''){
                                    $cno = $_GET['cno'];
                                    $cy = $_GET['cy'];
                                    $query1 = "SELECT * FROM file_degrees WHERE case_num='$cno' AND file_year='$cy'";
                                    $result1 = mysqli_query($conn, $query1);
                                    $row1 = mysqli_fetch_array($result1);
                                    $fid = $row1['fid'];
                                    $query = "SELECT * FROM file WHERE file_id = '$fid'";
                                    $result = mysqli_query($conn, $query);
                                  }

                                  if(isset($_GET['section']) && $_GET['section'] !== '4' && $result->num_rows == 0){
                                    exit();
                                  }

                                  if(($result->num_rows > 0)){
                                    while($row = mysqli_fetch_array($result)){
                                ?>
                              
                                <tr align="center" style="font-weight:bold;cursor:pointer; background-color:#FFF" onclick="location.href='AddTask.php?section=<?php if(isset($_GET['section']) && $_GET['section'] !== ''){ echo '1';} if(isset($row['file_id']) && $row['file_id'] !== ''){echo '&fno=' . $row['file_id'];}?>&agree=1';">
                                  <td>
                                    <font color="#FF0000">
                                      <?php
                                        if(isset($row['frelated_place']) && $row['frelated_place'] !== ''){
                                          $place = $row['frelated_place'];
                                          if($place === 'عجمان'){
                                            echo 'AJM';
                                          }
                                          else if($place === 'دبي'){
                                            echo 'DXB';
                                          }
                                          else if($place === 'الشارقة'){
                                            echo 'SHJ';
                                          }
                                        }
                                      ?>
                                    </font>
                                    <?php echo $row['file_id'];?>    
                                  </td>
                                  <td><?php if(isset($row['file_subject']) && $row['file_subject'] !== ''){ echo $row['file_subject']; }?></td>
                                  <td>
                                    <font color=blue>
                                      <?php 
                                        $fid = $row['file_id'];
                                        $queryfid = "SELECT * FROM file_degrees WHERE fid='$fid' ORDER BY created_at DESC";
                                        $resultfid = mysqli_query($conn, $queryfid);
                                        $rowfid = mysqli_fetch_array($resultfid);
                                        if(isset($rowfid['degree']) && $rowfid['degree'] !== ''){ 
                                          echo $rowfid['degree'];
                                        }
                                        echo '<br>'; 
                                        if(isset($rowfid['case_num']) && $rowfid['case_num'] !== ''){ 
                                          echo '-'.$rowfid['case_num']; 
                                        }
                                        if(isset($rowfid['file_year']) && $rowfid['file_year'] !== ''){ 
                                          echo '/'.$rowfid['file_year']; 
                                        }
                                      ?>
                                    </font>	
                                  </td>
                                  <td> 
                                    <?php 
                                        if(isset($row['file_client']) && $row['file_client'] !== ''){ 
                                            $cid1 = $row['file_client'];
                                            $queryc1 = "SELECT * FROM client WHERE id='$cid1'";
                                            $resultc1 = mysqli_query($conn, $queryc1);
                                            $rowc1 = mysqli_fetch_array($resultc1);
                                            echo $rowc1['arname'];
                                    ?> 
                                    <font color=blue>
                                      <?php 
                                        if(isset($row['fclient_characteristic']) && $row['fclient_characteristic'] !== ''){ 
                                            echo ' / ' . $row['fclient_characteristic'] . '<br>'; 
                                        }}
                                      ?>
                                    </font>
                                    
                                    <?php 
                                        if(isset($row['file_client2']) && $row['file_client2'] !== ''){ 
                                            $cid2 = $row['file_client2'];
                                            $queryc2 = "SELECT * FROM client WHERE id='$cid2'";
                                            $resultc2 = mysqli_query($conn, $queryc2);
                                            $rowc2 = mysqli_fetch_array($resultc2);
                                            echo $rowc2['arname'];
                                    ?> 
                                    <font color=blue>
                                      <?php 
                                        if(isset($row['fclient_characteristic2']) && $row['fclient_characteristic2'] !== ''){ 
                                            echo ' / ' . $row['fclient_characteristic2'] . '<br>'; 
                                        }}
                                      ?>
                                    </font>
                                    
                                    <?php 
                                        if(isset($row['file_client3']) && $row['file_client3'] !== ''){ 
                                            $cid3 = $row['file_client3'];
                                            $queryc3 = "SELECT * FROM client WHERE id='$cid3'";
                                            $resultc3 = mysqli_query($conn, $queryc3);
                                            $rowc3 = mysqli_fetch_array($resultc3);
                                            echo $rowc3['arname'];
                                    ?> 
                                    <font color=blue>
                                      <?php 
                                        if(isset($row['fclient_characteristic3']) && $row['fclient_characteristic3'] !== ''){ 
                                            echo ' / ' . $row['fclient_characteristic3'] . '<br>'; 
                                        }}
                                      ?>
                                    </font>
                                    
                                    <?php 
                                        if(isset($row['file_client4']) && $row['file_client4'] !== ''){ 
                                            $cid4 = $row['file_client4'];
                                            $queryc4 = "SELECT * FROM client WHERE id='$cid4'";
                                            $resultc4 = mysqli_query($conn, $queryc4);
                                            $rowc4 = mysqli_fetch_array($resultc4);
                                            echo $rowc4['arname'];
                                    ?> 
                                    <font color=blue>
                                      <?php 
                                        if(isset($row['fclient_characteristic4']) && $row['fclient_characteristic4'] !== ''){ 
                                            echo ' / ' . $row['fclient_characteristic4'] . '<br>'; 
                                        }}
                                      ?>
                                    </font>
                                    
                                    <?php 
                                        if(isset($row['file_client5']) && $row['file_client5'] !== ''){ 
                                            $cid5 = $row['file_client5'];
                                            $queryc5 = "SELECT * FROM client WHERE id='$cid5'";
                                            $resultc5 = mysqli_query($conn, $queryc5);
                                            $rowc5 = mysqli_fetch_array($resultc5);
                                            echo $rowc5['arname'];
                                    ?> 
                                    <font color=blue>
                                      <?php 
                                        if(isset($row['fclient_characteristic5']) && $row['fclient_characteristic5'] !== ''){ 
                                            echo ' / ' . $row['fclient_characteristic5'] . '<br>'; 
                                        }}
                                      ?>
                                    </font>
                                  </td>
                                
                                  <td> 
                                    <?php 
                                        if(isset($row['file_opponent']) && $row['file_opponent'] !== ''){ 
                                            $oid = $row['file_opponent'];
                                            $queryo = "SELECT * FROM client WHERE id='$oid'";
                                            $resulto = mysqli_query($conn, $queryo);
                                            $rowo = mysqli_fetch_array($resulto);
                                            echo $rowo['arname'];
                                    ?>
                                    <font color=blue>
                                      <?php 
                                        if(isset($row['fopponent_characteristic']) && $row['fopponent_characteristic'] !== ''){ 
                                            echo ' / ' . $row['fopponent_characteristic'] . '<br>'; 
                                        }}
                                      ?>
                                    </font>
                                    
                                    <?php 
                                        if(isset($row['file_opponent2']) && $row['file_opponent2'] !== ''){ 
                                            $oid2 = $row['file_opponent2'];
                                            $queryo2 = "SELECT * FROM client WHERE id='$oid2'";
                                            $resulto2 = mysqli_query($conn, $queryo2);
                                            $rowo2 = mysqli_fetch_array($resulto2);
                                            echo $rowo2['arname'];
                                    ?>
                                    <font color=blue>
                                      <?php 
                                        if(isset($row['fopponent_characteristic2']) && $row['fopponent_characteristic2'] !== ''){ 
                                            echo ' / ' . $row['fopponent_characteristic2'] . '<br>'; 
                                        }}
                                      ?>
                                    </font>
                                    
                                    <?php 
                                        if(isset($row['file_opponent3']) && $row['file_opponent3'] !== ''){ 
                                            $oid3 = $row['file_opponent3'];
                                            $queryo3 = "SELECT * FROM client WHERE id='$oid3'";
                                            $resulto3 = mysqli_query($conn, $queryo3);
                                            $rowo3 = mysqli_fetch_array($resulto3);
                                            echo $rowo3['arname'];
                                    ?>
                                    <font color=blue>
                                      <?php 
                                        if(isset($row['fopponent_characteristic3']) && $row['fopponent_characteristic3'] !== ''){ 
                                            echo ' / ' . $row['fopponent_characteristic3'] . '<br>'; 
                                        }}
                                      ?>
                                    </font>
                                    
                                    <?php 
                                        if(isset($row['file_opponent4']) && $row['file_opponent4'] !== ''){ 
                                            $oid4 = $row['file_opponent4'];
                                            $queryo4 = "SELECT * FROM client WHERE id='$oid4'";
                                            $resulto4 = mysqli_query($conn, $queryo4);
                                            $rowo4 = mysqli_fetch_array($resulto4);
                                            echo $rowo4['arname'];
                                    ?>
                                    <font color=blue>
                                      <?php 
                                        if(isset($row['fopponent_characteristic4']) && $row['fopponent_characteristic4'] !== ''){ 
                                            echo ' / ' . $row['fopponent_characteristic4'] . '<br>'; 
                                        }}
                                      ?>
                                    </font>
                                    
                                    <?php 
                                        if(isset($row['file_opponent5']) && $row['file_opponent5'] !== ''){ 
                                            $oid5 = $row['file_opponent5'];
                                            $queryo5 = "SELECT * FROM client WHERE id='$oid5'";
                                            $resulto5 = mysqli_query($conn, $queryo5);
                                            $rowo5 = mysqli_fetch_array($resulto5);
                                            echo $rowo5['arname'];
                                    ?>
                                    <font color=blue>
                                      <?php 
                                        if(isset($row['fopponent_characteristic5']) && $row['fopponent_characteristic5'] !== ''){ 
                                            echo ' / ' . $row['fopponent_characteristic5'] . '<br>'; 
                                        }}
                                      ?>
                                    </font>
                                </tr>
                                <?php }?>
                              </table>
                              <?php }?>
                            </th>
                          </tr>
                          <?php 
                              }
                            }
                            if(isset($_GET['agree']) && $_GET['agree'] === '1'){
                              echo '<input type="hidden" name="agree" value="1">';
                              if(isset($_GET['fno']) && $_GET['fno'] !== ''){
                                $fid = $_GET['fno'];
                                $query1 = "SELECT * FROM file WHERE file_id='$fid'";
                                $result1 = mysqli_query($conn, $query1);
                                $row1 = mysqli_fetch_array($result1);
                              }
                          ?>
                          <?php if(isset($_GET['section']) && $_GET['section'] !== '4'){?>
                          <tr>
                            <th align="left">الفرع :</th>
                            <th align="right" style=" font-size:18px; color:#00F">
                              <font color="#FF0000">
                                <?php
                                  if(isset($row1['frelated_place']) && $row1['frelated_place'] !== ''){
                                    $place = $row1['frelated_place'];
                                    if($place === 'عجمان'){
                                      echo 'AJM';
                                    }
                                    else if($place === 'دبي'){
                                      echo 'DXB';
                                    }
                                    else if($place === 'الشارقة'){
                                      echo 'SHJ';
                                    }
                                  }
                                ?>
                              </font>
                            </th>
                          </tr>
                            
                          <tr>
                            <th align="left">نوع القضية : </th>
                            <td align="right"><?php if(isset($row1['fcase_type']) && $row1['fcase_type'] !== ''){ echo $row1['fcase_type'];}?></td>
                          </tr>
                          <?php }?>
                            
                          <tr valign="middle">
                            <th align="left">الموظف المكلف:</th>
                            <th align="right">
                              <?php if(isset($_GET['pi']) && $_GET['pi'] !== ''){ $selected_position = $_GET['pi']; } else{ $selected_position = ''; }?>
                              <select name="position_id" dir="rtl" onchange="submit()" >
                                <option value=""></option>
                                <?php
                                  $query_positions = "SELECT * FROM positions";
                                  $result_positions = mysqli_query($conn, $query_positions);
                                  if($result_positions->num_rows > 0){
                                    while($row_positions=mysqli_fetch_array($result_positions)){
                                ?>
                                <option value="<?php echo $row_positions['id'];?>"<?php if($row_positions['id'] === $selected_position){ echo 'selected'; } ?>><?php echo $row_positions['position_name'];?></option>
                                <?php
                                    }
                                  }
                                ?>
                              </select>

                              <select name="re_name" dir="rtl" >
                                <?php 
                                  if(isset($_GET['pi']) && $_GET['pi'] !== ''){ 
                                    $pnid = $_GET['pi'];
                                    
                                    if(isset($_GET['rn']) && $_GET['rn'] !== ''){ $selected_emp = $_GET['rn']; } else{ $selected_emp = ''; }
                                    $queryemps = "SELECT * FROM user WHERE job_title = '$pnid'";
                                    $resultemps = mysqli_query($conn, $queryemps);
                                    if($resultemps->num_rows > 0){
                                      while($rowemps = mysqli_fetch_array($resultemps)){
                                ?>
                                <option value="<?php echo $rowemps['id'];?>" <?php if($rowemps['id'] === $selected_emp){ echo 'selected'; }?>><?php echo $rowemps['name'];?></option>
                                <?php
                                      }
                                    }
                                  }else{
                                ?>
                                <option value="" ></option>
                                <?php }?>
                              </select> <font color="#FF0000">*</font>
                              <?php if(isset($_GET['error']) && $_GET['error'] === '1'){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى اختيار الموظف</span><?php }?>
                            </th>
                          </tr>
                          
                          <tr valign="top">
                            <th align="left">نوع العمل :</th>
                            <th align="right"> 
                              <select name="type_name" dir="rtl" style="width:50%;">
                                <option value=""></option>
                                <?php
                                  $query_job = "SELECT * FROM job_name";
                                  $result_job = mysqli_query($conn, $query_job);
                                  if(isset($_GET['tn']) && $_GET['tn'] !== ''){$selectedTn = $_GET['tn'];} else {$selectedTn = '';}
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
                              <?php if(isset($_GET['error']) && $_GET['error'] === '2'){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى اختيار نوع العمل</span><?php }?>
                              <?php if($row_permcheck['admjobtypes_rperm'] === '1'){?> <img src="images/addmore.jpg" align="absmiddle"  title="اضافة" onclick="MM_openBrWindow('selector/addJob.php','','toolbar=yes,menubar=yes,scrollbars=yes,resizable=yes,width=400,height=400')"   style="cursor:pointer"/> <?php }?>
                            </th>
                          </tr>

                          <?php if(isset($_GET['section']) && $_GET['section'] !== '4'){?>
                          <tr valign="middle">
                            <th align="left">درجة التقاضي : </th>
                            <th align="right">
                              <?php if(isset($_GET['di']) && $_GET['di'] !== ''){$selectedDeg = $_GET['di'];} else{$selectedDeg = '';}?>
                              <select name="degree_id" dir="rtl" style="width:50%;">
                                <?php
                                    $fid = $_GET['fno'];
                                    $querydegs = "SELECT * FROM file_degrees WHERE fid='$fid' ORDER BY created_at DESC";
                                    $resultdegs = mysqli_query($conn, $querydegs);
                                    if($resultdegs->num_rows > 0){
                                        while($rowdegs = mysqli_fetch_array($resultdegs)){
                                ?>
                                <option value="<?php echo $rowdegs['id'];?>" <?php if($rowdegs['id'] === $selectedDeg){ echo 'selected'; }?>><?php echo $rowdegs['case_num'].'/'.$rowdegs['file_year'].'-'.$rowdegs['degree'];?></option>
                                <?php }}?>
                              </select>
                            </th>
                          </tr>
                          <?php }?>
                                
                          <tr>
                            <th align="left">الاهمية :</th>
                            <td align="right" dir="rtl" style="font-size:16px">
                              <input type="radio"  name="busi_priority" value="0" <?php if(isset($_GET['bp']) && $_GET['bp'] === '0'){ echo 'checked'; } if(!isset($_GET['bp'])){ echo 'checked'; }?>/><font color="#009900">عادي</font>
                              <input type="radio"  name="busi_priority"  value="1" <?php if(isset($_GET['bp']) && $_GET['bp'] === '1'){ echo 'checked'; }?>/><font color="#FF0000">عاجل</font>
                            </td>
                          </tr>

                          <tr>
                            <th align="left">تاريخ تنفيذ العمل  : </th>
                            <td align="right" dir="rtl"> 
                              <input type="date" name="busi_date" dir="rtl" size="10" value="<?php if(isset($_GET['bd']) && $_GET['bd'] !== ''){ echo $_GET['bd'];}?>" style="text-align:center; font-weight:bold; color:#F00" >
                            </td>
                          </tr>
                            
                          <tr valign="top">
                            <th align="left">التفاصيل : </th>
                            <th align="right">
                              <textarea dir="rtl" wrap="physical" rows="2" style="width:98%" name="busi_notes"><?php if(isset($_GET['bn']) && $_GET['bn'] !== ''){echo $_GET['bn'];}?></textarea>
                            </th>
                          </tr>
                            
                          <tr>
                            <th>&nbsp;</th>
                            <th align="right">
                              <input type="submit" value=" حفظ وتخزين العمل الإدارى" class="button" name="save_task_fid"/>
                            </th>
                          </tr>
                          <?php
                            }
                          ?>
                        </table>
                      </form>
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

<script language="JavaScript" type="text/JavaScript">
    function MM_openBrWindow(theURL,winName,features) { //v2.0
        window.open(theURL,winName,features);
    }
</script>