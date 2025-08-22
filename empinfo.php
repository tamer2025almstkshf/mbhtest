<?php
    include_once 'connection.php';
    include_once 'login_check.php';
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
        <?php
            $id = $_SESSION['id'];
            $querymain = "SELECT * FROM user WHERE id='$id'";
            $resultmain = mysqli_query($conn, $querymain);
            $rowmain = mysqli_fetch_array($resultmain);
            
            $myid = $_SESSION['id'];
            $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
            $result_permcheck = mysqli_query($conn, $query_permcheck);
            $row_permcheck = mysqli_fetch_array($result_permcheck);
            
            include_once 'AES256.php';
        ?>
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if(isset($_GET['empid']) && $_GET['empid'] !== ''){
                        if($row_permcheck['emp_rperm'] !== '1' && $_GET['empid'] !== $id){
                            exit;
                        }
                ?>
                <div class="web-page"><br><br>
                    <div style="height: 80vh; align-content: center; text-align: -webkit-center;">
                        <div class="advinputs-container" style="max-height: 80vh; overflow-y: auto; width: fit-content">
                            <h2 class="advinputs-h2">بيانات الموظف</h2>
                            <div class="links-container">
                                <div class="links3">
                                    <div class="link-align">
                                        <div class="link link2" onclick="MM_openBrWindow('UserProfile.php?id=<?php if($_GET['empid'] !== $myid && $row_permcheck['emp_rperm'] === '1'){ echo $_GET['empid']; } else{ echo $myid; }?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/manager.png');"></div>
                                            <p class="link-topic"><font id="courts-translate">ادارة البيانات</font></p>
                                        </div>
                                    </div>
                                    <div class="link-align">
                                        <div class="link link2" onclick="MM_openBrWindow('incdec.php?id=<?php if($_GET['empid'] !== $myid && $row_permcheck['emp_rperm'] === '1'){ echo $_GET['empid']; } else{ echo $myid; }?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/minus.png');"></div>
                                            <p class="link-topic"><font id="courts-translate">الخصومات</font></p>
                                        </div>
                                    </div>
                                    <div class="link-align">
                                        <div class="link link2" onclick="MM_openBrWindow('emp_rates.php?id=<?php if($_GET['empid'] !== $myid && $row_permcheck['emp_rperm'] === '1'){ echo $_GET['empid']; } else{ echo $myid; }?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/rate.png');"></div>
                                            <p class="link-topic"><font id="clients2-translate">تقييمات الموظف</font></p>
                                        </div>
                                    </div>
                                    <div class="link-align" style="grid-column: span 3; display: grid; grid-template-columns: 1fr 1fr; text-align: -webkit-center;">
                                        <div style="width: 100%;text-align: -webkit-left;">
                                            <div class="link link2" onclick="MM_openBrWindow('<?php if($_GET['empid'] !== $myid && $row_permcheck['emp_rperm'] === '1'){ echo "emp_holidays.php?id=$id"; } else{ echo 'vacationReq.php'; }?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                                <div class="images-style" style="background-image: url('img/leave.png');"></div>
                                                <p class="link-topic"><font id="tasks3-translate">الاجازات</font></p>
                                            </div>
                                        </div>
                                        <div class="link link2" onclick="MM_openBrWindow('emp_warns.php?id=<?php if($_GET['empid'] !== $myid && $row_permcheck['emp_rperm'] === '1'){ echo $_GET['empid']; } else{ echo $myid; }?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/warning.png');"></div>
                                            <p class="link-topic"><font id="clients2-translate">الانذارات</font></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                    } else{
                        if($row_permcheck['emp_rperm'] === '1'){
                ?>
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">بيانات الموظفين</font></h3>
                            </div>
                        </div>
                        <div class="table-body">
                            <table class="info-table" id="myTable">
                                <thead>
                                    <tr class="infotable-search">
                                        <td colspan="2">
                                            <div class="input-container">
                                                <p class="input-parag" style="display: inline-block">البحث : </p>
                                                <input class="form-input" style="display: inline-block; width: 50%;" type="text" id="SearchBox">
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <tr class="infotable-header" style="position: sticky; top: 0; z-index: 3;">
                                        <th>الرقم الوظيفي</th>
                                        <th>اسم الموظف</th>
                                    </tr>
                                </thead>
                                
                                <?php
                                    $query = "SELECT * FROM user ORDER BY id DESC";
                                    $result = mysqli_query($conn, $query);
                                    if($result->num_rows > 0){
                                        while($row = mysqli_fetch_array($result)){
                                ?>
                                <tbody id="table1">
                                    <tr class="infotable-body" style="cursor: pointer;" onclick="location.href='empinfo.php?empid=<?php echo $row['id'];?>';">
                                        <td><?php echo $row['id'];?></td>
                                        <td><?php echo $row['name'];?></td>
                                    </tr>
                                </tbody>
                                <?php }}?>
                            </table>
                        </div>
                        
                        <div class="table-footer">
                            <p></p>
                            <div id="pagination"></div>
                            <div id="pageInfo"></div>
                        </div>
                    </div>
                </div>
                <?php }}?>
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