<?php 

namespace MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data2;

use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowCollection;
use MrCoto\MigrationWorkflow\Domain\MigrationWorkflowContract;

class DropDummyWorkflow_prod_2019_10_21_101600 implements MigrationWorkflowContract
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