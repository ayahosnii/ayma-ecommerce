<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextFCFeature extends Model
{
    protected $table = 'text_fc_features';
    protected $fillable = ['text', 'flash_card_id'];

    /**
     * Get the flash card that owns the text feature.
     */
    public function flashCard()
    {
        return $this->belongsTo(FlashCard::class);
    }
}
