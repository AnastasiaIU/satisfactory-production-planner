<?php

require_once(__DIR__ . '/BaseModel.php');

class RecipeModel extends BaseModel
{
    public function hasAnyRecords(): bool
    {
        $query = self::$pdo->query('SELECT 1 FROM RECIPE LIMIT 1');
        return $query->fetch() !== false;
    }

    public function insertRecord(string $id, string $produced_in, string $display_name): void
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
}