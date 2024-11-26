<?php

require_once(__DIR__ . '/../models/MachineModel.php');

class MachineService
{
    private MachineModel $machineModel;

    public function __construct()
    {
        $this->machineModel = new MachineModel();
    }

    /**
     * Determines if the MACHINE table has no records.
     *
     * @return bool True if the table is empty, otherwise false.
     */
    public function isTableEmpty(): bool
    {
        return !$this->machineModel->hasAnyRecords();
    }

    // Load machines from JSON file into the database
    public function loadMachinesFromJson(string $jsonPath): void
    {
        // Read and decode the JSON file
        $jsonContent = file_get_contents($jsonPath);
        $jsonContent = mb_convert_encoding($jsonContent, 'UTF-8', 'UTF-16LE');
        $jsonContent = trim($jsonContent, "\xEF\xBB\xBF");
        $data = json_decode($jsonContent, true);

        if ($data === null) {
            error_log('Error decoding JSON: ' . json_last_error_msg());
            return;
        }

        $validNativeClasses = [
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGBuildableManufacturer'",
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGBuildableManufacturerVariablePower'",
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGBuildableResourceExtractor'"
        ];

        $filteredClasses = [];
        foreach ($data as $item) {
            if (isset($item['NativeClass']) && in_array($item['NativeClass'], $validNativeClasses, true)) {
                $filteredClasses = array_merge($filteredClasses, $item['Classes'] ?? []);
            }
        }

        // Insert data into the database using the model
        foreach ($filteredClasses as $item) {
            $id = $item['ClassName'] ?? null;
            $display_name = $item['mDisplayName'] ?? null;
            $icon_name = str_replace('Build_', '', $id);
            $icon_name = rtrim($icon_name, 'C') . '256.png';

            if ($id && $display_name) {
                $this->machineModel->insertRecord($id, $display_name, $icon_name);
            }
        }
    }
}