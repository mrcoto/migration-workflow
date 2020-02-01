<?php 

namespace MrCoto\MigrationWorkflow\Test\Integration\Action\Deploy\Handler;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use MrCoto\MigrationWorkflow\Action\Deploy\Handler\DeployHandler;
use MrCoto\MigrationWorkflow\Action\Deploy\Handler\DeployRepository;
use MrCoto\MigrationWorkflow\Action\Deploy\ValueObject\DeployData;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\MigrateHandler;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\MigrateHookHandler;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\MigrateStepHandler;
use MrCoto\MigrationWorkflow\Test\LaravelTest;

class DeployHandlerTest extends LaravelTest
{

    public function test_should_run_dev_migration_workflows()
    {
        Schema::dropIfExists('migration_workflow_detail');
        Schema::dropIfExists('migration_workflow');
        Schema::dropIfExists('dummy');
        
        $deployData = $this->get_deploy_data(['dev']);
        $deployHandler = $this->get_deploy_handler($deployData);
        $deployHandler->deploy();

        $this->assertTrue(Schema::hasTable('migration_workflow'));
        $this->assertTrue(Schema::hasTable('migration_workflow_detail'));
        $this->assertTrue(Schema::hasTable('dummy'));
        $this->assertDatabaseHas('dummy', ['dummy_column' => 'dummy_value']);
        $this->assertDatabaseHas('migration_workflow', ['workflow_class' => 'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data1\CreateDummyWorkflow_dev_2019_10_21_101600']);
        $this->assertDatabaseHas('migration_workflow', ['workflow_class' => 'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data2\SeedDummyWorkflow_dev_2019_10_21_101700']);
        $this->assertDatabaseHas('migration_workflow_detail', [
            'type' => 'migration',
            'file' => 'database/migrations/2019_10_19_120000_create_dummy_table'
        ]);
        $this->assertDatabaseHas('migration_workflow_detail', [
            'type' => 'seed',
            'file' => 'MrCoto\MigrationWorkflow\Test\Stub\Seeders\DummyTableSeeder'
        ]);
    }

    public function test_should_skip_dev_migration_workflows()
    {
        $deployData = $this->get_deploy_data(['dev']);
        $deployHandler = $this->get_deploy_handler($deployData);
        $deployHandler->deploy();

        $this->assertTrue(Schema::hasTable('migration_workflow'));
        $this->assertTrue(Schema::hasTable('migration_workflow_detail'));
        $this->assertTrue(Schema::hasTable('dummy'));

        $this->assertEquals(2, DB::table('migration_workflow')->count());
        $this->assertEquals(2, DB::table('migration_workflow_detail')->count());
    }

    /**
     * Get Deploy Data
     *
     * @param array $versions
     * @return DeployData
     */
    private function get_deploy_data(array $versions) : DeployData
    {
        return new DeployData(
            'migration_workflow',
            'migration_workflow_detail',
            [
                'tests',
            ],
            $versions
        );
    }

    /**
     * Get Deploy Handler
     *
     * @param DeployData $deployData
     * @return DeployHandler
     */
    private function get_deploy_handler(DeployData $deployData) : DeployHandler
    {
        return new DeployHandler(
            $deployData,
            new DeployRepository,
            $this->get_migrate_handler()
        );
    }

    /**
     * Get Migrate Handler
     *
     * @return MigrateHandler
     */
    private function get_migrate_handler() : MigrateHandler
    {
        return new MigrateHandler(
            new MigrateStepHandler,
            new MigrateHookHandler
        );
    }

}