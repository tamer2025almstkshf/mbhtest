<?php
    include_once 'connection.php';
    include_once 'login_check.php';

    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['agr_dperm'] === '1'){
        if (isset($_POST['CheckedD'])) {
            $CheckedD = $_POST['CheckedD'];
        
            $ids = implode(',', array_map('intval', $CheckedD));
            $idscheck = $ids;
            $idsc = explode(",", $idscheck);
            
            foreach($idsc as $id){
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
            }
            
            $query_del = "DELETE FROM consultations WHERE id IN ($ids)";
        
            if (mysqli_query($conn, $query_del)) {
                header("Location: Agreements.php");
                exit();
            } else {
                header("Location: Agreements.php?error=0");
                exit();
            }
        } else{
            header("Location: Agreements.php?error=8");
            exit();
        }
    }
    header("Location: Agreements.php");
    exit();
?>