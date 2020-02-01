<?php 

namespace MrCoto\MigrationWorkflow\Action\Delete\ValueObject;

use HaydenPierce\ClassFinder\ClassFinder;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflowContract;
use ReflectionClass;

class DeletePathInfoCollection
{

    /** @var DeletePathInfo[] $items */
    private $items;

    public function __construct(array $workflowPaths, array $versions = [])
    {
        $classes = $this->getClasses($workflowPaths);
        $workflows = $this->getWorkflowContractsData($classes);
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
                $workflowsData[] = new DeletePathInfo($workflow);
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