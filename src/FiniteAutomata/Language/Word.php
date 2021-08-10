<?php

declare(strict_types=1);

namespace Theory\FiniteAutomata\Language;

use OutOfRangeException;

class Word
{
    private const EMPTY_WORD = '';
    private const DEFAULT_ENCODING = 'UTF-8';

    public function __construct(
        private string $word
    ) {}

    public static function new(string $word): self
    {
        return new self($word);
    }

    public static function empty(): self
    {
        return new self(self::EMPTY_WORD);
    }

    public function getString(): string
    {
        return $this->word;
    }

    public function getLength(): int
    {
        return mb_strlen($this->word);
    }

    public function isEmpty(): bool
    {
        return $this->word === self::EMPTY_WORD;
    }

    public function isSingle(): bool
    {
        return $this->getLength() <= 1;
    }

    public function hasSuffix(Word $word): bool
    {
        return str_ends_with($this->getString(), $word->getString());
    }

    public function diff(Word $word): Word
    {
        if (!$this->hasSuffix($word)) {
            throw new OutOfRangeException();
        }

        /** @var string $prefix */
        $prefix = str_replace($word->getString(), '', $this->getString());
        return new Word($prefix);
    }

    public function isEqual(Word $word): bool
    {
        return $this->getString() === $word->getString();
    }

    public function shift(): Word
    {
        if ($this->word === self::EMPTY_WORD) {
            return $this;
        }

        /* Get first char */
        $char = mb_substr($this->word, 0, 1, self::DEFAULT_ENCODING);
        /* Cut first char from current word */
        $this->word = mb_substr($this->word, 1, null, self::DEFAULT_ENCODING);

        return new Word($char);
    }
}
