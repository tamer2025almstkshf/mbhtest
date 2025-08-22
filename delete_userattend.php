<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['userattendance_dperm']){
        if(isset($_GET['attendid']) && $_GET['attendid'] !== ''){
            $attendid = filter_input(INPUT_GET, 'attendid', FILTER_SANITIZE_NUMBER_INT);
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            $month = filter_input(INPUT_GET, 'month', FILTER_SANITIZE_NUMBER_INT);
            $year = filter_input(INPUT_GET, 'year', FILTER_SANITIZE_NUMBER_INT);
            
            $queryString = '';
            if(isset($id) && $id !== ''){
                if($queryString === ''){
                    $queryString = "?id=".$id;
                } else{
                    $queryString = $queryString."&id=".$id;
                }
            }
            if(isset($month) && $month !== ''){
                if($queryString === ''){
                    $queryString = "?month=".$month;
                } else{
                    $queryString = $queryString."&month=".$month;
                }
            }
            if(isset($year) && $year !== ''){
                if($queryString === ''){
                    $queryString = "?year=".$year;
                } else{
                    $queryString = $queryString."&year=".$year;
                }
            }
            
            $stmt = $conn->prepare("DELETE FROM user_logs WHERE id=?");
            $stmt->bind_param("i", $attendid);
            $stmt->execute();
            $stmt->close();
            
            header("Location: attendance.php$queryString");
            exit();
        }
    }
    header("Location: attendance.php");
    exit();
?>