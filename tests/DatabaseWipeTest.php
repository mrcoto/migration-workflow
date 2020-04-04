<?php


namespace MrCoto\MigrationWorkflow\Test;


use Illuminate\Support\Facades\Artisan;
use MrCoto\MigrationWorkflow\Test\LaravelTest;

class DatabaseWipeTest extends LaravelTest
{
    public function test_should_wipe_database()
    {
        Artisan::call('db:wipe');
        $this->assertTrue(true);
    }
}