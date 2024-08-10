<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('name'); // Category name
            $table->string('slug')->unique(); // SEO-friendly URL slug
            $table->text('description')->nullable(); // Description of the category
            $table->unsignedBigInteger('parent_id')->nullable(); // For nested categories (self-referencing foreign key)
            $table->string('image')->nullable(); // Path to category image
            $table->unsignedInteger('position')->default(0); // For ordering categories
            $table->boolean('is_active')->default(true); // Status of the category
            $table->timestamps(); // Created at and updated at timestamps
            $table->softDeletes(); // Soft delete column
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade'); // Foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
