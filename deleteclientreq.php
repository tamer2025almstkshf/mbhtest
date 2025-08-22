<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    if(isset($_GET['id']) && $_GET['id'] !== ''){
        $id = safe_output($_GET['id']);
        
        $action = "تم حذف طلب من موكل :<br>";
        
        $stmtr = $conn->prepare("SELECT * FROM clients_requests WHERE id=?");
        $stmtr->bind_param("i", $id);
        $stmtr->execute();
        $resultr = $stmtr->get_result();
        $row = $resultr->fetch_assoc();
        
        $client_id = $row['client_id'];
        $file_id = $row['file_id'];
        $details = $row['details'];
        
        $action = $action."<br>رقم الموكل : $client_id<br><br>رقم الملف : $file_id<br>تفاصيل الطلب : $details";
        
        include_once 'addlog.php';
        
        $stmtr = $conn->prepare("DELETE FROM clients_requests WHERE id=?");
        $stmtr->bind_param("i", $id);
        $stmtr->execute();
    }
    header("Location: clientsrequests.php");
    exit();
?>