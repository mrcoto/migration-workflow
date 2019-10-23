<?php 

namespace MrCoto\MigrationWorkflow\Test\Domain;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationDeployHandler;
use MrCoto\MigrationWorkflow\Domain\Handlers\MigrationWorkflowHandler;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationDeployData;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\HookEloquentHandler;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\MigrationDeployTableEloquentHandler;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\MigrationStepEloquentHandler;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\SeedStepEloquentHandler;
use MrCoto\MigrationWorkflow\Test\LaravelTest;

class MigrationDeployHandlerTest extends LaravelTest
{

    public function test_should_run_dev_migration_workflows()
    {
        Schema::dropIfExists('migration_workflow_detail');
        Schema::dropIfExists('migration_workflow');
        Schema::dropIfExists('dummy');
        
        $workflowHandler = new MigrationWorkflowHandler(
            new MigrationStepEloquentHandler,
            new SeedStepEloquentHandler,
            new HookEloquentHandler
        );
            
        $migrationDeployHandler = new MigrationDeployHandler(
            new MigrationDeployData(
                'migration_workflow',
                'migration_workflow_detail',
                [
                    'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data2',
                    'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data1',
                ],
                ['dev']
            ),
            new MigrationDeployTableEloquentHandler(),
            $workflowHandler
        );

        $migrationDeployHandler->deploy();

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

    public function test_should_skip_dev_migration_workflows()
    {
        $workflowHandler = new MigrationWorkflowHandler(
            new MigrationStepEloquentHandler,
            new SeedStepEloquentHandler,
            new HookEloquentHandler
        );
            
        $migrationDeployHandler = new MigrationDeployHandler(
            new MigrationDeployData(
                'migration_workflow',
                'migration_workflow_detail',
                [
                    'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data2',
                    'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data1',
                ],
                ['dev']
            ),
            new MigrationDeployTableEloquentHandler(),
            $workflowHandler
        );

        $migrationDeployHandler->deploy();

        $this->assertTrue(Schema::hasTable('migration_workflow'));
        $this->assertTrue(Schema::hasTable('migration_workflow_detail'));
        $this->assertTrue(Schema::hasTable('dummy'));

        $this->assertEquals(2, DB::table('migration_workflow')->count());
        $this->assertEquals(2, DB::table('migration_workflow_detail')->count());
    }

}