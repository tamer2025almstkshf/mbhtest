<?php
    if (!isset($_GET['key']) || $_GET['key'] !== 'refillVacs1232') {
        echo 'test';
        http_response_code(403);
        exit('Access Denied');
    }
    
    date_default_timezone_set('Asia/Dubai');
    $servername = 'localhost';
    $username = 'mbhdbdevHashem';
    $password = ',C?(w[lb~G00';
    $dbname = 'mbhdbase';

    $conn = new mysqli($servername, $username, $password, $dbname);
    if($conn->connect_error){
        die('error connecting to the database'.$conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    
    header("X-XSS-Protection: 1; mode=block");
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: SAMEORIGIN");
    
    function resetVacationIfAnniversary($conn) {
        $today = date('m-d');
        
        $stmt = $conn->prepare("SELECT id, work_startdate, yearly_vacbalance, sick_vacbalance, mother_vacbalance, father_vacbalance, study_vacbalance FROM user");
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $startDate = $row['work_startdate'];
            $userId = $row['id'];
            $anniversary = date('m-d', strtotime($startDate));
            
            if ($today === $anniversary) {
                $update = $conn->prepare("UPDATE user SET yearly_vacbalance = 30, sick_vacbalance = 90, mother_vacbalance = 30, father_vacbalance = 5, study_vacbalance = 10 WHERE id = ?");
                $update->bind_param("i", $userId);
                $update->execute();
            }
        }
    }
    
    resetVacationIfAnniversary($conn);
    $conn->close();
    
    echo "Done";
