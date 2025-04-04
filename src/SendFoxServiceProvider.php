<?php
namespace Buchin\SendFoxIntegration;

use Illuminate\Support\ServiceProvider;

class SendFoxServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish the configuration file
        $this->publishes([
            __DIR__.'/../config/sendfox.php' => config_path('sendfox.php'),
        ], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge the configuration file
        $this->mergeConfigFrom(
            __DIR__.'/../config/sendfox.php', 'sendfox'
        );

        // Bind the service
        $this->app->singleton(SendFoxService::class, function ($app) {
            return new SendFoxService();
        });
    }
}