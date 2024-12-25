<?php

require_once(__DIR__ . '/BaseModel.php');

class RecipeModel extends BaseModel
{
    public function hasAnyRecords(string $tableName): bool
    {
        $query = self::$pdo->query("SELECT 1 FROM `$tableName` LIMIT 1");
        return $query->fetch() !== false;
    }

    public function insertRecipe(string $id, string $produced_in, string $display_name): void
    {
        $stmt = self::$pdo->prepare(
            'INSERT INTO RECIPE (id, produced_in, display_name) VALUES (:id, :produced_in, :display_name)'
        );
        $stmt->execute([
            ':id' => $id,
            ':produced_in' => $produced_in,
            ':display_name' => $display_name
        ]);
    }

    public function insertRecipeOutput(string $recipe_id, string $item_id, int $amount, bool $is_standard_recipe): void
    {
        $stmt = self::$pdo->prepare(
            'INSERT INTO `RECIPE OUTPUT` (recipe_id, item_id, amount, is_standard_recipe) VALUES (:recipe_id, :item_id, :amount, :is_standard_recipe)'
        );
        $stmt->execute([
            ':recipe_id' => $recipe_id,
            ':item_id' => $item_id,
            ':amount' => $amount,
            ':is_standard_recipe' => (int)$is_standard_recipe
        ]);
    }

    public function insertRecipeInput(string $recipe_id, string $item_id, int $amount): void
    {
        $stmt = self::$pdo->prepare(
            'INSERT INTO `RECIPE INPUT` (recipe_id, item_id, amount) VALUES (:recipe_id, :item_id, :amount)'
        );
        $stmt->execute([
            ':recipe_id' => $recipe_id,
            ':item_id' => $item_id,
            ':amount' => $amount
        ]);
    }

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

    public function getRecipeOutputs(string $recipeId): array
    {
        $query = self::$pdo->prepare(
            'SELECT ro.recipe_id, ro.item_id, ro.amount, ro.is_standard_recipe, i.icon_name AS icon_name
         FROM `RECIPE OUTPUT` AS ro
         JOIN ITEM AS i ON ro.item_id = i.id
         WHERE recipe_id = :recipeId'
        );

        $query->execute([':recipeId' => $recipeId]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        // Ensure an array is returned even if no results are found
        return $results ?: [];
    }

    public function getRecipeInputs(string $recipeId): array
    {
        $query = self::$pdo->prepare(
            'SELECT ri.recipe_id, ri.item_id, ri.amount, i.icon_name AS icon_name
         FROM `RECIPE INPUT` AS ri
         JOIN ITEM AS i ON ri.item_id = i.id
         WHERE recipe_id = :recipeId'
        );

        $query->execute([':recipeId' => $recipeId]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        // Ensure an array is returned even if no results are found
        return $results ?: [];
    }
}