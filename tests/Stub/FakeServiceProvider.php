<?php 

namespace MrCoto\MigrationWorkflow\Test\Stub;

use Illuminate\Support\ServiceProvider;
use MrCoto\MigrationWorkflow\Test\Helper\FileHandler;

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
        // Copy migrations and seeders folders (this is a fix to window test, because publishes method doesn't
        // work on Windows, but works on Linux with no problem
        (new FileHandler)->copyFolder(__DIR__.'/migrations', database_path('migrations'));
        (new FileHandler)->copyFolder(__DIR__.'/seeders', database_path('seeders'));
    }

}