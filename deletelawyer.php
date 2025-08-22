<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    if(isset($_GET['id']) && $_GET['id'] !== ''){
        $id = $_GET['id'];
        
        $query = "DELETE FROM lawyers WHERE id='$id'";
        $result = mysqli_query($conn, $query);
        
        header("Location: Lawyers.php");
        exit();
    } else{
        header("Location: Lawyers.php?error=0");
        exit();
    }
?>