<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['agr_aperm'] === '1'){
        if(isset($_REQUEST['cat_type']) && $_REQUEST['cat_type'] !== '' && isset($_REQUEST['cat_name']) && $_REQUEST['cat_name'] !== ''){
            
            $flag = '0';
            $action = 'تم اضافة بند رئيسي جديد :<br>';
            
            $cat_type = stripslashes($_REQUEST['cat_type']);
            $cat_type = mysqli_real_escape_string($conn, $cat_type);
            if(isset($cat_type) && $cat_type !== ''){
                $flag = '1';
                
                $action = $action."<br>نوع البند : $cat_type";
            }
            
            $cat_name = stripslashes($_REQUEST['cat_name']);
            $cat_name = mysqli_real_escape_string($conn, $cat_name);
            if(isset($cat_name) && $cat_name !== ''){
                $flag = '1';
                
                $action = $action."<br>اسم البند : $cat_name";
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
            
            $empid = $_SESSION['id'];
            $timestamp = date("Y-m-d");
            
            $query22 = "INSERT INTO categories (cat_type, cat_name) VALUES ('$cat_type', '$cat_name')";
            $result22 = mysqli_query($conn, $query22);
            
            header("Location: SubCategory.php?savedmttype=1&addmore=1&addplus=1");
            exit();
        } else{
            header("Location: SubCategory.php");
            exit();
        }
    }
    header("Location: SubCategory.php");
    exit();
?>