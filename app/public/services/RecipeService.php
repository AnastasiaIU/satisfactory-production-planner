<?php

require_once(__DIR__ . '/../models/RecipeModel.php');

class RecipeService
{
    private RecipeModel $recipeModel;

    public function __construct()
    {
        $this->recipeModel = new RecipeModel();
    }

    public function isTableEmpty(): bool
    {
        return !$this->recipeModel->hasAnyRecords();
    }

    public function loadRecipesFromJson(string $jsonPath): void
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
            if (isset($item['NativeClass']) && $item['NativeClass'] === "/Script/CoreUObject.Class'/Script/FactoryGame.FGRecipe'") {
                $filteredClasses = $item['Classes'] ?? [];
                break;
            }
        }

        foreach ($filteredClasses as $item) {
            $id = $item['ClassName'] ?? null;
            $produced_in = $item['mProducedIn'] ?? null;
            $display_name = $item['mDisplayName'] ?? null;

            if ($id && $produced_in && $display_name) {
                $matches = [];
                preg_match_all('/Build_[^.]+_C/', $produced_in, $matches);
                $filteredMachines = array_filter($matches[0], function ($machine) {
                    return stripos($machine, 'WorkBench') === false;
                });
                $produced_in = $filteredMachines ? array_values($filteredMachines)[0] : null;

                // Skip this recipe if no valid machines are found
                if (empty($filteredMachines)) {
                    continue;
                }

                // Get the first valid machine
                $produced_in = array_values($filteredMachines)[0];

                // Insert the valid recipe into the database
                $this->recipeModel->insertRecord($id, $produced_in, $display_name);
            }
        }
    }
}