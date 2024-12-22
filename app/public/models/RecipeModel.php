<?php

require_once(__DIR__ . '/BaseModel.php');

class RecipeModel extends BaseModel
{
    public function hasAnyRecords(string $tableName): bool
    {
        $query = self::$pdo->query("SELECT 1 FROM `$tableName` LIMIT 1");
        return $query->fetch() !== false;
    }

    public function insertRecipe(string $id, string $produced_in, string $display_name, bool $is_alternative): void
    {
        $stmt = self::$pdo->prepare(
            'INSERT INTO RECIPE (id, produced_in, display_name, is_alternative) VALUES (:id, :produced_in, :display_name, :is_alternative)'
        );
        $stmt->execute([
            ':id' => $id,
            ':produced_in' => $produced_in,
            ':display_name' => $display_name,
            ':is_alternative' => (int)$is_alternative
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

    public function getRecipeDetails(string $itemId): array
    {
        $query = self::$pdo->prepare(
            'SELECT r.produced_in, m.icon_name AS machine_icon, i.icon_name AS item_icon, i.display_name 
         FROM `RECIPE OUTPUT` ro
         JOIN RECIPE r ON ro.recipe_id = r.id
         JOIN MACHINE m ON r.produced_in = m.id
         JOIN ITEM i ON ro.item_id = i.id
         WHERE i.id = :itemId'
        );

        $query->execute([':itemId' => $itemId]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}