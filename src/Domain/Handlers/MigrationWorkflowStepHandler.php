<?php 

namespace MrCoto\MigrationWorkflow\Domain\Handlers;

use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep;

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