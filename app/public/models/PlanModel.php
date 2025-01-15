<?php

require_once(__DIR__ . '/BaseModel.php');
require_once(__DIR__ . '/../dto/PlanDTO.php');

/**
 * PlanModel class extends BaseModel to interact with the PRODUCTION PLAN entity in the database.
 */
class PlanModel extends BaseModel
{
    /**
     * Fetches all production plans for the provided user.
     *
     * @return array An array of production plan objects.
     */
    public function fetchAllPlans(string $userId): array
    {
        $query = self::$pdo->prepare(
            'SELECT id, created_by, display_name
                    FROM `PRODUCTION PLAN`
                    WHERE created_by = :userId'
        );
        $query->execute(['userId' => $userId]);
        $plans = $query->fetchAll(PDO::FETCH_ASSOC);
        $dtos = [];

        foreach ($plans as $plan) {
            $items = $this->getPlanItems($plan['id']);

            $dto = new PlanDTO(
                $plan['id'],
                $plan['created_by'],
                $plan['display_name'],
                $items
            );
            $dtos[] = $dto;
        }

        return $dtos;
    }

    /**
     * Retrieves items for the provided production plan.
     *
     * @param string $planId The ID of the production plan.
     * @return array An associative array of item IDs and amounts.
     */
    private function getPlanItems(string $planId): array
    {
        $query = self::$pdo->prepare(
            'SELECT item_id, amount
                    FROM `PRODUCTION PLAN CONTENT`
                    WHERE plan_id = :planId'
        );
        $query->execute(['planId' => $planId]);
        $items = $query->fetchAll(PDO::FETCH_ASSOC);
        $plan_items = [];

        foreach ($items as $item) {
            $plan_items[$item['item_id']] = $item['amount'];
        }

        return $plan_items;
    }

    /**
     * Retrieves a production plan by its ID.
     *
     * @param string $planId The ID of the production plan to retrieve.
     * @return PlanDTO The data transfer object representing the production plan.
     */
    public function getProductionPlan(string $planId): PlanDTO
    {
        $query = self::$pdo->prepare(
            'SELECT id, created_by, display_name
                    FROM `PRODUCTION PLAN`
                    WHERE id = :planId'
        );
        $query->execute(['planId' => $planId]);
        $plan = $query->fetch(PDO::FETCH_ASSOC);
        $items = $this->getPlanItems($planId);

        return new PlanDTO(
            $plan['id'],
            $plan['created_by'],
            $plan['display_name'],
            $items
        );
    }

    /**
     * Retrieves a production plan by its name.
     *
     * @param string $planName The name of the production plan to retrieve.
     * @return PlanDTO|null The data transfer object representing the production plan or null the plan is not found.
     */
    public function getProductionPlanByName(string $planName): ?PlanDTO
    {
        $query = self::$pdo->prepare(
            'SELECT id, created_by, display_name
                    FROM `PRODUCTION PLAN`
                    WHERE display_name = :planName'
        );
        $query->execute(['planName' => $planName]);
        $plan = $query->fetch(PDO::FETCH_ASSOC);

        if (!$plan) {
            return null;
        }

        $items = $this->getPlanItems($plan['id']);

        return new PlanDTO(
            $plan['id'],
            $plan['created_by'],
            $plan['display_name'],
            $items
        );
    }

