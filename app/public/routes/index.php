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

    require(__DIR__ . '/../views/pages/index.php');
});