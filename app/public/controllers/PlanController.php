<?php

require_once(__DIR__ . '/../models/PlanModel.php');
require_once(__DIR__ . '/../dto/PlanDTO.php');

/**
 * Controller class for handling production plan-related operations.
 */
class PlanController
{
    private PlanModel $planModel;

    public function __construct()
    {
        $this->planModel = new PlanModel();
    }

    /**
     * Fetches all production plans for the provided user.
     *
     * @return array An array of production plan objects.
     */
    public function fetchAllPlans(string $userId): array
    {
        return $this->planModel->fetchAllPlans($userId);
    }

    /**
     * Retrieves a production plan by its ID.
     *
     * @param string $planId The ID of the production plan to retrieve.
     * @return PlanDTO|null The data transfer object representing the production plan or null if the plan is not found.
     */
    public function getProductionPlan(string $planId): ?PlanDTO
    {
        return $this->planModel->getProductionPlan($planId);
    }

    /**
     * Creates a new production plan in the database.
     *
     * @param string $createdBy The user who created the production plan.
     * @param string $displayName The display name of the production plan.
     * @param array $items An associative array of item IDs and amounts for this plan.
     */
    public function createProductionPlan(string $createdBy, string $displayName, array $items): void
    {
        if ($this->planModel->createProductionPlan($createdBy, $displayName, $items)) {
            header('Location: /plans');
        } else {
            http_response_code(500);
        }
    }

    /**
     * Deletes a production plan from the database.
     *
     * @param string $planId The ID of the production plan to delete.
     */
    public function deleteProductionPlan(string $planId): void
    {
        $this->planModel->deleteProductionPlan($planId);
    }

    /**
     * Updates a production plan in the database.
     *
     * @param string $planId The ID of the production plan to update.
     * @param string $displayName The new display name of the production plan.
     * @param array $items An associative array of item IDs and amounts for this plan.
     */
    public function updateProductionPlan(string $planId, string $displayName, array $items): void
    {
        if ($this->planModel->updateProductionPlan($planId, $displayName, $items)) {
            header('Location: /plans');
        } else {
            http_response_code(500);
        }
    }
}