<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager\Traits;

trait CmdWordResponseTrait
{
    public function success(mixed $data = null, ?string $message = 'success', ?int $code = 0)
    {
        return [
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ];
    }

    public function failure(int $code, ?string $message = 'failure', mixed $data = null)
    {
        return $this->success($data, $message, $code);
    }
}
