<?php 

namespace MrCoto\MigrationWorkflow\Test;

use MrCoto\MigrationWorkflow\Test\Helper\FileHandler;
use Orchestra\Testbench\TestCase;

abstract class LaravelTest extends TestCase
{

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        (new FileHandler)->delete('app');
        (new FileHandler)->delete('Modules');
    }

    protected function getPackageProviders($app)
    {
        return [
            'MrCoto\MigrationWorkflow\Config\LaravelMigrationWorkflowServiceProvider',
            'MrCoto\MigrationWorkflow\Test\Stub\FakeServiceProvider',
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('migration_workflow.workflows', ['tests']);
        $app['config']->set('migration_workflow.logger', 'MrCoto\MigrationWorkflow\Logger\Handler\SilentLogger');
    }

}