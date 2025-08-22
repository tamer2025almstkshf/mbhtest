<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $param = stripslashes($_REQUEST['param']);
    $param = mysqli_real_escape_string($conn, $param);
    
    if(isset($_REQUEST['SearchBY']) && $_REQUEST['SearchBY'] !== ''){
        $SearchBY = stripslashes($_REQUEST['SearchBY']);
        $SearchBY = mysqli_real_escape_string($conn, $SearchBY);
        
        $idquery = $rowjn['id'];
        if($param !== ''){
            if (strpos($param, 'SearchBY=Cli') !== false || strpos($param, 'SearchBY=FileNo') !== false) {
                parse_str($param, $queryParams);
                unset($queryParams['SearchBY']);
                $param = http_build_query($queryParams);
            }
            $param = $param."&SearchBY=".$SearchBY;
        } else{
            $param = "SearchBY=".$SearchBY;
        }
    }
    
    if(isset($_REQUEST['Fno']) && $_REQUEST['Fno'] !== ''){
        $Fno = stripslashes($_REQUEST['Fno']);
        $Fno = mysqli_real_escape_string($conn, $Fno);
        
        if($param !== ''){
            if (strpos($param, 'Fno') !== false) {
                parse_str($param, $queryParams);
                unset($queryParams['Fno']);
                $param = http_build_query($queryParams);
            }
            $param = $param."&Fno=".$Fno;
        } else{
            $param = "Fno=".$Fno;
        }
    }
    
    if(isset($_REQUEST['Mname']) && $_REQUEST['Mname'] !== ''){
        $Mname = stripslashes($_REQUEST['Mname']);
        $Mname = mysqli_real_escape_string($conn, $Mname);
        
        if($param !== ''){
            if (strpos($param, 'Mname') !== false) {
                parse_str($param, $queryParams);
                unset($queryParams['Mname']);
                $param = http_build_query($queryParams);
            }
            $param = $param."&Mname=".$Mname;
        } else{
            $param = "Mname=".$Mname;
        }
    }
    
    if ($SearchBY === 'Cli') {
        parse_str($param, $queryParams);
        unset($queryParams['Fno']);
        $param = http_build_query($queryParams);
    }
    
    if ($SearchBY === 'FileNo') {
        parse_str($param, $queryParams);
        unset($queryParams['Mname']);
        $param = http_build_query($queryParams);
    }
    
    if (strpos($param, 'addmore=1') !== false) {
        parse_str($param, $queryParams);
        unset($queryParams['addmore']);
        $param = http_build_query($queryParams);
    }
    
    if (strpos($param, 'savedfees=1') !== false) {
        parse_str($param, $queryParams);
        unset($queryParams['savedfees']);
        $param = http_build_query($queryParams);
    }
    
    if (strpos($param, 'error=12') !== false) {
        parse_str($param, $queryParams);
        unset($queryParams['error']);
        $param = http_build_query($queryParams);
    }
    $param = $param."&addmore=1";
    
    header("Location: CasesFees.php?$param")
?>