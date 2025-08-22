<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    if(isset($_REQUEST['SearchKey'])){
        
        $word = stripslashes($_REQUEST['SearchKey']);
        $word = mysqli_real_escape_string($conn, $word);

        $Ckind = stripslashes($_REQUEST['Ckind']);
        $Ckind = mysqli_real_escape_string($conn,$Ckind);

        header("Location: SearchResult.php?key=$word&kind=$Ckind");
    }
?>