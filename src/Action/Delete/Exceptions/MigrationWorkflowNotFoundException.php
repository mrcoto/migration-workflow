<?php 

namespace MrCoto\MigrationWorkflow\Action\Delete\Exceptions;

use Exception;

class MigrationWorkflowNotFoundException extends Exception
{

    public function __construct(string $workflowName, string $version)
    {
        parent::__construct("Migration Workflow '$workflowName' with version '$version' not found");
    }

}