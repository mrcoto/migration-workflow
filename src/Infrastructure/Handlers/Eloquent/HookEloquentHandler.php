<?php 

namespace MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent;

use Illuminate\Support\Facades\DB;
use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationWorkflowHookHandler;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowCollection;

class HookEloquentHandler implements MigrationWorkflowHookHandler
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