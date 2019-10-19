<?php 

namespace MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent;

use Illuminate\Database\Migrations\Migration;
use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationWorkflowStepHandler;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep;
use MrCoto\MigrationWorkflow\Infrastructure\Exceptions\ClassFileIsNotMigrationException;
use MrCoto\MigrationWorkflow\Infrastructure\Exceptions\MigrationFileNotFoundException;

class MigrationEloquentStepHandler implements MigrationWorkflowStepHandler
{
    
    /**
     * Handle migration step with eloquent
     *
     * @param integer $stepNumber
     * @param MigrationWorkflowStep $step
     * @return void
     */
    public function handle(int $stepNumber, MigrationWorkflowStep $step)
    {
        foreach($step->files() as $file)
        {
            $fileName = "$file.php";
            $filePath = base_path($fileName);
            if (!file_exists($filePath)) {
                throw new MigrationFileNotFoundException($fileName);
            }
            require_once $filePath;
            /**
             * Note: Before i use tokenizer to get Class from File,
             * For some reason this doesn't work for testing with Orchestra,
             * That's the reason why i'm using get_declared_classes()
             */
            $declaredClasses = get_declared_classes();
            $migrationClass = end($declaredClasses);
            $migrationObj = new $migrationClass;
            if (!$migrationObj instanceof Migration) {
                throw new ClassFileIsNotMigrationException($file);
            }
            $migrationObj->up();
        }
    }

}