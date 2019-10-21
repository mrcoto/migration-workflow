<?php 

namespace MrCoto\MigrationWorkflow\Domain\Handlers;

use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowData;

interface MigrationDeployTableHandler
{

    /**
     * Create migration workflow table if not exists
     *
     * @param string $tableName
     * @return void
     */
    public function createMigrationWorkflowTableIfNotExists(string $tableName);

    /**
     * Create migration workflow detail table if not exists
     *
     * @param string $tableName
     * @param string $detailTableName
     * @return void
     */
    public function createMigrationWorkflowDetailTableIfNotExists(string $tableName, string $detailTableName);


    /**
     * Return true if migration workflow is present in database
     *
     * @param string $tableName
     * @param MigrationWorkflowData $workflowData
     * @return bool
     */
    public function isWorkflowPresentInDatabase(string $tableName, MigrationWorkflowData $workflowData) : bool;

    /**
     * Save a specific migration workflow contract
     *
     * @param string $tableName
     * @param string $detailTableName
     * @param MigrationWorkflowData $workflowData
     * @return void
     */
    public function saveMigrationWorkflow(string $tableName, string $detailTableName, MigrationWorkflowData $workflowData);

}