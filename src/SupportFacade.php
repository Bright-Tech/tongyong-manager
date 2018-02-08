<?php
/**
 * Created by PhpStorm.
 * User: Daxu
 * Date: 2018/2/8
 * Time: 16:16
 */

namespace bright\support;

use Illuminate\Support\Facades\Facade;
class SupportFacade extends Facade
{
    protected static function getFacadeAccessor() {
        return Support::class;
    }

}
