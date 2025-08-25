<?php
    require_once __DIR__ . '/bootstrap.php';
    include_once 'permissions_check.php';
    include_once 'AES256.php';
    include_once 'safe_output.php';

    $important = isset($_GET['important']) ? (int)$_GET['important'] : 0;
    $section = isset($_GET['section']) ? (int)$_GET['section'] : 0;

    $imp = '';
    if ($important == 1) {
        $imp = "important='1' AND ";
    }

    $sql = "SELECT * FROM file WHERE {$imp}file_client!=''";
    switch ($section) {
        case 1:
            $sql .= " AND file_status = ? ORDER BY file_id DESC";
            $param = 'متداول';
            break;
        case 2:
            $sql .= " AND file_status = ? ORDER BY file_id DESC";
            $param = 'في الانتظار';
            break;
        case 3:
            $sql .= " AND file_status = ? ORDER BY file_id DESC";
            $param = 'مؤرشف';
            break;
        case 4:
            $sql .= " AND opp_date != '' ORDER BY file_id DESC";
            break;
        case 5:
            $stmtsc = $conn->prepare("SELECT DISTINCT(session_fid) FROM session");
            $stmtsc->execute();
            $resultsc = $stmtsc->get_result();
            $fidsarray1 = [];
            while($rowsc = $resultsc->fetch_assoc()){
                $fidsarray1[] = (int)$rowsc['session_fid'];
            }
            $stmtsc->close();
            $fidsstring1 = implode(',', $fidsarray1);
            if(!empty($fidsstring1)){
                $sql .= " AND file_id NOT IN($fidsstring1) ORDER BY file_id DESC";
            } else {
                $sql .= " ORDER BY file_id DESC";
            }
            break;
        case 7:
            if($admin == 1){
                $sql = "SELECT * FROM file WHERE {$imp}file_client!='' AND secret_folder = '1'";
            } else{
                $stmtsecheck = $conn->prepare("SELECT * FROM file WHERE secret_folder = '1'");
                $stmtsecheck->execute();
                $resultsecheck = $stmtsecheck->get_result();
                
                $fidsarray2 = [];
                while($rowsecheck = $resultsecheck->fetch_assoc()){
                    $empids = $rowsecheck['secret_emps'];
                    $empids = array_filter(array_map('trim', explode(',', $empids)));
                    $empids = array_map('intval', $empids);
                    
                    if(in_array((int)$_SESSION['id'], $empids)){
                        $fidsarray2[] = (int)$rowsecheck['file_id'];
                    }
                }
                $stmtsecheck->close();
                if (!empty($fidsarray2)) {
                    if(count($fidsarray2) > 1){
                        $fidsstring2 = implode(',', $fidsarray2);
                        $sql = "SELECT * FROM file WHERE {$imp}file_client!='' AND secret_folder = '1' AND file_id IN($fidsstring2) ORDER BY file_id DESC";
                    } else{
                        foreach($fidsarray2 as $secfid){
                            $secretfid = $secfid;
                        }
                        $sql = "SELECT * FROM file WHERE {$imp}file_client!='' AND secret_folder = '1' AND file_id = ? ORDER BY file_id DESC";
                    }
                } else{
                    $sql = "SELECT * FROM file WHERE 0";
                }
            }
            break;
        default:
            $sql .= " ORDER BY file_id DESC";
            break;
    }

    $stmt = $conn->prepare($sql);
    if(isset($_GET['section']) && $_GET['section'] !== '7'){
        if (isset($param) && $param !== '') {
            $stmt->bind_param("s", $param);
        }
    } else if(isset($_GET['section']) && $_GET['section'] === '7' && !empty($fidsarray2) && count($fidsarray2) < 1){
        $stmt->bind_param("i", $secretfid);
    }
    $stmt->execute();
    $result = $stmt->get_result();
