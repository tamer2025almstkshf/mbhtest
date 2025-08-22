<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['agr_aperm'] === '1'){
        if(isset($_REQUEST['client_name']) && $_REQUEST['client_name'] !== ''){
            
            $flag = '0';
            $action = 'تم اضافة اتفاقية جديدة :<br>';
            
            $client_name = stripslashes($_REQUEST['client_name']);
            $client_name = mysqli_real_escape_string($conn, $client_name);
            if(isset($client_name) && $client_name !== ''){
                $flag = '1';
                
                $action = $action."<br>اسم الموكل : $client_name";
            }
            
            $nationality = stripslashes($_REQUEST['nationality']);
            $nationality = mysqli_real_escape_string($conn, $nationality);
            if(isset($nationality) && $nationality !== ''){
                $flag = '1';
                
                $action = $action."<br>الجنسية : $nationality";
            }
            
            $telno = stripslashes($_REQUEST['telno']);
            $telno = mysqli_real_escape_string($conn, $telno);
            if(isset($telno) && $telno !== ''){
                $flag = '1';
                
                $action = $action."<br>الهاتف : $telno";
            }
            
            $email = stripslashes($_REQUEST['email']);
            $email = mysqli_real_escape_string($conn, $email);
            if(isset($email) && $email !== ''){
                $flag = '1';
                
                $action = $action."<br>الايميل : $email";
            }
            
            $others1 = stripslashes($_REQUEST['others1']);
            $others1 = mysqli_real_escape_string($conn, $others1);
            if(isset($others1) && $others1 !== ''){
                $flag = '1';
                
                $queryc = "SELECT * FROM user WHERE id='$others1'";
                $resultc = mysqli_query($conn, $queryc);
                $rowc = mysqli_fetch_array($resultc);
                $oname = $rowc['name'];
                
                $action = $action."<br>الحضور : $oname";
            }
            
            $others2 = stripslashes($_REQUEST['others2']);
            $others2 = mysqli_real_escape_string($conn, $others2);
            if(isset($others2) && $others2 !== ''){
                $flag = '1';
                
                $queryc = "SELECT * FROM user WHERE id='$others2'";
                $resultc = mysqli_query($conn, $queryc);
                $rowc = mysqli_fetch_array($resultc);
                $oname = $rowc['name'];
                
                $action = $action."<br>الحضور : $oname";
            }
            
            $others3 = stripslashes($_REQUEST['others3']);
            $others3 = mysqli_real_escape_string($conn, $others3);
            if(isset($others3) && $others3 !== ''){
                $flag = '1';
                
                $queryc = "SELECT * FROM user WHERE id='$others3'";
                $resultc = mysqli_query($conn, $queryc);
                $rowc = mysqli_fetch_array($resultc);
                $oname = $rowc['name'];
                
                $action = $action."<br>الحضور : $oname";
            }
            
            $place = stripslashes($_REQUEST['place']);
            $place = mysqli_real_escape_string($conn, $place);
            if(isset($place) && $place !== ''){
                $flag = '1';
                
                $action = $action."<br>الفرع : $place";
            }
            
            $way = stripslashes($_REQUEST['way']);
            $way = mysqli_real_escape_string($conn, $way);
            if(isset($way) && $way !== ''){
                $flag = '1';
                
                $action = $action."<br>كيف عرفت عن المكتب : $way";
            }
            
            $sign_date = stripslashes($_REQUEST['sign_date']);
            $sign_date = mysqli_real_escape_string($conn, $sign_date);
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
            
            $empid = $_SESSION['id'];
            $timestamp = date("Y-m-d");
            
            $query22 = "INSERT INTO consultations (type, client_name, nationality, telno, email, others1, others2, others3, place, way, cons_type, empid, timestamp, sign_date) VALUES 
            ('agreement', '$client_name', '$nationality', '$telno', '$email', '$others1', '$others2', '$others3', '$place', '$way', '', '$empid', '$timestamp', '$sign_date')";
            $result22 = mysqli_query($conn, $query22);
            
            $submit_back = stripslashes($_REQUEST['submit_back']);
            $submit_back = mysqli_real_escape_string($conn, $submit_back);
            
            if(isset($submit_back) && $submit_back === 'addmore'){
                header("Location: Agreements.php?agrsaved=1&addmore=1");
                exit();
            } else{
                header("Location: Agreements.php?agrsaved=1");
                exit();
            }
        } else{
            header("Location: Agreements.php?error=0");
            exit();
        }
    }
    header("Location: Agreements.php");
    exit();
?>