<?php

require_once(__DIR__ . '/BaseController.php');
require_once(__DIR__ . '/../models/RecipeModel.php');
require_once(__DIR__ . '/../services/RecipeService.php');

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

    public function getRecipeDetails(string $itemId): array
    {
        return $this->recipeModel->getRecipeDetails($itemId);
    }

    public function getRecipeOutputs(string $recipeId): array
    {
        return $this->recipeModel->getRecipeOutputs($recipeId);
    }

    public function getRecipeInputs(string $recipeId): array
    {
        return $this->recipeModel->getRecipeInputs($recipeId);
    }
}
