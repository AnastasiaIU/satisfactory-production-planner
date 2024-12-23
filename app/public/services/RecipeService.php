<?php

require_once(__DIR__ . '/../models/RecipeModel.php');

class RecipeService
{
    private RecipeModel $recipeModel;
    private array $ficsmas = [
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

    private array $resource_recipes = [
        ['Recipe_IronOre_C', 'Build_MinerMk1_C', 'Iron Ore'],
        ['Recipe_CopperOre_C', 'Build_MinerMk1_C', 'Copper Ore'],
        ['Recipe_LimestoneOre_C', 'Build_MinerMk1_C', 'Limestone'],
        ['Recipe_Coal_C', 'Build_MinerMk1_C', 'Coal'],
        ['Recipe_Water_C', 'Build_WaterPump_C', 'Water'],
        ['Recipe_LiquidOil_C', 'Build_OilPump_C', 'Crude Oil'],
        ['Recipe_CateriumOre_C', 'Build_MinerMk1_C', 'Caterium Ore'],
        ['Recipe_Bauxite_C', 'Build_MinerMk1_C', 'Bauxite'],
        ['Recipe_RawQuartz_C', 'Build_MinerMk1_C', 'Raw Quartz'],
        ['Recipe_Sulfur_C', 'Build_MinerMk1_C', 'Sulphur'],
        ['Recipe_Uranium_C', 'Build_MinerMk1_C', 'Uranium'],
        ['Recipe_NitrogenGas_C', 'Build_FrackingExtractor_C', 'Nitrogen Gas']
    ];

    private array $resource_recipe_outputs = [
        ['Recipe_IronOre_C', 'Desc_OreIron_C', 120, 1],
        ['Recipe_CopperOre_C', 'Desc_OreCopper_C', 120, 1],
        ['Recipe_LimestoneOre_C', 'Desc_Stone_C', 120, 1],
        ['Recipe_Coal_C', 'Desc_Coal_C', 120, 1],
        ['Recipe_Water_C', 'Desc_Water_C', 120, 1],
        ['Recipe_LiquidOil_C', 'Desc_LiquidOil_C', 120, 1],
        ['Recipe_CateriumOre_C', 'Desc_OreGold_C', 120, 1],
        ['Recipe_Bauxite_C', 'Desc_OreBauxite_C', 120, 1],
        ['Recipe_RawQuartz_C', 'Desc_RawQuartz_C', 120, 1],
        ['Recipe_Sulfur_C', 'Desc_Sulfur_C', 120, 1],
        ['Recipe_Uranium_C', 'Desc_OreUranium_C', 120, 1],
        ['Recipe_NitrogenGas_C', 'Desc_NitrogenGas_C', 60, 1]
    ];

    private array $alternative_recipes = array(
        'Recipe_ResidualPlastic_C' => ['Desc_Plastic_C'],
        'Recipe_AluminumScrap_C' => ['Desc_Water_C'],
        'Recipe_Battery_C' => ['Desc_Water_C'],
        'Recipe_NonFissileUranium_C' => ['Desc_Water_C'],
        'Recipe_UnpackageWater_C' => ['Desc_Water_C', 'Desc_FluidCanister_C'],
        'Recipe_Protein_Crab_C' => ['Desc_AlienProtein_C'],
        'Recipe_Protein_Spitter_C' => ['Desc_AlienProtein_C'],
        'Recipe_Protein_Stinger_C' => ['Desc_AlienProtein_C'],
        'Recipe_PureAluminumIngot_C' => ['Desc_AluminumIngot_C'],
        'Recipe_UnpackageAlumina_C' => ['Desc_AluminaSolution_C', 'Desc_FluidCanister_C'],
        'Recipe_CartridgeChaos_Packaged_C' => ['Desc_CartridgeChaos_C'],
        'Recipe_IonizedFuel_C' => ['Desc_CompactedCoal_C'],
        'Recipe_RocketFuel_C' => ['Desc_CompactedCoal_C'],
        'Recipe_PowerCrystalShard_2_C' => ['Desc_CrystalShard_C'],
        'Recipe_PowerCrystalShard_3_C' => ['Desc_CrystalShard_C'],
        'Recipe_SyntheticPowerShard_C' => ['Desc_CrystalShard_C', 'Desc_DarkEnergy_C'],
        'Recipe_AlienPowerFuel_C' => ['Desc_DarkEnergy_C'],
        'Recipe_FicsoniumFuelRod_C' => ['Desc_DarkEnergy_C'],
        'Recipe_SpaceElevatorPart_12_C' => ['Desc_DarkEnergy_C'],
        'Recipe_SuperpositionOscillator_C' => ['Desc_DarkEnergy_C'],
        'Recipe_TemporalProcessor_C' => ['Desc_DarkEnergy_C'],
        'Recipe_UnpackageBioFuel_C' => ['Desc_FluidCanister_C', 'Desc_LiquidBiofuel_C'],
        'Recipe_UnpackageFuel_C' => ['Desc_FluidCanister_C', 'Desc_LiquidFuel_C'],
        'Recipe_UnpackageOil_C' => ['Desc_FluidCanister_C', 'Desc_LiquidOil_C'],
        'Recipe_UnpackageOilResidue_C' => ['Desc_FluidCanister_C', 'Desc_HeavyOilResidue_C'],
        'Recipe_UnpackageSulfuricAcid_C' => ['Desc_FluidCanister_C', 'Desc_SulfuricAcid_C'],
        'Recipe_UnpackageTurboFuel_C' => ['Desc_FluidCanister_C'],
        'Recipe_UnpackageIonizedFuel_C' => ['Desc_GasTank_C', 'Desc_IonizedFuel_C'],
        'Recipe_UnpackageNitricAcid_C' => ['Desc_GasTank_C', 'Desc_NitricAcid_C'],
        'Recipe_UnpackageNitrogen_C' => ['Desc_GasTank_C', 'Desc_NitrogenGas_C'],
        'Recipe_UnpackageRocketFuel_C' => ['Desc_GasTank_C', 'Desc_RocketFuel_C'],
        'Recipe_Biomass_Leaves_C' => ['Desc_GenericBiomass_C'],
        'Recipe_Biomass_Mycelia_C' => ['Desc_GenericBiomass_C'],
        'Recipe_Biomass_Wood_C' => ['Desc_GenericBiomass_C'],
        'Recipe_Rubber_C' => ['Desc_HeavyOilResidue_C'],
        'Recipe_ResidualFuel_C' => ['Desc_LiquidFuel_C'],
        'Recipe_ResidualRubber_C' => ['Desc_Rubber_C'],
        'Recipe_AluminaSolution_C' => ['Desc_Silica_C'],
        'Recipe_UraniumCell_C' => ['Desc_SulfuricAcid_C']
    );

    private array $standard_recipes = array(
        'Recipe_Alternate_EnrichedCoal_C' => 'Desc_CompactedCoal_C',
        'Recipe_DarkEnergy_C' => 'Desc_DarkEnergy_C'
    );

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

        foreach ($this->resource_recipes as $recipe) {
            $this->recipeModel->insertRecipe($recipe[0], $recipe[1], $recipe[2]);
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

            if ($id && $produced_in && $display_name) {
                // Insert the valid recipe into the database
                $this->recipeModel->insertRecipe($id, $produced_in, $display_name);
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

        foreach ($this->resource_recipe_outputs as $recipe_output) {
            $this->recipeModel->insertRecipeOutput($recipe_output[0], $recipe_output[1], $recipe_output[2], $recipe_output[3]);
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

                        $is_alternative = str_contains($recipe_id, 'Recipe_Alternate_');
                        if ($filteredMachines[0] == 'Build_Converter_C') $is_alternative = true;

                        if (array_key_exists($recipe_id, $this->alternative_recipes)) {
                            if (in_array($item_id, $this->alternative_recipes[$recipe_id])) $is_alternative = true;
                        }

                        if (array_key_exists($recipe_id, $this->standard_recipes)) {
                            if ($this->standard_recipes[$recipe_id] == $item_id) $is_alternative = false;
                        }

                        $this->recipeModel->insertRecipeOutput($recipe_id, $item_id, $amount, !$is_alternative);
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