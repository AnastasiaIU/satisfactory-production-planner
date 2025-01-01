<?php

require_once(__DIR__ . '/BaseController.php');
require_once(__DIR__ . '/../models/MachineModel.php');
require_once(__DIR__ . '/../services/MachineService.php');

class MachineController extends BaseController
{
    private MachineModel $machineModel;
    private MachineService $machineService;

    public function __construct()
    {
        $this->machineModel = new MachineModel();
        $this->machineService = new MachineService();

        if (!$this->machineModel->hasAnyRecords()) $this->machineService->loadMachinesFromJson($this::INITIAL_DATASET);
    }
}
