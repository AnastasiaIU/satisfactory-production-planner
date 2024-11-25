<?php

require_once(__DIR__ . '/../models/MachineModel.php');
require_once(__DIR__ . '/../dto/MachineDTO.php');
require_once(__DIR__ . '/../services/MachineService.php');

class MachineController
{
    private MachineModel $machineModel;
    private MachineService $machineService;

    public function __construct()
    {
        $this->machineModel = new MachineModel();
        $this->machineService = new MachineService();

        if ($this->machineService->isTableEmpty()) {
            $this->machineService->loadMachinesFromJson($_ENV['INITIAL_DATASET']);
        }
    }
}