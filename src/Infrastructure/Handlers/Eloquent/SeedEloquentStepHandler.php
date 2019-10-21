<?php 

namespace MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent;

use Illuminate\Database\Seeder;
use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationWorkflowStepHandler;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep;
use MrCoto\MigrationWorkflow\Infrastructure\Exceptions\ClassFileIsNotSeederException;
use ReflectionClass;

class SeedEloquentStepHandler extends Seeder implements MigrationWorkflowStepHandler
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
            $reflection = new ReflectionClass($seedClass);
            if ($reflection->isInstantiable()) {
                $seedObj = new $seedClass;
                if (!$seedObj instanceof Seeder) {
                    throw new ClassFileIsNotSeederException($seedClass);
                }
                $seedObj->run();
            } else {
                // Call default seeds from database/seeders
                $this->callSilent($seedClass); 
            }
        }
    }

}