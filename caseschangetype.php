<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    if(isset($_REQUEST['type']) && $_REQUEST['type'] !== ''){
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if($type === 'select'){
            header("Location: $page");
            exit();
        }
        header("Location: $page?section=$type");
        exit();
    }
    header("Location: $page");
    exit();
?>