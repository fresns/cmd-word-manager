<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager\Exceptions;

use Fresns\CmdWordManager\CmdWordResponse;

class FresnsCmdWordException extends \RuntimeException
{
    use Traits\ExceptionThrowTrait;
    use Traits\FresnsCmdWordExceptionTrait;

    protected $unikey;

    protected $cmdWord;

    public function getData()
    {
        return [
            'code' => $this->getErrorCode(),
            'message' => sprintf('[%s][%s]: %s', $this->unikey, $this->cmdWord, $this->getErrorDescription()),
            'data' => [],
            'trace' => [
                'code' => $this->getCode(),
                'message' => $this->getMessage(),
            ],
        ];
    }

    public function createCmdWordResponse(?string $unikey = null, ?string $cmdWord = null)
    {
        $this->unikey = $unikey ?? 'unknown unikey';
        $this->cmdWord = $cmdWord ?? 'unknown cmdWord';

        return CmdWordResponse::create($this->getData());
    }
}
