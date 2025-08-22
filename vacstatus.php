<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['vacf_aperm'] == 1){
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
                
                if($days_diff <= $remaining){
                    $ask = 3;
                    $ask2 = 1;
                    
                    $stmt = $conn->prepare("UPDATE vacations SET ask=?, ask2=? WHERE id=?");
                    $stmt->bind_param("iii", $ask, $ask2, $id);
                    $stmt->execute();
                    $stmt->close();
                } else{
                    $ask = 2;
                    $ask2 = 2;
                    
                    $stmt = $conn->prepare("UPDATE vacations SET ask=?, ask2=? WHERE id=?");
                    $stmt->bind_param("iii", $ask, $ask2, $id);
                    $stmt->execute();
                    $stmt->close();
                    
                    header("Location: vacationReqs.php?vacrejecteddays=1");
                    exit();
                }
            } else{
                $ask = 3;
                $ask2 = 1;
                
                $stmt = $conn->prepare("UPDATE vacations SET ask=?, ask2=? WHERE id=?");
                $stmt->bind_param("iii", $ask, $ask2, $id);
                $stmt->execute();
                $stmt->close();
            }
            
            header("Location: vacationReqs.php?vacaccepted=1");
            exit();
        } else if(isset($_GET['status']) && $_GET['status'] === 'no'){
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            
            $ask = 2;
            $ask2 = 2;
            
            $stmt = $conn->prepare("UPDATE vacations SET ask=?, ask2=? WHERE id=?");
            $stmt->bind_param("iii", $ask, $ask2, $id);
            $stmt->execute();
            $stmt->close();
            
            header("Location: vacationReqs.php?vacrejected=1");
            exit();
        }
    }
    header("Location: vacationReqs.php?error=0");
    exit();
?>