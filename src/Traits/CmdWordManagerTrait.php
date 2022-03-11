<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the GPL-3.0 License.
 */

namespace Fresns\CmdWordManager\Traits;

use Fresns\CmdWordManager\CmdWord;
use Fresns\CmdWordManager\CmdWordResponse;
use Fresns\CmdWordManager\Contracts\CmdWordProviderContract;
use Fresns\CmdWordManager\Exceptions\Constants\ExceptionConstant;
use Fresns\CmdWordManager\Exceptions\FresnsCmdWordException;
use Fresns\CmdWordManager\Exceptions\UnikeyNotfoundException;

/**
 * @mixin
 */
trait CmdWordManagerTrait
{
    protected $defaultUniKey = 'Fresns';

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

    public static function plugin($unikey = null): CmdWordProviderContract | CmdWordResponse
    {
        $instance = static::make();

        try {
            $response = $instance->resolve($unikey);
        } catch (FresnsCmdWordException $e) {
            $response = $e->createCmdWordResponse();
        }

        return $response;
    }

    public function resolveUnikey(string $unikey = null)
    {
        return $unikey ?? $this->defaultUniKey;
    }

    public function resolve($unikey = null): CmdWordProviderContract
    {
        $unikey = $this->resolveUnikey($unikey);

        if (! array_key_exists($unikey, $this->plugins)) {
            ExceptionConstant::getHandleClassByCode(ExceptionConstant::ERROR_CODE_20001)::throw(("The cmd word provider $unikey notfound."));
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

    public function all()
    {
        $plugins = [];

        foreach ($this->plugins as $plugin) {
            $pluginCmdWords = $plugin->all();
            if (empty($pluginCmdWords)) {
                $plugins[$plugin->unikey()] = $pluginCmdWords;
                continue;
            }

            /** @var CmdWord $cmdWord */
            foreach ($pluginCmdWords as $cmdWordName => $cmdWord) {
                $plugins[$plugin->unikey()][$cmdWord->getName()] = [
                    'type' => $cmdWord->getForwardCallType(),
                    'cmd_word' => $cmdWord->getName(),
                    'provider' => $cmdWord->getProvider(),
                ];
            }
        }

        return $plugins;
    }
}
