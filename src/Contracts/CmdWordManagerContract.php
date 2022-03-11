<?php

namespace Fresns\CmdWordManager\Contracts;

interface CmdWordManagerContract
{
    public static function make(): static;

    public static function plugin($unikey = null): CmdWordProviderContract;

    public function addCmdWordProvider(CmdWordProviderContract $cmdWordProvider);

    public function removeCmdWordProvider(string $unikey);

    public function all();
}