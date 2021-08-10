<?php

use Theory\FiniteAutomata\Language\Alphabet;
use Theory\FiniteAutomata\Language\Word;
use Theory\FiniteAutomata\FiniteAutomata;
use Theory\FiniteAutomata\State\State;

require dirname(__DIR__) . '/vendor/autoload.php';

d("Hello world");

$alphabet = new Alphabet(['a', 'b', 'c']);

$q0 = State::new()->makeInitial();
$q1 = State::new();
$q2 = State::new()->makeTerminal();
$q3 = State::new()->makeTerminal();

$fa = (new FiniteAutomata($alphabet))
    ->addState($q0)
    ->addState($q1)
    ->addState($q2)
    ->addState($q3)
    ->addTransition($q0, $q0, Word::new('a'))
    ->addTransition($q0, $q1, Word::new('b'))
    ->addTransition($q0, $q2, Word::new('b'))
    ->addTransition($q2, $q2, Word::new('b'))
    ->addTransition($q1, $q3, Word::empty())
    ->addTransition($q2, $q3, Word::new('a'));

d($fa->render());
d($fa->minimizeTerminalStates()->render());

//d($fa->acceptWord('aaabbba'));
