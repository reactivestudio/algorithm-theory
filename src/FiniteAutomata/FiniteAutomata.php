<?php

declare(strict_types=1);

namespace Theory\FiniteAutomata;

use OutOfBoundsException;
use OutOfRangeException;
use Theory\FiniteAutomata\Language\Alphabet;
use Theory\FiniteAutomata\Language\Word;
use Theory\FiniteAutomata\Snapshot\Snapshot;
use Theory\FiniteAutomata\State\State;
use Theory\FiniteAutomata\State\StateSet;
use Theory\FiniteAutomata\Transition\Transition;
use Theory\FiniteAutomata\Transition\TransitionSet;

final class FiniteAutomata
{
    private StateSet $states;

    private Alphabet $alphabet;

    private TransitionSet $transitions;

    private ?State $initialState = null;

    private StateSet $terminalStates;

    public function __construct(Alphabet $alphabet)
    {
        $this->alphabet = $alphabet;
        $this->states = new StateSet();
        $this->transitions = new TransitionSet();
        $this->terminalStates = new StateSet();
    }

    public function getAlphabet(): Alphabet
    {
        return $this->alphabet;
    }

    public function addState(State $state): self
    {
        $this->states->add($state);

        if ($state->isInitial()) {
            if ($this->initialState !== null) {
                throw new OutOfRangeException();
            }

            $this->initialState = $state;
        }

        if ($state->isTerminal()) {
            $this->terminalStates->add($state);
        }

        return $this;
    }

    public function removeState(State $state): self
    {
        if (!$state->isIsolated()) {
            throw new OutOfBoundsException();
        }

        $this->states->remove($state);
        return $this;
    }

    public function addTransition(State $previous, State $next, Word $word): self
    {
        if (!$this->alphabet->allowWord($word)) {
            throw new OutOfBoundsException();
        }

        $transition = new Transition($previous, $next, $word);

        $this->transitions->add($transition);
        $previous->getOutputTransitions()->add($transition);
        $next->getInputTransitions()->add($transition);

        return $this;
    }

    public function removeTransition(Transition $transition): self
    {
        $transition
            ->getNextState()
            ->getInputTransitions()
            ->remove($transition);

        $transition
            ->getPreviousState()
            ->getOutputTransitions()
            ->remove($transition);

        $this->transitions->remove($transition);

        return $this;
    }

    public function isValid(): bool
    {
        return !$this->states->isEmpty()
            && $this->initialState !== null
            && !$this->terminalStates->isEmpty();
    }

    public function render(): string
    {
        return $this->renderState($this->initialState);
    }

    public function renderState(State $state, string $shift = " "): string
    {
        $output = $state->isInitial() ? '-' : '';
        $output .= $state->isTerminal() ? '◎' : '◯';

        $isFirst = true;
        /** @var Transition $transition */
        foreach ($state->getOutputTransitions()->getGenerator() as $transition) {
            $output .= $isFirst ? "" : "\n{$shift}|";
            $isFirst = false;
            $char = $transition->getWord()->isEmpty() ? 'e' : $transition->getWord()->getString();
            if ($transition->isLoop()) {
                $output .= "loop {$char}";
            } else {
                $output .= " -{$char}->" . $this->renderState($transition->getNextState(), "$shift    ");
            }
        }

        return $output;
    }

    public function acceptWord(string $word): bool
    {
        $initialSnapshot = new Snapshot($this->initialState, Word::new($word));
        /** @var State $terminalState */
        foreach ($this->terminalStates->getGenerator() as $terminalState) {
            $terminalSnapshot = new Snapshot($terminalState, Word::empty());
            if ($terminalSnapshot->isDeductedFrom($initialSnapshot)) {
                return true;
            }
        }

        return false;
    }

    public function minimizeTerminalStates(): self
    {
        if ($this->terminalStates->count() <= 1) {
            return $this;
        }

        $newTerminalState = State::new()->makeTerminal();
        $this->addState($newTerminalState);

        /** @var State $state */
        foreach ($this->terminalStates->getGenerator() as $state) {
            if ($state === $newTerminalState) {
                continue;
            }

            $state->makeNotTerminal();
            $this->addTransition($state, $newTerminalState, Word::empty());
        }

        return $this;
    }
}
