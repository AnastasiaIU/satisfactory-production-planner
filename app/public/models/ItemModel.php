<?php

require_once(__DIR__ . '/BaseModel.php');

class ItemModel extends BaseModel
{
    public function hasAnyRecords(): bool
    {
        $query = self::$pdo->query('SELECT * FROM ITEM LIMIT 1');
        return $query->fetch() !== false;
    }

    public function insertRecord(string $id, string $display_name, string $icon_name, string $type): void
    {
        $query = self::$pdo->prepare(
            'INSERT INTO ITEM (id, display_name, icon_name, type) VALUES (:id, :display_name, :icon_name, :type)'
        );
        $query->execute([
            ':id' => $id,
            ':display_name' => $display_name,
            ':icon_name' => $icon_name,
            ':type' => $type
        ]);
    }

    public function fetchAll(): array
    {
        $query = self::$pdo->query('SELECT id, display_name, icon_name, type FROM ITEM');
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}