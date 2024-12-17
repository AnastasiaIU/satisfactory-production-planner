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

        $validNativeClasses = [
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGItemDescriptor'",
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGResourceDescriptor'",
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGItemDescriptorBiomass'",
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGItemDescriptorNuclearFuel'",
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGPowerShardDescriptor'",
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGAmmoTypeProjectile'",
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGAmmoTypeSpreadshot'",
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGAmmoTypeInstantHit'",
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGItemDescriptorPowerBoosterFuel'"
        ];

        $filteredClasses = [];
        foreach ($data as $item) {
            if (isset($item['NativeClass']) && in_array($item['NativeClass'], $validNativeClasses, true)) {
                $filteredClasses = array_merge($filteredClasses, $item['Classes'] ?? []);

                $types = array(
                    "/Script/CoreUObject.Class'/Script/FactoryGame.FGResourceDescriptor'" => "Raw resources",
                    "mastercard" => "MSC",
                    "maestro" => "MAE",
                    "amex" => "AMX");
                $type = $types[$item['NativeClass']] ?? "Other";

                foreach ($item['Classes'] as $item) {
                    $id = $item['ClassName'] ?? null;
                    $display_name = $item['mDisplayName'] ?? null;

                    $icon_name = $item['mSmallIcon'] ?? null;
                    $segments = explode('/', $icon_name);
                    $last_segment = end($segments);
                    $parts = explode('.', $last_segment);
                    $icon_name = str_replace('IconDesc_', '', $parts[0]) . '.png';

                    if ($id && $display_name && $icon_name && $type) {
                        $this->itemModel->insertRecord($id, $display_name, $icon_name, $type);
                    }
                }
            }
        }
    }
}