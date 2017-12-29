<?php
/**
 * Created by PhpStorm.
 * User: JX
 * Date: 2017/12/29
 * Time: 12:52
 */

namespace bright_tech\laravel\tongyong_manager;

use Illuminate\Support\ServiceProvider;

class TongYongServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
        $this->publishes([
            __DIR__ . '/config/tongyong.php' => config_path('tongyong.php'),
        ]);
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TongYongService::class, function ($app) {
            $config = config('tongyong');
            return new TongYongService($config['access_id'], $config['secret'], $config['home']);
        });
        $this->app->alias(TongYongService::class, 'tongyong');
    }
}