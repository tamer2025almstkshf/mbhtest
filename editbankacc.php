<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $id = stripslashes($_REQUEST['id']);
    $id = mysqli_real_escape_string($conn, $id);
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['accbankaccs_eperm'] === '1'){
        if(isset($_REQUEST['name']) && $_REQUEST['name'] !== '' && isset($_REQUEST['account_no']) && $_REQUEST['account_no'] !== ''){
            
            $flag = '0';
            $action = "تم تعديل الحساب البنكي :<br>";
            
            $queryr = "SELECT * FROM bank_accounts WHERE id='$id'";
            $resultr = mysqli_query($conn, $queryr);
            $rowr = mysqli_fetch_array($resultr);
            
            $oldname = $rowr['name'];
            $oldbranch = $rowr['branch'];
            $oldaccount_no = $rowr['account_no'];
            $oldaccount_amount = $rowr['account_amount'];
            $oldsign_date = $rowr['sign_date'];
            $oldnotes = $rowr['notes'];
            $oldreceipt_photo = $rowr['receipt_photo'];
            
            $name = stripslashes($_REQUEST['name']);
            $name = mysqli_real_escape_string($conn, $name);
            if(isset($name) && $name !== $oldname){
                $flag = '1';
                
                $action = $action."<br>تم تغيير اسم البنك : من $oldname الى $name";
            }
            
            $branch = stripslashes($_REQUEST['branch']);
            $branch = mysqli_real_escape_string($conn, $branch);
            if(isset($branch) && $branch !== $oldbranch){
                $flag = '1';
                
                $action = $action."<br>تم تغيير الفرع : من $oldbranch الى $branch";
            }
            
            $account_no = stripslashes($_REQUEST['account_no']);
            $account_no = mysqli_real_escape_string($conn, $account_no);
            if(isset($account_no) && $account_no !== $oldaccount_no){
                $flag = '1';
                
                $action = $action."<br>تم تغيير رقم الحساب : من $oldaccount_no الى $account_no";
            }
            
            $account_amount = stripslashes($_REQUEST['account_amount']);
            $account_amount = mysqli_real_escape_string($conn, $account_amount);
            if(isset($account_amount) && $account_amount !== $oldaccount_amount){
                $flag = '1';
                
                $action = $action."<br>تم تغيير المبلغ الاستفتاحي : من $oldaccount_amount الى $account_amount";
            }
            
            $sign_date = stripslashes($_REQUEST['sign_date']);
            $sign_date = mysqli_real_escape_string($conn, $sign_date);
            if(isset($sign_date) && $sign_date !== $oldsign_date){
                $flag = '1';
                
                $action = $action."<br>تم تغيير تاريخ التسجيل : من $oldsign_date الى $sign_date";
            }
            
            $notes = stripslashes($_REQUEST['notes']);
            $notes = mysqli_real_escape_string($conn, $notes);
            if(isset($notes) && $notes !== $oldnotes){
                $flag = '1';
                
                $action = $action."<br>تم تغيير الملاحظات : من $oldnotes الى $notes";
            }
            
            $targetDir = "files_images/banks/$account_no";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            if (isset($_FILES['receipt_photo']) && $_FILES['receipt_photo']['error'] == 0) {
                $receipt_photo = $targetDir . "/" . basename($_FILES['receipt_photo']['name']);
                if (move_uploaded_file($_FILES['receipt_photo']['tmp_name'], $receipt_photo)) {
                    if(isset($receipt_photo) && $receipt_photo !== $oldreceipt_photo){
                        $flag = '1';
                        unlink($oldreceipt_photo);
                        
                        $action = $action."<br>تم تغيير صورة الايصال";
                    }
                } else {
                    echo "Sorry, there was an error uploading Photo 1.<br>";
                }
            } else{
                $receipt_photo = $oldreceipt_photo;
            }
            
            $query = "UPDATE bank_accounts SET name='$name', branch='$branch', account_no='$account_no', account_amount='$account_amount', sign_date='$sign_date', notes='$notes', receipt_photo='$receipt_photo' WHERE id='$id'";
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
                header("Location: BankAccs.php?addmore=1&bnkaccedited=1");
                exit();
            } else{
                header("Location: BankAccs.php?bnkaccedited=1");
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