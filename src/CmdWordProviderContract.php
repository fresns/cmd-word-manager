<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the GPL-3.0 License.
 */

namespace Fresns\CmdWordManager;

interface CmdWordProviderContract
{
    /**
     * unikey.
     *
     * @return string
     */
    public function unikey(): string;

    /**
     * Get all the command words available to the public.
     *
     * @return array
     */
    public function getAvailableCmdWords(): array;

    /**
     * Call the provided command word.
     *
     * @param  string  $cmdWord
     * @param  array  $args
     */
    public function forwardCmdWordCall(string $cmdWord, array $args);

    /**
     * Forwarding command word calls.
     *
     * @param  string  $cmdWord
     * @param  array  $args
     */
    public function __call(string $cmdWord, array $args);
}
