<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager;

use Fresns\CmdWordManager\DTO\CmdWordResponseDTO;
use Fresns\CmdWordManager\Exceptions\Constants\ExceptionConstant;
use Fresns\DTO\Exceptions\DTOException;

class CmdWord
{
    const FORWARD_CALL_TYPE_NEW = 'new';

    const FORWARD_CALL_TYPE_STATIC = 'static';

    private string $forwardCallType = CmdWord::FORWARD_CALL_TYPE_NEW;

    public function __construct(
        protected string $name,
        protected array $provider
    ) {
        $this->handleProvider = $this->getHandleProvider();
    }

    /**
     * @param  array  $cmdWord  ['word' => XxxClass::CMD_XXX_YYY, 'provider' => [ZzzClass::class, 'handleCmdXxYyy]];
     * @return static
     */
    public static function make(array $cmdWord): static
    {
        return new static($cmdWord['word'], $cmdWord['provider']);
    }

    /**
     * XxxService::CMD_XXX_CMD.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * [XxxService::class, 'xxxCmd'].
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

        [$className, $methodName] = $handleProvider;


        if (interface_exists(\Illuminate\Foundation\Application::class)) {
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

    public function getForwardCallType()
    {
        return $this->forwardCallType;
    }

    public function isCallable(): bool
    {
        return is_callable($this->handleProvider);
    }

    public function handle($args = null)
    {
        $args = (array) ($args ?? []);

        $response = call_user_func_array($this->handleProvider, $args);

        // Command word no response result
        $response ?? ExceptionConstant::getHandleClassByCode(ExceptionConstant::ERROR_CODE_20003)::throw((sprintf("The cmd word {$this->getName()} execution failed in plugin %s.", $this->getName())));

        if (! is_array($response)) {
            return $response;
        }

        try {
            // Verify that the response information meets the specification
            $responseDTO = CmdWordResponseDTO::make($response);
        } catch (DTOException $e) {
            ExceptionConstant::getHandleClassByCode(ExceptionConstant::ERROR_CODE_20008)::throw((sprintf("The cmd word {$this->getName()} response data error in plugin %s.", $this->getName())));
        }

        return CmdWordResponse::make([
            'code' => $responseDTO->getItem('code'),
            'message' => $responseDTO->getItem('message'),
            'data' => $responseDTO->getItem('data', []),
        ]);
    }
}
