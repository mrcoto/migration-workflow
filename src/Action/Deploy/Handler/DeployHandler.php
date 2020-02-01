<?php 

namespace MrCoto\MigrationWorkflow\Action\Deploy\Handler;

use MrCoto\MigrationWorkflow\Action\Deploy\Contract\DeployRepositoryContract;
use MrCoto\MigrationWorkflow\Action\Deploy\ValueObject\DeployData;
use MrCoto\MigrationWorkflow\Action\Deploy\ValueObject\DeployPathInfo;
use MrCoto\MigrationWorkflow\Action\Deploy\ValueObject\DeployPathInfoCollection;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\MigrateHandler;
use MrCoto\MigrationWorkflow\Logger\LoggerFactory;

class DeployHandler
{

    private $deployData;
    private $DeployRepository;
    private $migrateHandler;

    private $logger;

    public function __construct(
        DeployData $deployData,
        DeployRepositoryContract $DeployRepository,
        MigrateHandler $migrateHandler
    )
    {
        $this->logger = LoggerFactory::getLogger();
        $this->deployData = $deployData;
        $this->DeployRepository = $DeployRepository;
        $this->migrateHandler = $migrateHandler;
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
        $this->DeployRepository->createTableIfNotExists($tableName);
        $this->DeployRepository->createDetailTableIfNotExists($tableName, $detailTableName);
        $workflowCollection = new DeployPathInfoCollection(
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
     * @param DeployPathInfo $workflowData
     * @return void
     */
    private function handleWorkflowData(string $tableName, string $detailTableName, DeployPathInfo $workflowData)
    {
        $className = get_class($workflowData->workflow());
        if ($this->DeployRepository->exists($tableName, $workflowData)) {
            $this->logger->warning("Skipping workflow $className. Already In Database");
            return;
        }
        $this->logger->info("Executing $className workflow");
        $this->migrateHandler->handle($workflowData->workflow());
        $this->DeployRepository->save($tableName, $detailTableName, $workflowData);
        $this->logger->debug("Workflow $className executed");
    }

}