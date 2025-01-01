<?php

require_once(__DIR__ . '/BaseController.php');
require_once(__DIR__ . '/../models/ItemModel.php');
require_once(__DIR__ . '/../services/ItemService.php');

/**
 * Controller class for handling item-related operations.
 */
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

    /**
     * Fetches all producible items from the database.
     *
     * @return array An array of producible items.
     */
    public function fetchAllProducible(): array
    {
        return $this->itemModel->fetchAllProducible();
    }
}
