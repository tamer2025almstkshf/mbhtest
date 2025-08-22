<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $id = stripslashes($_REQUEST['id']);
    $id = mysqli_real_escape_string($conn, $id);
    
    if(isset($_REQUEST['save_cheque'])){
        $cheq_no = $_POST['cheq_no'] ?? [];
        $cheq_value = $_POST['cheq_value'] ?? [];
        $cheq_due_date = $_POST['cheq_due_date'] ?? [];
        $cheq_bank = $_POST['cheq_bank'] ?? [];
        
        foreach ($cheq_no as $index => $cheque_number) {
            $flagc = '0';
            $actionc = "تمت اضافة شيك جديد على الايرادات :<br>";
            
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
            
            $querytype = "SELECT * FROM incomes_expenses WHERE id='$id'";
            $resulttype = mysqli_query($conn, $querytype);
            $rowtype = mysqli_fetch_array($resulttype);
            $ie_type = $rowtype['ie_type'];
            
            if($cheque_number === '' || $cheque_number === '0'){
                continue;
            }
            if($cheque_value === '' || $cheque_value === '0'){
                continue;
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
    }
    
    $CheqNum = '0';
    if(isset($_REQUEST['CheqNum']) && $_REQUEST['CheqNum'] !== ''){
        $CheqNum = stripslashes($_REQUEST['CheqNum']);
        $CheqNum = mysqli_real_escape_string($conn, $CheqNum);
    }
    
    header("Location: cheques.php?ie_id=$id&CheqNum=$CheqNum");
    exit();
?>