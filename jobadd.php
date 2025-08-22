<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    
    if(isset($_REQUEST['job_name']) && $_REQUEST['job_name'] !== ''){
        if($row_permcheck['admjobs_aperm'] == 1){
            $job_name = filter_input(INPUT_POST, 'job_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $timestamp = date('Y/m/d H:i:s');
            
            $stmt = $conn->prepare("INSERT INTO job_name (job_name, timestamp) VALUES (?, ?)");
            $stmt->bind_param("ss", $job_name, $timestamp);
            
            $action = "تمت اضافة نوع عمل اداري جديد باسم : $job_name";
            include_once 'addlog.php';
            
            $queryString = filter_input(INPUT_POST, 'queryString', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ($queryString !== '') {
                if (strpos($queryString, 'addplus=1') !== false) {
                    parse_str($queryString, $queryParams);
                    unset($queryParams['addplus']);
                    $queryString = http_build_query($queryParams);
                }
                if (strpos($queryString, 'savedjtype=1') !== false) {
                    parse_str($queryString, $queryParams);
                    unset($queryParams['savedjtype']);
                    $queryString = http_build_query($queryParams);
                }
                if (strpos($queryString, 'editedjtype') !== false) {
                    parse_str($queryString, $queryParams);
                    unset($queryParams['editedjtype']);
                    $queryString = http_build_query($queryParams);
                }
                $queryString = "?$queryString&addplus=1";
            } else {
                $queryString = "?addplus=1";
            }
            
            if($stmt->execute()){
                header("Location: Tasks.php$queryString&savedjtype=1");
                exit();
            }
        }
    } else{
        header("Location: Tasks.php?error=0");
        exit();
    }
?>