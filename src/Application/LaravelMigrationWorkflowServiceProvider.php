<?php 

namespace MrCoto\MigrationWorkflow\Application;

use Illuminate\Support\ServiceProvider;
use MrCoto\MigrationWorkflow\Application\Commands\MigrateDeployCommand;
use MrCoto\MigrationWorkflow\Application\Commands\MigrateWorkflowCommand;

class LaravelMigrationWorkflowServiceProvider extends ServiceProvider
{

    /**
     * Publish configuration file
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MigrateWorkflowCommand::class,
                MigrateDeployCommand::class
            ]);
            $this->publishes([
                __DIR__.'/Config/migration_workflow.php' => config_path('migration_workflow.php'),
            ], 'migration-workflow-config');
        }
    }

    /**
     * Make config publishment optional by merge the config from the package
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/migration_workflow.php',
            'migration_workflow'
        );
    }

}