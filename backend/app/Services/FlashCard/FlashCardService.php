<?php

namespace App\Services\FlashCard;

use App\Models\FlashCard;
use App\FlashCardDecorators\AudioDecoratorDisplay;
use App\FlashCardDecorators\BasicFlashCard;
use App\FlashCardDecorators\ImageDecorator;
use App\FlashCardDecorators\MeaningGuessDecorator;
use App\FlashCardDecorators\TextDecorator;

class FlashCardService
{
    public function getRandomFlashCard(array $seenFlashCardIds)
    {
        $allFlashCardIds = FlashCard::pluck('id')->toArray();
        $unseenFlashCardIds = array_diff($allFlashCardIds, $seenFlashCardIds);

        if (empty($unseenFlashCardIds)) {
            return null;
        }

        $randomId = $unseenFlashCardIds[array_rand($unseenFlashCardIds)];

        $flashCard = FlashCard::with(['textFeatures', 'audioFeatures', 'imageFeatures', 'meaningGuessFeatures'])
            ->find($randomId);

        if (!$flashCard) {
            return null;
        }

        $flashCardDisplay = new BasicFlashCard($flashCard);

        foreach ($flashCard->textFeatures as $textFeature) {
            $flashCardDisplay = new TextDecorator($flashCardDisplay, $textFeature->text);
        }

        foreach ($flashCard->audioFeatures as $audioFeature) {
            $flashCardDisplay = new AudioDecoratorDisplay($flashCardDisplay, $audioFeature->audio_file);
        }

        foreach ($flashCard->imageFeatures as $imageFeature) {
            $flashCardDisplay = new ImageDecorator($flashCardDisplay, $imageFeature->image_file);
        }

        foreach ($flashCard->meaningGuessFeatures as $meaningGuessFeature) {
            $flashCardDisplay = new MeaningGuessDecorator($flashCardDisplay, $meaningGuessFeature->hint);
        }

        $data = $flashCardDisplay->display();

        return [
            'id' => $randomId,
            'data' => $data,
            'expected_answer' => $flashCard->word // Directly using the word column
        ];
    }

    public function checkAnswer(string $answer, string $expectedAnswer): bool
    {
        return strtolower($answer) === strtolower($expectedAnswer);
    }
}
