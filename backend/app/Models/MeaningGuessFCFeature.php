<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeaningGuessFCFeature extends Model
{
    protected $table = 'meaning_guess_fc_features';
    protected $fillable = ['hint', 'flash_card_id'];

    /**
     * Get the flash card that owns the meaning guess feature.
     */
    public function flashCard()
    {
        return $this->belongsTo(FlashCard::class);
    }
}
