<?php 

namespace MrCoto\MigrationWorkflow\Domain\Handlers;

use MrCoto\MigrationWorkflow\Domain\Contracts\MigrationDeployTableHandlerContract;
use MrCoto\MigrationWorkflow\Domain\Logger\Logger;
use MrCoto\MigrationWorkflow\Domain\Logger\LoggerFactory;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationDeployData;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowData;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowDataCollection;

class MigrationDeployHandler
{

    /** @var MigrationDeployData $deployData */
    private $deployData;

    /** @var MigrationDeployTableHandlerContract $tableHandler */
    private $tableHandler;

    /** @var Logger $logger */
    private $logger;

    /** @var MigrationWorkflowHandler $migrationWorkflowHandler */
    private $migrationWorkflowHandler;

    public function __construct(
        MigrationDeployData $deployData,
        MigrationDeployTableHandlerContract $tableHandler,
        MigrationWorkflowHandler $migrationWorkflowHandler
    )
    {
        $this->logger = LoggerFactory::getLogger();
        $this->deployData = $deployData;
        $this->tableHandler = $tableHandler;
        $this->migrationWorkflowHandler = $migrationWorkflowHandler;
    }

    /**
     * Deploy workflows
     *
     * @return void
     */
    public function deploy()
    {
        $tableName = $this->deployData->tableName();
        $detailTableName = $this->deployData->detailTableName();
        $this->tableHandler->createMigrationWorkflowTableIfNotExists($tableName);
        $this->tableHandler->createMigrationWorkflowDetailTableIfNotExists($tableName, $detailTableName);
        $workflowCollection = new MigrationWorkflowDataCollection(
            $this->deployData->workflowPaths(),
            $this->deployData->versions()
        );
        foreach($workflowCollection->items() as $workflowData) {
            $this->handleWorkflowData($tableName, $detailTableName, $workflowData);
        }
    }

    /**
     * Handle a workflow data
     *
     * @param string $tableName
     * @param string $detailTableName
     * @param MigrationWorkflowData $workflowData
     * @return void
     */
    private function handleWorkflowData(string $tableName, string $detailTableName, MigrationWorkflowData $workflowData)
    {
        $className = get_class($workflowData->workflow());
        if ($this->tableHandler->isWorkflowPresentInDatabase($tableName, $workflowData)) {
            $this->logger->warning("Skipping workflow $className. Already In Database");
            return;
        }
        $this->logger->info("Executing $className workflow");
        $this->migrationWorkflowHandler->handle($workflowData->workflow());
        $this->tableHandler->saveMigrationWorkflow($tableName, $detailTableName, $workflowData);
        $this->logger->debug("Workflow $className executed");
    }

}