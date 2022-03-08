<?php

namespace Fresns\CmdWordManager;


trait CmdWordManagerTrait
{
    /** @var CmdWordProviderContract[] $plugins  */
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
        if (!array_key_exists($unikey, $this->plugins)) {
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
