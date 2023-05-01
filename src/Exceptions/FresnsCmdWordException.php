<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager\Exceptions;

use Fresns\CmdWordManager\CmdWordResponse;

class FresnsCmdWordException extends \RuntimeException
{
    use Traits\ExceptionThrowTrait;
    use Traits\FresnsCmdWordExceptionTrait;

    protected $fskey;

    protected $cmdWord;

    public function getData()
    {
        return [
            'code' => $this->getErrorCode(),
            'message' => sprintf('[%s][%s]: %s, reason: %s', $this->fskey, $this->cmdWord, $this->getErrorDescription(), $this->getMessage()),
            'data' => [],
            'trace' => [
                'code' => $this->getCode(),
                'message' => $this->getMessage(),
            ],
        ];
    }

    public function createCmdWordResponse(?string $fskey = null, ?string $cmdWord = null)
    {
        $this->fskey = $fskey ?? 'unknown fskey';
        $this->cmdWord = $cmdWord ?? 'unknown cmdWord';

        return CmdWordResponse::create($this->getData());
    }
}
