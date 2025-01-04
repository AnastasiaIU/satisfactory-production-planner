<?php

require_once(__DIR__ . '/BaseController.php');
require_once(__DIR__ . '/../models/MachineModel.php');
require_once(__DIR__ . '/../services/MachineService.php');

/**
 * Controller class for handling machine-related operations.
 */
class MachineController extends BaseController
{
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
}
