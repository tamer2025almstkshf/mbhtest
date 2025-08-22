<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['sdocs_rperm'] == 1){
        if(isset($_REQUEST['cid']) && $_REQUEST['cid'] !== ''){
            $cid = filter_input(INPUT_POST, 'cid', FILTER_SANITIZE_NUMBER_INT);
            
            header("Location: prices_display.php?cid=$cid");
            exit();
        }
    }
    header("Location: prices_display.php?error=0");
    exit();
?>