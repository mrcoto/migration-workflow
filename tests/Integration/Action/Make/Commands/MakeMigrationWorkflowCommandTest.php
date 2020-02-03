<?php

namespace MrCoto\MigrationWorkflow\Test\Integration\Make\Commands;

use Illuminate\Support\Facades\Artisan;
use MrCoto\MigrationWorkflow\Test\Helper\FileHandler;
use MrCoto\MigrationWorkflow\Test\LaravelTest;

class MakeMigrationWorkflowCommandTest extends LaravelTest
{
    
    public function test_should_make_workflow()
    {
        Artisan::call('make:workflow', ['className' => 'MyMigrationClass', 'versions' => 'dev']);

        $date = date('Y_m_d_His');
        $this->assertTrue(file_exists("app/MigrationWorkflows/MyMigrationClass_dev_$date.php"));
        (new FileHandler)->delete('app');
    }

    public function test_should_make_multiple_versions_workflow()
    {
        Artisan::call('make:workflow', ['className' => 'MyMigrationClass', 'versions' => 'dev,prod']);
        
        $date = date('Y_m_d_His');
        $this->assertTrue(file_exists("app/MigrationWorkflows/MyMigrationClass_dev_$date.php"));
        $this->assertTrue(file_exists("app/MigrationWorkflows/MyMigrationClass_prod_$date.php"));
        (new FileHandler)->delete('app');
    }

    public function test_should_make_multiple_versions_workflow_with_options_owndir()
    {
        Artisan::call('make:workflow', ['className' => 'MyMigrationClass', 'versions' => 'dev,prod', '--owndir' => true]);
        
        $date = date('Y_m_d_His');
        $this->assertTrue(file_exists("app/MigrationWorkflows/Dev/MyMigrationClass_dev_$date.php"));
        $this->assertTrue(file_exists("app/MigrationWorkflows/Prod/MyMigrationClass_prod_$date.php"));
        (new FileHandler)->delete('app');
    }

    public function test_should_make_multiple_versions_workflow_with_options_date()
    {
        Artisan::call('make:workflow', ['className' => 'MyMigrationClass', 'versions' => 'dev,prod', '--date' => true]);
        
        $date = date('Y_m_d_His');
        $today = $this->get_today_date_folder();
        $this->assertTrue(file_exists("app/MigrationWorkflows/$today/MyMigrationClass_dev_$date.php"));
        $this->assertTrue(file_exists("app/MigrationWorkflows/$today/MyMigrationClass_prod_$date.php"));
        (new FileHandler)->delete('app');
    }

    public function test_should_make_multiple_versions_workflow_with_options_owndir_and_date()
    {
        Artisan::call('make:workflow', ['className' => 'MyMigrationClass', 'versions' => 'dev,prod', '--owndir' => true, '--date' => true]);
        
        $date = date('Y_m_d_His');
        $today = $this->get_today_date_folder();
        $this->assertTrue(file_exists("app/MigrationWorkflows/Dev/$today/MyMigrationClass_dev_$date.php"));
        $this->assertTrue(file_exists("app/MigrationWorkflows/Prod/$today/MyMigrationClass_prod_$date.php"));
        (new FileHandler)->delete('app');
    }

    private function get_today_date_folder() : string
    {
        return 'Y'.date('Y').'/M'.date('m').'/D'.date('d');
    }

}