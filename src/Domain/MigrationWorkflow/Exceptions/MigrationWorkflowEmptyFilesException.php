<?php 

namespace MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\Exceptions;

use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\MigrationWorkflowStep;

class MigrationWorkflowEmptyFilesException extends \IllegalArgumentException
{

    public function __construct()
    {
        parent::__construct("The 'files' array can't be empty");
    }

}