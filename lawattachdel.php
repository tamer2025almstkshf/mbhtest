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
    
    if($row_permcheck['emp_perms_edit'] === '1'){
        $id = $_GET['id'];
        if(isset($_GET['id']) && $_GET['id'] !== '' && isset($_GET['del']) && $_GET['del'] !== ''){
            $del = $_GET['del'];
            
            $delcount = 0;
            if($del === 'attachment1'){
                $delcount++;
            } else if($del === 'attachment2'){
                $delcount++;
            } else if($del === 'attachment3'){
                $delcount++;
            } else if($del === 'attachment4'){
                $delcount++;
            } else if($del === 'attachment5'){
                $delcount++;
            } else if($del === 'attachment6'){
                $delcount++;
            }
            
            if($delcount > 0){
                if($delcount === '1'){
                    $delar = 'مرفق';
                } else if($delcount === '2'){
                    $delar = 'مرفقين';
                } else if($delcount >= '3'){
                    $delar = $delcount.'مرفقات';
                }
                $action = "تم حذف $delar من مكاتب المحامين";
                
                $query = "UPDATE lawyers SET $del='' WHERE id='$id'";
                $result = mysqli_query($conn, $query);
                
                $empid = $_SESSION['id'];
                
                $queryu = "SELECT * FROM user WHERE id='$empid'";
                $resultu = mysqli_query($conn, $queryu);
                $rowu = mysqli_fetch_array($resultu);
                $emp_name = $rowu['name'];
                
                $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
                $resultlog = mysqli_query($conn, $querylog);
            }
        }
        header("Location: $page?attachments=1&id=$id");
        exit();
    } else {
        header("Location: $page");
    }
?>