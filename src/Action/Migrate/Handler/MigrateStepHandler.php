<?php 

namespace MrCoto\MigrationWorkflow\Action\Migrate\Handler;

use MrCoto\MigrationWorkflow\Action\Migrate\Contract\MigrateStepContract;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\Step\MigrationFileHandler;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\Step\SeedFileHandler;
use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep;

class MigrateStepHandler implements MigrateStepContract
{

    /**
     * Handle migration workflow's migration step
     *
     * @param int $stepNumber
     * @param MigrationWorkflowStep $step
     * @return void
     */
    public function handleMigration(int $stepNumber, MigrationWorkflowStep $step)
    {
        (new MigrationFileHandler)->handle($stepNumber, $step);
    }

    /**
     * Handle migration workflow's seed step
     *
     * @param int $stepNumber
     * @param MigrationWorkflowStep $step
     * @return void
     */
    public function handleSeed(int $stepNumber, MigrationWorkflowStep $step)
    {
        (new SeedFileHandler)->handle($stepNumber, $step);
    }

}