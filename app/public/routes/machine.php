<?php

require_once(__DIR__ . '/../controllers/MachineController.php');

// API route for fetching a machine by its ID
Route::add('/getMachine/([a-zA-Z0-9_-]*)', function ($machineId) {
    $machineController = new MachineController();
    $machine = $machineController->getMachine($machineId);
    echo json_encode($machine);
});