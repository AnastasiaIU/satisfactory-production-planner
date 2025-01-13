<?php

require_once(__DIR__ . '/../controllers/UserController.php');

// Registration page route
Route::add('/register', function () {
    $error = $_SESSION['error'] ?? null;
    $formData = $_SESSION['form_data'] ?? [];
    unset($_SESSION['error'], $_SESSION['form_data']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize input
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = htmlspecialchars(trim($_POST['password']));

        $userController = new UserController();
        $userController->registerUser($email, $password);

        if (http_response_code() === 400) {
            header('Location: /register');
        } else {
            header('Location: /');
        }
    } else {
        require_once(__DIR__ . '/../views/pages/register.php');
    }
}, ["get", "post"]);