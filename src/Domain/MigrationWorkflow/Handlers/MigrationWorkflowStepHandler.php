<?php 

namespace MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\Handlers;

use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\ValueObject\MigrationWorkflowStep;

interface MigrationWorkflowStepHandler
{

    /**
     * Handle migration workflow step
     *
     * @param int $stepNumber
     * @param MigrationWorkflowStep $step
     * @return void
     */
    public function handle(int $stepNumber, MigrationWorkflowStep $step);

}