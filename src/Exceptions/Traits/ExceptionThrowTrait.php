<?php

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