<?php

declare(strict_types=1);

namespace Theory\FiniteAutomata\Language;

use Ds\Set;

class Alphabet
{
    private Set $alphabet;

    public function __construct(array $chars)
    {
        $this->alphabet = new Set($chars);
    }

    public function allowWord(Word $word): bool
    {
        if ($word->isEmpty()) {
            return true;
        }

        $chars = mb_str_split($word->getString());
        foreach ($chars as $char) {
            if (!$this->alphabet->contains($char)) {
                return false;
            }
        }

        return true;
    }
}
