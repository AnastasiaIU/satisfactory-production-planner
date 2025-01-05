<?php

require_once(__DIR__ . '/BaseService.php');
require_once(__DIR__ . '/../models/UtilityItemModel.php');

/**
 * This class provides services related to recipes, including loading items from a JSON file.
 */
class ItemService extends BaseService
{
    private UtilityItemModel $utilityItemModel;

    public function __construct()
    {
        $this->utilityItemModel = new UtilityItemModel();
    }

    /**
     * Loads items from a JSON file and inserts them into the database.
     *
     * @param string $jsonPath The path to the JSON file.
     * @return void
     */
    public function loadItemsFromJson(string $jsonPath): void
    {
        $data = $this->getJsonContent($jsonPath);

        if ($data === null) {
            error_log('Error decoding JSON: ' . json_last_error_msg());
            return;
        }

        $native_classes = $this->utilityItemModel->getNativeClasses();
        $event_items = $this->utilityItemModel->getEventItems();
        $item_categories = $this->utilityItemModel->getItemCategories();

        foreach ($data as $class) {
            // Process only related native classes
            if (isset($class['NativeClass']) && in_array($class['NativeClass'], $native_classes)) {
                foreach ($class['Classes'] as $item) {
                    $id = $item['ClassName'];

                    // Skip items from the seasonal FICSMAS event
                    if (in_array($id, $event_items)) continue;

                    $display_name = $item['mDisplayName'];
                    $icon_name = $this->processIconName($item['mSmallIcon']);
                    $category = array_key_exists($id, $item_categories) ? $item_categories[$id][0] : 'Uncategorized';
                    $display_order = array_key_exists($id, $item_categories) ? (int)$item_categories[$id][1] : -1;

                    $this->utilityItemModel->insert($id, $display_name, $icon_name, $category, $display_order);
                }
            }
        }
    }

    /**
     * Processes the icon name to generate a standardized icon file name.
     *
     * @param string $icon_name The original icon name.
     * @return string The processed icon file name.
     */
    private function processIconName(string $icon_name): string
    {
        $segments = explode('/', $icon_name);
        $last_segment = end($segments);
        $parts = explode('.', $last_segment);
        return str_replace('IconDesc_', '', $parts[0]) . '.png';
    }
}