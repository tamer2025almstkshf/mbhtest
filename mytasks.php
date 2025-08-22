<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
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
    <body style="overflow: auto">
        <?php
        ?>
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['admprivjobs_rperm'] == 1){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <form action="tchangetype.php" method="post" enctype="multipart/form-data">
                                <div class="table-header-right">
                                    <h3 style="display: inline-block"><font id="clients-translate">اعمال موكلة</font></h3>
                                    <select class="table-header-selector" name="type" onchange="submit()" style="padding: 0 5px;">
                                        <option value="select">اختر التصنيف</option>
                                        <option value="all" <?php if($_GET['type'] === 'all'){ echo 'selected'; }?>><font id="all-translate">اجمالى الاعمال الإدارية</font></option>
                                        <option value="inprogress" <?php if($_GET['type'] === 'inprogress'){ echo 'selected'; }?>><font id="clients-translate">أعمال إدارية جارى العمل عليها</font></option>
                                        <option value="pending" <?php if($_GET['type'] === 'pending'){ echo 'selected'; }?>><font id="opponents-translate">اعمال إدارية لم يتخذ بها إجراء</font></option>
                                        <option value="finished" <?php if($_GET['type'] === 'finished'){ echo 'selected'; }?>><font id="opponents-translate">اعمال إدارية منتهية</font></option>
                                    </select>
                                </div>
                                <input type="hidden" name="page" value="mytasks.php">
                            </form>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"></div>
                                <div class="table-header-icons" style="margin-right: 30px;"></div>
                                
                                <div class="modal-overlay" <?php if(isset($_GET['actions']) && $_GET['actions'] == 1){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content" style="margin: auto; align-content: center">
                                        <div class="notes-displayer">
                                            <div class="addc-header">
                                                <h4 class="addc-header-parag" style="margin: auto">اجراءات المهام</h4>
                                                <div class="close-button-container">
                                                    <p class="close-button" onclick="location.href='mytasks.php';" style="display: inline-block">&times;</p>
                                                </div>
                                            </div>
                                            <div class="notes-body" style="padding: 10px; text-align: right;">
                                                <form action="task_statusedit.php" method="post">
                                                    <th width="95%" align="right">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="page" value="mytasks.php";>
                                                                    <font  style="font-size:16px; font-weight:bold">تحويل الى :</font> 
                                                                    <input type="hidden" name="idddd" value="<?php echo safe_output($_GET['taskid']); ?>">
                                                                    <select class="table-header-selector" name="re_name" style="width: 60%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                                        <?php
                                                                            $oldemp = '';
                                                                            
                                                                            $oldtskold = safe_output($_GET['taskid']);
                                                                            $stmttskold = $conn->prepare("SELECT * FROM tasks WHERE id=?");
                                                                            $stmttskold->bind_param("i", $oldtskold);
                                                                            $stmttskold->execute();
                                                                            $resulttskold = $stmttskold->get_result();
                                                                            if($resulttskold->num_rows > 0){
                                                                                $rowtskold = $resulttskold->fetch_assoc();
                                                                                $oldemp = $rowtskold['employee_id'];
                                                                            }
                                                                            
                                                                            $stmtemps = $conn->prepare("SELECT * FROM user");
                                                                            $stmtemps->execute();
                                                                            $resultemps = $stmtemps->get_result();
                                                                            if($resultemps->num_rows > 0){
                                                                                while($rowemps = $resultemps->fetch_assoc()){
                                                                        ?>
                                                                        <option value="<?php echo safe_output($rowemps['id']); ?>" <?php if($rowemps['id'] == $oldemp){ echo 'selected'; }?>><?php echo safe_output($rowemps['name']); ?></option>
                                                                        <?php
                                                                                }
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                    <br>
                                                                    <p style="text-align: right"><font style="font-size:16px; font-weight:bold">الملاحظة :</font> </p>
                                                                    <textarea name="t_note" rows="2" class="form-input"></textarea>
                                                                    <input type="hidden" name="getfid" value="<?php echo safe_output($_GET['id']); ?>">
                                                                    <br><br>
                                                                    <input type="submit" value="جاري العمل" name="inpt" style="cursor:pointer;" class="form-btn submit-btn">
                                                                    <input type="submit" value="تم الانتهاء" name="endt" style="cursor:pointer;" class="form-btn submit-btn">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </th>
                                                    
                                                    <br>
                                                    <td width="5%" align="right">
                                                        <button type="submit" name="submit_re_name" class="form-btn submit-btn">حفظ</button>
                                                    </td>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-body" id="printSection">
                            <form action="taskdel.php" method="post">
                                <table class="info-table" id="myTable" style="width: 100%; background-color: #99999940">
                                    <thead>
                                        <tr class="infotable-search no-print">
                                            <td colspan="19">
                                                <div class="input-container">
                                                    <p class="input-parag" style="display: inline-block">البحث : </p>
                                                    <input class="form-input" style="display: inline-block; width: 50%;" type="text" id="SearchBox">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="infotable-header" style="position: sticky; top: 0; z-index: 3;">
                                            <td align="center" width="20px">ت</td>
                                            <td align="center">رقم الملف</td>
                                            <td align="center">ت.التنفيذ</td>
                                            <td align="center">المهمة <font color="#ff0000">*</font></td>
                                            <td align="center">التفاصيل</td>
                                            <td align="center">اهمية المهمة</td>
                                            <td align="center">درجة التقاضي</td>
                                            <td align="center">الموظف المكلف <font color="#ff0000">*</font></td>
                                            <td align="center">ت.م الادخال</td>
                                            <td align="center" width="80px">الاجراءات</td>
                                        </tr>
                                    </thead>
                                    
                                    <tbody id="table1">
                                        <?php
                                            $myempid = $_SESSION['id'];
                                            if(isset($_GET['type']) && $_GET['type'] !== ''){
                                                $type = $_GET['type'];
                                                if($type === 'pending'){
                                                    $where = " WHERE employee_id='$myempid' AND task_status='0'";
                                                } else if($type === 'inprogress'){
                                                    $where = " WHERE employee_id='$myempid' AND task_status='1'";
                                                } else if($type === 'finished'){
                                                    $where = " WHERE employee_id='$myempid' AND task_status='2'";
                                                } else{
                                                    $where = " WHERE employee_id='$myempid'";
                                                }
                                            } else{
                                                $where = " WHERE employee_id='$myempid'";
                                            }
                                            $query = "SELECT * FROM tasks$where ORDER BY id DESC";
                                            $result = mysqli_query($conn, $query);
                                            
                                            $counttsk = 0;
                                            if($result->num_rows > 0){
                                                while($row = mysqli_fetch_array($result)){
                                                    $counttsk++;
                                        ?>
                                        <tr class="infotable-body" id="row-<?php echo $row['id']; ?>">
                                            <td align="center" <?php if($row['task_status'] == 1){ echo "style = 'background-color: #faffa6;'"; } else if($row['task_status'] == 2){ echo "style = 'background-color: #B5F3A3;'"; }?>><?php echo safe_output($counttsk); ?></td>
                                            
                                            <td align="center" <?php if($row_permcheck['cfiles_rperm'] == 1 && isset($row['file_no']) && $row['file_no'] !== '' && $row['file_no'] != 0){?> style="cursor: pointer" onclick="location.href='FileEdit.php?id=<?php echo $row['file_no'];?>';" <?php }?>><?php if(isset($row['file_no']) && $row['file_no'] !== '' && $row['file_no'] != 0){ echo $row['file_no']; }?></td>
                                            <td align="center"><?php if(isset($row['duedate']) && $row['duedate'] !== ''){echo safe_output($row['duedate']);}?></td>
                                            
                                            <td align="center">
                                                <?php
                                                    if(isset($row['task_type']) && $row['task_type'] !== ''){
                                                        $tskkid = $row['task_type'];
                                                        
                                                        $stmttskkid = $conn->prepare("SELECT * FROM job_name WHERE id=?");
                                                        $stmttskkid->bind_param("i", $tskkid);
                                                        $stmttskkid->execute();
                                                        $resulttskkid = $stmttskkid->get_result();
                                                        if($resulttskkid->num_rows > 0){
                                                            $rowkid = $resulttskkid->fetch_assoc();
                                                            if(isset($rowkid['job_name'])){ echo safe_output($rowkid['job_name']); }
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            
                                            <td align="center">
                                                <?php 
                                                    if(isset($row['details']) && $row['details'] !== ''){
                                                        echo safe_output($row['details']).'<br>';
                                                    }
                                                    
                                                    $stmttsknotes = $conn->prepare("SELECT * FROM task_notes WHERE taskid=?");
                                                    $stmttsknotes->bind_param("i", $row['id']);
                                                    $stmttsknotes->execute();
                                                    $resulttsknotes = $stmttsknotes->get_result();
                                                    if($resulttsknotes->num_rows > 0){
                                                        $counttsknotes = 0;
                                                        while($rownotes = $resulttsknotes->fetch_assoc()){
                                                            $counttsknotes++;
                                                ?>
                                                <br>
                                                <ul>
                                                    <li style='display: grid; grid-template-columns: 1fr 20px;'>
                                                        <p><?php echo '&#8226; '. $rownotes['note'];?></p>
                                                        <?php if($row['employee_id'] == $_SESSION['id'] || $admin == 1){?>
                                                        <img src="img/recycle-bin.png" width="20px" height="20px" style="cursor: pointer" onclick="location.href='task_statusedit.php?page=mytasks.php&tsknoteid=<?php echo $rownotes['id'];?>&fidnotetsk=<?php echo $_GET['id'];?>';">
                                                        <?php }?>
                                                    </li>
                                                </ul>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            
                                            <td align="center">
                                                <?php 
                                                    if(isset($row['priority']) && $row['priority'] !== ''){
                                                        if($row['priority'] == 0){
                                                            echo 'عادي';
                                                        } else if($row['priority'] == 1){
                                                            echo 'عاجل';
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            
                                            <td align="center">
                                                <?php 
                                                    if(isset($row['degree']) && $row['degree'] !== ''){
                                                        $didd = $row['degree'];
                                                        
                                                        $stmtdidd = $conn->prepare("SELECT * FROM file_degrees WHERE id=?");
                                                        $stmtdidd->bind_param("i", $didd);
                                                        $stmtdidd->execute();
                                                        $resultdidd = $stmtdidd->get_result();
                                                        if($resultdidd->num_rows > 0){
                                                            $rowdidd = $resultdidd->fetch_assoc();
                                                            if(isset($rowdidd['degree'])){ echo $rowdidd['degree']; }
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            
                                            <td align="center" style="<?php if($row['employee_id'] == $_SESSION['id']){echo 'color: red';}?>">
                                                <?php
                                                    if(isset($row['employee_id']) && $row['employee_id'] !== ''){
                                                        $empppid = $row['employee_id'];
                                                        
                                                        $stmtemppidd = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                        $stmtemppidd->bind_param("i", $empppid);
                                                        $stmtemppidd->execute();
                                                        $resultemppidd = $stmtemppidd->get_result();
                                                        if($resultemppidd->num_rows > 0){
                                                            $rowemppidd = $resultemppidd->fetch_assoc();
                                                            if(isset($rowemppidd['name'])){ echo $rowemppidd['name']; }
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            
                                            <td align="center" style="color: #333; font-size: 12px;">
                                                <?php 
                                                    if(isset($row['responsible']) && $row['responsible'] !== ''){
                                                        $etid = $row['responsible'];
                                                        
                                                        $stmtem = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                        $stmtem->bind_param("i", $etid);
                                                        $stmtem->execute();
                                                        $resultem = $stmtem->get_result();
                                                        
                                                        $rowem = $resultem->fetch_assoc();
                                                        echo $rowem['name'].'<br>'.$row['timestamp'];
                                                    }
                                                ?>
                                            </td>
                                            
                                            <td style="align-content: center;">
                                                <img src="img/actions.png" style="cursor: pointer;" title="الاجراءات" height="20px" width="20px" onclick="location.href='mytasks.php?actions=1&taskid=<?php echo $row['id'];?>';">
                                                <img src="img/printer.png" style="cursor: pointer;" title="طباعة" height="20px" width="20px" onclick="printRow('row-<?php echo $row['id']; ?>');">
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php }}}?>
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
        <script src="js/printDiv.js"></script>
        <script src="js/printRow.js"></script>
    </body>
</html>