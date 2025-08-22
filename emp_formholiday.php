<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $id = stripslashes($_REQUEST['id']);
    $id = mysqli_real_escape_string($conn, $id);
    
    if(isset($_REQUEST['starting_date']) && $_REQUEST['starting_date'] !== '' && isset($_REQUEST['ending_date']) && $_REQUEST['ending_date'] !== ''){
        
        $flag = '0';
        $action = "تم اضافة اجازة جديدة للموظف رقم : $id<br>";
        
        $type = stripslashes($_REQUEST['vac_type']);
        $type = mysqli_real_escape_string($conn, $type);
        if(isset($type) && $type !== ''){
            $flag = '1';
            
            $action = "<br>نوع الاجازة : $type";
        }
        
        $starting_date = stripslashes($_REQUEST['starting_date']);
        $starting_date = mysqli_real_escape_string($conn, $starting_date);
        if(isset($starting_date) && $starting_date !== ''){
            $flag = '1';
            
            $action = "<br>من تاريخ : $starting_date";
        }
        
        $ending_date = stripslashes($_REQUEST['ending_date']);
        $ending_date = mysqli_real_escape_string($conn, $ending_date);
        if(isset($ending_date) && $ending_date !== ''){
            $flag = '1';
            
            $action = "<br>الى تاريخ : $ending_date";
        }
        
        $ask_date = date('Y-m-d');
        $ask = '3';
        $ask2 = '3';
        
        $instanceID = "instance109692";
        $apiToken = "gc26sv6jdy1yeib9";
        
        $queryus = "SELECT * FROM user WHERE id='$id'";
        $resultus = mysqli_query($conn, $queryus);
        $rowus = mysqli_fetch_array($resultus);
        $emp_n = $rowus['name'];
        
        include_once 'AES256.php';
        $encrypted_telno = $rowus['tel1'];
        $decrypted_telno = openssl_decrypt($encrypted_telno, $cipher, $key, $options, $iv);
        $recipient = $decrypted_telno;
        
        $message = "*`مكتب محمد بني هاشم للمحاماة و الاستشارات القانونية`*\n\n*تم اعطاء الموظف $emp_n اجازة $type*\nتاريخ تقديم الطلب : $ask_date\nتبدأ بتاريخ : $starting_date\nتنتهي بتاريخ : $ending_date";
        $url = "https://api.ultramsg.com/$instanceID/messages/chat";
        
        $data = [
            'token' => $apiToken,
            'to' => $recipient,
            'body' => $message,
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apiToken",
        ]);
        $response = curl_exec($ch);
        
        if ($response === false) {
            die('Error: ' . curl_error($ch));
        }
        curl_close($ch);
            
        $query = "INSERT INTO vacations (emp_id, type, starting_date, ending_date, ask_date, ask, ask2) VALUES 
        ('$id', '$type', '$starting_date', '$ending_date', '$ask_date', '$ask', '$ask2')";
        $result = mysqli_query($conn, $query);
        
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
    header("Location: emp_holidays.php?id=$id");
    exit();
?>