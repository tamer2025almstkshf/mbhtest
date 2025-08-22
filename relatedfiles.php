<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
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
    <body>
        <div class="container">
            <div class="website">
                <?php 
                    $id = isset($_GET['mfid']) ? (int)$_GET['mfid'] : 0;
                    
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
                    if($row_permcheck['cfiles_eperm'] == 1){
                ?>
                <div class="web-page"><br><br>
                    <div class="advinputs-container">
                        <form method="post" action="javascript:void(0);" name="addform" enctype="multipart/form-data" onsubmit="submitForm()">
                            <h2 class="advinputs-h2">الملفات المرتبطة</h2>
                            <div class="advinputs">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">رقم الملف الرئيسي</font><br><?php if(isset($_GET['error']) && $_GET['error'] === '1'){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى كتابة رقم الملف الرئيسي</span><?php }?></p>
                                    <input class="form-input" type="number" name="mfid" value="<?php echo safe_output($_GET['mfid']);?>" style="font-size:16px; text-align:center; width:50%; color: #00F; cursor: not-allowed;" disabled>
                                    <input class="form-input" type="hidden" name="mfid" value="<?php echo safe_output($_GET['mfid']);?>" style="font-size:16px; text-align:center; width:50%; color: #00F">
                                </div>
                                <div class="input-container">
                                    <table class="info-table" id="myTable" style="width: 100%">
                                        <thead>
                                            <tr class="infotable-header" style="position: sticky; top: 0; z-index: 3;">
                                                <th>رقم الملف</th>
                                                <th>الموضوع</th>
                                                <th>الموكل</th>
                                            </tr>
                                        </thead>
                                        
                                        <?php
                                            if(isset($_GET['mfid']) && $_GET['mfid'] !== ''){
                                                $mfid = $_GET['mfid'];
                                                $stmtm = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                                                $stmtm->bind_param("i", $mfid);
                                                $stmtm->execute();
                                                $resultm = $stmtm->get_result();
                                                if($resultm->num_rows > 0){
                                                    $rowm = $resultm->fetch_assoc();
                                                    $stmtm->close();
                                        ?>
                                        <tbody id="table1">
                                            <tr class="infotable-body" style="cursor:pointer" onclick="MM_openBrWindow('CasePreview.php?fid=<?php echo safe_output($mfid);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                                <td style="color: #007bff; position: sticky; right: 40px; width: 50px;">
                                                    <font color="#FF0000">
                                                        <?php 
                                                            $place = $rowm['frelated_place'];
                                                            $fileid = $rowm['file_id'];
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
                                                <td style="color: #007bff"><?php echo safe_output($rowm['file_subject']);?></td>
                                                <td>
                                                    <?php if(isset($rowm['file_client']) && $rowm['file_client'] !== ''){?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client'];
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic']) && $rowm['fclient_characteristic'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic']); }
                                                        ?>
                                                    </p>
                                                    <?php 
                                                        }
                                                        if(isset($rowm['file_client2']) && $rowm['file_client2'] !== ''){
                                                    ?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client2']; 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic2']) && $rowm['fclient_characteristic2'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic2']); }
                                                        ?>
                                                    </p>
                                                    <?php 
                                                        }
                                                        if(isset($rowm['file_client3']) && $rowm['file_client3'] !== ''){
                                                    ?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client3']; 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic3']) && $rowm['fclient_characteristic3'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic3']); }
                                                        ?>
                                                    </p>
                                                    <?php 
                                                        }
                                                        if(isset($rowm['file_client4']) && $rowm['file_client4'] !== ''){
                                                    ?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client4']; 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic4']) && $rowm['fclient_characteristic4'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic4']); }
                                                        ?>
                                                    </p>
                                                    <?php 
                                                        }
                                                        if(isset($rowm['file_client5']) && $rowm['file_client5'] !== ''){
                                                    ?>
                                                    <p>
                                                        <?php 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic5']) && $rowm['fclient_characteristic5'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic5']); }
                                                        ?>
                                                    </p>
                                                    <?php }?>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <?php }}?>
                                    </table>
                                </div>
                                
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">رقم الملف المرتبط 1</font></p>
                                    <input class="form-input" type="number" name="fid1" value="<?php echo safe_output($_GET['fid1']);?>" style="font-size:16px; text-align:center; width:50%; color: #00F" onchange="submit()">
                                </div>
                                <div class="input-container">
                                    <table class="info-table" id="myTable" style="width: 100%">
                                        <thead>
                                            <tr class="infotable-header" style="position: sticky; top: 0; z-index: 3;">
                                                <th>رقم الملف</th>
                                                <th>الموضوع</th>
                                                <th>الموكل</th>
                                            </tr>
                                        </thead>
                                        
                                        <?php
                                            if(isset($_GET['fid1']) && $_GET['fid1'] !== ''){
                                                $fid1 = $_GET['fid1'];
                                                $stmtm = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                                                $stmtm->bind_param("i", $fid1);
                                                $stmtm->execute();
                                                $resultm = $stmtm->get_result();
                                                if($resultm->num_rows > 0){
                                                    $rowm = $resultm->fetch_assoc();
                                                    $stmtm->close();
                                        ?>
                                        <tbody id="table1">
                                            <tr class="infotable-body" style="cursor:pointer" onclick="MM_openBrWindow('CasePreview.php?fid=<?php echo safe_output($fid1);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                                <td style="color: #007bff; position: sticky; right: 40px; width: 50px;">
                                                    <font color="#FF0000">
                                                        <?php 
                                                            $place = $rowm['frelated_place'];
                                                            $fileid = $rowm['file_id'];
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
                                                <td style="color: #007bff"><?php echo safe_output($rowm['file_subject']);?></td>
                                                <td>
                                                    <?php if(isset($rowm['file_client']) && $rowm['file_client'] !== ''){?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client']; 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic']) && $rowm['fclient_characteristic'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic']); }
                                                        ?>
                                                    </p>
                                                    <?php 
                                                        }
                                                        if(isset($rowm['file_client2']) && $rowm['file_client2'] !== ''){
                                                    ?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client2']; 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic2']) && $rowm['fclient_characteristic2'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic2']); }
                                                        ?>
                                                    </p>
                                                    <?php 
                                                        }
                                                        if(isset($rowm['file_client3']) && $rowm['file_client3'] !== ''){
                                                    ?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client3']; 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic3']) && $rowm['fclient_characteristic3'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic3']); }
                                                        ?>
                                                    </p>
                                                    <?php 
                                                        }
                                                        if(isset($rowm['file_client4']) && $rowm['file_client4'] !== ''){
                                                    ?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client4']; 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic4']) && $rowm['fclient_characteristic4'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic4']); }
                                                        ?>
                                                    </p>
                                                    <?php 
                                                        }
                                                        if(isset($rowm['file_client5']) && $rowm['file_client5'] !== ''){
                                                    ?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client5']; 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic5']) && $rowm['fclient_characteristic5'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic5']); }
                                                        ?>
                                                    </p>
                                                    <?php }?>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <?php }}?>
                                    </table>
                                </div>
                                
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">رقم الملف المرتبط 2</font></p>
                                    <input class="form-input" type="number" name="fid2" value="<?php echo safe_output($_GET['fid2']);?>" style="font-size:16px; text-align:center; width:50%; color: #00F" onchange="submit()">
                                </div>
                                <div class="input-container">
                                    <table class="info-table" id="myTable" style="width: 100%">
                                        <thead>
                                            <tr class="infotable-header" style="position: sticky; top: 0; z-index: 3;">
                                                <th>رقم الملف</th>
                                                <th>الموضوع</th>
                                                <th>الموكل</th>
                                            </tr>
                                        </thead>
                                        
                                        <?php
                                            if(isset($_GET['fid2']) && $_GET['fid2'] !== ''){
                                                $fid2 = $_GET['fid2'];
                                                $stmtm = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                                                $stmtm->bind_param("i", $fid2);
                                                $stmtm->execute();
                                                $resultm = $stmtm->get_result();
                                                if($resultm->num_rows > 0){
                                                    $rowm = $resultm->fetch_assoc();
                                                    $stmtm->close();
                                        ?>
                                        <tbody id="table1">
                                            <tr class="infotable-body" style="cursor:pointer" onclick="MM_openBrWindow('CasePreview.php?fid=<?php echo safe_output($fid2);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                                <td style="color: #007bff; position: sticky; right: 40px; width: 50px;">
                                                    <font color="#FF0000">
                                                        <?php 
                                                            $place = $rowm['frelated_place'];
                                                            $fileid = $rowm['file_id'];
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
                                                <td style="color: #007bff"><?php echo safe_output($rowm['file_subject']);?></td>
                                                <td>
                                                    <?php if(isset($rowm['file_client']) && $rowm['file_client'] !== ''){?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client']; 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic']) && $rowm['fclient_characteristic'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic']); }
                                                        ?>
                                                    </p>
                                                    <?php 
                                                        }
                                                        if(isset($rowm['file_client2']) && $rowm['file_client2'] !== ''){
                                                    ?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client2']; 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic2']) && $rowm['fclient_characteristic2'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic2']); }
                                                        ?>
                                                    </p>
                                                    <?php 
                                                        }
                                                        if(isset($rowm['file_client3']) && $rowm['file_client3'] !== ''){
                                                    ?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client3']; 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic3']) && $rowm['fclient_characteristic3'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic3']); }
                                                        ?>
                                                    </p>
                                                    <?php 
                                                        }
                                                        if(isset($rowm['file_client4']) && $rowm['file_client4'] !== ''){
                                                    ?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client4']; 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic4']) && $rowm['fclient_characteristic4'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic4']); }
                                                        ?>
                                                    </p>
                                                    <?php 
                                                        }
                                                        if(isset($rowm['file_client5']) && $rowm['file_client5'] !== ''){
                                                    ?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client5']; 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic5']) && $rowm['fclient_characteristic5'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic5']); }
                                                        ?>
                                                    </p>
                                                    <?php }?>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <?php }}?>
                                    </table>
                                </div>
                                
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">رقم الملف المرتبط 3</font></p>
                                    <input class="form-input" type="number" name="fid3" value="<?php echo safe_output($_GET['fid3']);?>" style="font-size:16px; text-align:center; width:50%; color: #00F" onchange="submit()">
                                </div>
                                <div class="input-container">
                                    <table class="info-table" id="myTable" style="width: 100%">
                                        <thead>
                                            <tr class="infotable-header" style="position: sticky; top: 0; z-index: 3;">
                                                <th>رقم الملف</th>
                                                <th>الموضوع</th>
                                                <th>الموكل</th>
                                            </tr>
                                        </thead>
                                        
                                        <?php
                                            if(isset($_GET['fid3']) && $_GET['fid3'] !== ''){
                                                $fid3 = $_GET['fid3'];
                                                $stmtm = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                                                $stmtm->bind_param("i", $fid3);
                                                $stmtm->execute();
                                                $resultm = $stmtm->get_result();
                                                if($resultm->num_rows > 0){
                                                    $rowm = $resultm->fetch_assoc();
                                                    $stmtm->close();
                                        ?>
                                        <tbody id="table1">
                                            <tr class="infotable-body" style="cursor:pointer" onclick="MM_openBrWindow('CasePreview.php?fid=<?php echo safe_output($fid3);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                                <td style="color: #007bff; position: sticky; right: 40px; width: 50px;">
                                                    <font color="#FF0000">
                                                        <?php 
                                                            $place = $rowm['frelated_place'];
                                                            $fileid = $rowm['file_id'];
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
                                                <td style="color: #007bff"><?php echo safe_output($rowm['file_subject']);?></td>
                                                <td>
                                                    <?php if(isset($rowm['file_client']) && $rowm['file_client'] !== ''){?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client']; 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic']) && $rowm['fclient_characteristic'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic']); }
                                                        ?>
                                                    </p>
                                                    <?php 
                                                        }
                                                        if(isset($rowm['file_client2']) && $rowm['file_client2'] !== ''){
                                                    ?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client2']; 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic2']) && $rowm['fclient_characteristic2'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic2']); }
                                                        ?>
                                                    </p>
                                                    <?php 
                                                        }
                                                        if(isset($rowm['file_client3']) && $rowm['file_client3'] !== ''){
                                                    ?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client3']; 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic3']) && $rowm['fclient_characteristic3'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic3']); }
                                                        ?>
                                                    </p>
                                                    <?php 
                                                        }
                                                        if(isset($rowm['file_client4']) && $rowm['file_client4'] !== ''){
                                                    ?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client4']; 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic4']) && $rowm['fclient_characteristic4'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic4']); }
                                                        ?>
                                                    </p>
                                                    <?php 
                                                        }
                                                        if(isset($rowm['file_client5']) && $rowm['file_client5'] !== ''){
                                                    ?>
                                                    <p>
                                                        <?php 
                                                            $cid = $rowm['file_client5']; 
                                                            $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                            $stmtc->bind_param("i", $cid);
                                                            $stmtc->execute();
                                                            $resultc = $stmtc->get_result();
                                                            $rowc = $resultc->fetch_assoc();
                                                            $stmtc->close();
                                                            
                                                            echo safe_output($rowc['arname']);
                                                            if(isset($rowm['fclient_characteristic5']) && $rowm['fclient_characteristic5'] !== ''){ echo ' / ' . safe_output($rowm['fclient_characteristic5']); }
                                                        ?>
                                                    </p>
                                                    <?php }?>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <?php }}?>
                                    </table>
                                </div>
                            </div>
                            <button type="submit" name="linkfs" class="green-button" value="اضغط هنا لربط الملفات" style="width: 100%; font-size: 20px; margin-top: 10px">اضغط هنا لربط الملفات</button>
                        </form>
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
            function submitForm() {
                const form = document.forms['addform'];
                const formData = new FormData(form);
                
                fetch('flink.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        window.close();
                        window.opener.location.reload();
                    } else {
                        alert('Error: حدث خطأ');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error: حدث خطأ');
                });
            }
        </script>
    </body>
</html>