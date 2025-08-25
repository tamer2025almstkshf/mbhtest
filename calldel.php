<?php
    include_once 'connection.php';
    include_once 'login_check.php';

    
    $myid = $_SESSION['id'];
    $stmt = $conn->prepare("SELECT * FROM user WHERE id=?");
    $stmt->bind_param("i", $myid);
    $stmt->execute();
    $result_permcheck = $stmt->get_result();
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['call_dperm'] === '1'){
        if (isset($_POST['CheckedD'])) {
            $CheckedD = $_POST['CheckedD'];
            
            $ids = implode(',', array_map('intval', $CheckedD));
            $idscheck = $ids;
            $idsc = explode(",", $idscheck);
            
            foreach($idsc as $id){
                $flag = '0';
                $action = "تم حذف مكالمة :<br>";
                
                $stmt = $conn->prepare("SELECT * FROM clientsCalls WHERE id=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $resultr = $stmt->get_result();
                $rowr = mysqli_fetch_array($resultr);
                
                $oldcaller_name = $rowr['caller_name'];
                $oldcaller_no = $rowr['caller_no'];
                $olddetails = $rowr['details'];
                $oldaction = $rowr['action'];
                $oldmoved_to = $rowr['moved_to'];
                
                if(isset($oldcaller_name) && $oldcaller_name !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>اسم المتصل : $oldcaller_name";
                }
                
                if(isset($oldcaller_no) && $oldcaller_no !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>رقم المتصل : $oldcaller_no";
                }
                
                if(isset($olddetails) && $olddetails !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>تفاصيل المكالمة : $olddetails";
                }
                
                if(isset($oldaction) && $oldaction !== ''){
                    $flag = '1';
                    
                    $action = $action."<br>الاجراء : $oldaction";
                }
                
                if(isset($oldmoved_to) && $oldmoved_to !== ''){
                    $flag = '1';
                    
                    $stmt = $conn->prepare("SELECT * FROM user WHERE id=?");
                    $stmt->bind_param("i", $oldmoved_to);
                    $stmt->execute();
                    $resultu1 = $stmt->get_result();
                    $rowu1 = mysqli_fetch_array($resultu1);
                    $cname = $rowu1['name'];
                    
                    $action = $action."<br>تم تحويل المكالمة الى : $cname";
                }
                
                $empid = $_SESSION['id'];
                
                $stmt = $conn->prepare("SELECT * FROM user WHERE id=?");
                $stmt->bind_param("i", $empid);
                $stmt->execute();
                $resultu = $stmt->get_result();
                $rowu = mysqli_fetch_array($resultu);
                $emp_name = $rowu['name'];

                if($flag === '1'){
                    $stmt = $conn->prepare("INSERT INTO logs (empid, emp_name, action) VALUES (?,?,?)");
                    $stmt->bind_param("iss", $empid, $emp_name, $action);
                    $stmt->execute();
                }
            }

            $placeholders = implode(',', array_fill(0, count($CheckedD), '?'));
            $stmt = $conn->prepare("DELETE FROM clientsCalls WHERE id IN ($placeholders)");
            $types = str_repeat('i', count($CheckedD));
            $stmt->bind_param($types, ...$CheckedD);

            if ($stmt->execute()) {
                header("Location: clientsCalls.php");
                exit();
            } else {
                header("Location: clientsCalls.php?error=0");
                exit();
            }
        } else{
            header("Location: clientsCalls.php?error=3");
            exit();
        }
    }
    header("Location: clientsCalls.php");
    exit();
?>