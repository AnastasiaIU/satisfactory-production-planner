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
            ':display_name' => $display_name,
        ]);
    }

    public function insertRecipeOutput(string $recipe_id, string $item_id, int $amount): void
    {
        $stmt = self::$pdo->prepare(
            'INSERT INTO `RECIPE OUTPUT` (recipe_id, item_id, amount) VALUES (:recipe_id, :item_id, :amount)'
        );
        $stmt->execute([
            ':recipe_id' => $recipe_id,
            ':item_id' => $item_id,
            ':amount' => $amount,
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
            ':amount' => $amount,
        ]);
    }
}