<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager\Traits;

use Fresns\CmdWordManager\CmdWord;
use Fresns\CmdWordManager\CmdWordResponse;
use Fresns\CmdWordManager\Contracts\CmdWordProviderContract;
use Fresns\CmdWordManager\Exceptions\Constants\ExceptionConstant;
use Fresns\CmdWordManager\Exceptions\FresnsCmdWordException;

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

    public static function plugin($unikey = null): CmdWordProviderContract|CmdWordResponse
    {
        $instance = static::make();

        try {
            $response = $instance->resolve($unikey);
        } catch (FresnsCmdWordException $e) {
            $response = $e->createCmdWordResponse($instance->resolveUnikey($unikey), null);
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
            ExceptionConstant::getHandleClassByCode(ExceptionConstant::PLUGIN_DOES_NOT_EXIST)::throw("[$unikey] The cmd word provider $unikey not found.");
        }

        return $this->plugins[$unikey];
    }

    public function addCmdWordProvider(CmdWordProviderContract $cmdWordProvider)
    {
        if (empty($this->plugins[$cmdWordProvider->unikey()])) {
            $this->plugins[$cmdWordProvider->unikey()] = $cmdWordProvider;

            return;
        }

        $cmdWordMaps = $this->plugins[$cmdWordProvider->unikey()]->cmdWords();

        $mergedCmdWordMaps = array_merge($cmdWordMaps, $cmdWordProvider->cmdWords());

        $this->plugins[$cmdWordProvider->unikey()]->cmdWords($mergedCmdWordMaps);
    }

    public function removeCmdWordProvider(string $unikey)
    {
        unset($this->plugins[$unikey]);

        return $this;
    }

    public function all()
    {
        $plugins = [];

        foreach ($this->plugins as $unikey => $plugin) {
            $pluginCmdWords = $plugin->all();
            if (empty($pluginCmdWords)) {
                $plugins[$plugin->unikey()] = $pluginCmdWords;
                continue;
            }

            /** @var CmdWord $cmdWord */
            foreach ($pluginCmdWords as $cmdWordName => $cmdWord) {
                $plugins[$plugin->unikey()][$cmdWord->getName()] = [
                    'unikey' => $cmdWord->getUnikey(),
                    'type' => $cmdWord->getForwardCallType(),
                    'cmd_word' => $cmdWord->getName(),
                    'provider' => $cmdWord->getProvider(),
                ];
            }
        }

        return $plugins;
    }
}
