<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'safe_output.php';
    include_once 'AES256.php';
    include_once 'general_notifications.php';
    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $sessionid = $_SESSION['id'] ?? null;
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
    <body>
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php include_once 'header.php';?>
                <div class="web-page">
                    <div class="links-container">
                        <div class="links">
                            <div class="link-align" style="cursor: pointer;" onclick="MM_openBrWindow('selector/CourtLink.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                <img src="img/court.png" width="100px" height="100px">
                                <p style="font-size: 20px; font-weight: bold; background-color: #ffffff40; border-radius: 4px; padding: 10px">
                                    <font id="courts-translate">المحاكم</font>
                                </p>
                            </div>
                            
                            <div class="link-align" style="cursor: pointer;" onclick="MM_openBrWindow('cases.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                <img src="img/judges.png" width="100px" height="100px">
                                <p style="font-size: 20px; font-weight: bold; background-color: #ffffff40; border-radius: 4px; padding: 10px">
                                    <font id="courts-translate">القضايا</font>
                                </p>
                            </div>
                            
                            <div class="link-align" style="cursor: pointer;" onclick="MM_openBrWindow('hearing.php?tw=1','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                <img src="img/session.png" width="100px" height="100px">
                                <p style="font-size: 20px; font-weight: bold; background-color: #ffffff40; border-radius: 4px; padding: 10px">
                                    <font id="courts-translate">الجلسات</font>
                                </p>
                            </div>
                            
                            <div class="link-align" style="cursor: pointer;" onclick="MM_openBrWindow('Tasks.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                <img src="img/tasks.png" width="100px" height="100px">
                                <p style="font-size: 20px; font-weight: bold; background-color: #ffffff40; border-radius: 4px; padding: 10px">
                                    <font id="courts-translate">المهام</font>
                                </p>
                            </div>
                            
                            <div class="link-align" style="cursor: pointer;" onclick="MM_openBrWindow('clients.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                <img src="img/clients.png" width="100px" height="100px">
                                <p style="font-size: 20px; font-weight: bold; background-color: #ffffff40; border-radius: 4px; padding: 10px">
                                    <font id="courts-translate">الموكلين</font>
                                </p>
                            </div>
                            
                            <div class="link-align centered_hide2" style="cursor: pointer;" onclick="MM_openBrWindow('Accounts.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                <img src="img/accounting.png" width="100px" height="100px">
                                <p style="font-size: 20px; font-weight: bold; background-color: #ffffff40; border-radius: 4px; padding: 10px">
                                    <font id="courts-translate">الحسابات</font>
                                </p>
                            </div>
                            
                            <div class="centered_links2">
                                <div class="link-align" style="cursor: pointer;" onclick="MM_openBrWindow('Accounts.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                    <img src="img/accounting.png" width="100px" height="100px">
                                    <p style="font-size: 20px; font-weight: bold; background-color: #ffffff40; border-radius: 4px; padding: 10px">
                                        <font id="courts-translate">الحسابات</font>
                                    </p>
                                </div>
                            
                                <div class="link-align" style="cursor: pointer;" onclick="MM_openBrWindow('<?php if($row_permcheck['emp_perms_add'] == 1 || $row_permcheck['emp_perms_edit'] == 1 || $row_permcheck['emp_perms_delete'] == 1 || $row_permcheck['logs_rperm'] == 1 || $row_permcheck['vacl_aperm'] == 1 || $row_permcheck['vacf_aperm'] == 1 || $row_permcheck['logs_rperm'] == 1){ echo 'emps_data.php'; } else{ echo 'empinfo.php'; }?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                    <img src="img/hr.png" width="100px" height="100px">
                                    <p style="font-size: 20px; font-weight: bold; background-color: #ffffff40; border-radius: 4px; padding: 10px">
                                        <font id="courts-translate">الموارد البشرية</font>
                                    </p>
                                </div>
                                
                                <div class="link-align" style="cursor: pointer;" onclick="MM_openBrWindow('cases.php?important=1','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                    <img src="img/important.png" width="100px" height="100px">
                                    <p style="font-size: 20px; font-weight: bold; background-color: #ffffff40; border-radius: 4px; padding: 10px">
                                        <font id="courts-translate">دعاوي هامة</font>
                                    </p>
                                </div>
                                
                                <div class="link-align" style="cursor: pointer;" onclick="MM_openBrWindow('fastlinks.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                    <img src="img/fast-links.png" width="100px" height="100px">
                                    <p style="font-size: 20px; font-weight: bold; background-color: #ffffff40; border-radius: 4px; padding: 10px">
                                        <font id="courts-translate">روابط سريعة</font>
                                    </p>
                                </div>
                            </div>
                            
                            <p class="centered-parag1"></p>
                            
                            <div class="centered_links1">
                                <div class="link-align" style="cursor: pointer;" onclick="MM_openBrWindow('<?php if($row_permcheck['emp_perms_add'] == 1 || $row_permcheck['emp_perms_edit'] == 1 || $row_permcheck['emp_perms_delete'] == 1 || $row_permcheck['logs_rperm'] == 1 || $row_permcheck['vacl_aperm'] == 1 || $row_permcheck['vacf_aperm'] == 1 || $row_permcheck['logs_rperm'] == 1){ echo 'emps_data.php'; } else{ echo 'empinfo.php'; }?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                    <img src="img/hr.png" width="100px" height="100px">
                                    <p style="font-size: 20px; font-weight: bold; background-color: #ffffff40; border-radius: 4px; padding: 10px">
                                        <font id="courts-translate">الموارد البشرية</font>
                                    </p>
                                </div>
                                
                                <div class="link-align" style="cursor: pointer;" onclick="MM_openBrWindow('cases.php?important=1','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                    <img src="img/important.png" width="100px" height="100px">
                                    <p style="font-size: 20px; font-weight: bold; background-color: #ffffff40; border-radius: 4px; padding: 10px">
                                        <font id="courts-translate">دعاوي هامة</font>
                                    </p>
                                </div>
                                
                                <div class="link-align" style="cursor: pointer;" onclick="MM_openBrWindow('fastlinks.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                    <img src="img/fast-links.png" width="100px" height="100px">
                                    <p style="font-size: 20px; font-weight: bold; background-color: #ffffff40; border-radius: 4px; padding: 10px">
                                        <font id="courts-translate">روابط سريعة</font>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="general-info">
                        <div class="columns-align">
                            <div class="columns-style" style="text-align: -webkit-center;">
                                <div class="column-header">
                                    <div class="column-image-container">
                                        <div class="column-image" style="background-image: url('img/book.png');"></div>
                                    </div>
                                    <div class="column-parag">
                                        <h3 id="latedecisions-translate" style="display: inline-block;">القرارات المتأخرة</h3> <h3 style="display: inline-block;">(<font color="#FF0000">
                                            <?php
                                                $countStmt = $conn->prepare("
                                                SELECT COUNT(DISTINCT session_fid) AS latecount
                                                    FROM session
                                                    WHERE session_fid NOT IN (
                                                        SELECT DISTINCT session_fid FROM session WHERE session_decission IS NOT NULL AND session_decission <> ''
                                                    )
                                                ");
                                                $countStmt->execute();
                                                $countResult = $countStmt->get_result();
                                                $countRow = $countResult->fetch_assoc();
                                                
                                                $latecount = 0;
                                                if(isset($countRow['latecount'])){
                                                    $latecount = $countRow['latecount'];
                                                    echo safe_output($latecount);
                                                }
                                            ?>
                                        </font>)</h3>
                                    </div>
                                </div>
                                <?php
                                    $stmticos = $conn->prepare("
                                        SELECT DISTINCT s1.session_fid, s1.session_id
                                        FROM session s1
                                        LEFT JOIN session s2 
                                            ON s1.session_fid = s2.session_fid 
                                            AND s2.session_decission IS NOT NULL 
                                            AND s2.session_decission <> ''
                                        WHERE s2.session_fid IS NULL
                                        ORDER BY s1.session_fid DESC
                                    ");
                                    $stmticos->execute();
                                    $resulticos = $stmticos->get_result();
                                    if($resulticos->num_rows > 0){
                                        $rowicos = $resulticos->fetch_assoc();
                                        
                                        $session_id = $rowicos['session_id'];
                                        $stmticos2 = $conn->prepare("SELECT * FROM session WHERE session_id=?");
                                        $stmticos2->bind_param("i", $session_id);
                                        $stmticos2->execute();
                                        $resulticos2 = $stmticos2->get_result();
                                        $rowicos2 = $resulticos2->fetch_assoc();
                                        $stmticos2->close();
                                ?>
                                <div style="border: 1px solid #67676725; margin-top: 20px; text-align: -webkit-center; align-content: center; height: 50px; width: 80%; background-color: #fefeff; border-radius: 25px;">
                                    <img src="img/BookedJud2.png" title="منطوق الحكم" width="20px" height="20px" style="cursor:pointer" onclick="MM_openBrWindow('Judgement.php?sid=<?php echo safe_output($rowicos2['session_id']); ?>&fid=<?php echo safe_output($rowicos2['session_fid']); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=200')"> 
                                    <img src="img/ExtendedJud.png" title="مد أجل الحكم" width="20px" height="20px" style="cursor:pointer" onclick="MM_openBrWindow('ExtendJud.php?fid=<?php echo safe_output($rowicos2['session_fid']); ?>&sid=<?php echo safe_output($rowicos2['session_id']); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=200')"> 
                                    <img src="img/referral.png" title="تمت الاحالة" width="20px" height="20px" style="cursor: pointer;" onclick="location.href='editfile.php?page=index.php&referral=1&referralfid=<?php echo safe_output($rowicos2['session_fid']); ?>&referralsid=<?php echo safe_output($rowicos2['session_id']); ?>';"> 
                                    <img src="img/Handshake.png" title="تم الصلح" width="20px" height="20px" style="cursor: pointer;" onclick="location.href='editfile.php?page=index.php&reconciliation=1&reconciliationfid=<?php echo safe_output($rowicos2['session_fid']); ?>&reconciliationsid=<?php echo safe_output($rowicos2['session_id']); ?>';">
                                    <img src="img/BookedJud.png" title="حجزت للحكم" width="20px" height="20px" style="cursor: pointer;" onclick="MM_openBrWindow('BookedJud.php?sid=<?php echo safe_output($rowicos2['session_id']); ?>&fid=<?php echo safe_output($rowicos2['session_fid']);?>&deg=<?php echo safe_output($rowicos2['session_degree']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=300')"> 
                                    <img src="img/decission.png" title="قرار الجلسة" width="20px" height="20px" style="cursor: pointer;" onclick="MM_openBrWindow('decission.php?sid=<?php echo safe_output($rowicos2['session_id']); ?>&fid=<?php echo safe_output($rowicos2['session_fid']); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=300')"> 
                                </div>
                                <br>
                                <?php 
                                    }
                                    $stmticos->close();
                                ?>
                                <button class="green-button display-none-default2" onclick="toggleDiv('1')">اظهار / اخفاء</button>
                                <div class="column-body" id="display-column-1">
                                    <?php 
                                        $stmt = $conn->prepare("
                                            SELECT DISTINCT s1.session_fid
                                            FROM session s1
                                            LEFT JOIN session s2 
                                                ON s1.session_fid = s2.session_fid 
                                                AND s2.session_decission IS NOT NULL 
                                                AND s2.session_decission <> ''
                                            WHERE s2.session_fid IS NULL
                                            ORDER BY s1.session_fid DESC
                                        ");
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                                if(isset($row['session_fid'])){
                                                    $ffi = safe_output($row['session_fid']);
                                                    $stmt22 = $conn->prepare("SELECT * FROM session WHERE session_fid=?");
                                                    $stmt22->bind_param("i", $ffi);
                                                    $stmt22->execute();
                                                    $result22 = $stmt22->get_result();
                                                    while($row22 = $result22->fetch_assoc()){
                                                        if(isset($row22['session_decission'])){
                                                            $session_decission = safe_output($row22['session_decission']);
                                                            $flag_dec = '0';
                                                            if($session_decission !== ''){
                                                                $flag_dec = '1';
                                                            }
                                                        }
                                                    }
                                                    if($flag_dec === '1'){
                                                        continue;
                                                    }
                                                    
                                                    $stmts_f = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                                                    $stmts_f->bind_param("i", $ffi);
                                                    $stmts_f->execute();
                                                    $results_f = $stmts_f->get_result();
                                                    
                                                    $file_place = '';
                                                    if($results_f->num_rows > 0){
                                                        $rows_f = $results_f->fetch_assoc();
                                    ?>
                                    <div class="column-row">
                                        <div class="row-subject">
                                            <div class="row-subject-grid">
                                                <div class="exclamation-mark" <?php if($row_permcheck['cfiles_rperm'] == 1){?> onclick="MM_openBrWindow('CasePreview.php?fid=<?php if(isset($rows_f['file_id'])){ echo safe_output($rows_f['file_id']); }?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')" style="cursor:pointer" <?php }?>></div>
                                                <p>
                                                    <?php
                                                        $file_place = '';
                                                        if(isset($rows_f['frelated_place'])){
                                                            if($rows_f['frelated_place'] === 'دبي'){
                                                                $file_place = 'DXB';
                                                            }
                                                            else if($rows_f['frelated_place'] === 'الشارقة'){
                                                                $file_place = 'SHJ';
                                                            }
                                                            else if($rows_f['frelated_place'] === 'عجمان'){
                                                                $file_place = 'AJM';
                                                            }
                                                        }
                                                        
                                                        echo '<font color="red">'.safe_output($file_place).'</font> '.safe_output($ffi);
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                        <?php
                                            $stmt_session = $conn->prepare("SELECT * FROM session WHERE session_fid = ? ORDER BY session_fid DESC");
                                            $stmt_session->bind_param("i", $ffi);
                                            $stmt_session->execute();
                                            $result_session = $stmt_session->get_result();
                                            $row_session = $result_session->fetch_assoc();
                                        ?>
                                        <div class="row-details" <?php if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){?> style="cursor: pointer" onclick="MM_openBrWindow('hearing_edit.php?sid=<?php if(isset($row_session['session_id'])){ echo safe_output($row_session['session_id']); }?>&fid=<?php if(isset($rows_f['file_id'])){ echo safe_output($rows_f['file_id']); }?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=1000,height=600')" <?php }?>>
                                            <p class="row-parag">
                                                تاريخ الجلسة : 
                                                <font style="color: red">
                                                    <?php
                                                        if(isset($row_session['session_date'])){
                                                            echo safe_output($row_session['session_date']);
                                                        }
                                                    ?>
                                                </font>
                                            </p>
                                            <p class="row-parag">
                                                رقم القضية : 
                                                <?php 
                                                    if(isset($row_session['case_num']) && $row_session['case_num'] != 0){
                                                        echo safe_output($row_session['case_num']);
                                                    }
                                                    if(isset($row_session['year']) && $row_session['year'] != 0){
                                                        echo ' / '.safe_output($row_session['year']);
                                                    }
                                                    if(isset($row_session['session_degree']) && $row_session['session_degree'] !== ''){
                                                        echo ' - '.safe_output($row_session['session_degree']);
                                                    }
                                                ?>
                                            </p>
                                            <p class="row-parag">
                                                الموكل : 
                                                <?php 
                                                    if(isset($rows_f['file_client'])){
                                                        $cid1 = safe_output($rows_f['file_client']); 
                                                        
                                                        $stmtclient = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtclient->bind_param("i", $cid1);
                                                        $stmtclient->execute();
                                                        $resultclient = $stmtclient->get_result();
                                                        $rowclient = $resultclient->fetch_assoc();
                                                        
                                                        if(isset($rowclient['arname'])){
                                                            echo safe_output($rowclient['arname']);
                                                        }
                                                        
                                                        if(isset($rows_f['fclient_characteristic']) && $rows_f['fclient_characteristic'] !== ''){ 
                                                            echo ' / <font color=blue>' . safe_output($rows_f['fclient_characteristic']) . '</font>';
                                                        }
                                                    }
                                                    
                                                    if(isset($rows_f['file_client2'])){
                                                        echo '<br>';
                                                        
                                                        $cid2 = safe_output($rows_f['file_client2']); 
                                                        
                                                        $stmtclient = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtclient->bind_param("i", $cid2);
                                                        $stmtclient->execute();
                                                        $resultclient = $stmtclient->get_result();
                                                        $rowclient = $resultclient->fetch_assoc();
                                                        
                                                        if(isset($rowclient['arname'])){
                                                            echo safe_output($rowclient['arname']);
                                                        }
                                                        
                                                        if(isset($rows_f['fclient_characteristic2']) && $rows_f['fclient_characteristic2'] !== ''){ 
                                                            echo ' / <font color=blue>' . safe_output($rows_f['fclient_characteristic2']) . '</font>';
                                                        }
                                                    }
                                                    
                                                    if(isset($rows_f['file_client3']) && $rows_f['file_client3'] !== ''){
                                                        echo '<br>';
                                                        
                                                        $cid3 = safe_output($rows_f['file_client3']); 
                                                        
                                                        $stmtclient = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtclient->bind_param("i", $cid3);
                                                        $stmtclient->execute();
                                                        $resultclient = $stmtclient->get_result();
                                                        $rowclient = $resultclient->fetch_assoc();
                                                        
                                                        if(isset($rowclient['arname'])){
                                                            echo safe_output($rowclient['arname']);
                                                        }
                                                        
                                                        if(isset($rows_f['fclient_characteristic3']) && $rows_f['fclient_characteristic3'] !== ''){ 
                                                            echo ' / <font color=blue>' . safe_output($rows_f['fclient_characteristic3']) . '</font>';
                                                        }
                                                    }
                                                    
                                                    if(isset($rows_f['file_client4']) && $rows_f['file_client4'] !== ''){
                                                        echo '<br>';
                                                        
                                                        $cid4 = safe_output($rows_f['file_client4']); 
                                                        
                                                        $stmtclient = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtclient->bind_param("i", $cid4);
                                                        $stmtclient->execute();
                                                        $resultclient = $stmtclient->get_result();
                                                        $rowclient = $resultclient->fetch_assoc();
                                                        
                                                        if(isset($rowclient['arname'])){
                                                            echo safe_output($rowclient['arname']);
                                                        }
                                                        
                                                        if(isset($rows_f['fclient_characteristic4']) && $rows_f['fclient_characteristic4'] !== ''){ 
                                                            echo ' / <font color=blue>' . safe_output($rows_f['fclient_characteristic4']) . '</font>';
                                                        }
                                                    }
                                                    
                                                    if(isset($rows_f['file_client5']) && $rows_f['file_client5'] !== ''){
                                                        echo '<br>';
                                                        
                                                        $cid5 = safe_output($rows_f['file_client5']); 
                                                        
                                                        $stmtclient = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtclient->bind_param("i", $cid5);
                                                        $stmtclient->execute();
                                                        $resultclient = $stmtclient->get_result();
                                                        $rowclient = $resultclient->fetch_assoc();
                                                        
                                                        if(isset($rowclient['arname'])){
                                                            echo safe_output($rowclient['arname']);
                                                        }
                                                        
                                                        if(isset($rows_f['fclient_characteristic5']) && $rows_f['fclient_characteristic5'] !== ''){
                                                            echo ' / <font color=blue>' . safe_output($rows_f['fclient_characteristic5']) . '</font>';
                                                        }
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <?php
                                                    }
                                                }
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="columns-align">
                            <div class="columns-style">
                                <div class="column-header">
                                    <div class="column-image-container">
                                        <div class="column-image" style="background-image: url('img/hourglass.png');"></div>
                                    </div>
                                    <div class="column-parag">
                                        <h3 id="appeals-translate" style="display: inline-block;">الاستئنافات و الطعون</h3> <h3 style="display: inline-block;">(<font color="#FF0000">
                                            <?php
                                                $stmtcou = $conn->prepare("SELECT COUNT(*) as countsesss FROM session WHERE (resume_appeal!='' OR resume_appeal!='0') AND resume_overdue!='' AND (resume_daysno!='' OR resume_daysno!='0')");
                                                $stmtcou->execute();
                                                $resultcou = $stmtcou->get_result();
                                                $rowcou = $resultcou->fetch_assoc();
                                                if(isset($rowcou['countsesss'])){
                                                    echo safe_output($rowcou['countsesss']);
                                                }
                                            ?>
                                        </font>)</h3>
                                    </div>
                                </div>
                                <button class="green-button display-none-default2" onclick="toggleDiv('2')">اظهار / اخفاء</button>
                                <div class="column-body" id="display-column-2">
                                    <?php
                                        $stmtfixd = $conn->prepare("SELECT * FROM session WHERE resume_appeal > 0");
                                        $stmtfixd->execute();
                                        $resultfixd = $stmtfixd->get_result();
                                        
                                        $updates = [];
                                        while($rowfixd = $resultfixd->fetch_assoc()){
                                            $session_id = 0;
                                            if(isset($rowfixd['session_id'])){
                                                $session_id = safe_output($rowfixd['session_id']);
                                            }
                                            $rowfixd['resume_overdue'] = '';
                                            if(isset($rowfixd['resume_overdue'])){
                                                $resume_overdue = safe_output($rowfixd['resume_overdue']);
                                            }
                                            
                                            if (!$resume_overdue) continue;
                                            
                                            $today = new DateTime();
                                            $resume_date = new DateTime($resume_overdue);
                                            $difference = $today->diff($resume_date)->days;
                                            
                                            $updates[] = [$difference, $session_id];
                                        }
                                        
                                        if (!empty($updates)) {
                                            $stmt = $conn->prepare("UPDATE session SET resume_daysno = ? WHERE session_id = ?");
                                            foreach ($updates as [$diff, $id]) {
                                                $stmt->bind_param("ii", $diff, $id);
                                                $stmt->execute();
                                            }
                                        }
                                        
                                        $stmtselectdel = $conn->prepare("SELECT * FROM session WHERE resume_daysno = '0'");
                                        $stmtselectdel->execute();
                                        $resultselectdel = $stmtselectdel->get_result();
                                        
                                        $idselectdelarr = [];
                                        while($rowselectdel = $resultselectdel->fetch_assoc()){
                                            $idselectdel = safe_output($rowselectdel['session_id']);
                                            $idselectdelarr[] = $idselectdel;
                                        }
                                        if (count($idselectdelarr) > 0) {
                                            $placeholders3 = implode(',', array_fill(0, count($idselectdelarr), '?'));
                                            $types3 = str_repeat('i', count($idselectdelarr));
                                            
                                            $stmtdels = $conn->prepare("UPDATE session SET resume_appeal='0', resume_overdue='', resume_daysno='0' WHERE session_id IN ($placeholders3)");
                                            $stmtdels->bind_param($types3, ...$idselectdelarr);
                                            $stmtdels->execute();
                                        }
                                        
                                        $stmt_sesdel = $conn->prepare("DELETE FROM session WHERE resume_appeal < 0");
                                        $stmt_sesdel->execute();
                                        
                                        $stmt_sessions = $conn->prepare("SELECT * FROM session WHERE resume_appeal='1' OR resume_appeal='2' OR resume_appeal='3' OR resume_appeal='4'");
                                        $stmt_sessions->execute();
                                        $result_sessions = $stmt_sessions->get_result();
                                        if($result_sessions->num_rows > 0){
                                            $todaysdate = date('Y-m-d');
                                            while($row_sessions = $result_sessions->fetch_array()){
                                                $fdid = $row_sessions['session_fid'];
                                                if($row_sessions['session_date'] <= $todaysdate){
                                    ?>
                                    <div class="column-row">
                                        <div class="column-header" style="padding: 0 10px">
                                            <h3 style="color: red; align-content: center;" class="blink">
                                                <?php 
                                                    if(isset($row_sessions['resume_daysno'])){
                                                        if($row_sessions['resume_daysno'] === '0'){
                                                            $remain = '0 يوم';
                                                        } else if($row_sessions['resume_daysno'] === '1'){
                                                            $remain = 'يوم';
                                                        } else if($row_sessions['resume_daysno'] === '0'){
                                                            $remain = 'يومان';
                                                        } else if($row_sessions['resume_daysno'] <= 10){
                                                            $remain = $row_sessions['resume_daysno'].' ايام';
                                                        } else{
                                                            $remain = $row_sessions['resume_daysno'].' يوم';
                                                        }
                                                        if(isset($remain)){
                                                            echo $remain;
                                                        }
                                                    }
                                                ?>
                                            </h3>
                                            <div class="column-parag">
                                                حجزت للـ
                                                <?php 
                                                    if(isset($row_sessions['resume_appeal']) && $row_sessions['resume_appeal'] !== ''){
                                                        if($row_sessions['resume_appeal'] == 1){
                                                            echo 'استئناف لتاريخ <br>';
                                                        }
                                                        else if($row_sessions['resume_appeal'] == 2){
                                                            echo 'طعن لتاريخ <br>';
                                                        }
                                                        else if($row_sessions['resume_appeal'] == 3){
                                                            echo 'تظلم لتاريخ <br>';
                                                        } 
                                                        else if($row_sessions['resume_appeal'] == 4){
                                                            echo 'معارضة لتاريخ <br>';
                                                        }
                                                    }
                                                ?> 
                                                <font style="color: red" style="cursor: pointer;"><?php if(isset($row_sessions['resume_overdue'])){ echo $row_sessions['resume_overdue']; }?></font>
                                            </div>
                                            <?php if($row_permcheck['session_dperm'] == 1){?>
                                            <div style="align-content: center;"><div class="delete-session" style="background-image: url('img/comment.png');" onclick="MM_openBrWindow('resume_appeal.php?id=<?php echo $row_sessions['session_id'];?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')"></div></div>
                                            <?php }?>
                                        </div>
                                        <div class="row-details"  style="cursor:pointer" onclick="MM_openBrWindow('CasePreview.php?fid=<?php if(isset($fdid)){ echo $fdid; }?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <p class="row-parag">تاريخ الجلسة : <font style="color: red"><?php if(isset($row_sessions['session_date'])){ echo $row_sessions['session_date']; }?></font></p>
                                            <?php
                                                $case_num = '';
                                                $year = '';
                                                $session_degree = '';
                                                if(isset($row_sessions['case_num'])){
                                                    $case_num = safe_output($row_sessions['case_num']);
                                                } if(isset($row_sessions['year'])){
                                                    $year = safe_output($row_sessions['year']);
                                                } if(isset($row_sessions['session_degree'])){
                                                    $session_degree = safe_output($row_sessions['session_degree']);
                                                }
                                            ?>
                                            <p class="row-parag">رقم القضية : <?php echo $case_num.' / '.$year.' - '.$session_degree;?></p>
                                            <?php
                                                if(isset($row_sessions['session_fid'])){
                                                    $session_fid = safe_output($row_sessions['session_fid']);
                                                }
                                                
                                                $stmtclop = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                                                $stmtclop->bind_param("i", $session_fid);
                                                $stmtclop->execute();
                                                $resultclop = $stmtclop->get_result();
                                                if($resultclop->num_rows > 0){
                                                    $rowclop = $resultclop->fetch_assoc();
                                            ?>
                                            <p class="row-parag">
                                                الموكل : 
                                                <?php 
                                                    if(isset($rowclop['file_client'])){
                                                        $cid1 = safe_output($rowclop['file_client']); 
                                                        
                                                        $stmtclient = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtclient->bind_param("i", $cid1);
                                                        $stmtclient->execute();
                                                        $resultclient = $stmtclient->get_result();
                                                        $rowclient = $resultclient->fetch_assoc();
                                                        
                                                        if(isset($rowclient['arname'])){ 
                                                            echo safe_output($rowclient['arname']);
                                                        }
                                                        
                                                        if(isset($rowclop['fclient_characteristic']) && $rowclop['fclient_characteristic'] !== ''){ 
                                                            echo ' / <font color=blue>' . safe_output($rowclop['fclient_characteristic']) . '</font>';
                                                        }
                                                    }
                                                    
                                                    if(isset($rowclop['file_client2']) && $rowclop['file_client2'] !== ''){
                                                        echo '<br>';
                                                        
                                                        $cid2 = safe_output($rowclop['file_client2']); 
                                                    
                                                        $stmtclient = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtclient->bind_param("i", $cid2);
                                                        $stmtclient->execute();
                                                        $resultclient = $stmtclient->get_result();
                                                        $rowclient = $resultclient->fetch_assoc();
                                                        
                                                        if(isset($rowclient['arname'])){ 
                                                            echo safe_output($rowclient['arname']); 
                                                        }
                                                        
                                                        if(isset($rowclop['fclient_characteristic2']) && $rowclop['fclient_characteristic2'] !== ''){ 
                                                            echo ' / <font color=blue>' . safe_output($rowclop['fclient_characteristic2']) . '</font>';
                                                        }
                                                    }
                                                    
                                                    if(isset($rowclop['file_client3']) && $rowclop['file_client3'] !== ''){
                                                        echo '<br>';
                                                        
                                                        $cid3 = safe_output($rowclop['file_client3']); 
                                                    
                                                        $stmtclient = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtclient->bind_param("i", $cid3);
                                                        $stmtclient->execute();
                                                        $resultclient = $stmtclient->get_result();
                                                        $rowclient = $resultclient->fetch_assoc();
                                                        
                                                        if(isset($rowclient['arname'])){ 
                                                            echo safe_output($rowclient['arname']); 
                                                        }
                                                        
                                                        if(isset($rowclop['fclient_characteristic3']) && $rowclop['fclient_characteristic3'] !== ''){ 
                                                            echo ' / <font color=blue>' . safe_output($rowclop['fclient_characteristic3']) . '</font>';
                                                        }
                                                    }
                                                    
                                                    if(isset($rowclop['file_client4']) && $rowclop['file_client4'] !== ''){
                                                        echo '<br>';
                                                        
                                                        $cid4 = safe_output($rowclop['file_client4']); 
                                                    
                                                        $stmtclient = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtclient->bind_param("i", $cid4);
                                                        $stmtclient->execute();
                                                        $resultclient = $stmtclient->get_result();
                                                        $rowclient = $resultclient->fetch_assoc();
                                                        
                                                        if(isset($rowclient['arname'])){ 
                                                            echo safe_output($rowclient['arname']); 
                                                        }
                                                        
                                                        if(isset($rowclop['fclient_characteristic4']) && $rowclop['fclient_characteristic4'] !== ''){ 
                                                            echo ' / <font color=blue>' . safe_output($rowclop['fclient_characteristic4']) . '</font>';
                                                        }
                                                    }
                                                    
                                                    if(isset($rowclop['file_client5']) && $rowclop['file_client5'] !== ''){
                                                        echo '<br>';
                                                        
                                                        $cid5 = safe_output($rowclop['file_client5']); 
                                                        
                                                        $stmtclient = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtclient->bind_param("i", $cid5);
                                                        $stmtclient->execute();
                                                        $resultclient = $stmtclient->get_result();
                                                        $rowclient = $resultclient->fetch_assoc();
                                                        
                                                        if(isset($rowclient['arname'])){ 
                                                            echo safe_output($rowclient['arname']); 
                                                        }
                                                        
                                                        if(isset($rowclop['fclient_characteristic5']) && $rowclop['fclient_characteristic5'] !== ''){
                                                            echo ' / <font color=blue>' . safe_output($rowclop['fclient_characteristic5']) . '</font>';
                                                        }
                                                    }
                                                ?>
                                            </p>
                                            <?php }?>
                                        </div>
                                    </div>
                                    <?php
                                                }
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="columns-align">
                            <div class="columns-style">
                                <div class="column-header">
                                    <div class="column-image-container">
                                        <div class="column-image" style="background-image: url('img/schedule.png');"></div>
                                    </div>
                                    <div class="column-parag">
                                        <h3 id="weeklyhearings-translate" style="display: inline-block;">جلسات الاسبوع</h3> <h3 style="display: inline-block;">( <font color="#FF0000">
                                        <?php 
                                            $today = new DateTime();
                                          
                                            $future_date = clone $today;
                                            $future_date->modify('+7 days');
                                          
                                            $interval = new DateInterval('P1D');
                                            $daterange = new DatePeriod($today, $interval, $future_date);
                                            
                                            $week_sessions = 0;
                                            foreach ($daterange as $date) {
                                                $date = $date->format("Y-m-d");
                                                
                                                $stmt_sessionall = $conn->prepare("SELECT COUNT(*) as counts FROM session WHERE session_date=?");
                                                $stmt_sessionall->bind_param("s", $date);
                                                $stmt_sessionall->execute();
                                                $result_sessionall = $stmt_sessionall->get_result();
                                                if($result_sessionall->num_rows > 0){
                                                    $row_sessionall = $result_sessionall->fetch_assoc();
                                                    $week_sessions = $week_sessions + $row_sessionall['counts'];
                                                }
                                            }
                                            
                                            echo safe_output($week_sessions);
                                        ?></font> )</h3>
                                    </div>
                                </div>
                                <button class="green-button display-none-default2" onclick="toggleDiv('3')">اظهار / اخفاء</button>
                                <div class="column-body" id="display-column-3">
                                    <?php
                                        $today = new DateTime();
                                        
                                        $future_date = clone $today;
                                        $future_date->modify('+7 days');
                                        
                                        $interval = new DateInterval('P1D');
                                        $daterange = new DatePeriod($today, $interval, $future_date);
                                        
                                        foreach ($daterange as $date) {
                                            $format_d = $date->format('Y-m-d');
                                            
                                            $stmt_sessionall2 = $conn->prepare("SELECT * FROM session WHERE session_date=?");
                                            $stmt_sessionall2->bind_param("s", $format_d);
                                            $stmt_sessionall2->execute();
                                            $result_sessionall2 = $stmt_sessionall2->get_result();
                                            if($result_sessionall2->num_rows > 0){
                                                while($row_sessionall2 = $result_sessionall2->fetch_assoc()){
                                    ?>
                                    <div class="column-row">
                                        <div class="row-subject">
                                            <?php if(isset($row_sessionall2['session_details']) && $row_sessionall2['session_details'] !== ''){ echo $row_sessionall2['session_details']; } else { echo 'لم يتم ادخال موضوع الجلسة!'; }?>
                                        </div>
                                        <div class="row-details" style="cursor:pointer" onclick="MM_openBrWindow('CasePreview.php?fid=<?php if(isset($row_sessionall2['session_fid'])){ echo $row_sessionall2['session_fid']; }?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <p class="row-parag">تاريخ الجلسة : <font style="color: red;"><?php if(isset($row_sessionall2['session_date'])){ echo $row_sessionall2['session_date']; }?></font></p>
                                            <p class="row-parag">رقم القضية : 
                                                <?php 
                                                    if(isset($row_sessionall2['case_num']) && $row_sessionall2['case_num'] !== '0'){
                                                        echo safe_output($row_sessionall2['case_num']);
                                                    }
                                                    if(isset($row_sessionall2['year']) && $row_sessionall2['year'] !== '0'){
                                                        echo ' / '.safe_output($row_sessionall2['year']);
                                                    }
                                                    if(isset($row_sessionall2['session_degree']) && $row_sessionall2['session_degree'] !== ''){
                                                        echo ' - '.safe_output($row_sessionall2['session_degree']);
                                                    }
                                                ?>
                                            </p>
                                            <p class="row-parag">الموكل : 
                                                <?php 
                                                    if(isset($row_sessionall2['session_fid'])){
                                                        $fid = safe_output($row_sessionall2['session_fid']);
                                                        
                                                        $stmtf2 = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                                                        $stmtf2->bind_param("i", $fid);
                                                        $stmtf2->execute();
                                                        $resultf2 = $stmtf2->get_result();
                                                        $rowf2 = $resultf2->fetch_assoc();
                                                        
                                                        if(isset($rowf2['file_client'])){
                                                            $file_client = safe_output($rowf2['file_client']);
                                                            
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $file_client);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            if(isset($rowc['arname'])){ 
                                                                echo safe_output($rowc['arname']);
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <?php
                                                }
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="js/newWindow.js"></script>
        <script src="js/translate.js"></script>
    </body>
</html>