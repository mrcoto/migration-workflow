<?php

namespace MrCoto\MigrationWorkflow\Test\Integration\Make\Commands;

use Illuminate\Support\Facades\Artisan;
use MrCoto\MigrationWorkflow\Test\LaravelTest;

class ModuleMakeMigrationWorkflowCommandTest extends LaravelTest
{
    
    public function test_should_make_workflow()
    {
        Artisan::call('module:make-workflow', ['module' => 'General', 'className' => 'MyMigrationClass', 'version' => 'dev']);

        $date = date('Y_m_d_His');
        $this->assertTrue(file_exists("Modules/General/MigrationWorkflows/MyMigrationClass_dev_$date.php"));
        unlink("Modules/General/MigrationWorkflows/MyMigrationClass_dev_$date.php");
        rmdir('Modules/General/MigrationWorkflows');
        rmdir('Modules/General');
        rmdir('Modules');
    }

}