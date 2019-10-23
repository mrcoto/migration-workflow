<?php 

namespace MrCoto\MigrationWorkflow\Domain\Handlers;

use MrCoto\MigrationWorkflow\Domain\Contracts\DeleteMigrationWorkflowTableHandlerContract;
use MrCoto\MigrationWorkflow\Domain\Logger\Logger;
use MrCoto\MigrationWorkflow\Domain\Logger\LoggerFactory;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationDeleteData;
use ReflectionClass;

class DeleteMigrationWorkflowHandler
{

    /** @var Logger $logger */
    private $logger;

    /** @var MigrationDeleteData $deleteData */
    private $deleteData;

    /** @var DeleteMigratonWorkflowTableHandler $DeleteMigrationWorkflowTableHandlerContract */
    private $DeleteMigrationWorkflowTableHandlerContract;

    public function __construct(
        MigrationDeleteData $deleteData,
        DeleteMigrationWorkflowTableHandlerContract $DeleteMigrationWorkflowTableHandlerContract
    )
    {
        $this->logger = LoggerFactory::getLogger();
        $this->deleteData = $deleteData;
        $this->DeleteMigrationWorkflowTableHandlerContract = $DeleteMigrationWorkflowTableHandlerContract;
    }

    /**
     * Remove migration workflow from database
     *
     * @return void
     */
    public function deleteMigrationWorkflowFromDatabase()
    {
        $this->DeleteMigrationWorkflowTableHandlerContract->deleteMigrationWorkflow(
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
     * @return MigrationDeleteData
     */
    public function deleteData() : MigrationDeleteData
    {
        return $this->deleteData;
    }

}