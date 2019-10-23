<?php 

namespace MrCoto\MigrationWorkflow\Test\Infrastructure;

use CreateDummyTable;
use DropDummyTable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep;
use MrCoto\MigrationWorkflow\Infrastructure\Exceptions\ClassFileIsNotSeederException;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\SeedStepEloquentHandler;
use MrCoto\MigrationWorkflow\Test\LaravelTest;

class SeedStepEloquentHandlerTest extends LaravelTest
{

    public function test_should_throw_exception_if_class_file_is_not_a_seeder()
    {
        $this->expectException(ClassFileIsNotSeederException::class);
        Artisan::call('vendor:publish', ['--provider' => 'MrCoto\MigrationWorkflow\Test\Stub\FakeServiceProvider']);
        $migrationStepHandler = new SeedStepEloquentHandler();
        $migrationStepHandler->handle(1, new MigrationWorkflowStep('seed', [
            'MrCoto\MigrationWorkflow\Test\Stub\Seeders\NotSeedClassTableSeeder'
        ]));
    }

    public function test_should_create_seed_and_drop_dummy_table()
    {
        Artisan::call('vendor:publish', ['--provider' => 'MrCoto\MigrationWorkflow\Test\Stub\FakeServiceProvider']);
        Schema::dropIfExists('dummy');
        (new CreateDummyTable)->up();
        $seedStepHandler = new SeedStepEloquentHandler;
        $seedStepHandler->handle(1, new MigrationWorkflowStep('seed', [
            'MrCoto\MigrationWorkflow\Test\Stub\Seeders\DummyTableSeeder'
        ]));
        $this->assertDatabaseHas('dummy', ['dummy_column' => 'dummy_value']);
        (new DropDummyTable)->up();
    }

}