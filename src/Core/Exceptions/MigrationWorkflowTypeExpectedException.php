<?php 

namespace MrCoto\MigrationWorkflow\Core\Exceptions;

use Exception;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflowConstant;

class MigrationWorkflowTypeExpectedException extends Exception
{

    public function __construct(string $type)
    {
        $availableTypes = implode(', ', MigrationWorkflowConstant::TYPES);
        parent::__construct("Type '$type' is not available. Available types are: $availableTypes");
    }

}