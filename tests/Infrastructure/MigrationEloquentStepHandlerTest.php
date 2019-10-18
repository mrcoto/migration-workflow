<?php 

namespace MrCoto\MigrationWorkflow\Test\Infrastructure;

use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\ValueObject\MigrationWorkflowStep;
use MrCoto\MigrationWorkflow\Infrastructure\Exceptions\MigrationFileNotFoundException;
use MrCoto\MigrationWorkflow\Infrastructure\MigrationWorkflow\Handlers\Eloquent\MigrationEloquentStepHandler;
use MrCoto\MigrationWorkflow\Test\LaravelTest;

class MigrationEloquentStepHandlerTest extends LaravelTest
{

    public function test_should_if_migration_file_doesnt_exists()
    {
        $this->expectException(MigrationFileNotFoundException::class);
        $migrationStepHandler = new MigrationEloquentStepHandler;
        $migrationStepHandler->handle(1, new MigrationWorkflowStep('migration', [
            'RandomFile'
        ]));
    }

}