<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the GPL-3.0 License.
 */

namespace Fresns\CmdWordManager\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeCmdWordProviderCommand extends GeneratorCommand
{
    protected $signature = 'make:cmd-word-provider {unikey : cmd-word unikey}
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
        return str_replace(['dummy_kebab_name', '{{ kebab_name }}', '{{kebab_name}}'], $this->argument('unikey'), $stub);
    }

    public function qualifyClassNamespace()
    {
        $path = trim($this->option('path'), '/');
        $path = str_replace('/', '\\', $path);
        $path = Str::studly($path);

        if (! $path) {
            return app_path('Providers');
        }

        $path = $path.'\\'.Str::studly($this->argument('unikey')).'\\';

        return $path;
    }
}
