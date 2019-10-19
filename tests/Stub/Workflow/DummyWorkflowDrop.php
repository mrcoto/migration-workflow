<?php 

namespace MrCoto\MigrationWorkflow\Test\Stub\Workflow;

use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowCollection;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflowContract;

class DummyWorkflowDrop implements MigrationWorkflowContract
{

    public function getWorkFlow(): MigrationWorkflowCollection
    {
        return MigrationWorkflow::workflow(
            array(
                MigrationWorkflow::step('migration', [
                    'database/migrations/2019_10_19_120000_drop_dummy_table',
                ]),
            )
        );
    }

}