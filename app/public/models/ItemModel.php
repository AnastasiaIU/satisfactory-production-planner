<?php

require_once(__DIR__ . '/BaseModel.php');
require_once (__DIR__ . '/../dto/ItemDTO.php');

/**
 * ItemModel class extends BaseModel to interact with the ITEM entity in the database.
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

        $items = $query->fetchAll(PDO::FETCH_ASSOC);
        $dtos = [];

        foreach ($items as $item) {
            $dto = new ItemDTO(
                $item['id'],
                $item['display_name'],
                $item['icon_name'],
                $item['category'],
                $item['display_order']
            );
            $dtos[] = $dto;
        }

        return $dtos;
    }

    /**
     * Retrieves an item by its ID.
     *
     * @param string $itemId The ID of the item to retrieve.
     * @return ItemDTO The data transfer object representing the item.
     */
    public function getItem(string $itemId): ItemDTO
    {
        $query = self::$pdo->prepare(
            'SELECT id, display_name, icon_name, category, display_order
                    FROM ITEM
                    WHERE id = :itemId'
        );
        $query->execute(['itemId' => $itemId]);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        return new ItemDTO(
            $item['id'],
            $item['display_name'],
            $item['icon_name'],
            $item['category'],
            $item['display_order']
        );
    }
}