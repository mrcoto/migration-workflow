<?php 

namespace MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\Exceptions;

use MigrationWorkflowToken;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\MigrationWorkflowStep;

class MigrationWorkflowTypeExpectedException extends \IllegalArgumentException
{

    public function __construct(string $type)
    {
        $availableTypes = implode(', ', MigrationWorkflowToken::TYPES);
        parent::__construct("Type '$type' is not available. Available types are: $availableTypes");
    }

}