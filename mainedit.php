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
            $action = 'تم تعديل بيانات البند الرئيسي :<br>';
            
            $mtid = stripslashes($_REQUEST['mtid']);
            $mtid = mysqli_real_escape_string($conn, $mtid);
            
            $queryr = "SELECT * FROM categories WHERE id='$mtid'";
            $resultr = mysqli_query($conn, $queryr);
            $rowr = mysqli_fetch_array($resultr);
            
            $oldcattype = $rowr['cat_type'];
            $oldcatname = $rowr['cat_name'];
            
            $cat_type = stripslashes($_REQUEST['cat_type']);
            $cat_type = mysqli_real_escape_string($conn, $cat_type);
            if(isset($cat_type) && $cat_type !== $oldcattype){
                $flag = '1';
                
                $action = $action."<br>تم تغيير نوع البند من : $oldcattype الى $cat_type";
            }
            
            $cat_name = stripslashes($_REQUEST['cat_name']);
            $cat_name = mysqli_real_escape_string($conn, $cat_name);
            if(isset($cat_name) && $cat_name !== $oldcatname){
                $flag = '1';
                
                $action = $action."<br>تم تغيير نوع البند من : $oldcatname الى $cat_name";
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
            
            $query22 = "UPDATE categories SET cat_type='$cat_type', cat_name='$cat_name' WHERE id='$mtid'";
            $result22 = mysqli_query($conn, $query22);
            
            header("Location: SubCategory.php?editedmttype=1&addmore=1&addplus=1");
            exit();
        } else{
            header("Location: SubCategory.php?error=0");
            exit();
        }
    }
    header("Location: SubCategory.php");
    exit();
?>