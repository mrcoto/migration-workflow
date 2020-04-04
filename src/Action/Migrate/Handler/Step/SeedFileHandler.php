<?php 

namespace MrCoto\MigrationWorkflow\Action\Migrate\Handler\Step;

use Illuminate\Database\Seeder;
use MrCoto\MigrationWorkflow\Action\Migrate\Exceptions\ClassFileIsNotSeederException;
use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep;
use ReflectionClass;
use ReflectionException;

class SeedFileHandler extends Seeder
{

    /**
     * Handle seed step with eloquent
     *
     * @param integer $stepNumber
     * @param MigrationWorkflowStep $step
     * @return void
     * @throws ClassFileIsNotSeederException
     * @throws ReflectionException
     */
    public function handle(int $stepNumber, MigrationWorkflowStep $step)
    {
        foreach($step->files() as $seedClass) {
            $reflection = new ReflectionClass($seedClass);
            if ($reflection->isInstantiable()) {
                $this->runSeederClass($seedClass);
                continue;
            }
            // Call default seeds from database/seeders
            $this->callSilent($seedClass);
        }
    }

    /**
     * Run a single seed class
     *
     * @param string $seedClass
     * @return void
     * @throws ClassFileIsNotSeederException
     */
    private function runSeederClass(string $seedClass)
    {
        $seedObj = new $seedClass;
        if (!$seedObj instanceof Seeder) {
            throw new ClassFileIsNotSeederException($seedClass);
        }
        $seedObj->{'run'}(); // Allow to disable inteliphense method not found
    }

}