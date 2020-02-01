<?php 

namespace MrCoto\MigrationWorkflow\Config;

use Illuminate\Support\ServiceProvider;
use MrCoto\MigrationWorkflow\Action\Delete\Commands\DeleteMigrationWorkflowCommand;
use MrCoto\MigrationWorkflow\Action\Deploy\Commands\DeployMigrationWorkflowCommand;
use MrCoto\MigrationWorkflow\Action\Make\Commands\MakeMigrationWorkflowCommand;
use MrCoto\MigrationWorkflow\Action\Make\Commands\ModuleMakeMigrationWorkflowCommand;
use MrCoto\MigrationWorkflow\Action\Migrate\Commands\MigrateMigrationWorkflowCommand;

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
            $this->registerCommands();
            $this->publishConfig();
        }
    }

    /**
     * Publish Laravel Config
     *
     * @return void
     */
    private function publishConfig()
    {
        $this->publishes([
            __DIR__.'/migration_workflow.php' => config_path('migration_workflow.php'),
        ], 'migration-workflow-config');
    }

    /**
     * Register Laravel Commands
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->commands([
            MigrateMigrationWorkflowCommand::class,
            DeployMigrationWorkflowCommand::class,
            MakeMigrationWorkflowCommand::class,
            ModuleMakeMigrationWorkflowCommand::class,
            DeleteMigrationWorkflowCommand::class,
        ]);
    }

    /**
     * Make config publishment optional by merge the config from the package
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/migration_workflow.php',
            'migration_workflow'
        );
    }

}