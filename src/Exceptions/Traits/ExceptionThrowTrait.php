<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager\Exceptions\Traits;

trait ExceptionThrowTrait
{
    public static function throw(string $message = '', ?string $unikey = null)
    {
        $instance = new static($message);

        $instance->setUnikey($unikey);

        throw $instance;
    }

    public function setUnikey(?string $unikey = null)
    {
        $this->unikey = $unikey;
    }

    public function getUnikey()
    {
        return $this->unikey;
    }
}
