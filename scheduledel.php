<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['csched_dperm'] === '1'){
        if (isset($_POST['CheckedD'])) {
            $CheckedD = $_POST['CheckedD'];
    
            $ids = implode(',', array_map('intval', $CheckedD));
            $idscheck = $ids;
            $idsc = explode(",", $idscheck);
            $flag = '0';
            
            foreach($idsc as $id){
                $action = "تم حذف موعد موكل :<br>";
                
                $queryr = "SELECT * FROM clients_schedule WHERE id='$id'";
                $resultr = mysqli_query($conn, $queryr);
                $row = mysqli_fetch_array($resultr);
                
                $oldclientid = $row['client_id'];
                if(isset($oldclientid) && $oldclientid !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>رقم الموكل : $oldclientid";
                }
                
                $oldclientname = $row['name'];
                if(isset($oldclientname) && $oldclientname !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>اسم الموكل : $oldclientname";
                }
                
                $oldtel = $row['tel'];
                if(isset($oldtel) && $oldtel !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>رقم الموكل : $oldtel";
                }
                
                $olddets = $row['details'];
                if(isset($olddets) && $olddets !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>التفاصيل : $olddets";
                }
                
                $oldmeeting = $row['meeting'];
                if(isset($oldmeeting) && $oldmeeting !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>الاجتماع مع : $oldmeeting";
                }
                
                $oldmeet_with = $row['meet_with'];
                if(isset($oldmeet_with) && $oldmeet_with !== ''){
                    $flag = '1';
                    
                    $queryr = "SELECT * FROM user WHERE id='$oldmeet_with'";
                    $resultr = mysqli_query($conn, $queryr);
                    $rowr = mysqli_fetch_array($resultr);
                    $oldmeet_name = $rowr['name'];
                    
                    $action = $action."<br>الاجتماع مع : $oldmeet_name";
                }
                
                $olddate = $row['date'];
                if(isset($olddate) && $olddate !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>التاريخ : $olddate";
                }
                
                $oldtime = $row['time'];
                if(isset($oldtime) && $oldtime !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>الوقت : $oldtime";
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
            
            $query_del = "DELETE FROM clients_schedule WHERE id IN ($ids)";
    
            if (mysqli_query($conn, $query_del)) {
                header("Location: clients_schedule.php");
                exit();
            } else {
                header("Location: clients_schedule.php?error=0");
                exit();
            }
        }
    }
    header("Location: clients_schedule.php");
    exit();