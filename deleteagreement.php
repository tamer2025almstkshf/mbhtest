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
    
    if($row_permcheck['clients_dperm'] === '1'){
        if(isset($_GET['id']) && $_GET['id'] !== ''){
            $id = $_GET['id'];
            
            $action = "تم حذف اتفاقية :<br>";
            
            $queryr = "SELECT * FROM consultations WHERE id='$id'";
            $resultr = mysqli_query($conn, $queryr);
            $row = mysqli_fetch_array($resultr);
            
            $client_name = $row['client_name'];
            if(isset($client_name) && $client_name !== ''){
                $flag = '1';
                
                $action = $action."<br>اسم الموكل : $client_name";
            }
            
            $nationality = $row['nationality'];
            if(isset($nationality) && $nationality !== ''){
                $flag = '1';
                
                $action = $action."<br>الجنسية : $nationality";
            }
            
            $telno = $row['telno'];
            if(isset($telno) && $telno !== ''){
                $flag = '1';
                
                $action = $action."<br>الهاتف : $telno";
            }
            
            $email = $row['email'];
            if(isset($email) && $email !== ''){
                $flag = '1';
                
                $action = $action."<br>الايميل : $email";
            }
            
            $others1 = $row['others1'];
            if(isset($others1) && $others1 !== ''){
                $flag = '1';
                
                $queryc = "SELECT * FROM user WHERE id='$others1'";
                $resultc = mysqli_query($conn, $queryc);
                $rowc = mysqli_fetch_array($resultc);
                $oname = $rowc['name'];
                
                $action = $action."<br>الحضور : $oname";
            }
            
            $others2 = $row['others2'];
            if(isset($others2) && $others2 !== ''){
                $flag = '1';
                
                $queryc = "SELECT * FROM user WHERE id='$others2'";
                $resultc = mysqli_query($conn, $queryc);
                $rowc = mysqli_fetch_array($resultc);
                $oname = $rowc['name'];
                
                $action = $action."<br>الحضور : $oname";
            }
            
            $others3 = $row['others3'];
            if(isset($others3) && $others3 !== ''){
                $flag = '1';
                
                $queryc = "SELECT * FROM user WHERE id='$others3'";
                $resultc = mysqli_query($conn, $queryc);
                $rowc = mysqli_fetch_array($resultc);
                $oname = $rowc['name'];
                
                $action = $action."<br>الحضور : $oname";
            }
            
            $place = $row['place'];
            if(isset($place) && $place !== ''){
                $flag = '1';
                
                $action = $action."<br>الفرع : $place";
            }
            
            $way = $row['way'];
            if(isset($way) && $way !== ''){
                $flag = '1';
                
                $action = $action."<br>كيف عرفت عن المكتب : $way";
            }
            
            $sign_date = $row['sign_date'];
            if(isset($sign_date) && $sign_date !== ''){
                $flag = '1';
                
                $action = $action."<br>تاريخ توقيع الاتفاقية : $sign_date";
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
            
            $query = "DELETE FROM consultations WHERE id='$id'";
            $result = mysqli_query($conn, $query);
        }
    }
    header("Location: Agreements.php");
    exit();
?>