<?php 

namespace App\MigrationWorkflows;

use MrCoto\MigrationWorkflow\Core\MigrationWorkflowContract;
use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowCollection;
use MrCoto\MigrationWorkflow\Core\MigrationWorkflow;

class MyMigrationClass_dev_2020_04_01_150857 implements MigrationWorkflowContract
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