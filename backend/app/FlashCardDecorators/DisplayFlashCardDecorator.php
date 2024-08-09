<?php

namespace App\FlashCardDecorators;

class DisplayFlashCardDecorator implements IDisplay
{
    protected IDisplay $flashcard;

    public function __construct(IDisplay $flashcard)
    {
        $this->flashcard = $flashcard;
    }

    public function display(): array
    {
        return $this->flashcard->display();
    }
}
