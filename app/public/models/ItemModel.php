<?php

require_once(__DIR__ . '/BaseModel.php');

/**
 * ItemModel class extends BaseModel to interact with the ITEM table in the database.
 */
class ItemModel extends BaseModel
{
    /**
     * Checks if there are any records in the ITEM table.
     *
     * @return bool True if there are records, false otherwise.
     */
    public function hasAnyRecords(): bool
    {
        return $this->hasAnyRecordsInTable('ITEM');
    }

    /**
     * Inserts a new record into the ITEM table.
     *
     * @param string $id The ID of the item.
     * @param string $display_name The display name of the item.
     * @param string $icon_name The icon name of the item.
     * @param string $category The category of the item.
     * @param int $display_order The display order of the item.
     * @return void
     */
    public function insert(
        string $id, string $display_name, string $icon_name, string $category, int $display_order
    ): void
    {
        $query = self::$pdo->prepare(
            'INSERT INTO ITEM (id, display_name, icon_name, category, display_order) 
                    VALUES (:id, :display_name, :icon_name, :category, :display_order)'
        );

        $query->execute([
            ':id' => $id,
            ':display_name' => $display_name,
            ':icon_name' => $icon_name,
            ':category' => $category,
            ':display_order' => $display_order
        ]);
    }

    /**
     * Fetches all items producible by machines from the ITEM table.
     *
     * @return array An array of producible items.
     */
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