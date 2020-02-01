<?php 

namespace MrCoto\MigrationWorkflow\Action\Delete\Handler;

use MrCoto\MigrationWorkflow\Action\Delete\Contract\DeleteRepositoryContract;
use MrCoto\MigrationWorkflow\Action\Delete\ValueObject\DeleteData;
use ReflectionClass;

class DeleteHandler
{

    /** @var DeleteData $deleteData */
    private $deleteData;

    /** @var DeleteRepositoryContract $deleteRepository */
    private $deleteRepository;

    public function __construct(
        DeleteData $deleteData,
        DeleteRepositoryContract $deleteRepository
    )
    {
        $this->deleteData = $deleteData;
        $this->deleteRepository = $deleteRepository;
    }

    /**
     * Remove migration workflow from database
     *
     * @return void
     */
    public function deleteFromDatabase()
    {
        $this->deleteRepository->delete(
            $this->deleteData->tableName(),
            $this->deleteData->detailTableName(),
            $this->deleteData->workflowData()
        );
    }

    /**
     * Remove created file
     *
     * @return void
     */
    public function removeFile()
    {
        $workflowData = $this->deleteData->workflowData();
        $workflow = $workflowData->workflow();
        $reflection = new ReflectionClass($workflow);
        $pathToRemove = dirname($reflection->getFileName()).'/'.$reflection->getShortName().'.php';
        unlink($pathToRemove);
    }

    /**
     * Get delete data
     *
     * @return DeleteData
     */
    public function deleteData() : DeleteData
    {
        return $this->deleteData;
    }

}