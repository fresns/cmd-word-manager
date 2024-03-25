<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager;

use Fresns\CmdWordManager\DTO\CmdWordResponseDTO;
use Fresns\CmdWordManager\Exceptions\Constants\ExceptionConstant;
use Fresns\DTO\Exceptions\ResponseException;

class CmdWord
{
    const FORWARD_CALL_TYPE_NEW = 'new';
    const FORWARD_CALL_TYPE_STATIC = 'static';

    protected $handleProvider;

    private string $forwardCallType = CmdWord::FORWARD_CALL_TYPE_NEW;

    public function __construct(protected string $name, protected array $provider, protected string $fskey)
    {
        $this->handleProvider = $this->getHandleProvider();
    }

    /**
     * @param  array  $cmdWord  ['word' => XxxClass::CMD_XXX_YYY, 'provider' => [ZzzClass::class, 'handleCmdXxYyy]];
     * @return static
     */
    public static function make(array $cmdWord, string $fskey): static
    {
        return new static($cmdWord['word'], $cmdWord['provider'], $fskey);
    }

    /**
     * XxxService::CMD_XXX_CMD.
     *
     * @return string
     */
    public function getFskey(): string
    {
        return $this->fskey;
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

        try {
            $response = call_user_func_array($this->handleProvider, $args);

            // Command word no response result
            $response ?? ExceptionConstant::getHandleClassByCode(ExceptionConstant::CMD_WORD_RESP_ERROR)::throw($this->getErrorMessage("The cmd word {$this->getName()} execution failed."));

            if (! is_array($response)) {
                return $response;
            }

            // Verify that the response information meets the specification
            $responseDTO = CmdWordResponseDTO::make($response);
        } catch (ResponseException $e) {
            return CmdWordResponse::make([
                'code' => ExceptionConstant::CMD_WORD_PARAM_ERROR,
                'message' => $this->getErrorMessage(ExceptionConstant::getErrorDescriptionByCode(ExceptionConstant::CMD_WORD_PARAM_ERROR), $e),
                'data' => [
                    $e->getMessage(),
                ],
            ]);
        }

        return CmdWordResponse::make([
            'code' => $responseDTO->getItem('code'),
            'message' => $responseDTO->getItem('message'),
            'data' => $responseDTO->getItem('data', []),
        ]);
    }

    public function getErrorMessage($message, $exception = null)
    {
        $provider = $this->getProvider();

        return sprintf('[%s][%s]: %s, reason: %s', $this->getFskey(), $this->getName(), $message, $exception?->getMessage());
    }
}
