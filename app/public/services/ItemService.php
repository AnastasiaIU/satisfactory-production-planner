<?php

require_once(__DIR__ . '/BaseService.php');
require_once(__DIR__ . '/../models/ItemModel.php');

class ItemService extends BaseService
{
    private ItemModel $itemModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
    }

    public function loadItemsFromJson(string $jsonPath): void
    {
        $data = $this->getJsonContent($jsonPath);

        if ($data === null) {
            error_log('Error decoding JSON: ' . json_last_error_msg());
            return;
        }

        $native_classes = $this->itemModel->getNativeClasses();
        $event_items = $this->itemModel->getEventItems();
        $item_categories = $this->itemModel->getItemCategories();

        foreach ($data as $class) {
            // Process only appropriate native classes
            if (isset($class['NativeClass']) && in_array($class['NativeClass'], $native_classes)) {
                foreach ($class['Classes'] as $item) {
                    $id = $item['ClassName'];

                    // Skip items from the seasonal FICSMAS event
                    if (in_array($id, $event_items)) continue;

                    $display_name = $item['mDisplayName'];
                    $icon_name = $this->processIconName($item['mSmallIcon']);
                    $category = array_key_exists($id, $item_categories) ? $item_categories[$id][0] : 'Uncategorized';
                    $display_order = array_key_exists($id, $item_categories) ? (int)$item_categories[$id][1] : -1;

                    $this->itemModel->insert($id, $display_name, $icon_name, $category, $display_order);
                }
            }
        }
    }

    private function processIconName(string $icon_name): string
    {
        $segments = explode('/', $icon_name);
        $last_segment = end($segments);
        $parts = explode('.', $last_segment);
        return str_replace('IconDesc_', '', $parts[0]) . '.png';
    }
}