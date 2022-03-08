<?php

namespace Fresns\CmdWordManager;

trait CmdWordProviderTrait
{
    protected string $unikey;

    /** @var CmdWord[] */
    protected array $cmdWords = [];

    public function __construct()
    {
        $this->registerCmdWord();
    }

    public function registerCmdWord()
    {
        if (property_exists($this, 'unikeyName')) {
            $this->unikey($this->unikeyName);
        }

        if (property_exists($this, 'cmdWordsMap')) {
            $this->cmdWords($this->cmdWordsMap);
        }
    }

    public function unikey(?string $unikey = null): string
    {
        return $unikey
            ? $this->unikey = $unikey
            : $this->unikey;
    }

    /**
     * @return array
     */
    public function cmdWords(array $cmdWords = []): array
    {
        if ($cmdWords) {
            foreach ($cmdWords as $cmdWord) {
                $this->add(CmdWord::make($cmdWord));
            }
        }

        return $this->cmdWords;
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

    public function get(string$cmdWordName): CmdWord
    {
        return $this->cmdWords[$cmdWordName];
    }

    public function forwardCmdWordCall(string $cmdWord, array $args)
    {
        if (!in_array($cmdWord, $this->getAvailableCmdWords())) {
            throw new \LogicException("cmd word: $cmdWord notfound.");
        }

        if (!$this->get($cmdWord)->isCallable()) {
            throw new \LogicException("cmd word: $cmdWord call failure.");
        }

        return $this->get($cmdWord)->handle($args);
    }

    public function __call(string $cmdWord, array $args)
    {
        return $this->forwardCmdWordCall($cmdWord, $args);
    }
}
