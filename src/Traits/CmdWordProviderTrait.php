<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Fresns\CmdWordManager\Traits;

use Fresns\CmdWordManager\CmdWord;
use Fresns\CmdWordManager\Exceptions\Constants\ExceptionConstant;
use Fresns\CmdWordManager\Exceptions\FresnsCmdWordException;
use Fresns\CmdWordManager\FresnsCmdWord;

trait CmdWordProviderTrait
{
    protected string $fskey = '';

    /** @var CmdWord[] */
    protected array $cmdWords = [];

    public function __construct()
    {
        $this->registerCmdWord();
    }

    public function registerCmdWord()
    {
        if (property_exists($this, 'fsKeyName')) {
            $this->fskey($this->fsKeyName);
        }

        if (property_exists($this, 'cmdWordsMap')) {
            $this->cmdWords($this->cmdWordsMap);
        }
    }

    public function fskey(?string $fskey = null): string
    {
        return $fskey ? $this->fskey = $fskey : $this->fskey;
    }

    /**
     * @return array
     */
    public function cmdWords(array $cmdWords = []): array
    {
        if ($cmdWords) {
            foreach ($cmdWords as $cmdWord) {
                if (! ($cmdWord instanceof CmdWord)) {
                    $cmdWord = CmdWord::make($cmdWord, $this->fskey());
                }

                $this->add($cmdWord);
            }
        }

        return $this->all();
    }

    public function getAvailableCmdWords(): array
    {
        return array_keys($this->cmdWords());
    }

    public function all(): array
    {
        return  $this->cmdWords;
    }

    public function add(CmdWord $cmdWord): static
    {
        $this->cmdWords[$cmdWord->getName()] = $cmdWord;

        return  $this;
    }

    public function remove(string $cmdWordName): static
    {
        unset($this->cmdWords[$cmdWordName]);

        return $this;
    }

    public function get(string $cmdWordName): CmdWord
    {
        return $this->cmdWords[$cmdWordName];
    }

    public function registerCmdWordProvider()
    {
        FresnsCmdWord::make()->addCmdWordProvider($this);
    }

    public function forwardCmdWordCall(string $cmdWord, array $args)
    {
        if (! in_array($cmdWord, $this->getAvailableCmdWords())) {
            ExceptionConstant::getHandleClassByCode(ExceptionConstant::WORD_DOES_NOT_EXIST)::throw(sprintf("The cmd word $cmdWord not found in plugin %s.", $this->fskey()));
        }

        if (! $this->get($cmdWord)->isCallable()) {
            ExceptionConstant::getHandleClassByCode(ExceptionConstant::CMD_WORD_REQUEST_ERROR)::throw(sprintf("The cmd word $cmdWord execution failed in plugin %s.", $this->fskey()));
        }

        return $this->get($cmdWord)->handle($args);
    }

    public function __call(string $cmdWord, array $args)
    {
        try {
            $response = $this->forwardCmdWordCall($cmdWord, $args);
            // Verify that the response information meets the documentation standards
        } catch (FresnsCmdWordException $e) {
            $response = $e->createCmdWordResponse($this->fskey(), $cmdWord);
        }

        return $response;
    }
}
