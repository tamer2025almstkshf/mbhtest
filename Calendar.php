<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    
    $month = $_GET['month'] ?? date('m');
    $year = $_GET['year'] ?? date('Y');
    
    $firstDay = mktime(0, 0, 0, $month, 1, $year);
    $daysInMonth = date('t', $firstDay);
    
    $arabicMonths = [
        'January' => 'يناير',
        'February' => 'فبراير',
        'March' => 'مارس',
        'April' => 'أبريل',
        'May' => 'مايو',
        'June' => 'يونيو',
        'July' => 'يوليو',
        'August' => 'أغسطس',
        'September' => 'سبتمبر',
        'October' => 'أكتوبر',
        'November' => 'نوفمبر',
        'December' => 'ديسمبر',
    ];

    $monthNameEn = date('F', $firstDay);
    $monthName = $arabicMonths[$monthNameEn];
    $startDayOfWeek = date('w', $firstDay);
    
    $events = [];
    $stmt = $conn->prepare("SELECT * FROM hr_events WHERE MONTH(event_date) = ? AND YEAR(event_date) = ?");
    $stmt->bind_param("ss", $month, $year);
    $stmt->execute();
    $result= $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $title = '';
        $time = '';
        if(isset($row['title']) && $row['title'] !== ''){
            $title = $row['title'];
            $title = "$title";
        }
        if(isset($row['time']) && $row['time'] !== '' && $row['time'] !== '00:00'){
            $time = $row['time'];
            $time = "<br><br><font style='color: gray'>@</font>$time";
        }
        $empid = $row['empid'];
        $stmtempname = $conn->prepare("SELECT name FROM user WHERE id=?");
        $stmtempname->bind_param("i", $empid);
        $stmtempname->execute();
        $resultempname = $stmtempname->get_result();
        $rowempname = $resultempname->fetch_assoc();
        $empname = $rowempname['name'];
        
        $by = " $empname";
        $events[$row['event_date']][] = $title . $time . $by;
    }
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
        <link rel="stylesheet" href="css/calendar_style.css">
        <link rel="SHORTCUT ICON" href="img/favicon.ico">
    </head>
    <body style="overflow: auto; padding-bottom: 50px;">
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php include_once 'header.php';?>
                <div class="web-page">
                    <br><br>
                    <div class="calendar-container">
                        <div class="calendar-header">
                            <button onclick="navigateMonth(-1)">▶</button>
                            <h2><?= "$monthName $year" ?></h2>
                            <button onclick="navigateMonth(1)">◀</button>
                        </div>
                        
                        <div class="calendar-grid">
                            <?php
                                $dayNames = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
                                foreach ($dayNames as $dayName) echo "<div class='day-header' style='background-color: #125483; color: #fff'>".safe_output($dayName)."</div>";
                                
                                for ($i = 0; $i < $startDayOfWeek; $i++) echo "<div class='day-box empty'></div>";
                                
                                for ($day = 1; $day <= $daysInMonth; $day++) {
                                    $dateKey = sprintf('%04d-%02d-%02d', $year, $month, $day);
                                    echo "<div class='day-box' onclick='openModal(\"".safe_output($dateKey)."\")'>";
                                    echo "<div class='day-number'>".safe_output($day)."</div>";
                                    if (isset($events[$dateKey])) {
                                        echo "<div class='event'>";
                                        foreach ($events[$dateKey] as $event) {
                                            echo "<div class='event-item'>" . safe_output($event) . "</div>";
                                        }
                                        echo "</div>";
                                    }
                                    echo "</div>";
                                }
                            ?>
                        </div>
                    </div>
                    
                    <div id="eventModal" class="modal2">
                        <br><br>
                        <div class="modal-content2">
                            <div style="display: grid; grid-template-columns: 1fr auto; background-color: #125483; color: #fff; padding: 5px; border-radius: 3px;">
                                <h3 style="">اضافة مراجعة خارجية</h3>
                                <span class="close2" onclick="closeModal()">&times;</span>
                            </div>
                            <form action="save_hrevent.php" method="POST">
                                <input type="hidden" name="event_date" id="event_date">
                                <textarea class="form-input2" style="width: 95%" name="title" placeholder="التفاصيل" required></textarea>
                                <input type="number" class="form-input2" min="0" max="59"name="timeMM" style="width: 50px" placeholder="MM" required> : <input type="number" class="form-input2" min="0" max="23" name="timeHH" style="width: 50px" placeholder="HH" required>
                                <br>
                                <button type="submit">حفظ</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            function navigateMonth(offset) {
                const currentMonth = <?= (int)$month ?>;
                const currentYear = <?= (int)$year ?>;
                let newMonth = currentMonth + offset;
                let newYear = currentYear;
                
                if (newMonth < 1) { newMonth = 12; newYear--; }
                if (newMonth > 12) { newMonth = 1; newYear++; }
                
                window.location.href = `?month=${newMonth}&year=${newYear}`;
            }
            
            function openModal(date) {
                document.getElementById('event_date').value = date;
                document.getElementById('eventModal').style.display = 'block';
            }
            
            function closeModal() {
                document.getElementById('eventModal').style.display = 'none';
            }
        </script>
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
