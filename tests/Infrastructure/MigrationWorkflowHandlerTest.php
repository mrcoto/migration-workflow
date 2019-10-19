<?php

namespace MrCoto\MigrationWorkflow\Test\Infrastructure;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationWorkflowHandler;
use MrCoto\MigrationWorkflow\Domain\Logger\Logger;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\HookEloquentHandler;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\SeedEloquentStepHandler;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\MigrationEloquentStepHandler;
use MrCoto\MigrationWorkflow\Test\LaravelTest;
use MrCoto\MigrationWorkflow\Test\Stub\Workflow\DummyWorkflow;
use MrCoto\MigrationWorkflow\Test\Stub\Workflow\DummyWorkflowWithError;

class MigrationWorkflowHandlerTest extends LaravelTest
{

    public function test_should_rollback_transaction()
    {
        $this->expectException(Exception::class);
        Artisan::call('vendor:publish', ['--provider' => 'MrCoto\MigrationWorkflow\Test\Stub\FakeServiceProvider']);
        Schema::dropIfExists('dummy');
        $loggerMock = $this->getMockBuilder(Logger::class)->getMock();
        $workflowHandler = new MigrationWorkflowHandler(
            $loggerMock,
            new MigrationEloquentStepHandler,
            new SeedEloquentStepHandler,
            new HookEloquentHandler
        );
        $workflowHandler->handle(
            new DummyWorkflowWithError
        );
        // throw exception in seed, before dropping dummy table
        $this->assertTrue(Schema::hasTable('dummy'));
    }

    public function test_should_handle_workflow()
    {
        Artisan::call('vendor:publish', ['--provider' => 'MrCoto\MigrationWorkflow\Test\Stub\FakeServiceProvider']);
        Schema::dropIfExists('dummy');
        $loggerMock = $this->getMockBuilder(Logger::class)->getMock();
        $workflowHandler = new MigrationWorkflowHandler(
            $loggerMock,
            new MigrationEloquentStepHandler,
            new SeedEloquentStepHandler,
            new HookEloquentHandler
        );
        $workflowHandler->handle(
            new DummyWorkflow
        );
        $this->assertFalse(Schema::hasTable('dummy'));
    }

}