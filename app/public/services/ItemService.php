<?php

require_once(__DIR__ . '/../models/ItemModel.php');

class ItemService
{
    private ItemModel $itemModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
    }

    public function isTableEmpty(): bool
    {
        return !$this->itemModel->hasAnyRecords();
    }

    public function loadItemsFromJson(string $jsonPath): void
    {
        $jsonContent = file_get_contents($jsonPath);
        $jsonContent = mb_convert_encoding($jsonContent, 'UTF-8', 'UTF-16LE');
        $jsonContent = trim($jsonContent, "\xEF\xBB\xBF");
        $data = json_decode($jsonContent, true);

        if ($data === null) {
            error_log('Error decoding JSON: ' . json_last_error_msg());
            return;
        }

        $filteredClasses = [];
        foreach ($data as $item) {
            if (isset($item['NativeClass']) && $item['NativeClass'] === "/Script/CoreUObject.Class'/Script/FactoryGame.FGItemDescriptor'") {
                $filteredClasses = $item['Classes'] ?? [];
                break;
            }
        }

        foreach ($filteredClasses as $item) {
            $id = $item['ClassName'] ?? null;
            $display_name = $item['mDisplayName'] ?? null;
            $icon_name = $item['mSmallIcon'] ?? null;

            if ($id && $display_name && $icon_name) {
                $segments = explode('/', $icon_name);
                $last_segment = end($segments);
                $parts = explode('.', $last_segment);
                $icon_name = str_replace('IconDesc_', '', $parts[0]) . '.png';

                $this->itemModel->insertRecord($id, $display_name, $icon_name);
            }
        }
    }
}