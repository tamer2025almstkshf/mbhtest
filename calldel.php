<?php
    include_once 'connection.php';
    include_once 'login_check.php';

    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['call_dperm'] === '1'){
        if (isset($_POST['CheckedD'])) {
            $CheckedD = $_POST['CheckedD'];
            
            $ids = implode(',', array_map('intval', $CheckedD));
            $idscheck = $ids;
            $idsc = explode(",", $idscheck);
            
            foreach($idsc as $id){
                $flag = '0';
                $action = "تم حذف مكالمة :<br>";
                
                $queryr = "SELECT * FROM clientsCalls WHERE id='$id'";
                $resultr = mysqli_query($conn, $queryr);
                $rowr = mysqli_fetch_array($resultr);
                
                $oldcaller_name = $rowr['caller_name'];
                $oldcaller_no = $rowr['caller_no'];
                $olddetails = $rowr['details'];
                $oldaction = $rowr['action'];
                $oldmoved_to = $rowr['moved_to'];
                
                if(isset($oldcaller_name) && $oldcaller_name !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>اسم المتصل : $oldcaller_name";
                }
                
                if(isset($oldcaller_no) && $oldcaller_no !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>رقم المتصل : $oldcaller_no";
                }
                
                if(isset($olddetails) && $olddetails !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>تفاصيل المكالمة : $olddetails";
                }
                
                if(isset($oldaction) && $oldaction !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>الاجراء : $oldaction";
                }
                
                if(isset($oldmoved_to) && $oldmoved_to !== ''){
                    $flag = '1';
                    
                    $queryu1 = "SELECT * FROM user WHERE id='$oldmoved_to'";
                    $resultu1 = mysqli_query($conn, $queryu1);
                    $rowu1 = mysqli_fetch_array($resultu1);
                    $cname = $rowu1['name'];
                    
                    $action = $action."<br>تم تحويل المكالمة الى : $cname";
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
            
            $query_del = "DELETE FROM clientsCalls WHERE id IN ($ids)";
        
            if (mysqli_query($conn, $query_del)) {
                header("Location: clientsCalls.php");
                exit();
            } else {
                header("Location: clientsCalls.php?error=0");
                exit();
            }
        } else{
            header("Location: clientsCalls.php?error=3");
            exit();
        }
    }
    header("Location: clientsCalls.php");
    exit();
?>