<?php
// FILE: Calendar.php

/**
 * Displays a monthly calendar with events for HR.
 * Allows users to navigate between months and add new events.
 *
 * GET Params:
 * - month: The month to display (e.g., '01' for January). Defaults to the current month.
 * - year: The year to display (e.g., '2024'). Defaults to the current year.
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
include_once 'connection.php';
include_once 'login_check.php';
include_once 'safe_output.php';
include_once 'src/I18n.php';

$i18n = new I18n('translations/Calendar.yaml');

// 2. INITIALIZATION & DATE LOGIC
// =============================================================================
$currentMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$currentYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Validate month and year to prevent errors
if ($currentMonth < 1 || $currentMonth > 12) {
    $currentMonth = date('m');
}
if ($currentYear < 1970 || $currentYear > 2100) {
    $currentYear = date('Y');
}

$firstDayTimestamp = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
$daysInMonth = date('t', $firstDayTimestamp);
$startDayOfWeek = date('w', $firstDayTimestamp); // 0 (Sun) to 6 (Sat)

$monthNameKey = strtolower(date('F', $firstDayTimestamp));
$monthName = $i18n->get($monthNameKey);
$dayNames = [$i18n->get('sunday'), $i18n->get('monday'), $i18n->get('tuesday'), $i18n->get('wednesday'), $i18n->get('thursday'), $i18n->get('friday'), $i18n->get('saturday')];

// 3. FETCH EVENTS FROM DATABASE
// =============================================================================
$events = [];
// Prepare a statement to fetch events and the creator's name in a single query for efficiency.
$sql = "
    SELECT 
        e.event_date, e.title, e.time, u.name as employee_name
    FROM 
        hr_events e
    LEFT JOIN 
        user u ON e.empid = u.id
    WHERE 
        MONTH(e.event_date) = ? AND YEAR(e.event_date) = ?
    ORDER BY 
        e.time ASC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $currentMonth, $currentYear);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $eventDetails = [];
    if (!empty($row['title'])) {
        $eventDetails[] = htmlspecialchars($row['title']);
    }
    if (!empty($row['time']) && $row['time'] !== '00:00:00') {
        // Format time to HH:MM AM/PM
        $eventDetails[] = '<span class="event-time">@ ' . date("g:i A", strtotime($row['time'])) . '</span>';
    }
    if (!empty($row['employee_name'])) {
         $eventDetails[] = '<span class="event-creator">' . $i18n->get('by') . ': ' . htmlspecialchars($row['employee_name']) . '</span>';
    }
    // Group events by date
    $events[$row['event_date']][] = implode('<br>', $eventDetails);
}
$stmt->close();
?>
<!DOCTYPE html>
<html dir="<?php echo $i18n->getDirection(); ?>" lang="<?php echo $i18n->getLocale(); ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $i18n->get('hr_calendar'); ?> - <?php echo "$monthName $currentYear"; ?></title>
    
    <!-- Dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Local CSS -->
    <link href="css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="css/calendar_style.css">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
</head>
<body style="overflow: auto; padding-bottom: 50px;">
    <div class="container">
        <?php include_once 'sidebar.php'; ?>
        <div class="website">
            <?php include_once 'header.php'; ?>
            <main class="web-page">
                <div class="calendar-container">
                    <header class="calendar-header">
                        <button onclick="navigateMonth(1)" title="<?php echo $i18n->get('next_month'); ?>">◀</button>
                        <h2><?php echo "$monthName $currentYear"; ?></h2>
                        <button onclick="navigateMonth(-1)" title="<?php echo $i18n->get('previous_month'); ?>">▶</button>
                    </header>
                    
                    <div class="calendar-grid">
                        <?php foreach ($dayNames as $dayName) : ?>
                            <div class="day-header"><?php echo $dayName; ?></div>
                        <?php endforeach; ?>
                        
                        <?php for ($i = 0; $i < $startDayOfWeek; $i++) : ?>
                            <div class="day-box empty"></div>
                        <?php endfor; ?>
                        
                        <?php for ($day = 1; $day <= $daysInMonth; $day++) : 
                            $dateKey = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                        ?>
                            <div class="day-box" onclick='openModal("<?php echo $dateKey; ?>")' title="<?php echo $i18n->get('add_event_for'); ?> <?php echo $day; ?>/<?php echo $currentMonth; ?>">
                                <div class="day-number"><?php echo $day; ?></div>
                                <?php if (isset($events[$dateKey])) : ?>
                                    <div class="event-container">
                                        <?php foreach ($events[$dateKey] as $event) : ?>
                                            <div class="event-item"><?php echo $event; ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- Event Modal -->
                <div id="eventModal" class="modal2" style="display:none;">
                    <div class="modal-content2">
                        <header class="modal-header" style="display: flex; justify-content: space-between; align-items: center; background-color: #125483; color: #fff; padding: 10px; border-radius: 3px 3px 0 0;">
                            <h3><?php echo $i18n->get('add_external_review'); ?></h3>
                            <span class="close2" onclick="closeModal()" title="<?php echo $i18n->get('close'); ?>">&times;</span>
                        </header>
                        <form id="eventForm" action="save_hrevent.php" method="POST" style="padding: 15px;">
                            <input type="hidden" name="event_date" id="event_date">
                            
                            <label for="title"><?php echo $i18n->get('details'); ?>:</label>
                            <textarea id="title" class="form-input2" name="title" required></textarea>
                            
                            <label for="timeHH"><?php echo $i18n->get('time'); ?>:</label>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <input type="number" id="timeHH" class="form-input2" min="0" max="23" name="timeHH" style="width: 60px" placeholder="HH" required>
                                <span>:</span>
                                <input type="number" class="form-input2" min="0" max="59" name="timeMM" style="width: 60px" placeholder="MM" required>
                            </div>

                            <button type="submit" class="blue-button" style="margin-top: 15px;"><?php echo $i18n->get('save'); ?></button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script>
        /**
         * Navigates to the previous or next month.
         * @param {number} offset - 1 for next month, -1 for previous month.
         */
        function navigateMonth(offset) {
            const currentMonth = <?php echo $currentMonth; ?>;
            const currentYear = <?php echo $currentYear; ?>;
            let newMonth = currentMonth + offset;
            let newYear = currentYear;

            if (newMonth < 1) {
                newMonth = 12;
                newYear--;
            } else if (newMonth > 12) {
                newMonth = 1;
                newYear++;
            }
            
            window.location.href = `?month=${newMonth}&year=${newYear}`;
        }

        /**
         * Opens the event modal and sets the date.
         * @param {string} date - The selected date in 'YYYY-MM-DD' format.
         */
        function openModal(date) {
            document.getElementById('event_date').value = date;
            document.getElementById('eventModal').style.display = 'block';
            document.getElementById('title').focus(); // Focus on the first input
        }

        /**
         * Closes the event modal.
         */
        function closeModal() {
            document.getElementById('eventModal').style.display = 'none';
        }

        // Close modal if user clicks outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('eventModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
    
</body>
</html>
