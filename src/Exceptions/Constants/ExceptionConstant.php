<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the GPL-3.0 License.
 */

namespace Fresns\CmdWordManager\Exceptions\Constants;

use Fresns\CmdWordManager\Exceptions;

class ExceptionConstant
{
    const ERROR_CODE_20001 = 20001;
    const ERROR_CODE_20002 = 20002;
    const ERROR_CODE_20003 = 20003;
    const ERROR_CODE_20004 = 20004;
    const ERROR_CODE_20005 = 20005;
    const ERROR_CODE_20006 = 20006;
    const ERROR_CODE_20007 = 20007;
    const ERROR_CODE_20008 = 20008;
    const ERROR_CODE_20009 = 20009;
    const ERROR_CODE_20010 = 20010;

    const ERROR_CODE_DESCRIPTION_MAP = [
        ExceptionConstant::ERROR_CODE_20001 => 'Plugin does not exist',
        ExceptionConstant::ERROR_CODE_20002 => 'Command word does not exist',
        ExceptionConstant::ERROR_CODE_20003 => 'Command word not responding',
        ExceptionConstant::ERROR_CODE_20004 => 'Unconfigured plugin',
        ExceptionConstant::ERROR_CODE_20005 => 'Command word execution error',
        ExceptionConstant::ERROR_CODE_20006 => 'Command word unknown error',
        ExceptionConstant::ERROR_CODE_20007 => 'Command word request parameter error',
        ExceptionConstant::ERROR_CODE_20008 => 'Command word response result is incorrect',
        ExceptionConstant::ERROR_CODE_20009 => 'Data anomalies, queries not available or data duplication',
        ExceptionConstant::ERROR_CODE_20010 => 'Execution anomalies, missing files or logging errors',
    ];

    const ERROR_CODE_CLASS_MAP = [
        ExceptionConstant::ERROR_CODE_20001 => Exceptions\UnikeyNotfoundException::class,
        ExceptionConstant::ERROR_CODE_20002 => Exceptions\CmdWordNotfoundException::class,
        ExceptionConstant::ERROR_CODE_20003 => Exceptions\CmdWordNoResponseException::class,
        ExceptionConstant::ERROR_CODE_20004 => Exceptions\NoPluginsConfiguredException::class,
        ExceptionConstant::ERROR_CODE_20005 => Exceptions\CmdWordNonExecutableException::class,
        ExceptionConstant::ERROR_CODE_20006 => Exceptions\CmdWordUnknownErrorException::class,
        ExceptionConstant::ERROR_CODE_20007 => Exceptions\CmdWordRequestParameterErrorException::class,
        ExceptionConstant::ERROR_CODE_20008 => Exceptions\CmdWordResponseDataException::class,
        ExceptionConstant::ERROR_CODE_20009 => Exceptions\CmdWordDataException::class,
        ExceptionConstant::ERROR_CODE_20010 => Exceptions\CmdWordExecutionException::class,
    ];

    public static function ensureErrcodeExists(int $code): bool
    {
        if (! array_key_exists($code, static::ERROR_CODE_DESCRIPTION_MAP)) {
            $link = 'https://github.com/fresns/cmd-word-manager#result-output';

            FresnsCmdWordException::throw("unknown code $code, please see $link");
        }

        return true;
    }

    public static function getErrorCodeByClass(string $class): int
    {
        if (! in_array($class, static::ERROR_CODE_CLASS_MAP)) {
            $link = 'https://github.com/fresns/cmd-word-manager#result-output';

            FresnsCmdWordException::throw("unknown code class, please see $link");
        }

        return array_flip(static::ERROR_CODE_CLASS_MAP)[$class];
    }

    public static function getErrorDescriptionByCode(int $code)
    {
        static::ensureErrcodeExists($code);

        return static::ERROR_CODE_DESCRIPTION_MAP[$code];
    }

    public static function getHandleClassByCode(int $code)
    {
        static::ensureErrcodeExists($code);

        return static::ERROR_CODE_CLASS_MAP[$code];
    }
}
