<?php

require_once(__DIR__ . '/../controllers/ItemController.php');
require_once(__DIR__ . '/../controllers/MachineController.php');
require_once(__DIR__ . '/../controllers/RecipeController.php');

Route::add('/', function () {
    $itemController = new ItemController();
    $machineController = new MachineController();
    $recipeController = new RecipeController();
    require(__DIR__ . '/../views/pages/index.php');
});
