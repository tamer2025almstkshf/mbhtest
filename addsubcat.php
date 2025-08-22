<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['accsecterms_aperm'] === '1'){
        if(isset($_REQUEST['main_category']) && $_REQUEST['main_category'] !== '' && isset($_REQUEST['subcat_name']) && $_REQUEST['subcat_name'] !== ''){
            
            $flag = '0';
            $action = "تم انشاء بند فرعي جديد<br>";
            
            $main_category = stripslashes($_REQUEST['main_category']);
            $main_category = mysqli_real_escape_string($conn, $main_category);
            if(isset($main_category) && $main_category !== ''){
                $flag = '1';
                
                $queryr = "SELECT * FROM categories WHERE id='$main_category'";
                $resultr = mysqli_query($conn, $queryr);
                $rowr = mysqli_fetch_array($resultr);
                $main_name = $rowr['cat_name'];
                
                $action = $action."<br>البند الرئيسي : $main_name";
            }
            
            $subcat_name = stripslashes($_REQUEST['subcat_name']);
            $subcat_name = mysqli_real_escape_string($conn, $subcat_name);
            if(isset($subcat_name) && $subcat_name !== ''){
                $flag = '1';
                
                $action = $action."<br>البند الفرعي : $subcat_name";
            }
            
            $query = "INSERT INTO sub_categories (main_category, subcat_name) VALUES ('$main_category', '$subcat_name')";
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
                header("Location: SubCategory.php?addmore=1&subcatsaved=1");
                exit();
            } else{
                header("Location: SubCategory.php?subcatsaved=1");
                exit();
            }
        }
    }
    header("Location: SubCategory.php");
    exit();
?>