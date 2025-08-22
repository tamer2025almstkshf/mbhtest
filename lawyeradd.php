<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['emp_perms_add'] === '1'){
        if(isset($_REQUEST['name']) && $_REQUEST['name'] !== '' && isset($_REQUEST['tel_no']) && $_REQUEST['tel_no'] !== ''){
            $id = stripslashes($_REQUEST['id']);
            $id = mysqli_real_escape_string($conn, $id);
            
            $name = stripslashes($_REQUEST['name']);
            $name = mysqli_real_escape_string($conn, $name);
            
            $tel_no = stripslashes($_REQUEST['tel_no']);
            $tel_no = mysqli_real_escape_string($conn, $tel_no);
            
            $about = stripslashes($_REQUEST['about']);
            $about = mysqli_real_escape_string($conn, $about);
            
            $targetDir = "files_images/lawyers/$id";
            
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $attachment1 = '';
            if (isset($_FILES['attachment1']) && $_FILES['attachment1']['error'] == 0) {
                $attachment1 = $targetDir . "/" . basename($_FILES['attachment1']['name']);
                if (move_uploaded_file($_FILES['attachment1']['tmp_name'], $attachment1)) {
                    echo "attachment1 1 has been uploaded.<br>";
                }
            }
            
            $attachment2 = '';
            if (isset($_FILES['attachment2']) && $_FILES['attachment2']['error'] == 0) {
                $attachment2 = $targetDir . "/" . basename($_FILES['attachment2']['name']);
                if (move_uploaded_file($_FILES['attachment2']['tmp_name'], $attachment2)) {
                    echo "Photo 2 has been uploaded.<br>";
                }
            }
            
            $attachment3 = '';
            if (isset($_FILES['attachment3']) && $_FILES['attachment3']['error'] == 0) {
                $attachment3 = $targetDir . "/" . basename($_FILES['attachment3']['name']);
                if (move_uploaded_file($_FILES['attachment3']['tmp_name'], $attachment3)) {
                    echo "attachment3 1 has been uploaded.<br>";
                }
            }
            
            $attachment4 = '';
            if (isset($_FILES['attachment4']) && $_FILES['attachment4']['error'] == 0) {
                $attachment4 = $targetDir . "/" . basename($_FILES['attachment4']['name']);
                if (move_uploaded_file($_FILES['attachment4']['tmp_name'], $attachment4)) {
                    echo "Photo 2 has been uploaded.<br>";
                }
            }
            
            $attachment5 = '';
            if (isset($_FILES['attachment5']) && $_FILES['attachment5']['error'] == 0) {
                $attachment5 = $targetDir . "/" . basename($_FILES['attachment5']['name']);
                if (move_uploaded_file($_FILES['attachment5']['tmp_name'], $attachment5)) {
                    echo "attachment5 1 has been uploaded.<br>";
                }
            }
            
            $attachment6 = '';
            if (isset($_FILES['attachment6']) && $_FILES['attachment6']['error'] == 0) {
                $attachment6 = $targetDir . "/" . basename($_FILES['attachment6']['name']);
                if (move_uploaded_file($_FILES['attachment6']['tmp_name'], $attachment6)) {
                    echo "Photo 2 has been uploaded.<br>";
                }
            }
            
            $timestamp = date('Y/m/d');
            
            $query = "UPDATE lawyers SET name='$name', tel_no='$tel_no', about='$about', attachment1='$attachment1',
            attachment2='$attachment2', attachment3='$attachment3', attachment4='$attachment4',
            attachment5='$attachment5', attachment6='$attachment6', timestamp='$timestamp' WHERE id='$id'";
            $result = mysqli_query($conn, $query);
            
            $submit_back = stripslashes($_REQUEST['submit_back']);
            $submit_back = mysqli_real_escape_string($conn, $submit_back);
            
            if($submit_back === 'addmore'){
                header("Location: Lawyers.php?lawyersaved=1&addmore=1");
            } else{
                header("Location: Lawyers.php?lawyersaved=1");
            }
            exit();
        } else{
            header("Location:Lawyers.php?error=0");
            exit();
        }
    }
    header("Location: Lawyers.php");
    exit();
?>