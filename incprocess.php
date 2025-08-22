<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $page = stripslashes($_REQUEST['page']);
    $page = mysqli_real_escape_string($conn, $page);
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['accrevenues_aperm'] === '1' || $row_permcheck['accexpenses_aperm'] === '1'){
        if(isset($_REQUEST['save_data'])){
            $params = stripslashes($_REQUEST['params']);
            $params = mysqli_real_escape_string($conn, $params);
            if(!isset($_REQUEST['recive_from']) || $_REQUEST['recive_from'] === ''){
                if($page === 'income.php'){
                    header("Location: income.php?addmore=1&$params&error=name");
                } else{
                    header("Location: expenses.php?addmore=1&$params&error=name");
                }
                exit();
            }
            
            $querychecknull = "SELECT * FROM incomes_expenses WHERE recive_from=''";
            $resultchecknull = mysqli_query($conn, $querychecknull);
            if($resultchecknull->num_rows === 0){
                $queryinsertnull = "INSERT INTO incomes_expenses (recive_from, bank_accountid) VALUES ('', '')";
                $resultinsertnull = mysqli_query($conn, $queryinsertnull);
            }
            
            $querychecknull = "SELECT * FROM incomes_expenses WHERE recive_from=''";
            $resultchecknull = mysqli_query($conn, $querychecknull);
            $rowchecknull = mysqli_fetch_array($resultchecknull);
            $id = $rowchecknull['id'];
            
            $ie_type = stripslashes($_REQUEST['ie_type']);
            $ie_type = mysqli_real_escape_string($conn, $ie_type);
            
            $flag = '0';
            
            if($ie_type === 'ايرادات'){
                $action = "تم اضافة ايرادات جديدة :<br>";
            } else{
                $action = "تم اضافة مصروفات جديدة :<br>";
            }
            
            $subcat_id = stripslashes($_REQUEST['subcat_id']);
            $subcat_id = mysqli_real_escape_string($conn, $subcat_id);
            if(isset($subcat_id) && $subcat_id !== ''){
                $flag = '1';
                
                $queryr = "SELECT * FROM sub_categories WHERE id='$subcat_id'";
                $resultr = mysqli_query($conn, $queryr);
                $rowr = mysqli_fetch_array($resultr);
                $subcat_name = $rowr['subcat_name'];
                
                $action = $action."<br>البند الفرعي : $subcat_name";
            }
            
            $service = stripslashes($_REQUEST['service']);
            $service = mysqli_real_escape_string($conn, $service);
            if(isset($service) && $service !== ''){
                $flag = '1';
                
                $queryr = "SELECT * FROM services WHERE id='$service'";
                $resultr = mysqli_query($conn, $queryr);
                $rowr = mysqli_fetch_array($resultr);
                $ser_name = $rowr['name'];
                
                $action = $action."<br>اسم تصنيف الخدمة : $ser_name";
            }
            
            $amount = stripslashes($_REQUEST['amount']);
            $amount = mysqli_real_escape_string($conn, $amount);
            if(isset($amount) && $amount !== ''){
                $flag = '1';
                
                $action = $action."<br>تم الدفع (نقدا) : $amount درهم";
            }
            
            $recive_from = stripslashes($_REQUEST['recive_from']);
            $recive_from = mysqli_real_escape_string($conn, $recive_from);
            if(isset($recive_from) && $recive_from !== ''){
                $flag = '1';
                
                $action = $action."<br>استلمنا من السيد/السيدة : $recive_from";
            }
            
            $cheq_no = $_POST['cheq_no'] ?? [];
            $cheq_value = $_POST['cheq_value'] ?? [];
            $cheq_due_date = $_POST['cheq_due_date'] ?? [];
            $cheq_bank = $_POST['cheq_bank'] ?? [];
            
            foreach ($cheq_no as $index => $cheque_number) {
                $flagc = '0';
                $actionc = "تمت اضافة شيك جديد على الايرادات :<br>تم استلام الشيك من : $recive_from<br>";
                
                $cheque_number = mysqli_real_escape_string($conn, stripslashes($cheque_number));
                if(isset($cheque_number) && $cheque_number !== ''){
                    $flagc = '1';
                    
                    $actionc = $actionc."<br>رقم الشيك : $cheque_number";
                }
                
                $cheque_value = mysqli_real_escape_string($conn, stripslashes($cheq_value[$index] ?? ''));
                if(isset($cheque_value) && $cheque_value !== ''){
                    $flagc = '1';
                    
                    $actionc = $actionc."<br>قيمة الشيك : $cheque_value";
                }
                
                $cheque_due_date = mysqli_real_escape_string($conn, stripslashes($cheq_due_date[$index] ?? ''));
                if(isset($cheque_due_date) && $cheque_due_date !== ''){
                    $flagc = '1';
                    
                    $actionc = $actionc."<br>تاريخ الاستحقاق : $cheque_due_date";
                }
                
                $cheque_bank = mysqli_real_escape_string($conn, stripslashes($cheq_bank[$index] ?? ''));
                if(isset($cheque_bank) && $cheque_bank !== ''){
                    $flagc = '1';
                    
                    $actionc = $actionc."<br>البنك التابع له : $cheque_bank";
                }
                
                $queryc = "INSERT INTO cheques (ie_id, ie_type, chque_number, cheque_value, cheque_duedate, cheque_bank) VALUES ('$id', '$ie_type', '$cheque_number', '$cheque_value', '$cheque_due_date', '$cheque_bank')";
                $resultc = mysqli_query($conn, $queryc);
                
                if($flagc === '1'){
                    $empid = $_SESSION['id'];
                    
                    $queryu = "SELECT * FROM user WHERE id='$empid'";
                    $resultu = mysqli_query($conn, $queryu);
                    $rowu = mysqli_fetch_array($resultu);
                    $emp_name = $rowu['name'];
                    
                    $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$actionc')";
                    $resultlog = mysqli_query($conn, $querylog);
                }
            }
            
            $recive_reason = stripslashes($_REQUEST['recive_reason']);
            $recive_reason = mysqli_real_escape_string($conn, $recive_reason);
            if(isset($recive_reason) && $recive_reason !== ''){
                $flag = '1';
                
                $action = $action."<br>و ذلك عن : $recive_reason";
            }
            
            $account_id = stripslashes($_REQUEST['account_id']);
            $account_id = mysqli_real_escape_string($conn, $account_id);
            if(isset($account_id) && $account_id !== ''){
                $flag = '1';
                
                $queryr = "SELECT * FROM bank_accounts WHERE id='$account_id'";
                $resultr = mysqli_query($conn, $queryr);
                $rowr = mysqli_fetch_array($resultr);
                $name = $rowr['name'];
                
                $action = $action."<br>تم استلام المبلغ على حساب البنك : $name";
            }
            
            $amount_date = stripslashes($_REQUEST['amount_date']);
            $amount_date = mysqli_real_escape_string($conn, $amount_date);
            if(isset($amount_date) && $amount_date !== ''){
                $flag = '1';
                
                $action = $action."<br>تاريخ الاستلام : $amount_date";
            }
            
            $targetDir = "files_images/clients/$cid";
            
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            if (isset($_FILES['attach_file1']) && $_FILES['attach_file1']['error'] == 0) {
                $attach_file1 = $targetDir . "/" . basename($_FILES['attach_file1']['name']);
                if (move_uploaded_file($_FILES['attach_file1']['tmp_name'], $attach_file1)) {
                    if(isset($attach_file1) && $attach_file1 !== ''){
                        $flag = '1';
                        
                        $action = $action."<br>تمت اضافة المرفق (1)";
                    }
                } else {
                    echo "Sorry, there was an error uploading Photo 1.<br>";
                }
            } else{
                $attach_file1 = '';
            }
            
            if (isset($_FILES['attach_file2']) && $_FILES['attach_file2']['error'] == 0) {
                $attach_file2 = $targetDir . "/" . basename($_FILES['attach_file2']['name']);
                if (move_uploaded_file($_FILES['attach_file2']['tmp_name'], $attach_file2)) {
                    if(isset($attach_file2) && $attach_file2 !== ''){
                        $flag = '1';
                        
                        $action = $action."<br>تمت اضافة المرفق (2)";
                    }
                } else {
                    echo "Sorry, there was an error uploading Photo 1.<br>";
                }
            } else{
                $attach_file2 = '';
            }
            
            if (isset($_FILES['attach_file3']) && $_FILES['attach_file3']['error'] == 0) {
                $attach_file3 = $targetDir . "/" . basename($_FILES['attach_file3']['name']);
                if (move_uploaded_file($_FILES['attach_file3']['tmp_name'], $attach_file3)) {
                    if(isset($attach_file3) && $attach_file3 !== ''){
                        $flag = '1';
                        
                        $action = $action."<br>تمت اضافة المرفق (3)";
                    }
                } else {
                    echo "Sorry, there was an error uploading Photo 1.<br>";
                }
            } else{
                $attach_file3 = '';
            }
            
            $query222 = "UPDATE incomes_expenses SET subcat_id='$subcat_id', ie_type='$ie_type', service='$service', amount='$amount', recive_from='$recive_from', recive_reason='$recive_reason', bank_accountid='$account_id', amount_date='$amount_date', 
            attach_file1='$attach_file1', attach_file2='$attach_file2', attach_file3='$attach_file3' WHERE id='$id'";
            $result222 = mysqli_query($conn, $query222);
            
            if($flag === '1'){
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
                if($page === 'income.php'){
                    header("Location: income.php?addmore=1&incsaved=1");
                } else{
                    header("Location: expenses.php?addmore=1&expsaved=1");
                }
            } else{
                if($page === 'income.php'){
                    header("Location: income.php?incsaved=1");
                } else{
                    header("Location: expenses.php?expsaved=1");
                }
            }
            exit();
        }
        
        $parameter = ''; 
        if(isset($_REQUEST['subcat_id']) && $_REQUEST['subcat_id'] !== ''){
            $subcat_id = stripslashes($_REQUEST['subcat_id']);
            $subcat_id = mysqli_real_escape_string($conn, $subcat_id);
            
            if($parameter !== ''){
                $parameter = $parameter."&subcat_id=$subcat_id";
            } else{
                $parameter = "subcat_id=$subcat_id";
            }
        }
        
        if(isset($_REQUEST['service']) && $_REQUEST['service'] !== ''){
            $service = stripslashes($_REQUEST['service']);
            $service = mysqli_real_escape_string($conn, $service);
            
            if($parameter !== ''){
                $parameter = $parameter."&ser=$service";
            } else{
                $parameter = "ser=$service";
            }
        }
        
        if(isset($_REQUEST['SearchBY']) && $_REQUEST['SearchBY'] !== ''){
            $SearchBY = stripslashes($_REQUEST['SearchBY']);
            $SearchBY = mysqli_real_escape_string($conn, $SearchBY);
            
            if($parameter !== ''){
                $parameter = $parameter."&SearchBy=$SearchBY";
            } else{
                $parameter = "SearchBy=$SearchBY";
            }
        }
        
        if(isset($_REQUEST['Fno']) && $_REQUEST['Fno'] !== ''){
            $Fno = stripslashes($_REQUEST['Fno']);
            $Fno = mysqli_real_escape_string($conn, $Fno);
            
            if($parameter !== ''){
                $parameter = $parameter."&Fno=$Fno";
            } else{
                $parameter = "Fno=$Fno";
            }
        }
        
        if(isset($_REQUEST['Mname']) && $_REQUEST['Mname'] !== ''){
            $Mname = stripslashes($_REQUEST['Mname']);
            $Mname = mysqli_real_escape_string($conn, $Mname);
            
            if($parameter !== ''){
                $parameter = $parameter."&Mname=$Mname";
            } else{
                $parameter = "Mname=$Mname";
            }
        }
        
        if(isset($_REQUEST['recive_cash']) && $_REQUEST['recive_cash'] !== ''){
            $recive_cash = stripslashes($_REQUEST['recive_cash']);
            $recive_cash = mysqli_real_escape_string($conn, $recive_cash);
            
            if($parameter !== ''){
                $parameter = $parameter."&recive_cash=$recive_cash";
            } else{
                $parameter = "recive_cash=$recive_cash";
            }
        }
        
        if(isset($_REQUEST['recive_cheq']) && $_REQUEST['recive_cheq'] !== ''){
            $recive_cheq = stripslashes($_REQUEST['recive_cheq']);
            $recive_cheq = mysqli_real_escape_string($conn, $recive_cheq);
            
            if($parameter !== ''){
                $parameter = $parameter."&recive_cheq=$recive_cheq";
            } else{
                $parameter = "recive_cheq=$recive_cheq";
            }
        }
        
        if(isset($_REQUEST['recive_cheq']) && $_REQUEST['recive_cheq'] !== ''){
            $recive_cheq = stripslashes($_REQUEST['recive_cheq']);
            $recive_cheq = mysqli_real_escape_string($conn, $recive_cheq);
            
            if($parameter !== ''){
                $parameter = $parameter."&recive_cheq=$recive_cheq";
            } else{
                $parameter = "recive_cheq=$recive_cheq";
            }
        }
        
        if(isset($_REQUEST['CheqNum']) && $_REQUEST['CheqNum'] !== ''){
            $CheqNum = stripslashes($_REQUEST['CheqNum']);
            $CheqNum = mysqli_real_escape_string($conn, $CheqNum);
            
            if($parameter !== ''){
                $parameter = $parameter."&CheqNum=$CheqNum";
            } else{
                $parameter = "CheqNum=$CheqNum";
            }
        }
        
        if(isset($_REQUEST['SearchBYcat_id']) && $_REQUEST['SearchBYcat_id'] !== ''){
            $SearchBYcat_id = stripslashes($_REQUEST['SearchBYcat_id']);
            $SearchBYcat_id = mysqli_real_escape_string($conn, $SearchBYcat_id);
            
            if($parameter !== ''){
                $parameter = $parameter."&SearchBYcat_id=$SearchBYcat_id";
            } else{
                $parameter = "SearchBYcat_id=$SearchBYcat_id";
            }
        }
        
        if($page === 'income.php'){
            header("Location:income.php?$parameter&addmore=1");
        } else{
            header("Location:expenses.php?$parameter&addmore=1");
        }
        exit();
    }
    
    $submit_back = stripslashes($_REQUEST['submit_back']);
    $submit_back = mysqli_real_escape_string($conn, $submit_back);
    
    if($submit_back === 'addmore'){
        if($page === 'income.php'){
            header("Location: income.php?addmore=1&incsaved=1");
        } else{
            header("Location: expenses.php?addmore=1&expsaved=1");
        }
    } else{
        if($page === 'income.php'){
            header("Location: income.php?incsaved=1");
        } else{
            header("Location: expenses.php?expsaved=1");
        }
    }
    exit();
?>