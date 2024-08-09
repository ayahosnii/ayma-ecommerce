<?php

namespace App\FlashCardDecorators;

use App\Models\FlashCard;

class BasicFlashCard implements IDisplay
{
    protected FlashCard $flashCard;

    public function __construct($flashCard)
    {
        $this->flashCard = $flashCard;
    }

    /**
     * Display the basic flash card information.
     *
     * @return array
     */
    public function display(): array
    {
        // Basic flash card data
        return [
            'id' => $this->flashCard->id,
            'description' => 'Basic Flash Card',
        ];
    }
}
