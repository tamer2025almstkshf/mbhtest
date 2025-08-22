<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $vacaction = stripslashes($_REQUEST['action']);
    $vacaction = mysqli_real_escape_string($conn, $vacaction);
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    $id = stripslashes($_REQUEST['id']);
    $id = mysqli_real_escape_string($conn, $id);
    
    $type = stripslashes($_REQUEST['type']);
    $type = mysqli_real_escape_string($conn, $type);
    
    if(isset($_REQUEST['vactype']) && $_REQUEST['vactype'] !== '' && isset($_REQUEST['vac']) && $_REQUEST['vac'] !== '' && isset($_REQUEST['vacfrom']) && $_REQUEST['vacfrom'] !== '' && isset($_REQUEST['vacto']) && $_REQUEST['vacto'] !== ''){
        $vac = stripslashes($_REQUEST['vac']);
        $vac = mysqli_real_escape_string($conn, $vac);
        
        $vactype = stripslashes($_REQUEST['vactype']);
        $vactype = mysqli_real_escape_string($conn, $vactype);
        
        $ask_date = stripslashes($_REQUEST['ask_date']);
        $ask_date = mysqli_real_escape_string($conn, $ask_date);
        
        $vacfrom = stripslashes($_REQUEST['vacfrom']);
        $vacfrom = mysqli_real_escape_string($conn, $vacfrom);
        
        $vacto = stripslashes($_REQUEST['vacto']);
        $vacto = mysqli_real_escape_string($conn, $vacto);
        
        $targetDir = "files_images/vacations/$id";
        
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $certificate = '';
        if (isset($_FILES['certificate']) && $_FILES['certificate']['error'] == 0) {
            $certificate = $targetDir . "/" . basename($_FILES['certificate']['name']);
            if (move_uploaded_file($_FILES['certificate']['tmp_name'], $certificate)) {
                echo "certificate 1 has been uploaded.<br>";
            }
        }
        
        $notes = stripslashes($_REQUEST['notes']);
        $notes = mysqli_real_escape_string($conn, $notes);
        
        if($vacaction === 'ask'){
            $ask = '1';
            $ask2 = '0';
        } else if($vacaction === 'give'){
            if($row_permcheck['emp_aperm'] === '1'){
                $ask = '3';
                $ask2 = '3';
                if($id === $myid){
                    $ask = '1';
                    $ask2 = '0';
                }
            } else{
                $ask = '1';
                $ask2 = '0';
            }
        }
        
        $query = "INSERT INTO vacations(emp_id, type, vactype, ask_date, starting_date, ending_date, certificate, ask, ask2, notes) VALUES ('$id', '$vac', '$vactype', '$ask_date', '$vacfrom', '$vacto', '$certificate', '$ask', '$ask2', '$notes')";
        $result = mysqli_query($conn, $query);
        
        header("Location: vacationsBalance.php?id=$id&type=$type&success=1");
        exit();
    } else{
        header("Location: vacationsBalance.php?id=$id&type=$type&error=0");
        exit();
    }