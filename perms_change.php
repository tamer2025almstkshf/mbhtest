<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($admin == 1){
        if(isset($_GET['id'])){
            if(isset($_GET['admin'])){
                if($owner == 1){
                    $admin_perm = filter_input(INPUT_GET, 'admin', FILTER_SANITIZE_NUMBER_INT);
                    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
                    
                    if($admin_perm == 1){
                        $admin_perm = 0;
                    } else{
                        $admin_perm = 1;
                    }
                    
                    $stmt = $conn->prepare("UPDATE user SET admin = ? WHERE id = ?");
                    $stmt->bind_param("ii", $admin_perm, $id);
                    $stmt->execute();
                    $stmt->close();
                    
                    header("Location: mbhEmps.php?section=permissions");
                    exit();
                }
            } else if(isset($_GET['signin_perm'])){
                if($admin == 1){
                    $signin_perm = filter_input(INPUT_GET, 'signin_perm', FILTER_SANITIZE_NUMBER_INT);
                    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
                    $queryString = filter_input(INPUT_GET, 'queryString', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    
                    if($id == $ownerid){
                        header("Location: mbhEmps.php?error=0");
                        exit();
                    }
                    if($signin_perm == 1){
                        $signin_perm = 0;
                        $archived_date = date("Y-m-d");
                        $closed_by = $_SESSION['id'];
                        
                        $stmt = $conn->prepare("UPDATE user SET signin_perm = ?, archived_date = ?, closed_by = ? WHERE id = ?");
                        $stmt->bind_param("isii", $signin_perm, $archived_date, $closed_by, $id);
                        $stmt->execute();
                        $stmt->close();
                    } else{
                        $signin_perm = 1;
                        $activation_date = date("Y-m-d");
                        $opened_by = $_SESSION['id'];
                        
                        $stmt = $conn->prepare("UPDATE user SET signin_perm = ?, activation_date = ?, opened_by = ? WHERE id = ?");
                        $stmt->bind_param("isii", $signin_perm, $activation_date, $opened_by, $id);
                        $stmt->execute();
                        $stmt->close();
                    }
                    
                    header("Location: mbhEmps.php?section=$queryString");
                    exit();
                }
            }
        }
    }
    
    header("Location: mbhEmps.php?error=0");
    exit();
?>