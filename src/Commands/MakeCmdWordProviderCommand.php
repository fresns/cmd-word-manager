<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeCmdWordProviderCommand extends GeneratorCommand
{
    protected $signature = 'make:cmdword-provider {fskey : cmd-word fskey}
        {name=CmdWordServiceProvider : CmdWordProvider Name}
        {--path= : The location where the CmdWordProvider file should be created}';

    protected $description = 'Create a new CmdWordServiceProvider';

    protected $type = 'CmdWordServiceProvider';

    public function getNameInput()
    {
        $name = parent::getNameInput();

        $name = str_contains($name, 'ServiceProvider') ? $name : $name.'ServiceProvider';

        return Str::studly($name);
    }

    protected function rootNamespace()
    {
        if ($this->option('path')) {
            return $this->qualifyClassNamespace();
        }

        return $this->laravel->getNamespace();
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Providers';
    }

    public function getStub()
    {
        $relativePath = '/stubs/cmd-word-provider.stub';

        return file_exists($customPath = $this->laravel->basePath(trim($relativePath, '/')))
            ? $customPath
            : __DIR__.$relativePath;
    }

    protected function getPath($name)
    {
        if ($this->option('path')) {
            return $this->laravel['path.base'].'/'.str_replace('\\', '/', lcfirst($name)).'.php';
        }

        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }

    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        return $this->replaceContent($stub);
    }

    protected function replaceContent(string $stub): string
    {
        return str_replace(['dummy_studly_name', '{{ studly_name }}', '{{studly_name}}'], $this->argument('fskey'), $stub);
    }

    public function qualifyClassNamespace()
    {
        $path = trim($this->option('path'), '/');
        $path = str_replace('/', '\\', $path);
        $path = Str::studly($path);

        if (! $path) {
            return app_path('Providers');
        }

        $path = $path.'\\'.Str::studly($this->argument('fskey')).'\\';

        return $path;
    }
}
