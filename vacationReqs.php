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
    <body style="overflow: auto; padding-bottom: 50px;">
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['vacf_aperm'] == 1){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">طلبات الاجازات</font></h3>
                            </div>
                        </div>
                        <div class="table-body">
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
                                        <th>اسم الموظف</th>
                                        <th>نوع الاجازة</th>
                                        <th>من تاريخ</th>
                                        <th>الى تاريخ</th>
                                        <th>ملاحظات</th>
                                        <th width="50px">قبول</th>
                                        <th width="50px">رفض</th>
                                    </tr>
                                </thead>
                                
                                <?php
                                    $use = $_SESSION['id'];
                                    $stmt_vacs = $conn->prepare("SELECT * FROM vacations WHERE ask=?");
                                    $one = 1;
                                    $stmt_vacs->bind_param("i", $one);
                                    $stmt_vacs->execute();
                                    $result_vacs = $stmt_vacs->get_result();
                                    if($result_vacs->num_rows > 0){
                                        while($row_vacs= $result_vacs->fetch_assoc()){
                                ?>
                                <tbody id="table1">
                                    <tr class="infotable-body">
                                        <td>
                                            <?php
                                                if(isset($row_vacs['emp_id']) && $row_vacs['emp_id'] !== ''){
                                                    $emp_id = $row_vacs['emp_id'];
                                                    $querycheck = "SELECT * FROM user WHERE id='$emp_id'";
                                                    $resultcheck = mysqli_query($conn, $querycheck);
                                                    $rowcheck = mysqli_fetch_array($resultcheck);
                                                    echo safe_output($rowcheck['name']);
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if(isset($row_vacs['type']) && $row_vacs['type'] !== ''){
                                                    echo safe_output($row_vacs['type']);
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if(isset($row_vacs['starting_date']) && $row_vacs['starting_date'] !== ''){
                                                    echo safe_output($row_vacs['starting_date']);
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if(isset($row_vacs['ending_date']) && $row_vacs['ending_date'] !== ''){
                                                    echo safe_output($row_vacs['ending_date']);
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if(isset($row_vacs['notes']) && $row_vacs['notes'] !== ''){
                                                    echo safe_output($row_vacs['notes']);
                                                }
                                            ?>
                                        </td>
                                        <td align="center">
                                            <?php if($row_permcheck['vacf_aperm'] == 1){?>
                                            <div class="perms-check" style="cursor:pointer; background-image: url('img/verification.png');" onClick="location.href='vacstatus.php?id=<?php echo safe_output($row_vacs['id']) . '&status=yes&page=vacationReqs.php';?>';"></div>
                                            <?php }?>
                                        </td>
                                        <td align="center">
                                            <?php if($row_permcheck['vacf_aperm'] == 1){?>
                                            <div class="perms-check" style="cursor:pointer; background-image: url('img/remove.png');" onClick="location.href='vacstatus.php?id=<?php echo safe_output($row_vacs['id']) . '&status=no&page=vacationReqs.php';?>';"></div>
                                            <?php }?>
                                        </td>
                                    </tr>
                                </tbody>
                                <?php 
                                        }
                                    }
                                    $stmt_vacs->close();
                                ?>
                            </table>
                        </div>
                        
                        <div class="table-footer">
                            <p></p>
                            <div id="pagination"></div>
                            <div id="pageInfo"></div>
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