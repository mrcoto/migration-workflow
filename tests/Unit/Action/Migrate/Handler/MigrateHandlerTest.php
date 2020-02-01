<?php 

namespace MrCoto\MigrationWorkflow\Test\Unit\Action\Migrate\Handler;

use MrCoto\MigrationWorkflow\Action\Migrate\Contract\MigrateHookContract;
use MrCoto\MigrationWorkflow\Action\Migrate\Contract\MigrateStepContract;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\MigrateHandler;
use MrCoto\MigrationWorkflow\Test\Stub\Workflow\DummyWorkflow;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MigrateHandlerTest extends TestCase
{

    public function test_should_handle_workflow()
    {
        /** @var MigrateStepContract&MockObject */
        $migrateStepHandler = $this->getMockBuilder(MigrateStepContract::class)->getMock();
        /** @var MigrateHookContract&MockObject */
        $hookHandlerMock = $this->getMockBuilder(MigrateHookContract::class)->getMock();
        $migrateHandler = new MigrateHandler(
            $migrateStepHandler,
            $hookHandlerMock
        );

        $hookHandlerMock->expects($this->exactly(1))->method('beforeAll');
        $hookHandlerMock->expects($this->exactly(1))->method('afterAll');

        $migrateStepHandler->expects($this->exactly(2))->method('handleMigration');
        $migrateStepHandler->expects($this->exactly(1))->method('handleSeed');

        $migrateHandler->handle(
            new DummyWorkflow
        );
    }

}