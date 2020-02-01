<?php

namespace MrCoto\MigrationWorkflow\Test\Integration\Action\Deploy\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use MrCoto\MigrationWorkflow\Test\LaravelTest;

class DeployMigrationWorkflowCommandTest extends LaravelTest
{
    
    public function test_should_deploy_dev_workflows()
    {
        Schema::dropIfExists('migration_workflow_detail');
        Schema::dropIfExists('migration_workflow');
        Schema::dropIfExists('dummy');

        Artisan::call('migrate:deploy', ['--versions' => 'dev']);

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

    public function test_should_deploy_skip_dev_workflows()
    {
        Artisan::call('migrate:deploy', ['--versions' => 'dev']);
        $this->assertTrue(Schema::hasTable('migration_workflow'));
        $this->assertTrue(Schema::hasTable('migration_workflow_detail'));
        $this->assertTrue(Schema::hasTable('dummy'));

        $this->assertEquals(2, DB::table('migration_workflow')->count());
        $this->assertEquals(2, DB::table('migration_workflow_detail')->count());
    }

    public function test_should_deploy_run_dev_and_prod_workflows()
    {
        Schema::dropIfExists('migration_workflow_detail');
        Schema::dropIfExists('migration_workflow');
        Schema::dropIfExists('dummy');
        
        Artisan::call('migrate:deploy', ['--versions' => 'dev,prod']);
        
        $this->assertTrue(Schema::hasTable('migration_workflow'));
        $this->assertTrue(Schema::hasTable('migration_workflow_detail'));
        $this->assertFalse(Schema::hasTable('dummy'));

        $this->assertEquals(3, DB::table('migration_workflow')->count());
        $this->assertEquals(3, DB::table('migration_workflow_detail')->count());
    }

    public function test_should_deploy_run_all_workflows()
    {
        Schema::dropIfExists('migration_workflow_detail');
        Schema::dropIfExists('migration_workflow');
        Schema::dropIfExists('dummy');
        
        Artisan::call('migrate:deploy');
        
        $this->assertTrue(Schema::hasTable('migration_workflow'));
        $this->assertTrue(Schema::hasTable('migration_workflow_detail'));
        $this->assertFalse(Schema::hasTable('dummy'));

        $this->assertEquals(3, DB::table('migration_workflow')->count());
        $this->assertEquals(3, DB::table('migration_workflow_detail')->count());
    }

}