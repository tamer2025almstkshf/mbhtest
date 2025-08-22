<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    if(isset($_GET['id']) && $_GET['id'] !== ''){
        $id = $_GET['id'];
        
        $queryr = "SELECT * FROM user WHERE id='$id'";
        $resultr = mysqli_query($conn, $queryr);
        $rowr = mysqli_fetch_array($resultr);
        
        $name = $rowr['name'];
        $perm = $rowr['signin_perm'];
        
        if($perm === '1'){
            $newperm = '0';
            
            $permname = 'فعال';
            $newpermname = 'غير فعال';
        } else{
            $newperm = '1';
            
            $permname = 'غير فعال';
            $newpermname = 'فعال';
        }
        
        $action = "تم تغيير صلاحيات الدخول للموظف $name من : $permname الى $newpermname<br>";
        
        $empid = $_SESSION['id'];
        
        $queryu = "SELECT * FROM user WHERE id='$empid'";
        $resultu = mysqli_query($conn, $queryu);
        $rowu = mysqli_fetch_array($resultu);
        $emp_name = $rowu['name'];
        
        $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
        $resultlog = mysqli_query($conn, $querylog);
        
        $query = "UPDATE user SET signin_perm='$newperm' WHERE id='$id'";
        $result = mysqli_query($conn, $query);
        
        header("Location: mbhEmps.php");
        exit;
    }
?>