    /**
     * Creates a new production plan in the database.
     *
     * @param string $createdBy The user who created the production plan.
     * @param string $displayName The display name of the production plan.
     * @param array $items An associative array of item IDs and amounts for this plan.
     * @return bool True if the plan was created successfully, false otherwise.
     */
    public function createProductionPlan(string $createdBy, string $displayName, array $items): bool
    {
        try {
            // Begin transaction to ensure the correct production plan id is returned
            self::$pdo->beginTransaction();

            $insertPlanQuery = self::$pdo->prepare(
                'INSERT INTO `PRODUCTION PLAN` (created_by, display_name) VALUES (:createdBy, :displayName)'
            );
            $insertPlanQuery->bindParam("createdBy", $createdBy);
            $insertPlanQuery->bindParam("displayName", $displayName);
            $insertPlanQuery->execute();
            $insertPlanQuery->closeCursor();

            // Get the ID of the created production plan
            $planId = self::$pdo->lastInsertId();

            // Insert items associated with the production plan
            $this->createPlanItems($planId, $items);

            // Commit transaction
            self::$pdo->commit();
            return true;
        } catch (Exception $e) {
            // Rollback transaction in case of error
            self::$pdo->rollBack();
            $_SESSION['plan_error'] = 'Failed to save the production plan. Please, try again.';
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Deletes a production plan from the database.
     *
     * @param string $planId The ID of the production plan to delete.
     * @return bool True if the plan was deleted successfully, false otherwise.
     */
    public function deleteProductionPlan(string $planId): bool
    {
        try {
            // Begin transaction
            self::$pdo->beginTransaction();

            // Delete items associated with the production plan
            $this->deletePlanItems($planId);

            // Delete the production plan
            $deletePlanQuery = self::$pdo->prepare(
                'DELETE FROM `PRODUCTION PLAN` WHERE id = :planId'
            );
            $deletePlanQuery->bindParam('planId', $planId);
            $deletePlanQuery->execute();
            $deletePlanQuery->closeCursor();

            // Commit transaction
            self::$pdo->commit();
            return true;
        } catch (Exception $e) {
            // Rollback transaction in case of error
            self::$pdo->rollBack();
            $_SESSION['plan_error'] = 'Failed to delete the production plan. Please, try again.';
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Updates a production plan in the database.
     *
     * @param string $planId The ID of the production plan to update.
     * @param string $displayName The new display name of the production plan.
     * @param array $items An associative array of item IDs and amounts for this plan.
     * @return bool True if the plan was updated successfully, false otherwise.
     */
    public function updateProductionPlan(string $planId, string $displayName, array $items): bool
    {
        try {
            // Begin transaction
            self::$pdo->beginTransaction();

            // Update the production plan
            $updatePlanQuery = self::$pdo->prepare(
                'UPDATE `PRODUCTION PLAN` SET display_name = :displayName WHERE id = :planId'
            );
            $updatePlanQuery->bindParam('displayName', $displayName);
            $updatePlanQuery->bindParam('planId', $planId);
            $updatePlanQuery->execute();
            $updatePlanQuery->closeCursor();

            // Delete existing items associated with the production plan
            $this->deletePlanItems($planId);

            // Insert new items
            $this->createPlanItems($planId, $items);

            // Commit transaction
            self::$pdo->commit();
            return true;
        } catch (Exception $e) {
            // Rollback transaction in case of error
            self::$pdo->rollBack();
            $_SESSION['plan_error'] = 'Failed to update the production plan. Please, try again.';
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Inserts items associated with a production plan into the database.
     *
     * @param string $planId The ID of the production plan.
     * @param array $items An associative array of item IDs and amounts.
     */
    private function createPlanItems(string $planId, array $items): void {
        foreach ($items as $itemId => $amount) {
            $insertItemQuery = self::$pdo->prepare(
                'INSERT INTO `PRODUCTION PLAN CONTENT` (plan_id, item_id, amount) 
                        VALUES (:planId, :itemId, :amount)'
            );
            $insertItemQuery->bindParam('planId', $planId);
            $insertItemQuery->bindParam('itemId', $itemId);
            $insertItemQuery->bindParam('amount', $amount);
            $insertItemQuery->execute();
            $insertItemQuery->closeCursor();
        }
    }

    /**
     * Deletes items associated with a production plan from the database.
     *
     * @param string $planId The ID of the production plan.
     */
    private function deletePlanItems(string $planId): void {
        $deleteItemsQuery = self::$pdo->prepare(
            'DELETE FROM `PRODUCTION PLAN CONTENT` WHERE plan_id = :planId'
        );
        $deleteItemsQuery->bindParam('planId', $planId);
        $deleteItemsQuery->execute();
        $deleteItemsQuery->closeCursor();
    }
}