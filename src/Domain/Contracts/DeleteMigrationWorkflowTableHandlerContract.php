<?php 

namespace MrCoto\MigrationWorkflow\Domain\Contracts;

use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowData;

interface DeleteMigrationWorkflowTableHandlerContract
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