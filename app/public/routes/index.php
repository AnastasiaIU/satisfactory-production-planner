<?php

require_once(__DIR__ . '/../controllers/ItemController.php');
require_once(__DIR__ . '/../controllers/MachineController.php');

Route::add('/', function () {
    $itemController = new ItemController();
    $machineController = new MachineController();
    require(__DIR__ . '/../views/pages/index.php');
});
