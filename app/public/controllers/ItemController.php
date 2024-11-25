<?php

require_once(__DIR__ . '/../models/ItemModel.php');
require_once(__DIR__ . '/../dto/ItemDTO.php');
require_once(__DIR__ . '/../services/ItemService.php');

class ItemController
{
    private ItemModel $itemModel;
    private ItemService $itemService;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->itemService = new ItemService();

        if ($this->itemService->isTableEmpty()) {
            $this->itemService->loadItemsFromJson($_ENV['INITIAL_DATASET']);
        }
    }
}