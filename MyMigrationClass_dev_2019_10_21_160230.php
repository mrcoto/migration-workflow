<?php 

namespace App\MigrationWorkflows;

use MrCoto\MigrationWorkflow\Domain\MigrationWorkflowContract;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowCollection;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow;

class MyMigrationClass_dev_2019_10_21_160230 implements MigrationWorkflowContract
{

    /**
     * Return migration workflow's step
     *
     * @return MigrationWorkflowCollection
     */
    public function getWorkFlow(): MigrationWorkflowCollection
    {
        return MigrationWorkflow::workflow(
            array(
                MigrationWorkflow::step('migration', [
                    '',
                ]),
            )
        );
    }

}