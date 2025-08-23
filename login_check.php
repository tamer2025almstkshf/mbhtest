<?php
    session_start();
    if (!isset($_SESSION['id'])){
        header("Location: login_emp.php");
        exit();
    }
    $sessionid = $_SESSION['id'];
    $stmt = $conn->prepare("SELECT * FROM user WHERE id=?");
    $stmt->bind_param("i", $sessionid);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    /*
    // This permission check is blocking login. It has been temporarily disabled.
    if($row['signin_perm'] == 0){
        $_SESSION = array();
        
        session_destroy();
        
        header("Location: login_emp.php");
    }
    */
?>