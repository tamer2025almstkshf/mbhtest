<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';

    if(isset($_REQUEST['jnid']) && $_REQUEST['jnid'] !== '' && isset($_REQUEST['job_name']) && $_REQUEST['job_name'] !== ''){
        $id = filter_input(INPUT_POST, 'jnid', FILTER_SANITIZE_NUMBER_INT);
        
        if($row_permcheck['admjobs_eperm'] == 1){
            $job_name = filter_input(INPUT_POST, 'job_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $stmtcheck = $conn->prepare("SELECT * FROM job_name WHERE id=?");
            $stmtcheck->bind_param("i", $id);
            $stmtcheck->execute();
            $resultcheck = $stmtcheck->get_result();
            $row = $resultcheck->fetch_assoc();
            $oldname = $row['job_name'];
            
            $stmtcheck = $conn->prepare("UPDATE job_name SET job_name=? WHERE id=?");
            $stmtcheck->bind_param("si", $job_name, $id);
            $stmtcheck->execute();
            
            $action = "تم تغيير اسم العمل الاداري : من $oldname الى $job_name";
            
            include_once 'addlog.php';
            
            $queryString = filter_input(INPUT_POST, 'queryString', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            if ($queryString !== '') {
                if (strpos($queryString, 'addplus=1') !== false) {
                    parse_str($queryString, $queryParams);
                    unset($queryParams['addplus']);
                    $queryString = http_build_query($queryParams);
                }
                if (strpos($queryString, 'jnid') !== false) {
                    parse_str($queryString, $queryParams);
                    unset($queryParams['jnid']);
                    $queryString = http_build_query($queryParams);
                }
                if (strpos($queryString, 'savedjtype') !== false) {
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
            
            if($result){
                header("Location: Tasks.php$queryString&editedjtype=1");
                exit();
            } 
        }
        header("Location: Tasks.php");
        exit();
    } else{
        $queryString = filter_input(INPUT_POST, 'queryString', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if ($queryString !== '') {
            if (strpos($queryString, 'addplus=1') !== false) {
                parse_str($queryString, $queryParams);
                unset($queryParams['addplus']);
            }
            if (strpos($queryString, 'jnid') !== false) {
                parse_str($queryString, $queryParams);
                unset($queryParams['jnid']);
            }
            $queryString = http_build_query($queryParams);
            $queryString = "?$queryString&addplus=1";
        } else {
            $queryString = "?addplus=1";
        }
        
        header("Location: Tasks.php$queryString");
        exit();
    }
?>