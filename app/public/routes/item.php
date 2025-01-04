<?php

require_once(__DIR__ . '/../controllers/ItemController.php');

// API route for fetching producible items
Route::add('/producibleItems', function () {
    $itemController = new ItemController();
    $items = $itemController->fetchAllProducible();
    echo json_encode($items);
});