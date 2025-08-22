<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'safe_output.php';
    include_once 'errorscheck.php';
    
    if($row_permcheck['csched_aperm'] == 1){
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $date = filter_input(INPUT_POST, "event_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $title = trim(filter_input(INPUT_POST, "title", FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            
            $Participants = array_filter($_POST['employee_id'], function($id) {
                return !empty($id);
            });
            if (!empty($Participants)) {
                $ids = implode(',', array_map('intval', $Participants));
                $idsc = explode(",", $ids);
                foreach($idsc as $id){
                    $stmtr = $conn->prepare("SELECT * FROM user WHERE id=?");
                    $stmtr->bind_param("i", $id);
                    $stmtr->execute();
                    $resultr = $stmtr->get_result();
                    $rowr = $resultr->fetch_assoc();
                    $stmtr->close();
                    
                    $fromEmail = 'hashemh101@hotmail.com';
                    $toEmail = safe_output($rowr['email']);
                    $subject = safe_output($rowreq['subject']);
                    $htmlContent = '<p>مكتب محمد بني هاشم للمحاماة و الاستشارات القانونية<br><br>'.safe_output($rowreq['details']).'</p>';
                    
                    $ch = curl_init($apiUrl);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'api-key: ' . $apiKey,
                    ]);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                        'sender' => ['email' => $fromEmail],
                        'to' => [['email' => $toEmail]],
                        'subject' => $subject,
                        'htmlContent' => $htmlContent,
                    ]));
                    
                    $response = curl_exec($ch);
                    curl_close($ch);
                }
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
                $stmt = $conn->prepare("INSERT INTO events (event_date, title, participants, time, branch) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $date, $title, $ids, $time, $Branch);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
    $month = date('m', strtotime($date));
    $year = date('Y', strtotime($date));
    header("Location: meetings.php?month=$month&year=$year");
    exit;
?>
