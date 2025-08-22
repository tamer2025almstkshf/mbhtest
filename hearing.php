<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'safe_output.php';
    include_once 'AES256.php';
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
        <style>
            @media print {
                @page {
                    size: landscape;
                }
            }
        </style>
    </head>
    <body style="overflow: auto; padding-bottom: 50px;">
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['sessionrole_rperm'] == 1){
                        if(isset($_GET['tw']) && $_GET['tw'] === '1'){
                            $type = 'tw';
                        } else{
                            $type = 'fromto';
                        }
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">رول الجلسات</font></h3>
                            </div>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"></div>
                                <div class="table-header-icons" style="margin-right: 30px; background-image: url('img/printer.png');" onclick="printDiv();"></div>
                            </div>
                        </div>
                        <div class="table-body" id="printSection">
                            <table class="info-table" id="myTable" style="width: 100%; background-color: #99999940">
                                <thead>
                                    <tr class="infotable-search" class="no-print">
                                        <td colspan="19" class="no-print">
                                            <div class="input-container" class="no-print">
                                                <p class="input-parag" style="display: inline-block" class="no-print">البحث : </p>
                                                <input class="form-input" style="display: inline-block; width: 50%;" type="text" id="SearchBox" class="no-print">
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <form method="post" name="HearingForm" action="hcheck.php" enctype="multipart/form-data">
                                        <tr class="no-print infotable-search">
                                            <td align="center" colspan="4" style="padding: 0;">
                                                <div class="input-container; padding: 0">
                                                    <p class="input-parag" style="display: inline-block">من تاريخ</p>
                                                    <input class="form-input" name="start_date" type="date" value="<?php echo safe_output($_GET['from']);?>" style="width: 30%;">
                                                </div>
                                                <div class="input-container; padding: 0">
                                                    <p class="input-parag" style="display: inline-block">الى تاريخ</p>
                                                    <input class="form-input" name="to_date" type="date" value="<?php echo safe_output($_GET['to']);?>" style="width: 30%;">
                                                </div>
                                            </td>
                                            <td align="center" colspan="2">
                                                <div class="input-container">
                                                    <p class="input-parag" style="display: inline-block">المحكمة</p>
                                                    <select class="table-header-selector" name="court_name" style="min-width: 50%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                        <option value=""></option>
                                                        <?php
                                                            $stmtcourt = $conn->prepare("SELECT * FROM court");
                                                            $stmtcourt->execute();
                                                            $resultcourt = $stmtcourt->get_result();
                                                            if($resultcourt->num_rows > 0){
                                                                while($rowcourt = $resultcourt->fetch_assoc()){
                                                        ?>
                                                        <option value="<?php echo safe_output($rowcourt['id']);?>" <?php if($_GET['court'] === $rowcourt['court_name']){ echo 'selected'; }?>><?php echo safe_output($rowcourt['court_name']);?></option>
                                                        <?php
                                                                }
                                                            }
                                                            $stmtcourt->close();
                                                        ?>
                                                    </select>
                                                </div>
                                            </td>
                                            <td align="center" colspan="4">
                                                <button type="submit" name="search" value="بحث" class="alert-display-button">بحث</button>
                                                <button type="submit" name="tw" value="رول جلسات الاسبوع الحالى" class="alert-display-button">رول جلسات الاسبوع الحالى</button>
                                            </td>
                                        </tr>
                                    </form>
                                    
                                    <tr class="infotable-header" style="position: sticky; top: 0; z-index: 3;">
                                        <th>تاريخ الجلسة</th>
                                        <th>ت</th>
                                        <th>القضية</th>
                                        <th>الموكل وصفته</th>
                                        <th>الخصم وصفته</th>
                                        <th>رابط الجلسة</th>
                                        <th>قرار الجلسة</th>
                                        <th>ملاحظات</th>
                                        <th width="80px">اجراءات الجلسة</th>
                                        <th width="50px">الاجراءات</th>
                                    </tr>
                                </thead>
                                
                                <form method="post" name="HearingForm" action="deletehearing.php" enctype="multipart/form-data">
                                    <input type="hidden" name="page" value="hearing.php">
                                    <tbody id="table1">
                                        <?php if(isset($_REQUEST['tw']) && $_REQUEST['tw'] == 1){?>
                                        <input type="hidden" name="tw" value="<?php echo safe_output($_GET['tw']);?>">
                                        <?php
                                            $today = new DateTime();
                                            
                                            $future_date = clone $today;
                                            $future_date->modify('+7 days');
                                            
                                            $interval = new DateInterval('P1D');
                                            $daterange = new DatePeriod($today, $interval, $future_date);
                                            
                                            $clients = [];
                                            $stmtcresult = $conn->prepare("SELECT * FROM client");
                                            $stmtcresult->execute();
                                            $resultcresult = $stmtcresult->get_result();
                                            while ($clientRow = $resultcresult->fetch_assoc()) {
                                                $clients[$clientRow['id']] = $clientRow;
                                            }
                                            $stmtcresult->close();
                                            
                                            foreach ($daterange as $date) {
                                                $format_d = $date->format('Y-m-d');
                                                
                                                $stmt = $conn->prepare("SELECT * FROM session WHERE session_date=?");
                                                $stmt->bind_param("s", $format_d);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if($result->num_rows > 0){
                                        ?>
                                        <tr style="background-color: #fff">
                                            <th colspan="10" align="right" class="sbp-hearings" style="cursor: unset; text-align: right;">
                                                رول الجلسات لتاريخ <font color="#fff" style="background-color: #125386; border-radius: 10px; padding: 4px;"><?php echo safe_output($format_d);?></font>
                                            </th>
                                        </tr>
                                        <?php
                                            $count = 0;
                                            while($row = $result->fetch_assoc()){
                                                $count++;
                                                if(isset($row['session_fid']) && $row['session_id'] !== ''){
                                                    $sfid = $row['session_fid'];
                                                    
                                                    $stmtfile = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                                                    $stmtfile->bind_param("i", $sfid);
                                                    $stmtfile->execute();
                                                    $resultfile = $stmtfile->get_result();
                                                    
                                                    if($resultfile->num_rows > 0){
                                                        $rowfile = $resultfile->fetch_assoc();
                                                        if($rowfile['secret_folder'] == 1){
                                                            $count--;
                                                            continue;
                                                        }
                                                    }
                                                    $stmtfile->close();
                                                }
                                        ?>
                                        <tr class="infotable-body" id="row-<?php echo safe_output($row['session_id']); ?>">
                                            <td <?php if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){?> onclick="MM_openBrWindow('hearing_edit.php?sid=<?php echo safe_output($row['session_id']);?>&fid=<?php echo safe_output($row['session_fid']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=1000,height=600')" <?php }?> style="<?php if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){?> cursor:pointer; <?php }?>">
                                                <div style="height: 70px">
                                                    <div style="display: grid; grid-template-rows: 18px 52px; align-items: center;">
                                                        <div style="text-align: left;" class="no-print">
                                                            <img src="img/edit.png" width="18px" height="18px">
                                                        </div>
                                                        <b><?php if(isset($row['session_date']) && $row['session_date'] !== ''){ echo safe_output($row['session_date']); }?></b>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="cursor:pointer" <?php if(isset($row['session_fid']) && $row['session_fid'] != 0){?>onclick="MM_openBrWindow('CasePreview.php?fid=<?php echo safe_output($row['session_fid']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')"<?php }?>><?php echo safe_output($count);?></td>
                                            <td <?php if($row_permcheck['cfiles_eperm'] == 1){?> style="cursor: pointer" onclick="MM_openBrWindow('FileEdit.php?id=<?php echo safe_output($row['session_fid']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=1000,height=600')" <?php }?>>
                                                <b>رقم القضية :</b> <font color=blue><?php if(isset($row['session_degree']) && $row['session_degree'] !== ''){ echo safe_output($row['session_degree']); }?> - <?php if(isset($row['case_num']) && $row['case_num'] !== ''){ echo safe_output($row['case_num']); }?>/<?php if(isset($row['year']) && $row['year'] !== ''){ echo safe_output($row['year']); }?></font>
                                                <br />
                                                <b>المحكمة : </b><?php if(isset($rowfile['file_court']) && $rowfile['file_court'] !== ''){ echo safe_output($rowfile['file_court']); }?><br />
                                                <font color="#FF0000"><?php if(isset($row['session_date']) && $row['session_date'] !== ''){ echo safe_output($row['session_date']); }?></font>
                                                <?php if($row['expert_session'] === '1'){?><br><img src="img/suitcase.png" width="25px" height="25px" title="جلسات الخبرة"><br><?php }?>
                                            </td>
                                            <td <?php if($row_permcheck['cfiles_eperm'] == 1){?> style="cursor: pointer" onclick="MM_openBrWindow('FileEdit.php?id=<?php echo safe_output($row['session_fid']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=1000,height=600')" <?php }?>>
                                                <?php if(isset($rowfile['file_client']) && $rowfile['file_client'] !== ''){?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_client']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fclient_characteristic']) && $rowfile['fclient_characteristic'] !== ''){ echo ' / ' . safe_output($rowfile['fclient_characteristic']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($rowfile['file_client2']) && $rowfile['file_client2'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_client2']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fclient_characteristic2']) && $rowfile['fclient_characteristic2'] !== ''){ echo ' / ' . safe_output($rowfile['fclient_characteristic2']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($rowfile['file_client3']) && $rowfile['file_client3'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_client3']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fclient_characteristic3']) && $rowfile['fclient_characteristic3'] !== ''){ echo ' / ' . safe_output($rowfile['fclient_characteristic3']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($rowfile['file_client4']) && $rowfile['file_client4'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_client4']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fclient_characteristic4']) && $rowfile['fclient_characteristic4'] !== ''){ echo ' / ' . safe_output($rowfile['fclient_characteristic4']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($rowfile['file_client5']) && $rowfile['file_client5'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_client5']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fclient_characteristic5']) && $rowfile['fclient_characteristic5'] !== ''){ echo ' / ' . safe_output($rowfile['fclient_characteristic5']); }
                                                    ?>
                                                </p>
                                                <?php }?>
                                            </td>
                                            <td <?php if($row_permcheck['cfiles_eperm'] == 1){?> style="cursor: pointer" onclick="MM_openBrWindow('FileEdit.php?id=<?php echo safe_output($row['session_fid']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=1000,height=600')" <?php }?>>
                                                <?php if(isset($rowfile['file_opponent']) && $rowfile['file_opponent'] !== ''){?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_opponent']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fopponent_characteristic']) && $rowfile['fopponent_characteristic'] !== ''){ echo ' / ' . safe_output($rowfile['fopponent_characteristic']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($rowfile['file_opponent2']) && $rowfile['file_opponent2'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_opponent2']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fopponent_characteristic2']) && $rowfile['fopponent_characteristic2'] !== ''){ echo ' / ' . safe_output($rowfile['fopponent_characteristic2']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($rowfile['file_opponent3']) && $rowfile['file_opponent3'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_opponent3']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fopponent_characteristic3']) && $rowfile['fopponent_characteristic3'] !== ''){ echo ' / ' . safe_output($rowfile['fopponent_characteristic3']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($rowfile['file_opponent4']) && $rowfile['file_opponent4'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_opponent4']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fopponent_characteristic4']) && $rowfile['fopponent_characteristic4'] !== ''){ echo ' / ' . safe_output($rowfile['fopponent_characteristic4']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($rowfile['file_opponent5']) && $rowfile['file_opponent5'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_opponent5']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fopponent_characteristic5']) && $rowfile['fopponent_characteristic5'] !== ''){ echo ' / ' . safe_output($rowfile['fopponent_characteristic5']); }
                                                    ?>
                                                </p>
                                                <?php }?>
                                            </td>
                                            <td style="padding:3px;">
                                                <?php if(isset($row['link']) && $row['link'] !== ''){?>
                                                <a href="#" style="text-decoration: none;" onclick="MM_openBrWindow('<?php echo safe_output($row['link']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=1000,height=600')">الرابط</a>
                                                <?php }?>
                                            </td>
                                            <td <?php if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){?> onclick="MM_openBrWindow('hearing_edit.php?sid=<?php echo safe_output($row['session_id']);?>&fid=<?php echo safe_output($row['session_fid']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=1000,height=600')" <?php }?> style="padding:3px; <?php if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){?> cursor:pointer <?php }?>">
                                                <div style="height: 70px">
                                                    <div style="display: grid; grid-template-rows: 18px 52px; align-items: center;">
                                                        <div style="text-align: left;" class="no-print">
                                                            <img src="img/edit.png" width="18px" height="18px">
                                                        </div>
                                                        <b><?php if(isset($row['session_decission']) && $row['session_decission'] !== ''){ echo safe_output($row['session_decission']); }?></b>
                                                    </div>
                                                </div>
                                            </td>
                                            <td <?php if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){?> style="cursor: pointer" onclick="MM_openBrWindow('hearing_edit.php?sid=<?php echo safe_output($row['session_id']);?>&fid=<?php echo safe_output($row['session_fid']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=1000,height=600')" <?php }?>>
                                                <div style="height: 70px">
                                                    <div style="display: grid; grid-template-rows: 18px 52px; align-items: center;">
                                                        <div style="text-align: left;" class="no-print">
                                                            <img src="img/edit.png" width="18px" height="18px">
                                                        </div>
                                                        <b><?php if(isset($row['session_note']) && $row['session_note'] !== ''){ echo safe_output($row['session_note']); }?></b>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="no-print">
                                                <img src="img/BookedJud2.png" title="منطوق الحكم" width="20px" height="20px" style="cursor:pointer" onclick="MM_openBrWindow('Judgement.php?sid=<?php echo safe_output($row['session_id']); ?>&fid=<?php echo safe_output($row['session_fid']); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=200')"> 
                                                <img src="img/ExtendedJud.png" title="مد أجل الحكم" width="20px" height="20px" style="cursor:pointer" onclick="MM_openBrWindow('ExtendJud.php?fid=<?php echo safe_output($row['session_fid']); ?>&sid=<?php echo safe_output($row['session_id']); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=200')"> 
                                                <img src="img/referral.png" title="تمت الاحالة" width="20px" height="20px" style="cursor: pointer;" onclick="location.href='editfile.php?page=hearing.php&<?php echo $_SERVER['QUERY_STRING'];?>&referral=1&referralfid=<?php echo safe_output($_GET['id']); ?>&referralsid=<?php echo safe_output($row['session_id']); ?>';"> 
                                                <br>
                                                <img src="img/Handshake.png" title="تم الصلح" width="20px" height="20px" style="cursor: pointer;" onclick="location.href='editfile.php?page=hearing.php&<?php echo $_SERVER['QUERY_STRING'];?>&reconciliation=1&reconciliationfid=<?php echo safe_output($_GET['id']); ?>&reconciliationsid=<?php echo safe_output($row['session_id']); ?>';"></div>
                                                <img src="img/BookedJud.png" title="حجزت للحكم" width="20px" height="20px" style="cursor: pointer;" onclick="MM_openBrWindow('BookedJud.php?sid=<?php echo safe_output($row['session_id']); ?>&fid=<?php echo safe_output($row['session_fid']);?>&deg=<?php echo safe_output($row['session_degree']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=300')"> 
                                                <img src="img/decission.png" title="قرار الجلسة" width="20px" height="20px" style="cursor: pointer;" onclick="MM_openBrWindow('decission.php?sid=<?php echo safe_output($row['session_id']); ?>&fid=<?php echo safe_output($row['session_fid']); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=300')"> 
                                            </td>
                                            <td class="no-print">
                                                <?php if($row_permcheck['session_dperm'] == 1){?>
                                                <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='deletehearing.php?id=<?php echo safe_output($row['session_id']);?>&page=hearing.php&<?php echo safe_output($_SERVER['QUERY_STRING']);?>';">
                                                <?php }?>
                                                <img src="img/printer.png" style="cursor: pointer;" title="طباعة" height="20px" width="20px" onclick="printRow('row-<?php echo safe_output($row['session_id']); ?>');">
                                            </td>
                                        </tr>
                                        <?php }} $stmt->close(); }} else if(isset($_GET['from']) && $_GET['from'] !== '' && isset($_GET['to']) && $_GET['to'] !== ''){?>
                                            <input type="hidden" name="from" value="<?php echo safe_output($_GET['from']);?>">
                                            <input type="hidden" name="to" value="<?php echo safe_output($_GET['to']);?>">
                                            <input type="hidden" name="court" value="<?php echo safe_output($_GET['court']);?>">
                                        <?php
                                            $urlfrom = $_GET['from'];
                                            $urlto = $_GET['to'];
                                            
                                            function isValidDate($date, $format = 'Y-m-d') {
                                                $d = DateTime::createFromFormat($format, $date);
                                                return $d && $d->format($format) === $date;
                                            }
                                            if (!isValidDate($urlfrom) || !isValidDate($urlto)) {
                                                die('<p class="blink" style="text-align:center; color:red; font-size:14px;">يرجى تعديل صيغة كتابة التاريخ الى : Y-m-d <br> او بامكانك اختيار التاريخ من جدول التواريخ</p>');
                                            }
                                            $from = DateTime::createFromFormat('Y-m-d', $urlfrom);
                                            $to = DateTime::createFromFormat('Y-m-d', $urlto);
                                            if ($to < $from) {
                                                die('<p class="blink" style="text-align:center; color:red; font-size:14px;">لا يمكنك كتابة تاريخ بداية اصغر من تاريخ النهاية</p>');
                                            }
                                            
                                            $daterange = new DatePeriod($from, new DateInterval('P1D'), $to->modify('+1 day'));
                                            
                                            $clients = [];
                                            $stmtcresult = $conn->prepare("SELECT * FROM client");
                                            $stmtcresult->execute();
                                            $resultcresult = $stmtcresult->get_result();
                                            while ($clientRow = $resultcresult->fetch_assoc()) {
                                                $clients[$clientRow['id']] = $clientRow;
                                            }
                                            $stmtcresult->close();
                                            
                                            $dates = [];
                                            foreach ($daterange as $date) {
                                                $dates[] = $date;
                                            }
                                            
                                            if (empty($dates)) {
                                                die('لم يتم العثور على اي جلسات بهذه التواريخ');
                                            }
                                            
                                            for ($i = count($dates) - 1; $i >= 0; $i--) {
                                                $format_d = $dates[$i]->format('Y-m-d');
                                                $stmt = $conn->prepare("SELECT * FROM session WHERE session_date=?");
                                                $stmt->bind_param("s", $format_d);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if($result->num_rows > 0){
                                                    if(isset($_GET['court']) && $_GET['court'] !== ''){
                                                        $countrows = 0;
                                                        $stmtcrs = $conn->prepare("SELECT * FROM session WHERE session_date=?");
                                                        $stmtcrs->bind_param("s", $format_d);
                                                        $stmtcrs->execute();
                                                        $resultcrs = $stmtcrs->get_result();
                                                        while($row = $resultcrs->fetch_assoc()){
                                                            $fid = $row['session_fid'];
                                                            $stmtf = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                                                            $stmtf->bind_param("i", $fid);
                                                            $stmtf->execute();
                                                            $resultf = $stmtf->get_result();
                                                            $rowf = $resultf->fetch_assoc();
                                                            
                                                            $fcourt = $rowf['file_court'];
                                                            if($fcourt === $_GET['court']){
                                                                $countrows = $countrows + 1;
                                                            }
                                                        }
                                                        if($countrows == 0){
                                                            continue;
                                                        }
                                                    }
                                        ?>
                                        <tr>
                                            <th colspan="10" align="right" class="sbp-hearings" style="cursor: unset; text-align: right;">
                                                رول الجلسات لتاريخ <font color="#fff" style="background-color: #125386; border-radius: 10px; padding: 4px;"><?php echo safe_output($format_d);?></font>
                                            </th>
                                        </tr>
                                        <?php
                                            $count = 0;
                                            while($row = $result->fetch_assoc()){
                                                if(isset($_GET['court']) && $_GET['court'] !== ''){
                                                    $fid = $row['session_fid'];
                                                    $stmtf = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                                                    $stmtf->bind_param("i", $fid);
                                                    $stmtf->execute();
                                                    $resultf = $stmtf->get_result();
                                                    $rowf = $resultf->fetch_assoc();
                                                    
                                                    $fcourt = $rowf['file_court'];
                                                    if($fcourt !== $_GET['court']){
                                                        continue;
                                                    }
                                                }
                                                $count++;
                                                if(isset($row['session_fid']) && $row['session_id'] !== ''){
                                                    $sfid = $row['session_fid'];
                                                    
                                                    $stmtfile = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                                                    $stmtfile->bind_param("i", $sfid);
                                                    $stmtfile->execute();
                                                    $resultfile = $stmtfile->get_result();
                                                    
                                                    if($resultfile->num_rows > 0){
                                                        $rowfile = $resultfile->fetch_assoc();
                                                        if($rowfile['secret_folder'] == 1){
                                                            $count--;
                                                            continue;
                                                        }
                                                    }
                                                }
                                        ?>
                                        <tr class="infotable-body" id="row-<?php echo safe_output($row['session_id']); ?>">
                                            <td <?php if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){?> onclick="MM_openBrWindow('hearing_edit.php?sid=<?php echo safe_output($row['session_id']);?>&fid=<?php echo safe_output($row['session_fid']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=1000,height=600')" <?php }?> style="<?php if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){?> cursor:pointer; <?php }?>">
                                                <div style="height: 70px">
                                                    <div style="display: grid; grid-template-rows: 18px 52px; align-items: center;">
                                                        <div style="text-align: left;" class="no-print">
                                                            <img src="img/edit.png" width="18px" height="18px">
                                                        </div>
                                                        <b><?php if(isset($row['session_date']) && $row['session_date'] !== ''){ echo safe_output($row['session_date']); }?></b>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo safe_output($count);?></td>
                                            <td <?php if($row_permcheck['cfiles_eperm'] == 1){?> style="cursor: pointer" onclick="MM_openBrWindow('FileEdit.php?id=<?php echo safe_output($row['session_fid']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=1000,height=600')" <?php }?>>
                                                <b>رقم القضية :</b> <font color=blue><?php if(isset($row['session_degree']) && $row['session_degree'] !== ''){ echo safe_output($row['session_degree']); }?> - <?php if(isset($row['case_num']) && $row['case_num'] !== ''){ echo safe_output($row['case_num']); }?>/<?php if(isset($row['year']) && $row['year'] !== ''){ echo safe_output($row['year']); }?></font>
                                                <br />
                                                <b>المحكمة : </b><?php if(isset($rowfile['file_court']) && $rowfile['file_court'] !== ''){ echo safe_output($rowfile['file_court']); }?><br />
                                                <font color="#FF0000"><?php if(isset($row['session_date']) && $row['session_date'] !== ''){ echo safe_output($row['session_date']); }?></font>
                                                <?php if($row['expert_session'] === '1'){?><br><img src="img/suitcase.png" width="25px" height="25px" title="جلسات الخبرة"><br><?php }?>
                                            </td>
                                            <td <?php if($row_permcheck['cfiles_eperm'] == 1){?> style="cursor: pointer" onclick="MM_openBrWindow('FileEdit.php?id=<?php echo safe_output($row['session_fid']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=1000,height=600')" <?php }?>>
                                                <?php if(isset($rowfile['file_client']) && $rowfile['file_client'] !== ''){?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_client']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fclient_characteristic']) && $rowfile['fclient_characteristic'] !== ''){ echo ' / ' . safe_output($rowfile['fclient_characteristic']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($rowfile['file_client2']) && $rowfile['file_client2'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_client2']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fclient_characteristic2']) && $rowfile['fclient_characteristic2'] !== ''){ echo ' / ' . safe_output($rowfile['fclient_characteristic2']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($rowfile['file_client3']) && $rowfile['file_client3'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_client3']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fclient_characteristic3']) && $rowfile['fclient_characteristic3'] !== ''){ echo ' / ' . safe_output($rowfile['fclient_characteristic3']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($rowfile['file_client4']) && $rowfile['file_client4'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_client4']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fclient_characteristic4']) && $rowfile['fclient_characteristic4'] !== ''){ echo ' / ' . safe_output($rowfile['fclient_characteristic4']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($rowfile['file_client5']) && $rowfile['file_client5'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_client5']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fclient_characteristic5']) && $rowfile['fclient_characteristic5'] !== ''){ echo ' / ' . safe_output($rowfile['fclient_characteristic5']); }
                                                    ?>
                                                </p>
                                                <?php }?>
                                            </td>
                                            <td <?php if($row_permcheck['cfiles_eperm'] == 1){?> style="cursor: pointer" onclick="MM_openBrWindow('FileEdit.php?id=<?php echo safe_output($row['session_fid']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=1000,height=600')" <?php }?>>
                                                <?php if(isset($rowfile['file_opponent']) && $rowfile['file_opponent'] !== ''){?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_opponent']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fopponent_characteristic']) && $rowfile['fopponent_characteristic'] !== ''){ echo ' / ' . safe_output($rowfile['fopponent_characteristic']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($rowfile['file_opponent2']) && $rowfile['file_opponent2'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_opponent2']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fopponent_characteristic2']) && $rowfile['fopponent_characteristic2'] !== ''){ echo ' / ' . safe_output($rowfile['fopponent_characteristic2']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($rowfile['file_opponent3']) && $rowfile['file_opponent3'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_opponent3']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fopponent_characteristic3']) && $rowfile['fopponent_characteristic3'] !== ''){ echo ' / ' . safe_output($rowfile['fopponent_characteristic3']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($rowfile['file_opponent4']) && $rowfile['file_opponent4'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_opponent4']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fopponent_characteristic4']) && $rowfile['fopponent_characteristic4'] !== ''){ echo ' / ' . safe_output($rowfile['fopponent_characteristic4']); }
                                                    ?>
                                                </p>
                                                <?php 
                                                    }
                                                    if(isset($rowfile['file_opponent5']) && $rowfile['file_opponent5'] !== ''){
                                                ?>
                                                <p>
                                                    <?php 
                                                        $cid = $rowfile['file_opponent5']; 
                                                        echo safe_output($clients[$cid]['arname']) ?? '';
                                                        if(isset($rowfile['fopponent_characteristic5']) && $rowfile['fopponent_characteristic5'] !== ''){ echo ' / ' . safe_output($rowfile['fopponent_characteristic5']); }
                                                    ?>
                                                </p>
                                                <?php }?>
                                            </td>
                                            <td style="padding:3px;">
                                                <?php if(isset($row['link']) && $row['link'] !== ''){?>
                                                <a href="#" style="text-decoration: none;" onclick="MM_openBrWindow('<?php echo safe_output($row['link']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=1000,height=600')">الرابط</a>
                                                <?php }?>
                                            </td>
                                            <td <?php if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){?> onclick="MM_openBrWindow('hearing_edit.php?sid=<?php echo safe_output($row['session_id']);?>&fid=<?php echo safe_output($row['session_fid']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=1000,height=600')" <?php }?> style="padding:3px; <?php if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){?> cursor:pointer <?php }?>">
                                                <div style="height: 70px">
                                                    <div style="display: grid; grid-template-rows: 18px 52px; align-items: center;">
                                                        <div style="text-align: left;" class="no-print">
                                                            <img src="img/edit.png" width="18px" height="18px">
                                                        </div>
                                                        <b><?php if(isset($row['session_decission']) && $row['session_decission'] !== ''){ echo safe_output($row['session_decission']); }?></b>
                                                    </div>
                                                </div>
                                            </td>
                                            <td <?php if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){?> style="cursor: pointer" onclick="MM_openBrWindow('hearing_edit.php?sid=<?php echo safe_output($row['session_id']);?>&fid=<?php echo safe_output($row['session_fid']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=1000,height=600')" <?php }?>>
                                                <div style="height: 70px">
                                                    <div style="display: grid; grid-template-rows: 18px 52px; align-items: center;">
                                                        <div style="text-align: left;" class="no-print">
                                                            <img src="img/edit.png" width="18px" height="18px">
                                                        </div>
                                                        <b><?php if(isset($row['session_note']) && $row['session_note'] !== ''){ echo safe_output($row['session_note']); }?></b>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="no-print">
                                                <img src="img/BookedJud2.png" title="منطوق الحكم" width="20px" height="20px" style="cursor:pointer" onclick="MM_openBrWindow('Judgement.php?sid=<?php echo safe_output($row['session_id']); ?>&fid=<?php echo safe_output($row['session_fid']); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=200')"> 
                                                <img src="img/ExtendedJud.png" title="مد أجل الحكم" width="20px" height="20px" style="cursor:pointer" onclick="MM_openBrWindow('ExtendJud.php?fid=<?php echo safe_output($row['session_fid']); ?>&sid=<?php echo safe_output($row['session_id']); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=200')"> 
                                                <img src="img/referral.png" title="تمت الاحالة" width="20px" height="20px" style="cursor: pointer;" onclick="location.href='editfile.php?page=hearing.php&<?php echo $_SERVER['QUERY_STRING'];?>&referral=1&referralfid=<?php echo safe_output($_GET['id']); ?>&referralsid=<?php echo safe_output($row['session_id']); ?>';"> 
                                                <br>
                                                <img src="img/Handshake.png" title="تم الصلح" width="20px" height="20px" style="cursor: pointer;" onclick="location.href='editfile.php?page=hearing.php&<?php echo $_SERVER['QUERY_STRING'];?>&reconciliation=1&reconciliationfid=<?php echo safe_output($_GET['id']); ?>&reconciliationsid=<?php echo safe_output($row['session_id']); ?>';"></div>
                                                <img src="img/BookedJud.png" title="حجزت للحكم" width="20px" height="20px" style="cursor: pointer;" onclick="MM_openBrWindow('BookedJud.php?sid=<?php echo safe_output($row['session_id']); ?>&fid=<?php echo safe_output($row['session_fid']);?>&deg=<?php echo safe_output($row['session_degree']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=300')"> 
                                                <img src="img/decission.png" title="قرار الجلسة" width="20px" height="20px" style="cursor: pointer;" onclick="MM_openBrWindow('decission.php?sid=<?php echo safe_output($row['session_id']); ?>&fid=<?php echo safe_output($row['session_fid']); ?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=300')"> 
                                            </td>
                                            <td class="no-print">
                                                <?php if($row_permcheck['session_dperm'] == 1){?>
                                                <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='deletehearing.php?id=<?php echo safe_output($row['session_id']);?>&page=hearing.php&<?php echo safe_output($_SERVER['QUERY_STRING']);?>';">
                                                <?php }?>
                                                <img src="img/printer.png" style="cursor: pointer;" title="طباعة" height="20px" width="20px" onclick="printRow('row-<?php echo safe_output($row['session_id']); ?>');">
                                            </td>
                                        </tr>
                                        <?php }}}}?>
                                    </tbody>
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
        <script src="js/tablePages.js"></script>
        <script src="js/checkAll.js"></script>
        <script src="js/dropdown.js"></script>
        <script src="js/printRow.js"></script>
        <script src="js/printDiv.js"></script>
        <script>
  window.addEventListener("pageshow", function (event) {
    if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
      location.reload();
    }
  });
</script>


    </body>
</html>