<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $id = $_GET['id'];
    $queryr = "SELECT * FROM file_fees WHERE id='$id'";
    $resultr = mysqli_query($conn, $queryr);
    $rowr = mysqli_fetch_array($resultr);
    
    $fid = $rowr['fid'];
    $oldfees = $rowr['fees_amount'];
    $oldinstallments = $rowr['installments'];
    
    $action = "تم حذف احد اتعاب القضية من الملف رقم $fid<br><br>قيمة الاتعاب : $oldfees<br>الدفعات : $oldinstallments";
    
    $query = "DELETE FROM file_fees WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    
    $empid = $_SESSION['id'];
    
    $queryu = "SELECT * FROM user WHERE id='$empid'";
    $resultu = mysqli_query($conn, $queryu);
    $rowu = mysqli_fetch_array($resultu);
    $emp_name = $rowu['emp_name'];
    
    $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
    $resultlog = mysqli_query($conn, $querylog);
    
    header("Location: Fees.php?id=$fid");
    exit();
?>