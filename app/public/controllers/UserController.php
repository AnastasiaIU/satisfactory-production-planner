<?php

require_once(__DIR__ . '/../models/UserModel.php');
require_once(__DIR__ . '/../dto/UserDTO.php');

/**
 * Controller class for handling user-related operations.
 */
class UserController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Registers a new user.
     *
     * @param string $email The email of the user to register.
     * @param string $password The password of the user to register.
     */
    public function registerUser(string $email, string $password): void
    {
        // Retrieve the user by email
        $user = $this->userModel->getUser($email);

        // Check if the user already exists
        if ($user !== null) {
            // Set error message and form data in session
            $_SESSION['error'] = 'User already exists. Please use another email.';
            $_SESSION['form_data'] = ['email' => $email];
            http_response_code(400);
        } else {
            // Hash the password and save the user to the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->userModel->createUser($email, $hashedPassword);

            $_SESSION['login_user_created'] = 'User created successfully. Please log in.';
            header('Location: /login');
        }
    }

    /**
     * Attempts to log in a user with the provided email and password.
     *
     * @param string $email The email of the user attempting to log in.
     * @param string $password The password of the user attempting to log in.
     */
    public function attemptLogin(string $email, string $password): void
    {
        // Retrieve the user by email
        $user = $this->userModel->getUser($email);

        // Check if the user exists
        if ($user === null) {
            $this->setErrorMessageInSession($email, $password);
        } else {
            if ($user->verifyPassword($password)) {
                // Set logged-in user in session and redirect to home page
                $_SESSION['user'] = $user->id;
                header('Location: /');
            } else {
                $this->setErrorMessageInSession($email, $password);
            }
        }
    }

    /**
     * Sets an error message and form data in the session.
     *
     * @param string $email The email of the user.
     * @param string $password The password of the user.
     */
    public function setErrorMessageInSession(string $email, string $password): void
    {
        $_SESSION['login_error'] = 'Wrong email or password. Please, try again.';
        $_SESSION['login_form_data'] = ['email' => $email, 'password' => $password];
        http_response_code(400);
    }
}