<?php 

namespace MrCoto\MigrationWorkflow\Test\Domain;

use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationWorkflowHookHandler;
use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationWorkflowHandler;
use MrCoto\MigrationWorkflow\Domain\Logger\Logger;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\MigrationStepEloquentHandler;
use MrCoto\MigrationWorkflow\Test\Stub\Workflow\DummyWorkflow;
use PHPUnit\Framework\TestCase;

class MigrationWorkflowHandlerDomainTest extends TestCase
{

    public function test_should_handle_workflow()
    {
        $loggerMock = $this->getMockBuilder(Logger::class)->getMock();
        $migrationHandlerMock = $this->getMockBuilder(MigrationStepEloquentHandler::class)->getMock();
        $seedHandlerMock = $this->getMockBuilder(MigrationStepEloquentHandler::class)->getMock();
        $hookHandlerMock = $this->getMockBuilder(MigrationWorkflowHookHandler::class)->getMock();
        $workflowHandler = new MigrationWorkflowHandler(
            $loggerMock,
            $migrationHandlerMock,
            $seedHandlerMock,
            $hookHandlerMock
        );

        $hookHandlerMock->expects($this->exactly(1))->method('beforeAll');
        $hookHandlerMock->expects($this->exactly(1))->method('afterAll');

        $seedHandlerMock->expects($this->exactly(1))->method('handle');
        $migrationHandlerMock->expects($this->exactly(2))->method('handle');

        $workflowHandler->handle(
            new DummyWorkflow
        );
    }

}