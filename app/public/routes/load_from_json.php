<?php

require_once(__DIR__ . '/../controllers/ItemController.php');
require_once(__DIR__ . '/../controllers/MachineController.php');
require_once(__DIR__ . '/../controllers/RecipeController.php');

// Route for loading items from JSON to the database
Route::add('/loadItemsFromJson', function () {
    $itemController = new ItemController();
    $itemController->loadItemsFromJson();
});

// Route for loading machines from JSON to the database
Route::add('/loadMachinesFromJson', function () {
    $machineController = new MachineController();
    $machineController->loadMachinesFromJson();
});

// Route for loading recipes from JSON to the database
Route::add('/loadRecipesFromJson', function () {
    $recipeController = new RecipeController();
    $recipeController->loadRecipesFromJson();
});

// Route for loading recipe outputs from JSON to the database
Route::add('/loadRecipeOutputsFromJson', function () {
    $recipeController = new RecipeController();
    $recipeController->loadRecipeOutputsFromJson();
});

// Route for loading recipe inputs from JSON to the database
Route::add('/loadRecipeInputsFromJson', function () {
    $recipeController = new RecipeController();
    $recipeController->loadRecipeInputsFromJson();
});