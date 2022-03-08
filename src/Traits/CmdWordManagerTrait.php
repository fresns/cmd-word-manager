<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the GPL-3.0 License.
 */

namespace Fresns\CmdWordManager\Traits;

use Fresns\CmdWordManager\Contracts\CmdWordProviderContract;

trait CmdWordManagerTrait
{
    /** @var CmdWordProviderContract[] */
    protected array $plugins = [];

    protected static $instance = null;

    public static function make(): static
    {
        if (! static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public static function plugin($unikey = null): CmdWordProviderContract
    {
        $instance = static::make();

        return $instance->resolve($unikey);
    }

    public function resolve($unikey = null): CmdWordProviderContract
    {
        if (! array_key_exists($unikey, $this->plugins)) {
            throw new \RuntimeException("CmdWord of plugin $unikey notfound.");
        }

        return $this->plugins[$unikey];
    }

    public function addCmdWordProvider(CmdWordProviderContract $cmdWordProvider)
    {
        $this->plugins[$cmdWordProvider->unikey()] = $cmdWordProvider;
    }

    public function removeCmdWordProvider(string $unikey)
    {
        unset($this->plugins[$unikey]);

        return $this;
    }
}
