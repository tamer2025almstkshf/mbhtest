<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    if(isset($_REQUEST['current_password']) && $_REQUEST['current_password'] !== '' && isset($_REQUEST['new_password']) 
    && $_REQUEST['new_password'] !== '' && isset($_REQUEST['new_password2']) && $_REQUEST['new_password2'] !== ''){
        
        $current_password = stripslashes($_REQUEST['current_password']);
        $current_password = mysqli_real_escape_string($conn, $current_password);

        $id = $_SESSION['id'];
        $query = "SELECT * FROM user WHERE id='$id'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);

        include_once 'AES256.php';
        $db_password = $row['password'];
        $decrypted_password = openssl_decrypt($db_password, $cipher, $key, $options, $iv);

        if($current_password === $decrypted_password){
        
            $new_password = stripslashes($_REQUEST['new_password']);
            $new_password = mysqli_real_escape_string($conn, $new_password);
            
            $new_password2 = stripslashes($_REQUEST['new_password2']);
            $new_password2 = mysqli_real_escape_string($conn, $new_password2);

            if($new_password === $new_password2){
                $encrypted_newPassword = openssl_encrypt($new_password, $cipher, $key, $options, $iv);
                
                $emp_name = $row['name'];
                $action = "غير كلمة مرور حسابه";
                
                $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$id', '$emp_name', '$action')";
                $resutlog = mysqli_query($conn, $querylog);
                
                $queryc = "UPDATE user SET password='$encrypted_newPassword' WHERE id='$id'";
                $resultc = mysqli_query($conn, $queryc);
                if($result){
                    header("Location: change_userPassword.php");
                    exit();
                } else{
                    header("Location: change_userPassword.php?error=unknown");
                    exit();
                }
            } else{
                header("Location: change_userPassword.php?error=unmatched");
                exit();
            }
        } else{
            header("Location: change_userPassword.php?error=notmatched");
            exit();
        }
    } else{
        header("Location: change_userPassword.php");
        exit();
    }