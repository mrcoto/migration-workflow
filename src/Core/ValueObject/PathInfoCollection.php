<?php 

namespace MrCoto\MigrationWorkflow\Core\ValueObject;

use MrCoto\MigrationWorkflow\Core\MigrationWorkflowContract;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflowConstant;
use ReflectionClass;

class PathInfoCollection
{

    /** @var PathInfo[] $items */
    private $items;

    public function __construct(array $workflowPaths, array $versions = [])
    {
        $workflows = $this->getWorkflowContractsData($workflowPaths);
        $workflows = $this->applyVersionFilter($workflows, $versions);
        $workflows = $this->applySortAscending($workflows);
        $this->items = array_map(function(PathInfo $workflowData) {
            return new PathInfo(
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
            $workflowsData[] = new PathInfo($workflow);
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
        return array_filter($workflows, function(PathInfo $workflow) use ($versions) {
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
        usort($workflows, function(PathInfo $left, PathInfo $right) {
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
     * @return PathInfo[]
     */
    public function items() : array
    {
        return $this->items;
    }

}