<?php

require_once(__DIR__ . '/../models/RecipeModel.php');

class RecipeService
{
    private RecipeModel $recipeModel;
    private $ficsmas = [
        'Recipe_XmasBall1_C',
        'Recipe_XmasBall2_C',
        'Recipe_XmasBall3_C',
        'Recipe_XmasBall4_C',
        'Recipe_XmasBallCluster_C',
        'Recipe_XmasBow_C',
        'Recipe_XmasBranch_C',
        'Recipe_XmasStar_C',
        'Recipe_XmasWreath_C',
        'Recipe_CandyCane_C',
        'Recipe_Snow_C',
        'Recipe_Snowball_C',
        'Recipe_Fireworks_01_C',
        'Recipe_Fireworks_02_C',
        'Recipe_Fireworks_03_C'
    ];

    public function __construct()
    {
        $this->recipeModel = new RecipeModel();
    }

    public function isTableEmpty(string $tableName): bool
    {
        return !$this->recipeModel->hasAnyRecords($tableName);
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
            if (in_array($id, $this->ficsmas)) continue;

            $produced_in = $item['mProducedIn'] ?? null;
            $matches = [];
            preg_match_all('/Build_[^.]+_C/', $produced_in, $matches);
            $filteredMachines = array_filter($matches[0], function ($machine) {
                return stripos($machine, 'WorkBench') === false;
            });
            // Skip this recipe if no valid machines are found
            if (empty($filteredMachines)) {
                continue;
            }
            // Get the valid machine
            $produced_in = array_values($filteredMachines)[0];

            $display_name = $item['mDisplayName'] ?? null;
            $is_alternative = str_contains($id, 'Recipe_Alternate_');

            if ($id && $produced_in && $display_name) {
                // Insert the valid recipe into the database
                $this->recipeModel->insertRecipe($id, $produced_in, $display_name, $is_alternative);
            }
        }
    }

    public function loadRecipeOutputsFromJson(string $jsonPath): void
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
            $produced_in = $item['mProducedIn'] ?? null;
            $matches = [];
            preg_match_all('/Build_[^.]+_C/', $produced_in, $matches);
            $filteredMachines = array_filter($matches[0], function ($machine) {
                return stripos($machine, 'WorkBench') === false;
            });

            // Skip this recipe if no valid machines are found
            if (empty($filteredMachines)) {
                continue;
            }

            $recipe_id = $item['ClassName'] ?? null;
            if (in_array($recipe_id, $this->ficsmas)) continue;

            $products = $item['mProduct'] ?? null;

            if ($recipe_id && $products) {
                $productBlocks = [];
                preg_match_all('/\((.*?)\)/', $products, $productBlocks);

                if (empty($productBlocks[0])) {
                    error_log("No valid product blocks found for Recipe ID $recipe_id.");
                    continue;
                }

                foreach ($productBlocks[0] as $product) {
                    $productPair = [];
                    preg_match('/\(ItemClass="[^"]+(Desc_[^"]+_C)\'",Amount=(\d+)\)/', $product, $productPair);

                    if (!empty($productPair[0])) {
                        $item_id = $productPair[1];
                        $amount = (int)$productPair[2];

                        $this->recipeModel->insertRecipeOutput($recipe_id, $item_id, $amount);
                    } else {
                        error_log("No valid product pairs found for Recipe ID $recipe_id: " . $product);
                    }
                }
            }
        }
    }

    public function loadRecipeInputsFromJson(string $jsonPath): void
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
            $produced_in = $item['mProducedIn'] ?? null;
            $matches = [];
            preg_match_all('/Build_[^.]+_C/', $produced_in, $matches);
            $filteredMachines = array_filter($matches[0], function ($machine) {
                return stripos($machine, 'WorkBench') === false;
            });

            // Skip this recipe if no valid machines are found
            if (empty($filteredMachines)) {
                continue;
            }

            $recipe_id = $item['ClassName'] ?? null;
            if (in_array($recipe_id, $this->ficsmas)) continue;

            $ingredients = $item['mIngredients'] ?? null;

            if ($recipe_id && $ingredients) {
                $ingredientBlocks = [];
                preg_match_all('/\((.*?)\)/', $ingredients, $ingredientBlocks);

                if (empty($ingredientBlocks[0])) {
                    error_log("No valid ingredient blocks found for Recipe ID $recipe_id.");
                    continue;
                }

                foreach ($ingredientBlocks[0] as $ingredient) {
                    $ingredientPair = [];
                    preg_match('/\(ItemClass="[^"]+(Desc_[^"]+_C)\'",Amount=(\d+)\)/', $ingredient, $ingredientPair);

                    if (!empty($ingredientPair[0])) {
                        $item_id = $ingredientPair[1];
                        $amount = (int)$ingredientPair[2];

                        $this->recipeModel->insertRecipeInput($recipe_id, $item_id, $amount);
                    } else {
                        error_log("No valid ingredient pairs found for Recipe ID $recipe_id: " . $ingredient);
                    }
                }
            }
        }
    }
}