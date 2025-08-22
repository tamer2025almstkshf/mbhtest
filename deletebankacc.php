<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $id = $_SESSION['id'];
    $querymain = "SELECT * FROM user WHERE id='$id'";
    $resultmain = mysqli_query($conn, $querymain);
    $rowmain = mysqli_fetch_array($resultmain);
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['clients_dperm'] === '1'){
        if(isset($_GET['id']) && $_GET['id'] !== ''){
            $id = $_GET['id'];
            
            $action = "تم حذف حساب بنكي :<br>";
            
            $queryr = "SELECT * FROM bank_accounts WHERE id='$id'";
            $resultr = mysqli_query($conn, $queryr);
            $row = mysqli_fetch_array($resultr);
            
            $name = $row['name'];
            if(isset($name) && $name !== ''){
                $flag = '1';
                
                $action = $action."<br>اسم البنك : $name";
            }
            
            $branch = $row['$branch'];
            if(isset($branch) && $branch !== ''){
                $flag = '1';
                
                $action = $action."<br>الفرع : $branch";
            }
            
            $account_no = $row['account_no'];
            if(isset($account_no) && $account_no !== ''){
                $flag = '1';
                
                $action = $action."<br>رقم الحساب : $account_no";
            }
            
            $account_amount = $row['account_amount'];
            if(isset($account_amount) && $account_amount !== ''){
                $flag = '1';
                
                $action = $action."<br>المبلغ الاستفتاحي : $account_amount";
            }
            
            $sign_date = $row['sign_date'];
            if(isset($sign_date) && $sign_date !== ''){
                $flag = '1';
                
                $action = $action."<br>تاريخ التسجيل : $sign_date";
            }
            
            $notes = $row['notes'];
            if(isset($notes) && $notes !== ''){
                $flag = '1';
                
                $action = $action."<br>الملاحظات : $notes";
            }
            
            $receipt_photo = $row['receipt_photo'];
            if(isset($receipt_photo) && $receipt_photo !== ''){
                $flag = '1';
                
                unlink($receipt_photo);
                $action = $action."<br>تم حذف مرفق الايصال";
            }
            
            if($flag === '1'){
                $empid = $_SESSION['id'];
                
                $queryu = "SELECT * FROM user WHERE id='$empid'";
                $resultu = mysqli_query($conn, $queryu);
                $rowu = mysqli_fetch_array($resultu);
                $emp_name = $rowu['name'];
                
                $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
                $resultlog = mysqli_query($conn, $querylog);
            }
            
            $query = "DELETE FROM bank_accounts WHERE id='$id'";
            $result = mysqli_query($conn, $query);
        }
    }
    header("Location: BankAccs.php");
    exit();
?>