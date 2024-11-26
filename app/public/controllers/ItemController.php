<?php

require_once(__DIR__ . '/../dto/ItemDTO.php');
require_once(__DIR__ . '/../services/ItemService.php');

class ItemController
{
    private ItemService $itemService;

    public function __construct()
    {
        $this->itemService = new ItemService();

        if ($this->itemService->isTableEmpty()) {
            $this->itemService->loadItemsFromJson($_ENV['INITIAL_DATASET']);
        }
    }
}
