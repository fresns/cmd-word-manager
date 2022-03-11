<?php

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