<?php 

namespace MrCoto\MigrationWorkflow\Domain\Handlers;

use MrCoto\MigrationWorkflow\Domain\MigrationWorkflowContract;
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
     * @return void
     */
    public function createMigrationWorkflowDetailTableIfNotExists(string $tableName);


    /**
     * Return true if migration workflow is present in database
     *
     * @param MigrationWorkflowData $workflowData
     * @return bool
     */
    public function isWorkflowPresentInDatabase(MigrationWorkflowData $workflowData) : bool;

    /**
     * Save a specific migration workflow contract
     *
     * @param MigrationWorkflowData $workflowData
     * @return void
     */
    public function saveMigrationWorkflow(MigrationWorkflowData $workflowData);

}