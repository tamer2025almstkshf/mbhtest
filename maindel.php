<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['accmainterms_dperm'] === '1'){
        $flag = '0';
        $action = "تم حذف بند فرعي :<br>";
        
        $id = $_GET['id'];
        
        $queryr = "SELECT * FROM categories WHERE id='$id'";
        $resultr = mysqli_query($conn, $queryr);
        $rowr = mysqli_fetch_array($resultr);
        
        $old_cattype = $rowr['cat_type'];
        $old_catname = $rowm['cat_name'];
        
        if(isset($old_cattype) && $old_cattype !== ''){
            $flag = '1';
            
            $action = $action."<br>البند النوع : $old_cattype";
        }
        
        if(isset($old_catname) && $old_catname !== ''){
            $flag = '1';
            
            $action = $action."<br>اسم البند الرئيسي : $old_catname";
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
        
        $query_del = "DELETE FROM categories WHERE id='$id'";
        if (mysqli_query($conn, $query_del)) {
            header("Location: SubCategory.php?addmore=1&addplus=1");
            exit();
        } else {
            header("Location: SubCategory.php?error=0");
            exit();
        }
    } else{
        exit();
        header("Location: SubCategory.php");
        exit();
    }
?>