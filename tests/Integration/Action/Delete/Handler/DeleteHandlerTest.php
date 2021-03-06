<?php 

namespace MrCoto\MigrationWorkflow\Test\Integration\Delete\Handler;

use Illuminate\Support\Facades\DB;
use MrCoto\MigrationWorkflow\Action\Delete\Handler\DeleteHandler;
use MrCoto\MigrationWorkflow\Action\Delete\Handler\DeleteRepository;
use MrCoto\MigrationWorkflow\Action\Delete\ValueObject\DeleteData;
use MrCoto\MigrationWorkflow\Action\Make\Handler\Stub;
use MrCoto\MigrationWorkflow\Test\Helper\FileHandler;
use MrCoto\MigrationWorkflow\Test\LaravelTest;

class DeleteHandlerTest extends LaravelTest
{

    public function test_should_remove_database_records()
    {
        $this->add_dummy_data();
        $this->generate_stub();

        $deleteMigrationWorkflowHandler = $this->get_new_delete_migration_workflow_handler();
        
        $deleteMigrationWorkflowHandler->deleteFromDatabase();

        $this->assertDatabaseMissing('migration_workflow', [
            'workflow_class' => 'App\MigrationWorkflows\SomeWorkflow_dev_2019_10_23_143400',
        ]);

        (new FileHandler)->delete('app');
    }

    public function test_should_remove_file()
    {
        $this->generate_stub();

        $deleteMigrationWorkflowHandler = $this->get_new_delete_migration_workflow_handler();
        
        $deleteMigrationWorkflowHandler->removeFile();

        $this->assertFalse(file_exists('app/MigrationWorkflows/SomeWorkflow_dev_2019_10_23_143400.php'));

        (new FileHandler)->delete('app');
    }

    public function get_new_delete_migration_workflow_handler()  : DeleteHandler
    {
        return new DeleteHandler(
            new DeleteData(
                'migration_workflow',
                'migration_workflow_detail',
                [
                    'app'
                ],
                'SomeWorkflow',
                'dev'
            ),
            new DeleteRepository
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

}