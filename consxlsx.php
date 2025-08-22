<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['cons_rperm'] == 1){
        header('Content-Type: application/json');
        
        $branch = $_GET['branch'];
        $stmt = $conn->prepare("SELECT * FROM consultations WHERE branch=?");
        $stmt->bind_param("s", $branch);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $userid = $row['empid'];
            $stmt2 = $conn->prepare("SELECT * FROM user WHERE id=?");
            $stmt2->bind_param("i", $userid);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $row2 = $result2->fetch_assoc();
            $stmt2->close();
            $username = $row2['name'];
            
            if($row['status'] === '1'){
                $status = "عميل حالي";
            } else{
                $status = "عميل محتمل";
            }
            
            $data[] = [
                'date' => $row['timestamp'],
                'contact_name' => $row['client_name'],
                'contact_number' => $row['telno'],
                'email' => $row['email'],
                'reference' => $row['reference'],
                'meeting_details' => $row['details'],
                'meeting_result' => $row['result'],
                'followup1' => $row['followup1'],
                'followup2' => $row['followup2'],
                'followup3' => $row['followup3'],
                'final_status' => $status,
                'employee_name' => $username,
            ]; 
        }
        
        echo json_encode($data);
    } else{
        header("Location: consultations.php?error=0");
        exit();
    }
?>