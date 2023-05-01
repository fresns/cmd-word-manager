<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
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
    protected $defaultFskey = 'Fresns';

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

    public static function plugin($fskey = null): CmdWordProviderContract|CmdWordResponse
    {
        $instance = static::make();

        try {
            $response = $instance->resolve($fskey);
        } catch (FresnsCmdWordException $e) {
            $response = $e->createCmdWordResponse($instance->resolveFskey($fskey), null);
        }

        return $response;
    }

    public function resolveFskey(string $fskey = null)
    {
        return $fskey ?? $this->defaultFskey;
    }

    public function resolve($fskey = null): CmdWordProviderContract
    {
        $fskey = $this->resolveFskey($fskey);

        if (! array_key_exists($fskey, $this->plugins)) {
            ExceptionConstant::getHandleClassByCode(ExceptionConstant::PLUGIN_DOES_NOT_EXIST)::throw("[$fskey] The cmd word provider $fskey not found.");
        }

        return $this->plugins[$fskey];
    }

    public function addCmdWordProvider(CmdWordProviderContract $cmdWordProvider)
    {
        if (empty($this->plugins[$cmdWordProvider->fskey()])) {
            $this->plugins[$cmdWordProvider->fskey()] = $cmdWordProvider;

            return;
        }

        $cmdWordMaps = $this->plugins[$cmdWordProvider->fskey()]->cmdWords();

        $mergedCmdWordMaps = array_merge($cmdWordMaps, $cmdWordProvider->cmdWords());

        $this->plugins[$cmdWordProvider->fskey()]->cmdWords($mergedCmdWordMaps);
    }

    public function removeCmdWordProvider(string $fskey)
    {
        unset($this->plugins[$fskey]);

        return $this;
    }

    public function all()
    {
        $plugins = [];

        foreach ($this->plugins as $fskey => $plugin) {
            $pluginCmdWords = $plugin->all();
            if (empty($pluginCmdWords)) {
                $plugins[$plugin->fskey()] = $pluginCmdWords;
                continue;
            }

            /** @var CmdWord $cmdWord */
            foreach ($pluginCmdWords as $cmdWordName => $cmdWord) {
                $plugins[$plugin->fskey()][$cmdWord->getName()] = [
                    'fskey' => $cmdWord->getFskey(),
                    'type' => $cmdWord->getForwardCallType(),
                    'cmd_word' => $cmdWord->getName(),
                    'provider' => $cmdWord->getProvider(),
                ];
            }
        }

        return $plugins;
    }
}
