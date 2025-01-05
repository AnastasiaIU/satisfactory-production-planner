<?php

require_once(__DIR__ . '/BaseModel.php');
require_once (__DIR__ . '/../dto/RecipeDTO.php');

/**
 * RecipeModel class extends BaseModel to interact with the RECIPE entity in the database.
 */
class RecipeModel extends BaseModel
{
    /**
     * Checks if there are any records in the RECIPE table.
     *
     * @return bool True if there are records, false otherwise.
     */
    public function recipeHasAnyRecords(): bool
    {
        return $this->hasAnyRecordsInTable('RECIPE');
    }

    /**
     * Checks if there are any records in the RECIPE OUTPUT table.
     *
     * @return bool True if there are records, false otherwise.
     */
    public function recipeOutputHasAnyRecords(): bool
    {
        return $this->hasAnyRecordsInTable('RECIPE OUTPUT');
    }

    /**
     * Checks if there are any records in the RECIPE INPUT table.
     *
     * @return bool True if there are records, false otherwise.
     */
    public function recipeInputHasAnyRecords(): bool
    {
        return $this->hasAnyRecordsInTable('RECIPE INPUT');
    }

    /**
     * Retrieves the standard recipe for the given item ID.
     *
     * @param string $itemId The ID of the item.
     * @return RecipeDTO The standard recipe for the item.
     */
    public function getRecipeForItem(string $itemId): RecipeDTO
    {
        $query = self::$pdo->prepare(
            'SELECT ro.item_id, r.id AS recipe_id, r.produced_in, r.display_name 
                    FROM `RECIPE OUTPUT` ro
                    JOIN RECIPE r ON ro.recipe_id = r.id
                    WHERE ro.item_id = :itemId AND ro.is_standard_recipe = 1'
        );

        $query->execute([':itemId' => $itemId]);
        $recipe = $query->fetch(PDO::FETCH_ASSOC);
        $recipe_outputs = $this->getRecipeOutputs($recipe['recipe_id']);
        $recipe_inputs = $this->getRecipeInputs($recipe['recipe_id']);

        return new RecipeDTO(
            $recipe['recipe_id'],
            $recipe['produced_in'],
            $recipe['display_name'],
            $recipe_outputs,
            $recipe_inputs
        );
    }

    /**
     * Retrieves the outputs of a recipe based on the given recipe ID.
     *
     * @param string $recipe_id The ID of the recipe.
     * @return array An array with recipe output objects.
     */
    public function getRecipeOutputs(string $recipe_id): array
    {
        $query = self::$pdo->prepare(
            'SELECT recipe_id, item_id, amount, is_standard_recipe
                    FROM `RECIPE OUTPUT`
                    WHERE recipe_id = :recipeId'
        );

        $query->execute([':recipeId' => $recipe_id]);
        $recipe_outputs = $query->fetchAll(PDO::FETCH_ASSOC);

        $dtos = [];

        foreach ($recipe_outputs as $recipe_output) {
            $dto = new RecipeOutputDTO(
                $recipe_output['recipe_id'],
                $recipe_output['item_id'],
                $recipe_output['amount'],
                $recipe_output['is_standard_recipe']
            );
            $dtos[] = $dto;
        }

        return $dtos;
    }

    /**
     * Retrieves the inputs of a recipe based on the given recipe ID.
     *
     * @param string $recipe_id The ID of the recipe.
     * @return array An array with recipe input objects.
     */
    public function getRecipeInputs(string $recipe_id): array
    {
        $query = self::$pdo->prepare(
            'SELECT recipe_id, amount, amount
                    FROM `RECIPE INPUT`
                    WHERE recipe_id = :recipeId'
        );

        $query->execute([':recipeId' => $recipe_id]);
        $recipe_inputs = $query->fetchAll(PDO::FETCH_ASSOC);

        $dtos = [];

        foreach ($recipe_inputs as $recipe_input) {
            $dto = new RecipeInputDTO(
                $recipe_input['recipe_id'],
                $recipe_input['item_id'],
                $recipe_input['amount']
            );
            $dtos[] = $dto;
        }

        return $dtos;
    }
}