<?php

require_once(__DIR__ . '/../utils/AuthHandler.php');
require_once(__DIR__ . '/../controllers/PlanController.php');

// My plans page route
Route::add('/plans', function () {
    AuthHandler::checkUserLoggedIn();

    $planError = $_SESSION['plan_error'] ?? null;
    unset($_SESSION['plan_error']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deletePlanId'])) {
        $planId = filter_input(INPUT_POST, 'deletePlanId', FILTER_SANITIZE_NUMBER_INT);

        $planController = new PlanController();
        $planController->deleteProductionPlan($planId);

        header("Location: /plans");
    }

    require_once(__DIR__ . '/../views/pages/plans.php');
}, ["get", "post"]);

// API route for fetching production plans by user ID
Route::add('/getPlans/([a-zA-Z0-9_-]*)', function ($userId) {
    $planController = new PlanController();
    $plans = $planController->fetchAllPlans($userId);
    echo json_encode($plans);
});

// Route for passing a plan to the main page
Route::add('/plan/([a-zA-Z0-9_-]*)', function ($planId) {
    AuthHandler::checkUserLoggedIn();

    $planController = new PlanController();
    $plan = $planController->getProductionPlan($planId);

    if (!$plan || $plan->created_by !== $_SESSION['user']) {
        $_SESSION['plan_error'] = 'Plan not found.';
        header("Location: /");
        exit();
    }

    $_SESSION['plan'] = $plan;
    header("Location: /");
});

// API route for fetching the production plan by its ID
Route::add('/getPlan/([a-zA-Z0-9_-]*)', function ($planId) {
    $planController = new PlanController();
    $plan = $planController->getProductionPlan($planId);
    echo json_encode($plan);
});

// API route for importing a production plan
Route::add('/importPlan', function () {
    AuthHandler::checkUserLoggedIn();

    $plan = json_decode(file_get_contents('php://input'), true);

    if ($plan) {
        $planController = new PlanController();
        $planController->createProductionPlan($plan['created_by'], $plan['display_name'], $plan['items']);
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    }
}, ['post']);

// API route for logging import errors
Route::add('/logImportError', function () {
    AuthHandler::checkUserLoggedIn();

    $errorData = json_decode(file_get_contents('php://input'), true);

    if (isset($errorData['error'])) {
        $_SESSION['plan_error'] = $errorData['error'];
    }
}, ['post']);