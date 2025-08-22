<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $queryString = '';
    if(isset($_GET['addmore'])){
        if($queryString === ''){
            $queryString = "addmore=".$_GET['addmore'];
        } else{
            $queryString = $queryString."&addmore=".$_GET['addmore'];
        }
    }
    if(isset($_GET['section'])){
        if($queryString === ''){
            $queryString = "section=".$_GET['section'];
        } else{
            $queryString = $queryString."&section=".$_GET['section'];
        }
    }
    if(isset($_GET['fno'])){
        if($queryString === ''){
            $queryString = "fno=".$_GET['fno'];
        } else{
            $queryString = $queryString."&fno=".$_GET['fno'];
        }
    }
    if(isset($_GET['cn'])){
        if($queryString === ''){
            $queryString = "cn=".$_GET['cn'];
        } else{
            $queryString = $queryString."&cn=".$_GET['cn'];
        }
    }
    if(isset($_GET['ck'])){
        if($queryString === ''){
            $queryString = "ck=".$_GET['ck'];
        } else{
            $queryString = $queryString."&ck=".$_GET['ck'];
        }
    }
    if(isset($_GET['cno'])){
        if($queryString === ''){
            $queryString = "cno=".$_GET['cno'];
        } else{
            $queryString = $queryString."&cno=".$_GET['cno'];
        }
    }
    if(isset($_GET['cy'])){
        if($queryString === ''){
            $queryString = "cy=".$_GET['cy'];
        } else{
            $queryString = $queryString."&cy=".$_GET['cy'];
        }
    }
    if(isset($_GET['agree'])){
        if($queryString === ''){
            $queryString = "agree=".$_GET['agree'];
        } else{
            $queryString = $queryString."&agree=".$_GET['agree'];
        }
    }
    if(isset($_GET['pi'])){
        if($queryString === ''){
            $queryString = "pi=".$_GET['pi'];
        } else{
            $queryString = $queryString."&pi=".$_GET['pi'];
        }
    }
    if(isset($_GET['rn'])){
        if($queryString === ''){
            $queryString = "rn=".$_GET['rn'];
        } else{
            $queryString = $queryString."&rn=".$_GET['rn'];
        }
    }
    if(isset($_GET['tn'])){
        if($queryString === ''){
            $queryString = "tn=".$_GET['tn'];
        } else{
            $queryString = $queryString."&tn=".$_GET['tn'];
        }
    }
    if(isset($_GET['di'])){
        if($queryString === ''){
            $queryString = "di=".$_GET['di'];
        } else{
            $queryString = $queryString."&di=".$_GET['di'];
        }
    }
    if(isset($_GET['bp'])){
        if($queryString === ''){
            $queryString = "bp=".$_GET['bp'];
        } else{
            $queryString = $queryString."&bp=".$_GET['bp'];
        }
    }
    if(isset($_GET['bd'])){
        if($queryString === ''){
            $queryString = "bd=".$_GET['bd'];
        } else{
            $queryString = $queryString."&bd=".$_GET['bd'];
        }
    }
    if(isset($_GET['bn'])){
        if($queryString === ''){
            $queryString = "bn=".$_GET['bn'];
        } else{
            $queryString = $queryString."&bn=".$_GET['bn'];
        }
    }
    
    if (isset($_GET['id']) && $_GET['id'] !== '') {
        include_once 'permissions_check.php';
        
        if($row_permcheck['admjobs_dperm'] == 1){
            $id = $_GET['id'];
            
            $stmtold = $conn->prepare("SELECT * FROM job_name WHERE id=?");
            $stmtold->bind_param("i", $id);
            $stmtold->execute();
            $resultold = $stmtold->get_result();
            $rowold = $resultold->fetch_assoc();
            $oldname = $rowold['job_name'];
            
            $stmt = $conn->prepare("DELETE FROM job_name WHERE id=?");
            $stmt->bind_param("i", $id);
            
            $action = "تم حذف نوع عمل اداري : $oldname";
            
            include_once 'addlog.php';
            
            if($stmt->execute()){
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
                header ("Location:Tasks.php$queryString");
                exit();
            } else{
                header ("Location:Tasks.php$queryString&error=0");
                exit();
            }
        }
        header ("Location:Tasks.php$queryString");
        exit();
    } else{
        header ("Location:Tasks.php$queryString");
        exit();
    }

?>
