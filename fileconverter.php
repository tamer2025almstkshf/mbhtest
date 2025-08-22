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
    <body>
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['cfiles_eperm'] == 1){
                ?>
                <div class="web-page"><br>
                    <div class="advinputs-container">
                        <form action="fconv.php" method="post" enctype="multipart/form-data">
                            <h2 class="advinputs-h2">تحويل نوع الملف</h2>
                            <div style="width: 100%; text-align: center;">
                                <div class="input-container" style="width: 100%; text-align: center;">
                                    <p class="input-parag"><font class="blue-parag">رقم الملف المراد تحويله</font></p>
                                    <input class="form-input" style="width: 50%; min-width: 100px" type="number" name="fid" value="<?php echo safe_output($_GET['fid']);?>" onChange="submit()">
                                </div>
                            </div>
                            <?php
                                if(isset($_GET['fid']) && $_GET['fid'] !== ''){
                                    $fid = safe_output($_GET['fid']);
                                    
                                    $stmtid = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                                    $stmtid->bind_param("i", $fid);
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
                                    $stmt = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                                    $stmt->bind_param("i", $fid);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if($result->num_rows > 0){
                                        $row = $result->fetch_assoc();
                            ?>
                            <table class="info-table" id="myTable" style="width: 100%">
                                <thead>
                                    <tr class="infotable-header" style="position: sticky; top: 0; z-index: 3;">
                                        <th>رقم الملف</th>
                                        <th>الموضوع</th>
                                        <th>رقم القضية</th>
                                        <th>الموكل</th>
                                        <th>الخصم</th>
                                    </tr>
                                </thead>
                                
                                <?php
                                    $stmt = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                                    $stmt->bind_param("i", $fid);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if($result->num_rows > 0){
                                        $row = $result->fetch_assoc();
                                ?>
                                <tbody id="table1">
                                    <tr class="infotable-body">
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
                                            <?php echo safe_output($fileid);?>
                                        </td>
                                        <td style="color: #007bff"><?php echo safe_output($row['file_subject']);?></td>
                                        <td>
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
                                                            echo safe_output($row_degs['case_num']) . '/' . safe_output($row_degs['file_year']) . '-' . safe_output($row_degs['degree']);
                                                        }
                                                    }
                                                ?>
                                            </font>
                                        </td>
                                        <td>
                                            <?php if(isset($row['file_client']) && $row['file_client'] !== ''){?>
                                            <p>
                                                <?php 
                                                    $cid = $row['file_client']; 
                                                    $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                    $stmtc->bind_param("i", $cid);
                                                    $stmtc->execute();
                                                    $resultc = $stmtc->get_result();
                                                    $rowc = $resultc->fetch_assoc();    
                                                    
                                                    echo safe_output($rowc['arname']);
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
                                                    
                                                    echo safe_output($rowc['arname']);
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
                                                    
                                                    echo safe_output($rowc['arname']);
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
                                                    
                                                    echo safe_output($rowc['arname']);
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
                                                    
                                                    echo safe_output($rowc['arname']);
                                                    if(isset($row['fclient_characteristic5']) && $row['fclient_characteristic5'] !== ''){ echo ' / ' . safe_output($row['fclient_characteristic5']); }
                                                ?>
                                            </p>
                                            <?php }?>
                                        </td>
                                        <td>
                                            <?php if(isset($row['file_opponent']) && $row['file_opponent'] !== ''){?>
                                            <p>
                                                <?php 
                                                    $cid = $row['file_opponent']; 
                                                    $stmtc = $conn->prepare("SELECT * FROM client WHERE id=?");
                                                    $stmtc->bind_param("i", $cid);
                                                    $stmtc->execute();
                                                    $resultc = $stmtc->get_result();
                                                    $rowc = $resultc->fetch_assoc();    
                                                    
                                                    echo safe_output($rowc['arname']);
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
                                                    
                                                    echo safe_output($rowc['arname']);
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
                                                    
                                                    echo safe_output($rowc['arname']);
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
                                                    
                                                    echo safe_output($rowc['arname']);
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
                                                    
                                                    echo safe_output($rowc['arname']);
                                                    if(isset($row['fopponent_characteristic5']) && $row['fopponent_characteristic5'] !== ''){ echo ' / ' . safe_output($row['fopponent_characteristic5']); }
                                                ?>
                                            </p>
                                            <?php }?>
                                        </td>
                                    </tr>
                                </tbody>
                                <?php }?>
                            </table>
                            <?php
                                $stmt2 = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                                $stmt2->bind_param("i", $fid);
                                $stmt2->execute();
                                $result2 = $stmt2->get_result();
                                $row2 = $result2->fetch_assoc();
                            ?>
                            <div class="input-container" style="width: 100%; text-align: center;">
                                <p class="input-parag"><font class="blue-parag">نوع الملف</font></p>
                                <select class="table-header-selector" name="type_id" style="width: 50%; min-width: 100px; max-width: 200px; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                    <option value="" ></option>
                                    <option value="مدني -عمالى" <?php if($row2['file_type'] === 'مدني -عمالى'){ echo 'selected'; }?>>مدني -عمالى</option>
                                    <option value="أحوال شخصية" <?php if($row2['file_type'] === 'أحوال شخصية'){ echo 'selected'; }?>>أحوال شخصية</option>
                                    <option value="جزاء" <?php if($row2['file_type'] === 'جزاء'){ echo 'selected'; }?>>جزاء</option>
                                    <option value="المنازعات الإيجارية" <?php if($row2['file_type'] === 'المنازعات الإيجارية'){ echo 'selected'; }?>>المنازعات الإيجارية</option>
                                </select>
                            </div>
                            <button type="button" class="green-button" onclick="submit();" style="width: 100%; font-size: 20px; margin-top: 10px">اضغط هنا لتحويل نوع الملف</button>
                            <?php }}?>
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
    </body>
</html>