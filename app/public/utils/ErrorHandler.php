<?php

namespace utils;

/**
 * Handles exceptions and errors by logging them and displaying a custom error page.
 */
class ErrorHandler
{
    /**
     * Displays the custom error page.
     */
    private static function displayErrorPage(): void
    {
        include(__DIR__ . '/../views/partials/header.php');
        include(__DIR__ . '/../views/partials/error.php');
    }

    /**
     * Handles uncaught exceptions.
     * Logs the exception message, sets the HTTP response code to 500, and displays the error page.
     *
     * @param \Throwable $exception The exception that was thrown.
     */
    public static function handleException(\Throwable $exception): void
    {
        error_log($exception->getMessage());
        http_response_code(500);
        self::displayErrorPage();
    }

    /**
     * Handles PHP errors.
     * Logs the error details, sets the HTTP response code to 500, and displays the error page.
     *
     * @param int $errno The level of the error raised.
     * @param string $errstr The error message.
     * @param string $errfile The filename that the error was raised in.
     * @param int $errline The line number the error was raised at.
     */
    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): void
    {
        error_log("Error [$errno]: $errstr in $errfile on line $errline");
        http_response_code(500);
        self::displayErrorPage();
    }

    /**
     * Registers the custom exception and error handlers.
     */
    public static function register(): void
    {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
    }
}