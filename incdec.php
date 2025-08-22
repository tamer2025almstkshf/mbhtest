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
    <body style="overflow: auto">
        <?php
            $myid = $_SESSION['id'];
            $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
            $result_permcheck = mysqli_query($conn, $query_permcheck);
            $row_permcheck = mysqli_fetch_array($result_permcheck);
            
            if(isset($_GET['id']) && $_GET['id'] !== $_SESSION['id'] && ($row_permcheck['emp_rperm'] === '1' || $row_permcheck['emp_aperm'] === '1')){
                $id = $_GET['id'];
                $querymain = "SELECT * FROM user WHERE id='$id'";
            } else{
                $id = $_SESSION['id'];
                $querymain = "SELECT * FROM user WHERE id='$id'";
            }
            $resultmain = mysqli_query($conn, $querymain);
            $rowmain = mysqli_fetch_array($resultmain);
            
            include_once 'AES256.php';
        ?>
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php include_once 'header.php';?>
                
                <div class="web-page">
                    <?php if(($row_permcheck['emp_rperm'] === '1' && isset($_GET['id']) && $_GET['id'] !== '') || ($row_permcheck['emp_rperm'] !== '1' && (!isset($_GET['id']) || $_GET['id'] === $_SESSION['id']))){?>
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate" style="color: #125386;"></font></h3>
                            </div>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"></div>
                                <?php if(isset($_GET['id']) && $_GET['id'] !== $_SESSION['id'] && $row_permcheck['emp_aperm'] === '1'){?>
                                <div class="table-header-icons" style="background-image: url('img/arrow.png'); margin-right: 30px;" onclick="addclient()"></div>
                                <div id="addclient-btn" class="modal-overlay">
                                    <div class="modal-content">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto">الخصومات</h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="addclient()">&times;</p>
                                            </div>
                                        </div>
                                        <form action="decadd.php" method="post" enctype="multipart/form-data" >
                                            <input type="hidden" name="user_id" value="<?php if($_GET['id'] !== $_SESSION['id'] && $row_permcheck['emp_aperm'] === '1'){ echo $_GET['id']; } else{ echo $_SESSION['id']; }?>">
                                            <div class="addc-body">
                                                <div class="addc-body-form">
                                                    <div class="input-container">
                                                        <p class="input-parag">التاريخ</p>
                                                        <input type="date" class="form-input" name="date" value="<?php echo date("Y-m-d");?>" required>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">المبلغ المخصوم<font style="color: red;">*</font></p>
                                                        <input type="number" class="form-input" name="dec_amount" required>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">سبب الخصم</p>
                                                        <textarea class="form-input" rows="2" name="reason"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="addc-footer">
                                                <button style="cursor: pointer" type="submit" class="form-btn submit-btn">حفظ</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                        </div>
                        <div class="table-body">
                            <input type="hidden" name="page" value="clients.php">
                            <table class="info-table" id="myTable">
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
                                        <th>الموظف</th>
                                        <th>التاريخ</th>
                                        <th>المبلغ المخصوم</th>
                                        <th>سبب الخصم</th>
                                    </tr>
                                </thead>
                                
                                <?php
                                    $query = "SELECT * FROM incdec WHERE user_id='$id' ORDER BY id DESC";
                                    $result = mysqli_query($conn, $query);
                                    if($result->num_rows > 0){
                                        while($row = mysqli_fetch_array($result)){
                                ?>
                                <tbody id="table1">
                                    <tr class="infotable-body">
                                        <td>
                                            <?php
                                                $decid = $row['user_id'];
                                                $queryu = "SELECT * FROM user WHERE id='$decid'";
                                                $resultu = mysqli_query($conn, $queryu);
                                                $rowu = mysqli_fetch_array($resultu);
                                                echo $rowu['name'];
                                            ?>
                                        </td>
                                        <td><?php echo $row['date'];?></td>
                                        <td><?php echo $row['amount'];?></td>
                                        <td><?php echo $row['reason'];?></td>
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
                    <?php }?>
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
        <script src="js/sweetAlerts2.js"></script>
        <script src="js/tablePages.js"></script>
        <script src="js/checkAll.js"></script>
        <script src="js/dropdown.js"></script>
    </body>
</html>