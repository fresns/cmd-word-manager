<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the GPL-3.0 License.
 */

namespace Fresns\CmdWordManager\Exceptions\Traits;

trait ExceptionThrowTrait
{
    protected ?string $unikey;

    public static function throw(string $message, ?string $unikey = null)
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
