<?php namespace Waka\Wakajob\Classes;

use Illuminate\Support\ServiceProvider;
use Waka\Wakajob\Contracts\Clearer;

class LaravelQueueClearServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            Clearer::class,
            \Waka\Wakajob\Classes\Clearer::class
        );
        $this->commands('Waka\Wakajob\Console\QueueClearCommand');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
