<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'safe_output.php';
    include_once 'AES256.php';
    
    $current_page = basename($_SERVER['PHP_SELF']);
    $current_page = strtolower(strtok($current_page, '?'));
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
        <link href="css/hrstyle.css" rel="stylesheet">
        <link rel="SHORTCUT ICON" href="img/favicon.ico">
        <style>
            .progress-wrapper {
                font-family: 'Segoe UI', sans-serif;
                width: 100%;
                margin: 15px 0;
            }
            
            .progress-title {
                display: flex;
                justify-content: space-between;
                font-size: 14px;
                margin-bottom: 6px;
                color: #333;
            }
            
            .progress-bar-container {
                background-color: #e0e0e0;
                border-radius: 30px;
                height: 22px;
                position: relative;
                overflow: hidden;
            }
            
            .progress-bar-fill {
                height: 100%;
                width: 0%;
                transition: width 0.6s ease-in-out;
                display: flex;
                align-items: center;
                justify-content: flex-end;
                border-radius: 30px;
                padding-right: 10px;
                color: white;
                font-weight: bold;
            }
            
            .progress-label {
                padding-left: 15px;
                font-size: 13px;
            }
            .stepinfo {
                display: none;
            }
        </style>
    </head>
    <body>
        <?php
            $myid = $_SESSION['id'];
            $stmtme = $conn->prepare("SELECT * FROM user WHERE id=?");
            $stmtme->bind_param("i", $myid);
            $stmtme->execute();
            $resultme = $stmtme->get_result();
            $rowme = $resultme->fetch_assoc();
            $stmtme->close();
        ?>
        <div class="website">
            <?php include_once 'sidebar.php';?>
            <div class="<?php if(!isset($_GET['id'])){ echo 'web-page-grid'; } else if(isset($_GET['id']) && $_GET['id'] !== ''){ echo 'web-page-grid2'; }?>">
                <div class="user-header-grid">
                    <p style="font-size: 25px">
                        <i class='bx bx-user blue-parag'>
                            <font style="padding-right: 10px; color: #667078; cursor: pointer;" onclick="location.href='mbhEmps.php?section=users';">
                                المستخدمين
                            </font>
                        </i>
                    </p>
                    <p></p>
                    <div class="user-page-btns">
                        <?php if(isset($rowme['personal_image']) && $rowme['personal_image'] !== ''){?>
                        <div class="rounded-profile-image" style="background-image: url('<?php echo safe_output($rowme['personal_image']);?>');"></div>
                        <?php } else{?>
                        <div class="rounded-noprofimg">
                            <?php
                                $name = trim($rowme['name']);
                                $nameParts = explode(" ", $name);
                                
                                $firstname = $nameParts[0] ?? '';
                                $lastname = $nameParts[1] ?? '';
                                
                                $firstLetterFirst = mb_substr($firstname, 0, 1, "UTF-8");
                                $firstLetterLast = mb_substr($lastname, 0, 1, "UTF-8");
                                
                                $arabicToLatin = [
                                    'ا' => 'A', 'أ' => 'A', 'إ' => 'A', 'آ' => 'A',
                                    'ب' => 'B',
                                    'ت' => 'T',
                                    'ث' => 'T',
                                    'ج' => 'J',
                                    'ح' => 'H',
                                    'خ' => 'K',
                                    'د' => 'D',
                                    'ذ' => 'D',
                                    'ر' => 'R',
                                    'ز' => 'Z',
                                    'س' => 'S',
                                    'ش' => 'S',
                                    'ص' => 'S',
                                    'ض' => 'D',
                                    'ط' => 'T',
                                    'ظ' => 'Z',
                                    'ع' => 'A',
                                    'غ' => 'G',
                                    'ف' => 'F',
                                    'ق' => 'Q',
                                    'ك' => 'K',
                                    'ل' => 'L',
                                    'م' => 'M',
                                    'ن' => 'N',
                                    'ه' => 'H',
                                    'و' => 'W',
                                    'ي' => 'Y',
                                ];
                                
                                $convertedFirst = $arabicToLatin[$firstLetterFirst] ?? strtoupper($firstLetterFirst);
                                $convertedLast = $arabicToLatin[$firstLetterLast] ?? strtoupper($firstLetterLast);
                                
                                echo safe_output($convertedFirst.$convertedLast);
                            ?>
                        </div>
                        <?php }?>
                        <div class="user-header-btn blue-parag header-btn" onclick="toggleSidebar()">
                            <i class="bx bx-menu btn-icon"></i>
                            <p class="btn-parag"> القائمة الجانبية</p>
                        </div>
                        <div class="user-header-btn blue-parag header-btn" style="padding: 0 10px; cursor: default;">
                            <i class='bx bxs-home header-main-btn' onclick="window.open('index.php', '_blank');"></i>
                            <i class='bx bx-log-out header-main-btn' onclick="location.href='logout.php';"></i>
                        </div>
                    </div>
                </div>
                <?php
                    if((!isset($_GET['empadd']) || $_GET['empadd'] !== '1') && (!isset($_GET['empid']) || $_GET['empid'] === '') && (!isset($_GET['edit']) || $_GET['edit'] !== '1')){
                        if($row_permcheck['emp_perms_read'] == 1){
                ?>
                <div class="users-sections">
                    <div class="sections-grid">
                        <div class="users-section <?php if($_GET['section'] === 'users'){ echo 'active-section'; }?>" onclick="location.href='mbhEmps.php?section=users';">
                            المستخدمين
                            <?php
                                $one = 1;
                                $stmt_count = $conn->prepare("SELECT COUNT(*) as count_all FROM user WHERE signin_perm = ?");
                                $stmt_count->bind_param("i", $one);
                                $stmt_count->execute();
                                $result_count = $stmt_count->get_result();
                                $row_count = $result_count->fetch_assoc();
                                $stmt_count->close();
                                echo '( '.safe_output($row_count['count_all']).' )';
                            ?>
                        </div>
                        <div class="users-section <?php if($_GET['section'] === 'permissions'){ echo 'active-section'; }?>" onclick="location.href='mbhEmps.php?section=permissions';">
                            الصلاحيات
                            <?php
                                $one = 1;
                                $stmt_count = $conn->prepare("SELECT COUNT(*) as count_all FROM user WHERE signin_perm = ?");
                                $stmt_count->bind_param("i", $one);
                                $stmt_count->execute();
                                $result_count = $stmt_count->get_result();
                                $row_count = $result_count->fetch_assoc();
                                $stmt_count->close();
                                echo '( '.safe_output($row_count['count_all']).' )';
                            ?>
                        </div>
                        <div class="users-section <?php if($_GET['section'] === 'archived'){ echo 'active-section'; }?>" onclick="location.href='mbhEmps.php?section=archived';">
                            المستخدمين الموقوفين
                            <?php
                                $one = 1;
                                $stmt_count = $conn->prepare("SELECT COUNT(*) as count_archived FROM user WHERE signin_perm != ?");
                                $stmt_count->bind_param("i", $one);
                                $stmt_count->execute();
                                $result_count = $stmt_count->get_result();
                                $row_count = $result_count->fetch_assoc();
                                $stmt_count->close();
                                echo '( '.safe_output($row_count['count_archived']).' )';
                            ?>
                        </div>
                    </div>
                </div>
                <div class="table-section">
                    <table id="myTable" style="background-color: #fff; width: 100%;">
                        <thead>
                            <tr style="background-color: #fff; position: sticky; top: 0; z-index: 3;">
                                <td colspan="3" align="center">
                                    <?php if($row_permcheck['emp_perms_add'] == 1){?>
                                    <button class="add-btn" type="button" onclick="location.href='mbhEmps.php?empadd=1';">
                                        <p class="add-user-parag">اضافة مستخدم</p>
                                        <img class="add-user-img" src="img/add-button.png" width="25px" height="25px" loading="lazy">
                                    </button>
                                    <?php }?>
                                </td>
                                <td colspan="5">
                                    <div class="input-container">
                                        <div class="search-box" style="padding: 10px">
                                            <input type="text" id="SearchBox" placeholder="البحث">
                                            <span class="search-icon"><i class='bx bx-search'></i></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr class="infotable-header" style="position: sticky; top: 85px; z-index: 3; background-color: #125386; border-radius: 0 5px 5px 0; color: #fff;">
                                <th style="border-radius: 0 10px 10px 0;">
                                    <p style="padding: 10px;">الاسم</p>
                                </th>
                                <th style="text-align: center;">الرقم الوظيفي</th>
                                <th style="text-align: center;">المهنة</th>
                                <?php if($_GET['section'] === 'users' || $_GET['section'] === 'archived'){?>
                                <th style="text-align: center;">تاريخ اول يوم عمل</th>
                                <th style="text-align: center;">القسم</th>
                                <th style="text-align: center;">تاريخ تفعيل الحساب</th>
                                <th style="text-align: center;">تاريخ اخر دخول</th>
                                <?php } if($_GET['section'] === 'permissions'){?>
                                <th style="text-align: center;">الصلاحيات</th>
                                <th style="text-align: center;">اداري</th>
                                <?php } if($_GET['section'] === 'permissions' || $_GET['section'] === 'archived'){?>
                                <th style="text-align: center;">صلاحية الدخول</th>
                                <?php }?>
                                <th style="text-align: center; <?php if($_GET['section'] === 'users' || $_GET['section'] === 'permissions' || !isset($_GET['section'])){ echo ' border-radius: 10px 0 0 10px;'; }?>">فتح بواسطة</th>
                                <?php if($_GET['section'] === 'archived'){?>
                                <th style="text-align: center;">تاريخ اغلاق الحساب</th>
                                <th style="text-align: center; <?php if($_GET['section'] === 'archived'){ echo ' border-radius: 10px 0 0 10px;'; }?>">اغلق بواسطة</th>
                                <?php }?>
                            </tr>
                        </thead>
                        
                        <?php
                            $section = $_GET['section'];
                            if($section === 'users' || $section === 'permissions'){
                                $one = 1;
                                $stmt = $conn->prepare("SELECT * FROM user WHERE signin_perm=?");
                                $stmt->bind_param("i", $one);
                            } else if($section === 'archived'){
                                $zero = 0;
                                $stmt = $conn->prepare("SELECT * FROM user WHERE signin_perm=?");
                                $stmt->bind_param("i", $zero);
                            } else{
                                $stmt = $conn->prepare("SELECT * FROM user");
                            }
                            $stmt->execute();
                            $result = $stmt->get_result();
                        ?>
                        <tbody id="table1">
                            <?php 
                                if($result->num_rows > 0){
                                    while($row = $result->fetch_assoc()){
                            ?>
                            <tr class="infotable-body">
                                <td style="width: fit-content; text-align: center; cursor: pointer;" onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($row['id']);?>&empsection=data-management';">
                                    <p class="blue-parag" style="padding: 8px;">
                                        <?php echo safe_output($row['name']);?>
                                    </p>
                                </td>
                                <td style="text-align: center; cursor: pointer;" onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($row['id']);?>&empsection=data-management';">
                                    <p class="blue-parag">
                                        <?php echo safe_output($row['id']);?>
                                    </p>
                                </td>
                                <td style="text-align: center;">
                                    <?php 
                                        $job_title =$row['job_title'];
                                        
                                        $stmtjt = $conn->prepare("SELECT * FROM positions WHERE id=?");
                                        $stmtjt->bind_param("i", $job_title);
                                        $stmtjt->execute();
                                        $resultjt = $stmtjt->get_result();
                                        $rowjt = $resultjt->fetch_assoc();
                                        $stmtjt->close();
                                        
                                        echo safe_output($rowjt['position_name']);
                                    ?>
                                </td>
                                <?php if($_GET['section'] === 'users' || $_GET['section'] === 'archived'){?>
                                <td style="text-align: center;">
                                    <?php echo safe_output($row['app_date']);?>
                                </td>
                                <td style="text-align: center;">
                                    <?php echo safe_output($row['section']);?>
                                </td>
                                <td style="text-align: center;">
                                    <?php echo safe_output($row['activation_date']);?>
                                </td>
                                <td style="text-align: center;">
                                    <?php 
                                        $lastlogin = $row['lastlogin'];
                                        list($type, $time, $date) = explode(" ", $lastlogin);
                                        echo safe_output($date);
                                    ?>
                                </td>
                                <?php } if($_GET['section'] === 'permissions'){?>
                                <td style="text-align: center;">
                                    <?php
                                        $permFields = [
                                            'emp_perms_read',
                                            'emp_perms_add',
                                            'emp_perms_edit',
                                            'emp_perms_delete',
                                            'cfiles_rperm',
                                            'cfiles_aperm',
                                            'cfiles_eperm',
                                            'cfiles_dperm',
                                            'cfiles_archive_perm',
                                            'session_rperm',
                                            'session_aperm',
                                            'session_eperm',
                                            'session_dperm',
                                            'degree_rperm',
                                            'degree_aperm',
                                            'degree_dperm',
                                            'note_rperm',
                                            'note_aperm',
                                            'note_eperm',
                                            'note_dperm',
                                            'attachments_dperm',
                                            'admjobs_rperm',
                                            'admjobs_aperm',
                                            'admjobs_eperm',
                                            'admjobs_dperm',
                                            'admjobs_pperm',
                                            'admprivjobs_rperm',
                                            'clients_rperm',
                                            'clients_aperm',
                                            'clients_eperm',
                                            'clients_dperm',
                                            'sessionrole_rperm',
                                            'call_rperm',
                                            'call_aperm',
                                            'call_eperm',
                                            'call_dperm',
                                            'csched_rperm',
                                            'csched_aperm',
                                            'csched_eperm',
                                            'csched_dperm',
                                            'goaml_rperm',
                                            'goaml_aperm',
                                            'goaml_dperm',
                                            'terror_rperm',
                                            'terror_eperm',
                                            'terror_dperm',
                                            'cons_rperm',
                                            'cons_aperm',
                                            'cons_eperm',
                                            'cons_dperm',
                                            'agr_rperm',
                                            'agr_aperm',
                                            'agr_eperm',
                                            'agr_dperm',
                                            'accfinance_rperm',
                                            'accfinance_eperm',
                                            'accmainterms_rperm',
                                            'accmainterms_aperm',
                                            'accmainterms_eperm',
                                            'accmainterms_dperm',
                                            'accsecterms_rperm',
                                            'accsecterms_aperm',
                                            'accsecterms_eperm',
                                            'accsecterms_dperm',
                                            'accbankaccs_rperm',
                                            'accbankaccs_aperm',
                                            'accbankaccs_eperm',
                                            'accbankaccs_dperm',
                                            'acccasecost_rperm',
                                            'acccasecost_aperm',
                                            'accrevenues_rperm',
                                            'accrevenues_aperm',
                                            'accrevenues_dperm',
                                            'accexpenses_rperm',
                                            'accexpenses_aperm',
                                            'accexpenses_dperm',
                                            'vacf_aperm',
                                            'vacl_aperm',
                                            'vac_dperm',
                                            'logs_rperm',
                                            'logs_dperm',
                                            'emp_rperm',
                                            'emp_aperm',
                                            'emp_eperm',
                                            'emp_dperm',
                                            'doc_faperm',
                                            'doc_laperm',
                                            'secretf_aperm',
                                            'judicialwarn_rperm',
                                            'judicialwarn_aperm',
                                            'judicialwarn_eperm',
                                            'judicialwarn_dperm',
                                            'petition_rperm',
                                            'petition_aperm',
                                            'petition_eperm',
                                            'petition_dperm',
                                            'levels_eperm',
                                            'selectors_rperm',
                                            'selectors_aperm',
                                            'selectors_eperm',
                                            'selectors_dperm',
                                            'workingtime_rperm',
                                            'workingtime_aperm',
                                            'workingtime_eperm',
                                            'workingtime_dperm',
                                            'rate_rperm',
                                            'rate_aperm',
                                            'rate_eperm',
                                            'rate_dperm'
                                        ];
                                        
                                        $count = 0;
                                        foreach ($permFields as $field) {
                                            if (!empty($row[$field])) {
                                                $count++;
                                            }
                                        }
                                    ?>
                                    <font style="font-size: 14px; color: #676767">
                                    <?php
                                        if($count == 0){
                                            echo 'لا يوجد';
                                        } else {
                                            if($count > 99){
                                                echo '99+';
                                            } else{
                                                echo safe_output($count);
                                            }
                                        }
                                    ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php if($owner == 1){?>
                                    <label class="switch">
                                        <input name="admin" type="checkbox" <?php if($row['admin'] == 1){ echo 'checked'; }?> onchange="location.href='perms_change.php?id=<?php echo safe_output($row['id']);?>&admin=<?php echo safe_output($row['admin']);?>';">
                                        <span class="slider"></span>
                                    </label>
                                    <?php 
                                        } else{
                                            if($row['admin'] == 1){
                                    ?>
                                    <img src="img/verification.png" height="20px" width="20px" loading="lazy">
                                    <?php } else{?>
                                    <img src="img/remove.png" height="20px" width="20px" loading="lazy">
                                    <?php
                                            }
                                        }
                                    ?>
                                </td>
                                <?php } if($_GET['section'] === 'permissions' || $_GET['section'] === 'archived'){?>
                                <td style="text-align: center;">
                                    <?php if($admin == 1 && $row['id'] !== $ownerid){?>
                                    <label class="switch">
                                        <input name="signin_perm" type="checkbox" <?php if($row['signin_perm'] == 1){ echo 'checked'; }?> onchange="location.href='perms_change.php?id=<?php echo safe_output($row['id']);?>&signin_perm=<?php echo safe_output($row['signin_perm']);?>&queryString=<?php echo safe_output($_GET['section']);?>';">
                                        <span class="slider"></span>
                                    </label>
                                    <?php 
                                        } else{
                                            if($row['signin_perm'] == 1){
                                    ?>
                                    <img src="img/verification.png" height="20px" width="20px" loading="lazy">
                                    <?php } else{?>
                                    <img src="img/remove.png" height="20px" width="20px" loading="lazy">
                                    <?php
                                            }
                                        }
                                    ?>
                                </td>
                                <?php }?>
                                <td style="text-align: center;">
                                    <p style="color: #67676790; font-size: 14px">
                                        <?php 
                                            $opened_by = $row['opened_by'];
                                            $stmtu = $conn->prepare("SELECT * FROM user WHERE id=?");
                                            $stmtu->bind_param("i", $opened_by);
                                            $stmtu->execute();
                                            $resultu = $stmtu->get_result();
                                            $rowu = $resultu->fetch_assoc();
                                            $stmtu->close();
                                            echo safe_output($rowu['name']);
                                        ?>
                                    </p>
                                </td>
                                <?php if($_GET['section'] === 'archived'){?>
                                <td style="text-align: center;">
                                    <?php echo safe_output($row['archived_date']);?>
                                </td>
                                <td style="text-align: center;">
                                    <p style="color: #67676790; font-size: 14px">
                                        <?php 
                                            $closed_by = $row['closed_by'];
                                            $stmtu = $conn->prepare("SELECT * FROM user WHERE id=?");
                                            $stmtu->bind_param("i", $closed_by);
                                            $stmtu->execute();
                                            $resultu = $stmtu->get_result();
                                            $rowu = $resultu->fetch_assoc();
                                            $stmtu->close();
                                            echo safe_output($rowu['name']);
                                        ?>
                                    </p>
                                </td>
                                <?php }?>
                            </tr>
                            <?php 
                                    }
                                }
                                $stmt->close();
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-footer">
                    <div id="pagination"></div>
                    <p></p>
                    <div id="pageInfo"></div>
                </div>
                <?php 
                        }
                    } else if((isset($_GET['empid']) && $_GET['empid'] !== '') && (!isset($_GET['empadd']) || $_GET['empadd'] !== '1')){
                        if($row_permcheck['emp_perms_read'] == 1 || $_GET['empid'] === $myid){
                ?>
                <div class="empinfo-container">
                    <div class="empinfo-table">
                        <div class="empinfo-header">
                            <p onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($_GET['empid']);?>&empsection=<?php echo 'data-management';?>';" <?php if($_GET['empsection'] === 'data-management'){ echo 'class="active-empsection"'; }?>>إدارة البيانات</p>
                            <?php if($row_permcheck['useratts_eperm'] == 1 || $row_permcheck['useratts_dperm'] == 1 ||$_GET['empid'] === $myid){?>
                            <p onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($_GET['empid']);?>&empsection=<?php echo 'attachments';?>';" <?php if($_GET['empsection'] === 'attachments'){ echo 'class="active-empsection"'; }?>>المستندات و الوثائق</p>
                            <?php } if($row_permcheck['userattendance_rperm'] || $row_permcheck['vacf_aperm'] || $row_permcheck['vacl_aperm'] || $row_permcheck['discounts_rperm'] == 1 || $_GET['empid'] === $myid){?>
                            <p onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($_GET['empid']);?>&empsection=<?php echo 'time-management';?>';" <?php if($_GET['empsection'] === 'time-management'){ echo 'class="active-empsection"'; }?>>الحضور و الغياب</p>
                            <?php } if($row_permcheck['trainings_rperm'] == 1 || $row_permcheck['warnings_rperm'] == 1 || $row_permcheck['rate_rperm'] == 1 || $_GET['empid'] === $myid){?>
                            <p onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($_GET['empid']);?>&empsection=<?php echo 'rating-management';?>';" <?php if($_GET['empsection'] === 'rating-management'){ echo 'class="active-empsection"'; }?>>الأداء و التقييمات</p>
                            <?php } if($row_permcheck['vacf_aperm'] == 1 || $row_permcheck['vacl_aperm'] == 1 || $_GET['empid'] === $myid){?>
                            <p onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($_GET['empid']);?>&empsection=<?php echo 'requests';?>';" <?php if($_GET['empsection'] === 'requests'){ echo 'class="active-empsection"'; }?>>الطلبات</p>
                            <?php }?>
                        </div>
                        <div class="empinfo-body">
                            <?php 
                                if(isset($_GET['empsection']) && $_GET['empsection'] === 'data-management'){
                                    if($row_permcheck['emp_perms_read'] == 1 || $_GET['empid'] === $myid){
                                        if($row_permcheck['emp_perms_edit'] == 1){
                            ?>
                            <div class="listed_link" onclick="location.href='mbhEmps.php?id=<?php echo safe_output($_GET['empid']);?>&edit=1';">
                                تعديل بيانات المستخدم
                            </div>
                            <?php 
                                }
                                $idus = $_GET['empid'];
                                $stmtus = $conn->prepare("SELECT * FROM user WHERE id=?");
                                $stmtus->bind_param("i", $idus);
                                $stmtus->execute();
                                $resultus = $stmtus->get_result();
                                $rowus = $resultus->fetch_assoc();
                                $stmtus->close();
                            ?>
                            <div class="moreinps-container">
                                <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                    <span><i class='bx bxs-user' ></i> <p>حساب المستخدم</p></span> 
                                    <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                    <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                </button>
                                <div class="moreinps-content" id="contactInfo" id="fileUploadSection" <?php if(isset($_GET['showpassword']) && $_GET['showpassword'] === '1'){ echo 'style="display: block;"'; }?>>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>اسم الدخول : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['username']); ?></p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>
                                                <?php if(isset($_GET['showpassword'])){?> 
                                                    <i class='bx bx-hide' style="color: #3c88cc; font-size: 20px; cursor: pointer;" onclick="location.href='<?php if(isset($_GET['showpassword'])){ echo 'mbhEmps.php?empid='.safe_output($_GET['empid']).'&empsection=data-management'; } else{ echo 'mbhEmps.php?empid='.safe_output($_GET['empid']).'&empsection=data-management&showpassword=1'; }?>';"></i>
                                                <?php } else{?> 
                                                    <i class='bx bx-show' style="color: #3c88cc; font-size: 20px; cursor: pointer;" onclick="location.href='<?php if(isset($_GET['showpassword'])){ echo 'mbhEmps.php?empid='.safe_output($_GET['empid']).'&empsection=data-management'; } else{ echo 'mbhEmps.php?empid='.safe_output($_GET['empid']).'&empsection=data-management&showpassword=1'; }?>';"></i>
                                                <?php }?>
                                                كلمة المرور : 
                                            </p>
                                            <p class="profile-information">
                                                <?php 
                                                    if(isset($_GET['showpassword'])){
                                                        $password = $rowus['password'];
                                                        $decrypted_password = openssl_decrypt($password, $cipher, $key, $options, $iv);
                                                        echo safe_output($decrypted_password);
                                                    } else{
                                                        echo '********';
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>عدد مرات الدخول : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['logins_num']); ?></p>
                                        </div>
                                        <p></p>
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                            <div class="moreinps-container">
                                <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                    <span><i class='bx bxs-user' ></i> <p>المعلومات العامة</p></span> 
                                    <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                    <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                </button>
                                <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>الاسم الكامل : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['name']); ?></p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>الرقم الوظيفي : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['id']); ?></p>
                                        </div>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>الجنسية : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['nationality']); ?></p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>تاريخ الميلاد : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['dob']); ?></p>
                                        </div>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>الجنس : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['sex']); ?></p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>الحالة الاجتماعية : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['social']); ?></p>
                                        </div>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>الهاتف المتحرك : </p>
                                            <p class="profile-information">
                                                <?php
                                                    $tel1 = $rowus['tel1'];
                                                    $decrypted_tel1 = openssl_decrypt($tel1, $cipher, $key, $options, $iv);
                                                    echo safe_output($decrypted_tel1);
                                                ?>
                                            </p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>عنوان السكن : </p>
                                            <p class="profile-information">
                                                <?php
                                                    $address = $rowus['address'];
                                                    $decrypted_address = openssl_decrypt($address, $cipher, $key, $options, $iv);
                                                    echo safe_output($decrypted_address);
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="moreinps-container">
                                <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                    <span><i class='bx bxs-briefcase' ></i> <p>المعلومات الوظيفية</p></span> 
                                    <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                    <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                </button>
                                <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>تاريخ الانضمام : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['app_date']); ?></p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>المسمى الوظيفي : </p>
                                            <p class="profile-information">
                                                <?php 
                                                    $jt = $rowus['job_title'];
                                                    $stmtjt = $conn->prepare("SELECT * FROM positions WHERE id=?");
                                                    $stmtjt->bind_param("i", $jt);
                                                    $stmtjt->execute();
                                                    $resultjt = $stmtjt->get_result();
                                                    $rowjt = $resultjt->fetch_assoc();
                                                    $stmtjt->close();
                                                    echo safe_output($rowjt['position_name']);
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>القسم : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['section']); ?></p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>الفرع : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['work_place']); ?></p>
                                        </div>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>الراتب الشهري : </p>
                                            <p class="profile-information">
                                                <?php 
                                                    $total_salary = $rowus['basic_salary']+$rowus['travel_tickets']+$rowus['oil_cost']+$rowus['housing_cost']+$rowus['living_cost'];
                                                    echo safe_output($total_salary).' AED';
                                                ?>
                                            </p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>نوع العقد : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['app_type']); ?></p>
                                        </div>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>تاريخ انتهاء عقد العمل : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['contract_exp']); ?></p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>المسؤول المباشر : </p>
                                            <p class="profile-information">
                                                <?php
                                                    $responsible = $rowus['responsible'];
                                                    
                                                    $stmtru = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                    $stmtru->bind_param("i", $responsible);
                                                    $stmtru->execute();
                                                    $resultru = $stmtru->get_result();
                                                    $rowru = $resultru->fetch_assoc();
                                                    $stmtru->close();
                                                    echo safe_output($rowru['name']);
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>حالة الاقامة : </p>
                                            <p class="profile-information">
                                                <?php
                                                    if(isset($rowus['residence_exp']) && $rowus['residence_exp'] !== ''){
                                                        $today = date("Y-m-d");
                                                        $residence_exp = $rowus['residence_exp'];
                                                        
                                                        $future_date = new DateTime($residence_exp);
                                                        $future_date->modify('-10 days');
                                                        $formatted_ten = $future_date->format("Y-m-d");
                                                        
                                                        if($today >= $formatted_ten && $today < $residence_exp){
                                                            echo 'سارية حتى ' . safe_output($residence_exp) . '<br><font color="#ff0000" class="blink">(متبقي اقل من 10 ايام للانتهاء)</font>';
                                                        } else if($today === $residence_exp){
                                                            echo 'سارية حتى ' . safe_output($residence_exp) . '<br><font color="#ff0000" class="blink">(اخر يوم)</font>';
                                                        } else if($today > $residence_exp){
                                                            echo '<font color=#ff0000">منتهية بتاريخ ' . safe_output($residence_exp) . '</font>';
                                                        } else{
                                                            echo 'سارية حتى ' . safe_output($residence_exp);
                                                        }
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>تاريخ انتهاء قيد المندوب : </p>
                                            <p class="profile-information">
                                                <?php
                                                    if(isset($rowus['representative_exp']) && $rowus['representative_exp'] !== ''){
                                                        $today = date("Y-m-d");
                                                        $representative_exp = $rowus['representative_exp'];
                                                        
                                                        $future_date = new DateTime($representative_exp);
                                                        $future_date->modify('-10 days');
                                                        $formatted_ten = $future_date->format("Y-m-d");
                                                        
                                                        if($today >= $formatted_ten && $today < $representative_exp){
                                                            echo 'سارية حتى ' . safe_output($representative_exp) . '<br><font color="#ff0000" class="blink">(متبقي اقل من 10 ايام للانتهاء)</font>';
                                                        } else if($today === $representative_exp){
                                                            echo 'سارية حتى ' . safe_output($representative_exp) . '<br><font color="#ff0000" class="blink">(اخر يوم)</font>';
                                                        } else if($today > $representative_exp){
                                                            echo '<font color=#ff0000">منتهية بتاريخ ' . safe_output($representative_exp) . '</font>';
                                                        } else{
                                                            echo 'سارية حتى ' . safe_output($representative_exp);
                                                        }
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>تاريخ انتهاء قيد المحامي / دبي : </p>
                                            <p class="profile-information">
                                                <?php
                                                    if(isset($rowus['dxblaw_exp']) && $rowus['dxblaw_exp'] !== ''){
                                                        $today = date("Y-m-d");
                                                        $dxb_exp = $rowus['dxblaw_exp'];
                                                        
                                                        $future_date = new DateTime($dxb_exp);
                                                        $future_date->modify('-10 days');
                                                        $formatted_ten = $future_date->format("Y-m-d");
                                                        
                                                        if($today >= $formatted_ten && $today < $dxb_exp){
                                                            echo 'سارية حتى ' . safe_output($dxb_exp) . '<br><font color="#ff0000" class="blink">(متبقي اقل من 10 ايام للانتهاء)</font>';
                                                        } else if($today === $dxb_exp){
                                                            echo 'سارية حتى ' . safe_output($dxb_exp) . '<br><font color="#ff0000" class="blink">(اخر يوم)</font>';
                                                        } else if($today > $dxb_exp){
                                                            echo '<font color=#ff0000">منتهية بتاريخ ' . safe_output($dxb_exp) . '</font>';
                                                        } else{
                                                            echo 'سارية حتى ' . safe_output($dxb_exp);
                                                        }
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>تاريخ انتهاء قيد المحامي / الشارقة : </p>
                                            <p class="profile-information">
                                                <?php
                                                    if(isset($rowus['shjlaw_exp']) && $rowus['shjlaw_exp'] !== ''){
                                                        $today = date("Y-m-d");
                                                        $shj_exp = $rowus['shjlaw_exp'];
                                                        
                                                        $future_date = new DateTime($shj_exp);
                                                        $future_date->modify('-10 days');
                                                        $formatted_ten = $future_date->format("Y-m-d");
                                                        
                                                        if($today >= $formatted_ten && $today < $shj_exp){
                                                            echo 'سارية حتى ' . safe_output($shj_exp) . '<br><font color="#ff0000" class="blink">(متبقي اقل من 10 ايام للانتهاء)</font>';
                                                        } else if($today === $shj_exp){
                                                            echo 'سارية حتى ' . safe_output($shj_exp) . '<br><font color="#ff0000" class="blink">(اخر يوم)</font>';
                                                        } else if($today > $shj_exp){
                                                            echo '<font color=#ff0000">منتهية بتاريخ ' . safe_output($shj_exp) . '</font>';
                                                        } else{
                                                            echo 'سارية حتى ' . safe_output($shj_exp);
                                                        }
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>تاريخ انتهاء قيد المحامي / عجمان : </p>
                                            <p class="profile-information">
                                                <?php
                                                    if(isset($rowus['ajmlaw_exp']) && $rowus['ajmlaw_exp'] !== ''){
                                                        $today = date("Y-m-d");
                                                        $ajm_exp = $rowus['ajmlaw_exp'];
                                                        
                                                        $future_date = new DateTime($ajm_exp);
                                                        $future_date->modify('-10 days');
                                                        $formatted_ten = $future_date->format("Y-m-d");
                                                        
                                                        if($today >= $formatted_ten && $today < $ajm_exp){
                                                            echo 'سارية حتى ' . safe_output($ajm_exp) . '<br><font color="#ff0000" class="blink">(متبقي اقل من 10 ايام للانتهاء)</font>';
                                                        } else if($today === $ajm_exp){
                                                            echo 'سارية حتى ' . safe_output($ajm_exp) . '<br><font color="#ff0000" class="blink">(اخر يوم)</font>';
                                                        } else if($today > $ajm_exp){
                                                            echo '<font color=#ff0000">منتهية بتاريخ ' . safe_output($ajm_exp) . '</font>';
                                                        } else{
                                                            echo 'سارية حتى ' . safe_output($ajm_exp);
                                                        }
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>تاريخ انتهاء قيد المحامي / ابوظبي : </p>
                                            <p class="profile-information">
                                                <?php
                                                    if(isset($rowus['abdlaw_exp']) && $rowus['abdlaw_exp'] !== ''){
                                                        $today = date("Y-m-d");
                                                        $adb_exp = $rowus['abdlaw_exp'];
                                                        
                                                        $future_date = new DateTime($adb_exp);
                                                        $future_date->modify('-10 days');
                                                        $formatted_ten = $future_date->format("Y-m-d");
                                                        
                                                        if($today >= $formatted_ten && $today < $adb_exp){
                                                            echo 'سارية حتى ' . safe_output($adb_exp) . '<br><font color="#ff0000" class="blink">(متبقي اقل من 10 ايام للانتهاء)</font>';
                                                        } else if($today === $adb_exp){
                                                            echo 'سارية حتى ' . safe_output($adb_exp) . '<br><font color="#ff0000" class="blink">(اخر يوم)</font>';
                                                        } else if($today > $adb_exp){
                                                            echo '<font color=#ff0000">منتهية بتاريخ ' . safe_output($adb_exp) . '</font>';
                                                        } else{
                                                            echo 'سارية حتى ' . safe_output($adb_exp);
                                                        }
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="moreinps-container">
                                <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                    <span><i class='bx bx-plus-medical' ></i> <p>معلومات في حالات الطوارئ</p></span> 
                                    <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                    <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                </button>
                                <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>الاسم : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['emergency_name1']);?></p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>صلة القرابة : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['emergency_relate1']);?></p>
                                        </div>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px border-bottom: 1px solid #00000140">
                                        <div class="info-item">
                                            <p>رقم الهاتف : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['emergency_tel1']);?></p>
                                        </div>
                                        <p></p>
                                        <p></p>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>الاسم : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['emergency_name2']);?></p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>صلة القرابة : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['emergency_relate2']);?></p>
                                        </div>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>رقم الهاتف : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['emergency_tel2']);?></p>
                                        </div>
                                        <p></p>
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                            <div class="moreinps-container">
                                <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                    <span><i class='bx bxs-bank' ></i> <p>بيانات البنك</p></span> 
                                    <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                    <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                </button>
                                <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>اسم البنك : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['bank_name']);?></p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>رقم الiban : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['iban']);?></p>
                                        </div>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>رقم الحساب : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['acc_no']);?></p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>طريقة الدفع : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['pay_way']);?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="moreinps-container">
                                <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                    <span><i class='bx bxs-credit-card-alt' ></i> <p>تفاصيل الراتب</p></span> 
                                    <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                    <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                </button>
                                <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>الراتب الأساسي : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['basic_salary']);?> AED</p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>تذاكر السفر : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['travel_tickets']);?> AED</p>
                                        </div>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>البترول : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['oil_cost']);?> AED</p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>السكن : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['housing_cost']);?> AED</p>
                                        </div>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>المعيشة : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['living_cost']);?> AED</p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>اجمالي الراتب : </p>
                                            <p class="profile-information">
                                                <?php 
                                                    $total_salary = $rowus['basic_salary']+$rowus['travel_tickets']+$rowus['oil_cost']+$rowus['housing_cost']+$rowus['living_cost'];
                                                    echo safe_output($total_salary).' AED';
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="moreinps-container">
                                <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                    <span><i class='bx bxs-lock' ></i> <p>مستندات قانونية</p></span> 
                                    <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                    <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                </button>
                                <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>رقم جواز السفر : </p>
                                            <p class="profile-information">
                                                <?php
                                                    $passport_no = $rowus['passport_no'];
                                                    $decrypted_passport_no = openssl_decrypt($passport_no, $cipher, $key, $options, $iv);
                                                    echo safe_output($decrypted_passport_no);
                                                ?>
                                            </p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>تاريخ انتهاء جواز السفر : </p>
                                            <p class="profile-information">
                                                <?php
                                                    if(isset($rowus['passport_exp']) && $rowus['passport_exp'] !== ''){
                                                        $today = date("Y-m-d");
                                                        $passport_exp = $rowus['passport_exp'];
                                                        
                                                        $future_date = new DateTime($passport_exp);
                                                        $future_date->modify('-10 days');
                                                        $formatted_ten = $future_date->format("Y-m-d");
                                                        
                                                        if($today >= $formatted_ten && $today < $passport_exp){
                                                            echo 'ساري حتى ' . safe_output($passport_exp) . '<br><font color="#ff0000" class="blink">(متبقي اقل من 10 ايام للانتهاء)</font>';
                                                        } else if($today === $passport_exp){
                                                            echo 'ساري حتى ' . safe_output($passport_exp) . '<br><font color="#ff0000" class="blink">(اخر يوم)</font>';
                                                        } else if($today > $passport_exp){
                                                            echo '<font color=#ff0000">منتهي بتاريخ ' . safe_output($passport_exp) . '</font>';
                                                        } else{
                                                            echo 'ساري حتى ' . safe_output($passport_exp);
                                                        }
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>رقم الهوية : </p>
                                            <p class="profile-information">
                                                <?php
                                                    echo safe_output($rowus['idno']);
                                                ?>
                                            </p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>تاريخ انتهاء الهوية : </p>
                                            <p class="profile-information">
                                                <?php
                                                    if(isset($rowus['id_exp']) && $rowus['id_exp'] !== ''){
                                                        $today = date("Y-m-d");
                                                        $id_exp = $rowus['id_exp'];
                                                        
                                                        $future_date = new DateTime($id_exp);
                                                        $future_date->modify('-10 days');
                                                        $formatted_ten = $future_date->format("Y-m-d");
                                                        
                                                        if($today >= $formatted_ten && $today < $id_exp){
                                                            echo 'ساري حتى ' . safe_output($id_exp) . '<br><font color="#ff0000" class="blink">(متبقي اقل من 10 ايام للانتهاء)</font>';
                                                        } else if($today === $id_exp){
                                                            echo 'ساري حتى ' . safe_output($id_exp) . '<br><font color="#ff0000" class="blink">(اخر يوم)</font>';
                                                        } else if($today > $id_exp){
                                                            echo '<font color=#ff0000">منتهي بتاريخ ' . safe_output($id_exp) . '</font>';
                                                        } else{
                                                            echo 'ساري حتى ' . safe_output($id_exp);
                                                        }
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>رقم بطاقة العمل : </p>
                                            <p class="profile-information"><?php echo safe_output($rowus['card_no']); ?></p>
                                        </div>
                                        <p></p>
                                        <div class="info-item">
                                            <p>تاريخ انتهاء بطاقة العمل : </p>
                                            <p class="profile-information">
                                                <?php
                                                    if(isset($rowus['cardno_exp']) && $rowus['cardno_exp'] !== ''){
                                                        $today = date("Y-m-d");
                                                        $cardno_exp = $rowus['cardno_exp'];
                                                        
                                                        $future_date = new DateTime($cardno_exp);
                                                        $future_date->modify('-10 days');
                                                        $formatted_ten = $future_date->format("Y-m-d");
                                                        
                                                        if($today >= $formatted_ten && $today < $cardno_exp){
                                                            echo 'ساري حتى ' . safe_output($cardno_exp) . '<br><font color="#ff0000" class="blink">(متبقي اقل من 10 ايام للانتهاء)</font>';
                                                        } else if($today === $cardno_exp){
                                                            echo 'ساري حتى ' . safe_output($cardno_exp) . '<br><font color="#ff0000" class="blink">(اخر يوم)</font>';
                                                        } else if($today > $cardno_exp){
                                                            echo '<font color=#ff0000">منتهي بتاريخ ' . safe_output($cardno_exp) . '</font>';
                                                        } else{
                                                            echo 'ساري حتى ' . safe_output($cardno_exp);
                                                        }
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="profile-grid" style="background-color: rgba(0, 0, 0, 0.01); border-radius: 10px">
                                        <div class="info-item">
                                            <p>تاريخ انتهاء التأمين الصحي : </p>
                                            <p class="profile-information">
                                                <?php
                                                    if(isset($rowus['sigorta_exp']) && $rowus['sigorta_exp'] !== ''){
                                                        $today = date("Y-m-d");
                                                        $sigorta_exp = $rowus['sigorta_exp'];
                                                        
                                                        $future_date = new DateTime($sigorta_exp);
                                                        $future_date->modify('-10 days');
                                                        $formatted_ten = $future_date->format("Y-m-d");
                                                        
                                                        if($today >= $formatted_ten && $today < $sigorta_exp){
                                                            echo 'ساري حتى ' . safe_output($sigorta_exp) . '<br><font color="#ff0000" class="blink">(متبقي اقل من 10 ايام للانتهاء)</font>';
                                                        } else if($today === $sigorta_exp){
                                                            echo 'ساري حتى ' . safe_output($sigorta_exp) . '<br><font color="#ff0000" class="blink">(اخر يوم)</font>';
                                                        } else if($today > $sigorta_exp){
                                                            echo '<font color=#ff0000">منتهي بتاريخ ' . safe_output($sigorta_exp) . '</font>';
                                                        } else{
                                                            echo 'ساري حتى ' . safe_output($sigorta_exp);
                                                        }
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                        <p></p>
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                            <?php 
                                    }
                                } else if(isset($_GET['empsection']) && $_GET['empsection'] === 'attachments'){
                                    if($row_permcheck['useratts_eperm'] == 1 || $_GET['empid'] === $myid){
                            ?>
                            <table style="background-color: #fff; width: 100%;">
                                <thead>
                                    <tr style="z-index: 3; position: sticky; top: -30px; background-color: #125386; border-radius: 0 5px 5px 0; color: #fff;">
                                        <th style="border-radius: 0 10px 10px 0;">
                                            <p style="padding: 10px;">اسم المُدخِل</p>
                                        </th>
                                        <th style="text-align: center;">اسم المستند</th>
                                        <th style="text-align: center; <?php if(!isset($_GET['action']) || $_GET['action'] !== 'editattachments'){?>border-radius: 10px 0 0 10px;<?php }?>">المستند المرفق</th>
                                        <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                        <th style="text-align: center; border-radius: 10px 0 0 10px;">الاجراءات</th>
                                        <?php }?>
                                    </tr>
                                    
                                    <?php 
                                        if(!isset($_GET['action']) || $_GET['action'] !== 'editattachments'){
                                            if($row_permcheck['useratts_eperm'] == 1 || $_GET['empid'] === $myid){
                                    ?>
                                    <tr style="text-align: right; background-color: #fff">
                                        <td colspan="4">
                                            <button class="add-btn" style="margin-right: 40px;" type="button" onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($_GET['empid']);?>&empsection=attachments&action=editattachments';">
                                                <p class="add-user-parag">تعديل و اضافة</p>
                                                <img class="add-user-img" src="img/add-button.png" width="25px" height="25px" loading="lazy">
                                            </button>
                                        </td>
                                    </tr>
                                    <?php }} else{?>
                                    <tr style="text-align: center; background-color: #fff">
                                        <td style="text-align: -webkit-center;">
                                            <button class="add-btn" type="button" onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($_GET['empid']);?>&empsection=attachments';">
                                                <p class="add-user-parag">العودة</p>
                                                <img class="add-user-img" src="img/back.png" width="25px" height="25px" loading="lazy">
                                            </button>
                                        </td>
                                        <td style="text-align: center;"></td>
                                        <td style="text-align: center;"></td>
                                        <td>
                                            <?php if($_GET['empid'] === $myid || $row_permcheck['useratts_eperm'] == 1){?>
                                            <input type="button" onclick="emp_formatts.submit();" value="حفظ البيانات" class="green-button">
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php }?>
                                </thead>
                                
                                <tbody>
                                    <form action="emp_formatts.php" method="post" name="emp_formatts" enctype="multipart/form-data">
                                        <input type="hidden" name="user_id" value="<?php echo safe_output($_GET['empid']);?>">
                                        <tr style="text-align: center;">
                                            <td style="width: fit-content;">
                                                <?php
                                                    $id = $_GET['empid'];
                                                    $empty = '';
                                                    $stmtat = $conn->prepare("SELECT * FROM user_attachments WHERE biography!=? AND user_id=? ORDER BY id DESC");
                                                    $stmtat->bind_param("si", $empty, $id);
                                                    $stmtat->execute();
                                                    $resultat = $stmtat->get_result();
                                                    while($rowat = $resultat->fetch_assoc()){
                                                        $biography_by = $rowat['biography_by'];
                                                        
                                                        $stmtu = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                        $stmtu->bind_param("i", $biography_by);
                                                        $stmtu->execute();
                                                        $resultu = $stmtu->get_result();
                                                        $rowu = $resultu->fetch_assoc();
                                                        $stmtu->close();
                                                        
                                                        echo safe_output($rowu['name']).'<br>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                                <div style="display: grid; grid-template-columns: 1fr 1fr;">
                                                    <?php }?>
                                                    السيرة الذاتية
                                                    <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                                    <div style="text-align: right;">
                                                        <label class="custom-file-label" for="file-upload1">ادخل المرفق</label>
                                                        <input type="file" name="biography" id="file-upload1" class="custom-file-input" onchange="updateFileName('1')">
                                                        <span id="file-name1">لا يوجد مرفق.</span>
                                                    </div>
                                                </div>
                                                <?php }?>
                                            </td>
                                            <td>
                                                <?php
                                                    $resultat->data_seek(0);
                                                    while($rowat = $resultat->fetch_assoc()){
                                                ?>
                                                <a href="<?php echo safe_output($rowat['biography']);?>" onClick="window.open('<?php echo safe_output($rowat['biography']);?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                    <?php echo basename(safe_output($rowat['biography']));?>
                                                </a><br>
                                                <?php }?>
                                            </td>
                                            <?php 
                                                if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){
                                                    if($_GET['empid'] === $myid || $row_permcheck['useratts_dperm'] == 1){
                                            ?>
                                            <td>
                                                <?php
                                                    $resultat->data_seek(0);
                                                    while($rowat = $resultat->fetch_assoc()){
                                                ?>
                                                <img src="img/recycle-bin.png" width="20px" height="20px" style="cursor: pointer" onclick="location.href='empattachdel.php?id=<?php echo safe_output($rowat['id']);?>&del=biography&empid=<?php echo safe_output($_GET['empid']);?>';" loading="lazy"><br>
                                                <?php }?>
                                            </td>
                                            <?php 
                                                    }
                                                }
                                                $stmtat->close();
                                            ?>
                                        </tr>
                                        <tr style="text-align: center;">
                                            <?php
                                                $empid = $_GET['empid'];
                                                $stmtpr = $conn->prepare("SELECT name, passport_residence FROM user WHERE id=?");
                                                $stmtpr->bind_param("i", $empid);
                                                $stmtpr->execute();
                                                $resultpr = $stmtpr->get_result();
                                                $rowpr = $resultpr->fetch_assoc();
                                                $stmtpr->close();
                                            ?>
                                            <td style="width: fit-content;">
                                                <?php echo safe_output($rowpr['name']);?>
                                            </td>
                                            <td>
                                                <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                                <div style="display: grid; grid-template-columns: 1fr 1fr;">
                                                    <?php }?>
                                                    جواز السفر
                                                    <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                                    <div style="text-align: right;">
                                                        <label class="custom-file-label" for="file-upload2">ادخل المرفق</label>
                                                        <input type="file" name="passport_residence" id="file-upload2" class="custom-file-input" onchange="updateFileName('2')">
                                                        <span id="file-name2">لا يوجد مرفق.</span>
                                                    </div>
                                                </div>
                                                <?php }?>
                                            </td>
                                            <td>
                                                <a href="<?php echo safe_output($rowpr['passport_residence']);?>" onClick="window.open('<?php echo safe_output($rowpr['passport_residence']);?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                    <?php echo basename(safe_output($rowpr['passport_residence']));?>
                                                </a>
                                            </td>
                                            <?php 
                                                if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){
                                                    if($_GET['empid'] === $myid || $row_permcheck['useratts_dperm'] == 1){
                                            ?>
                                            <td>
                                                <img src="img/recycle-bin.png" width="20px" height="20px" style="cursor: pointer" title="<?php echo safe_output($rowpr['passport_residence']);?>" onclick="location.href='empattachdel.php?id=<?php echo safe_output($rowpr['id']);?>&del=passport_residence&empid=<?php echo safe_output($_GET['empid']);?>';" loading="lazy"><br>
                                            </td>
                                            <?php 
                                                    }
                                                }
                                            ?>
                                        </tr>
                                        <tr style="text-align: center;">
                                            <td style="width: fit-content;">
                                                <?php
                                                    $id = $_GET['empid'];
                                                    $empty = '';
                                                    $stmtat = $conn->prepare("SELECT * FROM user_attachments WHERE uaeresidence!=? AND user_id=? ORDER BY id DESC");
                                                    $stmtat->bind_param("si", $empty, $id);
                                                    $stmtat->execute();
                                                    $resultat = $stmtat->get_result();
                                                    while($rowat = $resultat->fetch_assoc()){
                                                        $uaeresidence_by = $rowat['uaeresidence_by'];
                                                        
                                                        $stmtu = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                        $stmtu->bind_param("i", $uaeresidence_by);
                                                        $stmtu->execute();
                                                        $resultu = $stmtu->get_result();
                                                        $rowu = $resultu->fetch_assoc();
                                                        $stmtu->close();
                                                        
                                                        echo safe_output($rowu['name']).'<br>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                                <div style="display: grid; grid-template-columns: 1fr 1fr;">
                                                    <?php }?>
                                                    الهوية الاماراتية
                                                    <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                                    <div style="text-align: right;">
                                                        <label class="custom-file-label" for="file-upload3">ادخل المرفق</label>
                                                        <input type="file" name="uaeresidence" id="file-upload3" class="custom-file-input" onchange="updateFileName('3')">
                                                        <span id="file-name3">لا يوجد مرفق.</span>
                                                    </div>
                                                </div>
                                                <?php }?>
                                            </td>
                                            <td>
                                                <?php
                                                    $resultat->data_seek(0);
                                                    while($rowat = $resultat->fetch_assoc()){
                                                ?>
                                                <a href="<?php echo safe_output($rowat['uaeresidence']);?>" onClick="window.open('<?php echo safe_output($rowat['uaeresidence']);?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                    <?php echo basename(safe_output($rowat['uaeresidence']));?>
                                                </a><br>
                                                <?php }?>
                                            </td>
                                            <?php 
                                                if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){
                                                    if($_GET['empid'] === $myid || $row_permcheck['useratts_dperm'] == 1){
                                            ?>
                                            <td>
                                                <?php
                                                        $resultat->data_seek(0);
                                                        while($rowat = $resultat->fetch_assoc()){
                                                ?>
                                                <img src="img/recycle-bin.png" width="20px" height="20px" style="cursor: pointer" title="<?php echo safe_output($rowat['uaeresidence']);?>" onclick="location.href='empattachdel.php?id=<?php echo safe_output($rowat['id']);?>&del=uaeresidence&empid=<?php echo safe_output($_GET['empid']);?>';" loading="lazy"><br>
                                                <?php }?>
                                            </td>
                                            <?php 
                                                    }
                                                }
                                                $stmtat->close();
                                            ?>
                                        </tr>
                                        <tr style="text-align: center;">
                                            <td style="width: fit-content;">
                                                <?php
                                                    $id = $_GET['empid'];
                                                    $empty = '';
                                                    $stmtat = $conn->prepare("SELECT * FROM user_attachments WHERE behaviour!=? AND user_id=? ORDER BY id DESC");
                                                    $stmtat->bind_param("si", $empty, $id);
                                                    $stmtat->execute();
                                                    $resultat = $stmtat->get_result();
                                                    while($rowat = $resultat->fetch_assoc()){
                                                        $behaviour_by = $rowat['behaviour_by'];
                                                        
                                                        $stmtu = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                        $stmtu->bind_param("i", $behaviour_by);
                                                        $stmtu->execute();
                                                        $resultu = $stmtu->get_result();
                                                        $rowu = $resultu->fetch_assoc();
                                                        $stmtu->close();
                                                        
                                                        echo safe_output($rowu['name']).'<br>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                                <div style="display: grid; grid-template-columns: 1fr 1fr;">
                                                    <?php }?>
                                                    شهادة حسن السيرة و السلوك
                                                    <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                                    <div style="text-align: right;">
                                                        <label class="custom-file-label" for="file-upload4">ادخل المرفق</label>
                                                        <input type="file" name="behaviour" id="file-upload4" class="custom-file-input" onchange="updateFileName('4')">
                                                        <span id="file-name4">لا يوجد مرفق.</span>
                                                    </div>
                                                </div>
                                                <?php }?>
                                            </td>
                                            <td>
                                                <?php
                                                    $resultat->data_seek(0);
                                                    while($rowat = $resultat->fetch_assoc()){
                                                ?>
                                                <a href="<?php echo safe_output($rowat['behaviour']);?>" onClick="window.open('<?php echo safe_output($rowat['behaviour']);?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                    <?php echo basename(safe_output($rowat['behaviour']));?>
                                                </a><br>
                                                <?php }?>
                                            </td>
                                            <?php 
                                                if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){
                                                    if($_GET['empid'] === $myid || $row_permcheck['useratts_dperm'] == 1){
                                            ?>
                                            <td>
                                                <?php
                                                    $resultat->data_seek(0);
                                                    while($rowat = $resultat->fetch_assoc()){
                                                ?>
                                                <img src="img/recycle-bin.png" width="20px" height="20px" style="cursor: pointer" title="<?php echo safe_output($rowat['behaviour']);?>" onclick="location.href='empattachdel.php?id=<?php echo safe_output($rowat['id']);?>&del=behaviour&empid=<?php echo safe_output($_GET['empid']);?>';" loading="lazy"><br>
                                                <?php }?>
                                            </td>
                                            <?php 
                                                    }
                                                }
                                                $stmtat->close();
                                            ?>
                                        </tr>
                                        <tr style="text-align: center;">
                                            <td style="width: fit-content;">
                                                <?php
                                                    $id = $_GET['empid'];
                                                    $empty = '';
                                                    $stmtat = $conn->prepare("SELECT * FROM user_attachments WHERE university!=? AND user_id=? ORDER BY id DESC");
                                                    $stmtat->bind_param("si", $empty, $id);
                                                    $stmtat->execute();
                                                    $resultat = $stmtat->get_result();
                                                    while($rowat = $resultat->fetch_assoc()){
                                                        $university_by = $rowat['university_by'];
                                                        
                                                        $stmtu = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                        $stmtu->bind_param("i", $university_by);
                                                        $stmtu->execute();
                                                        $resultu = $stmtu->get_result();
                                                        $rowu = $resultu->fetch_assoc();
                                                        $stmtu->close();
                                                        
                                                        echo safe_output($rowu['name']).'<br>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                                <div style="display: grid; grid-template-columns: 1fr 1fr;">
                                                    <?php }?>
                                                    الشهادة الجامعية
                                                    <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                                    <div style="text-align: right;">
                                                        <label class="custom-file-label" for="file-upload5">ادخل المرفق</label>
                                                        <input type="file" name="university" id="file-upload5" class="custom-file-input" onchange="updateFileName('5')">
                                                        <span id="file-name5">لا يوجد مرفق.</span>
                                                    </div>
                                                </div>
                                                <?php }?>
                                            </td>
                                            <td>
                                                <?php
                                                    $resultat->data_seek(0);
                                                    while($rowat = $resultat->fetch_assoc()){
                                                ?>
                                                <a href="<?php echo safe_output($rowat['university']);?>" onClick="window.open('<?php echo safe_output($rowat['university']);?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                    <?php echo basename(safe_output($rowat['university']));?>
                                                </a><br>
                                                <?php }?>
                                            </td>
                                            <?php 
                                                if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){
                                                    if($_GET['empid'] === $myid || $row_permcheck['useratts_dperm'] == 1){
                                            ?>
                                            <td>
                                                <?php
                                                    $resultat->data_seek(0);
                                                    while($rowat = $resultat->fetch_assoc()){
                                                ?>
                                                <img src="img/recycle-bin.png" width="20px" height="20px" style="cursor: pointer" title="<?php echo safe_output($rowat['university']);?>" onclick="location.href='empattachdel.php?id=<?php echo safe_output($rowat['id']);?>&empid=<?php echo safe_output($_GET['empid']);?>&del=university';" loading="lazy"><br>
                                                <?php }?>
                                            </td>
                                            <?php 
                                                    }
                                                }
                                                $stmtat->close();
                                            ?>
                                        </tr>
                                        <tr style="text-align: center;">
                                            <td style="width: fit-content;">
                                                <?php
                                                    $id = $_GET['empid'];
                                                    $empty = '';
                                                    $stmtat = $conn->prepare("SELECT * FROM user_attachments WHERE contract!=? AND user_id=? ORDER BY id DESC");
                                                    $stmtat->bind_param("si", $empty, $id);
                                                    $stmtat->execute();
                                                    $resultat = $stmtat->get_result();
                                                    while($rowat = $resultat->fetch_assoc()){
                                                        $contract_by = $rowat['contract_by'];
                                                        
                                                        $stmtu = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                        $stmtu->bind_param("i", $contract_by);
                                                        $stmtu->execute();
                                                        $resultu = $stmtu->get_result();
                                                        $rowu = $resultu->fetch_assoc();
                                                        $stmtu->close();
                                                        
                                                        echo safe_output($rowu['name']).'<br>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                                <div style="display: grid; grid-template-columns: 1fr 1fr;">
                                                    <?php }?>
                                                    عقد العمل
                                                    <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                                    <div style="text-align: right;">
                                                        <label class="custom-file-label" for="file-upload6">ادخل المرفق</label>
                                                        <input type="file" name="contract" id="file-upload6" class="custom-file-input" onchange="updateFileName('6')">
                                                        <span id="file-name6">لا يوجد مرفق.</span>
                                                    </div>
                                                </div>
                                                <?php }?>
                                            </td>
                                            <td>
                                                <?php
                                                    $resultat->data_seek(0);
                                                    while($rowat = $resultat->fetch_assoc()){
                                                ?>
                                                <a href="<?php echo safe_output($rowat['contract']);?>" onClick="window.open('<?php echo safe_output($rowat['contract']);?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                    <?php echo basename(safe_output($rowat['contract']));?>
                                                </a><br>
                                                <?php }?>
                                            </td>
                                            <?php 
                                                if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){
                                                    if($_GET['empid'] === $myid || $row_permcheck['useratts_dperm'] == 1){
                                            ?>
                                            <td>
                                                <?php
                                                    $resultat->data_seek(0);
                                                    while($rowat = $resultat->fetch_assoc()){
                                                ?>
                                                <img src="img/recycle-bin.png" width="20px" height="20px" style="cursor: pointer" title="<?php echo safe_output($rowat['contract']);?>" onclick="location.href='empattachdel.php?id=<?php echo safe_output($rowat['id']);?>&empid=<?php echo safe_output($_GET['empid']);?>&del=contract';" loading="lazy"><br>
                                                <?php }?>
                                            </td>
                                            <?php 
                                                    }
                                                }
                                                $stmtat->close();
                                            ?>
                                        </tr>
                                        <tr style="text-align: center;">
                                            <td style="width: fit-content;">
                                                <?php
                                                    $id = $_GET['empid'];
                                                    $empty = '';
                                                    $stmtat = $conn->prepare("SELECT * FROM user_attachments WHERE card!=? AND user_id=? ORDER BY id DESC");
                                                    $stmtat->bind_param("si", $empty, $id);
                                                    $stmtat->execute();
                                                    $resultat = $stmtat->get_result();
                                                    while($rowat = $resultat->fetch_assoc()){
                                                        $card_by = $rowat['card_by'];
                                                        
                                                        $stmtu = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                        $stmtu->bind_param("i", $card_by);
                                                        $stmtu->execute();
                                                        $resultu = $stmtu->get_result();
                                                        $rowu = $resultu->fetch_assoc();
                                                        $stmtu->close();
                                                        
                                                        echo safe_output($rowu['name']).'<br>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                                <div style="display: grid; grid-template-columns: 1fr 1fr;">
                                                    <?php }?>
                                                    بطاقة العمل
                                                    <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                                    <div style="text-align: right;">
                                                        <label class="custom-file-label" for="file-upload7">ادخل المرفق</label>
                                                        <input type="file" name="card" id="file-upload7" class="custom-file-input" onchange="updateFileName('7')">
                                                        <span id="file-name7">لا يوجد مرفق.</span>
                                                    </div>
                                                </div>
                                                <?php }?>
                                            </td>
                                            <td>
                                                <?php
                                                    $resultat->data_seek(0);
                                                    while($rowat = $resultat->fetch_assoc()){
                                                ?>
                                                <a href="<?php echo safe_output($rowat['card']);?>" onClick="window.open('<?php echo safe_output($rowat['card']);?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                    <?php echo basename(safe_output($rowat['card']));?>
                                                </a><br>
                                                <?php }?>
                                            </td>
                                            <?php 
                                                if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){
                                                    if($_GET['empid'] === $myid || $row_permcheck['useratts_dperm'] == 1){
                                            ?>
                                            <td>
                                                <?php
                                                    $resultat->data_seek(0);
                                                    while($rowat = $resultat->fetch_assoc()){
                                                ?>
                                                <img src="img/recycle-bin.png" width="20px" height="20px" style="cursor: pointer" title="<?php echo safe_output($rowat['card']);?>" onclick="location.href='empattachdel.php?id=<?php echo safe_output($rowat['id']);?>&empid=<?php echo safe_output($_GET['empid']);?>&del=card';" loading="lazy"><br>
                                                <?php }?>
                                            </td>
                                            <?php
                                                    }
                                                }
                                                $stmtat->close();
                                            ?>
                                        </tr>
                                        <tr style="text-align: center;">
                                            <td style="width: fit-content;">
                                                <?php
                                                    $id = $_GET['empid'];
                                                    $empty = '';
                                                    $stmtat = $conn->prepare("SELECT * FROM user_attachments WHERE sigorta!=? AND user_id=? ORDER BY id DESC");
                                                    $stmtat->bind_param("si", $empty, $id);
                                                    $stmtat->execute();
                                                    $resultat = $stmtat->get_result();
                                                    while($rowat = $resultat->fetch_assoc()){
                                                        $sigorta_by = $rowat['sigorta_by'];
                                                        
                                                        $stmtu = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                        $stmtu->bind_param("i", $sigorta_by);
                                                        $stmtu->execute();
                                                        $resultu = $stmtu->get_result();
                                                        $rowu = $resultu->fetch_assoc();
                                                        $stmtu->close();
                                                        
                                                        echo safe_output($rowu['name']).'<br>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                                <div style="display: grid; grid-template-columns: 1fr 1fr;">
                                                    <?php }?>
                                                    التأمين الصحي
                                                    <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                                    <div style="text-align: right;">
                                                        <label class="custom-file-label" for="file-upload8">ادخل المرفق</label>
                                                        <input type="file" name="sigorta" id="file-upload8" class="custom-file-input" onchange="updateFileName('8')">
                                                        <span id="file-name8">لا يوجد مرفق.</span>
                                                    </div>
                                                </div>
                                                <?php }?>
                                            </td>
                                            <td>
                                                <?php
                                                    $resultat->data_seek(0);
                                                    while($rowat = $resultat->fetch_assoc()){
                                                ?>
                                                <a href="<?php echo safe_output($rowat['sigorta']);?>" onClick="window.open('<?php echo safe_output($rowat['sigorta']);?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                    <?php echo basename(safe_output($rowat['sigorta']));?>
                                                </a><br>
                                                <?php }?>
                                            </td>
                                            <?php 
                                                if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){
                                                    if($_GET['empid'] === $myid || $row_permcheck['useratts_dperm'] == 1){
                                            ?>
                                            <td>
                                                <?php
                                                    $resultat->data_seek(0);
                                                    while($rowat = $resultat->fetch_assoc()){
                                                ?>
                                                <img src="img/recycle-bin.png" width="20px" height="20px" style="cursor: pointer" title="<?php echo safe_output($rowat['sigorta']);?>" onclick="location.href='empattachdel.php?id=<?php echo safe_output($rowat['id']);?>&empid=<?php echo safe_output($_GET['empid']);?>&del=sigorta';" loading="lazy"><br>
                                                <?php }?>
                                            </td>
                                            <?php 
                                                    }
                                                }
                                                $stmtat->close();
                                            ?>
                                        </tr>
                                        <tr style="text-align: center;">
                                            <td style="width: fit-content;">
                                                <?php
                                                    $id = $_GET['empid'];
                                                    $empty = '';
                                                    $stmtat = $conn->prepare("SELECT * FROM user_attachments WHERE other!=? AND user_id=? ORDER BY id DESC");
                                                    $stmtat->bind_param("si", $empty, $id);
                                                    $stmtat->execute();
                                                    $resultat = $stmtat->get_result();
                                                    while($rowat = $resultat->fetch_assoc()){
                                                        $other_by = $rowat['other_by'];
                                                        
                                                        $stmtu = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                        $stmtu->bind_param("i", $other_by);
                                                        $stmtu->execute();
                                                        $resultu = $stmtu->get_result();
                                                        $rowu = $resultu->fetch_assoc();
                                                        $stmtu->close();
                                                        
                                                        echo safe_output($rowu['name']).'<br>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                                <div style="display: grid; grid-template-columns: 1fr 1fr;">
                                                    <?php }?>
                                                    أُخرى
                                                    <?php if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){?>
                                                    <div style="text-align: right;">
                                                        <label class="custom-file-label" for="file-upload9">ادخل المرفق</label>
                                                        <input type="file" name="other" id="file-upload9" class="custom-file-input" onchange="updateFileName('9')">
                                                        <span id="file-name9">لا يوجد مرفق.</span>
                                                    </div>
                                                </div>
                                                <?php }?>
                                            </td>
                                            <td>
                                                <?php
                                                    $resultat->data_seek(0);
                                                    while($rowat = $resultat->fetch_assoc()){
                                                ?>
                                                <a href="<?php echo safe_output($rowat['other']);?>" onClick="window.open('<?php echo safe_output($rowat['other']);?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                    <?php echo basename(safe_output($rowat['other']));?>
                                                </a><br>
                                                <?php }?>
                                            </td>
                                            <?php 
                                                if(isset($_GET['action']) && $_GET['action'] === 'editattachments'){
                                                    if($_GET['empid'] === $myid || $row_permcheck['useratts_dperm'] == 1){
                                            ?>
                                            <td>
                                                <?php
                                                    $resultat->data_seek(0);
                                                    while($rowat = $resultat->fetch_assoc()){
                                                ?>
                                                <img src="img/recycle-bin.png" width="20px" height="20px" style="cursor: pointer" title="<?php echo safe_output($rowat['other']);?>" onclick="location.href='empattachdel.php?id=<?php echo safe_output($rowat['id']);?>&empid=<?php echo safe_output($_GET['empid']);?>&del=other';" loading="lazy"><br>
                                                <?php }?>
                                            </td>
                                            <?php 
                                                    }
                                                }
                                                $stmtat->close();
                                            ?>
                                        </tr>
                                    </form>
                                </tbody>
                            </table>
                            <?php 
                                }} else if(isset($_GET['empsection']) && $_GET['empsection'] === 'time-management'){
                                    if(!isset($_GET['time-section']) || (isset($_GET['time-section']) && $_GET['time-section'] !== 'attendance' && $_GET['time-section'] !== 'discounts' && $_GET['time-section'] !== 'vacbalance')){
                                        if($row_permcheck['userattendance_rperm'] == 1){
                            ?>
                            <div class="listed_link" onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($_GET['empid']);?>&empsection=time-management&time-section=attendance';">
                                سجل الحضور
                            </div>
                            <?php } if($row_permcheck['discounts_rperm'] == 1 || $_GET['empid'] === $myid){?>
                            <div class="listed_link" onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($_GET['empid']);?>&empsection=time-management&time-section=discounts';">
                                الخصومات
                            </div>
                            <?php } if($row_permcheck['vacf_aperm'] || $row_permcheck['vacl_aperm'] || $_GET['empid'] === $myid){?>
                            <div class="listed_link" onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($_GET['empid']);?>&empsection=time-management&time-section=vacbalance';">
                                رصيد الاجازات
                            </div>
                            <?php 
                                }} else{
                                    if($_GET['time-section'] === 'attendance'){
                                        if($row_permcheck['userattendance_rperm'] == 1){
                            ?>
                            <table style="background-color: #fff; width: 100%;">
                                <thead>
                                    <?php if($row_permcheck['loggingtimes_eperm'] == 1){?>
                                    <tr>
                                        <td style="text-align: right; margin-right: 40px" colspan="7">
                                            <button class="add-btn" type="button" onclick="times()">
                                                <p class="add-user-parag">تعديل اوقات الدوام</p>
                                                <img class="add-user-img" src="img/add-button.png" width="25px" height="25px" loading="lazy">
                                            </button>
                                            <script>
                                                function times(){
                                                    const times = document.getElementById("times-btn");
                                                    
                                                    if (times.style.display === "none" || times.style.display === "") {
                                                        times.style.display = "block";
                                                    } else {
                                                        times.style.display = "none";
                                                    }
                                                }
                                            </script>
                                            <div id="times-btn" class="modal-overlay">
                                                <div class="modal-content" style="margin: auto; align-content: center">
                                                    <div class="days-table">
                                                        <div class="days-header">
                                                            <h4 class="addc-header-parag" style="margin: auto">أوقات الدوام</h4>
                                                            <div class="close-button-container">
                                                                <p class="close-button" onclick="times()">&times;</p>
                                                            </div>
                                                        </div>
                                                        <div class="days-body" style="padding: 10px;">
                                                            <table style="background-color: #fff; width: 100%;">
                                                                <thead>
                                                                    <tr style="z-index: 3; position: sticky; top: 5px; background-color: #125386; border-radius: 0 5px 5px 0; color: #fff;">
                                                                        <th style="border-radius: 0 10px 10px 0;">
                                                                            <p style="padding: 10px;">اليوم</p>
                                                                        </th>
                                                                        <th style="text-align: center;">وقت الدخول</th>
                                                                        <th style="text-align: center; border-radius: 10px 0 0 10px;">وقت الخروج</th>
                                                                    </tr>
                                                                </thead>
                                                                
                                                                <tbody>
                                                                    <form action="update_logging_times.php" method="post" enctype="multipart/form-data">
                                                                        <input type="hidden" name="userid" value="<?php echo safe_output($_GET['empid']);?>">
                                                                        <tr style="text-align: center;">
                                                                            <td style="text-align: center;">
                                                                                <p style="padding: 10px;">الأحد</p>
                                                                            </td>
                                                                            <?php
                                                                                $day = "sunday";
                                                                                $stmtlogging = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
                                                                                $stmtlogging->bind_param("s", $day);
                                                                                $stmtlogging->execute();
                                                                                $resultlogging = $stmtlogging->get_result();
                                                                                $rowlogging = $resultlogging->fetch_assoc();
                                                                                $stmtlogging->close();
                                                                                
                                                                                list($sunday_inHH, $sunday_inMM) = explode(":", $rowlogging['login_time']);
                                                                                list($sunday_outHH, $sunday_outMM) = explode(":", $rowlogging['logout_time']);
                                                                            ?>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="sunday_loginMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($sunday_inMM); ?>"> : 
                                                                                <input type="number" name="sunday_loginHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($sunday_inHH); ?>">
                                                                            </td>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="sunday_logoutMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($sunday_outMM); ?>"> : 
                                                                                <input type="number" name="sunday_logoutHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($sunday_outHH); ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr style="text-align: center;">
                                                                            <td style="text-align: center;">
                                                                                <p style="padding: 10px;">الإثنين</p>
                                                                            </td>
                                                                            <?php
                                                                                $day = "monday";
                                                                                $stmtlogging = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
                                                                                $stmtlogging->bind_param("s", $day);
                                                                                $stmtlogging->execute();
                                                                                $resultlogging = $stmtlogging->get_result();
                                                                                $rowlogging = $resultlogging->fetch_assoc();
                                                                                $stmtlogging->close();
                                                                                
                                                                                list($monday_inHH, $monday_inMM) = explode(":", $rowlogging['login_time']);
                                                                                list($monday_outHH, $monday_outMM) = explode(":", $rowlogging['logout_time']);
                                                                            ?>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="monday_loginMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($monday_inMM); ?>"> : 
                                                                                <input type="number" name="monday_loginHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($monday_inHH); ?>">
                                                                            </td>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="monday_logoutMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($monday_outMM); ?>"> : 
                                                                                <input type="number" name="monday_logoutHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($monday_outHH); ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr style="text-align: center;">
                                                                            <td style="text-align: center;">
                                                                                <p style="padding: 10px;">الثلاثاء</p>
                                                                            </td>
                                                                            <?php
                                                                                $day = "tuesday";
                                                                                $stmtlogging = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
                                                                                $stmtlogging->bind_param("s", $day);
                                                                                $stmtlogging->execute();
                                                                                $resultlogging = $stmtlogging->get_result();
                                                                                $rowlogging = $resultlogging->fetch_assoc();
                                                                                $stmtlogging->close();
                                                                                
                                                                                list($tuesday_inHH, $tuesday_inMM) = explode(":", $rowlogging['login_time']);
                                                                                list($tuesday_outHH, $tuesday_outMM) = explode(":", $rowlogging['logout_time']);
                                                                            ?>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="tuesday_loginMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($tuesday_inMM); ?>"> : 
                                                                                <input type="number" name="tuesday_loginHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($tuesday_inHH); ?>">
                                                                            </td>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="tuesday_logoutMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($tuesday_outMM); ?>"> : 
                                                                                <input type="number" name="tuesday_logoutHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($tuesday_outHH); ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr style="text-align: center;">
                                                                            <td style="text-align: center;">
                                                                                <p style="padding: 10px;">الأربعاء</p>
                                                                            </td>
                                                                            <?php
                                                                                $day = "wednesday";
                                                                                $stmtlogging = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
                                                                                $stmtlogging->bind_param("s", $day);
                                                                                $stmtlogging->execute();
                                                                                $resultlogging = $stmtlogging->get_result();
                                                                                $rowlogging = $resultlogging->fetch_assoc();
                                                                                $stmtlogging->close();
                                                                                
                                                                                list($wednesday_inHH, $wednesday_inMM) = explode(":", $rowlogging['login_time']);
                                                                                list($wednesday_outHH, $wednesday_outMM) = explode(":", $rowlogging['logout_time']);
                                                                            ?>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="wednesday_loginMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($wednesday_inMM); ?>"> : 
                                                                                <input type="number" name="wednesday_loginHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($wednesday_inHH); ?>">
                                                                            </td>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="wednesday_logoutMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($wednesday_outMM); ?>"> : 
                                                                                <input type="number" name="wednesday_logoutHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($wednesday_outHH); ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr style="text-align: center;">
                                                                            <td style="text-align: center;">
                                                                                <p style="padding: 10px;">الخميس</p>
                                                                            </td>
                                                                            <?php
                                                                                $day = "thursday";
                                                                                $stmtlogging = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
                                                                                $stmtlogging->bind_param("s", $day);
                                                                                $stmtlogging->execute();
                                                                                $resultlogging = $stmtlogging->get_result();
                                                                                $rowlogging = $resultlogging->fetch_assoc();
                                                                                $stmtlogging->close();
                                                                                
                                                                                list($thursday_inHH, $thursday_inMM) = explode(":", $rowlogging['login_time']);
                                                                                list($thursday_outHH, $thursday_outMM) = explode(":", $rowlogging['logout_time']);
                                                                            ?>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="thursday_loginMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($thursday_inMM); ?>"> : 
                                                                                <input type="number" name="thursday_loginHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($thursday_inHH); ?>">
                                                                            </td>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="thursday_logoutMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($thursday_outMM); ?>"> : 
                                                                                <input type="number" name="thursday_logoutHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($thursday_outHH); ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr style="text-align: center;">
                                                                            <td style="text-align: center;">
                                                                                <p style="padding: 10px;">الجمعة</p>
                                                                            </td>
                                                                            <?php
                                                                                $day = "friday";
                                                                                $stmtlogging = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
                                                                                $stmtlogging->bind_param("s", $day);
                                                                                $stmtlogging->execute();
                                                                                $resultlogging = $stmtlogging->get_result();
                                                                                $rowlogging = $resultlogging->fetch_assoc();
                                                                                $stmtlogging->close();
                                                                                
                                                                                list($friday_inHH, $friday_inMM) = explode(":", $rowlogging['login_time']);
                                                                                list($friday_outHH, $friday_outMM) = explode(":", $rowlogging['logout_time']);
                                                                            ?>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="friday_loginMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($friday_inMM); ?>"> : 
                                                                                <input type="number" name="friday_loginHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($friday_inHH); ?>">
                                                                            </td>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="friday_logoutMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($friday_outMM); ?>"> : 
                                                                                <input type="number" name="friday_logoutHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($friday_outHH); ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr style="text-align: center;">
                                                                            <td style="text-align: center;">
                                                                                <p style="padding: 10px;">السبت</p>
                                                                            </td>
                                                                            <?php
                                                                                $day = "saturday";
                                                                                $stmtlogging = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
                                                                                $stmtlogging->bind_param("s", $day);
                                                                                $stmtlogging->execute();
                                                                                $resultlogging = $stmtlogging->get_result();
                                                                                $rowlogging = $resultlogging->fetch_assoc();
                                                                                $stmtlogging->close();
                                                                                
                                                                                list($saturday_inHH, $saturday_inMM) = explode(":", $rowlogging['login_time']);
                                                                                list($saturday_outHH, $saturday_outMM) = explode(":", $rowlogging['logout_time']);
                                                                            ?>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="saturday_loginMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($saturday_inMM); ?>"> : 
                                                                                <input type="number" name="saturday_loginHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($saturday_inHH); ?>">
                                                                            </td>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="saturday_logoutMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($saturday_outMM); ?>"> : 
                                                                                <input type="number" name="saturday_logoutHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($saturday_outHH); ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr style="text-align: center;">
                                                                            <td style="text-align: center; position: sticky; bottom: 0; background-color: #12548340;" colspan="3">
                                                                                <input type="submit" value="حفظ البيانات" class="green-button">
                                                                            </td>
                                                                        </tr>
                                                                    </form>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <tr style="z-index: 3; position: sticky; top: -30px; background-color: #125386; border-radius: 0 5px 5px 0; color: #fff;">
                                        <th style="border-radius: 0 10px 10px 0;">
                                            <p style="padding: 10px;">اليوم و التاريخ</p>
                                        </th>
                                        <th style="text-align: center;">تسجيل الدخول</th>
                                        <th style="text-align: center;">التأخير</th>
                                        <th style="text-align: center;">اليوم و التاريخ</th>
                                        <th style="text-align: center;">تسجيل الخروج</th>
                                        <th style="text-align: center;">خروج مبكر</th>
                                        <th style="text-align: center; border-radius: 10px 0 0 10px;">الاجراءات</th>
                                    </tr>
                                    
                                    <tr style="text-align: center;">
                                        <?php if(($_GET['empid'] !== $myid && ($row_permcheck['userattendance_aperm'] == 1 || $row_permcheck['userattendance_eperm'] == 1)) || $owner == 1){?>
                                        <form action="<?php if(isset($_GET['attendid']) && $_GET['attendid'] !== ''){ echo 'attendedit.php'; } else{ echo 'attendadd.php'; }?>" method="post" enctype="multipart/form-data">
                                            <?php 
                                                if(isset($_GET['attendid']) && $_GET['attendid'] !== ''){
                                                    $attendid = $_GET['attendid'];
                                                    $stmtr = $conn->prepare("SELECT * FROM user_logs WHERE id=?");
                                                    $stmtr->bind_param("i", $attendid);
                                                    $stmtr->execute();
                                                    $resultr = $stmtr->get_result();
                                                    $rowr = $resultr->fetch_assoc();
                                                    $stmtr->close();
                                            ?>
                                            <input type="hidden" name="id" value="<?php echo safe_output($_GET['attendid']);?>">
                                            <?php }?>
                                            <input type="hidden" name="user_id" value="<?php echo safe_output($_GET['empid']);?>">
                                            <td style="text-align: center;">
                                                <input type="date" style="max-width: fit-content;" class="form-input" value="<?php if(isset($rowr['login_date']) && $rowr['login_date'] !== ''){ echo safe_output($rowr['login_date']); } else{ echo date('Y-m-d'); }?>" name="login_date">
                                            </td>
                                            <td style="text-align: center;">
                                                <?php
                                                    if(isset($rowr['login_time']) && $rowr['login_time'] !== ''){
                                                        $login_time = $rowr['login_time'];
                                                        list($ltHH, $ltMM) = explode(":", $login_time);
                                                    }
                                                ?>
                                                <input type="number" style="width: 50px;" class="form-input" name="ltMM" placeholder="MM" max="59" min="0" value="<?php if(isset($rowr['login_time']) && $rowr['login_time'] !== ''){ echo $ltMM; }?>"> :
                                                <input type="number" style="width: 50px;" class="form-input" name="ltHH" placeholder="HH" max="23" min="0" value="<?php if(isset($rowr['login_time']) && $rowr['login_time'] !== ''){ echo $ltHH; }?>">
                                            </td>
                                            <td style="text-align: center;"></td>
                                            <td>
                                                <input type="date" style="max-width: fit-content;" class="form-input" value="<?php if(isset($rowr['logout_date']) && $rowr['logout_date'] !== ''){ echo safe_output($rowr['logout_date']); } else{ echo date('Y-m-d'); }?>" name="logout_date">
                                            </td>
                                            <td style="text-align: center;">
                                                <?php
                                                    if(isset($rowr['logout_time']) && $rowr['logout_time'] !== ''){
                                                        $logout_time = $rowr['logout_time'];
                                                        list($louttHH, $louttMM) = explode(":", $logout_time);
                                                    }
                                                ?>
                                                <input type="number" style="width: 50px;" class="form-input" name="louttMM" placeholder="MM" max="59" min="0" value="<?php if(isset($rowr['logout_time']) && $rowr['logout_time'] !== ''){ echo $louttMM; }?>"> :
                                                <input type="number" style="width: 50px;" class="form-input" name="louttHH" placeholder="HH" max="23" min="0" value="<?php if(isset($rowr['logout_time']) && $rowr['logout_time'] !== ''){ echo $louttHH; }?>">
                                            </td>
                                            <td style="text-align: center;"></td>
                                            <td style="text-align: center;">
                                                <input type="submit" value="حفظ البيانات" class="green-button">
                                            </td>
                                        </form>
                                        <?php }?>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php
                                        $id = $_GET['empid'];
                                        $stmtulogs = $conn->prepare("SELECT * FROM user_logs WHERE user_id=?");
                                        $stmtulogs->bind_param("i", $id);
                                        $stmtulogs->execute();
                                        $resultulogs = $stmtulogs->get_result();
                                        if($resultulogs->num_rows > 0){
                                            while($rowulogs = $resultulogs->fetch_assoc()){
                                    ?>
                                    <tr style="text-align: center;">
                                        <td style="width: fit-content;">
                                            <?php 
                                                $login_day = $rowulogs['login_day'];
                                                $days_ar = [
                                                    'Sunday'    => 'الأحد',
                                                    'Monday'    => 'الإثنين',
                                                    'Tuesday'   => 'الثلاثاء',
                                                    'Wednesday' => 'الأربعاء',
                                                    'Thursday'  => 'الخميس',
                                                    'Friday'    => 'الجمعة',
                                                    'Saturday'  => 'السبت'
                                                ];
                                                $day_en = ucfirst(strtolower($rowulogs['login_day']));
                                                $login_day = $days_ar[$day_en] ?? $day_en;
                                                echo safe_output($login_day).', '.safe_output($rowulogs['login_date']);
                                            ?>
                                        </td>
                                        <td><?php echo safe_output($rowulogs['login_time']);?></td>
                                        <td>
                                            <?php
                                                $late_login = $rowulogs['late_login'];
                                                list($lateHH, $lateMM) = explode(":", $late_login);
                                                echo "$lateHH ساعات و $lateMM دقائق";
                                            ?>
                                        </td>
                                        <td style="width: fit-content;">
                                            <?php 
                                                $logout_day = $rowulogs['logout_day'];
                                                $days_ar = [
                                                    'Sunday'    => 'الأحد',
                                                    'Monday'    => 'الإثنين',
                                                    'Tuesday'   => 'الثلاثاء',
                                                    'Wednesday' => 'الأربعاء',
                                                    'Thursday'  => 'الخميس',
                                                    'Friday'    => 'الجمعة',
                                                    'Saturday'  => 'السبت'
                                                ];
                                                $day_en = ucfirst(strtolower($rowulogs['logout_day']));
                                                $logout_day = $days_ar[$day_en] ?? $day_en;
                                                echo safe_output($logout_day).', '.safe_output($rowulogs['logout_date']);
                                            ?>
                                        </td>
                                        <td><?php echo safe_output($rowulogs['logout_time']);?></td>
                                        <td>
                                            <?php
                                                $early_logout = $rowulogs['early_logout'];
                                                list($earlyHH, $earlyMM) = explode(":", $early_logout);
                                                echo "$earlyHH ساعات و $earlyMM دقائق";
                                            ?>
                                        </td>
                                        <td>
                                            <?php if($row_permcheck['userattendance_eperm'] == 1){?>
                                            <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='mbhEmps.php?attendid=<?php echo safe_output($rowulogs['id']);?>&empid=<?php echo safe_output($_GET['empid']);?>&empsection=time-management&time-section=attendance';" loading="lazy">
                                            <?php } if($row_permcheck['userattendance_dperm'] == 1){?>
                                            <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='delete_attend.php?user_id=<?php echo safe_output($_GET['empid']);?>&attendid=<?php echo safe_output($rowulogs['id']);?>';" loading="lazy">
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        }
                                        $stmtratings->close();
                                    ?>
                                </tbody>
                            </table>
                            <?php    
                                }} else if($_GET['time-section'] === 'discounts'){
                                    if($row_permcheck['discounts_rperm'] == 1){
                            ?>
                            <table style="background-color: #fff; width: 100%;">
                                <thead>
                                    <tr style="z-index: 3; position: sticky; top: -30px; background-color: #125386; border-radius: 0 5px 5px 0; color: #fff;">
                                        <th style="border-radius: 0 10px 10px 0;">
                                            <p style="padding: 10px;">التاريخ</p>
                                        </th>
                                        <th style="text-align: center;">اجمالي الراتب</th>
                                        <th style="text-align: center;">المبلغ المخصوم</th>
                                        <th style="text-align: center;">سبب الخصم</th>
                                        <th style="text-align: center; border-radius: 10px 0 0 10px;">الاجراءات</th>
                                    </tr>
                                    
                                    <tr style="text-align: center;">
                                        <?php if(($_GET['empid'] !== $myid && $row_permcheck['discounts_aperm'] == 1) || $owner == 1){?>
                                        <form action="decadd.php" method="post" enctype="multipart/form-data" >
                                            <input type="hidden" name="user_id" value="<?php echo safe_output($_GET['empid']);?>">
                                            <td style="text-align: center;">
                                                <input type="date" class="form-input" style="max-width: fit-content;" name="date" value="<?php echo date("Y-m-d");?>" required>
                                            </td>
                                            <td style="text-align: center;">
                                                <input type="number" class="form-input" style="max-width: 100px;" name="total_salary" required>
                                            </td>
                                            <td style="text-align: center;">
                                                <input type="number" class="form-input" style="max-width: 100px;" name="dec_amount" required>
                                            </td>
                                            <td style="text-align: center;">
                                                <textarea class="form-input" rows="2" style="max-width: 250px;" name="reason"></textarea>
                                            </td>
                                            <td style="text-align: center;">
                                                <input type="submit" value="حفظ البيانات" class="green-button">
                                            </td>
                                        </form>
                                        <?php }?>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php
                                        $user_id = $_GET['empid'];
                                        $stmtdisc = $conn->prepare("SELECT * FROM incdec WHERE user_id=?");
                                        $stmtdisc->bind_param("i", $user_id);
                                        $stmtdisc->execute();
                                        $resultdisc = $stmtdisc->get_result();
                                        if($resultdisc->num_rows > 0){
                                            while($rowdisc = $resultdisc->fetch_assoc()){
                                    ?>
                                    <tr style="text-align: center;">
                                        <td style="text-align: center;">
                                            <?php echo $rowdisc['date'];?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $rowdisc['total_salary'];?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $rowdisc['amount'];?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $rowdisc['reason'];?>
                                        </td>
                                        <td style="text-align: center;">
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        }
                                        $stmtdisc->close();
                                    ?>
                                </tbody>
                            </table>
                            <?php 
                                }} else if($_GET['time-section'] === 'vacbalance'){
                                    if($row_permcheck['vacf_aperm'] == 1 || $row_permcheck['vacl_aperm'] == 1 || $_GET['empid'] === $myid){
                                        $userid = $_GET['empid'];
                                        $stmtuser = $conn->prepare("SELECT yearly_vacbalance, sick_vacbalance, mother_vacbalance, father_vacbalance, study_vacbalance FROM user WHERE id=?");
                                        $stmtuser->bind_param("i", $userid);
                                        $stmtuser->execute();
                                        $resultuser = $stmtuser->get_result();
                                        $rowuser = $resultuser->fetch_assoc();
                            ?>
                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
                                <div style="padding: 25px; border-radius: 20px; border: 1px solid #67676725; display: grid; grid-template-rows: auto 1fr auto;">
                                    <?php 
                                        $yearly_balance = $rowuser['yearly_vacbalance'];
                                        $taken_leave = 30 - $yearly_balance;
                                        $progress = ($taken_leave / 30) * 100;
                                        $percentage = round($progress);
                                        if($percentage >= 80){
                                            $color = 'red;';
                                        } else if($percentage >= 50){
                                            $color = 'orange;';
                                        } else if($percentage >= 20){
                                            $color = 'yellow; color: #676767';
                                        } else{
                                            $color = 'green;';
                                        }
                                    ?>
                                    <div style="background-color: #125483; color: #fff; border-radius: 4px; font-size: 16px; padding: 5px; text-align:center;"><?php echo $taken_leave.' / 30'; ?></div>
                                    <img src="img/yearly_vacs.png" width="100%" loading="lazy">
                                    <div class="progress-wrapper">
                                        <div class="progress-title">
                                            <span>الإجازات السنوية</span>
                                            <span><?= $taken_leave ?> / 30 يوم</span>
                                        </div>
                                        <div class="progress-bar-container">
                                            <div class="progress-bar-fill" style="padding-left: 5px; width: <?= $percentage ?>%; background-color: <?php echo $color;?>;">
                                                <span class="progress-label"><?= $percentage ?>%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="padding: 25px; border-radius: 20px; border: 1px solid #67676725; display: grid; grid-template-rows: auto 1fr auto;">
                                    <?php 
                                        $sick_balance = $rowuser['sick_vacbalance'];
                                        $taken_leave = 90 - $sick_balance;
                                        $progress = ($taken_leave / 90) * 100;
                                        $percentage = round($progress);
                                        if($percentage >= 80){
                                            $color = 'red;';
                                        } else if($percentage >= 50){
                                            $color = 'orange;';
                                        } else if($percentage >= 20){
                                            $color = 'yellow; color: #676767';
                                        } else{
                                            $color = 'green;';
                                        }
                                    ?>
                                    <div style="background-color: #125483; color: #fff; border-radius: 4px; font-size: 16px; padding: 5px; text-align:center;"><?php echo $taken_leave.' / 90'; ?></div>
                                    <img src="img/illness-vacs.png" width="100%" loading="lazy">
                                    <div class="progress-wrapper">
                                        <div class="progress-title">
                                            <span>الإجازات المرضية</span>
                                            <span><?= $taken_leave ?> / 90 يوم</span>
                                        </div>
                                        <div class="progress-bar-container">
                                            <div class="progress-bar-fill" style="padding-left: 5px; width: <?= $percentage ?>%; background-color: <?php echo $color;?>;">
                                                <span class="progress-label"><?= $percentage ?>%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="padding: 25px; border-radius: 20px; border: 1px solid #67676725; display: grid; grid-template-rows: 1fr auto;">
                                    <?php 
                                        if($rowuser['sex'] === 'انثى'){
                                            $mother_balance = $rowuser['mother_vacbalance'];
                                            $taken_leave = 30 - $mother_balance;
                                            $progress = ($taken_leave / 30) * 100;
                                            $percentage = round($progress);
                                            if($percentage >= 80){
                                                $color = 'red;';
                                            } else if($percentage >= 50){
                                                $color = 'orange;';
                                            } else if($percentage >= 20){
                                                $color = 'yellow; color: #676767';
                                            } else{
                                                $color = 'green;';
                                            }
                                    ?>
                                    <div style="background-color: #125483; color: #fff; border-radius: 4px; font-size: 16px; padding: 5px; text-align:center;"><?php echo $taken_leave.' / 30'; ?></div>
                                    <img src="img/m_vacs.png" width="100%" loading="lazy">
                                    <div class="progress-wrapper">
                                        <div class="progress-title">
                                            <span>إجازات الوضع</span>
                                            <span><?= $taken_leave ?> / 30 يوم</span>
                                        </div>
                                        <div class="progress-bar-container">
                                            <div class="progress-bar-fill" style="padding-left: 5px; width: <?= $percentage ?>%; background-color: <?php echo $color;?>;">
                                                <span class="progress-label"><?= $percentage ?>%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                        } else if($rowuser['sex'] === 'ذكر'){
                                            $father_balance = $rowuser['father_vacbalance'];
                                            $taken_leave = 5 - $father_balance;
                                            $progress = ($taken_leave / 5) * 100;
                                            $percentage = round($progress);
                                            if($percentage >= 80){
                                                $color = 'red;';
                                            } else if($percentage >= 50){
                                                $color = 'orange;';
                                            } else if($percentage >= 20){
                                                $color = 'yellow; color: #676767';
                                            } else{
                                                $color = 'green;';
                                            }
                                    ?>
                                    <div style="background-color: #125483; color: #fff; border-radius: 4px; font-size: 16px; padding: 5px; text-align:center;"><?php echo $taken_leave.' / 5'; ?></div>
                                    <img src="img/f_vacs.png" width="100%" loading="lazy">
                                    <div class="progress-wrapper">
                                        <div class="progress-title">
                                            <span>الإجازات الأبوية</span>
                                            <span><?= $taken_leave ?> / 5 أيام</span>
                                        </div>
                                        <div class="progress-bar-container">
                                            <div class="progress-bar-fill" style="padding-left: 5px; width: <?= $percentage ?>%; background-color: <?php echo $color;?>;">
                                                <span class="progress-label"><?= $percentage ?>%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }?>
                                </div>
                                <div style="padding: 25px; border-radius: 20px; border: 1px solid #67676725; display: grid; grid-template-rows: 1fr auto;">
                                    <?php 
                                        $study_balance = $rowuser['study_vacbalance'];
                                        $taken_leave = 10 - $study_balance;
                                        $progress = ($taken_leave / 10) * 100;
                                        $percentage = round($progress);
                                        if($percentage >= 80){
                                            $color = 'red;';
                                        } else if($percentage >= 50){
                                            $color = 'orange;';
                                        } else if($percentage >= 20){
                                            $color = 'yellow; color: #676767';
                                        } else{
                                            $color = 'green;';
                                        }
                                    ?>
                                    <div style="background-color: #125483; color: #fff; border-radius: 4px; font-size: 16px; padding: 5px; text-align:center;"><?php echo $taken_leave.' / 10'; ?></div>
                                    <img src="img/study_vacs.png" width="100%" loading="lazy">
                                    <div class="progress-wrapper">
                                        <div class="progress-title">
                                            <span>الإجازات الدراسية</span>
                                            <span><?= $taken_leave ?> / 10 أيام</span>
                                        </div>
                                        <div class="progress-bar-container">
                                            <div class="progress-bar-fill" style="padding-left: 5px; width: <?= $percentage ?>%; background-color: <?php echo $color;?>;">
                                                <span class="progress-label"><?= $percentage ?>%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="padding: 25px; border-radius: 20px; border: 1px solid #67676725; display: grid; grid-template-rows: 1fr auto;">
                                    <?php 
                                        $taken_leave = 0;
                                        $stmtvac = $conn->prepare("SELECT * FROM vacations WHERE emp_id=? AND type!=? AND type!=? AND type!=? AND type!=? AND type!=? AND ask=? AND ask2=?");
                                        $three = 3;
                                        $yearly = 'سنوية';
                                        $sick = 'مرضية';
                                        $mother = 'وضع';
                                        $father = 'أبوية';
                                        $study = 'دراسية';
                                        $stmtvac->bind_param("isssssii", $_GET['empid'], $yearly, $sick, $mother, $father, $study, $three, $three);
                                        $stmtvac->execute();
                                        $resultvac = $stmtvac->get_result();
                                        if($resultvac->num_rows > 0){
                                            $taken_leave = 0;
                                            while($rowvac = $resultvac->fetch_assoc()){
                                                $start_date = new DateTime($rowvac['starting_date']);
                                                $end_date = new DateTime($rowvac['ending_date']);
                                                $days_diff = $start_date <= $end_date ? $start_date->diff($end_date)->days + 1 : 0;
                                                $taken_leave += $days_diff;
                                            }
                                        }
                                        $stmtvac->close();
                                    ?>
                                    <div style="background-color: #125483; color: #fff; border-radius: 4px; font-size: 16px; padding: 5px; text-align:center;"><?php echo $taken_leave.' / ∞'; ?></div>
                                    <img src="img/other_vacs.png" width="100%" loading="lazy">
                                    <div class="progress-wrapper">
                                        <div class="progress-title">
                                            <span>الإجازات الأخرى</span>
                                            <span><?= $taken_leave ?> / ∞</span>
                                        </div>
                                        <div class="progress-bar-container">
                                            <div class="progress-bar-fill" style="padding-left: 5px; width: 100%; background-color: green;;">
                                                <span class="progress-label">100%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                            }
                                        }
                                    }
                                } else if(isset($_GET['empsection']) && $_GET['empsection'] === 'rating-management'){
                                    if(!isset($_GET['rating-section']) || (isset($_GET['rating-section']) && $_GET['rating-section'] !== 'ratings' && $_GET['rating-section'] !== 'trainings' && $_GET['rating-section'] !== 'warnings')){
                                        if($row_permcheck['rate_rperm'] == 1 || $_GET['empid'] === $myid){
                            ?>
                            <div class="listed_link" onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($_GET['empid']);?>&empsection=rating-management&rating-section=ratings';">
                                التقييمات
                            </div>
                            <?php } if($row_permcheck['trainings_rperm'] == 1 || $_GET['empid'] === $myid){?>
                            <div class="listed_link" onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($_GET['empid']);?>&empsection=rating-management&rating-section=trainings';">
                                التدريب و التطوير
                            </div>
                            <?php } if($row_permcheck['warnings_rperm'] == 1 || $_GET['empid'] === $myid){?>
                            <div class="listed_link" onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($_GET['empid']);?>&empsection=rating-management&rating-section=warnings';">
                                الانذارات
                            </div>
                            <?php 
                                    }
                                } else{
                                    if($_GET['rating-section'] === 'ratings'){
                                        if($_GET['empid'] == $myid || $row_permcheck['rate_rperm'] == 1){
                            ?>
                            <table style="background-color: #fff; width: 100%;">
                                <thead>
                                    <tr style="z-index: 3; position: sticky; top: -30px; background-color: #125386; border-radius: 0 5px 5px 0; color: #fff;">
                                        <th style="border-radius: 0 10px 10px 0;">
                                            <p style="padding: 10px;">تاريخ التقييم</p>
                                        </th>
                                        <th style="text-align: center;">نوع التقييم</th>
                                        <th style="text-align: center;">المرفقات</th>
                                        <th style="text-align: center; width: 50px; border-radius: 10px 0 0 10px;">الاجراءات</th>
                                    </tr>
                                    
                                    <tr style="text-align: center;">
                                        <?php if(($_GET['empid'] !== $myid && ($row_permcheck['rate_aperm'] == 1 || $row_permcheck['rate_eperm'] == 1)) || $owner == 1){?>
                                        <form action="<?php if(isset($_GET['rid']) && $_GET['rid'] !== ''){ echo 'emp_formratesedit.php'; } else{ echo 'emp_formrates.php'; }?>" method="post" enctype="multipart/form-data">
                                            <?php 
                                                if(isset($_GET['rid']) && $_GET['rid'] !== ''){
                                                    $rid = $_GET['rid'];
                                                    $stmtr = $conn->prepare("SELECT * FROM ratings WHERE id=?");
                                                    $stmtr->bind_param("i", $rid);
                                                    $stmtr->execute();
                                                    $resultr = $stmtr->get_result();
                                                    $rowr = $resultr->fetch_assoc();
                                                    $stmtr->close();
                                            ?>
                                            <input type="hidden" name="id" value="<?php echo safe_output($_GET['rid']);?>">
                                            <?php }?>
                                            <input type="hidden" name="user_id" value="<?php echo safe_output($_GET['empid']);?>">
                                            <td style="text-align: center;">
                                                <input type="date" style="max-width: fit-content;" class="form-input" value="<?php if(isset($rowr['rating_date']) && $rowr['rating_date'] !== ''){ echo safe_output($rowr['rating_date']); } else{ echo date('Y-m-d'); }?>" name="rating_date">
                                            </td>
                                            <td style="text-align: center;">
                                                <select class="form-input" name="rating_type" style="max-width: fit-content;">
                                                    <option value=""></option>
                                                    <option <?php if(isset($rowr['rating_type']) && $rowr['rating_type'] === 'ذاتي'){ echo 'selected'; }?> value="ذاتي">ذاتي</option>
                                                    <option <?php if(isset($rowr['rating_type']) && $rowr['rating_type'] === 'شهري'){ echo 'selected'; }?> value="شهري">شهري</option>
                                                    <option <?php if(isset($rowr['rating_type']) && $rowr['rating_type'] === 'سنوي'){ echo 'selected'; }?> value="سنوي">سنوي</option>
                                                </select>
                                            </td>
                                            <td style="text-align: center;">
                                                <label class="custom-file-label" for="file-upload10">ادخل المرفق</label>
                                                <input type="file" name="attachment" id="file-upload10" class="custom-file-input" onchange="updateFileName('10')">
                                                <span id="file-name10">لا يوجد مرفق.</span>
                                            </td>
                                            <td>
                                                <?php if($_GET['rid'] !== $myid && ($row_permcheck['rate_aperm'] === '1' || $row_permcheck['rate_eperm'])){?>
                                                <input type="submit" value="حفظ البيانات" class="green-button">
                                                <?php }?>
                                            </td>
                                        </form>
                                        <?php }?>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php
                                        $id = $_GET['empid'];
                                        $stmtratings = $conn->prepare("SELECT * FROM ratings WHERE emp_id=?");
                                        $stmtratings->bind_param("i", $id);
                                        $stmtratings->execute();
                                        $resultratings = $stmtratings->get_result();
                                        if($resultratings->num_rows > 0){
                                            while($rowratings = $resultratings->fetch_assoc()){
                                    ?>
                                    <tr style="text-align: center;">
                                        <td style="width: fit-content;"><?php echo safe_output($rowratings['rating_date']);?></td>
                                        <td><?php echo safe_output($rowratings['rating_type']);?></td>
                                        <td>
                                            <a href="<?php echo safe_output($rowratings['attachment']);?>" onClick="window.open('<?php echo safe_output($rowratings['attachment']);?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                <?php echo basename(safe_output($rowratings['attachment']));?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php if($row_permcheck['rate_eperm'] == 1 && $_GET['empid'] !== $myid){?>
                                            <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($_GET['empid']);?>&empsection=rating-management&rating-section=ratings&rid=<?php echo safe_output($rowratings['id']);?>';" loading="lazy">
                                            <?php } if($row_permcheck['rate_dperm'] == 1 && $_GET['empid'] !== $myid){?>
                                            <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='delete_emprating.php?rid=<?php echo safe_output($rowratings['id']); ?>';" loading="lazy">
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        }
                                        $stmtratings->close();
                                    ?>
                                </tbody>
                            </table>
                            <?php
                                    }
                                } else if(isset($_GET['rating-section']) && $_GET['rating-section'] === 'trainings'){
                                    if($row_permcheck['warnings_rperm'] == 1 || $_GET['empid'] === $myid){
                            ?>
                            <table style="background-color: #fff; width: 100%;">
                                <thead>
                                    <tr style="z-index: 3; position: sticky; top: -30px; background-color: #125386; border-radius: 0 5px 5px 0; color: #fff;">
                                        <th style="border-radius: 0 10px 10px 0;">
                                            <p style="padding: 10px;">تاريخ الدورة</p>
                                        </th>
                                        <th style="text-align: center;">نوع التدريب</th>
                                        <th style="text-align: center;">المرفقات</th>
                                        <th style="text-align: center; width: 50px; border-radius: 10px 0 0 10px;">الاجراءات</th>
                                    </tr>
                                    
                                    <tr style="text-align: center;">
                                        <?php if(($_GET['empid'] !== $myid && ($row_permcheck['trainings_aperm'] == 1 || $row_permcheck['trainings_eperm'] == 1)) || $owner == 1){?>
                                        <form action="<?php if(isset($_GET['trainid']) && $_GET['trainid'] !== ''){ echo 'emp_formtrainingsedit.php'; } else{ echo 'emp_formtrainings.php'; }?>" method="post" enctype="multipart/form-data">
                                            <?php 
                                                if(isset($_GET['trainid']) && $_GET['trainid'] !== ''){
                                                    $trainid = $_GET['trainid'];
                                                    $stmtr = $conn->prepare("SELECT * FROM trainings WHERE id=?");
                                                    $stmtr->bind_param("i", $trainid);
                                                    $stmtr->execute();
                                                    $resultr = $stmtr->get_result();
                                                    $rowr = $resultr->fetch_assoc();
                                                    $stmtr->close();
                                            ?>
                                            <input type="hidden" name="id" value="<?php echo safe_output($_GET['trainid']);?>">
                                            <?php }?>
                                            <input type="hidden" name="user_id" value="<?php echo safe_output($_GET['empid']);?>">
                                            <td style="text-align: center;">
                                                <input type="date" style="max-width: fit-content;" class="form-input" value="<?php if(isset($rowr['training_date']) && $rowr['training_date'] !== ''){ echo safe_output($rowr['training_date']); } else{ echo date('Y-m-d'); }?>" name="training_date">
                                            </td>
                                            <td style="text-align: center;">
                                                <input type="text" style="max-width: fit-content;" class="form-input" value="<?php if(isset($rowr['training_type']) && $rowr['training_type'] !== ''){ echo safe_output($rowr['training_type']); }?>" name="training_type">
                                            </td>
                                            <td style="text-align: center;">
                                                <label class="custom-file-label" for="file-upload22">ادخل المرفق</label>
                                                <input type="file" name="training_attachment" id="file-upload22" class="custom-file-input" onchange="updateFileName('22')">
                                                <span id="file-name22">لا يوجد مرفق.</span>
                                            </td>
                                            <td>
                                                <?php if($_GET['empid'] !== $myid && ($row_permcheck['trainings_aperm'] === '1' || $row_permcheck['trainings_eperm'])){?>
                                                <input type="submit" value="حفظ البيانات" class="green-button">
                                                <?php }?>
                                            </td>
                                        </form>
                                        <?php }?>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php
                                        $id = $_GET['empid'];
                                        $stmttrainings = $conn->prepare("SELECT * FROM trainings WHERE user_id=?");
                                        $stmttrainings->bind_param("i", $id);
                                        $stmttrainings->execute();
                                        $resulttrainings = $stmttrainings->get_result();
                                        if($resulttrainings->num_rows > 0){
                                            while($rowtrainings = $resulttrainings->fetch_assoc()){
                                    ?>
                                    <tr style="text-align: center;">
                                        <td style="width: fit-content;"><?php echo safe_output($rowtrainings['training_date']);?></td>
                                        <td><?php echo safe_output($rowtrainings['training_type']);?></td>
                                        <td>
                                            <a href="<?php echo safe_output($rowtrainings['attachment']);?>" onClick="window.open('<?php echo safe_output($rowtrainings['attachment']);?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                <?php echo basename(safe_output($rowtrainings['attachment']));?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php if($row_permcheck['trainings_eperm'] == 1 && $_GET['empid'] !== $myid){?>
                                            <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($_GET['empid']);?>&empsection=rating-management&rating-section=trainings&trainid=<?php echo safe_output($rowtrainings['id']);?>';" loading="lazy">
                                            <?php } if($row_permcheck['trainings_dperm'] == 1 && $_GET['empid'] !== $myid){?>
                                            <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='delete_training.php?user_id=<?php echo safe_output($_GET['empid']);?>&trainid=<?php echo safe_output($rowtrainings['id']); ?>';" loading="lazy">
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        }
                                        $stmttrainings->close();
                                    ?>
                                </tbody>
                            </table>
                            <?php
                                    }
                                } else if(isset($_GET['rating-section']) && $_GET['rating-section'] === 'warnings'){
                                    if(($row_permcheck['warnings_rperm'] == 1) || $_GET['empid'] === $myid){
                            ?>
                            <table style="background-color: #fff; width: 100%;">
                                <thead>
                                    <tr style="z-index: 3; position: sticky; top: -30px; background-color: #125386; border-radius: 0 5px 5px 0; color: #fff;">
                                        <th style="border-radius: 0 10px 10px 0;">
                                            <p style="padding: 10px;">تاريخ الانذار</p>
                                        </th>
                                        <th style="text-align: center;">نوع الانذار</th>
                                        <th style="text-align: center;">سبب الانذار</th>
                                        <th style="text-align: center;">المرفقات</th>
                                        <th style="text-align: center; width: 80px; border-radius: 10px 0 0 10px;">الاجراءات</th>
                                    </tr>
                                    
                                    <tr style="text-align: center;">
                                        <?php if(($_GET['empid'] !== $myid && ($row_permcheck['trainings_aperm'] == 1 || $row_permcheck['trainings_eperm'] == 1)) || $owner == 1){?>
                                        <form action="<?php if(isset($_GET['warnid']) && $_GET['warnid'] !== ''){ echo 'emp_formwarnsedit.php'; } else{ echo 'emp_formwarns.php'; }?>" method="post" enctype="multipart/form-data">
                                            <?php 
                                                if(isset($_GET['warnid']) && $_GET['warnid'] !== ''){
                                                    $warnid = $_GET['warnid'];
                                                    $stmtr = $conn->prepare("SELECT * FROM warnings WHERE id=?");
                                                    $stmtr->bind_param("i", $warnid);
                                                    $stmtr->execute();
                                                    $resultr = $stmtr->get_result();
                                                    $rowr = $resultr->fetch_assoc();
                                                    $stmtr->close();
                                            ?>
                                            <input type="hidden" name="id" value="<?php echo safe_output($_GET['warnid']);?>">
                                            <?php }?>
                                            <input type="hidden" name="user_id" value="<?php echo safe_output($_GET['empid']);?>">
                                            <td style="text-align: center;">
                                                <input type="date" style="max-width: fit-content;" class="form-input" name="warning_date" value="<?php if(isset($rowr['warning_date']) && $rowr['warning_date'] !== ''){ echo safe_output($rowr['warning_date']); } else{ echo date('Y-m-d'); }?>" required>
                                            </td>
                                            <td style="text-align: center;">
                                                <select class="form-input" name="warning_type" required>
                                                    <?php $selectwarntype = ''; if(isset($rowr['warning_type']) && $rowr['warning_type'] !== ''){ $selectwarntype = $rowr['warning_type']; }?>
                                                    <option value="شفهي" <?php if($selectwarntype === 'شفهي'){ echo 'selected'; }?>>شفهي</option>
                                                    <option value="كتابي" <?php if($selectwarntype === 'كتابي'){ echo 'selected'; }?>>كتابي</option>
                                                </select>
                                            </td>
                                            <td style="text-align: center;">
                                                <input type="text" class="form-input" name="warning_reason" value="<?php if(isset($rowr['warning_reason']) && $rowr['warning_reason'] !== ''){ echo $rowr['warning_reason']; }?>">
                                            </td>
                                            <td style="text-align: center;">
                                                <label class="custom-file-label" for="file-upload23">ادخل المرفق</label>
                                                <input type="file" name="warning_attachments" id="file-upload23" class="custom-file-input" onchange="updateFileName('23')">
                                                <span id="file-name23">لا يوجد مرفق.</span>
                                            </td>
                                            <td>
                                                <?php if($_GET['empid'] !== $myid && ($row_permcheck['warnings_aperm'] === '1' || $row_permcheck['warnings_eperm'])){?>
                                                <input type="submit" value="حفظ البيانات" class="green-button">
                                                <?php }?>
                                            </td>
                                        </form>
                                        <?php }?>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php
                                        $id = $_GET['empid'];
                                        $stmtwarnings = $conn->prepare("SELECT * FROM warnings WHERE emp_id=? ORDER BY id DESC");
                                        $stmtwarnings->bind_param("i", $id);
                                        $stmtwarnings->execute();
                                        $resultwarnings = $stmtwarnings->get_result();
                                        if($resultwarnings->num_rows > 0){
                                            while($rowwarnings = $resultwarnings->fetch_assoc()){
                                    ?>
                                    <tr style="text-align: center;">
                                        <td style="width: fit-content;"><?php echo safe_output($rowwarnings['warning_date']);?></td>
                                        <td><?php echo safe_output($rowwarnings['warning_type']);?></td>
                                        <td><?php echo safe_output($rowwarnings['warning_reason']);?></td>
                                        <td>
                                            <a href="<?php echo safe_output($rowwarnings['attachments']);?>" onClick="window.open('<?php echo safe_output($rowwarnings['attachments']);?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                <?php echo basename(safe_output($rowwarnings['attachments']));?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php if($row_permcheck['warnings_eperm'] == 1 && $_GET['empid'] !== $myid){?>
                                            <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($_GET['empid']);?>&empsection=rating-management&rating-section=warnings&warnid=<?php echo safe_output($rowwarnings['id']);?>';" loading="lazy">
                                            <?php } if($row_permcheck['warnings_dperm'] == 1 && $_GET['empid'] !== $myid){?>
                                            <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='delete_warning.php?user_id=<?php echo safe_output($_GET['empid']);?>&warnid=<?php echo safe_output($rowwarnings['id']); ?>';" loading="lazy">
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        }
                                        $stmtwarnings->close();
                                    ?>
                                </tbody>
                            </table>
                            <?php
                                            }
                                        }
                                    }
                                } else if(isset($_GET['empsection']) && $_GET['empsection'] === 'requests'){
                            ?>						
                            <table style="background-color: #fff; width: 100%;">
                                <thead>
                                    <tr style="z-index: 3; position: sticky; top: -30px; background-color: #125386; border-radius: 0 5px 5px 0; color: #fff;">
                                        <th style="border-radius: 0 10px 10px 0;">
                                            <p style="padding: 10px;">نوع الاجازة</p>
                                        </th>
                                        <th style="text-align: center;">من تاريخ</th>
                                        <th style="text-align: center;">الى تاريخ</th>
                                        <th style="text-align: center;">ملاحظات</th>
                                        <th style="text-align: center; border-radius: 10px 0 0 10px;">حالة الاجازة</th>
                                    </tr>
                                    
                                    <?php if($_GET['empid'] === $myid){?>
                                    <tr style="text-align: center;" class="infotable-body">
                                        <form action="vacadd.php" method="post">
                                            <th style="text-align: center;">
                                                <input type="hidden" name="empid" value="<?php echo $_GET['empid'];?>">
                                                <select class="form-input" name="type" style="width: fit-content">
                                                    <option value="سنوية">سنوية</option>
                                                    <option value="مرضية">مرضية</option>
                                                    <?php if($rowme['sex'] === 'انثى'){?>
                                                    <option value="وضع">وضع</option>
                                                    <?php } else{?>
                                                    <option value="أبوية">أبوية</option>
                                                    <?php }?>
                                                    <option value="دراسية">دراسية</option>
                                                    <option value="اخرى">اخرى</option>
                                                </select>
                                            </th>
                                            <th style="text-align: center;"><input type="date" name="from_date" style="width: fit-content" class="form-input"></th>
                                            <th style="text-align: center;"><input type="date" name="to_date" style="width: fit-content" class="form-input"></th>
                                            <th style="text-align: center;"><textarea class="form-input" rows="2" name="vac_note" style="width: 60%; min-width: 100px;"></textarea></th>
                                            <th style="text-align: center;"><input type="submit" class="green-button" value="حفظ البيانات"></th>
                                        </form>
                                    </tr>
                                    <?php }?>
                                </thead>
                                
                                <tbody>
                                    <?php 
                                        $empid = $_GET['empid'];
                                        
                                        $stmtvac = $conn->prepare("SELECT * FROM vacations WHERE emp_id=? ORDER BY id DESC");
                                        $stmtvac->bind_param("i", $empid);
                                        $stmtvac->execute();
                                        $resultvac = $stmtvac->get_result();
                                        if($resultvac->num_rows > 0){
                                            while($rowvac = $resultvac->fetch_assoc()){
                                    ?>
                                    <tr style="text-align: center;" class="infotable-body">
                                        <td style="text-align: center;"><?php echo safe_output($rowvac['type']);?></td>
                                        <td style="text-align: center;"><?php echo safe_output($rowvac['starting_date']);?></td>
                                        <td style="text-align: center;"><?php echo safe_output($rowvac['ending_date']);?></td>
                                        <td style="text-align: center;"><?php echo safe_output($rowvac['notes']);?></td>
                                        <td style="text-algin: center; color:<?php
                                                if($rowvac['ask'] == 2 || $rowvac['ask2'] == 2){
                                                    echo '#ff0000';
                                                } else if($rowvac['ask'] == 1){
                                                    echo '#676767';
                                                } else if($rowvac['ask'] == 3 && $rowvac['ask2'] == 1){
                                                    echo '#676767';
                                                } else if($rowvac['ask'] == 3 && $rowvac['ask2'] == 3){
                                                    echo '#00ff00';
                                                }
                                            ?>">
                                            <?php
                                                if($rowvac['ask'] == 2 || $rowvac['ask2'] == 2){
                                                    echo 'تم رفض الاجازة';
                                                } else if($rowvac['ask'] == 1){
                                                    echo 'في الانتظار';
                                                } else if($rowvac['ask'] == 3 && $rowvac['ask2'] == 1){
                                                    echo 'تم قبول الاجازة مبدئيا';
                                                } else if($rowvac['ask'] == 3 && $rowvac['ask2'] == 3){
                                                    echo 'تم قبول الاجازة';
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        }
                                        $stmtvac->close();
                                    ?>
                                </tbody>
                            </table>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <?php }} else if((!isset($_GET['id']) && $_GET['id'] !== '' && isset($_GET['empadd']) && $_GET['empadd'] === '1' && $row_permcheck['emp_perms_add'] == 1) || (isset($_GET['id']) && $_GET['id'] !== '' && isset($_GET['edit']) && $_GET['edit'] === '1' && $row_permcheck['emp_perms_edit'] == 1)){?>
                <div class="empinfo-container">
                    <div class="empinfo-table">
                        <form id="flexibleForm" action="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo 'editemp.php'; } else{ echo 'empadd.php'; }?>" name="employeeform" method="post" enctype="multipart/form-data">
                            <div class="empinfo-header">
                                <p onclick="showStep('1')" id="step1" class="step active-empsection">المعلومات العامة</p>
                                <p onclick="showStep('2')" id="step2" class="step">حساب المستخدم</p>
                                <p onclick="showStep('3')" id="step3" class="step">المعلومات الوظيفية</p>
                                <p onclick="showStep('4')" id="step4" class="step">المستندات القانونية</p>
                                <p onclick="showStep('5')" id="step5" class="step">الصلاحيات</p>
                            </div>
                            <div class="empinfo-body">
                                <?php
                                    if(isset($_GET['edit']) && $_GET['edit'] === '1' && isset($_GET['id']) && $_GET['id'] !== ''){
                                        $id = $_GET['id'];
                                ?>
                                <input type='hidden' value='<?php echo safe_output($id);?>' name='id'>
                                <?php
                                        $estmt = $conn->prepare("SELECT * FROM user WHERE id=?");
                                        $estmt->bind_param("i", $id);
                                        $estmt->execute();
                                        $eresult = $estmt->get_result();
                                        $erow = $eresult->fetch_assoc();
                                    }
                                ?>
                                <h2 class="advinputs-h2" style="color: #667078"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){?> تعديل بيانات الموظف رقم : <?php echo safe_output($_GET['id']); } else{ echo 'اضافة موظف جديد'; }?></h2><br>
                                <div class="step-container">
                                    <div class="stepinfo" id="stepinfo1" style="display: block">
                                        <div class="advinputs3">
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">اسم الموظف<font style="color: #aa0820;">*</font></p>
                                                <input class="form-input" name="name" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['name']); } else{ echo ''; }?>" type="text" required>
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">الجنس</p>
                                                <input type="radio" name="sex" value="ذكر" style="padding: 10px 0; margin: 10px 0;" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['sex'] === 'ذكر'){ echo 'checked'; }?>> ذكر <br>
                                                <input type="radio" name="sex" value="انثى" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($erow['sex'] === 'انثى'){ echo 'checked'; }} else{ echo 'checked'; }?>> انثى
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">تاريخ الميلاد</p>
                                                <input type="date" class="form-input" name="dob" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['dob']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">الجنسية</p>
                                                <?php $selectedCountry = safe_output($erow['nationality']);?>
                                                <select class="table-header-selector" name="nationality" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px; height: fit-content" dir="rtl">
                                                    <option value="" <?php echo ($selectedCountry == '0') ? 'selected' : ''; ?>></option>
                                                    <option value="أثيوبيا" <?php echo ($selectedCountry == 'أثيوبيا') ? 'selected' : ''; ?> >أثيوبيا</option>
                                                    <option value="أذربيجان" <?php echo ($selectedCountry == 'أذربيجان') ? 'selected' : ''; ?> >أذربيجان</option>
                                                    <option value="أراضي القطب الجنوبي" <?php echo ($selectedCountry == 'أراضي القطب الجنوبي') ? 'selected' : ''; ?> >أراضي القطب الجنوبي</option>
                                                    <option value="أرض المحيط الهندي البريطانية" <?php echo ($selectedCountry == 'أرض المحيط الهندي البريطانية') ? 'selected' : ''; ?> >أرض المحيط الهندي البريطانية</option>
                                                    <option value="أرمينيا" <?php echo ($selectedCountry == 'أرمينيا') ? 'selected' : ''; ?> >أرمينيا</option>
                                                    <option value="أروبا" <?php echo ($selectedCountry == 'أروبا') ? 'selected' : ''; ?> >أروبا</option>
                                                    <option value="أسبانيا" <?php echo ($selectedCountry == 'أسبانيا') ? 'selected' : ''; ?> >أسبانيا</option>
                                                    <option value="أستراليا" <?php echo ($selectedCountry == 'أستراليا') ? 'selected' : ''; ?> >أستراليا</option>
                                                    <option value="أستونيا" <?php echo ($selectedCountry == 'أستونيا') ? 'selected' : ''; ?> >أستونيا</option>
                                                    <option value="أفغانستان" <?php echo ($selectedCountry == 'أفغانستان') ? 'selected' : ''; ?> >أفغانستان</option>
                                                    <option value="ألبانيا" <?php echo ($selectedCountry == 'ألبانيا') ? 'selected' : ''; ?> >ألبانيا</option>
                                                    <option value="ألمانيا" <?php echo ($selectedCountry == 'ألمانيا') ? 'selected' : ''; ?> >ألمانيا</option>
                                                    <option value="أنتيجوا باربدا" <?php echo ($selectedCountry == 'أنتيجوا باربدا') ? 'selected' : ''; ?> >أنتيجوا باربدا</option>
                                                    <option value="أنجولا" <?php echo ($selectedCountry == 'أنجولا') ? 'selected' : ''; ?> >أنجولا</option>
                                                    <option value="أنجويلا" <?php echo ($selectedCountry == 'أنجويلا') ? 'selected' : ''; ?> >أنجويلا</option>
                                                    <option value="أندر سي فيتشر" <?php echo ($selectedCountry == 'أندر سي فيتشر') ? 'selected' : ''; ?> >أندر سي فيتشر</option>
                                                    <option value="أندورا" <?php echo ($selectedCountry == 'أندورا') ? 'selected' : ''; ?> >أندورا</option>
                                                    <option value="أندونيسيا" <?php echo ($selectedCountry == 'أندونيسيا') ? 'selected' : ''; ?> >أندونيسيا</option>
                                                    <option value="أورجواي" <?php echo ($selectedCountry == 'أورجواي') ? 'selected' : ''; ?> >أورجواي</option>
                                                    <option value="أوزبكستان" <?php echo ($selectedCountry == 'أوزبكستان') ? 'selected' : ''; ?> >أوزبكستان</option>
                                                    <option value="أوغندا" <?php echo ($selectedCountry == 'أوغندا') ? 'selected' : ''; ?> >أوغندا</option>
                                                    <option value="أوكرانيا" <?php echo ($selectedCountry == 'أوكرانيا') ? 'selected' : ''; ?> >أوكرانيا</option>
                                                    <option value="أيرلندا" <?php echo ($selectedCountry == 'أيرلندا') ? 'selected' : ''; ?> >أيرلندا</option>
                                                    <option value="أيسلندا" <?php echo ($selectedCountry == 'أيسلندا') ? 'selected' : ''; ?> >أيسلندا</option>
                                                    <option value="إريتريا" <?php echo ($selectedCountry == 'إريتريا') ? 'selected' : ''; ?> >إريتريا</option>
                                                    <option value="إيران" <?php echo ($selectedCountry == 'إيران') ? 'selected' : ''; ?> >إيران</option>
                                                    <option value="إيطاليا" <?php echo ($selectedCountry == 'إيطاليا') ? 'selected' : ''; ?> >إيطاليا</option>
                                                    <option value="الأرجنتين" <?php echo ($selectedCountry == 'الأرجنتين') ? 'selected' : ''; ?> >الأرجنتين</option>
                                                    <option value="الأردن" <?php echo ($selectedCountry == 'الأردن') ? 'selected' : ''; ?> >الأردن</option>
                                                    <option value="الإكوادور" <?php echo ($selectedCountry == 'الإكوادور') ? 'selected' : ''; ?> >الإكوادور</option>
                                                    <option value="الإمارات العربية المتحدة" <?php echo ($selectedCountry == 'الإمارات العربية المتحدة') ? 'selected' : ''; ?> >الإمارات العربية المتحدة</option>
                                                    <option value="الباهاما" <?php echo ($selectedCountry == 'الباهاما') ? 'selected' : ''; ?> >الباهاما</option>
                                                    <option value="البحرين" <?php echo ($selectedCountry == 'البحرين') ? 'selected' : ''; ?> >البحرين</option>
                                                    <option value="البرازيل" <?php echo ($selectedCountry == 'البرازيل') ? 'selected' : ''; ?> >البرازيل</option>
                                                    <option value="البرتغال" <?php echo ($selectedCountry == 'البرتغال') ? 'selected' : ''; ?> >البرتغال</option>
                                                    <option value="البلوفر" <?php echo ($selectedCountry == 'البلوفر') ? 'selected' : ''; ?> >البلوفر</option>
                                                    <option value="البوسنة والهرسك" <?php echo ($selectedCountry == 'البوسنة والهرسك') ? 'selected' : ''; ?> >البوسنة والهرسك</option>
                                                    <option value="البولينيسيا الفرنسية" <?php echo ($selectedCountry == 'البولينيسيا الفرنسية') ? 'selected' : ''; ?> >البولينيسيا الفرنسية</option>
                                                    <option value="الجابون" <?php echo ($selectedCountry == 'الجابون') ? 'selected' : ''; ?> >الجابون</option>
                                                    <option value="الجاكت" <?php echo ($selectedCountry == 'الجاكت') ? 'selected' : ''; ?> >الجاكت</option>
                                                    <option value="الجزائر" <?php echo ($selectedCountry == 'الجزائر') ? 'selected' : ''; ?> >الجزائر</option>
                                                    <option value="الجزر البريطانية" <?php echo ($selectedCountry == 'الجزر البريطانية') ? 'selected' : ''; ?> >الجزر البريطانية</option>
                                                    <option value="الدانمارك" <?php echo ($selectedCountry == 'الدانمارك') ? 'selected' : ''; ?> >الدانمارك</option>
                                                    <option value="السلفادور" <?php echo ($selectedCountry == 'السلفادور') ? 'selected' : ''; ?> >السلفادور</option>
                                                    <option value="السنغال" <?php echo ($selectedCountry == 'السنغال') ? 'selected' : ''; ?> >السنغال</option>
                                                    <option value="السودان" <?php echo ($selectedCountry == 'السودان') ? 'selected' : ''; ?> >السودان</option>
                                                    <option value="السويد" <?php echo ($selectedCountry == 'السويد') ? 'selected' : ''; ?> >السويد</option>
                                                    <option value="الصومال" <?php echo ($selectedCountry == 'الصومال') ? 'selected' : ''; ?> >الصومال</option>
                                                    <option value="الصين" <?php echo ($selectedCountry == 'الصين') ? 'selected' : ''; ?> >الصين</option>
                                                    <option value="الضفة الغربية" <?php echo ($selectedCountry == 'الضفة الغربية') ? 'selected' : ''; ?> >الضفة الغربية</option>
                                                    <option value="العراق" <?php echo ($selectedCountry == 'العراق') ? 'selected' : ''; ?> >العراق</option>
                                                    <option value="الفاتيكان" <?php echo ($selectedCountry == 'الفاتيكان') ? 'selected' : ''; ?> >الفاتيكان</option>
                                                    <option value="الفلبين" <?php echo ($selectedCountry == 'الفلبين') ? 'selected' : ''; ?> >الفلبين</option>
                                                    <option value="القارة القطبية الجنوبية" <?php echo ($selectedCountry == 'القارة القطبية الجنوبية') ? 'selected' : ''; ?> >القارة القطبية الجنوبية</option>
                                                    <option value="الكاميرون" <?php echo ($selectedCountry == 'الكاميرون') ? 'selected' : ''; ?> >الكاميرون</option>
                                                    <option value="الكونغو" <?php echo ($selectedCountry == 'الكونغو') ? 'selected' : ''; ?> >الكونغو</option>
                                                    <option value="الكويت" <?php echo ($selectedCountry == 'الكويت') ? 'selected' : ''; ?> >الكويت</option>
                                                    <option value="المارتينيخ" <?php echo ($selectedCountry == 'المارتينيخ') ? 'selected' : ''; ?> >المارتينيخ</option>
                                                    <option value="المالديف" <?php echo ($selectedCountry == 'المالديف') ? 'selected' : ''; ?> >المالديف</option>
                                                    <option value="المجر" <?php echo ($selectedCountry == 'المجر') ? 'selected' : ''; ?> >المجر</option>
                                                    <option value="المحيطات" <?php echo ($selectedCountry == 'المحيطات') ? 'selected' : ''; ?> >المحيطات</option>
                                                    <option value="المغرب" <?php echo ($selectedCountry == 'المغرب') ? 'selected' : ''; ?> >المغرب</option>
                                                    <option value="المكسيك" <?php echo ($selectedCountry == 'المكسيك') ? 'selected' : ''; ?> >المكسيك</option>
                                                    <option value="المملكة العربية السعودية" <?php echo ($selectedCountry == 'المملكة العربية السعودية') ? 'selected' : ''; ?> >المملكة العربية السعودية</option>
                                                    <option value="المملكة المتحدة" <?php echo ($selectedCountry == 'المملكة المتحدة') ? 'selected' : ''; ?> >المملكة المتحدة</option>
                                                    <option value="النرويج" <?php echo ($selectedCountry == 'النرويج') ? 'selected' : ''; ?> >النرويج</option>
                                                    <option value="النمسا" <?php echo ($selectedCountry == 'النمسا') ? 'selected' : ''; ?> >النمسا</option>
                                                    <option value="النيجر" <?php echo ($selectedCountry == 'النيجر') ? 'selected' : ''; ?> >النيجر</option>
                                                    <option value="الهند" <?php echo ($selectedCountry == 'الهند') ? 'selected' : ''; ?> >الهند</option>
                                                    <option value="الهند باساس دي" <?php echo ($selectedCountry == 'الهند باساس دي') ? 'selected' : ''; ?> >الهند باساس دي</option>
                                                    <option value="الولايات المتحدة الأمريكية" <?php echo ($selectedCountry == 'الولايات المتحدة الأمريكية') ? 'selected' : ''; ?> >الولايات المتحدة الأمريكية</option>
                                                    <option value="اليابان" <?php echo ($selectedCountry == 'اليابان') ? 'selected' : ''; ?> >اليابان</option>
                                                    <option value="اليمن" <?php echo ($selectedCountry == 'اليمن') ? 'selected' : ''; ?> >اليمن</option>
                                                    <option value="اليونان" <?php echo ($selectedCountry == 'اليونان') ? 'selected' : ''; ?> >اليونان</option>
                                                    <option value="باراجواي" <?php echo ($selectedCountry == 'باراجواي') ? 'selected' : ''; ?> >باراجواي</option>
                                                    <option value="باربادوس" <?php echo ($selectedCountry == 'باربادوس') ? 'selected' : ''; ?> >باربادوس</option>
                                                    <option value="باكستان" <?php echo ($selectedCountry == 'باكستان') ? 'selected' : ''; ?> >باكستان</option>
                                                    <option value="بالو" <?php echo ($selectedCountry == 'بالو') ? 'selected' : ''; ?> >بالو</option>
                                                    <option value="برمودا" <?php echo ($selectedCountry == 'برمودا') ? 'selected' : ''; ?> >برمودا</option>
                                                    <option value="بروناي" <?php echo ($selectedCountry == 'بروناي') ? 'selected' : ''; ?> >بروناي</option>
                                                    <option value="بلجيكا" <?php echo ($selectedCountry == 'بلجيكا') ? 'selected' : ''; ?> >بلجيكا</option>
                                                    <option value="بلغاريا" <?php echo ($selectedCountry == 'بلغاريا') ? 'selected' : ''; ?> >بلغاريا</option>
                                                    <option value="بنجلاديش" <?php echo ($selectedCountry == 'بنجلاديش') ? 'selected' : ''; ?> >بنجلاديش</option>
                                                    <option value="بنما" <?php echo ($selectedCountry == 'بنما') ? 'selected' : ''; ?> >بنما</option>
                                                    <option value="بنين" <?php echo ($selectedCountry == 'بنين') ? 'selected' : ''; ?> >بنين</option>
                                                    <option value="بوتان" <?php echo ($selectedCountry == 'بوتان') ? 'selected' : ''; ?> >بوتان</option>
                                                    <option value="بوتسوانا" <?php echo ($selectedCountry == 'بوتسوانا') ? 'selected' : ''; ?> >بوتسوانا</option>
                                                    <option value="بورتوريكو" <?php echo ($selectedCountry == 'بورتوريكو') ? 'selected' : ''; ?> >بورتوريكو</option>
                                                    <option value="بوركينا فاسو" <?php echo ($selectedCountry == 'بوركينا فاسو') ? 'selected' : ''; ?> >بوركينا فاسو</option>
                                                    <option value="بورما" <?php echo ($selectedCountry == 'بورما') ? 'selected' : ''; ?> >بورما</option>
                                                    <option value="بوروندي" <?php echo ($selectedCountry == 'بوروندي') ? 'selected' : ''; ?> >بوروندي</option>
                                                    <option value="بولندا" <?php echo ($selectedCountry == 'بولندا') ? 'selected' : ''; ?> >بولندا</option>
                                                    <option value="بوليفيا" <?php echo ($selectedCountry == 'بوليفيا') ? 'selected' : ''; ?> >بوليفيا</option>
                                                    <option value="بيرو" <?php echo ($selectedCountry == 'بيرو') ? 'selected' : ''; ?> >بيرو</option>
                                                    <option value="بيليز" <?php echo ($selectedCountry == 'بيليز') ? 'selected' : ''; ?> >بيليز</option>
                                                    <option value="تايلاند" <?php echo ($selectedCountry == 'تايلاند') ? 'selected' : ''; ?> >تايلاند</option>
                                                    <option value="تايوان" <?php echo ($selectedCountry == 'تايوان') ? 'selected' : ''; ?> >تايوان</option>
                                                    <option value="تراينيداد توباجو" <?php echo ($selectedCountry == 'تراينيداد توباجو') ? 'selected' : ''; ?> >تراينيداد توباجو</option>
                                                    <option value="تركيا" <?php echo ($selectedCountry == 'تركيا') ? 'selected' : ''; ?> >تركيا</option>
                                                    <option value="تشاد" <?php echo ($selectedCountry == 'تشاد') ? 'selected' : ''; ?> >تشاد</option>
                                                    <option value="تنزانيا" <?php echo ($selectedCountry == 'تنزانيا') ? 'selected' : ''; ?> >تنزانيا</option>
                                                    <option value="توجو" <?php echo ($selectedCountry == 'توجو') ? 'selected' : ''; ?> >توجو</option>
                                                    <option value="توركمنستان" <?php echo ($selectedCountry == 'توركمنستان') ? 'selected' : ''; ?> >توركمنستان</option>
                                                    <option value="توفالو" <?php echo ($selectedCountry == 'توفالو') ? 'selected' : ''; ?> >توفالو</option>
                                                    <option value="توكيلاو" <?php echo ($selectedCountry == 'توكيلاو') ? 'selected' : ''; ?> >توكيلاو</option>
                                                    <option value="تونجا" <?php echo ($selectedCountry == 'تونجا') ? 'selected' : ''; ?> >تونجا</option>
                                                    <option value="تونس" <?php echo ($selectedCountry == 'تونس') ? 'selected' : ''; ?> >تونس</option>
                                                    <option value="تيمور الشرقية" <?php echo ($selectedCountry == 'تيمور الشرقية') ? 'selected' : ''; ?> >تيمور الشرقية</option>
                                                    <option value="جامبيا" <?php echo ($selectedCountry == 'جامبيا') ? 'selected' : ''; ?> >جامبيا</option>
                                                    <option value="جاميكا" <?php echo ($selectedCountry == 'جاميكا') ? 'selected' : ''; ?> >جاميكا</option>
                                                    <option value="جان ميين" <?php echo ($selectedCountry == 'جان ميين') ? 'selected' : ''; ?> >جان ميين</option>
                                                    <option value="جبل طارق" <?php echo ($selectedCountry == 'جبل طارق') ? 'selected' : ''; ?> >جبل طارق</option>
                                                    <option value="جرينادا" <?php echo ($selectedCountry == 'جرينادا') ? 'selected' : ''; ?> >جرينادا</option>
                                                    <option value="جرينلاند" <?php echo ($selectedCountry == 'جرينلاند') ? 'selected' : ''; ?> >جرينلاند</option>
                                                    <option value="جزر أشمور كارتير" <?php echo ($selectedCountry == 'جزر أشمور كارتير') ? 'selected' : ''; ?> >جزر أشمور كارتير</option>
                                                    <option value="جزر الأنتيل الهولندية" <?php echo ($selectedCountry == 'جزر الأنتيل الهولندية') ? 'selected' : ''; ?> >جزر الأنتيل الهولندية</option>
                                                    <option value="جزر البحر المرجانية" <?php echo ($selectedCountry == 'جزر البحر المرجانية') ? 'selected' : ''; ?> >جزر البحر المرجانية</option>
                                                    <option value="جزر القمر" <?php echo ($selectedCountry == 'جزر القمر') ? 'selected' : ''; ?> >جزر القمر</option>
                                                    <option value="جزر الكوك" <?php echo ($selectedCountry == 'جزر الكوك') ? 'selected' : ''; ?> >جزر الكوك</option>
                                                    <option value="جزر باراسيل" <?php echo ($selectedCountry == 'جزر باراسيل') ? 'selected' : ''; ?> >جزر باراسيل</option>
                                                    <option value="جزر بيتكيرن" <?php echo ($selectedCountry == 'جزر بيتكيرن') ? 'selected' : ''; ?> >جزر بيتكيرن</option>
                                                    <option value="جزر جلوريوسو" <?php echo ($selectedCountry == 'جزر جلوريوسو') ? 'selected' : ''; ?> >جزر جلوريوسو</option>
                                                    <option value="جزر جوز الهند" <?php echo ($selectedCountry == 'جزر جوز الهند') ? 'selected' : ''; ?> >جزر جوز الهند</option>
                                                    <option value="جزر سبراتلي" <?php echo ($selectedCountry == 'جزر سبراتلي') ? 'selected' : ''; ?> >جزر سبراتلي</option>
                                                    <option value="جزر سولومون" <?php echo ($selectedCountry == 'جزر سولومون') ? 'selected' : ''; ?> >جزر سولومون</option>
                                                    <option value="جزر فارو" <?php echo ($selectedCountry == 'جزر فارو') ? 'selected' : ''; ?> >جزر فارو</option>
                                                    <option value="جزر فوكلاند" <?php echo ($selectedCountry == 'جزر فوكلاند') ? 'selected' : ''; ?> >جزر فوكلاند</option>
                                                    <option value="جزر كيكوس التركية" <?php echo ($selectedCountry == 'جزر كيكوس التركية') ? 'selected' : ''; ?> >جزر كيكوس التركية</option>
                                                    <option value="جزر مارشال" <?php echo ($selectedCountry == 'جزر مارشال') ? 'selected' : ''; ?> >جزر مارشال</option>
                                                    <option value="جزر مكدونالد" <?php echo ($selectedCountry == 'جزر مكدونالد') ? 'selected' : ''; ?> >جزر مكدونالد</option>
                                                    <option value="جزرالكايمان" <?php echo ($selectedCountry == 'جزرالكايمان') ? 'selected' : ''; ?> >جزرالكايمان</option>
                                                    <option value="جزيرة الأوروبا" <?php echo ($selectedCountry == 'جزيرة الأوروبا') ? 'selected' : ''; ?> >جزيرة الأوروبا</option>
                                                    <option value="جزيرة الرجل" <?php echo ($selectedCountry == 'جزيرة الرجل') ? 'selected' : ''; ?> >جزيرة الرجل</option>
                                                    <option value="جزيرة الكريستمز" <?php echo ($selectedCountry == 'جزيرة الكريستمز') ? 'selected' : ''; ?> >جزيرة الكريستمز</option>
                                                    <option value="جزيرة بوفيت" <?php echo ($selectedCountry == 'جزيرة بوفيت') ? 'selected' : ''; ?> >جزيرة بوفيت</option>
                                                    <option value="جزيرة تروميلين" <?php echo ($selectedCountry == 'جزيرة تروميلين') ? 'selected' : ''; ?> >جزيرة تروميلين</option>
                                                    <option value="جزيرة جوان دونوفا" <?php echo ($selectedCountry == 'جزيرة جوان دونوفا') ? 'selected' : ''; ?> >جزيرة جوان دونوفا</option>
                                                    <option value="جزيرة كليبيرتون" <?php echo ($selectedCountry == 'جزيرة كليبيرتون') ? 'selected' : ''; ?> >جزيرة كليبيرتون</option>
                                                    <option value="جزيرة نورفولك" <?php echo ($selectedCountry == 'جزيرة نورفولك') ? 'selected' : ''; ?> >جزيرة نورفولك</option>
                                                    <option value="جمهورية إفريقيا الوسطى" <?php echo ($selectedCountry == 'جمهورية إفريقيا الوسطى') ? 'selected' : ''; ?> >جمهورية إفريقيا الوسطى</option>
                                                    <option value="جمهورية التشيك" <?php echo ($selectedCountry == 'جمهورية التشيك') ? 'selected' : ''; ?> >جمهورية التشيك</option>
                                                    <option value="جمهورية الدومينكان" <?php echo ($selectedCountry == 'جمهورية الدومينكان') ? 'selected' : ''; ?> >جمهورية الدومينكان</option>
                                                    <option value="جمهورية الكونغو الديمقراطية" <?php echo ($selectedCountry == 'جمهورية الكونغو الديمقراطية') ? 'selected' : ''; ?> >جمهورية الكونغو الديمقراطية</option>
                                                    <option value="جنوب أفريقيا" <?php echo ($selectedCountry == 'جنوب أفريقيا') ? 'selected' : ''; ?> >جنوب أفريقيا</option>
                                                    <option value="جوادلوب" <?php echo ($selectedCountry == 'جوادلوب') ? 'selected' : ''; ?> >جوادلوب</option>
                                                    <option value="جورجيا" <?php echo ($selectedCountry == 'جورجيا') ? 'selected' : ''; ?> >جورجيا</option>
                                                    <option value="جورجيا الجنوبية" <?php echo ($selectedCountry == 'جورجيا الجنوبية') ? 'selected' : ''; ?> >جورجيا الجنوبية</option>
                                                    <option value="جويانا" <?php echo ($selectedCountry == 'جويانا') ? 'selected' : ''; ?> >جويانا</option>
                                                    <option value="جيبوتي" <?php echo ($selectedCountry == 'جيبوتي') ? 'selected' : ''; ?> >جيبوتي</option>
                                                    <option value="دومينيكا" <?php echo ($selectedCountry == 'دومينيكا') ? 'selected' : ''; ?> >دومينيكا</option>
                                                    <option value="رواندا" <?php echo ($selectedCountry == 'رواندا') ? 'selected' : ''; ?> >رواندا</option>
                                                    <option value="روسيا" <?php echo ($selectedCountry == 'روسيا') ? 'selected' : ''; ?> >روسيا</option>
                                                    <option value="روسيا البيضاء" <?php echo ($selectedCountry == 'روسيا البيضاء') ? 'selected' : ''; ?> >روسيا البيضاء</option>
                                                    <option value="رومانيا" <?php echo ($selectedCountry == 'رومانيا') ? 'selected' : ''; ?> >رومانيا</option>
                                                    <option value="ريونيون" <?php echo ($selectedCountry == 'ريونيون') ? 'selected' : ''; ?> >ريونيون</option>
                                                    <option value="زامبيا" <?php echo ($selectedCountry == 'زامبيا') ? 'selected' : ''; ?> >زامبيا</option>
                                                    <option value="زيمبابوي" <?php echo ($selectedCountry == 'زيمبابوي') ? 'selected' : ''; ?> >زيمبابوي</option>
                                                    <option value="سامو" <?php echo ($selectedCountry == 'سامو') ? 'selected' : ''; ?> >سامو</option>
                                                    <option value="سان مارينو" <?php echo ($selectedCountry == 'سان مارينو') ? 'selected' : ''; ?> >سان مارينو</option>
                                                    <option value="سانت بيير ميكيلون" <?php echo ($selectedCountry == 'سانت بيير ميكيلون') ? 'selected' : ''; ?> >سانت بيير ميكيلون</option>
                                                    <option value="سانت فينسينت" <?php echo ($selectedCountry == 'سانت فينسينت') ? 'selected' : ''; ?> >سانت فينسينت</option>
                                                    <option value="سانت كيتس نيفيس" <?php echo ($selectedCountry == 'سانت كيتس نيفيس') ? 'selected' : ''; ?> >سانت كيتس نيفيس</option>
                                                    <option value="سانت لوتشيا" <?php echo ($selectedCountry == 'سانت لوتشيا') ? 'selected' : ''; ?> >سانت لوتشيا</option>
                                                    <option value="سانت هيلينا" <?php echo ($selectedCountry == 'سانت هيلينا') ? 'selected' : ''; ?> >سانت هيلينا</option>
                                                    <option value="ساو توم" <?php echo ($selectedCountry == 'ساو توم') ? 'selected' : ''; ?> >ساو توم</option>
                                                    <option value="سريلانكا" <?php echo ($selectedCountry == 'سريلانكا') ? 'selected' : ''; ?> >سريلانكا</option>
                                                    <option value="سفالبارد" <?php echo ($selectedCountry == 'سفالبارد') ? 'selected' : ''; ?> >سفالبارد</option>
                                                    <option value="سلوفاكيا" <?php echo ($selectedCountry == 'سلوفاكيا') ? 'selected' : ''; ?> >سلوفاكيا</option>
                                                    <option value="سلوفانيا" <?php echo ($selectedCountry == 'سلوفانيا') ? 'selected' : ''; ?> >سلوفانيا</option>
                                                    <option value="سنغافورة" <?php echo ($selectedCountry == 'سنغافورة') ? 'selected' : ''; ?> >سنغافورة</option>
                                                    <option value="سوازيلاند" <?php echo ($selectedCountry == 'سوازيلاند') ? 'selected' : ''; ?> >سوازيلاند</option>
                                                    <option value="سوريا" <?php echo ($selectedCountry == 'سوريا') ? 'selected' : ''; ?> >سوريا</option>
                                                    <option value="سورينام" <?php echo ($selectedCountry == 'سورينام') ? 'selected' : ''; ?> >سورينام</option>
                                                    <option value="سويسرا" <?php echo ($selectedCountry == 'سويسرا') ? 'selected' : ''; ?> >سويسرا</option>
                                                    <option value="سيراليون" <?php echo ($selectedCountry == 'سيراليون') ? 'selected' : ''; ?> >سيراليون</option>
                                                    <option value="سيشل" <?php echo ($selectedCountry == 'سيشل') ? 'selected' : ''; ?> >سيشل</option>
                                                    <option value="شيلي" <?php echo ($selectedCountry == 'شيلي') ? 'selected' : ''; ?> >شيلي</option>
                                                    <option value="طاجكستان" <?php echo ($selectedCountry == 'طاجكستان') ? 'selected' : ''; ?> >طاجكستان</option>
                                                    <option value="عمان" <?php echo ($selectedCountry == 'عمان') ? 'selected' : ''; ?> >عمان</option>
                                                    <option value="غانا" <?php echo ($selectedCountry == 'غانا') ? 'selected' : ''; ?> >غانا</option>
                                                    <option value="غواتيمالا" <?php echo ($selectedCountry == 'غواتيمالا') ? 'selected' : ''; ?> >غواتيمالا</option>
                                                    <option value="غينيا" <?php echo ($selectedCountry == 'غينيا') ? 'selected' : ''; ?> >غينيا</option>
                                                    <option value="غينيا الاستوائية" <?php echo ($selectedCountry == 'غينيا الاستوائية') ? 'selected' : ''; ?> >غينيا الاستوائية</option>
                                                    <option value="غينيا الجديدة" <?php echo ($selectedCountry == 'غينيا الجديدة') ? 'selected' : ''; ?> >غينيا الجديدة</option>
                                                    <option value="غينيا الفرنسية" <?php echo ($selectedCountry == 'غينيا الفرنسية') ? 'selected' : ''; ?> >غينيا الفرنسية</option>
                                                    <option value="فانوتو" <?php echo ($selectedCountry == 'فانوتو') ? 'selected' : ''; ?> >فانوتو</option>
                                                    <option value="فرنسا" <?php echo ($selectedCountry == 'فرنسا') ? 'selected' : ''; ?> >فرنسا</option>
                                                    <option value="فلسطين" <?php echo ($selectedCountry == 'فلسطين') ? 'selected' : ''; ?> >فلسطين</option>
                                                    <option value="فنزويلا" <?php echo ($selectedCountry == 'فنزويلا') ? 'selected' : ''; ?> >فنزويلا</option>
                                                    <option value="فنلندا" <?php echo ($selectedCountry == 'فنلندا') ? 'selected' : ''; ?> >فنلندا</option>
                                                    <option value="فيتنام" <?php echo ($selectedCountry == 'فيتنام') ? 'selected' : ''; ?> >فيتنام</option>
                                                    <option value="فيجي" <?php echo ($selectedCountry == 'فيجي') ? 'selected' : ''; ?> >فيجي</option>
                                                    <option value="قبرص" <?php echo ($selectedCountry == 'قبرص') ? 'selected' : ''; ?> >قبرص</option>
                                                    <option value="قطر" <?php echo ($selectedCountry == 'قطر') ? 'selected' : ''; ?> >قطر</option>
                                                    <option value="كازاكستان" <?php echo ($selectedCountry == 'كازاكستان') ? 'selected' : ''; ?> >كازاكستان</option>
                                                    <option value="كالدونيا الجديدة" <?php echo ($selectedCountry == 'كالدونيا الجديدة') ? 'selected' : ''; ?> >كالدونيا الجديدة</option>
                                                    <option value="كامبوديا" <?php echo ($selectedCountry == 'كامبوديا') ? 'selected' : ''; ?> >كامبوديا</option>
                                                    <option value="كرواتيا" <?php echo ($selectedCountry == 'كرواتيا') ? 'selected' : ''; ?> >كرواتيا</option>
                                                    <option value="كندا" <?php echo ($selectedCountry == 'كندا') ? 'selected' : ''; ?> >كندا</option>
                                                    <option value="كوبا" <?php echo ($selectedCountry == 'كوبا') ? 'selected' : ''; ?> >كوبا</option>
                                                    <option value="كوتي دلفوير" <?php echo ($selectedCountry == 'كوتي دلفوير') ? 'selected' : ''; ?> >كوتي دلفوير</option>
                                                    <option value="كورجستان" <?php echo ($selectedCountry == 'كورجستان') ? 'selected' : ''; ?> >كورجستان</option>
                                                    <option value="كوريا الجنوبية" <?php echo ($selectedCountry == 'كوريا الجنوبية') ? 'selected' : ''; ?> >كوريا الجنوبية</option>
                                                    <option value="كوريا الشمالية" <?php echo ($selectedCountry == 'كوريا الشمالية') ? 'selected' : ''; ?> >كوريا الشمالية</option>
                                                    <option value="كوستا ريكا" <?php echo ($selectedCountry == 'كوستا ريكا') ? 'selected' : ''; ?> >كوستا ريكا</option>
                                                    <option value="كولومبيا" <?php echo ($selectedCountry == 'كولومبيا') ? 'selected' : ''; ?> >كولومبيا</option>
                                                    <option value="كيب فيردي" <?php echo ($selectedCountry == 'كيب فيردي') ? 'selected' : ''; ?> >كيب فيردي</option>
                                                    <option value="كيريباتي" <?php echo ($selectedCountry == 'كيريباتي') ? 'selected' : ''; ?> >كيريباتي</option>
                                                    <option value="كينيا" <?php echo ($selectedCountry == 'كينيا') ? 'selected' : ''; ?> >كينيا</option>
                                                    <option value="لاتفيا" <?php echo ($selectedCountry == 'لاتفيا') ? 'selected' : ''; ?> >لاتفيا</option>
                                                    <option value="لاو" <?php echo ($selectedCountry == 'لاو') ? 'selected' : ''; ?> >لاو</option>
                                                    <option value="لبنان" <?php echo ($selectedCountry == 'لبنان') ? 'selected' : ''; ?> >لبنان</option>
                                                    <option value="لوكسمبورج" <?php echo ($selectedCountry == 'لوكسمبورج') ? 'selected' : ''; ?> >لوكسمبورج</option>
                                                    <option value="ليبيا" <?php echo ($selectedCountry == 'ليبيا') ? 'selected' : ''; ?> >ليبيا</option>
                                                    <option value="ليبيريا" <?php echo ($selectedCountry == 'ليبيريا') ? 'selected' : ''; ?> >ليبيريا</option>
                                                    <option value="ليتوانيا" <?php echo ($selectedCountry == 'ليتوانيا') ? 'selected' : ''; ?> >ليتوانيا</option>
                                                    <option value="ليختنشتين" <?php echo ($selectedCountry == 'ليختنشتين') ? 'selected' : ''; ?> >ليختنشتين</option>
                                                    <option value="ليسوتو" <?php echo ($selectedCountry == 'ليسوتو') ? 'selected' : ''; ?> >ليسوتو</option>
                                                    <option value="ماكاو" <?php echo ($selectedCountry == 'ماكاو') ? 'selected' : ''; ?> >ماكاو</option>
                                                    <option value="مالاوي" <?php echo ($selectedCountry == 'مالاوي') ? 'selected' : ''; ?> >مالاوي</option>
                                                    <option value="مالطا" <?php echo ($selectedCountry == 'مالطا') ? 'selected' : ''; ?> >مالطا</option>
                                                    <option value="مالي" <?php echo ($selectedCountry == 'مالي') ? 'selected' : ''; ?> >مالي</option>
                                                    <option value="ماليزيا" <?php echo ($selectedCountry == 'ماليزيا') ? 'selected' : ''; ?> >ماليزيا</option>
                                                    <option value="مدغشقر" <?php echo ($selectedCountry == 'مدغشقر') ? 'selected' : ''; ?> >مدغشقر</option>
                                                    <option value="مصر" <?php echo ($selectedCountry == 'مصر') ? 'selected' : ''; ?> >مصر</option>
                                                    <option value="مقدونيا" <?php echo ($selectedCountry == 'مقدونيا') ? 'selected' : ''; ?> >مقدونيا</option>
                                                    <option value="منغوليا" <?php echo ($selectedCountry == 'منغوليا') ? 'selected' : ''; ?> >منغوليا</option>
                                                    <option value="موريتانيا" <?php echo ($selectedCountry == 'موريتانيا') ? 'selected' : ''; ?> >موريتانيا</option>
                                                    <option value="موريشيوس" <?php echo ($selectedCountry == 'موريشيوس') ? 'selected' : ''; ?> >موريشيوس</option>
                                                    <option value="موزمبيق" <?php echo ($selectedCountry == 'موزمبيق') ? 'selected' : ''; ?> >موزمبيق</option>
                                                    <option value="مولدافيا" <?php echo ($selectedCountry == 'مولدافيا') ? 'selected' : ''; ?> >مولدافيا</option>
                                                    <option value="موناكو" <?php echo ($selectedCountry == 'موناكو') ? 'selected' : ''; ?> >موناكو</option>
                                                    <option value="مونتسيرات" <?php echo ($selectedCountry == 'مونتسيرات') ? 'selected' : ''; ?> >مونتسيرات</option>
                                                    <option value="ميكرونيسيا" <?php echo ($selectedCountry == 'ميكرونيسيا') ? 'selected' : ''; ?> >ميكرونيسيا</option>
                                                    <option value="ميوت" <?php echo ($selectedCountry == 'ميوت') ? 'selected' : ''; ?> >ميوت</option>
                                                    <option value="ناميبيا" <?php echo ($selectedCountry == 'ناميبيا') ? 'selected' : ''; ?> >ناميبيا</option>
                                                    <option value="نورو" <?php echo ($selectedCountry == 'نورو') ? 'selected' : ''; ?> >نورو</option>
                                                    <option value="نومانز لاند" <?php echo ($selectedCountry == 'نومانز لاند') ? 'selected' : ''; ?> >نومانز لاند</option>
                                                    <option value="ني" <?php echo ($selectedCountry == 'ني') ? 'selected' : ''; ?> >ني</option>
                                                    <option value="نيبال" <?php echo ($selectedCountry == 'نيبال') ? 'selected' : ''; ?> >نيبال</option>
                                                    <option value="نيجيريا" <?php echo ($selectedCountry == 'نيجيريا') ? 'selected' : ''; ?> >نيجيريا</option>
                                                    <option value="نيكاراجوا" <?php echo ($selectedCountry == 'نيكاراجوا') ? 'selected' : ''; ?> >نيكاراجوا</option>
                                                    <option value="نيوزيلندا" <?php echo ($selectedCountry == 'نيوزيلندا') ? 'selected' : ''; ?> >نيوزيلندا</option>
                                                    <option value="هايتي" <?php echo ($selectedCountry == 'هايتي') ? 'selected' : ''; ?> >هايتي</option>
                                                    <option value="هندوراس" <?php echo ($selectedCountry == 'هندوراس') ? 'selected' : ''; ?> >هندوراس</option>
                                                    <option value="هولندا" <?php echo ($selectedCountry == 'هولندا') ? 'selected' : ''; ?> >هولندا</option>
                                                    <option value="هونج كونج" <?php echo ($selectedCountry == 'هونج كونج') ? 'selected' : ''; ?> >هونج كونج</option>
                                                    <option value="واليس فيوتنا" <?php echo ($selectedCountry == 'واليس فيوتنا') ? 'selected' : ''; ?> >واليس فيوتنا</option>
                                                    <option value="يوغسلافيا" <?php echo ($selectedCountry == 'يوغسلافيا') ? 'selected' : ''; ?> >يوغسلافيا</option>
                                                </select>
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">البريد الالكتروني<font style="color: #aa0820;">*</font></p>
                                                <?php
                                                    $email = $erow['email'];
                                                    $decrypted_email = openssl_decrypt($email, $cipher, $key, $options, $iv);
                                                ?>
                                                <input class="form-input" name="email" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($decrypted_email); } else{ echo ''; }?>" type="email" required>
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">الحالة الاجتماعية</p>
                                                <input type="text" class="form-input" name="social" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['social']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">الهاتف المتحرك 1 <font style="color: #aa0820;">*</font></p>
                                                <?php
                                                    $tel1 = $erow['tel1'];
                                                    $decrypted_tel1 = openssl_decrypt($tel1, $cipher, $key, $options, $iv);
                                                ?>
                                                <input type="text" class="form-input" name="tel1" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($decrypted_tel1); } else{ echo ''; }?>" required>
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">الهاتف المتحرك 2</p>
                                                <?php
                                                    $tel2 = $erow['tel2'];
                                                    $decrypted_tel2 = openssl_decrypt($tel2, $cipher, $key, $options, $iv);
                                                ?>
                                                <input type="text" class="form-input" name="tel2" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($decrypted_tel2); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">عنوان السكن</p>
                                                <?php
                                                    $address = $erow['address'];
                                                    $decrypted_address = openssl_decrypt($address, $cipher, $key, $options, $iv);
                                                ?>
                                                <textarea class="form-input" name="address" rows="2"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($decrypted_address); } else{ echo ''; }?></textarea>
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">الاسم 1</p>
                                                <input type="text" class="form-input" name="emergency_name1" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['emergency_name1']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">صلة القرابة</p>
                                                <input type="text" class="form-input" name="emergency_relate1" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['emergency_relate1']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">رقم الهاتف</p>
                                                <input type="text" class="form-input" name="emergency_tel1" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['emergency_tel1']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">الاسم 2</p>
                                                <input type="text" class="form-input" name="emergency_name2" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['emergency_name2']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">صلة القرابة</p>
                                                <input type="text" class="form-input" name="emergency_relate2" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['emergency_relate2']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">رقم الهاتف</p>
                                                <input type="text" class="form-input" name="emergency_tel2" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['emergency_tel2']); } else{ echo ''; }?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="stepinfo" id="stepinfo2">
                                        <div class="advinputs3">
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">اسم الدخول<font style="color: #aa0820;">*</font></p>
                                                <input class="form-input" name="username" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['username']); } else{ echo ''; }?>" type="text" required>
                                            </div>
                                            <p></p>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">كلمة المرور<font style="color: #aa0820;">*</font></p>
                                                <?php
                                                    $password = $erow['password'];
                                                    $decrypted_password = openssl_decrypt($password, $cipher, $key, $options, $iv);
                                                ?>
                                                <input class="form-input" name="password" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($decrypted_password); } else{ echo ''; }?>" type="text" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="stepinfo" id="stepinfo3">
                                        <div class="advinputs3">
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">المسمى الوظيفي<font color="red"> *</font> <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="MM_openBrWindow('selector/AddPosition.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=400')" align="absmiddle" style="cursor:pointer" loading="lazy"></p>
                                                <?php $selectedjt = $erow['job_title'];?>
                                                <select class="table-header-selector" name="job_title" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px; height: fit-content" dir="rtl">
                                                    <?php 
                                                        $stmtjs = $conn->prepare("SELECT * FROM positions");
                                                        $stmtjs->execute();
                                                        $resultjs = $stmtjs->get_result();
                                                    if($resultjs->num_rows > 0){
                                                        while($rowjs = $resultjs->fetch_assoc()){
                                                    ?>
                                                    <option value="<?php echo safe_output($rowjs['id']);?>" <?php if($selectedre === $rowjs['id']){ echo 'selected'; }?>><?php echo safe_output($rowjs['position_name']);?></option>
                                                    <?php }}?>
                                                </select>
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">يعمل فى فرع</p>
                                                <?php $selectedwp = $erow['work_place'];?>
                                                <select class="table-header-selector" name="work_place" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px; height: fit-content" dir="rtl">
                                                    <option value="Dubai" <?php if($selectedwp === 'Dubai'){ echo 'selected'; }?>><font id="clients-translate">Dubai</font></option>
                                                    <option value="Sharjah" <?php if($selectedwp === 'Sharjah'){ echo 'selected'; }?>><font id="opponents-translate">Sharjah</font></option>
                                                    <option value="Ajman" <?php if($selectedwp === 'Ajman'){ echo 'selected'; }?>><font id="subs-translate">Ajman</font></option>
                                                    <?php
                                                        $stmtbranchs = $conn->prepare("SELECT * FROM branchs");
                                                        $stmtbranchs->execute();
                                                        $resultbranchs = $stmtbranchs->get_result();
                                                        if($resultbranchs->num_rows > 0){
                                                            while($rowbranchs = $resultbranchs->fetch_assoc()){
                                                    ?>
                                                    <option value="<?php echo safe_output($rowbranchs['branch']);?>"<?php if($selectedwp === $rowbranchs['branch']){ echo 'selected'; };?>><?php echo safe_output($rowbranchs['branch']);?></option>
                                                    <?php
                                                            }
                                                        }
                                                        $stmtbranchs->close();
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">القسم</p>
                                                <input type="text" class="form-input" name="section" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['section']); } else{ echo ''; }?>">
                                            </div>
                                            <?php if($_GET['id'] !== $myid || $admin == 1){?>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">نوع العقد</p>
                                                <?php $selectedct = $erow['app_type'];?>
                                                <select class="table-header-selector" name="app_type" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px; height: fit-content" dir="rtl">
                                                    <option value="كامل" <?php if($selectedct === 'كامل'){ echo 'selected'; }?>>كامل</option>
                                                    <option value="جزئي" <?php if($selectedct === 'جزئي'){ echo 'selected'; }?>>جزئي</option>
                                                    <option value="متدرب" <?php if($selectedct === 'متدرب'){ echo 'selected'; }?>>متدرب</option>
                                                </select>
                                            </div>
                                            <p></p>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">تاريخ انتهاء عقد العمل</p>
                                                <input type="date" class="form-input" name="contract_exp" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['contract_exp']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">تاريخ التعيين</p>
                                                <input type="date" class="form-input" name="app_date" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['app_date']); } else{ echo ''; }?>">
                                            </div>
                                            <p></p>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">المسؤول المباشر</p>
                                                <?php $selectedre = $erow['responsible'];?>
                                                <select class="table-header-selector" name="responsible" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px; height: fit-content" dir="rtl">
                                                    <option value=""></option>
                                                    <?php
                                                        $stmtu = $conn->prepare("SELECT * FROM user");
                                                        $stmtu->execute();
                                                        $resultu = $stmtu->get_result();
                                                        while($rowru = $resultu->fetch_assoc()){
                                                    ?>
                                                    <option value="<?php echo safe_output($rowru['id']);?>" <?php if($selectedre === $rowru['id']){ echo 'selected'; }?>><?php echo safe_output($rowru['name']);?></option>
                                                    <?php 
                                                        }
                                                        $stmtu->close();
                                                    ?>
                                                </select>
                                            </div>
                                            <?php }?>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">رقم الاقامة</p>
                                                <input type="text" class="form-input" name="residence_no" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['residence_no']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">تاريخ صدور الإقامة</p>
                                                <input type="date" class="form-input" name="residence_date" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['residence_date']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">تاريخ انتهاء الإقامة</p>
                                                <input type="date" class="form-input" name="residence_exp" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['residence_exp']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">تاريخ انتهاء قيد المندوب</p>
                                                <input type="date" class="form-input" name="representative_exp" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['representative_exp']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">تاريخ انتهاء قيد المحامي / دبي</p>
                                                <input type="date" class="form-input" name="dxblaw_exp" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['dxblaw_exp']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">تاريخ انتهاء قيد المحامي / الشارقة</p>
                                                <input type="date" class="form-input" name="shjlaw_exp" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['shjlaw_exp']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">تاريخ انتهاء قيد المحامي / عجمان</p>
                                                <input type="date" class="form-input" name="ajmlaw_exp" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['ajmlaw_exp']); } else{ echo ''; }?>">
                                            </div>
                                            <p></p>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">تاريخ انتهاء قيد المحامي / ابو ظبي</p>
                                                <input type="date" class="form-input" name="abdlaw_exp" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['abdlaw_exp']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">اسم البنك</p>
                                                <input type="text" class="form-input" name="bank_name" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['bank_name']); } else{ echo ''; }?>">
                                            </div>
                                            <p></p>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">رقم الايبان</p>
                                                <input type="text" class="form-input" name="iban" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['iban']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">رقم الحساب</p>
                                                <input type="text" class="form-input" name="acc_no" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['acc_no']); } else{ echo ''; }?>">
                                            </div>
                                            <p></p>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">طريقة الدفع</p>
                                                <input type="text" class="form-input" name="pay_way" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['pay_way']); } else{ echo ''; }?>">
                                            </div>
                                            <?php if($_GET['id'] !== $myid || $owner == 1){?>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">الراتب الاساسي</p>
                                                <input type="text" class="form-input" name="basic_salary" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['basic_salary']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">تذاكر السفر</p>
                                                <input type="text" class="form-input" name="travel_tickets" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['travel_tickets']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">البترول</p>
                                                <input type="text" class="form-input" name="oil_cost" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['oil_cost']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">السكن</p>
                                                <input type="text" class="form-input" name="housing_cost" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['housing_cost']); } else{ echo ''; }?>">
                                            </div>
                                            <p></p>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">المعيشة</p>
                                                <input type="text" class="form-input" name="living_cost" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['living_cost']); } else{ echo ''; }?>">
                                            </div>
                                            <?php }?>
                                        </div>
                                    </div>
                                    <div class="stepinfo" id="stepinfo4">
                                        <div class="advinputs3">
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">رقم جواز السفر<font style="color: #aa0820;">*</font></p>
                                                <?php
                                                    $passport_no = $erow['passport_no'];
                                                    $decrypted_passport_no = openssl_decrypt($passport_no, $cipher, $key, $options, $iv);
                                                ?>
                                                <input type="text" class="form-input" name="passport_no" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($decrypted_passport_no); } else{ echo ''; }?>" required>
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">رقم بطاقة العمل</p>
                                                <input type="text" class="form-input" name="card_no" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['card_no']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">تاريخ انتهاء جواز السفر</p>
                                                <input type="date" class="form-input" name="passport_exp" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['passport_exp']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">رقم الهوية</p>
                                                <input type="text" class="form-input" name="idno" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['idno']); } else{ echo ''; }?>">
                                            </div>
                                            <p></p>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">تاريخ انتهاء الهوية</p>
                                                <input type="date" class="form-input" name="id_exp" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['id_exp']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">تاريخ انتهاء بطاقة العمل</p>
                                                <input type="date" class="form-input" name="cardno_exp" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['cardno_exp']); } else{ echo ''; }?>">
                                            </div>
                                            <p></p>
                                            <div class="input-container">
                                                <p class="input-parag blue-parag">تاريخ انتهاء التأمين الصحي</p>
                                                <input type="date" class="form-input" name="sigorta_exp" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['sigorta_exp']); } else{ echo ''; }?>">
                                            </div>
                                            <div class="input-container">
                                                <h4 class="input-parag blue-parag" style="padding-bottom: 10px;">صورة شخصية</h4>
                                                <div class="drop-zone" id="dropZone1">
                                                    <input type="file" id="fileInput1" name="personal_image" hidden>
                                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon" loading="lazy">
                                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput1').click()">إرفاق مستند</span></p>
                                                </div>
                                                <div id="fileList1" style="font-size: 14px"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['personal_image'] !== ''){ echo '<p>📄'.basename(safe_output($erow['personal_image'])).'</p>'; }?></div>
                                            </div>
                                            <div class="input-container">
                                                <h4 class="input-parag blue-parag" style="padding-bottom: 10px;">جواز السفر</h4>
                                                <div class="drop-zone" id="dropZone2">
                                                    <input type="file" id="fileInput2" name="passport_residence" hidden>
                                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon" loading="lazy">
                                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput2').click()">إرفاق مستند</span></p>
                                                </div>
                                                <div id="fileList2" style="font-size: 14px"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['passport_residence'] !== ''){ echo '<p>📄'.basename(safe_output($erow['passport_residence'])).'</p>'; }?></div>
                                            </div>
                                            <div class="input-container">
                                                <?php
                                                    $usid = $_GET['id'];
                                                    $estmt2 = $conn->prepare("SELECT * FROM user_attachments WHERE user_id=?");
                                                    $estmt2->bind_param("i", $usid);
                                                    $estmt2->execute();
                                                    $eresult2 = $estmt2->get_result();
                                                    $erow2 = $eresult2->fetch_assoc();
                                                ?>
                                                <h4 class="input-parag blue-parag" style="padding-bottom: 10px;">المؤهل العملي</h4>
                                                <div class="drop-zone" id="dropZone3">
                                                    <input type="file" id="fileInput3" name="practical_qualification" hidden>
                                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon" loading="lazy">
                                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput3').click()">إرفاق مستند</span></p>
                                                </div>
                                                <div id="fileList3" style="font-size: 14px"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['practical_qualification'] !== ''){ echo '<p>📄'.basename(safe_output($erow['practical_qualification'])).'</p>'; }?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="stepinfo" id="stepinfo5">
                                        <div class="moreinps-container">
                                            <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                                <span><i class="bx bx-data"></i> <p>صلاحيات على النظام</p></span> 
                                                <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                                <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                            </button>
                                            <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                                <table class="info-table" style="width: 100%;">
                                                    <thead>
                                                        <tr class="infotable-header" style="color: #fff; background-color: #125386;">
                                                            <td align="center"></td>
                                                            <td align="center">استعلام</td>
                                                            <td align="center">اضافة</td>
                                                            <td align="center">تعديل</td>
                                                            <td align="center">حذف</td>
                                                            <td align="center">ارشفة</td>
                                                            <td align="center">طباعة</td>
                                                        </tr>
                                                    </thead>
                                                    
                                                    <tbody>
                                                        <tr class="infotable-body">
                                                            <td align="center"> الخَيارات :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="selectors_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['selectors_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="selectors_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['selectors_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="selectors_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['selectors_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="selectors_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['selectors_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center"> ساعات العمل :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="workingtime_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['workingtime_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="workingtime_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['workingtime_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="workingtime_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['workingtime_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="workingtime_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['workingtime_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">التذكيرات :</td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="PERMISSIONS NEEDED!" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['PERMISSIONS NEEDED!'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="moreinps-container">
                                            <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                                <span><i class="bx bxs-briefcase bx-xs"></i> <p>صلاحيات الملفات</p></span> 
                                                <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                                <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                            </button>
                                            <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                                <table class="info-table" style="width: 100%;">
                                                    <thead>
                                                        <tr class="infotable-header" style="color: #fff; background-color: #125386;">
                                                            <td align="center"></td>
                                                            <td align="center">استعلام</td>
                                                            <td align="center">اضافة</td>
                                                            <td align="center">تعديل</td>
                                                            <td align="center">حذف</td>
                                                            <td align="center">ارشفة</td>
                                                            <td align="center">طباعة</td>
                                                        </tr>
                                                    </thead>
                                                    
                                                    <tbody>
                                                        <tr class="infotable-body">
                                                            <td align="center"> ملفات القضايا :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="cfiles_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['cfiles_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="cfiles_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['cfiles_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="cfiles_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['cfiles_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="cfiles_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['cfiles_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="cfiles_archive_perm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['cfiles_archive_perm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center"> تعيين ملفات سرية :</td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="secretf_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['secretf_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">الجلسات :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="session_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['session_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="session_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['session_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="session_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['session_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="session_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['session_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">رول الجلسات  :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="sessionrole_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['sessionrole_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">مرحلة الملف :</td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="levels_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['levels_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">تحديد لون الملف حسب نوعة :</td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="typecolor_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['typecolor_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">درجة التقاضي :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="degree_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['degree_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="degree_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['degree_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="degree_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['degree_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="degree_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['degree_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">الانذارات العدلية :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="judicialwarn_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['judicialwarn_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="judicialwarn_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['judicialwarn_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="judicialwarn_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['judicialwarn_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="judicialwarn_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['judicialwarn_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">الاوامر على عريضة :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="petition_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['petition_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="petition_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['petition_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="petition_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['petition_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="petition_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['petition_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center"> المهام الادارية :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="admjobs_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['admjobs_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="admjobs_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['admjobs_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="admjobs_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['admjobs_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="admjobs_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['admjobs_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="admjobs_pperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['admjobs_pperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">مهام ادارية خاصة :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="admprivjobs_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['admprivjobs_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">المرفقات :</td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="attachments_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['attachments_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">المذكرات :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="note_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['note_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="note_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['note_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="note_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['note_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="note_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['note_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">الاعتماد المبدئي للمذكرة</td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="doc_faperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['doc_faperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">الاعتماد النهائي للمذكرة</td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="doc_laperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['doc_laperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">ساعات قابلة للفوترة :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="ASKFIRST!" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['ASKFIRST!'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="ASKFIRST!" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['ASKFIRST!'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="ASKFIRST!" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['ASKFIRST!'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="ASKFIRST!" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['ASKFIRST!'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="moreinps-container">
                                            <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                                <span><i class="bx bxs-user"></i> <p>صلاحيات الموكلين</p></span> 
                                                <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                                <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                            </button>
                                            <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                                <table class="info-table" style="width: 100%;">
                                                    <thead>
                                                        <tr class="infotable-header" style="color: #fff; background-color: #125386;">
                                                            <td align="center"></td>
                                                            <td align="center">استعلام</td>
                                                            <td align="center">اضافة</td>
                                                            <td align="center">تعديل</td>
                                                            <td align="center">حذف</td>
                                                            <td align="center">ارشفة</td>
                                                            <td align="center">طباعة</td>
                                                        </tr>
                                                    </thead>
                                                    
                                                    <tbody>
                                                        <tr class="infotable-body">
                                                            <td align="center">الموكلين / الخصوم :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="clients_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['clients_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="clients_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['clients_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="clients_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['clients_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="clients_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['clients_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">ارسال الطلبات عن طريق الواتس :</td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="PERMISSIONS NEEDED!" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['PERMISSIONS NEEDED!'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">ارسال الطلبات عن طريق الايميل :</td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="PERMISSIONS NEEDED!" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['PERMISSIONS NEEDED!'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">مواعيد الموكلين :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="csched_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['csched_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="csched_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['csched_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="csched_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['csched_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="csched_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['csched_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">الموكلين المحتملين :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="cons_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['cons_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="cons_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['cons_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="cons_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['cons_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="cons_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['cons_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">سجل المكالمات الهاتفية :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="call_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['call_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="call_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['call_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="call_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['call_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="call_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['call_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">كشف قائمة الارهاب المحلية goAML :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="goaml_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['goaml_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="goaml_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['goaml_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="goaml_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['goaml_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">قائمة الارهاب :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="terror_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['terror_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="terror_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['terror_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="terror_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['terror_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">الاتفاقيات :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="agr_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['agr_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="agr_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['agr_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="agr_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['agr_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="agr_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['agr_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">ساعات قابلة للفوترة :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="ASKFIRST!" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['ASKFIRST!'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="ASKFIRST!" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['ASKFIRST!'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="ASKFIRST!" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['ASKFIRST!'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="ASKFIRST!" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['ASKFIRST!'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="moreinps-container">
                                            <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                                <span><i class="bx bxs-bank"></i> <p>ادارة المالية</p></span> 
                                                <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                                <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                            </button>
                                            <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                                <table class="info-table" style="width: 100%;">
                                                    <thead>
                                                        <tr class="infotable-header" style="color: #fff; background-color: #125386;">
                                                            <td align="center"></td>
                                                            <td align="center">استعلام</td>
                                                            <td align="center">اضافة</td>
                                                            <td align="center">تعديل</td>
                                                            <td align="center">حذف</td>
                                                            <td align="center">ارشفة</td>
                                                            <td align="center">طباعة</td>
                                                        </tr>
                                                    </thead>
                                                    
                                                    <tbody>
                                                        <tr class="infotable-body">
                                                            <td align="center">اعتماد ملف جديد :</td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="PERMISSIONS NEEDED!" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['PERMISSIONS NEEDED!'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">الفواتير :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">اعتماد الفواتير :</td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">شروط الدفع :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">طلبات الاسترداد :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">ادارة المدفوعات :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">رصيد الموكل :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">اعتماد / رفض الساعات القابلة للفوترة :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="TO DO AFTER FINISHING THE ACCOUNTING" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['TO DO AFTER FINISHING THE ACCOUNTING'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center"> الاشعارات :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="PERMISSIONS NEEDED!" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['PERMISSIONS NEEDED!'] === '1'){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="moreinps-container">
                                            <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                                <span><i class="bx bxs-user-check"></i> <p>الموارد البشرية</p></span> 
                                                <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                                <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                            </button>
                                            <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                                <table class="info-table" style="width: 100%;">
                                                    <thead>
                                                        <tr class="infotable-header" style="color: #fff; background-color: #125386;">
                                                            <td align="center"></td>
                                                            <td align="center">استعلام</td>
                                                            <td align="center">اضافة</td>
                                                            <td align="center">تعديل</td>
                                                            <td align="center">حذف</td>
                                                            <td align="center">ارشفة</td>
                                                            <td align="center">طباعة</td>
                                                        </tr>
                                                    </thead>
                                                    
                                                    <tbody>
                                                        <tr class="infotable-body">
                                                            <td align="center">الموظفين :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="emp_perms_read" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['emp_perms_read'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="emp_perms_add" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['emp_perms_add'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="emp_perms_edit" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['emp_perms_edit'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="emp_perms_delete" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['emp_perms_delete'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">سجلات البرنامج :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="logs_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['logs_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="logs_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['logs_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">سجل الحضور :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="userattendance_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['userattendance_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="userattendance_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['userattendance_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="userattendance_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['userattendance_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="userattendance_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['userattendance_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">مستندات و وثائق الموظفين :</td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="useratts_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['useratts_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="useratts_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['useratts_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">الخصومات :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="discounts_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['discounts_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="discounts_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['discounts_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="discounts_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['discounts_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">الانذارات :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="warnings_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['warnings_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="warnings_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['warnings_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="warnings_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['warnings_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="warnings_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['warnings_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">الدورات :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="trainings_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['trainings_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="trainings_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['trainings_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="trainings_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['trainings_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="trainings_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['trainings_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">أوقات الدوام :</td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="loggingtimes_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['loggingtimes_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">القبول المبدئي للاجازات :</td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="vacf_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['vacf_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">القبول النهائي للاجازات :</td>
                                                            <td align="center"></td>
                                                            <td align="center">
                                                                <input type="checkbox" name="vacl_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['vacl_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                        
                                                        <tr class="infotable-body">
                                                            <td align="center">تقييمات الموظفين :</td>
                                                            <td align="center">
                                                                <input type="checkbox" name="rate_rperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['rate_rperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="rate_aperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['rate_aperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="rate_eperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['rate_eperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center">
                                                                <input type="checkbox" name="rate_dperm" style="border:0;background : transparent;" value="1" <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['rate_dperm'] == 1){ echo 'checked'; }?>>
                                                            </td>
                                                            <td align="center"></td>
                                                            <td align="center"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div><br>
                                </div>
                                <div class="profedit-footer">
                                    <div style="text-align: center">
                                        <button type="submit" class="green-button" style="font-size: 18px">حفظ البيانات</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        <script src="js/newWindow.js"></script>
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
        <script>
            function updateFileName(id) {
                const input = document.getElementById('file-upload'+id);
                const fileName = input.files.length > 0 ? input.files[0].name : 'لا يوجد مرفق.';
                document.getElementById('file-name'+id).textContent = fileName;
            }
        </script>
        <script>
            function showStep(id) {
                document.querySelectorAll('.step').forEach(step => step.classList.remove('active-empsection'));
                document.getElementById('step'+id).classList.add('active-empsection');
                document.querySelectorAll('.stepinfo').forEach(stepinfo => stepinfo.style.display='none');
                document.getElementById('stepinfo'+id).style.display='block';
            }
        </script>
    </body>
</html>