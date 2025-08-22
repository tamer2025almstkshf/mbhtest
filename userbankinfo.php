<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $id = stripslashes($_REQUEST['id']);
    $id = mysqli_real_escape_string($conn, $id);
    
    $bank_name = stripslashes($_REQUEST['bank_name']);
    $bank_name = mysqli_real_escape_string($conn, $bank_name);
    
    $iban = stripslashes($_REQUEST['iban']);
    $iban = mysqli_real_escape_string($conn, $iban);
    
    $acc_no = stripslashes($_REQUEST['acc_no']);
    $acc_no = mysqli_real_escape_string($conn, $acc_no);
    
    $pay_way = stripslashes($_REQUEST['pay_way']);
    $pay_way = mysqli_real_escape_string($conn, $pay_way);
    
    $query = "UPDATE user SET bank_name = '$bank_name', iban = '$iban', acc_no = '$acc_no', pay_way = '$pay_way' WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    
    header("Location: emp_data.php?id=$id&bnk=success");
    exit();
?>