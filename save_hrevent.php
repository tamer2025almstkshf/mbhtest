<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $empid = $_SESSION['id'];
        $date = $_POST['event_date'];
        $title = trim($_POST['title']);
        $timeHH = $_POST['timeHH'];
        if($timeHH === ''){
            $timeHH = 0;
        }
        if($timeHH < 10){
            $timeHH = '0'.$timeHH;
        }
        $timeMM = $_POST['timeMM'];
        if($timeMM === ''){
            $timeMM = 0;
        }
        if($timeMM < 10){
            $timeMM = '0'.$timeMM;
        }
        $time = $timeHH.':'.$timeMM;
        
        if (!empty($title)) {
            $stmt = $conn->prepare("INSERT INTO hr_events (empid, event_date, title, time) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $empid, $date, $title, $time);
            $stmt->execute();
            $stmt->close();
        }
    }
    
    $month = date('m', strtotime($date));
    $year = date('Y', strtotime($date));
    header("Location: Calendar.php?month=$month&year=$year");
    exit;
?>
