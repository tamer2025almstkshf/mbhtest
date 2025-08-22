<?php
include_once 'connection.php';
include_once 'login_check.php';

if (isset($_GET['id']) && $_GET['id'] !== '') {
    $id = $_GET['id'];
    
    $queryr = "SELECT * FROM cheques WHERE id='$id'";
    $resultr = mysqli_query($conn, $queryr);
    $rowr = mysqli_fetch_array($resultr);
    
    $ie_id = $rowr['ie_id'];
    $ie_type = $rowr['ie_type'];
    $chque_number = $rowr['chque_number'];
    $cheque_value = $rowr['cheque_value'];
    $cheque_duedate = $rowr['cheque_duedate'];
    $cheque_bank = $rowr['cheque_bank'];
    $timestamp = $rowr['timestamp'];
    
    $flag = '0';
    $action = "تم حذف شيك من ال$ie_type : <br>تمت اضافة هذا الشيك بتاريخ : $timestamp<br>";
    
    if(isset($ie_id) && $ie_id !== ''){
        $flag = '1';
        
        $queryc = "SELECT * FROM incomes_expenses WHERE id='$ie_id'";
        $resultc = mysqli_query($conn, $queryc);
        $rowc = mysqli_fetch_array($resultc);
        $recive_from = $rowc['recive_from'];
        
        $action = $action."<br>تم استلام الشيك من : $recive_from";
    }
    
    if(isset($chque_number) && $chque_number !== ''){
        $flag = '1';
        
        $action = $action."<br>رقم الشيك : $chque_number";
    }
    
    if(isset($cheque_value) && $cheque_value !== ''){
        $flag = '1';
        
        $action = $action."<br>قيمة الشيك : $cheque_value";
    }
    
    if(isset($cheque_duedate) && $cheque_duedate !== ''){
        $flag = '1';
        
        $action = $action."<br>تاريخ استحقاق الشيك : $cheque_duedate";
    }
    
    if(isset($cheque_bank) && $cheque_bank !== ''){
        $flag = '1';
        
        $action = $action."<br>البنك التابع له : $cheque_bank";
    }
        
    if(isset($flag) && $flag === '1'){
        $empid = $_SESSION['id'];
        
        $queryu = "SELECT * FROM user WHERE id='$empid'";
        $resultu = mysqli_query($conn, $queryu);
        $rowu = mysqli_fetch_array($resultu);
        $emp_name = $rowu['name'];
        
        $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
        $resultlog = mysqli_query($conn, $querylog);
    }

    $query = "DELETE FROM cheques WHERE id='$id'";
    $result = mysqli_query($conn, $query);

    if($result){
        header("Location: cheques.php?ie_id=$ie_id&success=0");
        exit();
    } else{
        header ("Location:cheques.php?ie_id=$ie_id&error=0");
        exit();
    }
} else{
    header ("Location:ie_id=$ie_id&cheques.php");
    exit();
}

?>
