<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['csched_eperm'] == 1){
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $eventid = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $stmtr = $conn->prepare("SELECT * FROM events WHERE id=?");
            $stmtr->bind_param("i", $eventid);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmtr->close();
            
            $date = $rowr['event_date'];
            $title = trim(filter_input(INPUT_POST, "title", FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            $Participants = array_filter($_POST['employee_id'], function($id) {
                return !empty($id);
            });
            if (!empty($Participants)) {
                $ids = implode(',', array_map('intval', $Participants));
                $idsc = explode(",", $ids);
            } else{
                $ids = '';
            }
            $timeHH = filter_input(INPUT_POST, 'timeHH', FILTER_SANITIZE_NUMBER_INT);
            if($timeHH === '' || $timeHH === '0' || $timeHH === '00'){
                $timeHH = 0;
            }
            $timeHH = intval($timeHH);
            if($timeHH < 10){
                $timeHH = '0'.$timeHH;
            }
            $timeMM = filter_input(INPUT_POST, 'timeMM', FILTER_SANITIZE_NUMBER_INT);
            if($timeMM === '' || $timeMM === '0' || $timeMM === '00'){
                $timeMM = 0;
            }
            $timeMM = intval($timeMM);
            if($timeMM < 10){
                $timeMM = '0'.$timeMM;
            }
            $time = $timeHH.':'.$timeMM;
            $Branch = filter_input(INPUT_POST, "Branch", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            if (!empty($title)) {
                $stmt = $conn->prepare("UPDATE events SET event_date=?, title=?, participants=?, time=?, branch=? WHERE id=?");
                $stmt->bind_param("sssssi", $date, $title, $ids, $time, $Branch, $eventid);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
    
    $month = date('m', strtotime($date));
    $year = date('Y', strtotime($date));
    header("Location: meetings.php?month=$month&year=$year&edit=1");
    exit;
?>