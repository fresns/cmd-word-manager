<?php

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

        return $methodName;
    }

    public function getHandleClass(): object
    {
        [$className, $methodName] = $this->getProvider();

        if (!class_exists($this->getHandleClassName())) {
            throw new \RuntimeException("cmd word handle: $className::$methodName notfound.");
        }

        return new $className();
    }

    public function isCallable(): bool
    {
        if (is_callable($this->getProvider())) {
            $this->forwardCallType = CmdWord::FORWARD_CALL_TYPE_STATIC;
            return true;
        }

        return is_callable([$this->getHandleClass(), $this->getHandleMethod()]);
    }

    public function handle($args = null)
    {
        $args = (array) ($args ?? []);

        $handle = $this->getProvider();
        if ($this->forwardCallType === CmdWord::FORWARD_CALL_TYPE_NEW) {
            $handle = [$this->getHandleClass(), $this->getHandleMethod()];
        }

        return call_user_func_array($handle, $args);
    }
}