<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashCard extends Model
{
    protected $fillable = ['word'];

    public function textFeatures()
    {
        return $this->hasMany(TextFCFeature::class);
    }

    public function audioFeatures()
    {
        return $this->hasMany(AudioFCFeature::class);
    }

    public function imageFeatures()
    {
        return $this->hasMany(ImageFCFeature::class);
    }

    public function meaningGuessFeatures()
    {
        return $this->hasMany(MeaningGuessFCFeature::class);
    }
}
