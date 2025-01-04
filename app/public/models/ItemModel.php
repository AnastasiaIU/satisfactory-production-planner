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
     * Inserts a new record into the ITEM table.
     *
     * @param ItemDTO $item The item to insert.
     * @return void
     */
    public function insert(ItemDTO $item): void
    {
        $query = self::$pdo->prepare(
            'INSERT INTO ITEM (id, display_name, icon_name, category, display_order) 
                    VALUES (:id, :display_name, :icon_name, :category, :display_order)'
        );

        $query->execute([
            ':id' => $item->id,
            ':display_name' => $item->display_name,
            ':icon_name' => $item->icon_name,
            ':category' => $item->category,
            ':display_order' => $item->display_order
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
     * Retrieves all native classes from the UTILITY ITEM table.
     *
     * @return array An array of native classes.
     */
    public function getNativeClasses(): array
    {
        $native_class = 'Native Class';
        $query = self::$pdo->prepare(
            'SELECT native_class
                    FROM `UTILITY ITEM`
                    WHERE category = :native_class'
        );

        $query->execute([
            ':native_class' => $native_class
        ]);

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return array_column($result, 'native_class');
    }

    /**
     * Retrieves all FICSMAS event items from the UTILITY ITEM table.
     *
     * @return array An array of FICSMAS event items.
     */
    public function getEventItems(): array
    {
        $ficsmas = 'Ficsmas';
        $query = self::$pdo->prepare(
            'SELECT item_id
                    FROM `UTILITY ITEM`
                    WHERE category = :ficsmas'
        );

        $query->execute([
            ':ficsmas' => $ficsmas
        ]);

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return array_column($result, 'item_id');
    }

    /**
     * Retrieves item categories from the UTILITY ITEM table.
     *
     * @return array An associative array where the key is an item ID and
     * the value is an array with item's category and display order.
     */
    public function getItemCategories(): array
    {
        $native_class = 'Native Class';
        $ficsmas = 'Ficsmas';
        $query = self::$pdo->prepare(
            'SELECT category, item_id, display_order
                    FROM `UTILITY ITEM`
                    WHERE category NOT IN (:native_class, :ficsmas)'
        );

        $query->execute([
            ':native_class' => $native_class,
            ':ficsmas' => $ficsmas
        ]);

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $categories = [];

        foreach ($result as $row) {
            $categories[$row['item_id']] = [$row['category'], (int)$row['display_order']];
        }

        return $categories;
    }
}