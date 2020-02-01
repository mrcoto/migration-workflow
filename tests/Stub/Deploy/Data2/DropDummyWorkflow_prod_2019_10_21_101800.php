<?php 

namespace MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data2;

use MrCoto\MigrationWorkflow\Core\MigrationWorkflow;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflowContract;
use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowCollection;

class DropDummyWorkflow_prod_2019_10_21_101800 implements MigrationWorkflowContract
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