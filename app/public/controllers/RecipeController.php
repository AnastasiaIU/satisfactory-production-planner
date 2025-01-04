<?php

require_once(__DIR__ . '/BaseController.php');
require_once(__DIR__ . '/../models/RecipeModel.php');
require_once(__DIR__ . '/../services/RecipeService.php');

/**
 * Controller class for handling recipe-related operations.
 */
class RecipeController extends BaseController
{
    private RecipeModel $recipeModel;
    private RecipeService $recipeService;

    public function __construct()
    {
        $this->recipeModel = new RecipeModel();
        $this->recipeService = new RecipeService();
    }

    /**
     * Checks if the recipes table is empty.
     *s
     * @return bool True if the table is empty, false otherwise.
     */
    public function isRecipeTableEmpty(): bool
    {
        return $this->recipeModel->recipeHasAnyRecords();
    }

    /**
     * Checks if the recipe outputs table is empty.
     *s
     * @return bool True if the table is empty, false otherwise.
     */
    public function isRecipeOutputTableEmpty(): bool
    {
        return $this->recipeModel->recipeOutputHasAnyRecords();
    }

    /**
     * Checks if the recipe inputs table is empty.
     *s
     * @return bool True if the table is empty, false otherwise.
     */
    public function isRecipeInputTableEmpty(): bool
    {
        return $this->recipeModel->recipeInputHasAnyRecords();
    }

    /**
     * Loads recipe data from the JSON file to the database.
     *
     * @return void
     */
    public function loadRecipesFromJson(): void
    {
        $this->recipeService->loadRecipesFromJson($this::INITIAL_DATASET);
    }

    /**
     * Loads recipe outputs data from the JSON file to the database.
     *
     * @return void
     */
    public function loadRecipeOutputsFromJson(): void
    {
        $this->recipeService->loadRecipeOutputsFromJson($this::INITIAL_DATASET);
    }

    /**
     * Loads recipe inputs data from the JSON file to the database.
     *
     * @return void
     */
    public function loadRecipeInputsFromJson(): void
    {
        $this->recipeService->loadRecipeInputsFromJson($this::INITIAL_DATASET);
    }

    /**
     * Retrieves the standard recipe for the given item ID.
     *
     * @param string $itemId The ID of the item.
     * @return RecipeDTO The recipe for the item.
     */
    public function getRecipeForItem(string $itemId): RecipeDTO
    {
        return $this->recipeModel->getRecipeForItem($itemId);
    }

    /**
     * Retrieves the outputs of a recipe based on the given recipe ID.
     *
     * @param string $recipeId The ID of the recipe.
     * @return array The outputs of the recipe, including recipe ID, item ID, amount,
     *               whether it is a standard recipe, and the item icon name.
     */
    public function getRecipeOutputs(string $recipeId): array
    {
        return $this->recipeModel->getRecipeOutputs($recipeId);
    }

    /**
     * Retrieves the inputs of a recipe based on the given recipe ID.
     *
     * @param string $recipeId The ID of the recipe.
     * @return array The inputs of the recipe, including recipe ID, item ID, amount, and the item icon name.
     */
    public function getRecipeInputs(string $recipeId): array
    {
        return $this->recipeModel->getRecipeInputs($recipeId);
    }
}
