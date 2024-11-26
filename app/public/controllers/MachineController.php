<?php

require_once(__DIR__ . '/../dto/MachineDTO.php');
require_once(__DIR__ . '/../services/MachineService.php');

class MachineController
{
    private MachineService $machineService;

    public function __construct()
    {
        $this->machineService = new MachineService();

        if ($this->machineService->isTableEmpty()) {
            $this->machineService->loadMachinesFromJson($_ENV['INITIAL_DATASET']);
        }
    }
}
