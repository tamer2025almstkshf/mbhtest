<?php
    include_once 'connection.php';
    include_once 'login_check.php';
?>
<!DOCTYPE html>
<html dir="rtl">
    <head>
        <title>محمد بني هاشم للمحاماة و الاستشارات القانونية</title>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <meta name="google-site-verification" content="_xmqQ0kTuDS9ta1v4E4je5rweWQ4qtH1l8_cnWro7Tk" />
        <meta name="robots" content="noindex, nofollow">
        <meta name="googlebot" content="noindex">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="shortcut icon" href="files/images/instance/favicon.ico?v=35265" type="image/icon">
        <link href="css/styles.css" rel="stylesheet">
        <link rel="SHORTCUT ICON" href="img/favicon.ico">
    </head>
    <body style="overflow: auto; padding-bottom: 50px;">
        <?php
            $id = $_SESSION['id'];
            $querymain = "SELECT * FROM user WHERE id='$id'";
            $resultmain = mysqli_query($conn, $querymain);
            $rowmain = mysqli_fetch_array($resultmain);
            
            $myid = $_SESSION['id'];
            $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
            $result_permcheck = mysqli_query($conn, $query_permcheck);
            $row_permcheck = mysqli_fetch_array($result_permcheck);
            
            include_once 'AES256.php';
        ?>
        <script src="js/newWindow.js"></script>
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['cfiles_rperm'] === '1'){
                        if(!isset($_GET['kind']) && !isset($_GET['key'])){
                            exit();
                        }
                        
                        $Ckind = $_GET['kind'];
                        $key = $_GET['key'];
                        
                        $query_att = "SELECT * FROM file WHERE (SUBSTRING_INDEX(file_upload1, '/', -1) LIKE '%$key%' AND file_upload1 != '') OR (SUBSTRING_INDEX(file_upload2, '/', -1) LIKE '%$key%' AND file_upload2 != '') OR
                        (SUBSTRING_INDEX(file_upload3, '/', -1) LIKE '%$key%' AND file_upload3 != '') OR (SUBSTRING_INDEX(file_upload4, '/', -1) LIKE '%$key%' AND file_upload4 != '') OR (SUBSTRING_INDEX(file_upload5, '/', -1) LIKE '%$key%' AND file_upload5 != '')
                        OR (SUBSTRING_INDEX(file_upload6, '/', -1) LIKE '%$key%' AND file_upload6 != '')";
                        $result_att = mysqli_query($conn, $query_att);
                        
                        $queryattno = "SELECT * FROM file WHERE (SUBSTRING_INDEX(file_upload1, '/', -1) LIKE '%$key%' AND file_upload1 != '') OR (SUBSTRING_INDEX(file_upload2, '/', -1) LIKE '%$key%' AND file_upload2 != '') OR
                        (SUBSTRING_INDEX(file_upload3, '/', -1) LIKE '%$key%' AND file_upload3 != '') OR (SUBSTRING_INDEX(file_upload4, '/', -1) LIKE '%$key%' AND file_upload4 != '') OR (SUBSTRING_INDEX(file_upload5, '/', -1) LIKE '%$key%' AND file_upload5 != '')
                        OR (SUBSTRING_INDEX(file_upload6, '/', -1) LIKE '%$key%' AND file_upload6 != '')";
                        $resultattno = mysqli_query($conn, $queryattno);
                        $attachments_no = 0;
                        
                        while($rowattno=mysqli_fetch_assoc($resultattno)){
                            for($i = 1 ; $i <= 6 ; $i++){
                                $attcheck = $rowattno['file_upload'.$i];
                                if (strpos($attcheck, $_GET['key']) !== false) {
                                    $attachments_no++;
                                }
                            }
                        }
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <?php 
                            if(isset($key) && $Ckind === ''){
                                $query_fid = "SELECT * FROM file WHERE file_type!='' AND ((file_id LIKE '%$key%' AND file_id != '') OR (file_subject LIKE '%$key%' AND file_subject != '')
                                OR (file_court LIKE '%$key%' AND file_court != '')) ORDER BY file_id DESC";
                                $result_fid = mysqli_query($conn, $query_fid);
                                
                                $files_no = 0;
                                $queryfno = "SELECT COUNT(*) as countfiles FROM file WHERE file_type!='' AND ((file_id LIKE '%$key%' AND file_id != '') OR (file_subject LIKE '%$key%' AND file_subject != '') 
                                OR (file_court LIKE '%$key%' AND file_court != ''))";
                                $resultfno = mysqli_query($conn, $queryfno);
                                $rowfno= mysqli_fetch_assoc($resultfno);
                                $files_no = $rowfno['countfiles'];
                            } 
                            
                            else if($Ckind === '1'){
                                $queryc = "SELECT * FROM client WHERE arname LIKE '%$key%' OR engname LIKE '%$key%'";
                                $resultc = mysqli_query($conn, $queryc);
                                $ids = [];
                                while ($rowc = mysqli_fetch_assoc($resultc)) {
                                    $ids[] = $rowc['id'];
                                }
                                
                                if (!empty($ids)) {
                                    $ids_list = implode(',', $ids);
                                    
                                    $query_fid = "SELECT * FROM file WHERE file_client IN ($ids_list) OR file_client2 IN ($ids_list) OR file_client3 IN ($ids_list) OR file_client4 IN ($ids_list) OR file_client5 IN ($ids_list) ORDER BY file_id DESC";
                                    $result_fid = mysqli_query($conn, $query_fid);
                                    
                                    $files_no = 0;
                                    $queryfno = "SELECT COUNT(*) as countfiles FROM file WHERE file_client IN ($ids_list) OR file_client2 IN ($ids_list) OR file_client3 IN ($ids_list) OR file_client4 IN ($ids_list) OR file_client5 IN ($ids_list)";
                                    $resultfno = mysqli_query($conn, $queryfno);
                                    $rowfno= mysqli_fetch_assoc($resultfno);
                                    $files_no = $rowfno['countfiles'];
                                }
                            } 
                            
                            else if($Ckind === '2'){
                                $queryc = "SELECT * FROM client WHERE arname LIKE '%$key%' OR engname LIKE '%$key%'";
                                $resultc = mysqli_query($conn, $queryc);
                                $ids = [];
                                while ($rowc = mysqli_fetch_assoc($resultc)) {
                                    $ids[] = $rowc['id'];
                                }
                                
                                if (!empty($ids)) {
                                    $ids_list = implode(',', $ids);
                                    
                                    $query_fid = "SELECT * FROM file WHERE file_opponent IN ($ids_list) OR file_opponent2 IN ($ids_list) OR file_opponent3 IN ($ids_list) OR file_opponent4 IN ($ids_list) OR file_opponent5 IN ($ids_list) ORDER BY file_id DESC";
                                    $result_fid = mysqli_query($conn, $query_fid);
                                    
                                    $files_no = 0;
                                    $queryfno = "SELECT COUNT(*) as countfiles FROM file WHERE file_opponent IN ($ids_list) OR file_opponent2 IN ($ids_list) OR file_opponent3 IN ($ids_list) OR file_opponent4 IN ($ids_list) OR file_opponent5 IN ($ids_list)";
                                    $resultfno = mysqli_query($conn, $queryfno);
                                    $rowfno= mysqli_fetch_assoc($resultfno);
                                    $files_no = $rowfno['countfiles'];
                                }
                            } 
                            
                            else if($Ckind === '3'){
                                $query_fid = "SELECT * FROM file WHERE (file_upload1 LIKE '%$key%'AND file_upload1 != '') OR
                                (file_upload2 LIKE '%$key%'AND file_upload2 != '') OR (file_upload3 LIKE '%$key%'AND file_upload3 != '') OR
                                (file_upload4 LIKE '%$key%'AND file_upload4 != '') OR (file_upload5 LIKE '%$key%'AND file_upload5 != '') OR
                                (file_upload6 LIKE '%$key%'AND file_upload6 != '')";
                                $result_fid = mysqli_query($conn, $query_fid);
                                
                                $files_no = 0;
                                $queryfno = "SELECT COUNT(*) as countfiles FROM file WHERE (file_upload1 LIKE '%$key%'AND file_upload1 != '') OR
                                (file_upload2 LIKE '%$key%'AND file_upload2 != '') OR (file_upload3 LIKE '%$key%'AND file_upload3 != '') OR
                                (file_upload4 LIKE '%$key%'AND file_upload4 != '') OR (file_upload5 LIKE '%$key%'AND file_upload5 != '') OR
                                (file_upload6 LIKE '%$key%'AND file_upload6 != '')";
                                $resultfno = mysqli_query($conn, $queryfno);
                                $rowfno= mysqli_fetch_assoc($resultfno);
                                $files_no = $rowfno['countfiles'];
                            } else{
                                $queryc = "SELECT * FROM client WHERE arname LIKE '%$key%' OR engname LIKE '%$key%'";
                                $resultc = mysqli_query($conn, $queryc);
                                $ids = [];
                                while ($rowc = mysqli_fetch_assoc($resultc)) {
                                    $ids[] = $rowc['id'];
                                }
                                
                                if (!empty($ids)) {
                                    $ids_list = implode(',', $ids);
                                    
                                    $query_fid = "SELECT * FROM file WHERE file_id LIKE '%$key%' OR file_subject LIKE '%$key%' OR file_court LIKE '%$key%' OR file_client IN ($ids_list) OR file_client2 IN ($ids_list) 
                                    OR file_client3 IN ($ids_list) OR file_client4 IN ($ids_list) OR file_client5 IN ($ids_list) OR file_opponent IN ($ids_list) OR file_opponent2 IN ($ids_list) OR file_opponent3 IN ($ids_list) OR file_opponent4 IN ($ids_list) 
                                    OR file_opponent5 IN ($ids_list) ORDER BY file_id DESC";
                                    $result_fid = mysqli_query($conn, $query_fid);
                                    
                                    $files_no = 0;
                                    $queryfno = "SELECT COUNT(*) as countfiles FROM file WHERE file_id LIKE '%$key%' OR file_subject LIKE '%$key%' OR file_court LIKE '%$key%' OR file_client IN ($ids_list) OR file_client2 IN ($ids_list) 
                                    OR file_client3 IN ($ids_list) OR file_client4 IN ($ids_list) OR file_client5 IN ($ids_list) OR file_opponent IN ($ids_list) OR file_opponent2 IN ($ids_list) OR file_opponent3 IN ($ids_list) OR file_opponent4 IN ($ids_list) 
                                    OR file_opponent5 IN ($ids_list)";
                                    $resultfno = mysqli_query($conn, $queryfno);
                                    $rowfno= mysqli_fetch_assoc($resultfno);
                                    $files_no = $rowfno['countfiles'];
                                }
                            }
                        ?>
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">الملفات ( <font color="red"><?php if(isset($files_no) && $files_no !== '' && $files_no !== '0'){ echo $files_no; } else{ echo '0'; }?></font> )</font></h3>
                            </div>
                        </div>
                        <div class="table-body">
                            <table class="info-table" id="myTable" style="width: 2000px">
                                <thead>
                                    <tr class="infotable-search">
                                        <td colspan="19">
                                            <div class="input-container">
                                                <p class="input-parag" style="display: inline-block">البحث : </p>
                                                <input class="form-input" style="display: inline-block; width: 50%;" type="text" id="SearchBox">
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <tr class="infotable-header" style="position: sticky; top: 0; z-index: 3;">
                                        <th width="50px"></th>
                                        <th style="position: sticky; right: 40px; width: 50px;">رقم الملف</th>
                                        <th>الموضوع</th>
                                        <th>الموكل</th>
                                        <th>الخصم</th>
                                        <th>رقم القضية</th>
                                        <th>المحكمة</th>
                                        <th>ت. أخر جلسة</th>
                                        <th>عدد الجلسات</th>
                                        <th>اضافة مذكرة</th>
                                        <th>أعمال إدارية</th>
                                        <th>مدة العمل</th>
                                    </tr>
                                </thead>
                                
                                <?php
                                    if($result_fid->num_rows > 0){
                                        while($row=mysqli_fetch_assoc($result_fid)){
                                ?>
                                <tbody id="table1">
                                    <tr class="infotable-body">
                                        <td width="50px" class="options-td" style="background-color: #fff;">
                                            <i class='bx bx-dots-vertical-rounded bx-xs dropbtn' style="cursor: pointer;" onclick="toggleDropdown(event)"></i>
                                            <div class="dropdown">
                                                <?php if($row_permcheck['cfiles_eperm'] === '1'){?>
                                                <button type="button" onclick="location.href='FileEdit.php?id=<?php echo $row['file_id'];?>';">تعديل</button>
                                                <?php }?>
                                            </div>
                                        </td>
                                        <td style="color: #007bff; position: sticky; right: 40px; width: 50px;">
                                            <font color="#FF0000">
                                                <?php 
                                                    $place = $row['frelated_place'];
                                                    $fileid = $row['file_id'];
                                                    if($place === 'عجمان'){
                                                        echo 'AJM';
                                                    } elseif($place === 'دبي'){
                                                        echo 'DXB';
                                                    } elseif($place === 'الشارقة'){
                                                        echo 'SHJ';
                                                    }
                                                ?>
                                            </font>
                                            <?php echo $fileid;?>
                                        </td>
                                        <td style="color: #007bff"><?php echo $row['file_subject'];?></td>
                                        <td>
                                            <?php if(isset($row['file_client']) && $row['file_client'] !== ''){?>
                                            <p>
                                                <strong>الموكل 1 :</strong>
                                                <?php 
                                                    $cid = $row['file_client']; 
                                                    $queryc = "SELECT * FROM client WHERE id='$cid'";
                                                    $resultc = mysqli_query($conn, $queryc);
                                                    $rowc = mysqli_fetch_array($resultc);
                                                    
                                                    echo $rowc['arname'];
                                                    if(isset($row['fclient_characteristic']) && $row['fclient_characteristic'] !== ''){ echo ' / ' . $row['fclient_characteristic']; }
                                                ?>
                                            </p>
                                            <?php 
                                                }
                                                if(isset($row['file_client2']) && $row['file_client2'] !== ''){
                                            ?>
                                            <p>
                                                <strong>الموكل 2 :</strong>
                                                <?php 
                                                    $cid = $row['file_client2']; 
                                                    $queryc = "SELECT * FROM client WHERE id='$cid'";
                                                    $resultc = mysqli_query($conn, $queryc);
                                                    $rowc = mysqli_fetch_array($resultc);
                                                    
                                                    echo $rowc['arname'];
                                                    if(isset($row['fclient_characteristic2']) && $row['fclient_characteristic2'] !== ''){ echo ' / ' . $row['fclient_characteristic2']; }
                                                ?>
                                            </p>
                                            <?php 
                                                }
                                                if(isset($row['file_client3']) && $row['file_client3'] !== ''){
                                            ?>
                                            <p>
                                                <strong>الموكل 3 :</strong>
                                                <?php 
                                                    $cid = $row['file_client3']; 
                                                    $queryc = "SELECT * FROM client WHERE id='$cid'";
                                                    $resultc = mysqli_query($conn, $queryc);
                                                    $rowc = mysqli_fetch_array($resultc);
                                                    
                                                    echo $rowc['arname'];
                                                    if(isset($row['fclient_characteristic3']) && $row['fclient_characteristic3'] !== ''){ echo ' / ' . $row['fclient_characteristic3']; }
                                                ?>
                                            </p>
                                            <?php 
                                                }
                                                if(isset($row['file_client4']) && $row['file_client4'] !== ''){
                                            ?>
                                            <p>
                                                <strong>الموكل 4 :</strong>
                                                <?php 
                                                    $cid = $row['file_client4']; 
                                                    $queryc = "SELECT * FROM client WHERE id='$cid'";
                                                    $resultc = mysqli_query($conn, $queryc);
                                                    $rowc = mysqli_fetch_array($resultc);
                                                    
                                                    echo $rowc['arname'];
                                                    if(isset($row['fclient_characteristic4']) && $row['fclient_characteristic4'] !== ''){ echo ' / ' . $row['fclient_characteristic4']; }
                                                ?>
                                            </p>
                                            <?php 
                                                }
                                                if(isset($row['file_client5']) && $row['file_client5'] !== ''){
                                            ?>
                                            <p>
                                                <strong>الموكل 5 :</strong>
                                                <?php 
                                                    $cid = $row['file_client5']; 
                                                    $queryc = "SELECT * FROM client WHERE id='$cid'";
                                                    $resultc = mysqli_query($conn, $queryc);
                                                    $rowc = mysqli_fetch_array($resultc);
                                                    
                                                    echo $rowc['arname'];
                                                    if(isset($row['fclient_characteristic5']) && $row['fclient_characteristic5'] !== ''){ echo ' / ' . $row['fclient_characteristic5']; }
                                                ?>
                                            </p>
                                            <?php }?>
                                        </td>
                                        <td>
                                            <?php if(isset($row['file_opponent']) && $row['file_opponent'] !== ''){?>
                                            <p>
                                                <strong>الخصم 1 :</strong>
                                                <?php 
                                                    $cid = $row['file_opponent']; 
                                                    $queryc = "SELECT * FROM client WHERE id='$cid'";
                                                    $resultc = mysqli_query($conn, $queryc);
                                                    $rowc = mysqli_fetch_array($resultc);
                                                    
                                                    echo $rowc['arname'];
                                                    if(isset($row['fopponent_characteristic']) && $row['fopponent_characteristic'] !== ''){ echo ' / ' . $row['fopponent_characteristic']; }
                                                ?>
                                            </p>
                                            <?php 
                                                }
                                                if(isset($row['file_opponent2']) && $row['file_opponent2'] !== ''){
                                            ?>
                                            <p>
                                                <strong>الخصم 2 :</strong>
                                                <?php 
                                                    $cid = $row['file_opponent2']; 
                                                    $queryc = "SELECT * FROM client WHERE id='$cid'";
                                                    $resultc = mysqli_query($conn, $queryc);
                                                    $rowc = mysqli_fetch_array($resultc);
                                                    
                                                    echo $rowc['arname'];
                                                    if(isset($row['fopponent_characteristic2']) && $row['fopponent_characteristic2'] !== ''){ echo ' / ' . $row['fopponent_characteristic2']; }
                                                ?>
                                            </p>
                                            <?php 
                                                }
                                                if(isset($row['file_opponent3']) && $row['file_opponent3'] !== ''){
                                            ?>
                                            <p>
                                                <strong>الخصم 3 :</strong>
                                                <?php 
                                                    $cid = $row['file_opponent3']; 
                                                    $queryc = "SELECT * FROM client WHERE id='$cid'";
                                                    $resultc = mysqli_query($conn, $queryc);
                                                    $rowc = mysqli_fetch_array($resultc);
                                                    
                                                    echo $rowc['arname'];
                                                    if(isset($row['fopponent_characteristic3']) && $row['fopponent_characteristic3'] !== ''){ echo ' / ' . $row['fopponent_characteristic3']; }
                                                ?>
                                            </p>
                                            <?php 
                                                }
                                                if(isset($row['file_opponent4']) && $row['file_opponent4'] !== ''){
                                            ?>
                                            <p>
                                                <strong>الخصم 4 :</strong>
                                                <?php 
                                                    $cid = $row['file_opponent4']; 
                                                    $queryc = "SELECT * FROM client WHERE id='$cid'";
                                                    $resultc = mysqli_query($conn, $queryc);
                                                    $rowc = mysqli_fetch_array($resultc);
                                                    
                                                    echo $rowc['arname'];
                                                    if(isset($row['fopponent_characteristic4']) && $row['fopponent_characteristic4'] !== ''){ echo ' / ' . $row['fopponent_characteristic4']; }
                                                ?>
                                            </p>
                                            <?php 
                                                }
                                                if(isset($row['file_opponent5']) && $row['file_opponent5'] !== ''){
                                            ?>
                                            <p>
                                                <strong>الخصم 5 :</strong>
                                                <?php 
                                                    $cid = $row['file_opponent5']; 
                                                    $queryc = "SELECT * FROM client WHERE id='$cid'";
                                                    $resultc = mysqli_query($conn, $queryc);
                                                    $rowc = mysqli_fetch_array($resultc);
                                                    
                                                    echo $rowc['arname'];
                                                    if(isset($row['fopponent_characteristic5']) && $row['fopponent_characteristic5'] !== ''){ echo ' / ' . $row['fopponent_characteristic5']; }
                                                ?>
                                            </p>
                                            <?php }?>
                                        </td>
                                        <td>
                                            <font color=blue>
                                                <?php
                                                    $fiddeg = $row['file_id'];         
                                                    $query_degs = "SELECT * FROM file_degrees WHERE fid='$fiddeg' ORDER BY created_at DESC";
                                                    $result_degs = mysqli_query($conn, $query_degs);
                                                    if($result_degs->num_rows > 0){
                                                        $row_degs = mysqli_fetch_array($result_degs);
                                                        if(isset($row_degs['fid']) && $row_degs['fid'] !== ''){
                                                            echo $row_degs['case_num'] . '/' . $row_degs['file_year'] . '-' . $row_degs['degree'];
                                                        }
                                                    }
                                                ?>
                                            </font>
                                        </td>
                                        <td><?php echo $row['file_court'];?></td>
                                        <td>
                                            <?php
                                                $fidsee = $row['file_id'];
                                                $querysee = "SELECT * FROM session WHERE session_fid='$fidsee' ORDER BY created_at DESC";
                                                $resultsee = mysqli_query($conn,$querysee);
                                                if($resultsee->num_rows > 0){
                                                    $rowsee = mysqli_fetch_array($resultsee);
                                                    echo $rowsee['session_date'];
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                $fiiid = $row['file_id'];
                                                $query_scount = "SELECT COUNT(*) as sessions_count FROM session WHERE session_fid='$fiiid' AND session_degree!='تنفيذ'";
                                                $result_scount = mysqli_query($conn, $query_scount);
                                                $row_scount = mysqli_fetch_array($result_scount);
                                                $sessions_count = $row_scount['sessions_count'];
                                                echo $sessions_count;
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                $thisid = $row['file_id'];
                                                $query_docs = "SELECT * FROM case_document WHERE dfile_no = $thisid";
                                                $result_docs = mysqli_query($conn,$query_docs);
                                                $i = 0;
                                                while($row3=mysqli_fetch_array($result_docs)){
                                                        $i++;
                                                    }
                                                echo $i;
                                            ?>
                                            <img src="img/add-document.png" width="25px" height="25px" style="cursor:pointer" onclick="open('AddNotes.php?fno=<?php $id=$row['file_id']; echo $id;?>','Pic','width=800 height=800 scrollbars=yes')"/> 
                                        </td>
                                        <td>
                                            <?php
                                                if(isset($row['file_id']) && $row['file_id'] !== ''){
                                                    $fiddds = $row['file_id'];
                                                    $query_tno = "SELECT COUNT(*) as tcount FROM tasks WHERE file_no='$fiddds'";
                                                    $result_tno = mysqli_query($conn, $query_tno);
                                                    $row_tno = mysqli_fetch_array($result_tno);
                                                    $tno = $row_tno['tcount'];
                                                    
                                                    echo $tno;
                                                }
                                            ?>
                                        </td>
                                        <td style="cursor: pointer" onclick="MM_openBrWindow('clientSearchResult.php?id=<?php echo $row['file_id'];?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <?php 
                                                if(isset($row['duration']) && $row['duration'] !== ''){
                                                    echo $row['duration'];
                                                }
                                                if(isset($row['done_by']) && $row['done_by'] !== ''){
                                                    echo '<br>'.$row['done_by'];
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                                <?php }}?>
                            </table>
                        </div>
                        
                        <div class="table-footer">
                            <p></p>
                            <div id="pagination"></div>
                            <div id="pageInfo"></div>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">المرفقات ( <font color="red"><?php if(isset($attachments_no) && $attachments_no !== '' && $attachments_no !== '0'){ echo $attachments_no; } else{ echo '0'; }?> </font></font>)</h3>
                            </div>
                        </div>
                        
                        <div class="table-body" style="height: fit-content; max-height: 75vh;">
                            <?php if($result_att->num_rows > 0){?>
                            <table class="info-table">
                                <thead>
                                    <tr class="infotable-search">
                                        <td colspan="19">
                                            <div class="input-container">
                                                <p class="input-parag" style="display: inline-block">البحث : </p>
                                                <input class="form-input" style="display: inline-block; width: 50%;" type="text" id="SearchBox">
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <tr class="infotable-header" style="position: sticky; top: 0; z-index: 3;">
                                        <th width="50px"></th>
                                        <th style="position: sticky; right: 40px;">رقم الملف</th>
                                        <th>المرفق</th>
                                    </tr>
                                </thead>
                                <?php
                                    while($row_att=mysqli_fetch_assoc($result_att)){
                                        for($i = 1 ; $i <= 6 ; $i++){
                                            $att = $row_att['file_upload'.$i];
                                            if (strpos($att, $_GET['key']) !== false) {
                                ?>
                                <tbody>
                                    <tr class="infotable-body">
                                        <td></td>
                                        <td><?php echo $row_att['file_id'];?></td>
                                        <td>
                                            <a href="<?php echo $att;?>" onClick="window.open('<?php echo $att;?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                <?php echo basename($att);?>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                                <?php }}}?>
                            </table>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        
        <script src="js/translate.js"></script>
        <script src="js/toggleSection.js"></script>
        <script src="js/dropfiles.js"></script>
        <script src="js/popups.js"></script>
        <script src="js/randomPassGenerator.js"></script>
        <script src="js/sweetAlerts.js"></script>
        <script src="js/sweetAlerts2.js"></script>
        <script src="js/tablePages.js"></script>
        <script src="js/checkAll.js"></script>
        <script src="js/dropdown.js"></script>
    </body>
</html>