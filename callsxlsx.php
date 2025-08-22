<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['call_rperm'] == 1){
        header('Content-Type: application/json');
        
        $branch = $_GET['branch'];
        $stmt = $conn->prepare("SELECT * FROM clientsCalls WHERE branch=?");
        $stmt->bind_param("s", $branch);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            list($idFromTimestamp, $date) = explode("<br>", $row['timestamp']);
            $row['date'] = $date;
            $id2 = $row['moved_to'];
            
            $stmt2 = $conn->prepare("SELECT * FROM user WHERE id=?");
            $stmt2->bind_param("i", $idFromTimestamp);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $row2 = $result2->fetch_assoc();
            $stmt2->close();
            $username = $row2['username'];
            
            $stmt3 = $conn->prepare("SELECT * FROM user WHERE id=?");
            $stmt3->bind_param("i", $id2);
            $stmt3->execute();
            $result3 = $stmt3->get_result();
            $row3 = $result3->fetch_assoc();
            $stmt3->close();
            $moved_to = $row3['username'];
            
            $data[] = [
                'date' => $row['date'],
                'employee_name' => $username,
                'contact_name' => $row['caller_name'],
                'contact_no' => $row['caller_no'],
                'details' => $row['details'],
                'forwarded_to' => $moved_to,
                'status' => $row['action']
            ]; 
        }
        
        echo json_encode($data);
    } else{
        header("Location: clientsCalls.php?error=0");
        exit();
    }
?>