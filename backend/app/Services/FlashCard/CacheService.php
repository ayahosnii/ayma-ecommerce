<?php

namespace App\Services\FlashCard;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    public function storeFlashCardDetails(int $id, string $expectedAnswer): void
    {
        Cache::put('current_flash_card_id', $id);
        Cache::put('expected_answer', $expectedAnswer);
    }

    public function getExpectedAnswer(): ?string
    {
        return Cache::get('expected_answer');
    }
}
