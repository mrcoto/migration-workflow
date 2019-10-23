<?php 

namespace MrCoto\MigrationWorkflow\Domain\ValueObject;

use ReflectionClass;

class MigrationDeleteData
{

    /** @var string $tableName */
    private $tableName;

    /** @var string $detailTableName */
    private $detailTableName;

    /** @var array $workflowPaths */
    private $workflowPaths;

    /** @var MigrationWorkflowData $workflowDataToRemove */
    private $workflowDataToRemove;

    public function __construct(
        string $tableName,
        string $detailTableName,
        array $workflowPaths,
        string $workflowNameToRemove,
        string $versionToRemove
    )
    {
        $this->tableName = $tableName;
        if (empty($this->tableName)) {
            throw new \InvalidArgumentException("tableName can't be an empty string");
        }
        $this->detailTableName = $detailTableName;
        if (empty($this->detailTableName)) {
            throw new \InvalidArgumentException("detailTableName can't be an empty string");
        }
        $this->workflowPaths = $this->uniqueAndNotEmptyStringArray($workflowPaths);
        if (empty($this->workflowPaths)) {
            throw new \InvalidArgumentException("workflowsPath can't be an empty array");
        }
        if (empty($workflowNameToRemove)) {
            throw new \InvalidArgumentException("Workflow name cannot be empty");
        }
        if (empty($versionToRemove)) {
            throw new \InvalidArgumentException("Workflow name cannot be empty");
        }
        $this->setWorkflowDataToRemove($workflowNameToRemove, $versionToRemove);
    }

    /**
     * Set Workflow data to remove
     *
     * @param string $workflowNameToRemove
     * @param string $versionToRemove
     * @return void
     */
    private function setWorkflowDataToRemove(string $workflowNameToRemove, string $versionToRemove)
    {
        $workflowsCollection = new MigrationWorkflowDataCollection($this->workflowPaths, [$versionToRemove]);
        $workflowsData = $workflowsCollection->items();
        $filteredWorkflows = array_filter($workflowsData, function(MigrationWorkflowData $workflowData) use ($workflowNameToRemove) {
            $reflection = new ReflectionClass($workflowData->workflow());
            return mb_strpos($reflection->getShortName(), $workflowNameToRemove) !== false;
        });
        $this->workflowDataToRemove = end($filteredWorkflows);
    }

    /**
     * Get unique array with string and not empty data
     *
     * @param array $data
     * @return array
     */
    private function uniqueAndNotEmptyStringArray(array $data) : array
    {
        return array_unique(array_filter($data, function($element) {
            return is_string($element) && !empty($element);
        }));
    }

    /**
     * Get table name
     *
     * @return string
     */
    public function tableName() : string
    {
        return $this->tableName;
    }

    /**
     * Get migration detail table name
     *
     * @return string
     */
    public function detailTableName() : string
    {
        return $this->detailTableName;
    }

    /**
     * Get workflow paths
     *
     * @return array
     */
    public function workflowPaths() : array
    {
        return $this->workflowPaths;
    }

    /**
     * Get migration deploy versions to run
     *
     * @return array
     */
    public function versions() : array
    {
        return $this->versions;
    }

    /**
     * Get migration workflow data
     *
     * @return MigrationWorkflowData
     */
    public function workflowData() : MigrationWorkflowData
    {
        return $this->workflowDataToRemove;
    }

}