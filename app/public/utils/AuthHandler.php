<?php

/**
 * Class AuthHandler for handling user authentication.
 */
class AuthHandler
{
    /**
     * Checks if the user is logged in.
     *
     * This method verifies if the user session is set. If the user is not logged in,
     * it redirects to the login page and terminates the script execution.
     */
    public static function checkUserLoggedIn(): void
    {
        if (!isset($_SESSION['user'])) {
            // If not logged in, redirect to the login page
            header("Location: /login");
            exit();
        }
    }
}