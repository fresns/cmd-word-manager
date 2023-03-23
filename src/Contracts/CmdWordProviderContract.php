<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager\Contracts;

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
     * Register CmdWord list and get current CmdWord list.
     *
     * @param  array  $cmdWords
     * @return array
     */
    public function cmdWords(array $cmdWords = []): array;

    /**
     * Get current CmdWord list.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Register Plugin CmdWord Provider.
     *
     * @return void
     */
    public function registerCmdWordProvider();

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
