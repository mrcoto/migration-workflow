<?php

namespace MrCoto\MigrationWorkflow\Test\Integration\Make\Commands;

use Illuminate\Support\Facades\Artisan;
use MrCoto\MigrationWorkflow\Test\LaravelTest;

class MakeMigrationWorkflowCommandTest extends LaravelTest
{
    
    public function test_should_make_workflow()
    {
        Artisan::call('make:workflow', ['className' => 'MyMigrationClass', 'version' => 'dev']);

        $date = date('Y_m_d_His');
        $this->assertTrue(file_exists("app/MigrationWorkflows/MyMigrationClass_dev_$date.php"));
        unlink("app/MigrationWorkflows/MyMigrationClass_dev_$date.php");
        rmdir('app/MigrationWorkflows');
        rmdir('app');

    }

}