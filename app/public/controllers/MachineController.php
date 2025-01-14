<?php

require_once(__DIR__ . '/../models/MachineModel.php');
require_once(__DIR__ . '/../services/MachineService.php');
require_once (__DIR__ . '/../dto/MachineDTO.php');

/**
 * Controller class for handling machine-related operations.
 */
class MachineController
{
    protected const INITIAL_DATASET = __DIR__ . '/../../private/assets/datasets/en-GB.json';
    private MachineModel $machineModel;
    private MachineService $machineService;

    public function __construct()
    {
        $this->machineModel = new MachineModel();
        $this->machineService = new MachineService();
    }

    /**
     * Checks if the machines table is empty.
     *
     * @return bool True if the table is empty, false otherwise.
     */
    public function isTableEmpty(): bool
    {
        return $this->machineModel->hasAnyRecords();
    }

    /**
     * Loads data from the JSON file to the database.
     *
     * @return void
     */
    public function loadMachinesFromJson(): void
    {
        $this->machineService->loadMachinesFromJson($this::INITIAL_DATASET);
    }

    /**
     * Retrieves a machine by its ID.
     *
     * @param string $machineId The ID of the machine to retrieve.
     * @return MachineDTO The data transfer object representing the machine.
     */
    public function getMachine(string $machineId): MachineDTO
    {
        return $this->machineModel->getMachine($machineId);
    }
}
