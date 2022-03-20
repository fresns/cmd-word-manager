<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager\Exceptions\Traits;

use Fresns\CmdWordManager\Exceptions\Constants\ExceptionConstant;

trait FresnsCmdWordExceptionTrait
{
    public function getErrorCode()
    {
        return ExceptionConstant::getErrorCodeByClass(static::class);
    }

    public function getErrorDescription()
    {
        return ExceptionConstant::getErrorDescriptionByCode(static::getErrorCode());
    }
}
