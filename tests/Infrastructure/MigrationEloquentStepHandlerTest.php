<?php 

namespace MrCoto\MigrationWorkflow\Test\Infrastructure;

use MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\ValueObject\MigrationWorkflowStep;
use MrCoto\MigrationWorkflow\Infrastructure\MigrationWorkflow\Handlers\Eloquent\MigrationEloquentStepHandler;
use MrCoto\MigrationWorkflow\Test\LaravelTest;

class MigrationEloquentStepHandlerTest extends LaravelTest
{

    public function test_should_get_data()
    {
        $migrationStepHandler = new MigrationEloquentStepHandler;
        $migrationStepHandler->handle(1, new MigrationWorkflowStep('migration', [
            'MrCoto\MigrationWorkflow\Domain\MigrationWorkflow\ValueObject\MigrationWorkflowStep'
        ]));
        $this->assertEquals(0, 1);
    }

}