<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['accbankaccs_aperm'] === '1'){
        if(isset($_REQUEST['name']) && $_REQUEST['name'] !== '' && isset($_REQUEST['account_no']) && $_REQUEST['account_no'] !== ''){
            
            $flag = '0';
            $action = "تم اضافة حساب بنكي جديد :<br>";
            
            $name = stripslashes($_REQUEST['name']);
            $name = mysqli_real_escape_string($conn, $name);
            if(isset($name) && $name !== ''){
                $flag = '1';
                
                $action = $action."<br>اسم البنك : $name";
            }
            
            $branch = stripslashes($_REQUEST['branch']);
            $branch = mysqli_real_escape_string($conn, $branch);
            if(isset($branch) && $branch !== ''){
                $flag = '1';
                
                $action = $action."<br>الفرع : $branch";
            }
            
            $account_no = stripslashes($_REQUEST['account_no']);
            $account_no = mysqli_real_escape_string($conn, $account_no);
            if(isset($account_no) && $account_no !== ''){
                $flag = '1';
                
                $action = $action."<br>رقم الحساب : $account_no";
            }
            
            $account_amount = stripslashes($_REQUEST['account_amount']);
            $account_amount = mysqli_real_escape_string($conn, $account_amount);
            if(isset($account_amount) && $account_amount !== ''){
                $flag = '1';
                
                $action = $action."<br>المبلغ الاستفتاحي : $account_amount";
            }
            
            $sign_date = stripslashes($_REQUEST['sign_date']);
            $sign_date = mysqli_real_escape_string($conn, $sign_date);
            if(isset($sign_date) && $sign_date !== ''){
                $flag = '1';
                
                $action = $action."<br>تاريخ التسجيل : $sign_date";
            }
            
            $notes = stripslashes($_REQUEST['notes']);
            $notes = mysqli_real_escape_string($conn, $notes);
            if(isset($notes) && $notes !== ''){
                $flag = '1';
                
                $action = $action."<br>الملاحظات : $notes";
            }
            
            $targetDir = "files_images/banks/$account_no";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            if (isset($_FILES['receipt_photo']) && $_FILES['receipt_photo']['error'] == 0) {
                $receipt_photo = $targetDir . "/" . basename($_FILES['receipt_photo']['name']);
                if (move_uploaded_file($_FILES['receipt_photo']['tmp_name'], $receipt_photo)) {
                    if(isset($receipt_photo) && $receipt_photo !== ''){
                        $flag = '1';
                        
                        $action = $action."<br>تمت اضافة صورة الايصال";
                    }
                } else {
                    echo "Sorry, there was an error uploading Photo 1.<br>";
                }
            } else{
                $receipt_photo = '';
            }
            
            $query = "INSERT INTO bank_accounts (name, branch, account_no, account_amount, sign_date, notes, receipt_photo) VALUES ('$name', '$branch', '$account_no', '$account_amount', '$sign_date', '$notes', '$receipt_photo')";
            $result = mysqli_query($conn, $query);
            
            if(isset($flag) && $flag === '1'){
                $empid = $_SESSION['id'];
                
                $queryu = "SELECT * FROM user WHERE id='$empid'";
                $resultu = mysqli_query($conn, $queryu);
                $rowu = mysqli_fetch_array($resultu);
                $emp_name = $rowu['name'];
                
                $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
                $resultlog = mysqli_query($conn, $querylog);
            }
            
            $submit_back = stripslashes($_REQUEST['submit_back']);
            $submit_back = mysqli_real_escape_string($conn, $submit_back);
            if($submit_back === 'addmore'){
                header("Location: BankAccs.php?addmore=1&bnkaccsaved=1");
                exit();
            } else{
                header("Location: BankAccs.php?bnkaccsaved=1");
                exit();
            }
        } else{
            header("Location: BankAccs.php?error=0");
            exit();
        }
    }
    header("Location: BankAccs.php");
    exit();
?>