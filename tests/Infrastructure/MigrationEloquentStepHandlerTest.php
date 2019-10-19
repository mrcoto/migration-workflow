<?php 

namespace MrCoto\MigrationWorkflow\Test\Infrastructure;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationWorkflowStep;
use MrCoto\MigrationWorkflow\Infrastructure\Exceptions\ClassFileIsNotMigrationException;
use MrCoto\MigrationWorkflow\Infrastructure\Exceptions\MigrationFileNotFoundException;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\MigrationEloquentStepHandler;
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

    public function test_should_if_class_file_is_not_a_migration()
    {
        $this->expectException(ClassFileIsNotMigrationException::class);
        Artisan::call('vendor:publish', ['--provider' => 'MrCoto\MigrationWorkflow\Test\Stub\FakeServiceProvider']);
        $migrationStepHandler = new MigrationEloquentStepHandler;
        $migrationStepHandler->handle(1, new MigrationWorkflowStep('migration', [
            'database/migrations/2019_10_19_104700_create_not_migration_class_table'
        ]));
    }

    public function test_should_create_and_drop_migration_table()
    {
        Artisan::call('vendor:publish', ['--provider' => 'MrCoto\MigrationWorkflow\Test\Stub\FakeServiceProvider']);
        Schema::dropIfExists('dummy');
        $migrationStepHandler = new MigrationEloquentStepHandler;
        $migrationStepHandler->handle(1, new MigrationWorkflowStep('migration', [
            'database/migrations/2019_10_19_120000_create_dummy_table'
        ]));
        $this->assertTrue(Schema::hasTable('dummy'));
        $migrationStepHandler->handle(1, new MigrationWorkflowStep('migration', [
            'database/migrations/2019_10_19_120000_drop_dummy_table'
        ]));
        $this->assertFalse(Schema::hasTable('dummy'));
    }

}