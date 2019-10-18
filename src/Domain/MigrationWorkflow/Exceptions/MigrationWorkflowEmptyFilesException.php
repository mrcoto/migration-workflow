<?php 

namespace MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\Exceptions;

use Exception;

class MigrationWorkflowEmptyFilesException extends Exception
{

    public function __construct()
    {
        parent::__construct("The 'files' array can't be empty");
    }

}