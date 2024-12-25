<?php

require_once(__DIR__ . '/BaseModel.php');

/**
 * RecipeModel class extends BaseModel to interact with the RECIPE table in the database.
 */
class RecipeModel extends BaseModel
{
    /**
     * Checks if there are any records in the RECIPE table.
     *
     * @return bool True if there are records, false otherwise.
     */
    public function hasAnyRecords(): bool
    {
        return $this->hasAnyRecordsInTable('RECIPE');
    }

    /**
     * Inserts a new record into the RECIPE table.
     *
     * @param string $id The ID of the recipe.
     * @param string $produced_in The ID of the machine where the recipe is produced.
     * @param string $display_name The display name of the recipe.
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
     * @param string $recipe_id The ID of the recipe.
     * @param string $item_id The ID of the item.
     * @param int $amount The amount of the item.
     * @param bool $is_standard_recipe Indicates if it is a standard recipe.
     * @return void
     */
    public function insertRecipeOutput(string $recipe_id, string $item_id, int $amount, bool $is_standard_recipe): void
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
     * @param string $recipe_id The ID of the recipe.
     * @param string $item_id The ID of the item.
     * @param int $amount The amount of the item.
     * @return void
     */
    public function insertRecipeInput(string $recipe_id, string $item_id, int $amount): void
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
     * Retrieves the details of a standard recipe for the given item ID.
     *
     * @param string $itemId The ID of the item.
     * @return array The details of the recipe, including recipe ID, machine icon, item icon, and display name.
     */
    public function getRecipeDetails(string $itemId): array
    {
        $query = self::$pdo->prepare(
            'SELECT r.id AS recipe_id, r.produced_in, m.icon_name AS machine_icon, i.icon_name AS item_icon, i.display_name 
                    FROM `RECIPE OUTPUT` ro
                    JOIN RECIPE r ON ro.recipe_id = r.id
                    JOIN MACHINE m ON r.produced_in = m.id
                    JOIN ITEM i ON ro.item_id = i.id
                    WHERE i.id = :itemId AND ro.is_standard_recipe = 1'
        );

        $query->execute([':itemId' => $itemId]);
        return $query->fetch(PDO::FETCH_ASSOC);
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
        $query = self::$pdo->prepare(
            'SELECT ro.recipe_id, ro.item_id, ro.amount, ro.is_standard_recipe, i.icon_name
                    FROM `RECIPE OUTPUT` AS ro
                    JOIN ITEM AS i ON ro.item_id = i.id
                    WHERE recipe_id = :recipeId'
        );

        $query->execute([':recipeId' => $recipeId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves the inputs of a recipe based on the given recipe ID.
     *
     * @param string $recipeId The ID of the recipe.
     * @return array The inputs of the recipe, including recipe ID, item ID, amount, and the item icon name.
     */
    public function getRecipeInputs(string $recipeId): array
    {
        $query = self::$pdo->prepare(
            'SELECT ri.recipe_id, ri.item_id, ri.amount, i.icon_name AS icon_name
                    FROM `RECIPE INPUT` AS ri
                    JOIN ITEM AS i ON ri.item_id = i.id
                    WHERE recipe_id = :recipeId'
        );

        $query->execute([':recipeId' => $recipeId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}