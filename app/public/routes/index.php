<?php

require_once(__DIR__ . '/../controllers/ItemController.php');
require_once(__DIR__ . '/../controllers/MachineController.php');
require_once(__DIR__ . '/../controllers/RecipeController.php');
require_once(__DIR__ . '/../controllers/PlanController.php');

// Main page route
Route::add('/', function () {
    $itemController = new ItemController();
    $machineController = new MachineController();
    $recipeController = new RecipeController();

    // Load data from JSON if the tables are empty
    if (!$itemController->isTableEmpty()) file_get_contents($_ENV['BASE_URL'] . '/loadItemsFromJson');
    if (!$machineController->isTableEmpty()) file_get_contents($_ENV['BASE_URL'] . '/loadMachinesFromJson');
    if (!$recipeController->isRecipeTableEmpty()) file_get_contents($_ENV['BASE_URL'] . '/loadRecipesFromJson');
    if (!$recipeController->isRecipeOutputTableEmpty()) file_get_contents($_ENV['BASE_URL'] . '/loadRecipeOutputsFromJson');
    if (!$recipeController->isRecipeInputTableEmpty()) file_get_contents($_ENV['BASE_URL'] . '/loadRecipeInputsFromJson');

    $planError = $_SESSION['plan_error'] ?? null;
    $planName = $_SESSION['plan_name'] ?? null;
    $planFormData = $_SESSION['plan_form_data'] ?? [];
    unset($_SESSION['plan_error'], $_SESSION['plan_name'], $_SESSION['plan_form_data']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize input
        $name = '';
        $items = [];
        foreach ($_POST as $key => $value) {
            if ($key === 'planName') {
                $name = htmlspecialchars(trim($value));
            } else {
                $items[$key] = htmlspecialchars(trim($value));
            }
        }

        $planController = new PlanController();
        $planController->createProductionPlan($_SESSION['user'], $name, $items);

        if (http_response_code() === 400 || http_response_code() === 500) {
            header('Location: /');
        }
    } else {
        require(__DIR__ . '/../views/pages/index.php');
    }
}, ["get", "post"]);