<?php 

namespace MrCoto\MigrationWorkflow\Action\Migrate\Contract;

use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowCollection;
use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep;

interface MigrateHookContract
{

    /**
     * Hook executed before all steps
     *
     * @param MigrationWorkflowCollection $workflow
     * @return void
     */
    public function beforeAll(MigrationWorkflowCollection $workflow);

    /**
     * Hook executed after all steps
     *
     * @param MigrationWorkflowCollection $workflow
     * @return void
     */
    public function afterAll(MigrationWorkflowCollection $workflow);

    /**
     * Hook executed on exception thrown
     *
     * @param MigrationWorkflowCollection $workflow
     * @param MigrationWorkflowStep $step
     * @param integer $stepNumber
     * @return void
     */
    public function onError(MigrationWorkflowCollection $workflow, MigrationWorkflowStep $step, int $stepNumber);

}