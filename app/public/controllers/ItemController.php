<?php

require_once(__DIR__ . '/../dto/ItemDTO.php');
require_once(__DIR__ . '/../services/ItemService.php');
require_once(__DIR__ . '/../models/ItemModel.php');

class ItemController
{
    private ItemModel $itemModel;
    private ItemService $itemService;

    public function __construct()
    {
        $this->itemService = new ItemService();
        $this->itemModel = new ItemModel();

        if ($this->itemService->isTableEmpty()) {
            $this->itemService->loadItemsFromJson($_ENV['INITIAL_DATASET']);
        }
    }

    public function fetchAllProducible(): array
    {
        return $this->itemModel->fetchAllProducible();
    }
}
