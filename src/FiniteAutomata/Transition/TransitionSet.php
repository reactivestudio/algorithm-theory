<?php

declare(strict_types=1);

namespace Theory\FiniteAutomata\Transition;

use Ds\Set;
use Generator;
use Theory\FiniteAutomata\Language\Word;

final class TransitionSet
{
    private Set $set;

    public function __construct(Set $set = null)
    {
        $this->set = $set ?? new Set();
    }

    public function add(Transition $transition): self
    {
        $this->set->add($transition);
        return $this;
    }

    public function remove(Transition $transition): self
    {
        $this->set->remove($transition);
        return $this;
    }

    public function getGenerator(): Generator
    {
        return $this->set->getIterator();
    }

    public function isEmpty(): bool
    {
        return $this->set->isEmpty();
    }

    public function filterBySingleWord(Word $word): self
    {
        $set = $this->set
            ->filter(fn (Transition $t) => $t->getWord()->isEqual($word) || $t->getWord()->isEmpty());

        return new self($set);
    }

    public function containsOnlyLoops(): bool
    {
        return $this->set
            ->filter(fn (Transition $transition) => !$transition->isLoop())
            ->isEmpty();
    }
}
