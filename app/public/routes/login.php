<?php

require_once(__DIR__ . '/../controllers/UserController.php');

// Login page route
Route::add('/login', function () {
    $loginError = $_SESSION['login_error'] ?? null;
    $loginFormData = $_SESSION['login_form_data'] ?? [];
    $loginUserCreated = $_SESSION['login_user_created'] ?? null;
    unset($_SESSION['login_error'], $_SESSION['login_form_data'], $_SESSION['login_user_created']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize input
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = htmlspecialchars(trim($_POST['password']));

        $userController = new UserController();
        $userController->attemptLogin($email, $password);

        if (http_response_code() === 400) {
            header('Location: /login');
        }
    } else {
        require_once(__DIR__ . '/../views/pages/login.php');
    }
}, ["get", "post"]);