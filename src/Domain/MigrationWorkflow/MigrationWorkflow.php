<?php 

namespace MrCoto\MigrationWorkflow\Domain\MigrationWorkflow;

use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\ValueObject\MigrationWorkflowCollection;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\ValueObject\MigrationWorkflowStep;

/**
 * Facade to generate Workflow steps and Workflow step
 */
class MigrationWorkflow
{

    /**
     * Generate new MigrationWorkflowCollection from steps
     *
     * @param MigrationWorkflowStep[] $steps $steps
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