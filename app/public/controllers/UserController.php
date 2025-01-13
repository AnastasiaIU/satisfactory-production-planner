<?php

require_once(__DIR__ . '/BaseController.php');
require_once(__DIR__ . '/../models/UserModel.php');
require_once(__DIR__ . '/../dto/UserDTO.php');

/**
 * Controller class for handling user-related operations.
 */
class UserController extends BaseController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Retrieves a user by their email.
     *
     * @param string $email The email of the user to retrieve.
     * @return UserDTO|null The data transfer object representing the user or null if the user is not found.
     */
    public function getUser(string $email): ?UserDTO
    {
        return $this->userModel->getUser($email);
    }

    /**
     * Creates a new user in the database.
     *
     * @param string $email The email of the user to create.
     * @param string $password The password of the user to create.
     */
    public function createUser(string $email, string $password): void {
        $this->userModel->createUser($email, $password);
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
        $user = $this->getUser($email);

        // Check if the user already exists
        if ($user !== null) {
            // Set error message and form data in session
            $_SESSION['error'] = 'User already exists. Please use another email.';
            $_SESSION['form_data'] = ['email' => $email];
            http_response_code(400);
        } else {
            // Hash the password and save the user to the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->createUser($email, $hashedPassword);

            // Set logged-in user in session and redirect to home page
            $_SESSION['user'] = $user;
            header('Location: /');
        }
    }
}