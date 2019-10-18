<?php 

namespace MrCoto\MigrationWorkflow\Infrastructure\MigrationWorkflow\Handlers\Eloquent;

use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\Handlers\MigrationWorkflowStepHandler;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\ValueObject\MigrationWorkflowStep;

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
        $db = new Database;
        $connection = $db->connection();
        var_dump($connection->table('random_table')->get()->toArray());
    }

}