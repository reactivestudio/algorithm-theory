<?php

declare(strict_types=1);

namespace Theory\FiniteAutomata\State;

use Theory\FiniteAutomata\Transition\TransitionSet;

class State
{
    private TransitionSet $input;

    private TransitionSet $output;

    private function __construct(
        private bool $initial = false,
        private bool $terminal = false,
    ) {
        $this->input = new TransitionSet();
        $this->output = new TransitionSet();
    }

    public static function new(): self
    {
        return new self();
    }

    public function getInputTransitions(): TransitionSet
    {
        return $this->input;
    }

    public function getOutputTransitions(): TransitionSet
    {
        return $this->output;
    }

    public function isInitial(): bool
    {
        return $this->initial;
    }

    public function makeInitial(): self
    {
        $this->initial = true;
        return $this;
    }

    public function makeNotInitial(): self
    {
        $this->initial = false;
        return $this;
    }

    public function isTerminal(): bool
    {
        return $this->terminal;
    }

    public function makeTerminal(): self
    {
        $this->terminal = true;
        return $this;
    }

    public function makeNotTerminal(): self
    {
        $this->terminal = false;
        return $this;
    }

    public function isDeadLock(): bool
    {
        return $this->output->isEmpty() || $this->output->containsOnlyLoops();
    }

    public function isIsolated(): bool
    {
        return ($this->isInitial() && $this->output->isEmpty())
            || (!$this->isInitial() && $this->input->isEmpty());
    }
}
