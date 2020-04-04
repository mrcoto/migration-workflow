<?php 

namespace MrCoto\MigrationWorkflow\Action\Deploy\ValueObject;

use InvalidArgumentException;

class DeployData
{

    /** @var string $tableName */
    private $tableName;

    /** @var string $detailTableName */
    private $detailTableName;

    /** @var array $workflowPaths */
    private $workflowPaths;

    /** @var array $versions */
    private $versions;

    /**
     * DeployData constructor.
     * @param string $tableName
     * @param string $detailTableName
     * @param array $workflowPaths
     * @param array $versions
     * @throws InvalidArgumentException
     */
    public function __construct(
        string $tableName,
        string $detailTableName,
        array $workflowPaths,
        array $versions = []
    )
    {
        $this->tableName = $tableName;
        $this->checkEmptyness($this->tableName, "tableName can't be an empty string");
        $this->detailTableName = $detailTableName;
        $this->checkEmptyness($this->detailTableName, "detailTableName can't be an empty string");
        $this->workflowPaths = $this->uniqueAndNotEmptyStringArray($workflowPaths);
        $this->checkEmptyness($this->workflowPaths, "workflowsPath can't be an empty array");
        $this->versions = $this->uniqueAndNotEmptyStringArray($versions);
    }

    /**
     * Check if data is empty
     * @param string|array $data Data to check
     * @param string $message Message
     * @throws InvalidArgumentException If data is empty
     */
    private function checkEmptyness($data, string $message)
    {
        if (empty($data))
            throw new InvalidArgumentException($message);
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