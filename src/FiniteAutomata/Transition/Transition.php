<?php

declare(strict_types=1);

namespace Theory\FiniteAutomata\Transition;

use Theory\FiniteAutomata\Language\Word;
use Theory\FiniteAutomata\State\State;

class Transition
{
    public function __construct(
        private State $previous,
        private State $next,
        private Word $word,
    ) {}

    public function getWord(): Word
    {
        return $this->word;
    }

    public function setWord(Word $word): self
    {
        $this->word = $word;
        return $this;
    }

    public function getPreviousState(): State
    {
        return $this->previous;
    }

    public function getNextState(): State
    {
        return $this->next;
    }

    public function isLoop(): bool
    {
        return $this->previous === $this->next;
    }
}
