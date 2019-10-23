<?php 

namespace MrCoto\MigrationWorkflow\Test\Infrastructure;

use Illuminate\Support\Facades\Artisan;
use MrCoto\MigrationWorkflow\Domain\ValueObject\Stub;
use MrCoto\MigrationWorkflow\Test\LaravelTest;

class DeleteMigrationWorkflowCommandTest extends LaravelTest
{
    
    public function test_should_remove_workflow_from_database()
    {
        $this->clean_app_folder();

        Artisan::call('vendor:publish', ['--provider' => 'MrCoto\MigrationWorkflow\Application\LaravelMigrationWorkflowServiceProvider']);
        $this->set_workflow_paths();

        $date = date('Y_m_d_His');
        $this->generate_stub($date);
        
        Artisan::call('migrate:deploy');

        Artisan::call('delete:workflow', ['className' => 'MyMigrationClass', 'version' => 'dev']);

        $this->assertDatabaseMissing('migration_workflow', [
            'workflow_class' => "App\MigrationWorkflows\MyMigrationClass_dev_$date"
        ]);

        $this->remove_stub($date);
        $this->remove_folders();
    }

    public function test_should_remove_workflow_from_database_and_file()
    {
        Artisan::call('vendor:publish', ['--provider' => 'MrCoto\MigrationWorkflow\Application\LaravelMigrationWorkflowServiceProvider']);
        $this->set_workflow_paths();

        $date = date('Y_m_d_His');
        $this->generate_stub($date);
        
        Artisan::call('delete:workflow', ['className' => 'MyMigrationClass', 'version' => 'dev', '--file' => true]);

        $this->assertDatabaseMissing('migration_workflow', [
            'workflow_class' => "App\MigrationWorkflows\MyMigrationClass_dev_$date"
        ]);

        $this->assertFalse(file_exists("app/MigrationWorkflows/MyMigrationClass_dev_$date.php"));

        $this->remove_folders();
    }

    public function set_workflow_paths()
    {
        $this->app['config']->set('migration_workflow.workflows', [
            'App\MigrationWorkflows',
        ]);
    }

    public function generate_stub(string $date)
    {
        $stub = new Stub('App\MigrationWorkflows', "MyMigrationClass_dev_$date", 'migration_workflow');
        $stub->setStubPath(__DIR__.'/../Stub/.stub/');
        $stub->generate();
    }

    public function remove_stub(string $date)
    {
        unlink("app/MigrationWorkflows/MyMigrationClass_dev_$date.php");
    }

    public function remove_folders()
    {
        if (is_dir('app/MigrationWorkflows'))   rmdir('app/MigrationWorkflows');
        if (is_dir('app'))   rmdir('app');
    }

    public function clean_app_folder()
    {
        $files = glob('app/MigrationWorkflows/*');
        foreach($files as $file){
            if(is_file($file)) {
                unlink($file);
            }
        }
        $this->remove_folders();
    }

}