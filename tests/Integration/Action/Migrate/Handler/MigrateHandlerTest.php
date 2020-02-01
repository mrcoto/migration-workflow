<?php

namespace MrCoto\MigrationWorkflow\Test\Integration\Action\Migrate\Handler;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\MigrateHandler;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\MigrateHookHandler;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\MigrateStepHandler;
use MrCoto\MigrationWorkflow\Test\LaravelTest;
use MrCoto\MigrationWorkflow\Test\Stub\Workflow\DummyWorkflow;
use MrCoto\MigrationWorkflow\Test\Stub\Workflow\DummyWorkflowWithError;

class MigrateHandlerTest extends LaravelTest
{

    public function test_should_rollback_transaction()
    {
        $this->expectException(Exception::class);
        Schema::dropIfExists('dummy');
        $workflowHandler = $workflowHandler = $this->get_migrate_handler();
        $workflowHandler->handle(
            new DummyWorkflowWithError
        );
        // throw exception in seed, before dropping dummy table
        $this->assertTrue(Schema::hasTable('dummy'));
    }

    public function test_should_handle_workflow()
    {
        Schema::dropIfExists('dummy');
        $workflowHandler = $this->get_migrate_handler();
        $workflowHandler->handle(
            new DummyWorkflow
        );
        $this->assertFalse(Schema::hasTable('dummy'));
    }

    private function get_migrate_handler() : MigrateHandler
    {
        return new MigrateHandler(
            new MigrateStepHandler,
            new MigrateHookHandler
        );
    }

}