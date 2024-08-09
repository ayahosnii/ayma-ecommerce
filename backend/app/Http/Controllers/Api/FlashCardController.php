<?php

namespace App\Http\Controllers\Api;

use App\Services\FlashCard\FlashCardService;
use App\Services\FlashCard\SessionService;
use App\Services\FlashCard\CacheService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class FlashCardController extends Controller
{
    protected $flashCardService;
    protected $sessionService;
    protected $cacheService;

    public function __construct(FlashCardService $flashCardService, SessionService $sessionService, CacheService $cacheService)
    {
        $this->flashCardService = $flashCardService;
        $this->sessionService = $sessionService;
        $this->cacheService = $cacheService;
    }

    /**
     * Show a random flash card with all available features.
     *
     * @return JsonResponse
     */
    public function showRandom(): JsonResponse
    {
        $seenFlashCardIds = $this->sessionService->getSeenFlashCardIds();

        $flashCardData = $this->flashCardService->getRandomFlashCard($seenFlashCardIds);

        if (is_null($flashCardData)) {
            return response()->json(['message' => 'You have seen all flash cards.'], 200);
        }

        $this->sessionService->addSeenFlashCardId($flashCardData['id']);
        $this->cacheService->storeFlashCardDetails($flashCardData['id'], $flashCardData['expected_answer']);

        return response()->json(['flash_card_id' => $flashCardData['id'], 'data' => $flashCardData['data']]);
    }

    /**
     * Answer a flash card and get another random flash card.
     *
     * @param Request $request
     * @param int $flashCardId
     * @return JsonResponse
     */
    public function answerFlashCard(Request $request, int $flashCardId): JsonResponse
    {
        $validated = $request->validate([
            'answer' => 'required|string',
        ]);

        $expectedAnswer = $this->cacheService->getExpectedAnswer();

        $correct = $this->flashCardService->checkAnswer($validated['answer'], $expectedAnswer);
        $this->sessionService->updateAnswerCounts($correct);

        if ($this->sessionService->hasMaxIncorrectAnswers()) {
            return response()->json([
                'message' => 'Game Over! You have made too many incorrect guesses.',
                'correct' => $correct,
                'correct_answers' => $this->sessionService->getCorrectAnswers(),
                'incorrect_answers' => $this->sessionService->getIncorrectAnswers()
            ], 200);
        }

        $nextFlashCardData = $this->showRandom()->getData(true);

        return response()->json([
            'message' => 'Flash card answered.',
            'correct' => $correct,
            'correct_answers' => $this->sessionService->getCorrectAnswers(),
            'incorrect_answers' => $this->sessionService->getIncorrectAnswers(),
            'next_flash_card' => $nextFlashCardData
        ]);
    }
}
