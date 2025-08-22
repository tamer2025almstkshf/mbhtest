<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
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
        <style>
            .add-btn {
                background: none;
                border: none;
                background-color: #fff;
                border: 1px solid #67676740;
                color: #43a3ff;
                border-radius: 50px;
                padding: 10px;
                text-align: center;
                align-content: center;
                font-size: 18px;
                cursor: pointer;
                display: grid;
                grid-template-columns: 1fr auto;
            }
            .add-user-img {
                padding-right: 10px;
            }
            .add-user-parag {
                padding-left: 10px;
                border-left: 1px solid #67676740;
            }
            .days-table {
                background-color: #fff;
                border-radius: 8px;
            }
            .days-header {
                background-color: #125483;
                border-radius: 4px 4px 0 0;
                display: grid;
                grid-template-columns: 1fr auto;
                color: #fff;
                padding: 10px;
                font-size: 18px;
            }
            tr:nth-child(even){
                background-color: #67676725;
            }
        </style>
    </head>
    <body style="overflow: auto; padding-bottom: 50px;">
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['userattendance_rperm'] == 1){
                ?>
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">سجل الحضور</font></h3>
                            </div>
                        </div>
                        <div class="table-body">
                            <table style="background-color: #fff; width: 100%;" id="myTable">
                                <thead>
                                    <?php if($row_permcheck['loggingtimes_eperm'] == 1){?>
                                    <tr>
                                        <td style="text-align: right; margin-right: 40px" colspan="2">
                                            <button class="add-btn" type="button" onclick="times()">
                                                <p class="add-user-parag">تعديل اوقات الدوام</p>
                                                <img class="add-user-img" src="img/add-button.png" width="25px" height="25px" loading="lazy">
                                            </button>
                                            <script>
                                                function times(){
                                                    const times = document.getElementById("times-btn");
                                                    
                                                    if (times.style.display === "none" || times.style.display === "") {
                                                        times.style.display = "block";
                                                    } else {
                                                        times.style.display = "none";
                                                    }
                                                }
                                            </script>
                                            <div id="times-btn" class="modal-overlay">
                                                <div class="modal-content" style="margin: auto; align-content: center">
                                                    <div class="days-table">
                                                        <div class="days-header">
                                                            <h4 class="addc-header-parag" style="margin: auto">أوقات الدوام</h4>
                                                            <div class="close-button-container">
                                                                <p class="close-button" onclick="times()">&times;</p>
                                                            </div>
                                                        </div>
                                                        <div class="days-body" style="padding: 10px;">
                                                            <table style="background-color: #fff; width: 100%;">
                                                                <thead>
                                                                    <tr style="z-index: 3; position: sticky; top: 5px; background-color: #125386; border-radius: 0 5px 5px 0; color: #fff;">
                                                                        <th style="border-radius: 0 10px 10px 0;">
                                                                            <p style="padding: 10px;">اليوم</p>
                                                                        </th>
                                                                        <th style="text-align: center;">وقت الدخول</th>
                                                                        <th style="text-align: center; border-radius: 10px 0 0 10px;">وقت الخروج</th>
                                                                    </tr>
                                                                </thead>
                                                                
                                                                <tbody>
                                                                    <form action="update_logging_times.php" method="post" enctype="multipart/form-data">
                                                                        <input type="hidden" name="userid" value="<?php echo safe_output($_GET['empid']);?>">
                                                                        <tr style="text-align: center;">
                                                                            <td style="text-align: center;">
                                                                                <p style="padding: 10px;">الأحد</p>
                                                                            </td>
                                                                            <?php
                                                                                $day = "sunday";
                                                                                $stmtlogging = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
                                                                                $stmtlogging->bind_param("s", $day);
                                                                                $stmtlogging->execute();
                                                                                $resultlogging = $stmtlogging->get_result();
                                                                                $rowlogging = $resultlogging->fetch_assoc();
                                                                                $stmtlogging->close();
                                                                                
                                                                                list($sunday_inHH, $sunday_inMM) = explode(":", $rowlogging['login_time']);
                                                                                list($sunday_outHH, $sunday_outMM) = explode(":", $rowlogging['logout_time']);
                                                                            ?>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="sunday_loginMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($sunday_inMM); ?>"> : 
                                                                                <input type="number" name="sunday_loginHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($sunday_inHH); ?>">
                                                                            </td>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="sunday_logoutMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($sunday_outMM); ?>"> : 
                                                                                <input type="number" name="sunday_logoutHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($sunday_outHH); ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr style="text-align: center;">
                                                                            <td style="text-align: center;">
                                                                                <p style="padding: 10px;">الإثنين</p>
                                                                            </td>
                                                                            <?php
                                                                                $day = "monday";
                                                                                $stmtlogging = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
                                                                                $stmtlogging->bind_param("s", $day);
                                                                                $stmtlogging->execute();
                                                                                $resultlogging = $stmtlogging->get_result();
                                                                                $rowlogging = $resultlogging->fetch_assoc();
                                                                                $stmtlogging->close();
                                                                                
                                                                                list($monday_inHH, $monday_inMM) = explode(":", $rowlogging['login_time']);
                                                                                list($monday_outHH, $monday_outMM) = explode(":", $rowlogging['logout_time']);
                                                                            ?>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="monday_loginMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($monday_inMM); ?>"> : 
                                                                                <input type="number" name="monday_loginHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($monday_inHH); ?>">
                                                                            </td>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="monday_logoutMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($monday_outMM); ?>"> : 
                                                                                <input type="number" name="monday_logoutHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($monday_outHH); ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr style="text-align: center;">
                                                                            <td style="text-align: center;">
                                                                                <p style="padding: 10px;">الثلاثاء</p>
                                                                            </td>
                                                                            <?php
                                                                                $day = "tuesday";
                                                                                $stmtlogging = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
                                                                                $stmtlogging->bind_param("s", $day);
                                                                                $stmtlogging->execute();
                                                                                $resultlogging = $stmtlogging->get_result();
                                                                                $rowlogging = $resultlogging->fetch_assoc();
                                                                                $stmtlogging->close();
                                                                                
                                                                                list($tuesday_inHH, $tuesday_inMM) = explode(":", $rowlogging['login_time']);
                                                                                list($tuesday_outHH, $tuesday_outMM) = explode(":", $rowlogging['logout_time']);
                                                                            ?>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="tuesday_loginMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($tuesday_inMM); ?>"> : 
                                                                                <input type="number" name="tuesday_loginHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($tuesday_inHH); ?>">
                                                                            </td>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="tuesday_logoutMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($tuesday_outMM); ?>"> : 
                                                                                <input type="number" name="tuesday_logoutHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($tuesday_outHH); ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr style="text-align: center;">
                                                                            <td style="text-align: center;">
                                                                                <p style="padding: 10px;">الأربعاء</p>
                                                                            </td>
                                                                            <?php
                                                                                $day = "wednesday";
                                                                                $stmtlogging = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
                                                                                $stmtlogging->bind_param("s", $day);
                                                                                $stmtlogging->execute();
                                                                                $resultlogging = $stmtlogging->get_result();
                                                                                $rowlogging = $resultlogging->fetch_assoc();
                                                                                $stmtlogging->close();
                                                                                
                                                                                list($wednesday_inHH, $wednesday_inMM) = explode(":", $rowlogging['login_time']);
                                                                                list($wednesday_outHH, $wednesday_outMM) = explode(":", $rowlogging['logout_time']);
                                                                            ?>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="wednesday_loginMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($wednesday_inMM); ?>"> : 
                                                                                <input type="number" name="wednesday_loginHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($wednesday_inHH); ?>">
                                                                            </td>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="wednesday_logoutMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($wednesday_outMM); ?>"> : 
                                                                                <input type="number" name="wednesday_logoutHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($wednesday_outHH); ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr style="text-align: center;">
                                                                            <td style="text-align: center;">
                                                                                <p style="padding: 10px;">الخميس</p>
                                                                            </td>
                                                                            <?php
                                                                                $day = "thursday";
                                                                                $stmtlogging = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
                                                                                $stmtlogging->bind_param("s", $day);
                                                                                $stmtlogging->execute();
                                                                                $resultlogging = $stmtlogging->get_result();
                                                                                $rowlogging = $resultlogging->fetch_assoc();
                                                                                $stmtlogging->close();
                                                                                
                                                                                list($thursday_inHH, $thursday_inMM) = explode(":", $rowlogging['login_time']);
                                                                                list($thursday_outHH, $thursday_outMM) = explode(":", $rowlogging['logout_time']);
                                                                            ?>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="thursday_loginMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($thursday_inMM); ?>"> : 
                                                                                <input type="number" name="thursday_loginHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($thursday_inHH); ?>">
                                                                            </td>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="thursday_logoutMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($thursday_outMM); ?>"> : 
                                                                                <input type="number" name="thursday_logoutHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($thursday_outHH); ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr style="text-align: center;">
                                                                            <td style="text-align: center;">
                                                                                <p style="padding: 10px;">الجمعة</p>
                                                                            </td>
                                                                            <?php
                                                                                $day = "friday";
                                                                                $stmtlogging = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
                                                                                $stmtlogging->bind_param("s", $day);
                                                                                $stmtlogging->execute();
                                                                                $resultlogging = $stmtlogging->get_result();
                                                                                $rowlogging = $resultlogging->fetch_assoc();
                                                                                $stmtlogging->close();
                                                                                
                                                                                list($friday_inHH, $friday_inMM) = explode(":", $rowlogging['login_time']);
                                                                                list($friday_outHH, $friday_outMM) = explode(":", $rowlogging['logout_time']);
                                                                            ?>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="friday_loginMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($friday_inMM); ?>"> : 
                                                                                <input type="number" name="friday_loginHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($friday_inHH); ?>">
                                                                            </td>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="friday_logoutMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($friday_outMM); ?>"> : 
                                                                                <input type="number" name="friday_logoutHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($friday_outHH); ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr style="text-align: center;">
                                                                            <td style="text-align: center;">
                                                                                <p style="padding: 10px;">السبت</p>
                                                                            </td>
                                                                            <?php
                                                                                $day = "saturday";
                                                                                $stmtlogging = $conn->prepare("SELECT * FROM user_logging WHERE day=?");
                                                                                $stmtlogging->bind_param("s", $day);
                                                                                $stmtlogging->execute();
                                                                                $resultlogging = $stmtlogging->get_result();
                                                                                $rowlogging = $resultlogging->fetch_assoc();
                                                                                $stmtlogging->close();
                                                                                
                                                                                list($saturday_inHH, $saturday_inMM) = explode(":", $rowlogging['login_time']);
                                                                                list($saturday_outHH, $saturday_outMM) = explode(":", $rowlogging['logout_time']);
                                                                            ?>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="saturday_loginMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($saturday_inMM); ?>"> : 
                                                                                <input type="number" name="saturday_loginHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($saturday_inHH); ?>">
                                                                            </td>
                                                                            <td style="text-align: center;">
                                                                                <input type="number" name="saturday_logoutMM" class="form-input" style="width: 50px" placeholder="MM" max="59" min="0" value="<?php echo safe_output($saturday_outMM); ?>"> : 
                                                                                <input type="number" name="saturday_logoutHH" class="form-input" style="width: 50px" placeholder="HH" max="23" min="0" value="<?php echo safe_output($saturday_outHH); ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <tr style="text-align: center;">
                                                                            <td style="text-align: center; position: sticky; bottom: 0; background-color: #12548340;" colspan="3">
                                                                                <input type="submit" value="حفظ البيانات" class="green-button">
                                                                            </td>
                                                                        </tr>
                                                                    </form>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <form action="attendance_filter.php" method="post" enctype="multipart/form-data">
                                            <td colspan="2">
                                                الموظف <font color="#ff0000">*</font>
                                                <select class="form-input" name="user_id" required>
                                                    <option value=""></option>
                                                    <?php
                                                        $stmtusers = $conn->prepare("SELECT id, name FROM user");
                                                        $stmtusers->execute();
                                                        $resultusers = $stmtusers->get_result();
                                                        if($resultusers->num_rows > 0){
                                                            while($rowusers = $resultusers->fetch_assoc()){
                                                    ?>
                                                    <option value="<?php echo $rowusers['id'];?>" <?php if(isset($_GET['id']) && intval($_GET['id']) == intval($rowusers['id'])){ echo 'selected'; }?>><?php echo $rowusers['name'];?></option>
                                                    <?php
                                                            }
                                                        }
                                                        $stmtusers->close();
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                الشهر
                                                <select class="form-input" name="month">
                                                    <?php for($i = 12 ; $i >= 1 ; $i--){?>
                                                    <option value="<?php echo $i;?>" <?php $currentMonth = date("m"); if(!isset($_GET['month']) && intval($currentMonth) == $i){ echo 'selected'; } else if(isset($_GET['month']) && intval($_GET['month']) == $i){ echo 'selected'; }?>><?php echo $i;?></option>
                                                    <?php }?>
                                                </select>
                                            </td>
                                            <td>
                                                السنة
                                                <select class="form-input" name="year">
                                                    <?php
                                                        $currentYear = date("Y");
                                                        for($i = intval($currentYear) ; $i >= 2010 ; $i--){
                                                    ?>
                                                    <option value="<?php echo $i;?>" <?php if(!isset($_GET['year']) && intval($currentYear) == $i){ echo 'selected'; } else if(isset($_GET['year']) && intval($_GET['year']) == $i){ echo 'selected'; }?>><?php echo $i;?></option>
                                                    <?php }?>
                                                </select>
                                            </td>
                                            <td>
                                                <input class="green-button" type="submit" value="البحث">
                                                <?php if(isset($_GET['id']) && $_GET['id'] !== ''){?>
                                                <br><input class="green-button" style="margin-top: 5px" type="button" value="تصفير الفلتر" onclick="location.href='attendance.php';">
                                                <?php }?>
                                            </td>
                                        </form>
                                    </tr>
                                    <?php }?>
                                    <tr style="z-index: 3; position: sticky; top: 0; background-color: #125386; border-radius: 0 5px 5px 0; color: #fff;">
                                        <?php if(isset($_GET['id']) && $_GET['id'] !== ''){?>
                                        <th style="text-align: center; border-radius: 0 10px 10px 0;">اليوم و التاريخ</th>
                                        <?php }?>
                                        <th <?php if(!isset($_GET['id']) || $_GET['id'] === ''){?>style="border-radius: 0 10px 10px 0;"<?php }?>>
                                            <p style="padding: 10px;">اسم الموظف</p>
                                        </th>
                                        <?php if(!isset($_GET['id']) || $_GET['id'] === ''){?>
                                        <th style="text-align: center;">اليوم و التاريخ</th>
                                        <?php }?>
                                        <th style="text-align: center;">تسجيل الدخول</th>
                                        <th style="text-align: center;">التأخير</th>
                                        <th style="text-align: center;">تسجيل الخروج</th>
                                        <th style="text-align: center;">خروج مبكر</th>
                                        <th style="text-align: center; border-radius: 10px 0 0 10px; width: 50px">الاجراءات</th>
                                    </tr>
                                    
                                    <?php if(!isset($_GET['id']) || $_GET['id'] === ''){?>
                                    <tr style="text-align: center;">
                                        <?php if(($_GET['empid'] !== $myid && ($row_permcheck['userattendance_aperm'] == 1 || $row_permcheck['userattendance_eperm'] == 1)) || $owner == 1){?>
                                        <form action="<?php if(isset($_GET['attendid']) && $_GET['attendid'] !== ''){ echo 'attendedit.php'; } else{ echo 'attendadd.php'; }?>" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="page" value="attendance.php">
                                            <?php 
                                                if(isset($_GET['attendid']) && $_GET['attendid'] !== ''){
                                                    $attendid = $_GET['attendid'];
                                                    $stmtr = $conn->prepare("SELECT * FROM user_logs WHERE id=?");
                                                    $stmtr->bind_param("i", $attendid);
                                                    $stmtr->execute();
                                                    $resultr = $stmtr->get_result();
                                                    $rowr = $resultr->fetch_assoc();
                                                    $stmtr->close();
                                            ?>
                                            <input type="hidden" name="id" value="<?php echo safe_output($_GET['attendid']);?>">
                                            <?php }?>
                                            <td style="text-align: center;">
                                                <select class="form-input" name="user_id" style="max-width: fit-content;" required>
                                                    <option value=""></option>
                                                    <?php 
                                                        $stmtemps = $conn->prepare("SELECT id, name FROM user");
                                                        $stmtemps->execute();
                                                        $resultemps = $stmtemps->get_result();
                                                        if($resultemps->num_rows > 0){
                                                            while($rowemps = $resultemps->fetch_assoc()){
                                                    ?>
                                                    <option value="<?php echo safe_output($rowemps['id'])?>" <?php if(isset($rowr['user_id']) && $rowr['user_id'] === $rowemps['id']){ echo 'selected'; }?>><?php echo safe_output($rowemps['name']);?></option>
                                                    <?php
                                                            }
                                                        }
                                                        $stmtemps->close();
                                                    ?>
                                                </select>
                                            </td>
                                            <td style="text-align: center;">
                                                <input type="date" style="max-width: fit-content;" class="form-input" value="<?php if(isset($rowr['login_date']) && $rowr['login_date'] !== ''){ echo safe_output($rowr['login_date']); } else{ echo date('Y-m-d'); }?>" name="login_date">
                                            </td>
                                            <td style="text-align: center; min-width: 170px">
                                                <?php
                                                    if(isset($rowr['login_time']) && $rowr['login_time'] !== ''){
                                                        $login_time = $rowr['login_time'];
                                                        list($ltHH, $ltMM) = explode(":", $login_time);
                                                    }
                                                ?>
                                                <input type="number" style="width: 50px;" class="form-input" name="ltMM" placeholder="MM" max="59" min="0" value="<?php if(isset($rowr['login_time']) && $rowr['login_time'] !== ''){ echo safe_output($ltMM); }?>"> :
                                                <input type="number" style="width: 50px;" class="form-input" name="ltHH" placeholder="HH" max="23" min="0" value="<?php if(isset($rowr['login_time']) && $rowr['login_time'] !== ''){ echo safe_output($ltHH); }?>">
                                            </td>
                                            <td style="text-align: center;"></td>
                                            <td style="text-align: center; min-width: 170px">
                                                <?php
                                                    if(isset($rowr['logout_time']) && $rowr['logout_time'] !== ''){
                                                        $logout_time = $rowr['logout_time'];
                                                        list($louttHH, $louttMM) = explode(":", $logout_time);
                                                    }
                                                ?>
                                                <input type="number" style="width: 50px;" class="form-input" name="louttMM" placeholder="MM" max="59" min="0" value="<?php if(isset($rowr['logout_time']) && $rowr['logout_time'] !== ''){ echo safe_output($louttMM); }?>"> :
                                                <input type="number" style="width: 50px;" class="form-input" name="louttHH" placeholder="HH" max="23" min="0" value="<?php if(isset($rowr['logout_time']) && $rowr['logout_time'] !== ''){ echo safe_output($louttHH); }?>">
                                            </td>
                                            <td style="text-align: center;"></td>
                                            <td style="text-align: center;">
                                                <input type="submit" value="حفظ البيانات" class="green-button">
                                            </td>
                                        </form>
                                        <?php }?>
                                    </tr>
                                    <?php }?>
                                </thead>
                                <tbody id="table1">
                                    <?php
                                        if(!isset($_GET['id']) || $_GET['id'] === ''){
                                            $stmtulogs = $conn->prepare("SELECT * FROM user_logs");
                                            $stmtulogs->execute();
                                            $resultulogs = $stmtulogs->get_result();
                                            if($resultulogs->num_rows > 0){
                                                while($rowulogs = $resultulogs->fetch_assoc()){
                                    ?>
                                    <tr style="text-align: center;">
                                        <td style="width: fit-content;">
                                            <?php
                                                $empid = $rowulogs['user_id'];
                                                $stmtemp = $conn->prepare("SELECT name FROM user WHERE id=?");
                                                $stmtemp->bind_param("i", $empid);
                                                $stmtemp->execute();
                                                $resultemp = $stmtemp->get_result();
                                                $rowemp = $resultemp->fetch_assoc();
                                                $stmtemp->close();
                                                echo safe_output($rowemp['name']);
                                            ?>
                                        </td>
                                        <td style="width: fit-content;">
                                            <?php 
                                                $login_day = $rowulogs['login_day'];
                                                $days_ar = [
                                                    'Sunday'    => 'الأحد',
                                                    'Monday'    => 'الإثنين',
                                                    'Tuesday'   => 'الثلاثاء',
                                                    'Wednesday' => 'الأربعاء',
                                                    'Thursday'  => 'الخميس',
                                                    'Friday'    => 'الجمعة',
                                                    'Saturday'  => 'السبت'
                                                ];
                                                $day_en = ucfirst(strtolower($rowulogs['login_day']));
                                                $login_day = $days_ar[$day_en] ?? $day_en;
                                                echo safe_output($login_day).', '.safe_output($rowulogs['login_date']);
                                            ?>
                                        </td>
                                        <td><?php echo safe_output($rowulogs['login_time']);?></td>
                                        <td>
                                            <?php
                                                $late_login = $rowulogs['late_login'];
                                                if (strpos($late_login, 'اجازة') !== false) {
                                                    echo '<font color="red">'.$late_login.'</font>';
                                                } else{
                                                    list($lateHH, $lateMM) = explode(":", $late_login);
                                                    echo "<font color='#ff0000'>".safe_output($lateHH)."</font> ساعات و <font color='#ff0000'>".safe_output($lateMM)."</font> دقائق";
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo safe_output($rowulogs['logout_time']);?></td>
                                        <td>
                                            <?php
                                                $early_logout = $rowulogs['early_logout'];
                                                if (strpos($early_logout, 'اجازة') !== false) {
                                                    echo '<font color="red">'.$early_logout.'</font>';
                                                } else{
                                                    list($earlyHH, $earlyMM) = explode(":", $early_logout);
                                                    echo "<font color='#ff0000'>".safe_output($earlyHH)."</font> ساعات و <font color='#ff0000'>".safe_output($earlyMM)."</font> دقائق";
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if($row_permcheck['userattendance_eperm'] == 1){?>
                                            <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='attendance.php?attendid=<?php echo safe_output($rowulogs['id']);?>';" loading="lazy">
                                            <?php } if($row_permcheck['userattendance_dperm'] == 1){?>
                                            <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='delete_attend.php?page=attendance.php&user_id=<?php echo safe_output($_GET['empid']);?>&attendid=<?php echo safe_output($rowulogs['id']);?>';" loading="lazy">
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php
                                                }
                                            }
                                            $stmtulogs->close();
                                        } else{ 
                                    ?>
                                    <form method="post" action="empattendance.php" name="useratts">
                                        <input type="hidden" name="queryString" value="<?php echo $_SERVER['QUERY_STRING'];?>">
                                        <input type="hidden" name="user_id" value="<?php echo safe_output($_GET['id']);?>">
                                        <?php    
                                            $firstzero = '';
                                            if(intval($_GET['month']) < 10){
                                                $firstzero = '0';
                                            }
                                            $firstday = $_GET['year'].'-'.$firstzero.$_GET['month'].'-01';
                                            $today = new DateTime($firstday);
                                            
                                            $future_date = clone $today;
                                            $future_date->modify('last day of '.$_GET['year'].'-'.$_GET['month']);
                                            
                                            $end_date = clone $future_date;
                                            $end_date->modify('+1 day');
                                            
                                            $interval = new DateInterval('P1D');
                                            $daterange = new DatePeriod($today, $interval, $end_date);
                                            
                                            foreach($daterange as $date){
                                                $date = $date->format("Y-m-d");
                                                $stmtulogs = $conn->prepare("SELECT * FROM user_logs WHERE login_date=? AND logout_date=? AND user_id=? ORDER BY id DESC");
                                                $stmtulogs->bind_param("ssi", $date, $date, $_GET['id']);
                                                $stmtulogs->execute();
                                                $resultulogs = $stmtulogs->get_result();
                                                $rowulogs = $resultulogs->fetch_assoc();
                                                $stmtulogs->close();
                                        ?>
                                        <tr style="text-align: center;">
                                            <td style="width: fit-content;">
                                                <?php 
                                                    $day = date("l", strtotime($date));
                                                    $days_ar = [
                                                        'Sunday'    => 'الأحد',
                                                        'Monday'    => 'الإثنين',
                                                        'Tuesday'   => 'الثلاثاء',
                                                        'Wednesday' => 'الأربعاء',
                                                        'Thursday'  => 'الخميس',
                                                        'Friday'    => 'الجمعة',
                                                        'Saturday'  => 'السبت'
                                                    ];
                                                    $day_en = ucfirst(strtolower($day));
                                                    $day = $days_ar[$day_en] ?? $day_en;
                                                    echo safe_output($day).', '.safe_output($date);
                                                ?>
                                            </td>
                                            <td style="width: fit-content;">
                                                <?php
                                                    $empid = $_GET['id'];
                                                    $stmtemp = $conn->prepare("SELECT name FROM user WHERE id=?");
                                                    $stmtemp->bind_param("i", $empid);
                                                    $stmtemp->execute();
                                                    $resultemp = $stmtemp->get_result();
                                                    $rowemp = $resultemp->fetch_assoc();
                                                    $stmtemp->close();
                                                    echo safe_output($rowemp['name']);
                                                ?>
                                            </td>
                                            <td><input type="text" name="login_time[<?php echo $date; ?>]" class="form-input" style="width: fit-content;" value="<?php if(isset($rowulogs['login_time'])){ echo safe_output($rowulogs['login_time']); }?>" placeholder="استخدم HH:MM"></td>
                                            <td>
                                                <?php
                                                    if(isset($rowulogs['late_login']) && $rowulogs['late_login'] !== ''){
                                                        $late_login = $rowulogs['late_login'];
                                                        if (strpos($late_login, 'اجازة') !== false) {
                                                            echo '<font color="red">'.$late_login.'</font>';
                                                        } else{
                                                            list($lateHH, $lateMM) = explode(":", $late_login);
                                                            echo "<font color='#ff0000'>".safe_output($lateHH)."</font> ساعات و <font color='#ff0000'>".safe_output($lateMM)."</font> دقائق";
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <td><input type="text" name="logout_time[<?php echo $date; ?>]" style="width: fit-content;" class="form-input" value="<?php if(isset($rowulogs['logout_time'])){ echo safe_output($rowulogs['logout_time']); }?>" placeholder="استخدم HH:MM"></td>
                                            <td>
                                                <?php
                                                    if(isset($rowulogs['early_logout']) && $rowulogs['early_logout'] !== ''){
                                                        $early_logout = $rowulogs['early_logout'];
                                                        if (strpos($early_logout, 'اجازة') !== false) {
                                                            echo '<font color="red">'.$early_logout.'</font>';
                                                        } else{
                                                            list($earlyHH, $earlyMM) = explode(":", $early_logout);
                                                            echo "<font color='#ff0000'>".safe_output($earlyHH)."</font> ساعات و <font color='#ff0000'>".safe_output($earlyMM)."</font> دقائق";
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if($row_permcheck['userattendance_dperm'] == 1){?>
                                                <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='delete_userattend.php?id=<?php echo safe_output($_GET['id']);?>&month=<?php echo safe_output($_GET['month']);?>&year=<?php echo safe_output($_GET['year']);?>&attendid=<?php echo safe_output($rowulogs['id']);?>';" loading="lazy">
                                                <?php }?>
                                            </td>
                                        </tr>
                                        <?php 
                                                }
                                            }
                                        ?>
                                    </form>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="table-footer">
                            <?php 
                                if(isset($_GET['id']) && $_GET['id'] !== ''){
                                    if($row_permcheck['userattendance_aperm'] == 1){
                            ?>
                            <input type="submit" value="حفظ البيانات" class="green-button" style="margin-top: 10px" onclick="useratts.submit()">
                            <?php }} else{?>
                            <p></p>
                            <?php }?>
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
        <script src="js/tablePages.js"></script>
        <script src="js/checkAll.js"></script>
        <script src="js/dropdown.js"></script>
        <script>
            function toggleMore() {
                const div = document.getElementById("toggle-more");
                if (div.style.display === "none" || div.style.display === "") {
                    div.style.display = "block";
                } else {
                    div.style.display = "none";
                }
            }
        </script>
    </body>
</html>