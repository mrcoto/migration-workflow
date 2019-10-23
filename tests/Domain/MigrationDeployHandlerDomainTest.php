<?php 

namespace MrCoto\MigrationWorkflow\Test\Domain;

use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationDeployHandler;
use MrCoto\MigrationWorkflow\Domain\Contracts\MigrationDeployTableHandlerContract;
use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationWorkflowHandler;
use MrCoto\MigrationWorkflow\Domain\Contracts\MigrationWorkflowHookHandlerContract;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationDeployData;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\MigrationStepEloquentHandler;
use PHPUnit\Framework\TestCase;

class MigrationDeployHandlerDomainTest extends TestCase
{

    public function test_should_run_dev_migration_workflows()
    {
        $tableHandlerMock = $this->getMockBuilder(MigrationDeployTableHandlerContract::class)->getMock();
        $tableHandlerMock->method('isWorkflowPresentInDatabase')->will($this->onConsecutiveCalls(
            'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data1\CreateDummyWorkflow_dev_2019_10_21_101600',
            'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data2\SeedDummyWorkflow_dev_2019_10_21_101700'
        ))->willReturn(false);

        $migrationHandlerMock = $this->getMockBuilder(MigrationStepEloquentHandler::class)->getMock();
        $seedHandlerMock = $this->getMockBuilder(MigrationStepEloquentHandler::class)->getMock();
        $hookHandlerMock = $this->getMockBuilder(MigrationWorkflowHookHandlerContract::class)->getMock();
        $migrationWorkflowHandler = new MigrationWorkflowHandler(
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
            $migrationWorkflowHandler
        );

        $tableHandlerMock->expects($this->exactly(1))->method('createMigrationWorkflowTableIfNotExists');
        $tableHandlerMock->expects($this->exactly(1))->method('createMigrationWorkflowDetailTableIfNotExists');
        $tableHandlerMock->expects($this->exactly(2))->method('isWorkflowPresentInDatabase');
        $tableHandlerMock->expects($this->exactly(2))->method('saveMigrationWorkflow');

        $migrationDeployHandler->deploy();
    }

    public function test_should_run_prod_migration_workflows()
    {
        $tableHandlerMock = $this->getMockBuilder(MigrationDeployTableHandlerContract::class)->getMock();
        $tableHandlerMock->method('isWorkflowPresentInDatabase')->willReturn(true);

        $migrationHandlerMock = $this->getMockBuilder(MigrationStepEloquentHandler::class)->getMock();
        $seedHandlerMock = $this->getMockBuilder(MigrationStepEloquentHandler::class)->getMock();
        $hookHandlerMock = $this->getMockBuilder(MigrationWorkflowHookHandlerContract::class)->getMock();
        $migrationWorkflowHandler = new MigrationWorkflowHandler(
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
            $migrationWorkflowHandler
        );

        $tableHandlerMock->expects($this->exactly(1))->method('createMigrationWorkflowTableIfNotExists');
        $tableHandlerMock->expects($this->exactly(1))->method('createMigrationWorkflowDetailTableIfNotExists');
        $tableHandlerMock->expects($this->exactly(1))->method('isWorkflowPresentInDatabase');
        $tableHandlerMock->expects($this->exactly(0))->method('saveMigrationWorkflow');

        $migrationDeployHandler->deploy();
    }

}