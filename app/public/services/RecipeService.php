<?php

require_once(__DIR__ . '/BaseService.php');
require_once(__DIR__ . '/../models/RecipeModel.php');

/**
 * This class provides services related to items, including loading items from a JSON file.
 */
class RecipeService extends BaseService
{
    private const NATIVE_CLASS = "/Script/CoreUObject.Class'/Script/FactoryGame.FGRecipe'";
    private RecipeModel $recipeModel;

    public function __construct()
    {
        $this->recipeModel = new RecipeModel();
    }

    /**
     * Loads recipes from a JSON file and inserts them into the database.
     *
     * @param string $jsonPath The path to the JSON file containing the recipes.
     * @return void
     */
    public function loadRecipesFromJson(string $jsonPath): void
    {
        $data = $this->getJsonContent($jsonPath);

        if ($data === null) {
            error_log('Error decoding JSON: ' . json_last_error_msg());
            return;
        }

        $resource_recipes = $this->recipeModel->getResourceRecipes();
        $event_recipes = $this->recipeModel->getEventRecipes();

        // Insert resource recipes into the database
        foreach ($resource_recipes as $recipe) {
            $this->recipeModel->insertRecipe($recipe['recipe_id'], $recipe['machine_id'], $recipe['display_name']);
        }

        foreach ($data as $class) {
            // Process only the related native class
            if (isset($class['NativeClass']) && $class['NativeClass'] === $this::NATIVE_CLASS) {
                foreach ($class['Classes'] as $item) {
                    $recipe_id = $item['ClassName'];

                    // Skip items from the seasonal FICSMAS event
                    if (in_array($recipe_id, $event_recipes)) continue;

                    $automated_machine = $this->filterAutomatedMachine($item['mProducedIn']);

                    // Skip this recipe if no valid machines are found
                    if ($automated_machine === null) continue;

                    $display_name = $item['mDisplayName'];

                    $this->recipeModel->insertRecipe($recipe_id, $automated_machine, $display_name);
                }
            }
        }
    }

    /**
     * Loads recipe outputs from a JSON file and inserts them into the database.
     *
     * @param string $jsonPath The path to the JSON file containing the recipe outputs.
     * @return void
     */
    public function loadRecipeOutputsFromJson(string $jsonPath): void
    {
        $data = $this->getJsonContent($jsonPath);

        if ($data === null) {
            error_log('Error decoding JSON: ' . json_last_error_msg());
            return;
        }

        $resource_recipe_outputs = $this->recipeModel->getResourceRecipeOutputs();
        $event_recipes = $this->recipeModel->getEventRecipes();

        // Insert resource recipe outputs into the database
        foreach ($resource_recipe_outputs as $recipe_output) {
            $this->recipeModel->insertRecipeOutput(
                $recipe_output['recipe_id'],
                $recipe_output['standard_output'],
                (int)$recipe_output['amount'],
                (bool)$recipe_output['is_standard_recipe']);
        }

        foreach ($data as $class) {
            if (isset($class['NativeClass']) && $class['NativeClass'] === $this::NATIVE_CLASS) {
                foreach ($class['Classes'] as $item) {
                    $recipe_id = $item['ClassName'];

                    // Skip items from the seasonal FICSMAS event
                    if (in_array($recipe_id, $event_recipes)) continue;

                    $automated_machine = $this->filterAutomatedMachine($item['mProducedIn']);

                    // Skip this recipe if no valid machines are found
                    if ($automated_machine === null) continue;

                    preg_match_all('/\((.*?)\)/', $item['mProduct'], $productBlocks);

                    foreach ($productBlocks[0] as $product) {
                        preg_match('/\(ItemClass="[^"]+(Desc_[^"]+_C)\'",Amount=(\d+)\)/', $product, $productPair);

                        // Skip this product if no valid product pairs are found
                        if (empty($productPair[0])) continue;

                        $item_id = $productPair[1];
                        $amount = (int)$productPair[2];

                        $is_alternative = $this->getIsAlternative($recipe_id, $automated_machine, $item_id);

                        $this->recipeModel->insertRecipeOutput($recipe_id, $item_id, $amount, !$is_alternative);
                    }
                }
            }
        }
    }

    /**
     * Loads recipe inputs from a JSON file and inserts them into the database.
     *
     * @param string $jsonPath The path to the JSON file containing the recipe inputs.
     * @return void
     */
    public function loadRecipeInputsFromJson(string $jsonPath): void
    {
        $data = $this->getJsonContent($jsonPath);

        if ($data === null) {
            error_log('Error decoding JSON: ' . json_last_error_msg());
            return;
        }

        $event_recipes = $this->recipeModel->getEventRecipes();

        foreach ($data as $class) {
            if (isset($class['NativeClass']) && $class['NativeClass'] === $this::NATIVE_CLASS) {
                foreach ($class['Classes'] as $item) {
                    $recipe_id = $item['ClassName'];

                    // Skip items from the seasonal FICSMAS event
                    if (in_array($recipe_id, $event_recipes)) continue;

                    $automated_machine = $this->filterAutomatedMachine($item['mProducedIn']);

                    // Skip this recipe if no valid machines are found
                    if ($automated_machine === null) continue;

                    preg_match_all('/\((.*?)\)/', $item['mIngredients'], $ingredientBlocks);

                    foreach ($ingredientBlocks[0] as $ingredient) {
                        preg_match('/\(ItemClass="[^"]+(Desc_[^"]+_C)\'",Amount=(\d+)\)/', $ingredient, $ingredientPair);

                        // Skip this ingredient if no valid ingredient pairs are found
                        if (empty($ingredientPair[0])) continue;

                        $item_id = $ingredientPair[1];
                        $amount = (int)$ingredientPair[2];

                        $this->recipeModel->insertRecipeInput($recipe_id, $item_id, $amount);
                    }
                }
            }
        }
    }

    /**
     * Filters out non-automated machines from the given string of machines.
     *
     * @param string $machines The string containing machine identifiers.
     * @return string|null The first automated machine identifier, or null if none found.
     */
    private function filterAutomatedMachine(string $machines): ?string
    {
        $automated_machines = [];
        preg_match_all('/Build_[^.]+_C/', $machines, $automated_machines);
        $filtered_machines = array_filter($automated_machines[0], function ($machine) {
            return stripos($machine, 'WorkBench') === false;
        });

        return !empty($filtered_machines) ? $filtered_machines[0] : null;
    }

    /**
     * Determines if a recipe is an alternative recipe.
     *
     * @param string $recipe_id The ID of the recipe.
     * @param string $automated_machine The machine in which the recipe is produced.
     * @param string $item_id The ID of the item produced by the recipe.
     * @return bool True if the recipe is an alternative recipe, false otherwise.
     */
    private function getIsAlternative(string $recipe_id, string $automated_machine, string $item_id): bool
    {
        $alternative_recipe_outputs = $this->recipeModel->getAlternativeRecipeOutputs();
        $standard_recipe_outputs = $this->recipeModel->getStandardRecipeOutputs();

        $is_alternative = str_contains($recipe_id, 'Recipe_Alternate_');
        if ($automated_machine == 'Build_Converter_C') $is_alternative = true;

        if (array_key_exists($recipe_id, $alternative_recipe_outputs)) {
            if (in_array($item_id, $alternative_recipe_outputs[$recipe_id])) $is_alternative = true;
        }

        if (array_key_exists($recipe_id, $standard_recipe_outputs)) {
            if ($standard_recipe_outputs[$recipe_id] == $item_id) $is_alternative = false;
        }

        return $is_alternative;
    }
}