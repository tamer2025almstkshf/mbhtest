<?php
    $owner = 0;
    $admin = 0;
    $ownerid = 0;
    if($_SESSION['id'] == 45){
        if(isset($row_permcheck)){
            $row_permcheck = array_fill_keys(array_keys($row_permcheck), 1);
        }
        if(isset($rowuserid)){
            $rowuserid = array_fill_keys(array_keys($rowuserid), 1);
        }
        $owner = 1;
        $admin = 1;
    } else{
        $golden_id = $_SESSION['id'];
        $stmtgolden = $conn->prepare("SELECT admin FROM user WHERE id=?");
        $stmtgolden->bind_param("i", $golden_id);
        $stmtgolden->execute();
        $resultgolden = $stmtgolden->get_result();
        $rowgolden = $resultgolden->fetch_assoc();
        $stmtgolden->close();
        $admin = (int)$rowgolden['admin'];
        if($admin == 1){
            if(isset($row_permcheck)){
                $row_permcheck = array_fill_keys(array_keys($row_permcheck), 1);
            }
            if(isset($rowuserid)){
                $rowuserid = array_fill_keys(array_keys($rowuserid), 1);
            }
        }
    }
?>