<?php 

namespace MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent;

use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationWorkflowStepHandler;

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