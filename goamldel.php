<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['goaml_dperm'] === '1'){
        if (isset($_POST['CheckedD'])) {
            $CheckedD = $_POST['CheckedD'];
            
            $ids = implode(',', array_map('intval', $CheckedD));
            $idscheck = $ids;
            $idsc = explode(",", $idscheck);
            
            foreach($idsc as $id){
                $action = "تم حذف احد مستندات goAML";
                
                $queryr = "SELECT * FROM goAML WHERE id='$id'";
                $resultr = mysqli_query($conn, $queryr);
                $row = mysqli_fetch_array($resultr);
                
                $full_path = $row['goAML_attachment'];
                $filename = basename($full_path);
                if(isset($full_path) && $full_path !== ''){
                    $flag = '1';
                    unlink($full_path);
                    
                    $action = $action."<br>اسم المستند : $filename";
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
            
            $query_del = "DELETE FROM goAML WHERE id IN ($ids)";
            $result_del = mysqli_query($conn, $query_del);
        } else{
            header("Location: goAMList.php?error=5");
            exit();
        }
    }
    header("Location: goAMList.php");
    exit();
?>