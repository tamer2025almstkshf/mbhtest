<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_check.php';

    if(isset($_REQUEST['search']) && $_REQUEST['search'] === 'بحث'){
        $start_date = filter_input(INPUT_POST, "start_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $to_date = filter_input(INPUT_POST, "to_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $court_name = filter_input(INPUT_POST, "court_name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $stmtc = $conn->prepare("SELECT * FROM court WHERE id=?");
        $stmtc->bind_param("s", $court_name);
        $stmtc->execute();
        $resultc = $stmtc->get_result();
        $rowc = $resultc->fetch_assoc();
        $stmtc->close();
        $court_name = $rowc['court_name'];
        
        header("Location: hearing.php?from=$start_date&to=$to_date&court=$court_name");
        exit();
        
    } else if(isset($_REQUEST['tw'])){
        header("Location: hearing.php?tw=1");
        exit();
    } else{
        header("Location: hearing.php?tw=1");
        exit();
    }
?>