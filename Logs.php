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
    <body style="overflow: auto">
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['logs_rperm'] == 1){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">سجلات البرنامج</font></h3>
                            </div>
                        </div>
                        <div class="table-body">
                            <form action="logdel.php" method="post">
                                <table class="info-table" id="myTable" style="width: 100%;">
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
                                            <th width="100px">اسم الموظف</th>
                                            <th>العمل</th>
                                            <th width="100px">الوقت</th>
                                            <th width="50px"></th>
                                        </tr>
                                    </thead>
                                    
                                    <?php
                                        $stmt = $conn->prepare("SELECT * FROM logs ORDER BY id DESC");
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                    ?>
                                    <tbody id="table1">
                                        <tr class="infotable-body">
                                            <td width="100px">
                                                <?php
                                                    if(isset($row['empid']) && $row['empid'] !== ''){
                                                        $empid = $_SESSION['id'];
                                                        $stmtemp = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                        $stmtemp->bind_param("i", $empid);
                                                        $stmtemp->execute();
                                                        $resultemp = $stmtemp->get_result();
                                                        if($resultemp->num_rows > 0){
                                                            $rowemp = $resultemp->fetch_assoc();
                                                            $name = $rowemp['name'];
                                                            echo safe_output($name);
                                                        }
                                                        $stmtemp->close();
                                                    }                           
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if(isset($row['action']) && $row['action'] !== ''){
                                                        echo safe_output($row['action']);
                                                    }
                                                ?>
                                            </td>
                                            <td width="100px">
                                                <?php
                                                    if(isset($row['timestamp']) && $row['timestamp'] !== ''){
                                                        list($time, $date) = explode(" ", $row['timestamp']);
                                                        echo safe_output($time).'<br>'.safe_output($date);
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if($row_permcheck['logs_dperm'] == 1){?>
                                                <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='deletelog.php?id=<?php echo safe_output($row['id']);?>';">
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