<?php

namespace Fresns\CmdWordManager\Exceptions;

use Fresns\CmdWordManager\CmdWordResponse;

class FresnsCmdWordException extends \RuntimeException
{
    use Traits\ExceptionThrowTrait;
    use Traits\FresnsCmdWordExceptionTrait;

    public function getData()
    {
        return [
            'code' => $this->getErrorCode(),
            'message' => $this->getErrorDescription(),
            'data' => [],
        ];
    }

    public function createCmdWordResponse()
    {
        return CmdWordResponse::create($this->getData());
    }
}