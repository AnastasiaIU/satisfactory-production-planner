<?php

require_once(__DIR__ . '/BaseController.php');
require_once(__DIR__ . '/../dto/MachineDTO.php');
require_once(__DIR__ . '/../services/MachineService.php');

class MachineController extends BaseController
{
    private MachineService $machineService;

    public function __construct()
    {
        $this->machineService = new MachineService();

        if ($this->machineService->isTableEmpty()) {
            $this->machineService->loadMachinesFromJson($this::INITIAL_DATASET);
        }
    }
}
