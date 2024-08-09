<?php

namespace App\FlashCardDecorators;

class TextDecorator extends DisplayFlashCardDecorator
{
    protected string $text;

    /**
     * TextDecorator constructor.
     * @param IDisplay $flashcard
     * @param string $text
     */
    public function __construct(IDisplay $flashcard, string $text)
    {
        parent::__construct($flashcard);
        $this->text = $text;
    }

    /**
     * Display the flash card with additional text.
     *
     * @return array
     */
    public function display(): array
    {
        $data = parent::display();

        $data['text'] = $this->text;

        return $data;
    }
}
