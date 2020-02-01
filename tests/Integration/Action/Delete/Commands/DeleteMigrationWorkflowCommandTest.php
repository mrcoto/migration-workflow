<?php 

namespace MrCoto\MigrationWorkflow\Test\Integration\Delete\Commands;

use Illuminate\Support\Facades\Artisan;
use MrCoto\MigrationWorkflow\Action\Make\Handler\Stub;
use MrCoto\MigrationWorkflow\Test\Helper\FileHandler;
use MrCoto\MigrationWorkflow\Test\LaravelTest;

class DeleteMigrationWorkflowCommandTest extends LaravelTest
{
    
    public function test_should_remove_workflow_from_database()
    {
        $this->set_workflow_paths();

        $date = date('Y_m_d_His');
        $this->generate_stub($date);
        
        Artisan::call('migrate:deploy');

        Artisan::call('delete:workflow', ['className' => 'MyMigrationClass', 'version' => 'dev']);

        $this->assertDatabaseMissing('migration_workflow', [
            'workflow_class' => "App\MigrationWorkflows\MyMigrationClass_dev_$date"
        ]);

        (new FileHandler)->delete('app');
    }

    public function test_should_remove_workflow_from_database_and_file()
    {
        $this->set_workflow_paths();

        $date = date('Y_m_d_His');
        $this->generate_stub($date);
        
        Artisan::call('delete:workflow', ['className' => 'MyMigrationClass', 'version' => 'dev', '--file' => true]);

        $this->assertDatabaseMissing('migration_workflow', [
            'workflow_class' => "App\MigrationWorkflows\MyMigrationClass_dev_$date"
        ]);

        $this->assertFalse(file_exists("app/MigrationWorkflows/MyMigrationClass_dev_$date.php"));

        (new FileHandler)->delete('app');
    }

    public function set_workflow_paths()
    {
        $this->app['config']->set('migration_workflow.workflows', [
            'app',
        ]);
    }

    public function generate_stub(string $date)
    {
        $stub = new Stub('App\MigrationWorkflows', "MyMigrationClass_dev_$date", 'migration_workflow');
        $stub->setStubPath(__DIR__.'/../../../../Stub/.stub/');
        $stub->generate();
    }

}