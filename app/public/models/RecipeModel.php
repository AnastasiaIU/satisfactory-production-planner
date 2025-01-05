<?php

require_once(__DIR__ . '/BaseModel.php');
require_once (__DIR__ . '/../dto/RecipeDTO.php');
require_once (__DIR__ . '/../dto/ItemDTO.php');

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
     * Inserts a new record into the RECIPE table.
     *
     * @param RecipeDTO $recipe The recipe to insert.
     * @return void
     */
    public function insertRecipe(RecipeDTO $recipe): void
    {
        $query = self::$pdo->prepare(
            'INSERT INTO RECIPE (id, produced_in, display_name) VALUES (:id, :produced_in, :display_name)'
        );

        $query->execute([
            ':id' => $recipe->id,
            ':produced_in' => $recipe->produced_in,
            ':display_name' => $recipe->display_name
        ]);
    }

    /**
     * Inserts a new record into the RECIPE OUTPUT table.
     *
     * @param RecipeOutputDTO $recipe_output The recipe output to insert.
     * @return void
     */
    public function insertRecipeOutput(RecipeOutputDTO $recipe_output): void
    {
        $query = self::$pdo->prepare(
            'INSERT INTO `RECIPE OUTPUT` (recipe_id, item_id, amount, is_standard_recipe) 
                    VALUES (:recipe_id, :item_id, :amount, :is_standard_recipe)'
        );

        $query->execute([
            ':recipe_id' => $recipe_output->recipe_id,
            ':item_id' => $recipe_output->item_id,
            ':amount' => $recipe_output->amount,
            ':is_standard_recipe' => (int)$recipe_output->is_standard_recipe
        ]);
    }

    /**
     * Inserts a new record into the RECIPE INPUT table.
     *
     * @param RecipeInputDTO $recipe_input The recipe input to insert.
     * @return void
     */
    public function insertRecipeInput(RecipeInputDTO $recipe_input): void
    {
        $query = self::$pdo->prepare(
            'INSERT INTO `RECIPE INPUT` (recipe_id, item_id, amount) VALUES (:recipe_id, :item_id, :amount)'
        );

        $query->execute([
            ':recipe_id' => $recipe_input->recipe_id,
            ':item_id' => $recipe_input->item_id,
            ':amount' => $recipe_input->amount
        ]);
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

    /**
     * Retrieves all FICSMAS event recipes from the UTILITY RECIPE table.
     *
     * @return array An array of FICSMAS event recipes.
     */
    public function getEventRecipes(): array
    {
        $ficsmas = 'Ficsmas';
        $query = self::$pdo->prepare(
            'SELECT recipe_id
                    FROM `UTILITY RECIPE`
                    WHERE category = :ficsmas'
        );

        $query->execute([
            ':ficsmas' => $ficsmas
        ]);

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return array_column($result, 'recipe_id');
    }

    /**
     * Retrieves resource recipes from the UTILITY RECIPE table.
     *
     * @return array An associative array where each element contains the recipe ID, machine ID, and display name.
     */
    public function getResourceRecipes(): array
    {
        $resource_recipe = 'Resource Recipe';
        $query = self::$pdo->prepare(
            'SELECT recipe_id, machine_id, display_name
                    FROM `UTILITY RECIPE`
                    WHERE category = :resource_recipe'
        );

        $query->execute([
            ':resource_recipe' => $resource_recipe
        ]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves resource recipe outputs from the UTILITY RECIPE table.
     *
     * @return array An associative array where each element contains the recipe ID, output amount,
     * is the recipe standard, and the output item ID.
     */
    public function getResourceRecipeOutputs(): array
    {
        $resource_recipe = 'Resource Recipe Output';
        $query = self::$pdo->prepare(
            'SELECT recipe_id, amount, is_standard_recipe, standard_output
                    FROM `UTILITY RECIPE`
                    WHERE category = :resource_recipe'
        );

        $query->execute([
            ':resource_recipe' => $resource_recipe
        ]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves alternative recipe outputs from the UTILITY RECIPE table.
     *
     * @return array An associative array where each element contains the recipe ID and
     * first and second alternative outputs.
     */
    public function getAlternativeRecipeOutputs(): array
    {
        $resource_recipe = 'Alternative Recipe Output';
        $query = self::$pdo->prepare(
            'SELECT recipe_id, alternative_output_1, alternative_output_2
                    FROM `UTILITY RECIPE`
                    WHERE category = :resource_recipe'
        );

        $query->execute([
            ':resource_recipe' => $resource_recipe
        ]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves standard recipe outputs from the UTILITY RECIPE table.
     *
     * @return array An associative array where each element contains the recipe ID and the standard output.
     */
    public function getStandardRecipeOutputs(): array
    {
        $resource_recipe = 'Standard Recipe Output';
        $query = self::$pdo->prepare(
            'SELECT recipe_id, standard_output
                    FROM `UTILITY RECIPE`
                    WHERE category = :resource_recipe'
        );

        $query->execute([
            ':resource_recipe' => $resource_recipe
        ]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}