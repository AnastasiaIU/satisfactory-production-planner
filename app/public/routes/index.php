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
    $plan = $_SESSION['plan'] ?? null;
    unset($_SESSION['plan_error'], $_SESSION['plan']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize input
        $planId = null;
        $name = '';
        $items = [];
        foreach ($_POST as $key => $value) {
            $name = $key === 'planName' ? htmlspecialchars(trim($value)) : $name;
            $planId = $key === 'createPlanId' ? htmlspecialchars(trim($value)) : $planId;
            if ($key !== 'planName' && $key !== 'createPlanId') {
                $items[$key] = htmlspecialchars(trim($value));
            }
        }

        $planController = new PlanController();

        if ($planId) {
            $planController->updateProductionPlan($planId, $name, $items);
        } else {
            $planController->createProductionPlan($_SESSION['user'], $name, $items);
        }

        if (http_response_code() === 500) {
            header('Location: /');
        }
    } else {
        require(__DIR__ . '/../views/pages/index.php');
    }
}, ["get", "post"]);