<?php 

namespace MrCoto\MigrationWorkflow\Domain\Contracts;

use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowCollection;

interface MigrationWorkflowHookHandlerContract
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