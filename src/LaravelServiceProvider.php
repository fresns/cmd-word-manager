<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager;

class LaravelServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->singleton(FresnsCmdWord::class, function () {
            return FresnsCmdWord::make();
        });

        $this->app->alias(FresnsCmdWord::class, 'fresns.cmd-word-manager');

        $this->commands([
            Commands\MakeCmdWordProviderCommand::class,
        ]);
    }

    public function boot()
    {
        //
    }

    public function providers()
    {
        return [FresnsCmdWord::class, 'fresns.cmd-word-manager'];
    }
}
