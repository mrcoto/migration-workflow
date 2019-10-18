<?php 

namespace MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\Exceptions;

use Exception;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\MigrationWorkflowToken;

class MigrationWorkflowTypeExpectedException extends Exception
{

    public function __construct(string $type)
    {
        $availableTypes = implode(', ', MigrationWorkflowToken::TYPES);
        parent::__construct("Type '$type' is not available. Available types are: $availableTypes");
    }

}