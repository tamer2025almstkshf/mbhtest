<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $id = $_SESSION['id'];
    $querymain = "SELECT * FROM user WHERE id='$id'";
    $resultmain = mysqli_query($conn, $querymain);
    $rowmain = mysqli_fetch_array($resultmain);
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    $page = $_GET['page'];
    
    if($row_permcheck['accsecterms_dperm'] === '1'){
        if(isset($_GET['id']) && $_GET['id'] !== ''){
            $id = $_GET['id'];
            
            $action = "تم حذف بند فرعي :<br>";
            
            $queryr = "SELECT * FROM sub_categories WHERE id='$id'";
            $resultr = mysqli_query($conn, $queryr);
            $row = mysqli_fetch_array($resultr);
            $main_category = $row['main_category'];
            
            $mquery = "SELECT * FROM categories WHERE id='$main_category'";
            $mresult = mysqli_query($conn, $mquery);
            $mrow = mysqli_fetch_array($mresult);
            
            $cat_name = $mrow['cat_name'];
            $subcat_name = $mrow['subcat_name'];
            
            $action = $action."<br>اسم البند الرئيسي : $cat_name";
            $action = $action."<br>اسم البند الفرعي : $subcat_name";
            
            $empid = $_SESSION['id'];
            
            $queryu = "SELECT * FROM user WHERE id='$empid'";
            $resultu = mysqli_query($conn, $queryu);
            $rowu = mysqli_fetch_array($resultu);
            
            $emp_name = $rowu['name'];
            
            if($flag === '1'){
                $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
                $resultlog = mysqli_query($conn, $querylog);
            }
            
            $query = "DELETE FROM sub_categories WHERE id='$id'";
            $result = mysqli_query($conn, $query);
        }
    }
    header("Location: newTheme1.php");
    exit();
?>