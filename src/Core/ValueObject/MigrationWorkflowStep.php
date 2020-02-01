<?php 

namespace MrCoto\MigrationWorkflow\Core\ValueObject;

use MrCoto\MigrationWorkflow\Core\Exceptions\MigrationWorkflowEmptyFilesException;
use MrCoto\MigrationWorkflow\Core\Exceptions\MigrationWorkflowTypeExpectedException;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflowConstant;

class MigrationWorkflowStep
{

    /** @var string $type */
    private $type;

    /** @var array $files */
    private $files;

    public function __construct(string $type, array $files)
    {
        if (!in_array($type, MigrationWorkflowConstant::TYPES)) {
            throw new MigrationWorkflowTypeExpectedException($type);
        }

        if (count($files) == 0) {
            throw new MigrationWorkflowEmptyFilesException();
        }

        $this->type = $type;
        $this->files = $files;
    }

    /**
     * Get Migration workflow step's type
     *
     * @return string
     */
    public function type() : string
    {
        return $this->type;
    }

    /**
     * Return true if this step is a Migration step
     *
     * @return boolean
     */
    public function isMigrationStep() : bool
    {
        return $this->type == MigrationWorkflowConstant::MIGRATION;
    }

    /**
     * Return true if this step is a Seed step
     *
     * @return boolean
     */
    public function isSeedStep() : bool
    {
        return $this->type == MigrationWorkflowConstant::SEED;
    }

    /**
     * Get Migration workflow step's files
     *
     * @return array
     */
    public function files() : array
    {
        return $this->files;
    }

}