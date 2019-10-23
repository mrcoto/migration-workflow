<?php 

namespace MrCoto\MigrationWorkflow\Test\Infrastructure;

use Illuminate\Support\Facades\DB;
use MrCoto\MigrationWorkflow\Domain\Handlers\DeleteMigrationWorkflowHandler;
use MrCoto\MigrationWorkflow\Domain\ValueObject\MigrationDeleteData;
use MrCoto\MigrationWorkflow\Domain\ValueObject\Stub;
use MrCoto\MigrationWorkflow\Infrastructure\Handlers\Eloquent\DeleteMigrationWorkflowTableEloquentHandler;
use MrCoto\MigrationWorkflow\Test\LaravelTest;

class DeleteMigrationWorkflowHandlerTest extends LaravelTest
{

    public function test_should_remove_database_records()
    {
        $this->add_dummy_data();
        $this->generate_stub();

        $deleteMigrationWorkflowHandler = $this->get_new_delete_migration_workflow_handler();
        
        $deleteMigrationWorkflowHandler->deleteMigrationWorkflowFromDatabase();

        $this->assertDatabaseMissing('migration_workflow', [
            'workflow_class' => 'App\MigrationWorkflows\SomeWorkflow_dev_2019_10_23_143400',
        ]);

        $this->remove_stub();
    }

    public function test_should_remove_file()
    {
        $this->generate_stub();

        $deleteMigrationWorkflowHandler = $this->get_new_delete_migration_workflow_handler();
        
        $deleteMigrationWorkflowHandler->removeFile();

        $this->assertFalse(file_exists('app/MigrationWorkflows/SomeWorkflow_dev_2019_10_23_143400.php'));

        $this->remove_folders();
    }

    public function get_new_delete_migration_workflow_handler() 
    {
        return new DeleteMigrationWorkflowHandler(
            new MigrationDeleteData(
                'migration_workflow',
                'migration_workflow_detail',
                [
                    'App\MigrationWorkflows'
                ],
                'SomeWorkflow',
                'dev'
            ),
            new DeleteMigrationWorkflowTableEloquentHandler
        );
    }

    public function add_dummy_data()
    {
        DB::table('migration_workflow')->insert([
            'workflow_class' => 'App\MigrationWorkflows\SomeWorkflow_dev_2019_10_23_143400',
            'version' => 'dev',
            'date' => '2019-10-23 14:34:00',
            'timestamp' => 1571652960
        ]);
        $lastId = DB::table('migration_workflow')->max('id') ?? 1;
        DB::table('migration_workflow_detail')->insert([
            'step_number' => 1,
            'type' => 'migraton',
            'file' => 'database/migrations/2019_10_19_120000_create_dummy_table',
            'migration_workflow_id' => $lastId
        ]);
    }

    public function generate_stub()
    {
        $stub = new Stub('App\MigrationWorkflows', 'SomeWorkflow_dev_2019_10_23_143400', 'migration_workflow');
        $stub->generate();
    }

    public function remove_stub()
    {
        unlink('app/MigrationWorkflows/SomeWorkflow_dev_2019_10_23_143400.php');
    }

    public function remove_folders()
    {
        rmdir('app/MigrationWorkflows');
        rmdir('app');
    }

}