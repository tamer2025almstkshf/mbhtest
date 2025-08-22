<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
?>
<!DOCTYPE html>
<html lang="ar">
    <head>
        <title>محمد بني هاشم للمحاماة و الاستشارات القانونية</title>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <meta charset="UTF-8">
        <meta name="google-site-verification" content="_xmqQ0kTuDS9ta1v4E4je5rweWQ4qtH1l8_cnWro7Tk" />
        <meta name="robots" content="noindex, nofollow">
        <meta name="googlebot" content="noindex">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link href="css/sites.css" rel="stylesheet">
        <link rel="SHORTCUT ICON" href="img/favicon.ico">
    </head>
    <body>
        <?php if($row_permcheck['cfiles_rperm'] == 1){?>
        <div align="center" class="printbtn" id="printbtn" onclick="showPrint();">طباعة</div>
        <?php
            $fid = filter_input(INPUT_GET, 'fid', FILTER_VALIDATE_INT);
            
            $stmtid = $conn->prepare("SELECT * FROM file WHERE file_id=?");
            $stmtid->bind_param("i", $id);
            $stmtid->execute();
            $resultid = $stmtid->get_result();
            $row_details = $resultid->fetch_assoc();
            $stmtid->close();
            if($admin != 1){
                if($row_details['secret_folder'] == 1){
                    $empids = $row_details['secret_emps'];
                    $empids = array_filter(array_map('trim', explode(',', $empids)));
                    if (!in_array($_SESSION['id'], $empids)) {
                        exit();
                    }
                }
            }
            if(!$fid) {
                die('Invalid file ID');
            }
            
            $stmt = $conn->prepare("SELECT * FROM file WHERE file_id = ?");
            $stmt->bind_param("i", $fid);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
        ?>
        <div class="container" id="PrintMainDiv">
            <main class="main-content">
                <section id="case-header" class="case-header">
                    <h1>
                        تفاصيل الملف رقم 
                        <span class="case-number">
                            <?php
                                $related_place = isset($row['frelated_place']) ? safe_output($row['frelated_place']) : '';
                                if($related_place === 'الشارقة'){
                                    echo "SHJ";
                                } else if($related_place === 'دبي'){
                                    echo "DXB";
                                } else if($related_place === 'عجمان'){
                                    echo "AJM";
                                }
                                echo " " . (isset($row['file_id']) ? safe_output($row['file_id']) : '');
                            ?>
                        </span>
                    </h1>
                </section>
                
                <section id="case-info" class="case-info">
                    <h2>معلومات الملف</h2>
                    <div class="info-grid">
                        <div class="info-box"><h4>نوع الملف</h4><p><?php echo isset($row['file_type']) ? safe_output($row['file_type']) : ''; ?></p></div>
                        <div class="info-box"><h4>تصنيف الملف</h4><p><?php echo isset($row['file_class']) ? safe_output($row['file_class']) : ''; ?></p></div>
                        <div class="info-box"><h4>مركز الشرطة</h4><p><?php echo isset($row['fpolice_station']) ? safe_output($row['fpolice_station']) : ''; ?></p></div>
                        <div class="info-box"><h4>المحكمة</h4><p><?php echo isset($row['file_court']) ? safe_output($row['file_court']) : ''; ?></p></div>
                    </div>
                </section>
                
                <div class="clients-description moreinps-container" class="clients-description">
                    <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                        <span style="font-size: 22px"><i class='bx bxs-user' ></i> <p>الموكلين</p></span> 
                        <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                        <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                    </button>
                    <div class="moreinps-content" id="showclientstoprint" style="display: none;">
                        <?php if(isset($row['file_client']) && $row['file_client'] !== ''){?>
                        <p>
                            <?php 
                                $cid = $row['file_client'];
                                
                                $stmt_client = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                $stmt_client->bind_param("i", $cid);
                                $stmt_client->execute();
                                $result_client = $stmt_client->get_result();
                                $rowc = $result_client->fetch_assoc();
                                $stmt_client->close();
                                
                                echo isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                                if(isset($row['fclient_characteristic']) && $row['fclient_characteristic'] !== ''){ 
                                    echo ' / ' . safe_output($row['fclient_characteristic']); 
                                }
                            ?>
                        </p>
                        <?php 
                            }
                            if(isset($row['file_client2']) && $row['file_client2'] !== ''){
                        ?>
                        <p>
                            <?php 
                                $cid = $row['file_client2'];
                                
                                $stmt_client = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                $stmt_client->bind_param("i", $cid);
                                $stmt_client->execute();
                                $result_client = $stmt_client->get_result();
                                $rowc = $result_client->fetch_assoc();
                                $stmt_client->close();
                                
                                echo isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                                if(isset($row['fclient_characteristic2']) && $row['fclient_characteristic2'] !== ''){ 
                                    echo ' / ' . safe_output($row['fclient_characteristic2']); 
                                }
                            ?>
                        </p>
                        <?php 
                            }
                            if(isset($row['file_client3']) && $row['file_client3'] !== ''){
                        ?>
                        <p>
                            <?php 
                                $cid = $row['file_client3'];
                                
                                $stmt_client = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                $stmt_client->bind_param("i", $cid);
                                $stmt_client->execute();
                                $result_client = $stmt_client->get_result();
                                $rowc = $result_client->fetch_assoc();
                                $stmt_client->close();
                                
                                echo isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                                if(isset($row['fclient_characteristic3']) && $row['fclient_characteristic3'] !== ''){ 
                                    echo ' / ' . safe_output($row['fclient_characteristic3']); 
                                }
                            ?>
                        </p>
                        <?php 
                            }
                            if(isset($row['file_client4']) && $row['file_client4'] !== ''){
                        ?>
                        <p>
                            <?php 
                                $cid = $row['file_client4'];
                                
                                $stmt_client = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                $stmt_client->bind_param("i", $cid);
                                $stmt_client->execute();
                                $result_client = $stmt_client->get_result();
                                $rowc = $result_client->fetch_assoc();
                                $stmt_client->close();
                                
                                echo isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                                if(isset($row['fclient_characteristic4']) && $row['fclient_characteristic4'] !== ''){ 
                                    echo ' / ' . safe_output($row['fclient_characteristic4']); 
                                }
                            ?>
                        </p>
                        <?php 
                            }
                            if(isset($row['file_client5']) && $row['file_client5'] !== ''){
                        ?>
                        <p>
                            <?php 
                                $cid = $row['file_client5'];
                                
                                $stmt_client = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                $stmt_client->bind_param("i", $cid);
                                $stmt_client->execute();
                                $result_client = $stmt_client->get_result();
                                $rowc = $result_client->fetch_assoc();
                                $stmt_client->close();
                                
                                echo isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                                if(isset($row['fclient_characteristic5']) && $row['fclient_characteristic5'] !== ''){ 
                                    echo ' / ' . safe_output($row['fclient_characteristic5']); 
                                }
                            ?>
                        </p>
                        <?php }?>
                    </div>
                </div>
                
                <div class="case-description moreinps-container" class="case-description">
                    <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                        <span style="font-size: 22px"><i class='bx bxs-folder' ></i> <p>تفاصيل القضية</p></span> 
                        <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                        <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                    </button>
                    <div class="moreinps-content" id="showcasetoprint" style="display: none;">
                        <p><strong>نوع القضية :</strong> <?php echo isset($row['fcase_type']) ? safe_output($row['fcase_type']) : ''; ?></p>
                        <p>
                            <strong>رقم القضية :</strong> 
                            <?php
                                $fiddeg = $row['file_id'];         
                                $stmt_degrees = $conn->prepare("SELECT * FROM file_degrees WHERE fid = ? ORDER BY created_at DESC");
                                $stmt_degrees->bind_param("i", $fiddeg);
                                $stmt_degrees->execute();
                                $result_degs = $stmt_degrees->get_result();
                                if($result_degs->num_rows > 0){
                                    $row_degs = $result_degs->fetch_assoc();
                                    if(isset($row_degs['fid']) && $row_degs['fid'] !== ''){
                                        echo safe_output($row_degs['case_num'] . '/' . $row_degs['file_year'] . '-' . $row_degs['degree']);
                                    }
                                }
                                $stmt_degrees->close();
                            ?>
                        </p>
                        <p><strong>الموضوع :</strong> <?php echo isset($row['file_subject']) ? safe_output($row['file_subject']) : ''; ?></p>
                        <p><strong>الملاحظات :</strong> <?php echo isset($row['file_notes']) ? safe_output($row['file_notes']) : ''; ?></p>
                        <p>
                            <strong>المستشار القانوني :</strong>
                            <?php 
                                if(isset($row['flegal_advisor'])) {
                                    $fladv = $row['flegal_advisor'];
                                    $stmt_advisor = $conn->prepare("SELECT * FROM user WHERE id = ?");
                                    $stmt_advisor->bind_param("i", $fladv);
                                    $stmt_advisor->execute();
                                    $result_advisor = $stmt_advisor->get_result();
                                    $rowu = $result_advisor->fetch_assoc();
                                    $stmt_advisor->close();
                                    
                                    echo isset($rowu['name']) ? safe_output($rowu['name']) : '';
                                }
                            ?>
                        </p>
                        <p>
                            <strong>الباحث القانوني :</strong>
                            <?php 
                                if(isset($row['flegal_researcher'])) {
                                    $flrsrch = $row['flegal_researcher'];
                                    $stmt_researcher = $conn->prepare("SELECT * FROM user WHERE id = ?");
                                    $stmt_researcher->bind_param("i", $flrsrch);
                                    $stmt_researcher->execute();
                                    $result_researcher = $stmt_researcher->get_result();
                                    $rowu = $result_researcher->fetch_assoc();
                                    $stmt_researcher->close();
                                    
                                    echo isset($rowu['name']) ? safe_output($rowu['name']) : '';
                                }
                            ?>
                        </p>
                    </div>
                </div>
                
                <div class="degrees moreinps-container" class="degrees">
                    <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                        <span style="font-size: 22px"><i class='bx bxs-folder-open' ></i> <p>درجات التقاضي</p></span> 
                        <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                        <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                    </button>
                    <div class="moreinps-content" id="showdegreestoprint" style="display: none;">
                        <table width="100%" border="0" cellspacing="2" cellpadding="2">
                            <tbody>
                                <?php
                                    $stmt_degrees = $conn->prepare("SELECT * FROM file_degrees WHERE fid = ?");
                                    $stmt_degrees->bind_param("i", $fid);
                                    $stmt_degrees->execute();
                                    $resultd = $stmt_degrees->get_result();
                                    if($resultd->num_rows > 0){
                                ?>
                                <tr align="center" style="font-weight:bold" class="header_table">
                                    <td width="30%">الدرجة</td>
                                    <td width="16%" align="left">رقم القضية / </td>
                                    <td width="7%" align="right">السنة </td>
                                    <td width="18%" align="right">صفة الموكل</td>
                                    <td width="15%" align="right">صفة الخصم</td>
                                    <td width="14%">ت.م.الإدخال</td>
                                </tr>
                                
                                <?php
                                    while($rowd = $resultd->fetch_assoc()){
                                ?>
                                <tr align="center" style="background-color:#B5F3A3">
                                    <td><?php echo isset($rowd['degree']) ? safe_output($rowd['degree']) : ''; ?></td>
                                    <td align="left" style="color:#F00"><?php echo isset($rowd['case_num']) ? safe_output($rowd['case_num']) : ''; ?> /</td>
                                    <td align="right" style="color:#00F"><?php echo isset($rowd['file_year']) ? safe_output($rowd['file_year']) : ''; ?></td>
                                    <td align="right" style="color:#00F"><?php echo isset($rowd['client_characteristic']) ? safe_output($rowd['client_characteristic']) : ''; ?></td>
                                    <td align="right" style="color:#00F"><?php echo isset($rowd['opponent_characteristic']) ? safe_output($rowd['opponent_characteristic']) : ''; ?></td>
                                    <td style="color:#999; font-size:12px"><?php echo isset($rowd['timestamp']) ? safe_output($rowd['timestamp']) : ''; ?></td>
                                </tr>
                                <?php 
                                        }
                                    }
                                    $stmt_degrees->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="sessions moreinps-container" class="sessions">
                    <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                        <span style="font-size: 22px">
                            <i class='bx bxs-folder-open' ></i>
                            <p>
                                الجلسات 
                                (<font color="#FF0000">
                                    <?php 
                                    $stmt_count = $conn->prepare("SELECT COUNT(*) as counts FROM session WHERE session_fid = ?");
                                    $stmt_count->bind_param("i", $fid);
                                    $stmt_count->execute();
                                    $result_counts = $stmt_count->get_result();
                                    $row_counts = $result_counts->fetch_assoc();
                                    $stmt_count->close();
                                    $session_count = $row_counts['counts'];
                                    echo safe_output($session_count);
                                    ?>
                                </font>)
                            </p>
                        </span> 
                        <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                        <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                    </button>
                    <div class="moreinps-content" id="showsessionstoprint" style="display: none;">
                        <table width="100%" align="center" border="0" cellspacing="1" cellpadding="1"  dir="ltr" class="table" bgcolor="#FFFFFF">
                            <tr align="center" style="font-weight:bold;font-size:16px" class="header_table">
                                <td width="10%" align="center" dir="rtl">منطوق الحكم</td>
                                <td width="15%" align="center" dir="rtl">درجة التقاضي</td>
                                <td width="22%" align="center" dir="rtl">التفاصيل</td>
                                <td width="12%" align="center" dir="rtl">ت.الجلسة</td>
                            </tr>
                            <?php
                                if(isset($fid) && $fid !== ''){
                                    $stmt_sessions = $conn->prepare("SELECT * FROM session WHERE session_fid = ?");
                                    $stmt_sessions->bind_param("i", $fid);
                                    $stmt_sessions->execute();
                                    $result_sessions = $stmt_sessions->get_result();
                                    if($result_sessions->num_rows > 0){
                                        while($row_sessions = $result_sessions->fetch_assoc()){
                            ?>
                            <tr align="center" style="font-weight:normal; color:#000">
                                <td align="center" dir="rtl"><?php echo isset($row_sessions['session_trial']) ? safe_output($row_sessions['session_trial']) : ''; ?></td>
                                <td align="center" dir="rtl"><?php 
                                    if(isset($row_sessions['session_degree']) && $row_sessions['session_degree'] !== '') {
                                        echo (isset($row_sessions['case_num']) ? safe_output($row_sessions['case_num']) : '') . '/' . 
                                             (isset($row_sessions['year']) ? safe_output($row_sessions['year']) : '') . '-' . 
                                             safe_output($row_sessions['session_degree']);
                                    }
                                ?></td>
                                <td align="center" dir="rtl"><?php echo isset($row_sessions['session_details']) ? safe_output($row_sessions['session_details']) : ''; ?></td>
                                <td align="center" dir="rtl"><?php echo isset($row_sessions['session_date']) ? safe_output($row_sessions['session_date']) : ''; ?></td>
                            </tr>
                            <?php
                                        }
                                    }
                                    $stmt_sessions->close();
                                }
                            ?>
                        </table>
                    </div>
                </div>
                
                <div class="admin-works moreinps-container" class="admin-works">
                    <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                        <span style="font-size: 22px">
                            <i class='bx bx-task'></i>
                            <p>
                                المهام الادارية
                                (<font color="#FF0000">
                                    <?php 
                                        $stmt_task_count = $conn->prepare("SELECT COUNT(*) as countj FROM tasks WHERE file_no = ?");
                                        $stmt_task_count->bind_param("i", $fid);
                                        $stmt_task_count->execute();
                                        $result_countj = $stmt_task_count->get_result();
                                        $row_countj = $result_countj->fetch_assoc();
                                        $stmt_task_count->close();
                                        $job_count = $row_countj['countj'];
                                        echo safe_output($job_count);
                                    ?>
                                </font>)
                            </p>
                        </span> 
                        <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                        <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                    </button>
                    <div class="moreinps-content" id="showtaskstoprint" style="display: none;">
                        <table width="100%" align="center" border="0" cellspacing="1" cellpadding="1"  dir="ltr" class="table" bgcolor="#FFFFFF">
                            <tr align="center" style="font-weight:bold;font-size:16px" class="header_table">
                                <td width="8%" >ت.م الادخال</td>
                                <td width="7%" align="center" dir="rtl">الاهمية</td>
                                <td width="11%" align="center" dir="rtl">المهمة</td>
                                <td width="10%" align="center" dir="rtl">الباحث القانوني</td>
                                <td width="9%" align="center" dir="rtl">درجة التقاضي</td>
                                <td width="20%" align="center" dir="rtl">التفاصيل</td>
                                <td width="10%" align="center" dir="rtl">ت.التنفيذ</td>
                            </tr>
                            
                            <?php
                                if(isset($fid) && $fid !== ''){
                                    $stmt_tasks = $conn->prepare("SELECT * FROM tasks WHERE file_no = ?");
                                    $stmt_tasks->bind_param("i", $fid);
                                    $stmt_tasks->execute();
                                    $result_jobs = $stmt_tasks->get_result();
                                    if($result_jobs->num_rows > 0){
                                        while($row_jobs = $result_jobs->fetch_assoc()){
                            ?>       
                            
                            <tr style="font-weight:normal; color:#000">
                                <td align="center" style="color:#999; font-size:12px">
                                    <?php 
                                    if(isset($row_jobs['timestamp']) && $row_jobs['timestamp'] !== ''){
                                        $job_timestamp = $row_jobs['timestamp'];
                                        list($date, $time) = explode(' ', $job_timestamp);
                                        echo safe_output($date);
                                        if(isset($row_jobs['responsible']) && $row_jobs['responsible'] !== ''){
                                            $resp = $row_jobs['responsible'];
                                            $stmt_r = $conn->prepare("SELECT * FROM user WHERE id = ?");
                                            $stmt_r->bind_param("i", $resp);
                                            $stmt_r->execute();
                                            $resultr = $stmt_r->get_result();
                                            
                                            if($resultr->num_rows > 0){
                                                $rowr = $resultr->fetch_assoc();
                                                echo ' ' . (isset($rowr['name']) ? safe_output($rowr['name']) : '');
                                            }
                                            $stmt_r->close();
                                        }
                                    } else{
                                        echo ' - - ';
                                    }
                                    ?>
                                </td>
                                <td align="center" dir="rtl">
                                    <?php 
                                    if(isset($row_jobs['priority']) && $row_jobs['priority'] == 1){
                                        echo 'عاجل';
                                    } else{
                                        echo 'عادي';
                                    }
                                    ?>
                                </td>
                                <td align="center" dir="rtl">
                                    <?php 
                                    if(isset($row_jobs['task_type']) && $row_jobs['task_type'] !== ''){
                                        $ttype = $row_jobs['task_type'];
                                        $stmt_t = $conn->prepare("SELECT * FROM job_name WHERE id = ?");
                                        $stmt_t->bind_param("i", $ttype);
                                        $stmt_t->execute();
                                        $resultt = $stmt_t->get_result();
                                        
                                        if($resultt->num_rows > 0){
                                            $rowt = $resultt->fetch_assoc();
                                            echo isset($rowt['job_name']) ? safe_output($rowt['job_name']) : '';
                                        }
                                        $stmt_t->close();
                                    } else {
                                        echo ' - - ';
                                    }
                                    ?>
                                </td>
                                <td align="center" dir="rtl">
                                    <?php 
                                    if(isset($row_jobs['employee_id']) && $row_jobs['employee_id'] !== ''){
                                        $empid = $row_jobs['employee_id'];
                                        $stmt_emp = $conn->prepare("SELECT * FROM user WHERE id = ?");
                                        $stmt_emp->bind_param("i", $empid);
                                        $stmt_emp->execute();
                                        $resultemp = $stmt_emp->get_result();
                                        
                                        if($resultemp->num_rows > 0){
                                            $rowt = $resultemp->fetch_assoc();
                                            echo safe_output($rowt['name']);
                                        }
                                        $stmt_emp->close();
                                    } else{
                                        echo ' - - ';
                                    }
                                    ?>
                                </td>
                                <td align="center" dir="rtl" style="color:#000">
                                    <?php 
                                    if(isset($row_jobs['degree']) && $row_jobs['degree'] !== ''){
                                        $degree = $row_jobs['degree'];
                                        $stmt_degree = $conn->prepare("SELECT * FROM file_degrees WHERE id = ?");
                                        $stmt_degree->bind_param("i", $degree);
                                        $stmt_degree->execute();
                                        $resultt = $stmt_degree->get_result();
                                        
                                        if($resultt->num_rows > 0){
                                            $rowt = $resultt->fetch_assoc();
                                            echo safe_output($rowt['case_num'] . '/' . $rowt['file_year'] . '-' . $rowt['degree']);
                                        }
                                        $stmt_degree->close();
                                    } else{
                                        echo ' - - ';
                                    }
                                    ?>
                                </td>
                                <td align="center" dir="rtl">
                                    <?php 
                                        if(isset($row_jobs['details']) && $row_jobs['details'] !== ''){
                                            $details = $row_jobs['details'];
                                    ?>
                                    <div style="width:200px; word-wrap: break-word;"><?php echo safe_output($details)?></div>
                                    <?php
                                        } else{
                                            echo ' - - ';
                                        }
                                    ?>
                                </td>
                                <td align="center" dir="rtl">
                                    <?php 
                                    if(isset($row_jobs['duedate']) && $row_jobs['duedate'] !== ''){
                                        echo safe_output($row_jobs['duedate']);
                                    } else{
                                        echo ' - - ';
                                    }
                                    ?>
                                </td>
                            </tr>
                            
                            <?php
                                        }
                                    }
                                    $stmt_tasks->close();
                                }
                            ?>
                        </table>
                    </div>
                </div>
                
                <div class="attachments moreinps-container" class="attachments">
                    <?php
                        $stmt_attachments = $conn->prepare("SELECT * FROM files_attachments WHERE fid = ?");
                        $stmt_attachments->bind_param("i", $fid);
                        $stmt_attachments->execute();
                        $result_attachments = $stmt_attachments->get_result();
                        $stmt_attachments->close();
                    ?>
                    <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                        <span style="font-size: 22px">
                            <i class='bx bxs-camera' ></i>
                            <p>
                                المرفقات
                                (<font color="#FF0000">
                                    <?php
                                        $stmt_countatts = $conn->prepare("SELECT COUNT(*) as countatts FROM files_attachments WHERE fid = ?");
                                        $stmt_countatts->bind_param("i", $fid);
                                        $stmt_countatts->execute();
                                        $result_countatts = $stmt_countatts->get_result();
                                        $row_countatts = $result_countatts->fetch_assoc();
                                        $stmt_countatts->close();
                                        echo isset($row_countatts['countatts']) ? safe_output($row_countatts['countatts']) : '0';
                                    ?>
                                </font>)
                            </p>
                        </span> 
                        <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                        <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                    </button>
                    <div class="moreinps-content" id="showattachmentstoprint" style="display: none;">     
                        <?php 
                            if($result_attachments->num_rows > 0){
                                while($rowatts = $result_attachments->fetch_assoc()){
                                    if($rowatts['attachment'] !== ''){
                        ?>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); padding: 15px; border-bottom: 1px solid #00000140">
                            <a href="<?php echo isset($rowatts['attachment']) ? safe_output($rowatts['attachment']) : '#'; ?>" onClick="window.open('<?php echo isset($rowatts['attachment']) ? safe_output($rowatts['attachment']) : '#'; ?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                <?php echo isset($rowatts['attachment']) ? safe_output(basename($rowatts['attachment'])) : ''; ?>
                            </a>
                            <div style="text-align: center;">
                                <img src="img/<?php echo isset($rowatts['attachment_type']) ? safe_output($rowatts['attachment_type']) : ''; ?>.png" alt="<?php echo isset($rowatts['attachment_type']) ? safe_output($rowatts['attachment_type']) : ''; ?>" width="30px" height="30px">
                                <?php echo isset($rowatts['attachment_size']) ? safe_output($rowatts['attachment_size']) : ''; ?>
                            </div>
                            <div style="text-align: center;">
                                <?php echo isset($rowatts['timestamp']) ? safe_output($rowatts['timestamp']) : ''; ?>
                                <br>
                                <?php
                                    if(isset($rowatts['done_by'])) {
                                        $empid = $rowatts['done_by'];
                                        $stmt_emp_details = $conn->prepare("SELECT * FROM user WHERE id = ?");
                                        $stmt_emp_details->bind_param("i", $empid);
                                        $stmt_emp_details->execute();
                                        $resultempfat = $stmt_emp_details->get_result();
                                        if($resultempfat->num_rows > 0) {
                                            $rowempfat = $resultempfat->fetch_assoc();
                                            echo isset($rowempfat['name']) ? safe_output($rowempfat['name']) : '';
                                        }
                                        $stmt_emp_details->close();
                                    }
                                ?>
                            </div>
                        </div>
                        <?php 
                                    }
                                }
                            }
                            $stmt_documents = $conn->prepare("SELECT * FROM case_document WHERE dfile_no = ?");
                            $stmt_documents->bind_param("i", $fid);
                            $stmt_documents->execute();
                            $result_documents = $stmt_documents->get_result();
                            if($result_documents->num_rows > 0){
                                while($rowdocs = $result_documents->fetch_assoc()){
                        ?>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); padding: 15px; border-bottom: 1px solid #00000140">
                            <a href="#" onClick="window.open('AddNotes.php?fno=<?php echo isset($rowdocs['dfile_no']) ? safe_output($rowdocs['dfile_no']) : ''; ?>&id=<?php echo isset($rowdocs['did']) ? safe_output($rowdocs['did']) : ''; ?>&edit=1', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                <?php echo isset($rowdocs['document_subject']) ? safe_output($rowdocs['document_subject']) : ''; ?>
                            </a>
                            <div style="text-align: center;">
                                <img src="img/doc.png" alt="document" width="30px" height="30px">
                                مذكرة
                            </div>
                            <div style="text-align: center;">
                                <?php echo isset($rowdocs['document_date']) ? safe_output($rowdocs['document_date']) : ''; ?>
                            </div>
                        </div>
                        <?php 
                                }
                            }
                            $stmt_documents->close();
                        ?>
                    </div>
                </div>
            </main>
        </div>
        <?php } $stmt->close();?>
        <script src="js/toggleSection.js"></script>
        <?php }?>
    </body>
</html>

<script>
    function printContent(id) {
    let content = document.getElementById(id).innerHTML;
    let newwin = window.open('', 'printwin', 'left=10,top=10,width=500,height=500');
    
    // HTML for the new window
    let htmlContent = `
        <!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <title>Print Page</title>
            <link href="css/sites.css" rel="stylesheet">
            <link href="css/styles.css" rel="stylesheet">
            <script>
                function chkstate() {
                    if (document.readyState === "complete") {
                        window.close();
                    } else {
                        setTimeout(chkstate, 2000);
                    }
                }
                function print_win() {
                    window.print();
                    chkstate();
                }`;
    
    
    newwin.document.open();
    newwin.document.write(htmlContent);
    newwin.document.close();
}
</script>

<script>
    function showPrint() {
        document.getElementById('showclientstoprint').style.display='block'; 
        document.getElementById('showcasetoprint').style.display='block'; 
        document.getElementById('showsessionstoprint').style.display='block'; 
        document.getElementById('showdegreestoprint').style.display='block'; 
        document.getElementById('showtaskstoprint').style.display='block'; 
        document.getElementById('showattachmentstoprint').style.display='block'; 
        document.getElementById('printbtn').style.display='none'; 
        print();
    }
</script>