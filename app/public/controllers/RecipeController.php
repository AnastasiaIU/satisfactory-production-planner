<?php

require_once(__DIR__ . '/../dto/RecipeDTO.php');
require_once(__DIR__ . '/../services/RecipeService.php');

class RecipeController
{
    private RecipeModel $recipeModel;
    private RecipeService $recipeService;

    public function __construct()
    {
        $this->recipeModel = new RecipeModel();
        $this->recipeService = new RecipeService();

        if ($this->recipeService->isTableEmpty('RECIPE')) {
            $this->recipeService->loadRecipesFromJson($_ENV['INITIAL_DATASET']);
        }

        if ($this->recipeService->isTableEmpty('RECIPE OUTPUT')) {
            $this->recipeService->loadRecipeOutputsFromJson($_ENV['INITIAL_DATASET']);
        }

        if ($this->recipeService->isTableEmpty('RECIPE INPUT')) {
            $this->recipeService->loadRecipeInputsFromJson($_ENV['INITIAL_DATASET']);
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
