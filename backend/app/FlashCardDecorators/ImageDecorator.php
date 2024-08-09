<?php

namespace App\FlashCardDecorators;

class ImageDecorator extends DisplayFlashCardDecorator
{
    protected string $image;

    /**
     * ImageDecorator constructor.
     * @param IDisplay $flashcard
     * @param string $image
     */
    public function __construct(IDisplay $flashcard, string $image)
    {
        parent::__construct($flashcard);
        $this->image = $image;
    }

    /**
     * Display the flash card with additional image.
     *
     * @return array
     */
    public function display(): array
    {
        // Get the base flash card data
        $data = parent::display();

        $baseUrl = env('APP_URL');

        // Add the image feature to the flash card
        $data['image'] = "<img src='$baseUrl/storage/{$this->image}' alt='Flash Card Image' />";

        return $data;
    }
}
