<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeaningGuessFcFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meaning_guess_fc_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flash_card_id')->constrained('flash_cards')->onDelete('cascade');
            $table->string('hint');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meaning_guess_fc_features');
    }
}
