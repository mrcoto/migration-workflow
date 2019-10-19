<?php 

namespace MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent;

use Illuminate\Database\Seeder;
use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationWorkflowStepHandler;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep;
use MrCoto\MigrationWorkflow\Infrastructure\Exceptions\ClassFileIsNotSeederException;

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
        foreach($step->files() as $seedClass)
        {
            $seedObj = new $seedClass;
            if (!$seedObj instanceof Seeder) {
                throw new ClassFileIsNotSeederException($seedClass);
            }
            $seedObj->run();
        }
    }

}