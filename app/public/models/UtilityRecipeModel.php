<?php

require_once(__DIR__ . '/BaseModel.php');

/**
 * UtilityRecipeModel class extends BaseModel to interact with the UTILITY RECIPE
 * entity in the database and populate the RECIPE entity.
 */
class UtilityRecipeModel extends BaseModel
{
    /**
     * Inserts a new record into the RECIPE table.
     *
     * @param string $id The recipe ID.
     * @param string $produced_in The machine ID where the recipe is produced.
     * @param string $display_name The recipe display name.
     * @return void
     */
    public function insertRecipe(string $id, string $produced_in, string $display_name): void
    {
        $query = self::$pdo->prepare(
            'INSERT INTO RECIPE (id, produced_in, display_name) VALUES (:id, :produced_in, :display_name)'
        );

        $query->execute([
            ':id' => $id,
            ':produced_in' => $produced_in,
            ':display_name' => $display_name
        ]);
    }

    /**
     * Inserts a new record into the RECIPE OUTPUT table.
     *
     * @param string $recipe_id The recipe ID.
     * @param string $item_id The item ID.
     * @param float $amount The output amount.
     * @param bool $is_standard_recipe Is the recipe standard.
     * @return void
     */
    public function insertRecipeOutput(string $recipe_id, string $item_id, float $amount, bool $is_standard_recipe): void
    {
        $query = self::$pdo->prepare(
            'INSERT INTO `RECIPE OUTPUT` (recipe_id, item_id, amount, is_standard_recipe) 
                    VALUES (:recipe_id, :item_id, :amount, :is_standard_recipe)'
        );

        $query->execute([
            ':recipe_id' => $recipe_id,
            ':item_id' => $item_id,
            ':amount' => $amount,
            ':is_standard_recipe' => (int)$is_standard_recipe
        ]);
    }

    /**
     * Inserts a new record into the RECIPE INPUT table.
     *
     * @param string $recipe_id The recipe ID.
     * @param string $item_id The item ID.
     * @param float $amount The output amount.
     * @return void
     */
    public function insertRecipeInput(string $recipe_id, string $item_id, float $amount): void
    {
        $query = self::$pdo->prepare(
            'INSERT INTO `RECIPE INPUT` (recipe_id, item_id, amount) VALUES (:recipe_id, :item_id, :amount)'
        );

        $query->execute([
            ':recipe_id' => $recipe_id,
            ':item_id' => $item_id,
            ':amount' => $amount
        ]);
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