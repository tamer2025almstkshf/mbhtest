<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    $page = stripslashes($_REQUEST['page']);
    $page = mysqli_real_escape_string($conn, $page);
    
    if(isset($_REQUEST['name']) && $_REQUEST['name'] !== '' && isset($_REQUEST['Cell_no']) && $_REQUEST['Cell_no'] !== '' && isset($_REQUEST['date']) && $_REQUEST['date'] !== ''  && isset($_REQUEST['timeHH']) && $_REQUEST['timeHH'] !== ''
    && isset($_REQUEST['timeMM']) && $_REQUEST['timeMM'] !== ''){
        if($row_permcheck['csched_aperm'] === '1'){
            
            $name = stripslashes($_REQUEST['name']);
            $name = mysqli_real_escape_string($conn, $name);
            
            $queryc = "SELECT * FROM client WHERE arname='$name' OR engname='$name'";
            $resultc = mysqli_query($conn, $queryc);
            if($resultc->num_rows > 0){
                $rowc = mysqli_fetch_array($resultc);
                $cid = $rowc['id'];
            } else{
                $cid = '0';
            }
            
            $flag = '0';
            $action = "تم اضافة موعد للموكل رقم : $cid<br>اسم الموكل : $name<br>";
            
            $date = stripslashes($_REQUEST['date']);
            $date = mysqli_real_escape_string($conn, $date);
            if(isset($date) && $date !== ''){
                $flag = '1';
                
                $action = $action."<br>تاريخ الموعد : $date";
            }
            
            $Cell_no = stripslashes($_REQUEST['Cell_no']);
            $Cell_no = mysqli_real_escape_string($conn, $Cell_no);
            if(isset($Cell_no) && $Cell_no !== ''){
                $flag = '1';
                
                $action = $action."<br>هاتف الموكل : $Cell_no";
            }
            
            $timeHH = stripslashes($_REQUEST['timeHH']);
            $timeHH = mysqli_real_escape_string($conn, $timeHH);
            if(intVal($timeHH) < 10){
                $timeHH = "0".intVal($timeHH);
            }
            
            $timeMM = stripslashes($_REQUEST['timeMM']);
            $timeMM = mysqli_real_escape_string($conn, $timeMM);
            if(intVal($timeMM) < 10){
                $timeMM = "0".intVal($timeMM);
            }
            
            $time = $timeHH.':'.$timeMM;
            if(isset($time) && $time !== ''){
                $flag = '1';
                
                $action = $action."<br>الوقت : $time";
            }
            
            $tel = stripslashes($_REQUEST['Cell_no']);
            $tel = mysqli_real_escape_string($conn, $tel);
            if(isset($tel) && $tel !== ''){
                $flag = '1';
                
                $action = $action."<br>الهاتف : $tel";
            }
            
            $details = stripslashes($_REQUEST['details']);
            $details = mysqli_real_escape_string($conn, $details);
            if(isset($details) && $details !== ''){
                $flag = '1';
                
                $action = $action."<br>التفاصيل : $details";
            }
            
            $targetDir = "files_images/meetings";
            
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            if (isset($_FILES['meeting']) && $_FILES['meeting']['error'] == 0) {
                $meeting = $targetDir . "/" . basename($_FILES['meeting']['name']);
                if (move_uploaded_file($_FILES['meeting']['tmp_name'], $meeting)) {
                    $flag = '1';
                    
                    $action = $action."<br>تمت اضافة ملف محضر الاجتماع";
                } else {
                    echo "Sorry, there was an error uploading Photo 1.<br>";
                }
            } else{
                $meeting = '';
            }
            
            $meet_with = stripslashes($_REQUEST['meet_with']);
            $meet_with = mysqli_real_escape_string($conn, $meet_with);
            if(isset($meet_with) && $meet_with !== ''){
                $flag = '1';
                
                $queryr = "SELECT * FROM user WHERE id='$meet_with'";
                $resultr = mysqli_query($conn, $queryr);
                $rowr = mysqli_fetch_array($resultr);
                $meet_name = $rowr['name'];
                
                $action = $action."<br>الاجتماع مع : $meet_name";
            }
            
            $timestamp = date('Y-m-d');
            $timestamp = $_SESSION['id']." <br> ".$timestamp;
            
            $query = "INSERT INTO clients_schedule (client_id, name, tel, date, time, details, meet_with, meeting, timestamp)
            VALUES ('$cid', '$name', '$Cell_no', '$date', '$time', '$details', '$meet_with', '$meeting', '$timestamp')";
            $result = mysqli_query($conn, $query);
            
            if($flag === '1'){
                $empid = $_SESSION['id'];
                
                $queryu = "SELECT * FROM user WHERE id='$empid'";
                $resultu = mysqli_query($conn, $queryu);
                $rowu = mysqli_fetch_array($resultu);
                $name = $rowu['name'];
                
                $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$name', '$action')";
                $resultlog = mysqli_query($conn, $querylog);
            }
            
            if($result){
                $submit_back = stripslashes($_REQUEST['submit_back']);
                $submit_back = mysqli_real_escape_string($conn, $submit_back);
                
                if(isset($submit_back) && $submit_back === 'addmore'){
                    header("Location: $page?schedulesaved=1&addmore=1");
                    exit();
                } else{
                    header("Location: $page?schedulesaved=1");
                    exit();
                }
            } else{
                header("Location: $page?error=0");
                exit();
            }
        }
    } else if (isset($_POST['CheckedD'])) {
        if($row_permcheck['csched_dperm'] === '1'){
            $CheckedD = $_POST['CheckedD'];
            
            $ids = implode(',', array_map('intval', $CheckedD));
            $idscheck = $ids;
            $idsc = explode(",", $idscheck);
            
            foreach($idsc as $id){
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
    } else{
        header("Location: clients_schedule.php?error=4");
        exit();
    }
?>