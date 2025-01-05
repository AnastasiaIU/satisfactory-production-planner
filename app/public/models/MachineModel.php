<?php

require_once(__DIR__ . '/BaseModel.php');

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
}