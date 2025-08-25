<?php
require_once __DIR__ . '/bootstrap.php';

use App\I18n;

if (isset($_POST['lang'])) {
    $lang = $_POST['lang'];
    if ($lang === 'en' || $lang === 'ar') {
        I18n::setLocale($lang);
    }
}

// Redirect back to the previous page
if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    // Fallback to the homepage if the referrer is not available
    header("Location: index.php");
}
exit;
