<?php

/**
 * Set env variables and enable error reporting in local environment.
 */
require_once(__DIR__ . '/lib/env.php');
require_once(__DIR__ . '/lib/error_reporting.php');

/**
 * Enable the ErrorHandler class for using the error and exception handlers.
 */
require_once(__DIR__ . '/utils/ErrorHandler.php');
ErrorHandler::register();

/**
 * Start user session.
 */
session_start();

/**
 * Require routing library.
 */
require_once(__DIR__ . '/lib/Route.php');

/**
 * Require routes.
 */
require_once(__DIR__ . '/routes/index.php');
require_once(__DIR__ . '/routes/load_from_json.php');
require_once(__DIR__ . '/routes/item.php');
require_once(__DIR__ . '/routes/recipe.php');

// run router
Route::run();