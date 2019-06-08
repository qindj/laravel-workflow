<?php

namespace LaravelWorkflow;

use Illuminate\Support\ServiceProvider;

class WorkflowServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services...
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/workflow.php' => config_path('workflow.php')
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom( __DIR__ . '/../config/workflow.php','workflow');

        $this->app->singleton( 'workflow', function ($app) {
            return new WorkflowRegistry($app['config']->get('workflow'));
        });
    }

    /**
    * Get the services provided by the provider.
    *
    * @return array
    */
    public function provides()
    {
        return ['workflow'];
    }
}
