<?php 

namespace MrCoto\MigrationWorkflow\Action\Deploy\ValueObject;

use MrCoto\MigrationWorkflow\Core\MigrationWorkflowContract;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflowConstant;
use ReflectionClass;

class DeployPathInfoCollection
{

    /** @var DeployPathInfo[] $items */
    private $items;

    public function __construct(array $workflowPaths, array $versions = [])
    {
        $workflows = $this->getWorkflowContractsData($workflowPaths);
        $workflows = $this->applyVersionFilter($workflows, $versions);
        $workflows = $this->applySortAscending($workflows);
        $this->items = array_map(function(DeployPathInfo $workflowData) {
            return new DeployPathInfo(
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
            $workflowsData[] = new DeployPathInfo($workflow);
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
        return array_filter($workflows, function(DeployPathInfo $workflow) use ($versions) {
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
        usort($workflows, function(DeployPathInfo $left, DeployPathInfo $right) {
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
     * @return DeployPathInfo[]
     */
    public function items() : array
    {
        return $this->items;
    }

}