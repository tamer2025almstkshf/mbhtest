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
    </head>
    <body style="overflow: auto; padding-bottom: 50px;">
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['sessionrole_rperm'] == 1){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">جلسات صدر فيها الحكم</font></h3>
                            </div>
                            <div class="table-header-left">
                                <div class="" style="margin-right: 10px;"></div>
                                <div class="" style="margin-right: 20px;"></div>
                                <div class="table-header-icons" style="margin-right: 30px;"></div>
                            </div>
                        </div>
                        <div class="table-body">
                            <form method="post" name="HearingForm" action="deletehearing.php" enctype="multipart/form-data">
                                <input type="hidden" name="page" value="judgedHearings.php">
                                <table class="info-table" id="myTable" style="width: 100%; background-color: #99999940">
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
                                            <th>تاريخ الجلسة</th>
                                            <th style="width: 50px;">ت</th>
                                            <th>القضية</th>
                                            <th>الموكل وصفته</th>
                                            <th>الخصم وصفته</th>
                                            <th>الحكم</th>
                                            <th>ملاحظات</th>
                                            <th width="50px">الاجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table1">
                                        <?php
                                            $empty = '';
                                            $stmt = $conn->prepare("SELECT * FROM session WHERE session_trial != ? ORDER BY session_id DESC");
                                            $stmt->bind_param("s", $empty);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            if($result->num_rows > 0){
                                                $count = 0;
                                                while($row=$result->fetch_assoc()){
                                                    $count++;
                                                    if(isset($row['session_fid']) && $row['session_id'] !== ''){
                                                        $sfid = $row['session_fid'];
                                                        
                                                        $stmtfile = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                                                        $stmtfile->bind_param("i", $sfid);
                                                        $stmtfile->execute();
                                                        $resultfile = $stmtfile->get_result();
                                                        
                                                        if($resultfile->num_rows > 0){
                                                            $rowfile = $resultfile->fetch_assoc();
                                                            if($rowfile['secret_folder'] === '1'){
                                                                continue;
                                                            }
                                                        }
                                                        $stmtfile->close();
                                                    }
                                        ?>
                                        <tr class="infotable-body">
                                            <td style="cursor:pointer" <?php if(isset($row['session_fid']) && $row['session_fid'] != 0){?>onclick="MM_openBrWindow('CasePreview.php?fid=<?php echo safe_output($row['session_fid']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')"<?php }?>>
                                                <b><?php if(isset($row['session_date']) && $row['session_date'] !== ''){ echo safe_output($row['session_date']); }?></b>
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
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo safe_output($rowc['arname']);
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
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo safe_output($rowc['arname']);
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
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo safe_output($rowc['arname']);
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
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo safe_output($rowc['arname']);
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
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo safe_output($rowc['arname']);
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
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo safe_output($rowc['arname']);
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
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo safe_output($rowc['arname']);
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
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo safe_output($rowc['arname']);
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
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo safe_output($rowc['arname']);
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
                                                        $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                        $stmtc->bind_param("i", $cid);
                                                        $stmtc->execute();
                                                        $resultc = $stmtc->get_result();
                                                        $rowc = $resultc->fetch_assoc();
                                                        $stmtc->close();
                                                        
                                                        echo safe_output($rowc['arname']);
                                                        if(isset($rowfile['fopponent_characteristic5']) && $rowfile['fopponent_characteristic5'] !== ''){ echo ' / ' . safe_output($rowfile['fopponent_characteristic5']); }
                                                    ?>
                                                </p>
                                                <?php }?>
                                            </td>
                                            <td style="padding:3px;">
                                                <b><?php if(isset($row['session_trial']) && $row['session_trial'] !== ''){ echo safe_output($row['session_trial']); }?></b>
                                            </td>
                                            <td>
                                                <div align="justify" dir="rtl"><?php if(isset($row['session_note']) && $row['session_note'] !== ''){ echo safe_output($row['session_note']); }?></div>
                                            </td>
                                            <td>
                                                <?php if($row_permcheck['session_dperm'] == 1){?>
                                                <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='deletehearing.php?id=<?php echo safe_output($row['session_id']);?>&page=judgedHearings.php';">
                                                <?php }?>
                                            </td>
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
    </body>
</html>