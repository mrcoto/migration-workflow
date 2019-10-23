<?php 

namespace MrCoto\MigrationWorkflow\Domain\Contracts;

use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep;

interface MigrationWorkflowStepHandlerContract
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