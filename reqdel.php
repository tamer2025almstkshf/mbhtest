<?php
    include_once 'connection.php';
    include_once 'login_check.php';

if (isset($_POST['CheckedD'])) {
    $CheckedD = $_POST['CheckedD'];

    $ids = implode(',', array_map('intval', $CheckedD));
    $idscheck = $ids;
    $idsc = explode(",", $idscheck);
    
    foreach($idsc as $id){
        $action = "تم حذف طلب اجازة :<br>";
        
        $queryr = "SELECT * FROM vacations WHERE id='$id'";
        $resultr = mysqli_query($conn, $queryr);
        $row = mysqli_fetch_array($resultr);
        
        $oldemp = $row['emp_id'];
        
        $queryu1 = "SELECT * FROM user WHERE id='$oldemp'";
        $resultu1 = mysqli_query($conn, $queryu1);
        $rowu1 = mysqli_fetch_array($resultu1);
        $oldempname = $rowu1['name'];
        
        $action = $action."اسم الموظف : $oldempname<br>";
        
        $oldtype = $row['type'];
        if(isset($oldtype) && $oldtype !== ''){
            $flag = '1';
            
            $action = $action."<br>نوع الاجازة : $oldtype";
        }
        
        $oldsd = $row['starting_date'];
        if(isset($oldsd) && $oldsd !== ''){
            $flag = '1';
            
            $action = $action."<br>تاريخ بداية الاجازة : $oldsd";
        }
        
        $olded = $row['ending_date'];
        if(isset($olded) && $olded !== ''){
            $flag = '1';
            
            $action = $action."<br>تاريخ نهاية الاجازة : $olded";
        }
        
        $oldask = $row['ask'];
        if(isset($oldask) && $oldask !== ''){
            $flag = '1';
            
            if($oldask === '0'){
                $a = 'مرفوض';
            } else if($oldask === '1'){
                $a = 'في الانتظار';
            } else if($oldask === '2'){
                $a = 'مقبول';
            }
            
            $action = $action."<br>حالة الطلب : $a";
        }
        
        $oldnotes = $row['notes'];
        if(isset($oldnotes) && $oldnotes !== ''){
            $flag = '1';
            
            $action = $action."<br>الملاحظات : $oldnotes";
        }
    }

    $query_del = "DELETE FROM vacations WHERE id IN ($ids)";
    
    if(isset($flag) && $flag === '1'){
        $empid = $_SESSION['id'];
        
        $queryu = "SELECT * FROM user WHERE id='$empid'";
        $resultu = mysqli_query($conn, $queryu);
        $rowu = mysqli_fetch_array($resultu);
        $emp_name = $rowu['name'];
        
        $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
        $resultlog = mysqli_query($conn, $querylog);
    }

    if (mysqli_query($conn, $query_del)) {
        header("Location: vacationReq.php");
        exit();
    } else {
        header("Location: vacationReq.php?error=0");
        exit();
    }
} else{
    header("Location: vacationReq.php?error=null");
    exit();
}
?>