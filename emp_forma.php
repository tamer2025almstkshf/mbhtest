<?php 
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $targetDir = "files_images/employees/$user_id";

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $flag = '0';
    $action = "تم التعديل على احد انذارات الموظف صاحب الرقم الوظيفي : $user_id<br>";
    
    $warning_id = stripslashes($_REQUEST['warning_id']);
    $warning_id = mysqli_real_escape_string($conn, $warning_id);
    
    echo "<pre>";
    print_r($_REQUEST);
    print_r($_FILES);
    echo "</pre>";
    
    if(isset($_REQUEST['warning_type22']) && $_REQUEST['warning_type22'] !== ''){
        echo 'test1';
        $warning_type = stripslashes($_REQUEST['warning_type22']);
        $warning_type = mysqli_real_escape_string($conn, $warning_type);
        
        $flag = '1';
        $action = "<br>نوع الانذار : $warning_type";
    } else{
        $warning_type = '';
    }
        echo 'test2';
    exit();
    
    if (isset($_FILES['attachments']) && $_FILES['attachments']['error'] == 0) {
        $attachments = $targetDir . "/" . basename($_FILES['attachments']['name']);
        if (move_uploaded_file($_FILES['attachments']['tmp_name'], $attachments)) {
            $flag = '1';
            
            $action = $action."<br>تمت اضافة مرفق";
        } else {
            echo "Sorry, there was an error uploading Photo 1.<br>";
        }
    } else{
        $attachments = '';
    }
    
    $query = "UPDATE warnings SET warning_type='$warning_type', attachments='$attachments' WHERE id='$warning_id'";
    $result = mysqli_query($conn, $query);
    
    if(isset($flag) && $flag === '1'){
        $empid = $_SESSION['id'];
        
        $queryu = "SELECT * FROM user WHERE id='$empid'";
        $resultu = mysqli_query($conn, $queryu);
        $rowu = mysqli_fetch_array($resultu);
        $emp_name = $rowu['name'];
        
        $querylogs = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
        $resultlogs = mysqli_query($conn, $querylogs);
    }
?>