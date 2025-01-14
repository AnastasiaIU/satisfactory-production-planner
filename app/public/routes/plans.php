<?php

require_once(__DIR__ . '/../controllers/PlanController.php');

// My plans page route
Route::add('/plans', function () {
    $planError = $_SESSION['plan_error'] ?? null;
    $deletePlanError = $_SESSION['delete_plan_error'] ?? null;
    $updatePlanError = $_SESSION['update_plan_error'] ?? null;
    $planFormData = $_SESSION['plan_form_data'] ?? [];
    unset(
        $_SESSION['plan_error'],
        $_SESSION['delete_plan_error'],
        $_SESSION['update_plan_error'],
        $_SESSION['plan_form_data']
    );

    require_once(__DIR__ . '/../views/pages/plans.php');
});