<?php 

namespace MrCoto\MigrationWorkflow\Action\Delete\Contract;

use MrCoto\MigrationWorkflow\Core\ValueObject\PathInfo;

interface DeleteRepositoryContract
{

    /**
     * Delete migration workflow from database
     *
     * @param string $tableName
     * @param string $detailTableName
     * @param PathInfo $workflowData
     * @return void
     */
    public function delete(string $tableName, string $detailTableName, PathInfo $workflowData);

}