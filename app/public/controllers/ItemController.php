<?php

require_once(__DIR__ . '/BaseController.php');
require_once(__DIR__ . '/../models/ItemModel.php');
require_once(__DIR__ . '/../services/ItemService.php');

class ItemController extends BaseController
{
    private ItemModel $itemModel;
    private ItemService $itemService;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->itemService = new ItemService();

        if (!$this->itemModel->hasAnyRecords()) $this->itemService->loadItemsFromJson($this::INITIAL_DATASET);
    }

    public function insert(
        string $id, string $display_name, string $icon_name, string $category, int $display_order
    ): void
    {
        $this->itemModel->insert($id, $display_name, $icon_name, $category, $display_order);
    }

    public function fetchAllProducible(): array
    {
        return $this->itemModel->fetchAllProducible();
    }

    public function getNativeClasses(): array
    {
        return $this->itemModel->getNativeClasses();
    }

    public function getEventItems(): array
    {
        return $this->itemModel->getEventItems();
    }

    public function getItemCategories(): array
    {
        return $this->itemModel->getItemCategories();
    }
}
