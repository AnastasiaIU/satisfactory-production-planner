<?php

require_once(__DIR__ . '/BaseModel.php');
require_once (__DIR__ . '/../dto/MachineDTO.php');

/**
 * MachineModel class extends BaseModel to interact with the MACHINE entity in the database.
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
     * @param MachineDTO $machine The machine to insert.
     * @return void
     */
    public function insert(MachineDTO $machine): void
    {
        $query = self::$pdo->prepare(
            'INSERT INTO MACHINE (id, display_name, icon_name) VALUES (:id, :display_name, :icon_name)'
        );

        $query->execute([
            ':id' => $machine->id,
            ':display_name' => $machine->display_name,
            ':icon_name' => $machine->icon_name,
        ]);
    }

    /**
     * Retrieves all native classes from the UTILITY MACHINE table.
     *
     * @return array An array of native classes.
     */
    public function getNativeClasses(): array
    {
        $native_class = 'Native Class';
        $query = self::$pdo->prepare(
            'SELECT native_class
                    FROM `UTILITY MACHINE`
                    WHERE category = :native_class'
        );

        $query->execute([
            ':native_class' => $native_class
        ]);

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return array_column($result, 'native_class');
    }
}