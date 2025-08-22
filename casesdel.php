<?php
    include_once 'connection.php';
    include_once 'login_check.php';

    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['acccasecost_aperm'] === '1'){
        if (isset($_POST['CheckedD'])) {
            $CheckedD = $_POST['CheckedD'];
            
            $ids = implode(',', array_map('intval', $CheckedD));
            $idscheck = $ids;
            $idsc = explode(",", $idscheck);
            
            foreach($idsc as $id){
                $flag = '0';
                $action = "تم حذف اتعاب قضية :<br>";
                
                $queryr = "SELECT * FROM cases_fees WHERE id='$id'";
                $resultr = mysqli_query($conn, $queryr);
                $rowr = mysqli_fetch_array($resultr);
                
                $oldfees = $rowr['fees'];
                $oldbm_fees = $rowr['bm_fees'];
                $oldbm_alert = $rowr['bm_alert'];
                
                if(isset($oldfees) && $oldfees !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>قيمة الاتعاب : $oldfees";
                }
                
                if(isset($oldbm_fees) && $oldbm_fees !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>قيمة الاعمال الادارية : $oldbm_fees";
                }
                
                if(isset($oldbm_alert) && $oldbm_alert !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>نسبة التنبية لقيمة الاعمال الإدارية للمحاسب : $oldbm_alert";
                }
                
                $empid = $_SESSION['id'];
                
                $queryu = "SELECT * FROM user WHERE id='$empid'";
                $resultu = mysqli_query($conn, $queryu);
                $rowu = mysqli_fetch_array($resultu);
                $emp_name = $rowu['name'];
                
                if($flag === '1'){
                    $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
                    $resultlog = mysqli_query($conn, $querylog);
                }
            }
            
            $query_del = "DELETE FROM cases_fees WHERE id IN ($ids)";
            
            if (mysqli_query($conn, $query_del)) {
                header("Location: CasesFees.php");
                exit();
            } else {
                header("Location: CasesFees.php?error=0");
                exit();
            }
        } else{
            header("Location: CasesFees.php?error=12");
            exit();
        }
    }
    header("Location: CasesFees.php");
    exit();
?>