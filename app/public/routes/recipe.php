<?php

require_once(__DIR__ . '/../controllers/RecipeController.php');

// API route for fetching a standard recipe for an item
Route::add('/getRecipeForItem/([a-zA-Z0-9_-]*)', function ($itemId) {
    $recipeController = new RecipeController();
    $recipe = $recipeController->getRecipeForItem($itemId);
    echo json_encode($recipe);
});