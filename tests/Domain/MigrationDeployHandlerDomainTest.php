<?php 

namespace MrCoto\MigrationWorkflow\Test\Domain;

use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationDeployHandler;
use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationDeployTableHandler;
use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationWorkflowHandler;
use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationWorkflowHookHandler;
use MrCoto\MigrationWorkflow\Domain\Logger\Logger;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationDeployData;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\MigrationEloquentStepHandler;
use PHPUnit\Framework\TestCase;

class MigrationDeployHandlerDomainTest extends TestCase
{

    public function test_should_run_dev_migration_workflows()
    {
        $loggerMock = $this->getMockBuilder(Logger::class)->getMock();
        $tableHandlerMock = $this->getMockBuilder(MigrationDeployTableHandler::class)->getMock();
        $tableHandlerMock->method('isWorkflowPresentInDatabase')->will($this->onConsecutiveCalls(
            'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data1\CreateDummyWorkflow_dev_2019_10_21_101600',
            'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data2\SeedDummyWorkflow_dev_2019_10_21_101700'
        ))->willReturn(true);

        $migrationHandlerMock = $this->getMockBuilder(MigrationEloquentStepHandler::class)->getMock();
        $seedHandlerMock = $this->getMockBuilder(MigrationEloquentStepHandler::class)->getMock();
        $hookHandlerMock = $this->getMockBuilder(MigrationWorkflowHookHandler::class)->getMock();
        $migrationWorkflowHandler = new MigrationWorkflowHandler(
            $loggerMock,
            $migrationHandlerMock,
            $seedHandlerMock,
            $hookHandlerMock
        );
            
        $migrationDeployHandler = new MigrationDeployHandler(
            new MigrationDeployData(
                'table_name',
                'detail_table_name',
                [
                    'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data2',
                    'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data1',
                ],
                ['dev']
            ),
            $tableHandlerMock,
            $migrationWorkflowHandler,
            $loggerMock
        );

        $tableHandlerMock->expects($this->exactly(1))->method('createMigrationWorkflowTableIfNotExists');
        $tableHandlerMock->expects($this->exactly(1))->method('createMigrationWorkflowDetailTableIfNotExists');
        $tableHandlerMock->expects($this->exactly(2))->method('isWorkflowPresentInDatabase');
        $tableHandlerMock->expects($this->exactly(2))->method('saveMigrationWorkflow');

        $migrationDeployHandler->deploy();
    }

    public function test_should_run_prod_migration_workflows()
    {
        $loggerMock = $this->getMockBuilder(Logger::class)->getMock();
        $tableHandlerMock = $this->getMockBuilder(MigrationDeployTableHandler::class)->getMock();
        $tableHandlerMock->method('isWorkflowPresentInDatabase')->willReturn(false);

        $migrationHandlerMock = $this->getMockBuilder(MigrationEloquentStepHandler::class)->getMock();
        $seedHandlerMock = $this->getMockBuilder(MigrationEloquentStepHandler::class)->getMock();
        $hookHandlerMock = $this->getMockBuilder(MigrationWorkflowHookHandler::class)->getMock();
        $migrationWorkflowHandler = new MigrationWorkflowHandler(
            $loggerMock,
            $migrationHandlerMock,
            $seedHandlerMock,
            $hookHandlerMock
        );
            
        $migrationDeployHandler = new MigrationDeployHandler(
            new MigrationDeployData(
                'table_name',
                'detail_table_name',
                [
                    'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data2',
                    'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data1',
                ],
                ['prod']
            ),
            $tableHandlerMock,
            $migrationWorkflowHandler,
            $loggerMock
        );

        $tableHandlerMock->expects($this->exactly(1))->method('createMigrationWorkflowTableIfNotExists');
        $tableHandlerMock->expects($this->exactly(1))->method('createMigrationWorkflowDetailTableIfNotExists');
        $tableHandlerMock->expects($this->exactly(1))->method('isWorkflowPresentInDatabase');
        $tableHandlerMock->expects($this->exactly(0))->method('saveMigrationWorkflow');

        $migrationDeployHandler->deploy();
    }

}