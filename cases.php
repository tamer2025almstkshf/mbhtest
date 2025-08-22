<?php
    include_once 'connection.php';
    include_once 'login_check.php';
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
                                    <h3 style="display: inline-block"><font id="clients-translate"><?php if(isset($_GET['important']) && $_GET['important'] == 1){ echo 'الدعاوي الهامة'; } else{ echo 'الملفات و القضايا'; }?></font></h3>
                                    <?php
                                        $c1 = 'متداول';
                                        $c2 = 'في الانتظار';
                                        $c3 = 'مؤرشف';
                                        $empty = '';
                                        
                                        $stmt_count1 = $conn->prepare("SELECT COUNT(*) as count1 FROM file WHERE file_status = ? AND file_client != ?");
                                        $stmt_count1->bind_param("ss", $c1, $empty);
                                        $stmt_count1->execute();
                                        $result_count1 = $stmt_count1->get_result();
                                        $row_count1 = $result_count1->fetch_assoc();
                                        $stmt_count1->close();
                                        $count1 = $row_count1['count1'];
                                        
                                        $stmt_count2 = $conn->prepare("SELECT COUNT(*) as count2 FROM file WHERE file_status = ? AND file_client != ?");
                                        $stmt_count2->bind_param("ss", $c2, $empty);
                                        $stmt_count2->execute();
                                        $result_count2 = $stmt_count2->get_result();
                                        $row_count2 = $result_count2->fetch_assoc();
                                        $stmt_count2->close();
                                        $count2 = $row_count2['count2'];
                                        
                                        $stmt_count3 = $conn->prepare("SELECT COUNT(*) as count3 FROM file WHERE file_status = ? AND file_client != ?");
                                        $stmt_count3->bind_param("ss", $c3, $empty);
                                        $stmt_count3->execute();
                                        $result_count3 = $stmt_count3->get_result();
                                        $row_count3 = $result_count3->fetch_assoc();
                                        $stmt_count3->close();
                                        $count3 = $row_count3['count3'];
                                        
                                        $stmt_count4 = $conn->prepare("SELECT COUNT(*) as count4 FROM file WHERE opp_date != ? AND file_client != ?");
                                        $stmt_count4->bind_param("ss", $empty, $empty);
                                        $stmt_count4->execute();
                                        $result_count4 = $stmt_count4->get_result();
                                        $row_count4 = $result_count4->fetch_assoc();
                                        $stmt_count4->close();
                                        $count4 = $row_count4['count4'];
                                        
                                        $stmtsche = $conn->prepare("SELECT * FROM file WHERE file_client != ?");
                                        $stmtsche->bind_param("s", $empty);
                                        $stmtsche->execute();
                                        $resultsche = $stmtsche->get_result();
                                        $fidsarray = [];
                                        while($rowsche = $resultsche->fetch_assoc()){
                                            $fidsarray[] = $rowsche['file_id'];
                                        }
                                        $stmtsche->close();
                                        $countfidsarray = count($fidsarray);
                                        
                                        $fidsstring = implode(',', $fidsarray);
                                        $stmtsche2 = $conn->prepare("SELECT COUNT(DISTINCT(session_fid)) as countschesfid FROM session WHERE session_fid IN ($fidsstring)");
                                        $stmtsche2->execute();
                                        $resultsche2 = $stmtsche2->get_result();
                                        $rowsche2 = $resultsche2->fetch_assoc();
                                        $stmtsche2->close();
                                        $count5 = $countfidsarray - $rowsche2['countschesfid'];
                                        
                                        $stmt_count6 = $conn->prepare("SELECT COUNT(*) as count6 FROM file WHERE file_client != ?");
                                        $stmt_count6->bind_param("s", $empty);
                                        $stmt_count6->execute();
                                        $result_count6 = $stmt_count6->get_result();
                                        $row_count6 = $result_count6->fetch_assoc();
                                        $stmt_count6->close();
                                        $count6 = $row_count6['count6'];
                                        
                                        if($admin == 1){
                                            $stmt_count7 = $conn->prepare("SELECT COUNT(*) as count7 FROM file WHERE file_client != ? AND secret_folder = '1'");
                                            $stmt_count7->bind_param("s", $empty);
                                            $stmt_count7->execute();
                                            $result_count7 = $stmt_count7->get_result();
                                            $row_count7 = $result_count7->fetch_assoc();
                                            $stmt_count7->close();
                                            $count7 = $row_count7['count7'];
                                        } else{
                                            $stmtschec = $conn->prepare("SELECT * FROM file WHERE secret_folder = '1'");
                                            $stmtschec->execute();
                                            $resultschec = $stmtschec->get_result();
                                            $fidsarray3 = [];
                                            while($rowschec = $resultschec->fetch_assoc()){
                                                $empids = $rowschec['secret_emps'];
                                                $empids = array_filter(array_map('trim', explode(',', $empids)));
                                                if(in_array($_SESSION['id'], $empids)){
                                                    $fidsarray3[] = (int)$rowschec['file_id'];
                                                }
                                            }
                                            $countfidsarray = count($fidsarray3);
                                            $stmtschec->close();
                                            
                                            if(!empty($fidsarray3)){
                                                if(count($fidsarray3) > 1){
                                                    $fidsstring3 = implode(',', $fidsarray3);
                                                    $stmt_count7 = $conn->prepare("SELECT COUNT(*) as countsecret FROM file WHERE file_id IN ($fidsstring3)");
                                                    $stmt_count7->execute();
                                                    $result_count7 = $stmt_count7->get_result();
                                                    $row_count7 = $result_count7->fetch_assoc();
                                                    $stmt_count7->close();
                                                    $count7 = $row_count7['countsecret'];
                                                } else{
                                                    $count7 = 1;
                                                }
                                            } else{
                                                $count7 = 0;
                                            }
                                        }
                                    ?>
                                    <select class="table-header-selector" name="type" onchange="submit()" style="padding: 0 5px;">
                                        <option value="select">اختر التصنيف</option>
                                        <option value="6" <?php if(isset($_GET['section']) && $_GET['section'] == 6){ echo 'selected'; }?>><font id="all-translate">جميع الملفات <?php echo '( '.safe_output($count6).' )';?></font></option>
                                        <option value="2" <?php if(isset($_GET['section']) && $_GET['section'] == 2){ echo 'selected'; }?>><font id="clients-translate">ملفات فى الانتظار <?php echo '( '.safe_output($count2).' )';?></font></option>
                                        <option value="1" <?php if(isset($_GET['section']) && $_GET['section'] == 1){ echo 'selected'; }?>><font id="opponents-translate">ملفات متداولة <?php echo '( '.safe_output($count1).' )';?></font></option>
                                        <option value="3" <?php if(isset($_GET['section']) && $_GET['section'] == 3){ echo 'selected'; }?>><font id="subs-translate">ملفات مؤرشفة <?php echo '( '.safe_output($count3).' )';?></font></option>
                                        <option value="5" <?php if(isset($_GET['section']) && $_GET['section'] == 5){ echo 'selected'; }?>><font id="subs-translate">ملفات بدون جلسات <?php echo '( '.safe_output($count5).' )';?></font></option>
                                        <option value="4" <?php if(isset($_GET['section']) && $_GET['section'] == 4){ echo 'selected'; }?>><font id="subs-translate">قضايا متقابلة <?php echo '( '.safe_output($count4).' )';?></font></option>
                                        <option value="7" <?php if(isset($_GET['section']) && $_GET['section'] == 7){ echo 'selected'; }?>><font id="subs-translate">ملفات سرية <?php echo '( '.safe_output($count7).' )';?></font></option>
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
                                                    <p class="input-parag" style="display: inline-block">البحث : </p>
                                                    <input class="form-input" style="display: inline-block; width: 50%;" type="text" id="SearchBox">
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr class="infotable-header" style="position: sticky; top: 0; z-index: 3;">
                                            <th width="60px">حالة</th>
                                            <th>رقم الملف</th>
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
                                            <th width="50px">الاجراءات</th>
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
                                                if($row['file_type'] === 'مدني -عمالى'){ 
                                                    echo '#FFFF99'; 
                                                } else if($row['file_type'] === 'جزاء'){ 
                                                    echo '#99B5E8'; 
                                                } else if($row['file_type'] === 'أحوال شخصية'){ 
                                                    echo '#FAB8BA'; 
                                                } else if($row['file_type'] === 'أحوال شخصية'){ 
                                                    echo '#e1e1e1'; 
                                                } else{ 
                                                    $stmtft = $conn->prepare("SELECT * FROM file_types WHERE file_type=?"); 
                                                    $stmtft->bind_param("s", $ft); 
                                                    $stmtft->execute(); 
                                                    $resultft = $stmtft->get_result(); 
                                                    $rowft = $resultft->fetch_assoc(); 
                                                    $stmtft->close(); 
                                                    echo $rowft['type_color']; 
                                                }
                                            ?>"
                                            <?php 
                                                if($row['secret_folder'] == 1) { 
                                                    $allowed_ids_string = $row['secret_emps']; 
                                                    $allowed_ids_array = array_map('intval', explode(',', $allowed_ids_string)); 
                                                    if(in_array((int)$_SESSION['id'], $allowed_ids_array, true)){
                                            ?> title="تعديل" style="cursor: pointer;" onclick="location.href='FileEdit.php?id=<?php echo safe_output($row['file_id']);?>';"<?php }}?>>
                                                <?php if($row['file_status'] === 'في الانتظار'){?>
                                                <img src="img/pending.png" width="25px" height="25px">
                                                <?php } else if($row['file_status'] === 'متداول'){?>
                                                <img src="img/Circulating.png" width="25px" height="25px">
                                                <?php } else if($row['file_status'] === 'مؤرشف'){?>
                                                <img src="img/archive.png" width="25px" height="25px">
                                                <?php } if($row['secret_folder'] == 1){?>
                                                <img src="img/unsecure.png" width="25px" height="25px">
                                                <?php }?>
                                            </td>
                                            <td <?php if ($row['secret_folder'] == 1) { $allowed_ids_string = $row['secret_emps']; $allowed_ids_array = array_map('intval', explode(',', $allowed_ids_string)); if (in_array((int)$_SESSION['id'], $allowed_ids_array, true)) {?> title="تعديل" style="cursor: pointer;" onclick="location.href='FileEdit.php?id=<?php echo safe_output($row['file_id']);?>';"<?php }}?>>
                                                <font color="#FF0000">
                                                    <?php 
                                                        $place = safe_output($row['frelated_place']);
                                                        $fileid = safe_output($row['file_id']);
                                                        if($place === 'عجمان'){
                                                            echo 'AJM';
                                                        } elseif($place === 'دبي'){
                                                            echo 'DXB';
                                                        } elseif($place === 'الشارقة'){
                                                            echo 'SHJ';
                                                        }
                                                    ?>
                                                </font>
                                                <?php echo safe_output($fileid);?>
                                            </td>
                                            <?php
                                                if ($row['secret_folder'] == 1) {
                                                    $allowed_ids_string = $row['secret_emps'];
                                                    $allowed_ids_array = array_map('intval', explode(',', $allowed_ids_string));
                                                    if (!in_array((int)$_SESSION['id'], $allowed_ids_array, true)) {
                                            ?>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <?php
                                                        continue;
                                                    }
                                                }
                                            ?>
                                            <td style="color: #007bff; cursor: pointer;" title="اضغط هنا للتعديل" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';"><?php echo safe_output($row['file_subject']);?></td>
                                            <td style="cursor: pointer;" title="اضغط هنا للتعديل" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';">
                                                <?php if(isset($row['file_client']) && $row['file_client'] !== ''){?>
                                                <p>
                                                    <?php 
                                                        $cid = $row['file_client']; 
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                                                        if(isset($row['fclient_characteristic']) && $row['fclient_characteristic'] !== ''){ echo ' / ' . safe_output($row['fclient_characteristic']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($row['file_client2']) && $row['file_client2'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $row['file_client2']; 
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                                                        if(isset($row['fclient_characteristic2']) && $row['fclient_characteristic2'] !== ''){ echo ' / ' . safe_output($row['fclient_characteristic2']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($row['file_client3']) && $row['file_client3'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $row['file_client3']; 
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                                                        if(isset($row['fclient_characteristic3']) && $row['fclient_characteristic3'] !== ''){ echo ' / ' . safe_output($row['fclient_characteristic3']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($row['file_client4']) && $row['file_client4'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $row['file_client4']; 
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                                                        if(isset($row['fclient_characteristic4']) && $row['fclient_characteristic4'] !== ''){ echo ' / ' . safe_output($row['fclient_characteristic4']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($row['file_client5']) && $row['file_client5'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $row['file_client5']; 
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                                                        if(isset($row['fclient_characteristic5']) && $row['fclient_characteristic5'] !== ''){ echo ' / ' . safe_output($row['fclient_characteristic5']); }
                                                    ?>
                                                </p>
                                                <?php }?>
                                            </td>
                                            <td style="cursor: pointer;" title="اضغط هنا للتعديل" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';">
                                                <?php if(isset($row['file_opponent']) && $row['file_opponent'] !== ''){?>
                                                <p>
                                                    <?php 
                                                        $cid = $row['file_opponent']; 
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                                                        if(isset($row['fopponent_characteristic']) && $row['fopponent_characteristic'] !== ''){ echo ' / ' . safe_output($row['fopponent_characteristic']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($row['file_opponent2']) && $row['file_opponent2'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $row['file_opponent2']; 
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                                                        if(isset($row['fopponent_characteristic2']) && $row['fopponent_characteristic2'] !== ''){ echo ' / ' . safe_output($row['fopponent_characteristic2']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($row['file_opponent3']) && $row['file_opponent3'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $row['file_opponent3']; 
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                                                        if(isset($row['fopponent_characteristic3']) && $row['fopponent_characteristic3'] !== ''){ echo ' / ' . safe_output($row['fopponent_characteristic3']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($row['file_opponent4']) && $row['file_opponent4'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $row['file_opponent4']; 
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                                                        if(isset($row['fopponent_characteristic4']) && $row['fopponent_characteristic4'] !== ''){ echo ' / ' . safe_output($row['fopponent_characteristic4']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($row['file_opponent5']) && $row['file_opponent5'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $row['file_opponent5']; 
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo isset($rowc['arname']) ? safe_output($rowc['arname']) : '';
                                                        if(isset($row['fopponent_characteristic5']) && $row['fopponent_characteristic5'] !== ''){ echo ' / ' . safe_output($row['fopponent_characteristic5']); }
                                                    ?>
                                                </p>
                                                <?php }?>
                                            </td>
                                            <td style="cursor: pointer;" title="اضغط هنا للتعديل" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';">
                                                <font color=blue>
                                                    <?php
                                                        $fiddeg = $row['file_id'];
                                                        $stmt_degs = $conn->prepare("SELECT * FROM file_degrees WHERE fid=? ORDER BY created_at DESC");
                                                        $stmt_degs->bind_param("i", $fiddeg);
                                                        $stmt_degs->execute();
                                                        $result_degs = $stmt_degs->get_result();
                                                        if($result_degs->num_rows > 0){
                                                            $row_degs = $result_degs->fetch_assoc();
                                                            if(isset($row_degs['fid']) && $row_degs['fid'] !== ''){
                                                                echo safe_output($row_degs['case_num']) . ' / ' . safe_output($row_degs['file_year']) . ' - ' . safe_output($row_degs['degree']);
                                                            }
                                                        }
                                                        $stmt_degs->close();
                                                    ?>
                                                </font>
                                            </td>
                                            <td style="cursor: pointer;" title="اضغط هنا للتعديل" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';"><?php echo safe_output($row['file_court']);?></td>
                                            <td style="cursor: pointer;" title="اضغط هنا للتعديل" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';">
                                                <?php
                                                    $fidsee = $row['file_id'];
                                                    $stmtsee = $conn->prepare("SELECT * FROM session WHERE session_fid=? ORDER BY created_at DESC");
                                                    $stmtsee->bind_param("i", $fidsee);
                                                    $stmtsee->execute();
                                                    $resultsee = $stmtsee->get_result();
                                                    if($resultsee->num_rows > 0){
                                                        $rowsee = $resultsee->fetch_assoc();
                                                        echo safe_output($rowsee['session_date']);
                                                    }
                                                    $stmtsee->close();
                                                ?>
                                            </td>
                                            <td style="cursor: pointer;" title="اضغط هنا للتعديل" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';">
                                                <?php 
                                                    $fiiid = $row['file_id'];
                                                    $stmt_scount = $conn->prepare("SELECT COUNT(*) as sessions_count FROM session WHERE session_fid=? AND session_degree!='تنفيذ'");
                                                    $stmt_scount->bind_param("i", $fiiid);
                                                    $stmt_scount->execute();
                                                    $result_scount = $stmt_scount->get_result();
                                                    $row_scount = $result_scount->fetch_assoc();
                                                    $sessions_count = $row_scount['sessions_count'];
                                                    $stmt_scount->close();
                                                    echo safe_output($sessions_count);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $thisid = $row['file_id'];
                                                    $stmt_docs = $conn->prepare("SELECT * FROM case_document WHERE dfile_no = ?");
                                                    $stmt_docs->bind_param("i", $thisid);
                                                    $stmt_docs->execute();
                                                    $result_docs = $stmt_docs->get_result();
                                                    $i = 0;
                                                    while($row3=$result_docs->fetch_assoc()){
                                                        $i++;
                                                    }
                                                    $stmt_docs->close();
                                                    echo $i;
                                                ?>
                                                <img src="img/add-document.png" width="25px" height="25px" title="كتابة مذكرة" style="cursor:pointer" onclick="open('AddNotes.php?fno=<?php $id=$row['file_id']; echo safe_output($id);?>','Pic','width=800 height=800 scrollbars=yes')"/> 
                                            </td>
                                            <td style="cursor: pointer;" title="اضغط هنا للتعديل" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';">
                                                <?php
                                                    if(isset($row['file_id']) && $row['file_id'] !== ''){
                                                        $fiddds = $row['file_id'];
                                                        $stmt_tno = $conn->prepare("SELECT COUNT(*) as tcount FROM tasks WHERE file_no=?");
                                                        $stmt_tno->bind_param("i", $fiddds);
                                                        $stmt_tno->execute();
                                                        $result_tno = $stmt_tno->get_result();
                                                        $row_tno = $result_tno->fetch_assoc();
                                                        $stmt_tno->close();
                                                        $tno = $row_tno['tcount'];
                                                        
                                                        echo $tno;
                                                    }
                                                ?>
                                            </td>
                                            <td style="cursor: pointer;" title="اضغط هنا للتعديل" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';">
                                                <?php 
                                                    if(isset($row['duration']) && $row['duration'] !== ''){
                                                        echo safe_output($row['duration']);
                                                    }
                                                    if(isset($row['done_by']) && $row['done_by'] !== ''){
                                                        $done_by = $row['done_by'];
                                                        
                                                        $stmtdb = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                        $stmtdb->bind_param("i", $done_by);
                                                        $stmtdb->execute();
                                                        $resultdb = $stmtdb->get_result();
                                                        $rowdb = $resultdb->fetch_assoc();
                                                        $stmtdb->close();
                                                        echo '<br>'.safe_output($rowdb['name']);
                                                    }
                                                ?>
                                            </td>
                                            <td style="align-content: center;">
                                                <?php if($row_permcheck['session_eperm'] == 1){?>
                                                <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" style="cursor: pointer;" title="اضغط هنا للتعديل" onclick="location.href='FileEdit.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>';">
                                                <?php } if($row_permcheck['session_dperm'] == 1){?>
                                                <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='deletefile.php?id=<?php if (isset($row['file_id'])) { echo safe_output($row['file_id']); }?>&page=cases.php';">
                                                <?php }?>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php 
                                            }
                                        }
                                        $stmt->close();
                                    ?>
                                </table>
                            </div>
                            
                            <div class="table-footer">
                                <p></p>
                                <div id="pagination"></div>
                                <div id="pageInfo"></div>
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
    </body>
</html>