<?php

require_once(__DIR__ . '/../controllers/ItemController.php');

// API route for fetching producible items
Route::add('/producibleItems', function () {
    $itemController = new ItemController();
    $items = $itemController->fetchAllProducible();
    echo json_encode($items);
});

// API route for fetching an item by its ID
Route::add('/getItem/([a-zA-Z0-9_-]*)', function ($itemId) {
    $itemController = new ItemController();
    $item = $itemController->getItem($itemId);
    echo json_encode($item);
});