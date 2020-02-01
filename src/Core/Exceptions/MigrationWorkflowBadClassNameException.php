<?php 

namespace MrCoto\MigrationWorkflow\Core\Exceptions;

use Exception;

class MigrationWorkflowBadClassNameException extends Exception
{

    public function __construct(string $className)
    {
        parent::__construct("MigrationWorkflow class name should have this format: NAME_VERSION_YYYY_MM_DD_His. Given: $className");
    }

}