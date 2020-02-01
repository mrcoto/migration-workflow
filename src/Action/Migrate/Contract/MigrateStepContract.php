<?php 

namespace MrCoto\MigrationWorkflow\Action\Migrate\Contract;

use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep;

interface MigrateStepContract
{

    /**
     * Handle migration workflow's migration step
     *
     * @param int $stepNumber
     * @param MigrationWorkflowStep $step
     * @return void
     */
    public function handleMigration(int $stepNumber, MigrationWorkflowStep $step);

    /**
     * Handle migration workflow's seed step
     *
     * @param int $stepNumber
     * @param MigrationWorkflowStep $step
     * @return void
     */
    public function handleSeed(int $stepNumber, MigrationWorkflowStep $step);

}