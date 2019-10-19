<?php 

namespace MrCoto\MigrationWorkflow\Domain\Exceptions;

use Exception;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflowToken;

class MigrationWorkflowTypeExpectedException extends Exception
{

    public function __construct(string $type)
    {
        $availableTypes = implode(', ', MigrationWorkflowToken::TYPES);
        parent::__construct("Type '$type' is not available. Available types are: $availableTypes");
    }

}