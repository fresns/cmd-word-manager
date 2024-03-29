<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager\Facades;

use Illuminate\Support\Facades\Facade;

class FresnsCmdWord extends Facade
{
    public static function getFacadeAccessor()
    {
        return \Fresns\CmdWordManager\FresnsCmdWord::class;
    }
}
