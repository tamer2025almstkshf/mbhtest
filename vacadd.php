<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    $empid = filter_input(INPUT_POST, 'empid', FILTER_SANITIZE_NUMBER_INT);
    if($empid === $_SESSION['id'] && $_REQUEST['type'] !== '' && $_REQUEST['from_date'] !== '' && $_REQUEST['to_date'] !== ''){
        $type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $ask_date = date("Y-m-d");
        $starting_date = filter_input(INPUT_POST, "from_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $ending_date = filter_input(INPUT_POST, "to_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $ask = 1;
        $ask2 = 0;
        $notes = filter_input(INPUT_POST, "vac_note", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $timstamp = date("Y-m-d");
        
        if($type !== 'اخرى'){
            if($type === 'سنوية'){
                $vt = 'yearly_vacbalance';
            } else if($type === 'مرضية'){
                $vt = 'sick_vacbalance';
            } else if($type === 'وضع'){
                $vt = 'mother_vacbalance';
            } else if($type === 'أبوية'){
                $vt = 'father_vacbalance';
            } else if($type === 'دراسية'){
                $vt = 'study_vacbalance';
            }
            
            $stmtus = $conn->prepare("SELECT $vt FROM user WHERE id=?");
            $stmtus->bind_param("i", $empid);
            $stmtus->execute();
            $resultus = $stmtus->get_result();
            $rowus = $resultus->fetch_assoc();
            $stmtus->close();
            $remaining = $rowus["$vt"];
            
            $start_date = new DateTime($starting_date);
            $end_date = new DateTime($ending_date);
            $days_diff = $start_date <= $end_date ? $start_date->diff($end_date)->days + 1 : 100;
            
            if($days_diff <= $remaining){
                $stmt = $conn->prepare("INSERT INTO vacations (emp_id, type, ask_date, starting_date, ending_date, ask, ask2, notes, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issssiiss", $empid, $type, $ask_date, $starting_date, $ending_date, $ask, $ask2, $notes, $timestamp);
                $stmt->execute();
                $stmt->close();
            } else{
                header("Location: mbhEmps.php?empid=45&empsection=requests&remaining_days=$remaining");
                exit();
            }
        } else{
            $stmt = $conn->prepare("INSERT INTO vacations (emp_id, type, ask_date, starting_date, ending_date, ask, ask2, notes, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssiiss", $empid, $type, $ask_date, $starting_date, $ending_date, $ask, $ask2, $notes, $timestamp);
            $stmt->execute();
            $stmt->close();
        }
        header("Location: mbhEmps.php?empid=45&empsection=requests");
        exit();
    }
    header("Location: mbhEmps.php?empid=45&empsection=requests&error=0");
    exit();
?>