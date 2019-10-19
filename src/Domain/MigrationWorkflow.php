<?php 

namespace MrCoto\MigrationWorkflow\Domain;

use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowCollection;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep;

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
     */
    public static function step(string $type, array $files) : MigrationWorkflowStep
    {
        return new MigrationWorkflowStep($type, $files);
    }

}