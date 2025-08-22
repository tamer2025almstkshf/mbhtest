<?php
    include_once 'connection.php';
    if(isset($_REQUEST['username']) && $_REQUEST['username'] !== '' && isset($_REQUEST['password']) && $_REQUEST['password'] !== ''){
        $username = stripslashes($_REQUEST['username']);
        $username = mysqli_real_escape_string($conn, $username);
        
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($conn, $password);
        
        $remember_me = isset($_POST['remember_me']);
        
        $query = "SELECT * FROM user WHERE username = '$username'";
        $result = mysqli_query($conn, $query);
        
        if($result->num_rows > 0){
            $row = mysqli_fetch_array($result);
            $dbpassword = $row['password'];
            include_once 'AES256.php';
            $decrypted_password = openssl_decrypt($dbpassword, $cipher, $key, $options, $iv);
            if($decrypted_password === $password){
                session_start();
                $_SESSION['id'] = $row['id'];
                $id = $_SESSION['id'];
                
                $_SESSION['username'] = $username;
        
                if ($remember_me) {
                    setcookie('username', $username, time() + (86400 * 30), "/"); // 30 days expiration
                    setcookie('password', $password, time() + (86400 * 30), "/"); // 30 days expiration
                } else {
                    setcookie('username', '', time() - 3600, "/");
                    setcookie('password', '', time() - 3600, "/");
                }
                
                $timestamp = date('A H:i:s d/m/Y');
                
                if(isset($row['logins_num']) && $row['logins_num'] !== ''){
                    $logins_num = $row['logins_num'];
                }
                if(!isset($row['logins_num'])){
                    $logins_num = 0;
                }
                if($row['logins_num'] === ''){
                    $logins_num = 0;
                }
                $logins_num++;
                
                $query_upd = "UPDATE user SET logins_num = '$logins_num', lastlogin = '$timestamp' WHERE id = '$id'";
                $result_upd = mysqli_query($conn, $query_upd);
                
                header("Location: index.php");
                exit();
            } else{
                header("Location: login_emp.php?error=wrong");
            }
        } else{
            header("Location: login_emp.php?error=wrong");
        }
    } else{
        header("Location: login_emp.php?error=0");
    }
?>