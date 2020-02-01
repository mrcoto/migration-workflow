<?php 

namespace MrCoto\MigrationWorkflow\Test\Integration\Action\Migrate\Handler;

use CreateDummyTable;
use DropDummyTable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use MrCoto\MigrationWorkflow\Action\Migrate\Exceptions\ClassFileIsNotMigrationException;
use MrCoto\MigrationWorkflow\Action\Migrate\Exceptions\ClassFileIsNotSeederException;
use MrCoto\MigrationWorkflow\Action\Migrate\Exceptions\MigrationFileNotFoundException;
use MrCoto\MigrationWorkflow\Action\Migrate\Handler\MigrateStepHandler;
use MrCoto\MigrationWorkflow\Core\ValueObject\MigrationWorkflowStep;
use MrCoto\MigrationWorkflow\Test\LaravelTest;

class MigrateStepHandlerTest extends LaravelTest
{

    public function test_should_throw_exception_if_migration_file_doesnt_exists()
    {
        $this->expectException(MigrationFileNotFoundException::class);
        $migrationStepHandler = new MigrateStepHandler;
        $migrationStepHandler->handleMigration(1, new MigrationWorkflowStep('migration', [
            'RandomFile'
        ]));
    }

    public function test_should_throw_exception_if_class_file_is_not_a_migration()
    {
        $this->expectException(ClassFileIsNotMigrationException::class);
        $migrationStepHandler = new MigrateStepHandler;
        $migrationStepHandler->handleMigration(1, new MigrationWorkflowStep('migration', [
            'database/migrations/2019_10_19_104700_create_not_migration_class_table'
        ]));
    }

    public function test_should_create_and_drop_migration_table()
    {
        Schema::dropIfExists('dummy');
        $migrationStepHandler = new MigrateStepHandler;
        $migrationStepHandler->handleMigration(1, new MigrationWorkflowStep('migration', [
            'database/migrations/2019_10_19_120000_create_dummy_table'
        ]));
        $this->assertTrue(Schema::hasTable('dummy'));
        $migrationStepHandler->handleMigration(1, new MigrationWorkflowStep('migration', [
            'database/migrations/2019_10_19_120000_drop_dummy_table'
        ]));
        $this->assertFalse(Schema::hasTable('dummy'));
    }

    public function test_should_throw_exception_if_class_file_is_not_a_seeder()
    {
        $this->expectException(ClassFileIsNotSeederException::class);
        $migrationStepHandler = new MigrateStepHandler();
        $migrationStepHandler->handleSeed(1, new MigrationWorkflowStep('seed', [
            'MrCoto\MigrationWorkflow\Test\Stub\Seeders\NotSeedClassTableSeeder'
        ]));
    }

    public function test_should_create_seed_and_drop_dummy_table()
    {
        Schema::dropIfExists('dummy');
        (new CreateDummyTable)->up();
        $seedStepHandler = new MigrateStepHandler;
        $seedStepHandler->handleSeed(1, new MigrationWorkflowStep('seed', [
            'MrCoto\MigrationWorkflow\Test\Stub\Seeders\DummyTableSeeder'
        ]));
        $this->assertDatabaseHas('dummy', ['dummy_column' => 'dummy_value']);
        (new DropDummyTable)->up();
    }

}