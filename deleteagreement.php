<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit();
    }
    
    $myid = $_SESSION['id'];
    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("i", $myid);
    $stmt->execute();
    $result_permcheck = $stmt->get_result();
    $row_permcheck = $result_permcheck->fetch_assoc();
    $stmt->close();
    
    if ($row_permcheck && $row_permcheck['clients_dperm'] === '1') {
        if (isset($_GET['id']) && $_GET['id'] !== '') {
            $agreement_id = $_GET['id'];
            
            // Prepare statement for fetching user names to avoid repetition
            $user_stmt = $conn->prepare("SELECT name FROM user WHERE id = ?");
    
            // Fetch agreement details before deleting to log them
            $stmt = $conn->prepare("SELECT * FROM consultations WHERE id = ?");
            $stmt->bind_param("i", $agreement_id);
            $stmt->execute();
            $resultr = $stmt->get_result();
            $row = $resultr->fetch_assoc();
            $stmt->close();
    
            if ($row) {
                $action = "تم حذف اتفاقية :<br>";
                $flag = '0';
    
                $client_name = $row['client_name'];
                if (isset($client_name) && $client_name !== '') {
                    $flag = '1';
                    $action .= "<br>اسم الموكل : " . htmlspecialchars($client_name);
                }
    
                $nationality = $row['nationality'];
                if (isset($nationality) && $nationality !== '') {
                    $flag = '1';
                    $action .= "<br>الجنسية : " . htmlspecialchars($nationality);
                }
    
                $telno = $row['telno'];
                if (isset($telno) && $telno !== '') {
                    $flag = '1';
                    $action .= "<br>الهاتف : " . htmlspecialchars($telno);
                }
    
                $email = $row['email'];
                if (isset($email) && $email !== '') {
                    $flag = '1';
                    $action .= "<br>الايميل : " . htmlspecialchars($email);
                }
    
                $others_ids = [$row['others1'], $row['others2'], $row['others3']];
                foreach ($others_ids as $other_id) {
                    if (isset($other_id) && $other_id !== '') {
                        $flag = '1';
                        $user_stmt->bind_param("i", $other_id);
                        $user_stmt->execute();
                        $resultc = $user_stmt->get_result();
                        if ($rowc = $resultc->fetch_assoc()) {
                            $oname = $rowc['name'];
                            $action .= "<br>الحضور : " . htmlspecialchars($oname);
                        }
                    }
                }
    
                $place = $row['place'];
                if (isset($place) && $place !== '') {
                    $flag = '1';
                    $action .= "<br>الفرع : " . htmlspecialchars($place);
                }
    
                $way = $row['way'];
                if (isset($way) && $way !== '') {
                    $flag = '1';
                    $action .= "<br>كيف عرفت عن المكتب : " . htmlspecialchars($way);
                }
    
                $sign_date = $row['sign_date'];
                if (isset($sign_date) && $sign_date !== '') {
                    $flag = '1';
                    $action .= "<br>تاريخ توقيع الاتفاقية : " . htmlspecialchars($sign_date);
                }
    
                if ($flag === '1') {
                    $empid = $_SESSION['id'];
                    $emp_name = $row_permcheck['name']; // We already have this from the first query
    
                    $log_stmt = $conn->prepare("INSERT INTO logs (empid, emp_name, action) VALUES (?, ?, ?)");
                    $log_stmt->bind_param("iss", $empid, $emp_name, $action);
                    $log_stmt->execute();
                    $log_stmt->close();
                }
    
                // Now, delete the agreement
                $delete_stmt = $conn->prepare("DELETE FROM consultations WHERE id = ?");
                $delete_stmt->bind_param("i", $agreement_id);
                $delete_stmt->execute();
                $delete_stmt->close();
            }
            $user_stmt->close();
        }
    }
    
    header("Location: Agreements.php");
    exit();
?>