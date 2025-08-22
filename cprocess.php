<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    if(isset($_REQUEST['SearchName']) && $_REQUEST['SearchName'] !== ''){
        $SearchName = stripslashes($_REQUEST['SearchName']);
        $SearchName = mysqli_real_escape_string($conn, $_REQUEST['SearchName']);

        $cquery = "SELECT COUNT(*) as ccount FROM client WHERE arname LIKE '%$SearchName%' OR engname LIKE '%$SearchName%'";
        $cresult = mysqli_query($conn, $cquery);
        $crow = mysqli_fetch_assoc($cresult);
        $ccount = $crow['ccount'];

        $check_query = "SELECT COUNT(*) as c2count FROM client WHERE arname = '$SearchName' OR engname = '$SearchName'";
        $check_result = mysqli_query($conn, $check_query);
        $check_row = mysqli_fetch_assoc($check_result);
        $check_count = $check_row['c2count'];

        if($check_count == 0){
            header("Location: ClientAdd.php?name=$SearchName");
            exit();
        }
        else if($check_count > 0){
            header("Location: Clients.php?name=$SearchName&result=$ccount&exists=1");
            exit();
        }
    } else{
        header("Location: ClientAdd.php");
        exit();
    }
?>