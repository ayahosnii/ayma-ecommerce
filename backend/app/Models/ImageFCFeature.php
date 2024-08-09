<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageFCFeature extends Model
{
    protected $table = 'image_fc_features';
    protected $fillable = ['image_file', 'flash_card_id'];

    /**
     * Get the flash card that owns the image feature.
     */
    public function flashCard()
    {
        return $this->belongsTo(FlashCard::class);
    }
}

