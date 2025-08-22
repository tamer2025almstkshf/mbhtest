<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    $id = stripslashes($_REQUEST['id']);
    $id = mysqli_real_escape_string($conn, $id);
    
    if($row_permcheck['csched_eperm'] === '1'){
        if(isset($_REQUEST['name']) && $_REQUEST['name'] !== '' && isset($_REQUEST['Cell_no']) && $_REQUEST['Cell_no'] !== '' && isset($_REQUEST['date']) && $_REQUEST['date'] !== '' 
        && isset($_REQUEST['timeHH']) && $_REQUEST['timeHH'] !== '' && isset($_REQUEST['timeMM']) && $_REQUEST['timeMM'] !== ''){
            
            $queryr = "SELECT * FROM clients_schedule WHERE id='$id'";
            $resultr = mysqli_query($conn, $queryr);
            $rowr = mysqli_fetch_array($resultr);
            
            $oldname = $rowr['name'];
            $oldcid = $rowr['cleint_id'];
            $olddate = $rowr['date'];
            $oldtime = $rowr['time'];
            $oldtel = $rowr['tel'];
            $olddetails = $rowr['details'];
            
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
            $action = "تم تعديل موعد الموكل رقم : $cid<br>اسم الموكل : $name<br>";
            
            if(isset($name) && $name !== $oldname){
                $flag = '1';
                
                $action = $action."<br>تم تعديل اسم الموكل : من $oldname الى $name";
            }
            
            $date = stripslashes($_REQUEST['date']);
            $date = mysqli_real_escape_string($conn, $date);
            if(isset($date) && $date !== $olddate){
                $flag = '1';
                
                $action = $action."<br>تم تعديل تاريخ الموعد : من $olddate الى $date";
            }
            
            $Cell_no = stripslashes($_REQUEST['Cell_no']);
            $Cell_no = mysqli_real_escape_string($conn, $Cell_no);
            if(isset($Cell_no) && $Cell_no !== $oldtel){
                $flag = '1';
                
                $action = $action."<br>تم تغيير هاتف الموكل : من $oldtel الى $Cell_no";
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
                
                $action = $action."<br>تم تغيير وقت الموعد : من $oldtime الى $time";
            }
            $details = stripslashes($_REQUEST['details']);
            $details = mysqli_real_escape_string($conn, $details);
            if(isset($details) && $details !== $olddetails){
                $flag = '1';
                
                $action = $action."<br>تم تغيير التفاصيل : من $olddetails الى $details";
            }
            
            $targetDir = "files_images/meetings";
            
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $meeting = $rowr['meeting'];
            if (isset($_FILES['meeting']) && $_FILES['meeting']['error'] == 0) {
                $meeting = $targetDir . "/" . basename($_FILES['meeting']['name']);
                if (move_uploaded_file($_FILES['meeting']['tmp_name'], $meeting)) {
                    $flag = '1';
                    
                    $action = $action."<br>تم تغيير ملف محضر الاجتماع";
                } else {
                    echo "Sorry, there was an error uploading Photo 1.<br>";
                }
            }
            
            $oldmw = $rowr['meet_with'];
            $meet_with = stripslashes($_REQUEST['meet_with']);
            $meet_with = mysqli_real_escape_string($conn, $meet_with);
            if(isset($meet_with) && $meet_with !== $oldmw){
                $flag = '1';
                
                $queryr = "SELECT * FROM user WHERE id='$oldmw'";
                $resultr = mysqli_query($conn, $queryr);
                $rowr = mysqli_fetch_array($resultr);
                $old_name = $rowr['name'];
                
                $queryr = "SELECT * FROM user WHERE id='$meet_with'";
                $resultr = mysqli_query($conn, $queryr);
                $rowr = mysqli_fetch_array($resultr);
                $meet_name = $rowr['name'];
                
                $action = $action."<br>تم تغيير خانة الاجتماع مع : من $old_name الى $meet_name";
            }
            
            $query = "UPDATE clients_schedule SET client_id = '$cid', name = '$name', tel = '$Cell_no', date = '$date', time = '$time', details = '$details', meet_with = '$meet_with', meeting = '$meeting' WHERE id = '$id'";
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
                    header("Location: clients_schedule.php?scheduleedited=1&addmore=1");
                    exit();
                } else{
                    header("Location: clients_schedule.php?scheduleedited=1");
                    exit();
                }
            } else{
                header("Location: clients_schedule.php?edit=1&id=$id&error=0");
                exit();
            }
        }
    }
    header("Location: clients_schedule.php&error=0");
    exit();
?>