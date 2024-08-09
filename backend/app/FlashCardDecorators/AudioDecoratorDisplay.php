<?php

namespace App\FlashCardDecorators;

class AudioDecoratorDisplay extends DisplayFlashCardDecorator
{
    protected string $audio;

    public function __construct(IDisplay $flashcard, string $audio)
    {
        parent::__construct($flashcard);
        $this->audio = $audio;
    }

    public function display(): array
    {
        // Get the base flashcard data
        $data = parent::display();

        $baseUrl = env('APP_URL');

        // Add audio data
        $data['audio'] = "<audio controls><source src='$baseUrl/storage/{$this->audio}' type='audio/mpeg'></audio>";

        return $data;
    }
}
