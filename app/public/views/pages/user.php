<?php

$error_message = $_SESSION['error_message'] ?? null;

if ($error_message) {
    require(__DIR__ . '/../partials/error.php');
} else {
    require(__DIR__ . '/../partials/header.php');
    require(__DIR__ . '/../partials/user.php');
    require(__DIR__ . '/../partials/footer.php');
}