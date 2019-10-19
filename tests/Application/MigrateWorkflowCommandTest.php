<?php

namespace MrCoto\MigrationWorkflow\Test\Infrastructure;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use MrCoto\MigrationWorkflow\Test\LaravelTest;

class MigrateWorkflowCommandTest extends LaravelTest
{
    
    public function test_should_create_and_seed_dummy_table()
    {
        Artisan::call('vendor:publish', ['--provider' => 'MrCoto\MigrationWorkflow\Application\LaravelMigrationWorkflowServiceProvider']);
        Schema::dropIfExists('dummy');
        Artisan::call('migrate:workflow', ['--class' => 'MrCoto\MigrationWorkflow\Test\Stub\Workflow\DummyWorkflowCreate']);
        $this->assertTrue(Schema::hasTable('dummy'));
        $this->assertDatabaseHas('dummy', ['dummy_column' => 'dummy_value']);
    }

    public function test_should_drop_dummy_table()
    {
        Artisan::call('vendor:publish', ['--provider' => 'MrCoto\MigrationWorkflow\Application\LaravelMigrationWorkflowServiceProvider']);
        Artisan::call('migrate:workflow', ['--class' => 'MrCoto\MigrationWorkflow\Test\Stub\Workflow\DummyWorkflowDrop']);
        $this->assertFalse(Schema::hasTable('dummy'));
    }

}