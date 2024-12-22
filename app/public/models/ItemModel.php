<?php

require_once(__DIR__ . '/BaseModel.php');

class ItemModel extends BaseModel
{
    public function hasAnyRecords(): bool
    {
        $query = self::$pdo->query('SELECT * FROM ITEM LIMIT 1');
        return $query->fetch() !== false;
    }

    public function insertRecord(string $id, string $display_name, string $icon_name, string $category, int $display_order): void
    {
        $query = self::$pdo->prepare(
            'INSERT INTO ITEM (id, display_name, icon_name, category, display_order) VALUES (:id, :display_name, :icon_name, :category, :display_order)'
        );
        $query->execute([
            ':id' => $id,
            ':display_name' => $display_name,
            ':icon_name' => $icon_name,
            ':category' => $category,
            ':display_order' => $display_order
        ]);
    }

    public function fetchAllProducible(): array
    {
        $query = self::$pdo->query(
            'SELECT i.id, i.display_name, i.icon_name, i.category, i.display_order
         FROM ITEM i
         JOIN `RECIPE OUTPUT` ro ON i.id = ro.item_id
         GROUP BY i.id, i.display_name, i.icon_name, i.category, i.display_order
         ORDER BY category, display_order'
        );
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

}