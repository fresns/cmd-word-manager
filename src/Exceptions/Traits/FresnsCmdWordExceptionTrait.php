<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager\Exceptions\Traits;

use Fresns\CmdWordManager\Exceptions\Constants\ExceptionConstant;

trait FresnsCmdWordExceptionTrait
{
    public function getErrorCode()
    {
        $code = $this->getCode();
        if (! array_key_exists($code, ExceptionConstant::ERROR_CODE_DESCRIPTION_MAP)) {
            return $code;
        }

        return ExceptionConstant::getErrorCodeByClass(static::class);
    }

    public function getErrorDescription()
    {
        $code = $this->getCode();
        if (! array_key_exists($code, ExceptionConstant::ERROR_CODE_DESCRIPTION_MAP)) {
            return $code;
        }

        return ExceptionConstant::getErrorDescriptionByCode(static::getErrorCode());
    }
}
