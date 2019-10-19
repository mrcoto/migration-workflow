<?php 

namespace MrCoto\MigrationWorkflow\Test\Stub;

use Illuminate\Support\ServiceProvider;

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
    }

    /**
     * Make config publishment optional by merge the config from the package
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([__DIR__.'/migrations' => database_path('migrations')], 'migrations');
    }

}