<?php

declare(strict_types=1);

namespace Theory\FiniteAutomata\Snapshot;

use Theory\FiniteAutomata\Language\Word;
use Theory\FiniteAutomata\State\State;
use Theory\FiniteAutomata\Transition\Transition;

class Snapshot
{
    public function __construct(
        private State $state,
        private Word $remainingWord,
    ) {}

    public function getState(): State
    {
        return $this->state;
    }

    public function getRemainingWord(): Word
    {
        return $this->remainingWord;
    }

    public function isDeductedFrom(Snapshot $from): bool
    {
        if (!$from->getRemainingWord()->hasSuffix($this->getRemainingWord())) {
            return false;
        }

        $firstChar = $from->getRemainingWord()->shift();
        if ($firstChar->isEmpty() && $this->getState() === $from->getState()) {
            return true;
        }

        /** @var Transition $transition */
        foreach ($from->getState()->getOutputTransitions()->filterBySingleWord($firstChar)->getGenerator() as $transition) {
            $mediator = new Snapshot($transition->getNextState(), $from->getRemainingWord());
            if ($this->isDeductedFrom($mediator)) {
                return true;
            }
        }

        return false;
    }
}
