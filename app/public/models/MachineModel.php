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
     * Retrieves a machine by its ID.
     *
     * @param string $machineId The ID of the machine to retrieve.
     * @return MachineDTO The data transfer object representing the machine.
     */
    public function getMachine(string $machineId): MachineDTO
    {
        $query = self::$pdo->prepare(
            'SELECT id, display_name, icon_name
                    FROM MACHINE
                    WHERE id = :machineId'
        );
        $query->execute(['machineId' => $machineId]);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        return new MachineDTO(
            $item['id'],
            $item['display_name'],
            $item['icon_name']
        );
    }
}