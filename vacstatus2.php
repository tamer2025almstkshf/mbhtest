<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'errorscheck.php';
    
    if($row_permcheck['vacl_aperm'] == 1){
        if(isset($_GET['status']) && $_GET['status'] === 'yes'){
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            
            $stmtr = $conn->prepare("SELECT * FROM vacations WHERE id=?");
            $stmtr->bind_param("i", $id);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmtr->close();
            
            $starting_date = $rowr['starting_date'];
            $ending_date = $rowr['ending_date'];
            $type = $rowr['type'];
            $empid = $rowr['emp_id'];
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
                $new_balance = $remaining - $days_diff;
                
                if($days_diff <= $remaining){
                    $ask2 = 3;
                    
                    $stmt = $conn->prepare("UPDATE vacations SET ask2=? WHERE id=?");
                    $stmt->bind_param("ii", $ask2, $id);
                    $stmt->execute();
                    $stmt->close();
                    
                    $stmt2 = $conn->prepare("UPDATE user SET $vt = ? WHERE id = ?");
                    $stmt2->bind_param("ii", $new_balance, $empid);
                    $stmt2->execute();
                    $stmt2->close();
                    
                    $interval = new DateInterval('P1D');
                    $newend_date = $end_date->modify("+1 days");
                    $daterange = new DatePeriod($start_date, $interval, $newend_date);
                    foreach($daterange as $date){
                        $date = $date->format("Y-m-d");
                        $stmt = $conn->prepare("INSERT INTO user_logs (user_id, login_day, login_date, login_time, late_login, logout_day, logout_date, logout_time, early_logout) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        $vact = 'اجازة '.$type;
                        $day = date("l", strtotime($date));
                        $time = '--:--';
                        $stmt->bind_param("issssssss", $empid, $day, $date, $time, $vact, $day, $date, $time, $vact);
                        $stmt->execute();
                        $stmt->close();
                    }
                } else{
                    $ask = 2;
                    $ask2 = 2;
                    
                    $stmt = $conn->prepare("UPDATE vacations SET ask=?, ask2=? WHERE id=?");
                    $stmt->bind_param("iii", $ask, $ask2, $id);
                    $stmt->execute();
                    $stmt->close();
                    
                    header("Location: vacationReqs2.php?vacrejecteddays=1");
                    exit();
                }
            } else{
                $ask2 = 3;
                
                $stmt = $conn->prepare("UPDATE vacations SET ask2=? WHERE id=?");
                $stmt->bind_param("ii", $ask2, $id);
                $stmt->execute();
                $stmt->close();
                
                $start_date = new DateTime($starting_date);
                $end_date = new DateTime($ending_date);
                $interval = new DateInterval('P1D');
                $newend_date = $end_date->modify("+1 days");
                $daterange = new DatePeriod($start_date, $interval, $newend_date);
                foreach($daterange as $date){
                    $date = $date->format("Y-m-d");
                    $stmt = $conn->prepare("INSERT INTO user_logs (user_id, login_day, login_date, login_time, late_login, logout_day, logout_date, logout_time, early_logout) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $vact = 'اجازة '.$type;
                    $day = date("l", strtotime($date));
                    $time = '--:--';
                    $stmt->bind_param("issssssss", $empid, $day, $date, $time, $vact, $day, $date, $time, $vact);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            
            header("Location: vacationReqs2.php?vacaccepted=1");
            exit();
        } else if(isset($_GET['status']) && $_GET['status'] === 'no'){
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            
            $ask = 2;
            $ask2 = 2;
            
            $stmt = $conn->prepare("UPDATE vacations SET ask=?, ask2=? WHERE id=?");
            $stmt->bind_param("iii", $ask, $ask2, $id);
            $stmt->execute();
            $stmt->close();
            
            header("Location: vacationReqs2.php?vacrejected=1");
            exit();
        } else{
            header("Location: vacationReqs2.php?error=0");
            exit();
        }
    }
    header("Location: vacationReqs2.php");
    exit();
?>