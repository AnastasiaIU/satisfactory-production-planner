<?php

require_once(__DIR__ . '/BaseModel.php');

class ItemModel extends BaseModel
{
    public function hasAnyRecords(): bool
    {
        $query = self::$pdo->query('SELECT 1 FROM ITEM LIMIT 1');
        return $query->fetch() !== false;
    }

    public function insertRecord(string $id, string $display_name, string $icon_name): void
    {
        $stmt = self::$pdo->prepare(
            'INSERT INTO ITEM (id, display_name, icon_name) VALUES (:id, :display_name, :icon_name)'
        );
        $stmt->execute([
            ':id' => $id,
            ':display_name' => $display_name,
            ':icon_name' => $icon_name,
        ]);
    }
}