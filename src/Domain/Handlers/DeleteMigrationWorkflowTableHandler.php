<?php 

namespace MrCoto\MigrationWorkflow\Domain\Handlers;

use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowData;

interface DeleteMigrationWorkflowTableHandler
{

    /**
     * Delete migration workflow from database
     *
     * @param string $tableName
     * @param string $detailTableName
     * @param MigrationWorkflowData $workflowData
     * @return void
     */
    public function deleteMigrationWorkflow(string $tableName, string $detailTableName, MigrationWorkflowData $workflowData);

}