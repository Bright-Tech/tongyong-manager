<?php
/**
 * Created by PhpStorm.
 * User: JX
 * Date: 2017/12/29
 * Time: 12:52
 */

namespace bright\support;

use Illuminate\Support\ServiceProvider;

class SupportProvider extends ServiceProvider
{
    public function boot()
    {
        //
        $this->publishes([
            __DIR__ . '/config/bright-support.php' => config_path('bright-support.php'),
        ]);
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Support::class, function ($app) {
            $config = config('bright-support');
            return new Support($config['client_access_id'], $config['client_secret'], $config['domain']);
        });
        $this->app->alias(Support::class, 'bright-support');
    }
}