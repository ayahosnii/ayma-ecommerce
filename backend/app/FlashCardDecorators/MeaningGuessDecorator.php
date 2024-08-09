<?php

namespace App\FlashCardDecorators;

class MeaningGuessDecorator extends DisplayFlashCardDecorator
{
    protected string $meaningGuess;

    /**
     * MeaningGuessDecorator constructor.
     * @param IDisplay $flashcard
     * @param string $meaningGuess
     */
    public function __construct(IDisplay $flashcard, string $meaningGuess)
    {
        parent::__construct($flashcard);
        $this->meaningGuess = $meaningGuess;
    }

    /**
     * Display the flash card with additional meaning guess feature.
     *
     * @return array
     */
    public function display(): array
    {
        // Get the base flash card data
        $data = parent::display();

        // Add the meaning guess feature to the flash card
        $data['meaningGuess'] = $this->meaningGuess;

        return $data;
    }
}
