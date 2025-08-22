<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['call_rperm'] == 1){
        if(isset($_REQUEST['branch']) && $_REQUEST['branch'] !== ''){
            $branch = filter_input(INPUT_POST, "branch", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            if(isset($_REQUEST['page'])){
                $page = filter_input(INPUT_POST, "page", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            } else {
                $page = 'clientsCalls.php';
            }
            if($branch === 'select'){
                header("Location: $page");
                exit();
            }
            header("Location: $page?branch=$branch");
            exit();
        }
    }
    header("Location: $page");
    exit();
?>