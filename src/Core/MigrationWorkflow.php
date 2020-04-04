<?php 

namespace MrCoto\MigrationWorkflow\Core;

use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowCollection;
use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep;

/**
 * Facade to generate Workflow steps and Workflow step
 */
class MigrationWorkflow
{

    /**
     * Generate new MigrationWorkflowCollection from steps
     *
     * @param array $steps $steps
     * @return MigrationWorkflowCollection
     * @throws Exceptions\MigrationWorkflowEmptyFilesException
     * @throws Exceptions\MigrationWorkflowTypeExpectedException
     */
    public static function workflow(array $steps) : MigrationWorkflowCollection
    {
        return new MigrationWorkflowCollection($steps);
    }

    /**
     * Generate new MigrationWorkflowStep
     *
     * @param string $type
     * @param array $files
     * @return MigrationWorkflowStep
     * @throws Exceptions\MigrationWorkflowEmptyFilesException
     * @throws Exceptions\MigrationWorkflowTypeExpectedException
     */
    public static function step(string $type, array $files) : MigrationWorkflowStep
    {
        return new MigrationWorkflowStep($type, $files);
    }

}