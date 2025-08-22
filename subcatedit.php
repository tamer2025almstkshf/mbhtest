<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $id = stripslashes($_REQUEST['id']);
    $id = mysqli_real_escape_string($conn, $id);
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['accsecterms_eperm'] === '1'){
        if(isset($_REQUEST['main_category']) && $_REQUEST['main_category'] !== '' && isset($_REQUEST['subcat_name']) && $_REQUEST['subcat_name'] !== ''){
            
            $flag = '0';
            $action = "تم التعديل على بند فرعي :<br>";
            
            $querysc = "SELECT * FROM sub_categories WHERE id='$id'";
            $resultsc = mysqli_query($conn, $querysc);
            $rowsc = mysqli_fetch_array($resultsc);
            
            $oldmaincat = $rowsc['main_category'];
            $oldsubcat = $rowsc['subcat_name'];
            
            $main_category = stripslashes($_REQUEST['main_category']);
            $main_category = mysqli_real_escape_string($conn, $main_category);
            if(isset($main_category) && $main_category !== $oldmaincat){
                $flag = '1';
                
                $oldqueryr = "SELECT * FROM categories WHERE id='$oldmaincat'";
                $oldresultr = mysqli_query($conn, $oldqueryr);
                $oldrowr = mysqli_fetch_array($oldresultr);
                $oldmain_name = $oldrowr['cat_name'];
                
                $queryr = "SELECT * FROM categories WHERE id='$main_category'";
                $resultr = mysqli_query($conn, $queryr);
                $rowr = mysqli_fetch_array($resultr);
                $main_name = $rowr['cat_name'];
                
                $action = $action."<br>تم تعديل البند الرئيسي : من $oldmain_name الى $main_name";
            }
            
            $subcat_name = stripslashes($_REQUEST['subcat_name']);
            $subcat_name = mysqli_real_escape_string($conn, $subcat_name);
            if(isset($subcat_name) && $subcat_name !== $oldsubcat){
                $flag = '1';
                
                $action = $action."<br>تم تعديل البند الفرعي : من $oldsubcat الى $subcat_name";
            }
            
            $query = "UPDATE sub_categories SET main_category='$main_category', subcat_name='$subcat_name' WHERE id='$id'";
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
                header("Location: SubCategory.php?addmore=1&subcatedited=1");
                exit();
            } else{
                header("Location: SubCategory.php?subcatedited=1");
                exit();
            }
        }
    }
    header("Location: SubCategory.php");
    exit();
?>