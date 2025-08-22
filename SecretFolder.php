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
    </head>
    <body>
        <?php if($row_permcheck['secretf_aperm'] == 1){?>
        <br><br>
        <div style="text-align: -webkit-center;">
            <div class="advinputs-container" style="width: 500px; text-align: -webkit-center">
                <form name="addform" action="secretfs.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="fid" value="<?php echo safe_output($_GET['id']);?>">
                    <h2 class="advinputs-h2 blue-parag">ملف سري</h2>
                    <div class="advinputs">
                        <div class="select-container input-container">
                            <?php
                                $fid = $_GET['id'];
                                
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
                                
                                $stmt = $conn->prepare("SELECT * FROM file WHERE file_id = ?");
                                $stmt->bind_param("i", $fid);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();
                                $stmt->close();
                                
                                if($row['secret_emps'] !== ''){
                                    $secret_emps = $row['secret_emps'];
                                } else{
                                    $secret_emps = '';
                                }
                            ?>
                            <input type="checkbox" name="secret_folder" value="1" style="margin: 10px 0;" <?php if(isset($row['secret_folder']) && $row['secret_folder'] == 1){ echo 'checked'; }?>>
                            <font class="blue-parag"> تعيين كملف سري</font>
                        </div>
                        <div class="select-container input-container">
                            <p class="input-parag"><font class="blue-parag">الموظفين المصرح لهم بمراجعة و تعديل الملف</font></p>
                            <select name="employee_id[]" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;" onchange="handleSelect(this)">
                                <option></option>
                                <?php
                                    if($secret_emps !==  ''){
                                        $ids1 = explode(',', $secret_emps);
                                        $placeholders1 = implode(',', array_fill(0, count($ids1), '?'));
                                        $types1 = str_repeat('i', count($ids1));
                                        
                                        $sql1 = "SELECT * FROM user WHERE id NOT IN ($placeholders1) ORDER BY id DESC";
                                        $stmtemps = $conn->prepare($sql1);
                                        $stmtemps->bind_param($types1, ...$ids1);
                                        $stmtemps->execute();
                                    } else{
                                        $stmtemps = $conn->prepare("SELECT * FROM user");
                                        $stmtemps->execute();
                                    }
                                    $resultemps = $stmtemps->get_result();
                                    if($resultemps->num_rows > 0){
                                        while($rowemps = $resultemps->fetch_assoc()){
                                ?>
                                <option value="<?php echo safe_output($rowemps['id']);?>"><?php echo safe_output($rowemps['name']);?></option>
                                <?php 
                                        }
                                    }
                                    $stmtemps->close();
                                ?>
                            </select>
                        </div>
                        <input type="submit" value="حفظ البيانات" class="green-button">
                    </div>
                </form>
            </div>
        </div>
        
        <div class="table-container">
            <p></p>
            <div class="table-body">
                <table class="info-table" id="myTable" style="width: 450px">
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
                            <th width="100px">الرقم الوظيفي</th>
                            <th>اسم الموظف</th>
                            <th>ازالة</th>
                        </tr>
                    </thead>
                    
                    <tbody id="table1">
                        <?php
                            $ids = explode(',', $secret_emps);
                            $placeholders = implode(',', array_fill(0, count($ids), '?'));
                            $types = str_repeat('i', count($ids));
                            
                            $sql = "SELECT * FROM user WHERE id IN ($placeholders) ORDER BY id DESC";
                            $stmt = $conn->prepare($sql);
                            
                            $stmt->bind_param($types, ...$ids);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if($result->num_rows > 0){
                                while($row = $result->fetch_assoc()){
                        ?>
                        <tr class="infotable-body">
                            <td width="100px"><?php echo safe_output($row['id']);?></td>
                            <td><?php echo safe_output($row['name']);?></td>
                            <td width="100px" style="cursor: pointer;" onclick="location.href='secdel.php?fid=<?php echo safe_output($_GET['id']);?>&empid=<?php echo safe_output($row['id']);?>';"><img src="img/remove.png" width="20px" height="20px" onclick="secdel.php"></td>
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
            </div>
        </div>
        
        <script src="js/dynamic_selectors.js"></script>
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