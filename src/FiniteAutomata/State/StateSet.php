<?php

declare(strict_types=1);

namespace Theory\FiniteAutomata\State;

use Ds\Set;
use Generator;
use OutOfRangeException;

final class StateSet
{
    private Set $set;

    public function __construct(Set $states = null)
    {
        $this->set = $states ?? new Set();
    }

    /**
     * @throws OutOfRangeException
     */
    public function get(int $position): State
    {
        /** @var State $state */
        $state = $this->set->get($position);
        return $state;
    }

    public function add(State $state): self
    {
        $this->set->add($state);
        return $this;
    }

    public function remove(State $state): self
    {
        $this->set->remove($state);
        return $this;
    }

    public function count(): int
    {
        return $this->set->count();
    }

    public function isEmpty(): bool
    {
        return $this->set->isEmpty();
    }

    public function getGenerator(): Generator
    {
        return $this->set->getIterator();
    }
}
