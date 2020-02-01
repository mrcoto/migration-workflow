<?php 

namespace MrCoto\MigrationWorkflow\Test\Stub;

use Illuminate\Support\ServiceProvider;

/**
 * Provide fake migration and seeders files
 */
class FakeServiceProvider extends ServiceProvider
{

    /**
     * Publish configuration file
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadMigrationsFrom(__DIR__.'/seeders');
    }

    /**
     * Make config publishment optional by merge the config from the package
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([__DIR__.'/migrations' => database_path('migrations')], 'migrations');
        $this->publishes([__DIR__.'/seeders' => database_path('seeders')], 'seeders');
    }

}