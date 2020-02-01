<?php 

namespace MrCoto\MigrationWorkflow\Action\Delete\ValueObject;

use HaydenPierce\ClassFinder\ClassFinder;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflowConstant;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflowContract;
use ReflectionClass;

class DeletePathInfoCollection
{

    /** @var DeletePathInfo[] $items */
    private $items;

    public function __construct(array $workflowPaths, array $versions = [])
    {
        $workflows = $this->getWorkflowContractsData($workflowPaths);
        $workflows = $this->applyVersionFilter($workflows, $versions);
        $workflows = $this->applySortAscending($workflows);
        $this->items = array_map(function(DeletePathInfo $workflowData) {
            return new DeletePathInfo(
                $workflowData->workflow()
            );
        }, $workflows);
    }

    /**
     * Get all classes for given namespaces
     *
     * @param array $workflowPaths
     * @return MigrationWorkflowContract[]
     */
    private function getWorkflowContractsData(array $workflowPaths) : array
    {
        $finder = new \Symfony\Component\Finder\Finder();
        $iter = new \hanneskod\classtools\Iterator\ClassIterator(
            $finder->files()
                ->name(MigrationWorkflowConstant::MIGRATION_WORKFLOW_FILE_REGEX)
                ->in($workflowPaths)
        );
        $workflowsData = [];
        foreach ($iter->type(MigrationWorkflowContract::class) as $workflowType) {
            $reflection = new ReflectionClass($workflowType->getName());
            $workflow = $reflection->newInstance();
            $workflowsData[] = new DeletePathInfo($workflow);
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
        return array_filter($workflows, function(DeletePathInfo $workflow) use ($versions) {
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
        usort($workflows, function(DeletePathInfo $left, DeletePathInfo $right) {
            if ($left->timestamp() == $right->timestamp()) {
                return 0;
            }
            return ($left->timestamp() < $right->timestamp()) ? -1 : 1;
        });
        return $workflows;
    }

    /**
     * Get migration workflow data rows
     *
     * @return DeletePathInfo[]
     */
    public function items() : array
    {
        return $this->items;
    }

}