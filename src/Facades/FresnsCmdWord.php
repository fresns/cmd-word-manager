<?php

namespace Fresns\CmdWordManager\Facades;

use Illuminate\Support\Facades\Facade;

class FresnsCmdWord extends Facade
{
    public static function getFacadeAccessor()
    {
        return \Fresns\CmdWordManager\FresnsCmdWord::class;
    }
}