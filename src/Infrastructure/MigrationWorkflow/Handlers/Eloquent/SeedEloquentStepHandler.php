<?php 

namespace MrCoto\MigrationWorkflow\Infrastructure\MigrationWorkflow\Handlers\Eloquent;

use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\Handlers\MigrationWorkflowStepHandler;

class SeedEloquentStepHandler implements MigrationWorkflowStepHandler
{
    
    /**
     * Handle seed step with eloquent
     *
     * @param integer $stepNumber
     * @param MigrationWorkflowStep $step
     * @return void
     */
    public function handle(int $stepNumber, MigrationWorkflowStep $step)
    {
        
    }

}