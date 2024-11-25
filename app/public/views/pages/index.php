<?php

$error_message = $_SESSION['error_message'] ?? null;

if ($error_message) {
    require(__DIR__ . '/../partials/error.php');
} else {
    require(__DIR__ . '/../partials/header.php');
    require(__DIR__ . '/../partials/login.php');
    require(__DIR__ . '/../partials/homepage_content.php');
    require(__DIR__ . '/../partials/footer.php');
}