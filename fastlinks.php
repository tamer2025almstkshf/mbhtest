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
                            <h2 class="advinputs-h2">الروابط السريعة</h2>
                            <div class="links-container">
                                <div class="links3">
                                    <?php 
                                        if((!isset($_GET['exp_hearings']) || $_GET['exp_hearings'] !== '1') && (!isset($_GET['court_hearings']) || $_GET['court_hearings'] !== '1') &&
                                        (!isset($_GET['legislation']) || $_GET['legislation'] !== '1') && (!isset($_GET['police']) || $_GET['police'] !== '1')){
                                    ?>
                                    <div class="link-align">
                                        <div class="link link2" onclick="MM_openBrWindow('fastlinks.php?exp_hearings=1','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/governance.png');"></div>
                                            <p class="link-topic"><font id="courts-translate">جلسات خبرة</font></p>
                                        </div>
                                    </div>
                                    <div class="link-align">
                                        <div class="link link2" onclick="MM_openBrWindow('fastlinks.php?court_hearings=1','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/jusry.png');"></div>
                                        <p class="link-topic"><font id="accounting2-translate">جلسات محكمة</font></p>
                                        </div>
                                    </div>
                                    <div class="link-align">
                                        <div class="link link2" onclick="MM_openBrWindow('fastlinks.php?legislation=1','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/commercial.png');"></div>
                                        <p class="link-topic"><font id="humanresources2-translate">تشريعات</font></p>
                                        </div>
                                    </div>
                                    <div class="link-align">
                                        <div class="link link2" onclick="MM_openBrWindow('https://chatgpt.com/','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/chatgpt.png');"></div>
                                        <p class="link-topic"><font id="clients2-translate">الذكاء الاصطناعي (ChatGPT)</font></p>
                                        </div>
                                    </div>
                                    <div class="link-align">
                                        <div class="link link2" onclick="MM_openBrWindow('fastlinks.php?police=1','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                                            <div class="images-style" style="background-image: url('img/policeman.png');"></div>
                                        <p class="link-topic"><font id="tasks3-translate">الشرطة</font></p>
                                        </div>
                                    </div>
                                    <?php 
                                        } else{
                                            if(isset($_GET['exp_hearings']) && $_GET['exp_hearings'] === '1'){
                                                $query = "SELECT * FROM fast_links WHERE link_for='جلسات الخبرة'";
                                            } else if(isset($_GET['court_hearings']) && $_GET['court_hearings'] === '1'){
                                                $query = "SELECT * FROM fast_links WHERE link_for='جلسات المحكمة'";
                                            } else if(isset($_GET['legislation']) && $_GET['legislation'] === '1'){
                                                $query = "SELECT * FROM fast_links WHERE link_for='التشريعات'";
                                            } else if(isset($_GET['police']) && $_GET['police'] === '1'){
                                                $query = "SELECT * FROM fast_links WHERE link_for='الشرطة'";
                                            }
                                            $result = mysqli_query($conn, $query);
                                        }
                                    ?>
                                </div>
                            </div>
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