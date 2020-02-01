<?php

namespace MrCoto\MigrationWorkflow\Test\Integration\Action\Migrate\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use MrCoto\MigrationWorkflow\Test\LaravelTest;

class MigrateMigrationWorkflowCommandTest extends LaravelTest
{
    
    public function test_should_create_and_seed_dummy_table()
    {
        Schema::dropIfExists('dummy');
        Artisan::call('migrate:workflow', ['--class' => 'MrCoto\MigrationWorkflow\Test\Stub\Workflow\DummyWorkflowCreate']);
        $this->assertTrue(Schema::hasTable('dummy'));
        $this->assertDatabaseHas('dummy', ['dummy_column' => 'dummy_value']);
    }

    public function test_should_drop_dummy_table()
    {
        Artisan::call('migrate:workflow', ['--class' => 'MrCoto\MigrationWorkflow\Test\Stub\Workflow\DummyWorkflowDrop']);
        $this->assertFalse(Schema::hasTable('dummy'));
    }

}