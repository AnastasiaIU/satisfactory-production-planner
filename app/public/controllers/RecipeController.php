<?php

require_once(__DIR__ . '/../dto/RecipeDTO.php');
require_once(__DIR__ . '/../services/RecipeService.php');

class RecipeController
{
    private RecipeService $recipeService;

    public function __construct()
    {
        $this->recipeService = new RecipeService();

        if ($this->recipeService->isTableEmpty()) {
            $this->recipeService->loadRecipesFromJson($_ENV['INITIAL_DATASET']);
        }
    }
}
