<?php 

namespace MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\ValueObject;

use MigrationWorkflowToken;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\Exceptions\MigrationWorkflowEmptyFilesException;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\Exceptions\MigrationWorkflowTypeExpectedException;

class MigrationWorkflowStep
{

    /** @var string $type */
    private $type;

    /** @var array $files */
    private $files;

    public function __construct(string $type, array $files)
    {
        if (!in_array($type, MigrationWorkflowToken::TYPES)) {
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
     * Get Migration workflow step's files
     *
     * @return array
     */
    public function files() : array
    {
        return $this->files;
    }

}