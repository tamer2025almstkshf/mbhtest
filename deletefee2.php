<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $id = $_GET['id'];
    $queryr = "SELECT * FROM balance WHERE id='$id'";
    $resultr = mysqli_query($conn, $queryr);
    $rowr = mysqli_fetch_array($resultr);
    
    $fid = $rowr['fid'];
    $oldbalance = $rowr['balance'];
    $oldinvoice_no = $rowr['invoice_no'];
    $olddate = $rowr['date'];
    $olddetails = $rowr['details'];
    $oldpaid = $rowr['paid'];
    $oldattachment = $rowr['attachment'];
    
    $action = "تم حذف احد مصروفات الملف رقم $fid<br><br>الرصيد : $oldbalance<br>رقم الفاتورة : $oldinvoice_no<br>التاريخ : $olddate<br>التفاصيل : $olddetails<br>المبلغ المدفوع : $oldpaid";
    
    $query = "DELETE FROM balance WHERE id='$id'";
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