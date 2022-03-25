<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager\Exceptions\Constants;

use Fresns\CmdWordManager\Exceptions;

class ExceptionConstant
{
    const PLUGIN_CONFIG_ERROR = 21001;
    const PLUGIN_DOES_NOT_EXIST = 21002;
    const WORD_DOES_NOT_EXIST = 21003;
    const WORD_UNKNOWN_ERROR = 21004;
    const CMD_WORD_RESP_ERROR = 21005;
    const CMD_WORD_PARAM_ERROR = 21006;
    const CMD_WORD_REQUEST_ERROR = 21007;
    const CMD_WORD_RESULT_ERROR = 21008;
    const CMD_WORD_DATA_ERROR = 21009;
    const CMD_WORD_RUN_ERROR = 21010;

    const ERROR_CODE_DESCRIPTION_MAP = [
        ExceptionConstant::PLUGIN_CONFIG_ERROR      => 'Unconfigured plugin',
        ExceptionConstant::PLUGIN_DOES_NOT_EXIST    => 'Plugin does not exist',
        ExceptionConstant::WORD_DOES_NOT_EXIST      => 'Command word does not exist',
        ExceptionConstant::WORD_UNKNOWN_ERROR       => 'Command word unknown error',
        ExceptionConstant::CMD_WORD_RESP_ERROR      => 'Command word not responding',
        ExceptionConstant::CMD_WORD_PARAM_ERROR     => 'Command word request parameter error',
        ExceptionConstant::CMD_WORD_REQUEST_ERROR   => 'Command word execution request error',
        ExceptionConstant::CMD_WORD_RESULT_ERROR    => 'Command word response result is incorrect',
        ExceptionConstant::CMD_WORD_DATA_ERROR      => 'Data anomalies, queries not available or data duplication',
        ExceptionConstant::CMD_WORD_RUN_ERROR       => 'Execution anomalies, missing files or logging errors',
    ];

    const ERROR_CODE_CLASS_MAP = [
        ExceptionConstant::PLUGIN_CONFIG_ERROR => Exceptions\NoPluginsConfiguredException::class,
        ExceptionConstant::PLUGIN_DOES_NOT_EXIST => Exceptions\UnikeyNotfoundException::class,
        ExceptionConstant::WORD_DOES_NOT_EXIST => Exceptions\CmdWordNotfoundException::class,
        ExceptionConstant::WORD_UNKNOWN_ERROR => Exceptions\CmdWordUnknownErrorException::class,
        ExceptionConstant::CMD_WORD_RESP_ERROR => Exceptions\CmdWordNoResponseException::class,
        ExceptionConstant::CMD_WORD_PARAM_ERROR => Exceptions\CmdWordRequestParameterErrorException::class,
        ExceptionConstant::CMD_WORD_REQUEST_ERROR => Exceptions\CmdWordNonExecutableException::class,
        ExceptionConstant::CMD_WORD_RESULT_ERROR => Exceptions\CmdWordResponseDataException::class,
        ExceptionConstant::CMD_WORD_DATA_ERROR => Exceptions\CmdWordDataException::class,
        ExceptionConstant::CMD_WORD_RUN_ERROR => Exceptions\CmdWordExecutionException::class,
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
