<?php
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        die("Not logged in.");
    }
    
    $id = intval($_SESSION['id']);
    $stmtmain = $conn->prepare("SELECT * FROM user WHERE id = ?");
    $stmtmain->bind_param("i", $id);
    $stmtmain->execute();
    $resultmain = $stmtmain->get_result();
    
    $myid = intval($_SESSION['id']);
    $stmt_permcheck = $conn->prepare("SELECT * FROM user WHERE id = ?");
    $stmt_permcheck->bind_param("i", $myid);
    $stmt_permcheck->execute();
    $result_permcheck = $stmt_permcheck->get_result();
    
    if ($resultmain->num_rows > 0) {
        $rowmain = $resultmain->fetch_assoc();
        $row_permcheck = $result_permcheck->fetch_assoc();
    } else {
        die("User not found.");
    }
?>