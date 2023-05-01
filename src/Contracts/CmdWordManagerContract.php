<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager\Contracts;

interface CmdWordManagerContract
{
    public static function make(): static;

    public static function plugin($fskey = null): CmdWordProviderContract;

    public function addCmdWordProvider(CmdWordProviderContract $cmdWordProvider);

    public function removeCmdWordProvider(string $fskey);

    public function all();
}
