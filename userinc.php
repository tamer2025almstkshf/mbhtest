<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $id = stripslashes($_REQUEST['id']);
    $id = mysqli_real_escape_string($conn, $id);
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($_GET['id'] !== $myid && $row_permcheck['emp_eperm'] === '1'){
        $basic_salary = stripslashes($_REQUEST['basic_salary']);
        $basic_salary = mysqli_real_escape_string($conn, $basic_salary);
        
        $travel_tickets = stripslashes($_REQUEST['travel_tickets']);
        $travel_tickets = mysqli_real_escape_string($conn, $travel_tickets);
        
        $oil_cost = stripslashes($_REQUEST['oil_cost']);
        $oil_cost = mysqli_real_escape_string($conn, $oil_cost);
        
        $housing_cost = stripslashes($_REQUEST['housing_cost']);
        $housing_cost = mysqli_real_escape_string($conn, $housing_cost);
        
        $living_cost = stripslashes($_REQUEST['living_cost']);
        $living_cost = mysqli_real_escape_string($conn, $living_cost);
        
        $query = "UPDATE user SET basic_salary = '$basic_salary', travel_tickets = '$travel_tickets', oil_cost = '$oil_cost', housing_cost = '$housing_cost', living_cost = '$living_cost' WHERE id='$id'";
        $result = mysqli_query($conn, $query);
    }
    header("Location: emp_data.php?id=$id&inc=success");
    exit();
?>