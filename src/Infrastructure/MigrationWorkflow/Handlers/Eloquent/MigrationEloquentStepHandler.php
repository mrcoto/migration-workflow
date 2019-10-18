<?php 

namespace MrCoto\MigrationWorkflow\Infrastructure\MigrationWorkflow\Handlers\Eloquent;

use Illuminate\Support\Facades\Artisan;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\Handlers\MigrationWorkflowStepHandler;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\ValueObject\MigrationWorkflowStep;
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
        // $db = new Database;
        // $connection = $db->connection();
        // var_dump($connection->table('random_table')->get()->toArray());
        foreach($step->files() as $file)
        {
            $fileName = "$file.php";
            $filePath = base_path($fileName);
            if (!file_exists($filePath)) {
                throw new MigrationFileNotFoundException($fileName);
            }
        }
    }

}