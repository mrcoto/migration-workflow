<?php 

namespace MrCoto\MigrationWorkflow\Action\Deploy\Contract;

use MrCoto\MigrationWorkflow\Core\ValueObject\PathInfo;

interface DeployRepositoryContract
{

    /**
     * Create migration workflow table if not exists
     *
     * @param string $tableName
     * @return void
     */
    public function createTableIfNotExists(string $tableName);

    /**
     * Create migration workflow detail table if not exists
     *
     * @param string $tableName
     * @param string $detailTableName
     * @return void
     */
    public function createDetailTableIfNotExists(string $tableName, string $detailTableName);


    /**
     * Return true if migration workflow is present in database
     *
     * @param string $tableName
     * @param PathInfo $workflowData
     * @return bool
     */
    public function exists(string $tableName, PathInfo $workflowData) : bool;

    /**
     * Save a specific migration workflow contract
     *
     * @param string $tableName
     * @param string $detailTableName
     * @param PathInfo $workflowData
     * @return void
     */
    public function save(string $tableName, string $detailTableName, PathInfo $workflowData);

}