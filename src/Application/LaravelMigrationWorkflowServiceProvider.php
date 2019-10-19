<?php 

namespace MrCoto\MigrationWorkflow\Application;

use Illuminate\Support\ServiceProvider;
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
                MigrateWorkflowCommand::class
            ]);
        }
    }

    /**
     * Make config publishment optional by merge the config from the package
     *
     * @return void
     */
    public function register()
    {
        // TODO: Fill me with code if needed
    }

}