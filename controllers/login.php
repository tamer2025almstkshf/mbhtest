<?php
// controllers/login.php

// If the user is already logged in, redirect them to the homepage.
if (isset($_SESSION['id'])) {
    header('Location: /');
    exit();
}

// In a real application, the POST logic for handling the login would go here.
// For now, we will just display the login form.

$pageTitle = 'Login';
// The original login.php was a full HTML page. We will render a view instead.
// For simplicity in this step, we will just include the original file.
// A full refactor would involve creating a login.view.php.
include_once 'login.php';
