<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
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
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1256" />
    </head>
    <body>
        <?php if($row_permcheck['session_rperm'] == 1){?>
        <br>
        <?php 
            if(isset($_GET['fid']) && $_GET['fid'] !== ''){
                $fid = $_GET['fid'];
                
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
            } else{
                exit();
            }
        ?>
        <div class="advinputs-container" style="height: fit-content; overflow-y: auto">
            <?php
                $fid = $_GET['fid'];
                
                $file_degreevalue = '';
                $datevalue = '';
                $casenovalue = '';
                $yearvalue = '';
                $ccvalue = '';
                $ocvalue = '';
                
                if(isset($_GET['id'])){
                    $id = $_GET['id'];
                    $stmtedit = $conn->prepare("SELECT * FROM oppcase WHERE id=?");
                    $stmtedit->bind_param("i", $id);
                    $stmtedit->execute();
                    $resultedit = $stmtedit->get_result();
                    $rowedit = $resultedit->fetch_assoc();
                    $stmtedit->close();
                    
                    $file_degreevalue = $rowedit['file_degree_id'];
                    $datevalue = $rowedit['opp_date'];
                    $casenovalue = $rowedit['case_no'];
                    $yearvalue = $rowedit['year'];
                    $ccvalue = $rowedit['client_characteristic'];
                    $ocvalue = $rowedit['opponent_characteristic'];
                }
            ?>
            <?php if(($row_permcheck['session_aperm'] == 1 && !isset($_GET['id'])) || ($row_permcheck['session_eperm'] == 1 && isset($_GET['id']))){?>
            <form method="post" action="<?php if(isset($_GET['id']) && $_GET['id'] !== ''){ echo 'oppcedit.php'; } else{ echo 'oppc.php'; }?>" enctype="multipart/form-data">
                <input type="hidden" name="timermainid" value="<?php echo safe_output($_GET['fid']);?>">
                <input type="hidden" name="timeraction" value="<?php if(isset($_GET['id']) && $_GET['id'] !== ''){ echo 'oppcase_edit'; } else { echo 'oppcase_add'; }?>">
                <input type="hidden" name="timerdone_date" value="<?php echo date("Y-m-d");?>">
                <input type="hidden" name="timerdone_action" value="<?php if(isset($_GET['id']) && $_GET['id'] !== ''){ echo 'تعديل بيانات القضية المتقابلة'; } else { echo 'ادخال بيانات القضية المتقابلة'; }?>">
                <input type="hidden" name="timer_timestamp" value="<?php echo time();?>">
                <input type="hidden" name="timersubinfo" value="<?php if(isset($_GET['id']) && $_GET['id'] !== ''){ echo $_GET['id']; } else{ echo '0'; }?>">
                <h2 class="advinputs-h2">اضافة - تعديل القضية المتقابلة</h2>
                <input type="hidden" name="fid" value="<?php echo safe_output($fid);?>" required>
                <div class="advinputs3">
                    <div class="input-container">
                        <p class="input-parag"><font class="blue-parag">الدرجة<font color="#FF0000">*</font></font></p>
                        <select name="file_degree_id" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;" required>
                            <option value=""></option>
                            <?php 
                                $fid = $_GET['fid'];
                                $stmt_fdegs = $conn->prepare("SELECT * FROM file_degrees WHERE fid=?");
                                $stmt_fdegs->bind_param("i", $fid);
                                $stmt_fdegs->execute();
                                $result_fdegs = $stmt_fdegs->get_result();
                                
                                if($result_fdegs->num_rows > 0){
                                    while($row_fdegs = $result_fdegs->fetch_assoc()){
                                        $fdegid = $row_fdegs['id'];
                                        $degree = $row_fdegs['degree'];
                                        $case_num = $row_fdegs['case_num'];
                                        $file_year = $row_fdegs['file_year'];
                            ?>
                            <option value='<?php echo safe_output($fdegid);?>' <?php if($fdegid == $file_degreevalue){ echo 'selected'; }?>><?php echo safe_output($degree);?></option>
                            <?php
                                    }
                                }
                                $stmt_fdegs->close();
                            ?>
                        </select>
                    </div>
                    <div class="input-container">
                        <p class="input-parag"><font class="blue-parag">رقم القضية</font></p>
                        <input class="form-input" type="number" name="opp_no" style="width: 100px;" value="<?php echo safe_output($casenovalue);?>" placeholder="رقم القضية">
                        <input class="form-input" type="number" name="opp_year" style="width: 100px;" value="<?php echo safe_output($yearvalue);?>" placeholder="السنة">
                    </div>
                    <div class="input-container">
                        <p class="input-parag"><font class="blue-parag">التاريخ</font></p>
                        <input class="form-input" type="date" name="opp_date" value="<?php echo safe_output($datevalue);?>">
                    </div>
                    <div class="input-container">
                        <p class="input-parag">
                            <font class="blue-parag">
                                صفة الموكل
                            </font>
                            <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="open('selector/AddClientStatus.php','Pic','width=600 height=700 scrollbars=yes')" align="absmiddle" style="cursor:pointer">
                        </p>
                        <select name="client_char" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                            <option value=""></option>
                            <?php 
                                $stmt_scharachteristics11 = $conn->prepare("SELECT * FROM client_status");
                                $stmt_scharachteristics11->execute();
                                $result_scharachteristics11 = $stmt_scharachteristics11->get_result();
                                
                                if($result_scharachteristics11->num_rows > 0){
                                    while($row_scharachteristics11 = $result_scharachteristics11->fetch_assoc()){
                                        $stname11 = $row_scharachteristics11['arname'];
                            ?>
                            <option value='<?php echo safe_output($stname11);?>' <?php if($ccvalue === $stname11){ echo 'selected'; }?>><?php echo safe_output($stname11);?></option>
                            <?php
                                    }
                                }
                                $stmt_scharachteristics11->close();
                            ?>
                        </select>
                    </div>
                    <div class="input-container">
                        <p class="input-parag">
                            <font class="blue-parag">
                                صفة الخصم
                            </font>
                            <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="open('selector/AddClientStatus.php','Pic','width=600 height=700 scrollbars=yes')" align="absmiddle" style="cursor:pointer">
                        </p>
                        <select name="opponent_char" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                            <option value=""></option>
                            <?php 
                                $stmt_scharachteristics11 = $conn->prepare("SELECT * FROM client_status");
                                $stmt_scharachteristics11->execute();
                                $result_scharachteristics11 = $stmt_scharachteristics11->get_result();
                                
                                if($result_scharachteristics11->num_rows > 0){
                                    while($row_scharachteristics11 = $result_scharachteristics11->fetch_assoc()){
                                        $stname11 = $row_scharachteristics11['arname'];
                            ?>
                            <option value='<?php echo safe_output($stname11);?>' <?php if($ocvalue === $stname11){ echo 'selected'; }?>><?php echo safe_output($stname11);?></option>
                            <?php
                                    }
                                }
                                $stmt_scharachteristics11->close();
                            ?>
                        </select>
                    </div>
                </div>
                <?php if(isset($_GET['id']) && $_GET['id'] !== ''){?>
                <input type='hidden' name='id' value='<?php echo safe_output($id);?>'>
                <?php }?>
                <div class="advinputs3">
                    <?php if(isset($_GET['id']) && $_GET['id'] !== ''){?>
                    <button class="green-button" type="submit" value="تعديل البيانات">تعديل البيانات</button>
                    <?php } else{?>
                    <button class="green-button" type="submit" value="حفظ البيانات">حفظ البيانات</button>
                    <?php }?>
                </div>
            </form>
            <?php }?>
            <table width="100%" class="info-table" align="center" style="grid-column: span 2; margin-top: 20px">
                <tbody>
                    <tr align="center" class="header_table" style="padding: 10px" cell-padding="10px">
                        <th style="padding: 10px;" align="center">الدرجة</th>
                        <th style="padding: 10px;" align="center">رقم القضية</th>
                        <th style="padding: 10px;" align="center">السنة</th>
                        <th style="padding: 10px;" align="center">صفة الموكل</th>
                        <th style="padding: 10px;" align="center">صفة الخصم</th>
                        <th style="padding: 10px;" align="center">ت.م الادخال</th>
                        <th width="50px" align="center">الاجراءات</th>
                    </tr>
                    
                    <?php
                        $stmtopp = $conn->prepare("SELECT * FROM oppcase WHERE fid=?");
                        $stmtopp->bind_param("i", $fid);
                        $stmtopp->execute();
                        $resultopp = $stmtopp->get_result();
                        if($resultopp->num_rows > 0){
                            while($rowopp = $resultopp->fetch_assoc()){
                    ?>
                    <tr valign="top" class="infotable-body" style="font-weight: normal;">
                        <td align="center">
                            <font color="#4132d1">
                                <?php
                                    $file_degree_id = $rowopp['file_degree_id'];
                                    
                                    $stmtfdr = $conn->prepare("SELECT * FROM file_degrees WHERE id=?");
                                    $stmtfdr->bind_param("i", $file_degree_id);
                                    $stmtfdr->execute();
                                    $resultfdr = $stmtfdr->get_result();
                                    $rowfdr = $resultfdr->fetch_assoc();
                                    $stmtfdr->close();
                                    
                                    echo safe_output($rowfdr['degree']);
                                ?>
                            </font>
                        </td>
                        <td align="center" style="color: #F00;"><?php echo safe_output($rowopp['case_no']);?></td>
                        <td align="center" style="color: #F00;"><?php echo safe_output($rowopp['year']);?></td>
                        <td align="center" style="color: #F00;"><?php echo safe_output($rowopp['client_characteristic']);?></td>
                        <td align="center" style="color: #F00;"><?php echo safe_output($rowopp['opponent_characteristic']);?></td align="center">
                        <td align="center" style="color: #999999; font-size: 12px;"><?php echo safe_output($rowopp['created_at']);?></td>
                        <td align="center">
                            <?php if($row_permcheck['session_eperm'] == 1){?>
                            <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='oppCase.php?fid=<?php echo safe_output($_GET['fid']);?>&id=<?php echo safe_output($rowopp['id']);?>';">
                            <?php } if($row_permcheck['session_dperm'] == 1){?>
                            <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='oppdel.php?fid=<?php echo safe_output($_GET['fid']);?>&id=<?php echo safe_output($rowopp['id']);?>';">
                            <?php }?>
                        </td>
                    </tr>
                    <?php 
                            }
                        }
                        $stmtopp->close();
                    ?>
                </tbody>
            </table>
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
        <?php }?>
    </body>
</html>