<?php 

namespace MrCoto\MigrationWorkflow\Action\Migrate\Handler;

use Illuminate\Support\Facades\DB;
use MrCoto\MigrationWorkflow\Action\Migrate\Contract\MigrateHookContract;
use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowCollection;
use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep;

class MigrateHookHandler implements MigrateHookContract
{
    /**
     * Hook executed before all steps
     *
     * @param MigrationWorkflowCollection $workflow
     * @return void
     */
    public function beforeAll(MigrationWorkflowCollection $workflow)
    {
        DB::beginTransaction();
    }

    /**
     * Hook executed after all steps
     *
     * @param MigrationWorkflowCollection $workflow
     * @return void
     */
    public function afterAll(MigrationWorkflowCollection $workflow)
    {
        DB::commit();
    }

    /**
     * Hook executed on exception thrown
     *
     * @param MigrationWorkflowCollection $workflow
     * @param MigrationWorkflowStep $step
     * @param integer $stepNumber
     * @return void
     */
    public function onError(MigrationWorkflowCollection $workflow, MigrationWorkflowStep $step, int $stepNumber)
    {
        DB::rollBack();
    }
}