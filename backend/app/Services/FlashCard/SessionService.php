<?php

namespace App\Services\FlashCard;

use Illuminate\Support\Facades\Session;

class SessionService
{
    public function getSeenFlashCardIds(): array
    {
        return Session::get('seen_flash_card_ids', []);
    }

    public function addSeenFlashCardId(int $id): void
    {
        $seenFlashCardIds = $this->getSeenFlashCardIds();
        $seenFlashCardIds[] = $id;
        Session::put('seen_flash_card_ids', $seenFlashCardIds);
    }

    public function updateAnswerCounts(bool $correct): void
    {
        if ($correct) {
            $correctAnswers = Session::get('correct_answers', 0) + 1;
            Session::put('correct_answers', $correctAnswers);
        } else {
            $incorrectAnswers = Session::get('incorrect_answers', 0) + 1;
            Session::put('incorrect_answers', $incorrectAnswers);
        }
    }

    public function getCorrectAnswers(): int
    {
        return Session::get('correct_answers', 0);
    }

    public function getIncorrectAnswers(): int
    {
        return Session::get('incorrect_answers', 0);
    }

    public function hasMaxIncorrectAnswers(): bool
    {
        return $this->getIncorrectAnswers() >= 5;
    }
}
