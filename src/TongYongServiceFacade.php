<?php
/**
 * Created by PhpStorm.
 * User: JX
 * Date: 2017/12/29
 * Time: 12:56
 */

namespace bright_tech\laravel\tongyong_manager;

use Illuminate\Support\Facades\Facade;
class TongYongServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tongyong';
    }
}