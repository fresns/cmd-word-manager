<?php

namespace Fresns\CmdWordManager;

interface CmdWordProviderContract
{
    /**
     * unikey
     *
     * @return string
     */
    public function unikey(): string;

    /**
     * Get all the command words available to the public
     *
     * @return array
     */
    public function getAvailableCmdWords(): array;

    /**
     * Call the provided command word
     *
     * @param string $cmdWord
     * @param array $args
     */
    public function forwardCmdWordCall(string $cmdWord, array $args);

    /**
     * Forwarding command word calls
     *
     * @param string $cmdWord
     * @param array $args
     */
    public function __call(string $cmdWord, array $args);
}
