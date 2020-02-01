<?php 

namespace MrCoto\MigrationWorkflow\Test\Stub\Workflow;

use MrCoto\MigrationWorkflow\Core\MigrationWorkflow;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflowContract;
use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowCollection;

class DummyWorkflow implements MigrationWorkflowContract
{

    public function getWorkFlow(): MigrationWorkflowCollection
    {
        return MigrationWorkflow::workflow(
            array(
                MigrationWorkflow::step('migration', [
                    'database/migrations/2019_10_19_120000_create_dummy_table',
                ]),
                MigrationWorkflow::step('seed', [
                    'MrCoto\MigrationWorkflow\Test\Stub\Seeders\DummyTableSeeder',
                ]),
                MigrationWorkflow::step('migration', [
                    'database/migrations/2019_10_19_120000_drop_dummy_table',
                ]),
            )
        );
    }

}