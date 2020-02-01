<?php 

namespace MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data2;

use MrCoto\MigrationWorkflow\Core\MigrationWorkflow;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflowContract;
use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowCollection;

class SeedDummyWorkflow_dev_2019_10_21_101700 implements MigrationWorkflowContract
{

    public function getWorkFlow(): MigrationWorkflowCollection
    {
        return MigrationWorkflow::workflow(
            array(
                MigrationWorkflow::step('seed', [
                    'MrCoto\MigrationWorkflow\Test\Stub\Seeders\DummyTableSeeder',
                ]),
            )
        );
    }

}