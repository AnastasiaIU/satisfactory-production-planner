<?php

require_once(__DIR__ . '/../controllers/PlanController.php');

// My plans page route
Route::add('/plans', function () {
    // Check if the user is logged in
    if (!isset($_SESSION['user'])) {
        // If not logged in, redirect to the login page
        header("Location: /login");
        exit();
    }

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