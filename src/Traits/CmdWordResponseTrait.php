<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager\Traits;

trait CmdWordResponseTrait
{
    public function success($data = [], $message = 'success', $code = 0)
    {
        return [
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ];
    }

    public function failure($code, $message = 'failure', $data = [])
    {
        return $this->success($data, $message, $code);
    }
}
