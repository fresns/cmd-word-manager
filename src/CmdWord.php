<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the GPL-3.0 License.
 */

namespace Fresns\CmdWordManager;

class CmdWord
{
    const FORWARD_CALL_TYPE_NEW = 'new';

    const FORWARD_CALL_TYPE_STATIC = 'static';

    private string $forwardCallType = CmdWord::FORWARD_CALL_TYPE_NEW;

    public function __construct(
        protected string $name,
        protected array $provider
    ) {
    }

    /**
     *
     * @param array $cmdWord ['name' => XxxClass::CMD_XXX_YYY, 'provider' => [ZzzClass::class, 'handleCmdXxYyy]];
     * @return static
     */
    public static function make(array $cmdWord): static
    {
        return new static($cmdWord['name'], $cmdWord['provider']);
    }

    /**
     * XxxService::CMD_XXX_CMD
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * [XxxService::class, 'xxxCmd']
     *
     * @return array
     */
    public function getProvider(): array
    {
        return $this->provider;
    }

    public function getHandleProvider()
    {
        $handleProvider = [$this->getHandleClassName(), $this->getHandleMethod()];

        if (is_callable($handleProvider)) {
            $this->forwardCallType = self::FORWARD_CALL_TYPE_STATIC;

            return $handleProvider;
        }

        $this->forwardCallType = self::FORWARD_CALL_TYPE_NEW;

        [$className, $methodName] =  $handleProvider;

        if (class_exists(\Illuminate\Contracts\Foundation\Application::class)) {
            $handleProvider = [app($className), $methodName];
        } else {
            $handleProvider = [new $className, $methodName];
        }


        return $handleProvider;
    }

    /**
     * @return string
     */
    public function getHandleClassName(): string
    {
        [$className, $methodName] = $this->getProvider();

        return $className;
    }

    /**
     * @return string
     */
    public function getHandleMethod(): string
    {
        [$className, $methodName] = $this->getProvider();

        return Str::camel($methodName);
    }

    public function getHandleClass(): object
    {
        [$class, $methodName] = $this->getHandleProvider();

        return $class;
    }

    public function isCallable(): bool
    {
        return is_callable($this->getHandleProvider());
    }

    public function handle($args = null)
    {
        $args = (array) ($args ?? []);

        $response = call_user_func_array($this->getHandleProvider(), $args);

        if (!is_array($response)) {
            return $response;
        }

        return CmdWordResponse::make($response);
    }
}
