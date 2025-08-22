<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['accsecterms_dperm'] === '1'){
        if (isset($_POST['CheckedD'])) {
            $CheckedD = $_POST['CheckedD'];
            
            $ids = implode(',', array_map('intval', $CheckedD));
            $idscheck = $ids;
            $idsc = explode(",", $idscheck);
            
            foreach($idsc as $id){
                $flag = '0';
                $action = "تم حذف بند فرعي :<br>";
                
                $queryr = "SELECT * FROM sub_categories WHERE id='$id'";
                $resultr = mysqli_query($conn, $queryr);
                $rowr = mysqli_fetch_array($resultr);
                
                $old_maincatid = $rowr['main_category'];
                $querym = "SELECT * FROM categories WHERE id='$oldmaincatid'";
                $resultm = mysqli_query($conn, $querym);
                $rowm = mysqli_fetch_array($resultm);
                
                $old_maincat = $rowm['cat_name'];
                $old_subcatname = $rowr['subcat_name'];
                
                if(isset($old_maincat) && $old_maincat !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>البند الرئيسي : $old_maincat";
                }
                
                if(isset($old_subcatname) && $old_subcatname !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>اسم البند الفرعي : $old_subcatname";
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
            }
            
            $query_del = "DELETE FROM sub_categories WHERE id IN ($ids)";
        
            if (mysqli_query($conn, $query_del)) {
                header("Location: SubCategory.php");
                exit();
            } else {
                header("Location: SubCategory.php?error=0");
                exit();
            }
        } else{
            header("Location: SubCategory.php?error=10");
            exit();
        }
    } else{
        header("Location: SubCategory.php");
        exit();
    }
?>