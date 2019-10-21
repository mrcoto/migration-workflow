<?php 

namespace MrCoto\MigrationWorkflow\Domain\ValueObject;

class MigrationDeployData
{

    /** @var string $tableName */
    private $tableName;

    /** @var string $detailTableName */
    private $detailTableName;

    /** @var array $workflowPaths */
    private $workflowPaths;

    /** @var array $versions */
    private $versions;

    public function __construct(
        string $tableName,
        string $detailTableName,
        array $workflowPaths,
        array $versions = []
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
        $this->versions = $this->uniqueAndNotEmptyStringArray($versions);
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

}