<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudioFCFeature extends Model
{
    protected $table = 'audio_fc_features';
    protected $fillable = ['audio_file', 'flash_card_id'];

    /**
     * Get the flash card that owns the audio feature.
     */
    public function flashCard()
    {
        return $this->belongsTo(FlashCard::class);
    }
}
