<?php
// controllers/index.php

// All controllers will need the session started.
// This is a candidate for a central bootstrap file later.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in.
if (isset($_SESSION['id'])) {
    $pageTitle = __('dashboard');
    
    // For now, we will just show a simple welcome message.
    // This can be expanded into a full dashboard later.
    include_once 'layout/header.php';
    echo '<div class="container mt-5"><h1>' . __('welcome_back') . '</h1><p>' . __('dashboard_message') . '</p></div>';
    include_once 'layout/footer.php';

} else {
    // If not logged in, redirect to the login page.
    // We use the new clean URL.
    header('Location: /login');
    exit();
}
