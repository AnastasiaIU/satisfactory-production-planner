<?php

require_once(__DIR__ . '/../controllers/ItemController.php');
require_once(__DIR__ . '/../controllers/MachineController.php');
require_once(__DIR__ . '/../controllers/RecipeController.php');

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

    if (isset($_GET['item_id'])) {
        $result = $recipeController->getRecipeDetails($_GET['item_id']);
        header('Content-Type: application/json');
    }
    require(__DIR__ . '/../views/pages/index.php');
});

// API route for fetching recipe details
Route::add('/getRecipeDetails', function () {
    if (!isset($_GET['item_id'])) {
        http_response_code(400); // Bad request if item_id is missing
        echo json_encode(["error" => "Item ID is required"]);
        exit;
    }

    $recipeController = new RecipeController();
    $result = $recipeController->getRecipeDetails($_GET['item_id']);

    if ($result) {
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404); // Not found if no recipe is found
        echo json_encode(["error" => "Recipe not found"]);
    }
});

// API route for fetching recipe outputs
Route::add('/getRecipeOutputs', function () {
    if (!isset($_GET['recipe_id'])) {
        http_response_code(400); // Bad request if recipe_id is missing
        echo json_encode(["error" => "Recipe ID is required"]);
        exit;
    }

    $recipeController = new RecipeController();
    $result = $recipeController->getRecipeOutputs($_GET['recipe_id']);

    if ($result) {
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404); // Not found if no recipe is found
        echo json_encode(["error" => "Recipe outputs not found"]);
    }
});

// API route for fetching recipe inputs
Route::add('/getRecipeInputs', function () {
    if (!isset($_GET['recipe_id'])) {
        http_response_code(400); // Bad request if recipe_id is missing
        echo json_encode(["error" => "Recipe ID is required"]);
        exit;
    }

    $recipeController = new RecipeController();
    $result = $recipeController->getRecipeInputs($_GET['recipe_id']);

    if ($result) {
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404); // Not found if no recipe is found
        echo json_encode(["error" => "Recipe inputs not found"]);
    }
});