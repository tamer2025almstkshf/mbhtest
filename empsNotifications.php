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
    <body style="overflow: auto; padding-bottom: 50px;">
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php include_once 'header.php';?>
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <input type="hidden" name="page" value="clients.php">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">تنبيهات الموظفين</font></h3>
                            </div>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"></div>
                                <div class="table-header-icons" style="margin-right: 30px;"></div>
                            </div>
                        </div>
                        <div class="table-body">
                            <?php
                                $today = date("Y-m-d");
                                
                                $stmt = $conn->prepare("SELECT id, name, dob, residence_exp, representative_exp, dxblaw_exp, shjlaw_exp, ajmlaw_exp, abdlaw_exp, passport_exp, id_exp, cardno_exp, sigorta_exp FROM user");
                                $stmt->execute();
                                $result = $stmt->get_result();
                                
                                $notifications = [];
                                
                                while ($row = $result->fetch_assoc()) {
                                    $name = $row['name'];
                                    $fields = [
                                        'dob' => 'تاريخ الميلاد',
                                        'residence_exp' => 'تاريخ انتهاء الاقامة',
                                        'representative_exp' => 'تاريخ انتهاء قيد المندوب',
                                        'dxblaw_exp' => 'تاريخ انتهاء قيد المحامي / دبي',
                                        'shjlaw_exp' => 'تاريخ انتهاء قيد المحامي / الشارقة',
                                        'ajmlaw_exp' => 'تاريخ انتهاء قيد المحامي / عجمان',
                                        'abdlaw_exp' => 'تاريخ انتهاء قيد المحامي / ابوظبي',
                                        'passport_exp' => 'تاريخ انتهاء جواز السفر',
                                        'id_exp' => 'تاريخ انتهاء الهوية',
                                        'cardno_exp' => 'تاريخ انتهاء بطاقة العمل',
                                        'sigorta_exp' => 'تاريخ انتهاء التأمين الصحي'
                                    ];
                                
                                    foreach ($fields as $field => $label) {
                                        $exp_date = $row[$field];
                                
                                        if ($exp_date) {
                                            $days_left = (strtotime($exp_date) - strtotime($today)) / (60 * 60 * 24);
                                
                                            if ($days_left <= 10 && $days_left >= -10) {
                                                $notifications[] = [
                                                    'name' => $name,
                                                    'needs_renewal' => $label,
                                                    'exp_date' => $exp_date,
                                                    'days_left' => $days_left
                                                ];
                                            }
                                        }
                                    }
                                }
                            ?>
                            <table class="info-table" id="myTable" style="background-color: #99999940">
                                <thead>
                                    <tr class="infotable-search">
                                        <td colspan="19">
                                            <div class="input-container">
                                                <p class="input-parag" style="display: inline-block">البحث : </p>
                                                <input class="form-input" style="display: inline-block; width: 50%;" type="text" id="SearchBox">
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <tr class="infotable-header">
                                        <th>اسم الموظف</th>
                                        <th>المستند</th>
                                        <th>تاريخ الانتهاء</th>
                                        <th>عدد الايام المتبقي</th>
                                    </tr>
                                </thead>
                                
                                <tbody id="table1">
                                    <?php foreach ($notifications as $item){ ?>
                                    <tr class="infotable-body">
                                        <td><?= safe_output($item['name']) ?></td>
                                        <td><?= safe_output($item['needs_renewal']) ?></td>
                                        <td><?= safe_output($item['exp_date']) ?></td>
                                        <td>
                                            <?php
                                                if($item['days_left'] < 0) {
                                                    echo "انتهى منذ " . abs($item['days_left']) . " ايام";
                                                } elseif($item['days_left'] == 0) {
                                                    echo "0 يوم";
                                                } elseif($item['days_left'] == 1){
                                                    echo "يوم واحد";
                                                }  elseif($item['days_left'] == 2){
                                                    echo "يومان";
                                                } else {
                                                    echo $item['days_left'] . " ايام";
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="table-footer">
                            <p></p>
                            <div id="pagination"></div>
                            <div id="pageInfo"></div>
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