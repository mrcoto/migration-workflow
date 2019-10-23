<?php 

namespace MrCoto\MigrationWorkflow\Domain\Handlers;

use HaydenPierce\ClassFinder\ClassFinder;
use MrCoto\MigrationWorkflow\Domain\Logger\Logger;
use MrCoto\MigrationWorkflow\Domain\Logger\LoggerFactory;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflowContract;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationDeployData;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowData;
use ReflectionClass;

class MigrationDeployHandler
{

    /** @var MigrationDeployData $deployData */
    private $deployData;

    /** @var MigrationDeployTableHandler $tableHandler */
    private $tableHandler;

    /** @var Logger $logger */
    private $logger;

    /** @var MigrationWorkflowHandler $migrationWorkflowHandler */
    private $migrationWorkflowHandler;

    public function __construct(
        MigrationDeployData $deployData,
        MigrationDeployTableHandler $tableHandler,
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
        $workflows = $this->getWorkflows(
            $this->deployData->workflowPaths(),
            $this->deployData->versions()
        );
        /** @var MigrationWorkflowData $workflow */
        foreach($workflows as $workflowData) {
            $className = get_class($workflowData->workflow());
            if ($this->tableHandler->isWorkflowPresentInDatabase($tableName, $workflowData)) {
                $this->logger->warning("Skipping workflow $className. Already In Database");
                continue;
            }
            $this->logger->info("Executing $className workflow");
            $this->migrationWorkflowHandler->handle($workflowData->workflow());
            $this->tableHandler->saveMigrationWorkflow($tableName, $detailTableName, $workflowData);
            $this->logger->debug("Workflow $className executed");
        }
    }

    /**
     * Get ordered workflows by specefied versions
     *
     * @param array $workflowPaths
     * @return MigrationWorkflowData[]
     */
    private function getWorkflows(array $workflowPaths, array $versions = []) : array
    {
        $classes = $this->getClasses($workflowPaths);
        $workflows = $this->getWorkflowContractsData($classes);
        $workflows = $this->applyVersionFilter($workflows, $versions);
        $workflows = $this->applySortAscending($workflows);
        return $workflows;
    }

    /**
     * Get all classes for given namespaces
     *
     * @param array $workflowPaths
     * @return array
     */
    private function getClasses(array $workflowPaths) : array
    {
        $classesList = [];
        foreach($workflowPaths as $path) {
            $classes = ClassFinder::getClassesInNamespace($path);
            array_map(function(string $class) use (&$classesList) {
                $classesList[] = $class;
            }, $classes);
        }
        return $classesList;
    }

    /**
     * Get all classes that implements workflow contract
     *
     * @param array $workflowClasses
     * @return MigrationWorkflowContract[] 
     */
    private function getWorkflowContractsData(array $workflowClasses) : array
    {
        $workflowsData = [];
        foreach($workflowClasses as $workflowClass) {
            $reflection = new ReflectionClass($workflowClass);
            if ($reflection->implementsInterface(MigrationWorkflowContract::class)) {
                $workflow = $reflection->newInstance();
                $workflowsData[] = new MigrationWorkflowData($workflow);
            }
        }
        return $workflowsData;
    }

    /**
     * Filters workflows by version
     *
     * @param MigrationWorkflowData[]  $versions
     * @param array $versions
     * @return MigrationWorkflowData[] 
     */
    private function applyVersionFilter(array $workflows, array $versions = []) : array
    {
        if (empty($versions)) {
            return $workflows;
        }
        return array_filter($workflows, function(MigrationWorkflowData $workflow) use ($versions) {
            return in_array($workflow->version(), $versions);
        });
    }

    /**
     * Apply sort to workflow data
     *
     * @param array $workflows
     * @return MigrationWorkflowData[] 
     */
    private function applySortAscending(array $workflows) : array
    {
        usort($workflows, function(MigrationWorkflowData $left, MigrationWorkflowData $right) {
            if ($left->timestamp() == $right->timestamp()) {
                return 0;
            }
            return ($left->timestamp() < $right->timestamp()) ? -1 : 1;
        });
        return $workflows;
    }

}