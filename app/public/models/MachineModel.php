<?php

require_once(__DIR__ . '/BaseModel.php');

/**
 * MachineModel class extends BaseModel to interact with the MACHINE table in the database.
 */
class MachineModel extends BaseModel
{
    /**
     * Checks if there are any records in the MACHINE table.
     *
     * @return bool True if there are records, false otherwise.
     */
    public function hasAnyRecords(): bool
    {
        return $this->hasAnyRecordsInTable('MACHINE');
    }

    /**
     * Inserts a new record into the MACHINE table.
     *
     * @param string $id The ID of the machine.
     * @param string $display_name The display name of the machine.
     * @param string $icon_name The icon name of the machine.
     * @return void
     */
    public function insert(string $id, string $display_name, string $icon_name): void
    {
        $query = self::$pdo->prepare(
            'INSERT INTO MACHINE (id, display_name, icon_name) VALUES (:id, :display_name, :icon_name)'
        );

        $query->execute([
            ':id' => $id,
            ':display_name' => $display_name,
            ':icon_name' => $icon_name,
        ]);
    }
}