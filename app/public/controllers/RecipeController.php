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

        if (!$this->recipeModel->recipeHasAnyRecords()) {
            $this->recipeService->loadRecipesFromJson($this::INITIAL_DATASET);
        }
        if (!$this->recipeModel->recipeOutputHasAnyRecords()) {
            $this->recipeService->loadRecipeOutputsFromJson($this::INITIAL_DATASET);
        }
        if (!$this->recipeModel->recipeInputHasAnyRecords()) {
            $this->recipeService->loadRecipeInputsFromJson($this::INITIAL_DATASET);
        }
    }

    /**
     * Retrieves the details of a standard recipe for the given item ID.
     *
     * @param string $itemId The ID of the item.
     * @return array The details of the recipe, including recipe ID, machine icon, item icon, and display name.
     */
    public function getRecipeDetails(string $itemId): array
    {
        return $this->recipeModel->getRecipeDetails($itemId);
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