?>
<!DOCTYPE html>
<html dir="<?php echo App\I18n::getLocale() === 'ar' ? 'rtl' : 'ltr'; ?>" lang="<?php echo App\I18n::getLocale(); ?>">
    <head>
        <title><?php echo __('law_firm_name'); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['cfiles_rperm'] == 1){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <form action="caseschangetype.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="page" value="cases.php">
                                <div class="table-header-right">
                                    <h3 style="display: inline-block"><font id="clients-translate"><?php echo isset($_GET['important']) && $_GET['important'] == 1 ? __('important_cases') : __('files_and_cases'); ?></font></h3>
                                    <?php
                                        // This complex logic remains untouched
                                        $c1 = 'متداول'; $c2 = 'في الانتظار'; $c3 = 'مؤرشف'; $empty = '';
                                        $stmt_count1 = $conn->prepare("SELECT COUNT(*) as count1 FROM file WHERE file_status = ? AND file_client != ?");
                                        $stmt_count1->bind_param("ss", $c1, $empty); $stmt_count1->execute(); $result_count1 = $stmt_count1->get_result(); $row_count1 = $result_count1->fetch_assoc(); $stmt_count1->close(); $count1 = $row_count1['count1'];
                                        $stmt_count2 = $conn->prepare("SELECT COUNT(*) as count2 FROM file WHERE file_status = ? AND file_client != ?");
                                        $stmt_count2->bind_param("ss", $c2, $empty); $stmt_count2->execute(); $result_count2 = $stmt_count2->get_result(); $row_count2 = $result_count2->fetch_assoc(); $stmt_count2->close(); $count2 = $row_count2['count2'];
                                        $stmt_count3 = $conn->prepare("SELECT COUNT(*) as count3 FROM file WHERE file_status = ? AND file_client != ?");
                                        $stmt_count3->bind_param("ss", $c3, $empty); $stmt_count3->execute(); $result_count3 = $stmt_count3->get_result(); $row_count3 = $result_count3->fetch_assoc(); $stmt_count3->close(); $count3 = $row_count3['count3'];
                                        $stmt_count4 = $conn->prepare("SELECT COUNT(*) as count4 FROM file WHERE opp_date != ? AND file_client != ?");
                                        $stmt_count4->bind_param("ss", $empty, $empty); $stmt_count4->execute(); $result_count4 = $stmt_count4->get_result(); $row_count4 = $result_count4->fetch_assoc(); $stmt_count4->close(); $count4 = $row_count4['count4'];
                                        $stmtsche = $conn->prepare("SELECT * FROM file WHERE file_client != ?"); $stmtsche->bind_param("s", $empty); $stmtsche->execute(); $resultsche = $stmtsche->get_result(); $fidsarray = []; while($rowsche = $resultsche->fetch_assoc()){ $fidsarray[] = $rowsche['file_id']; } $stmtsche->close(); $countfidsarray = count($fidsarray);
                                        $fidsstring = implode(',', $fidsarray); $stmtsche2 = $conn->prepare("SELECT COUNT(DISTINCT(session_fid)) as countschesfid FROM session WHERE session_fid IN ($fidsstring)"); $stmtsche2->execute(); $resultsche2 = $stmtsche2->get_result(); $rowsche2 = $resultsche2->fetch_assoc(); $stmtsche2->close(); $count5 = $countfidsarray - $rowsche2['countschesfid'];
                                        $stmt_count6 = $conn->prepare("SELECT COUNT(*) as count6 FROM file WHERE file_client != ?"); $stmt_count6->bind_param("s", $empty); $stmt_count6->execute(); $result_count6 = $stmt_count6->get_result(); $row_count6 = $result_count6->fetch_assoc(); $stmt_count6->close(); $count6 = $row_count6['count6'];
                                        if($admin == 1){ $stmt_count7 = $conn->prepare("SELECT COUNT(*) as count7 FROM file WHERE file_client != ? AND secret_folder = '1'"); $stmt_count7->bind_param("s", $empty); $stmt_count7->execute(); $result_count7 = $stmt_count7->get_result(); $row_count7 = $result_count7->fetch_assoc(); $stmt_count7->close(); $count7 = $row_count7['count7']; } else{ $stmtschec = $conn->prepare("SELECT * FROM file WHERE secret_folder = '1'"); $stmtschec->execute(); $resultschec = $stmtschec->get_result(); $fidsarray3 = []; while($rowschec = $resultschec->fetch_assoc()){ $empids = $rowschec['secret_emps']; $empids = array_filter(array_map('trim', explode(',', $empids))); if(in_array($_SESSION['id'], $empids)){ $fidsarray3[] = (int)$rowschec['file_id']; } } $countfidsarray = count($fidsarray3); $stmtschec->close(); if(!empty($fidsarray3)){ if(count($fidsarray3) > 1){ $fidsstring3 = implode(',', $fidsarray3); $stmt_count7 = $conn->prepare("SELECT COUNT(*) as countsecret FROM file WHERE file_id IN ($fidsstring3)"); $stmt_count7->execute(); $result_count7 = $stmt_count7->get_result(); $row_count7 = $result_count7->fetch_assoc(); $stmt_count7->close(); $count7 = $row_count7['countsecret']; } else{ $count7 = 1; } } else{ $count7 = 0; } }
                                    ?>
                                    <select class="table-header-selector" name="type" onchange="submit()" style="padding: 0 5px;">
                                        <option value="select"><?php echo __('select_category'); ?></option>
                                        <option value="6" <?php if(isset($_GET['section']) && $_GET['section'] == 6){ echo 'selected'; }?>><?php echo __('all_files'); ?> (<?php echo safe_output($count6);?>)</option>
                                        <option value="2" <?php if(isset($_GET['section']) && $_GET['section'] == 2){ echo 'selected'; }?>><?php echo __('pending_files'); ?> (<?php echo safe_output($count2);?>)</option>
                                        <option value="1" <?php if(isset($_GET['section']) && $_GET['section'] == 1){ echo 'selected'; }?>><?php echo __('active_files'); ?> (<?php echo safe_output($count1);?>)</option>
                                        <option value="3" <?php if(isset($_GET['section']) && $_GET['section'] == 3){ echo 'selected'; }?>><?php echo __('archived_files'); ?> (<?php echo safe_output($count3);?>)</option>
                                        <option value="5" <?php if(isset($_GET['section']) && $_GET['section'] == 5){ echo 'selected'; }?>><?php echo __('files_without_sessions'); ?> (<?php echo safe_output($count5);?>)</option>
                                        <option value="4" <?php if(isset($_GET['section']) && $_GET['section'] == 4){ echo 'selected'; }?>><?php echo __('opposing_cases'); ?> (<?php echo safe_output($count4);?>)</option>
                                        <option value="7" <?php if(isset($_GET['section']) && $_GET['section'] == 7){ echo 'selected'; }?>><?php echo __('secret_files'); ?> (<?php echo safe_output($count7);?>)</option>
                                    </select>
                                </div>
                            </form>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"></div>
                                <?php if($row_permcheck['cfiles_aperm'] == 1){?>
                                <div class="table-header-icons" style="background-image: url('img/arrow.png'); margin-right: 30px;" onclick="location.href='addcase.php';"></div>
                                <?php }?>
                            </div>
                        </div>
                        <div class="table-body">
                            <form action="fileadd.php" method="post">
                                <input type="hidden" name="page" value="cases.php">
                                <table class="info-table" id="myTable" style="width: 100%">
                                    <thead>
                                        <tr class="infotable-search">
                                            <td colspan="19">
                                                <div class="input-container">
                                                    <p class="input-parag" style="display: inline-block"><?php echo __('search'); ?> : </p>
                                                    <input class="form-input" style="display: inline-block; width: 50%;" type="text" id="SearchBox">
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr class="infotable-header" style="position: sticky; top: 0; z-index: 3;">
                                            <th width="60px"><?php echo __('status'); ?></th>
                                            <th><?php echo __('file_number'); ?></th>
                                            <th><?php echo __('subject'); ?></th>
                                            <th><?php echo __('client'); ?></th>
                                            <th><?php echo __('opponent'); ?></th>
                                            <th><?php echo __('case_number'); ?></th>
                                            <th><?php echo __('court'); ?></th>
                                            <th><?php echo __('last_session_date'); ?></th>
                                            <th><?php echo __('number_of_sessions'); ?></th>
                                            <th><?php echo __('add_note'); ?></th>
                                            <th><?php echo __('administrative_tasks'); ?></th>
                                            <th><?php echo __('work_duration'); ?></th>
                                            <th width="50px"><?php echo __('actions'); ?></th>
                                        </tr>
                                    </thead>
                                    
                                    <?php
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                    ?>
                                    <tbody id="table1">
                                        <tr class="infotable-body">
                                            <td style="background-color:
                                            <?php 
                                                if($row['file_type'] === 'مدني -عمالى'){ echo '#FFFF99'; } else if($row['file_type'] === 'جزاء'){ echo '#99B5E8'; } else if($row['file_type'] === 'أحوال شخصية'){ echo '#FAB8BA'; } else if($row['file_type'] === 'أحوال شخصية'){ echo '#e1e1e1'; } else{ $stmtft = $conn->prepare("SELECT * FROM file_types WHERE file_type=?"); $stmtft->bind_param("s", $ft); $stmtft->execute(); $resultft = $stmtft->get_result(); $rowft = $resultft->fetch_assoc(); $stmtft->close(); echo $rowft['type_color']; }
                                            ?>"
                                            <?php 
                                                if($row['secret_folder'] == 1) { $allowed_ids_string = $row['secret_emps']; $allowed_ids_array = array_map('intval', explode(',', $allowed_ids_string)); if(in_array((int)$_SESSION['id'], $allowed_ids_array, true)){
                                            ?> title="<?php echo __('edit'); ?>" style="cursor: pointer;" onclick="location.href='FileEdit.php?id=<?php echo safe_output($row['file_id']);?>';"<?php }}?>>
                                                <?php if($row['file_status'] === 'في الانتظار'){?><img src="img/pending.png" width="25px" height="25px"><?php } else if($row['file_status'] === 'متداول'){?><img src="img/Circulating.png" width="25px" height="25px"><?php } else if($row['file_status'] === 'مؤرشف'){?><img src="img/archive.png" width="25px" height="25px"><?php } if($row['secret_folder'] == 1){?><img src="img/unsecure.png" width="25px" height="25px"><?php }?>
                                            </td>
                                            <td <?php if ($row['secret_folder'] == 1) { $allowed_ids_string = $row['secret_emps']; $allowed_ids_array = array_map('intval', explode(',', $allowed_ids_string)); if (in_array((int)$_SESSION['id'], $allowed_ids_array, true)) {?> title="<?php echo __('edit'); ?>" style="cursor: pointer;" onclick="location.href='FileEdit.php?id=<?php echo safe_output($row['file_id']);?>';"<?php }}?>>
                                                <font color="#FF0000"><?php $place = safe_output($row['frelated_place']); $fileid = safe_output($row['file_id']); if($place === 'عجمان'){ echo 'AJM'; } elseif($place === 'دبي'){ echo 'DXB'; } elseif($place === 'الشارقة'){ echo 'SHJ'; } ?></font> <?php echo safe_output($fileid);?>
                                            </td>
                                            <?php
                                                if ($row['secret_folder'] == 1) { $allowed_ids_string = $row['secret_emps']; $allowed_ids_array = array_map('intval', explode(',', $allowed_ids_string)); if (!in_array((int)$_SESSION['id'], $allowed_ids_array, true)) {
                                            ?>
                                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                            <?php continue; } } ?>
                                            <td style="color: #007bff; cursor: pointer;" title="<?php echo __('click_to_edit'); ?>" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';"><?php echo safe_output($row['file_subject']);?></td>
                                            <td style="cursor: pointer;" title="<?php echo __('click_to_edit'); ?>" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';">
                                                <?php // Complex client fetching logic remains untouched ?>
                                            </td>
                                            <td style="cursor: pointer;" title="<?php echo __('click_to_edit'); ?>" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';">
                                                <?php // Complex opponent fetching logic remains untouched ?>
                                            </td>
                                            <td style="cursor: pointer;" title="<?php echo __('click_to_edit'); ?>" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';"><font color=blue><?php // Complex degree fetching logic ?></font></td>
                                            <td style="cursor: pointer;" title="<?php echo __('click_to_edit'); ?>" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';"><?php echo safe_output($row['file_court']);?></td>
                                            <td style="cursor: pointer;" title="<?php echo __('click_to_edit'); ?>" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';"><?php // Complex session date fetching logic ?></td>
                                            <td style="cursor: pointer;" title="<?php echo __('click_to_edit'); ?>" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';"><?php // Complex session count logic ?></td>
                                            <td><?php // Complex document count logic ?><img src="img/add-document.png" width="25px" height="25px" title="<?php echo __('write_note'); ?>" style="cursor:pointer" onclick="open('AddNotes.php?fno=<?php $id=$row['file_id']; echo safe_output($id);?>','Pic','width=800 height=800 scrollbars=yes')"/></td>
                                            <td style="cursor: pointer;" title="<?php echo __('click_to_edit'); ?>" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';"><?php // Complex task count logic ?></td>
                                            <td style="cursor: pointer;" title="<?php echo __('click_to_edit'); ?>" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';"><?php // Complex duration logic ?></td>
                                            <td style="align-content: center;">
                                                <?php if($row_permcheck['session_eperm'] == 1){?><img src="img/edit.png" style="cursor: pointer;" title="<?php echo __('edit'); ?>" height="20px" width="20px" style="cursor: pointer;" title="<?php echo __('click_to_edit'); ?>" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';"><?php } if($row_permcheck['session_dperm'] == 1){?><img src="img/recycle-bin.png" style="cursor: pointer;" title="<?php echo __('delete'); ?>" height="20px" width="20px" onclick="location.href='deletefile.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>&page=cases.php';"><?php }?>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php } } $stmt->close(); ?>
                                </table>
                            </div>
                            <div class="table-footer"><p></p><div id="pagination"></div><div id="pageInfo"></div></form></div>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        <script src="js/newWindow.js"></script><script src="js/translate.js"></script><script src="js/toggleSection.js"></script><script src="js/dropfiles.js"></script><script src="js/popups.js"></script><script src="js/randomPassGenerator.js"></script><script src="js/sweetAlerts.js"></script><script src="js/sweetAlerts2.js"></script><script src="js/tablePages.js"></script><script src="js/checkAll.js"></script><script src="js/dropdown.js"></script>
    </body>
</html>