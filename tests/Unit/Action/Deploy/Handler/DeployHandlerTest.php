<?php 

namespace MrCoto\MigrationWorkflow\Test\Unit\Action\Deploy\Handler;

use MrCoto\MigrationWorkflow\Action\Deploy\Contract\DeployRepositoryContract;
use MrCoto\MigrationWorkflow\Action\Deploy\Handler\DeployHandler;
use MrCoto\MigrationWorkflow\Action\Deploy\ValueObject\DeployData;
use MrCoto\MigrationWorkflow\Action\Migrate\Contract\MigrateHookContract;
use MrCoto\MigrationWorkflow\Action\Migrate\Contract\MigrateStepContract;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\MigrateHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeployHandlerTest extends TestCase
{

    public function test_should_deploy_dev_migration_workflows()
    {
        /** @var DeployRepositoryContract&MockObject */
        $tableHandlerMock = $this->getMockBuilder(DeployRepositoryContract::class)->getMock();
        $tableHandlerMock->method('exists')->will($this->onConsecutiveCalls(
            'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data1\CreateDummyWorkflow_dev_2019_10_21_101600',
            'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data2\SeedDummyWorkflow_dev_2019_10_21_101700'
        ))->willReturn(false);

        $migrateHandler = $this->get_migrate_handler();
            
        $migrationDeployHandler = new DeployHandler(
            $this->get_deploy_data(['dev']),
            $tableHandlerMock,
            $migrateHandler
        );

        $tableHandlerMock->expects($this->exactly(1))->method('createTableIfNotExists');
        $tableHandlerMock->expects($this->exactly(1))->method('createDetailTableIfNotExists');
        $tableHandlerMock->expects($this->exactly(2))->method('exists');
        $tableHandlerMock->expects($this->exactly(2))->method('save');

        $migrationDeployHandler->deploy();
    }

    public function test_should_deploy_prod_migration_workflows()
    {
        /** @var DeployRepositoryContract&MockObject */
        $tableHandlerMock = $this->getMockBuilder(DeployRepositoryContract::class)->getMock();
        $tableHandlerMock->method('exists')->willReturn(true);

        $migrateHandler = $this->get_migrate_handler();
            
        $migrationDeployHandler = new DeployHandler(
            $this->get_deploy_data(['prod']),
            $tableHandlerMock,
            $migrateHandler
        );

        $tableHandlerMock->expects($this->exactly(1))->method('createTableIfNotExists');
        $tableHandlerMock->expects($this->exactly(1))->method('createDetailTableIfNotExists');
        $tableHandlerMock->expects($this->exactly(1))->method('exists');
        $tableHandlerMock->expects($this->exactly(0))->method('save');

        $migrationDeployHandler->deploy();
    }

    public function test_should_deploy_dev_and_prod_migration_workflows()
    {
        /** @var DeployRepositoryContract&MockObject */
        $tableHandlerMock = $this->getMockBuilder(DeployRepositoryContract::class)->getMock();
        $tableHandlerMock->method('exists')->willReturn(false);

        $migrateHandler = $this->get_migrate_handler();
            
        $migrationDeployHandler = new DeployHandler(
            $this->get_deploy_data(['dev', 'prod']),
            $tableHandlerMock,
            $migrateHandler
        );

        $tableHandlerMock->expects($this->exactly(1))->method('createTableIfNotExists');
        $tableHandlerMock->expects($this->exactly(1))->method('createDetailTableIfNotExists');
        $tableHandlerMock->expects($this->exactly(3))->method('exists');
        $tableHandlerMock->expects($this->exactly(3))->method('save');

        $migrationDeployHandler->deploy();
    }

    private function get_deploy_data(array $versions) : DeployData
    {
        return new DeployData(
            'table_name',
            'detail_table_name',
            [
                'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data2',
                'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data1',
            ],
            $versions
        );
    }

    private function get_migrate_handler() : MigrateHandler
    {
        /** @var MigrateStepContract&MockObject */
        $migrateStepHandler = $this->getMockBuilder(MigrateStepContract::class)->getMock();
        /** @var MigrateHookContract&MockObject */
        $hookHandlerMock = $this->getMockBuilder(MigrateHookContract::class)->getMock();
        return new MigrateHandler(
            $migrateStepHandler,
            $hookHandlerMock
        );
    }

}