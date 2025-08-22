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
    
    if($row_permcheck['csched_dperm'] === '1'){
        if(isset($_GET['id']) && $_GET['id'] !== ''){
            $id = $_GET['id'];
            
            $action = "تم حذف موعد :<br>";
            
            $queryr = "SELECT * FROM clients_schedule WHERE id='$id'";
            $resultr = mysqli_query($conn, $queryr);
            $row = mysqli_fetch_array($resultr);
            
            $client_id = $row['client_id'];
            if(isset($client_id) && $client_id !== ''){
                $flag = '1';
                
                $action = $action."<br>رقم الموكل : $client_id";
            }
            
            $name = $row['name'];
            if(isset($name) && $name !== ''){
                $flag = '1';
                
                $action = $action."<br>الاسم : $name";
            }
            
            $tel = $row['tel'];
            if(isset($tel) && $tel !== ''){
                $flag = '1';
                
                $action = $action."<br>رقم الهاتف : $tel";
            }
            
            $details = $row['details'];
            if(isset($details) && $details !== ''){
                $flag = '1';
                
                $action = $action."<br>التفاصيل : $details";
            }
            
            $meet_with = $row['meet_with'];
            if(isset($meet_with) && $meet_with !== ''){
                $flag = '1';
                
                $action = $action."<br>الاجتماع مع : $meet_with";
            }
            
            $date = $row['date'];
            if(isset($date) && $date !== ''){
                $flag = '1';
                
                $action = $action."<br>تاريخ الموعد : $date";
            }
            
            $time = $row['time'];
            if(isset($time) && $time !== ''){
                $flag = '1';
                
                $action = $action."<br>وقت الموعد : $time";
            }
            
            $meeting = $row['meeting'];
            if(isset($meeting) && $meeting !== ''){
                $flag = '1';
                
                unlink($meeting);
                $action = $action."<br>تم حذف محضر الاجتماع";
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
            
            $query = "DELETE FROM clients_schedule WHERE id='$id'";
            $result = mysqli_query($conn, $query);
        }
    }
    header("Location: clients_schedule.php");
    exit();
?>