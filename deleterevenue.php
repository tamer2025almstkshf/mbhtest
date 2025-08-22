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
    
    $page = $_GET['page'];
    
    if($row_permcheck['clients_dperm'] === '1'){
        if(isset($_GET['id']) && $_GET['id'] !== ''){
            $id = $_GET['id'];
            $querych = "SELECT * FROM cheques WHERE ie_id='$id'";
            $resultch = mysqli_query($conn, $querych);
            
            while($rowch = mysqli_fetch_array($resultch)){
                $cid = $rowch['id'];
                
                $queryc1 = "SELECT * FROM cheques WHERE id='$cid'";
                $resultc1 = mysqli_query($conn, $queryc1);
                $rowc1 = mysqli_fetch_array($resultc1);
                
                $ie_id = $rowc1['ie_id'];
                $ie_type = $rowc1['ie_type'];
                $chque_number = $rowc1['chque_number'];
                $cheque_value = $rowc1['cheque_value'];
                $cheque_duedate = $rowc1['cheque_duedate'];
                $cheque_bank = $rowc1['cheque_bank'];
                $timestamp = $rowc1['timestamp'];
                
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
                
                $querydelch = "DELETE FROM cheques WHERE id='$cid'";
                $resultdelch = mysqli_query($conn, $querydelch);
            }
            
            $queryr = "SELECT * FROM incomes_expenses WHERE id='$id'";
            $resultr = mysqli_query($conn, $queryr);
            $row = mysqli_fetch_array($resultr);
            
            $timestamp = $row['timestamp'];
            $type = $row['ie_type'];
            
            $flag = '0';
            $action = "تم حذف ال$type :<br>تمت اضافة هذه ال$type بتاريخ : $timestamp<br>";
            
            $subcat_id = $row['subcat_id'];
            if(isset($subcat_id) && $subcat_id !== ''){
                $flag = '1';
                
                $queryc = "SELECT * FROM sub_categories WHERE id='$subcat_id'";
                $resultc = mysqli_query($conn, $queryc);
                $rowc = mysqli_fetch_array($resultc);
                $cons = $rowc['subcat_name'];
                
                $action = $action."<br>البند الفرعي : $cons";
            }
            
            $service = $row['service'];
            if(isset($service) && $service !== ''){
                $flag = '1';
                
                $queryc = "SELECT * FROM services WHERE id='$service'";
                $resultc = mysqli_query($conn, $queryc);
                $rowc = mysqli_fetch_array($resultc);
                $cons = $rowc['name'];
                
                $action = $action."<br>تصنيف الخدمة : $cons";
            }
            
            $amount = $row['amount'];
            if(isset($amount) && $amount !== ''){
                $flag = '1';
                
                $action = $action."<br>المبلغ المدفوع ( نقدا ) : $amount";
            }
            
            $recive_from = $row['recive_from'];
            if(isset($recive_from) && $recive_from !== ''){
                $flag = '1';
                
                $action = $action."<br>تم استلام المبلغ من : $recive_from";
            }
            
            $recive_reason = $row['recive_reason'];
            if(isset($recive_reason) && $recive_reason !== ''){
                $flag = '1';
                
                $action = $action."<br>و ذلك عن : $recive_reason";
            }
            
            $bank_accountid = $row['bank_accountid'];
            if(isset($bank_accountid) && $bank_accountid !== ''){
                $flag = '1';
                
                $queryc = "SELECT * FROM bank_accounts WHERE id='$bank_accountid'";
                $resultc = mysqli_query($conn, $queryc);
                $rowc = mysqli_fetch_array($resultc);
                $cons = $rowc['name'];
                $cons2 = $rowc['account_no'];
                
                $action = $action."<br>تم استلام المبلغ على الحساب البنكي : $cons - $cons2";
            }
            
            $amount_date = $row['amount_date'];
            if(isset($amount_date) && $amount_date !== ''){
                $flag = '1';
                
                $action = $action."<br>تاريخ الاستلام : $amount_date";
            }
            
            $attach_file1 = $row['attach_file1'];
            if(isset($attach_file1) && $attach_file1 !== ''){
                $flag = '1';
                unlink($attach_file1);
                
                $action = $action."<br>تم حذف المرفق (1)";
            }
            
            $attach_file2 = $row['attach_file2'];
            if(isset($attach_file2) && $attach_file2 !== ''){
                $flag = '1';
                unlink($attach_file2);
                
                $action = $action."<br>تم حذف المرفق (2)";
            }
            
            $attach_file3 = $row['attach_file3'];
            if(isset($attach_file3) && $attach_file3 !== ''){
                $flag = '1';
                unlink($attach_file3);
                
                $action = $action."<br>تم حذف المرفق (3)";
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
            
            $query = "DELETE FROM incomes_expenses WHERE id='$id'";
            $result = mysqli_query($conn, $query);
        }
    }
    header("Location: newTheme1.php");
    exit();
?>