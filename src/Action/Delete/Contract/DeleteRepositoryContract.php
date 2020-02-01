<?php 

namespace MrCoto\MigrationWorkflow\Action\Delete\Contract;

use MrCoto\MigrationWorkflow\Action\Delete\ValueObject\DeletePathInfo;

interface DeleteRepositoryContract
{

    /**
     * Delete migration workflow from database
     *
     * @param string $tableName
     * @param string $detailTableName
     * @param DeletePathInfo $workflowData
     * @return void
     */
    public function delete(string $tableName, string $detailTableName, DeletePathInfo $workflowData);

}