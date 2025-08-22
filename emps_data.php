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
                <?php include_once 'header.php';?>
                <div class="web-page"><br><br>
                    <div style="height: 80vh; align-content: center; text-align: -webkit-center;">
                        <div class="advinputs-container" style="max-height: 80vh; overflow-y: auto; width: fit-content">
                            <?php if($row_permcheck['emp_perms_add'] !== '1' && $row_permcheck['emp_perms_edit'] !== '1' && $row_permcheck['emp_perms_delete'] !== '1'){?>
                            <h2 class="advinputs-h2">بيانات الموظفين</h2>
                            <div class="links-container">
                                <div class="links3">
                                    <?php if($row_permcheck['accfinance_rperm'] === '1'){?>
                                    <div class="link-align">
                                        <div class="link link2" onclick="MM_openBrWindow('Finance.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/finance.png');"></div>
                                            <p class="link-topic"><font id="courts-translate">قسم المالية</font></p>
                                        </div>
                                    </div>
                                    <?php 
                                        }
                                        if($row_permcheck['accsecterms_rperm'] === '1'){
                                    ?>
                                    <div class="link-align">
                                        <div class="link link2" onclick="MM_openBrWindow('SubCategory.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/subcategory.png');"></div>
                                        <p class="link-topic"><font id="accounting2-translate">البنود الفرعية</font></p>
                                        </div>
                                    </div>
                                    <?php 
                                        }
                                        if($row_permcheck['accbankaccs_rperm'] === '1'){
                                    ?>
                                    <div class="link-align">
                                        <div class="link link2" onclick="MM_openBrWindow('BankAccs.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/bankaccs.png');"></div>
                                        <p class="link-topic"><font id="humanresources2-translate">حسابات البنوك</font></p>
                                        </div>
                                    </div>
                                    <?php }?>
                                </div>
                                <div class="links3">
                                    <?php if($row_permcheck['acccasecost_rperm'] === '1'){?>
                                    <div class="link-align">
                                        <div class="link link3" onclick="MM_openBrWindow('CasesFees.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/fees.png');"></div>
                                        <p class="link-topic"><font id="clients2-translate">اتعاب القضايا</font></p>
                                        </div>
                                    </div>
                                    <?php 
                                        }
                                        if($row_permcheck['accrevenues_rperm'] === '1'){
                                    ?>
                                    <div class="link-align">
                                        <div class="link link3" onclick="MM_openBrWindow('income.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/income.png');"></div>
                                        <p class="link-topic"><font id="tasks3-translate">الايرادات</font></p>
                                        </div>
                                    </div>
                                    <?php 
                                        }
                                        if($row_permcheck['accexpenses_rperm'] === '1'){
                                    ?>
                                    <div class="link-align">
                                        <div class="link link3" onclick="MM_openBrWindow('expenses.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/expenses.png');"></div>
                                        <p class="link-topic"><font id="clients2-translate">المصروفات</font></p>
                                        </div>
                                    </div>
                                    <?php }?>
                                </div>
                            </div>
                            <?php } else{?>
                            <h2 class="advinputs-h2">الموارد البشرية</h2>
                            <div class="links-container">
                                <div class="links3">
                                    <?php if($row_permcheck['emp_perms_add'] === '1' || $row_permcheck['emp_perms_edit'] === '1' || $row_permcheck['emp_perms_delete'] === '1'){?>
                                    <div class="link-align">
                                        <div class="link link2" onclick="MM_openBrWindow('mbhEmps.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/employees.png');"></div>
                                            <p class="link-topic"><font id="courts-translate">الموظفين - الصلاحيات</font></p>
                                        </div>
                                    </div>
                                    
                                    <div class="link-align">
                                        <div class="link link2" onclick="MM_openBrWindow('Office_Vehicles.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/resources.png');"></div>
                                        <p class="link-topic"><font id="accounting2-translate">الموارد و المركبات</font></p>
                                        </div>
                                    </div>
                                    
                                    <div class="link-align">
                                        <div class="link link2" onclick="MM_openBrWindow('Contracts.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/contracts.png');"></div>
                                        <p class="link-topic"><font id="humanresources2-translate">العقود و الرخص</font></p>
                                        </div>
                                    </div>
                                    <?php }?>
                                </div>
                                <div class="links3">
                                    <?php if($row_permcheck['emp_perms_add'] === '1' || $row_permcheck['emp_perms_edit'] === '1' || $row_permcheck['emp_perms_delete'] === '1'){?>
                                    <div class="link-align">
                                        <div class="link link3" onclick="MM_openBrWindow('Lawyers.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/offices.png');"></div>
                                        <p class="link-topic"><font id="clients2-translate">مكاتب المحاميين</font></p>
                                        </div>
                                    </div>
                                    <?php 
                                        }
                                        if($row_permcheck['logs_rperm'] === '1'){
                                    ?>
                                    <div class="link-align">
                                        <div class="link link3" onclick="MM_openBrWindow('Logs.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/logs.png');"></div>
                                        <p class="link-topic"><font id="tasks3-translate">سجلات البرنامج</font></p>
                                        </div>
                                    </div>
                                    <?php 
                                        }
                                        if($row_permcheck['vacf_aperm'] === '1'){
                                    ?>
                                    <div class="link-align">
                                        <div class="link link3" onclick="MM_openBrWindow('vacationReqs.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/vacf.png');"></div>
                                        <p class="link-topic"><font id="clients2-translate">طلبات الاجازات</font></p>
                                        </div>
                                    </div>
                                    <?php }?>
                                </div>
                                <div class="links3">
                                    <?php if($row_permcheck['vacl_aperm'] === '1'){?>
                                    <div class="link-align">
                                        <div class="link link2" onclick="MM_openBrWindow('vacationReqs2.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/vacl.png');"></div>
                                        <p class="link-topic"><font id="clients2-translate">قبول الاجازات</font></p>
                                        </div>
                                    </div>
                                    <?php 
                                        }
                                        if($row_permcheck['logs_rperm'] === '1'){
                                    ?>
                                    <div class="link-align">
                                        <div class="link link2" onclick="MM_openBrWindow('empinfo.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/data.png');"></div>
                                        <p class="link-topic"><font id="tasks3-translate">بيانات الموظفين</font></p>
                                        </div>
                                    </div>
                                    <?php }?>
                                </div>
                            </div>
                            <?php }?>
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
        <script src="js/sweetAlerts2.js"></script>
        <script src="js/tablePages.js"></script>
        <script src="js/checkAll.js"></script>
        <script src="js/dropdown.js"></script>
    </body>
</html>