<?php
// Start session
session_start();

$_SESSION = array();

session_destroy();

header("Location: login_emp.php");
exit;
?>